<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Services\FcmNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastrobarEventoController extends Controller
{
    private FcmNotificationService $fcm;

    const LIMITE_EVENTOS_VISIBLES_MES = 12;

    public function __construct(FcmNotificationService $fcm)
    {
        $this->fcm = $fcm;
    }

    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    /**
     * Cuenta eventos visibles al público creados en el mes calendario actual.
     */
    private function eventosVisiblesEsteMes(int $gastrobarId)
    {
        return Evento::where('gastrobar_id', $gastrobarId)
            ->where('visible_publico', true)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
    }

    public function index()
    {
        $gastrobar = $this->gastrobar();
        $eventos   = Evento::where('gastrobar_id', $gastrobar->id)
            ->latest()->paginate(10);

        $visiblesEsteMes = $this->eventosVisiblesEsteMes($gastrobar->id);

        return view('gastrobar.eventos.index', compact('gastrobar', 'eventos', 'visiblesEsteMes'));
    }

    public function create()
    {
        $gastrobar     = $this->gastrobar();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('gastrobar.eventos.create', compact('gastrobar', 'departamentos'));
    }

    public function store(Request $request)
    {
        $gastrobar = $this->gastrobar();

        $request->validate([
            'titulo'       => 'required|max:255',
            'descripcion'  => 'nullable|string',
            'imagen'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'galeria'      => 'nullable|array|max:4',
            'galeria.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = $request->except(['imagen', 'galeria']);
        $datos['gastrobar_id']    = $gastrobar->id;
        $datos['departamento_id'] = $gastrobar->departamento_id;
        $datos['is_destacado']    = false;

        // Si ya alcanzó el límite de visibles este mes, el evento se crea oculto
        $yaAlcanzoLimite = $this->eventosVisiblesEsteMes($gastrobar->id) >= self::LIMITE_EVENTOS_VISIBLES_MES;
        $datos['visible_publico'] = !$yaAlcanzoLimite;

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

        // ✅ Solo notificar si el evento quedó visible al público
        if ($datos['visible_publico']) {
            $this->fcm->enviar(
                'Nuevo evento',
                "¡{$evento->titulo} ya está disponible en {$gastrobar->nombre}!"
            );
        }

        $mensaje = '¡Evento publicado exitosamente!';
        if ($yaAlcanzoLimite) {
            $mensaje .= ' Alcanzaste el límite de ' . self::LIMITE_EVENTOS_VISIBLES_MES . ' eventos visibles este mes, así que este quedó guardado pero oculto del público. Podés activarlo desactivando otro, o esperar al próximo mes.';
        }

        return redirect()->route('gastrobar.eventos.index')->with('success', $mensaje);
    }

    public function show(Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);
        return redirect()->route('gastrobar.eventos.edit', $evento);
    }

    public function edit(Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);

        $evento->load('imagenes');
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $gastrobar->departamento_id)->get();

        return view('gastrobar.eventos.edit', compact('gastrobar', 'evento', 'departamentos', 'municipios'));
    }

    public function update(Request $request, Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);

        $validated = $request->validate([
            'titulo'       => 'required|max:255',
            'descripcion'  => 'nullable|string',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'galeria'      => 'nullable|array|max:4',
            'galeria.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
            $validated['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        } else {
            unset($validated['imagen']);
        }

        unset($validated['galeria']);
        $validated['is_destacado'] = false;
        $evento->update($validated);

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                if ($img && $img->isValid()) {
                    $path = $img->store('eventos/galeria', 'public');
                    $evento->imagenes()->create(['ruta' => $path]);
                }
            }
        }

        return redirect()->route('gastrobar.eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Activa/desactiva la visibilidad pública de un evento, respetando el límite mensual.
     */
    public function toggleVisibilidad(Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);

        if (!$evento->visible_publico) {
            $visiblesEsteMes = $this->eventosVisiblesEsteMes($gastrobar->id);

            if ($visiblesEsteMes >= self::LIMITE_EVENTOS_VISIBLES_MES) {
                return back()->with('error', 'Ya alcanzaste el límite de ' . self::LIMITE_EVENTOS_VISIBLES_MES . ' eventos visibles este mes. Oculta otro evento primero, o esperá al próximo mes.');
            }
        }

        $evento->visible_publico = !$evento->visible_publico;
        $evento->save();

        // ✅ NUEVO: notificar solo cuando pasa de oculto a visible
        if ($evento->visible_publico) {
            $this->fcm->enviar(
                'Evento disponible',
                "¡{$evento->titulo} ya está disponible en {$gastrobar->nombre}!"
            );
        }

        return back()->with('success', $evento->visible_publico
            ? 'Evento ahora visible al público.'
            : 'Evento oculto del público.');
    }

    public function destroy(Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);

        if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
        foreach ($evento->imagenes as $img) {
            Storage::disk('public')->delete($img->ruta);
            $img->delete();
        }
        $evento->delete();

        return redirect()->route('gastrobar.eventos.index')
            ->with('success', 'Evento eliminado.');
    }
}
