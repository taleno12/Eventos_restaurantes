<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Plato;
use App\Models\PlatoOpcion;
use App\Models\PlatoOpcionValor;
use App\Models\CategoriaPlato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantePlatoController extends Controller
{
    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    public function index()
    {
        $restaurante = $this->restaurante();
        $categorias  = $restaurante->categoriasPlato()->ordenadas()->get();

        $platos = Plato::where('restaurante_id', $restaurante->id)
            ->orderBy('categoria_id')
            ->orderBy('orden')
            ->get()
            ->groupBy(fn($p) => $p->categoriaPlato->nombre ?? 'Sin categoría');

        return view('restaurante.platos.index', compact('restaurante', 'platos', 'categorias'));
    }

    public function create()
    {
        $restaurante = $this->restaurante();
        $categorias  = $restaurante->categoriasPlato()->ordenadas()->get();
        return view('restaurante.platos.create', compact('restaurante', 'categorias'));
    }

    public function store(Request $request)
    {
        $restaurante = $this->restaurante();

        $validated = $request->validate([
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categoria_id' => 'nullable|exists:categorias_plato,id',
            'orden'        => 'nullable|integer|min:0',
        ]);

        $validated['restaurante_id'] = $restaurante->id;
        $validated['activo']         = $request->boolean('activo', true);
        $validated['orden']          = $validated['orden'] ?? 0;

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('platos', 'public');
        }

        $plato = Plato::create($validated);

        // Guardar opciones
        $this->guardarOpciones($plato, $request);

        return redirect()->route('restaurante.platos.index')
            ->with('success', '¡Plato añadido al menú!');
    }

    public function edit(Plato $plato)
    {
        $restaurante = $this->restaurante();
        abort_unless($plato->restaurante_id === $restaurante->id, 403);

        $categorias = $restaurante->categoriasPlato()->ordenadas()->get();
        $plato->load('opciones.valores');

        return view('restaurante.platos.edit', compact('restaurante', 'plato', 'categorias'));
    }

    public function update(Request $request, Plato $plato)
    {
        $restaurante = $this->restaurante();
        abort_unless($plato->restaurante_id === $restaurante->id, 403);

        $validated = $request->validate([
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categoria_id' => 'nullable|exists:categorias_plato,id',
            'orden'        => 'nullable|integer|min:0',
        ]);

        $validated['activo'] = $request->boolean('activo', false);
        $validated['orden']  = $validated['orden'] ?? 0;

        if ($request->hasFile('imagen')) {
            if ($plato->imagen) Storage::disk('public')->delete($plato->imagen);
            $validated['imagen'] = $request->file('imagen')->store('platos', 'public');
        } else {
            unset($validated['imagen']);
        }

        $plato->update($validated);

        // Reemplazar opciones
        $plato->opciones()->each(fn($op) => $op->valores()->delete());
        $plato->opciones()->delete();
        $this->guardarOpciones($plato, $request);

        return redirect()->route('restaurante.platos.index')
            ->with('success', 'Plato actualizado correctamente.');
    }

    public function destroy(Plato $plato)
    {
        $restaurante = $this->restaurante();
        abort_unless($plato->restaurante_id === $restaurante->id, 403);

        if ($plato->imagen) Storage::disk('public')->delete($plato->imagen);
        $plato->delete();

        return redirect()->route('restaurante.platos.index')
            ->with('success', 'Plato eliminado del menú.');
    }

    public function toggleActivo(Plato $plato)
    {
        $restaurante = $this->restaurante();
        abort_unless($plato->restaurante_id === $restaurante->id, 403);

        $plato->update(['activo' => !$plato->activo]);

        return back()->with('success', $plato->activo ? 'Plato activado.' : 'Plato desactivado.');
    }

    private function guardarOpciones(Plato $plato, Request $request)
    {
        $opciones = $request->input('opciones', []);

        foreach ($opciones as $i => $opcionData) {
            if (empty($opcionData['nombre'])) continue;

            $opcion = PlatoOpcion::create([
                'plato_id'  => $plato->id,
                'nombre'    => $opcionData['nombre'],
                'tipo'      => $opcionData['tipo'] ?? 'radio',
                'requerido' => isset($opcionData['requerido']) ? 1 : 0,
                'orden'     => $i,
            ]);

            foreach ($opcionData['valores'] ?? [] as $j => $valorData) {
                if (empty($valorData['valor'])) continue;

                PlatoOpcionValor::create([
                    'opcion_id'    => $opcion->id,
                    'valor'        => $valorData['valor'],
                    'precio_extra' => $valorData['precio_extra'] ?? 0,
                    'orden'        => $j,
                ]);
            }
        }
    }
}
