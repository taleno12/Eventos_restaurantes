<?php

namespace App\Http\Controllers;

use App\Models\EventoImagen;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventoImagenController extends Controller
{
    /**
     * Guarda múltiples imágenes de galería (llamado desde el form del edit)
     */
    public function store(Request $request, Evento $evento)
    {
        $this->soloAdmin();

        $request->validate([
            'fotos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $img) {
                if ($img && $img->isValid()) {
                    $path = $img->store('eventos/galeria', 'public');
                    $evento->imagenes()->create(['ruta' => $path]);
                }
            }
            return redirect()->route('eventos.edit', $evento->id)
                ->with('success', 'Fotos agregadas correctamente.');
        }

        return back()->withErrors(['fotos' => 'No se recibieron imágenes.']);
    }

    /**
     * Elimina una imagen de la galería
     */
    public function destroy(EventoImagen $imagen)
    {
        $this->soloAdmin();

        Storage::disk('public')->delete($imagen->ruta);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada.');
    }

    private function soloAdmin(): void
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@turismo.ni') {
            abort(403, 'Acceso no autorizado.');
        }
    }
}