<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\RestauranteFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestauranteGaleriaController extends Controller
{
    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    public function index()
    {
        $restaurante = $this->restaurante();
        $fotos = RestauranteFoto::where('restaurante_id', $restaurante->id)->latest()->get();
        return view('restaurante.galeria.index', compact('restaurante', 'fotos'));
    }

    public function store(Request $request)
    {
        $restaurante = $this->restaurante();

        $request->validate([
            'fotos'   => 'required|array|max:10',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        foreach ($request->file('fotos') as $foto) {
            $path = $foto->store('restaurantes/galerias', 'public');
            RestauranteFoto::create([
                'restaurante_id' => $restaurante->id,
                'ruta_foto'      => $path,
            ]);
        }

        return back()->with('success', 'Fotos subidas correctamente.');
    }

    public function destroy(RestauranteFoto $foto)
    {
        $restaurante = $this->restaurante();
        abort_unless($foto->restaurante_id === $restaurante->id, 403);

        Storage::disk('public')->delete($foto->ruta_foto);
        $foto->delete();

        return back()->with('success', 'Foto eliminada.');
    }
}