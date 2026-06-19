<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Plato;
use App\Models\PlatoOpcion;
use App\Models\PlatoOpcionValor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastrobarPlatoController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index()
    {
        $gastrobar = $this->gastrobar();
        $platos = Plato::where('gastrobar_id', $gastrobar->id)
            ->orderBy('orden')
            ->get()
            ->groupBy(fn($p) => $p->categoria ?? 'Sin categoria');
        $categorias = $gastrobar->categoriasPlato()->ordenadas()->get();
        return view('gastrobar.platos.index', compact('gastrobar', 'platos', 'categorias'));
    }

    public function create()
    {
        $gastrobar  = $this->gastrobar();
        $categorias = $gastrobar->categoriasPlato()->ordenadas()->get();
        return view('gastrobar.platos.create', compact('gastrobar', 'categorias'));
    }

    public function store(Request $request)
    {
        $gastrobar = $this->gastrobar();
        $validated = $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categoria'   => 'nullable|string|max:100',
            'orden'       => 'nullable|integer|min:0',
        ]);
        $validated['gastrobar_id'] = $gastrobar->id;
        $validated['activo']       = $request->boolean('activo', true);
        $validated['orden']        = $validated['orden'] ?? 0;
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('platos', 'public');
        }
        $plato = Plato::create($validated);
        $this->guardarOpciones($plato, $request);
        return redirect()->route('gastrobar.platos.index')->with('success', 'Plato anadido al menu.');
    }

    public function show(Plato $plato)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($plato->gastrobar_id === $gastrobar->id, 403);
        return redirect()->route('gastrobar.platos.edit', $plato);
    }

    public function edit(Plato $plato)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($plato->gastrobar_id === $gastrobar->id, 403);
        $plato->load('opciones.valores');
        $categorias = $gastrobar->categoriasPlato()->ordenadas()->get();
        return view('gastrobar.platos.edit', compact('gastrobar', 'plato', 'categorias'));
    }

    public function update(Request $request, Plato $plato)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($plato->gastrobar_id === $gastrobar->id, 403);
        $validated = $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categoria'   => 'nullable|string|max:100',
            'orden'       => 'nullable|integer|min:0',
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
        $plato->opciones()->each(fn($op) => $op->valores()->delete());
        $plato->opciones()->delete();
        $this->guardarOpciones($plato, $request);
        return redirect()->route('gastrobar.platos.index')->with('success', 'Plato actualizado.');
    }

    public function destroy(Plato $plato)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($plato->gastrobar_id === $gastrobar->id, 403);
        if ($plato->imagen) Storage::disk('public')->delete($plato->imagen);
        $plato->opciones()->each(fn($op) => $op->valores()->delete());
        $plato->opciones()->delete();
        $plato->delete();
        return redirect()->route('gastrobar.platos.index')->with('success', 'Plato eliminado.');
    }

    public function toggleActivo(Plato $plato)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($plato->gastrobar_id === $gastrobar->id, 403);
        $plato->update(['activo' => !$plato->activo]);
        return back()->with('success', $plato->activo ? 'Plato activado.' : 'Plato desactivado.');
    }

    private function guardarOpciones(Plato $plato, Request $request)
    {
        foreach ($request->input('opciones', []) as $i => $opcionData) {
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
