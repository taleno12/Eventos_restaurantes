<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\CategoriaPlato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    public function index()
    {
        $restaurante = $this->restaurante();
        $categorias  = $restaurante->categoriasPlato()->ordenadas()->withCount('platos')->get();

        return view('restaurante.categorias.index', compact('restaurante', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100']);

        $restaurante = $this->restaurante();
        $orden = $restaurante->categoriasPlato()->max('orden') + 1;

        $restaurante->categoriasPlato()->create([
            'nombre' => $request->nombre,
            'orden'  => $orden,
        ]);

        return back()->with('success', 'Categoría creada.');
    }

    public function update(Request $request, CategoriaPlato $categoria)
    {
        abort_if($categoria->restaurante_id !== $this->restaurante()->id, 403);

        $request->validate(['nombre' => 'required|string|max:100']);

        $categoria->update(['nombre' => $request->nombre]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy(CategoriaPlato $categoria)
    {
        abort_if($categoria->restaurante_id !== $this->restaurante()->id, 403);

        $categoria->delete();

        return back()->with('success', 'Categoría eliminada.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['orden' => 'required|array', 'orden.*' => 'integer']);

        $restaurante = $this->restaurante();

        foreach ($request->orden as $posicion => $id) {
            $restaurante->categoriasPlato()
                ->where('id', $id)
                ->update(['orden' => $posicion]);
        }

        return response()->json(['ok' => true]);
    }
}
