<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\GastrobarFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastrobarGaleriaController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index()
    {
        $gastrobar = $this->gastrobar();
        $fotos     = GastrobarFoto::where('gastrobar_id', $gastrobar->id)->latest()->get();

        return view('gastrobar.galeria.index', compact('gastrobar', 'fotos'));
    }

    public function store(Request $request)
    {
        $gastrobar = $this->gastrobar();

        $request->validate([
            'fotos'   => 'required|array|max:10',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        foreach ($request->file('fotos') as $foto) {
            $path = $foto->store('gastrobares/galerias', 'public');
            GastrobarFoto::create([
                'gastrobar_id' => $gastrobar->id,
                'ruta_foto'    => $path,
            ]);
        }

        return back()->with('success', 'Fotos subidas correctamente.');
    }

    public function destroy(GastrobarFoto $foto)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($foto->gastrobar_id === $gastrobar->id, 403);

        Storage::disk('public')->delete($foto->ruta_foto);
        $foto->delete();

        return back()->with('success', 'Foto eliminada.');
    }
}
