<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\CategoriaPlato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index()
    {
        $gastrobar  = $this->gastrobar();
        $categorias = $gastrobar->categoriasPlato()->ordenadas()->withCount('platos')->get();

        return view('gastrobar.categorias.index', compact('gastrobar', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100']);

        $gastrobar = $this->gastrobar();
        $orden = $gastrobar->categoriasPlato()->max('orden') + 1;

        $gastrobar->categoriasPlato()->create([
            'nombre' => $request->nombre,
            'orden'  => $orden,
        ]);

        return back()->with('success', 'Categoría creada.');
    }

    public function update(Request $request, CategoriaPlato $categoria)
    {
        abort_if($categoria->gastrobar_id !== $this->gastrobar()->id, 403);

        $request->validate(['nombre' => 'required|string|max:100']);

        $categoria->update(['nombre' => $request->nombre]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy(CategoriaPlato $categoria)
    {
        abort_if($categoria->gastrobar_id !== $this->gastrobar()->id, 403);

        $categoria->delete();

        return back()->with('success', 'Categoría eliminada.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['orden' => 'required|array', 'orden.*' => 'integer']);

        $gastrobar = $this->gastrobar();

        foreach ($request->orden as $posicion => $id) {
            $gastrobar->categoriasPlato()
                ->where('id', $id)
                ->update(['orden' => $posicion]);
        }

        return response()->json(['ok' => true]);
    }
}
