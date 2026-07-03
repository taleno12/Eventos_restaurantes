<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Services\FcmNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestauranteEventoController extends Controller
{
    private FcmNotificationService $fcm;

    public function __construct(FcmNotificationService $fcm)
    {
        $this->fcm = $fcm;
    }

    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    /**
     * Cuenta cuántos eventos visibles al público tiene el restaurante
     * dentro del mes calendario actual (día 1 al último día del mes).
     */
    private function visiblesEsteMes($restauranteId, $excluirEventoId = null)
    {
        $inicioMes = now()->startOfMonth();
        $finMes    = now()->endOfMonth();

        $query = Evento::where('restaurante_id', $restauranteId)
            ->where('visible_publico', true)
            ->whereBetween('created_at', [$inicioMes, $finMes]);

        if ($excluirEventoId) {
            $query->where('id', '!=', $excluirEventoId);
        }

        return $query->count();
    }

    public function index()
    {
        $restaurante = $this->restaurante();
        $eventos = Evento::where('restaurante_id', $restaurante->id)
            ->latest()->paginate(10);

        $visiblesEsteMes = $this->visiblesEsteMes($restaurante->id);

        return view('restaurante.eventos.index', compact('restaurante', 'eventos', 'visiblesEsteMes'));
    }

    public function create()
    {
        $restaurante   = $this->restaurante();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('restaurante.eventos.create', compact('restaurante', 'departamentos'));
    }

    public function store(Request $request)
    {
        $restaurante = $this->restaurante();

        $request->validate([
            'titulo'       => 'required|max:255',
            'descripcion'  => 'nullable|string',
            'imagen'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'galeria.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = $request->except(['imagen', 'galeria']);
        $datos['restaurante_id']  = $restaurante->id;
        $datos['departamento_id'] = $restaurante->departamento_id;
        $datos['is_destacado']    = false;

        // Si ya llegó al límite mensual de visibles, el evento se crea oculto
        $datos['visible_publico'] = $this->visiblesEsteMes($restaurante->id) < 12;

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $evento = Evento::create($datos);

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                if ($img && $img->isValid()) {
                    $path = $img->store('eventos/galeria', 'public');
                    $evento->imagenes()->create(['ruta' => $path]);
                }
            }
        }

        if ($datos['visible_publico']) {
            $this->fcm->enviar(
                '📅 Nuevo evento',
                "¡{$evento->titulo} ya está disponible en {$restaurante->nombre}!"
            );
        }

        $mensaje = $datos['visible_publico']
            ? '¡Evento publicado exitosamente!'
            : 'Evento creado, pero alcanzaste el límite de 12 eventos visibles este mes. Quedó oculto — actívalo cuando tengas espacio disponible.';

        return redirect()->route('restaurante.eventos.index')->with('success', $mensaje);
    }

    public function edit(Evento $evento)
    {
        $restaurante = $this->restaurante();
        abort_unless($evento->restaurante_id === $restaurante->id, 403);

        $evento->load('imagenes');
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $restaurante->departamento_id)->get();

        return view('restaurante.eventos.edit', compact('restaurante', 'evento', 'departamentos', 'municipios'));
    }

    public function update(Request $request, Evento $evento)
    {
        $restaurante = $this->restaurante();
        abort_unless($evento->restaurante_id === $restaurante->id, 403);

        $validated = $request->validate([
            'titulo'       => 'required|max:255',
            'descripcion'  => 'nullable|string',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'galeria.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Imagen: solo actualizar si se subió una nueva
        if ($request->hasFile('imagen')) {
            if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
            $validated['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        } else {
            unset($validated['imagen']); // mantener la imagen existente
        }

        // Galería adicional
        unset($validated['galeria']);
        unset($validated['is_destacado']); // bloquear que restaurantes cambien esto
        $validated['is_destacado'] = false; // forzar siempre normal
        $evento->update($validated);

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                if ($img && $img->isValid()) {
                    $path = $img->store('eventos/galeria', 'public');
                    $evento->imagenes()->create(['ruta' => $path]);
                }
            }
        }

        return redirect()->route('restaurante.eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Activa/desactiva la visibilidad pública del evento, respetando
     * el límite de 12 eventos visibles por mes calendario.
     */
    public function toggleVisibilidad(Evento $evento)
    {
        $restaurante = $this->restaurante();
        abort_unless($evento->restaurante_id === $restaurante->id, 403);

        if (!$evento->visible_publico) {
            // Se quiere activar: validar que no supere el límite mensual
            $visibles = $this->visiblesEsteMes($restaurante->id, $evento->id);

            if ($visibles >= 12) {
                return back()->with('error', 'Alcanzaste el límite de 12 eventos visibles este mes. Oculta otro evento o esperá al próximo mes.');
            }

            $evento->update(['visible_publico' => true]);

            // ✅ NUEVO: notificar al volver a mostrarlo
            $this->fcm->enviar(
                '📅 Evento disponible',
                "¡{$evento->titulo} ya está disponible en {$restaurante->nombre}!"
            );

            return back()->with('success', 'Evento ahora visible al público.');
        }

        $evento->update(['visible_publico' => false]);
        return back()->with('success', 'Evento ocultado del público.');
    }

    public function destroy(Evento $evento)
    {
        $restaurante = $this->restaurante();
        abort_unless($evento->restaurante_id === $restaurante->id, 403);

        if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
        foreach ($evento->imagenes as $img) {
            Storage::disk('public')->delete($img->ruta);
            $img->delete();
        }
        $evento->delete();

        return redirect()->route('restaurante.eventos.index')
            ->with('success', 'Evento eliminado.');
    }
}
