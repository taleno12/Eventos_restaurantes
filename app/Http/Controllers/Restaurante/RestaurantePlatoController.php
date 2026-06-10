<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Plato;
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

        Plato::create($validated);

        return redirect()->route('restaurante.platos.index')
            ->with('success', '¡Plato añadido al menú!');
    }

    public function edit(Plato $plato)
    {
        $restaurante = $this->restaurante();
        abort_unless($plato->restaurante_id === $restaurante->id, 403);

        $categorias = $restaurante->categoriasPlato()->ordenadas()->get();
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
}
