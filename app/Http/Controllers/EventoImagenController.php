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
        $this->autorizarEvento($evento);

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
            return back()->with('success', 'Fotos agregadas correctamente.');
        }

        return back()->withErrors(['fotos' => 'No se recibieron imágenes.']);
    }

    /**
     * Elimina una imagen de la galería
     */
    public function destroy(EventoImagen $imagen)
    {
        $evento = $imagen->evento;
        $this->autorizarEvento($evento);

        Storage::disk('public')->delete($imagen->ruta);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada.');
    }

    /**
     * Permite al admin o al dueño real del evento (restaurante/gastrobar)
     * gestionar las imágenes. Antes solo dejaba pasar al admin, por eso
     * los restaurantes y gastrobares recibían 403.
     */
    private function autorizarEvento(?Evento $evento): void
    {
        if (!Auth::check()) {
            abort(403, 'Acceso no autorizado.');
        }

        $user = Auth::user();

        // El admin siempre puede
        if ($user->email === 'admin@turismo.ni' || $user->role === 'admin') {
            return;
        }

        if (!$evento) {
            abort(403, 'Acceso no autorizado.');
        }

        $esDuenoRestaurante = $user->role === 'restaurante'
            && $evento->restaurante_id !== null
            && $evento->restaurante_id === $user->restaurante_id;

        $esDuenoGastrobar = $user->role === 'gastrobar'
            && $evento->gastrobar_id !== null
            && $evento->gastrobar_id === $user->gastrobar_id;

        if (!$esDuenoRestaurante && !$esDuenoGastrobar) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}
