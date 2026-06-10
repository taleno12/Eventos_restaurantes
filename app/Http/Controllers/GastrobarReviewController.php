<?php

namespace App\Http\Controllers;

use App\Models\Gastrobar;
use App\Models\Review;
use Illuminate\Http\Request;

class GastrobarReviewController extends Controller
{
    public function store(Request $request, Gastrobar $gastrobar)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:100',
            'body'   => 'nullable|string|max:1000',
        ]);

        // Verificar que no haya reseñado antes
        $existe = Review::where('gastrobar_id', $gastrobar->id)
                        ->where('user_id', auth()->id())
                        ->exists();

        if ($existe) {
            return back()->with('error', 'Ya dejaste una reseña para este gastrobar.');
        }

        Review::create([
            'gastrobar_id' => $gastrobar->id,
            'user_id'      => auth()->id(),
            'rating'       => $request->rating,
            'title'        => $request->title,
            'body'         => $request->body,
        ]);

        return back()->with('success', '¡Reseña publicada con éxito!');
    }

    public function update(Request $request, Review $review)
    {
        // Solo el dueño o admin puede editar
        if (auth()->id() !== $review->user_id && auth()->user()->email !== 'admin@turismo.ni') {
            return back()->with('error', 'No tienes permiso para editar esta reseña.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:100',
            'body'   => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'title'  => $request->title,
            'body'   => $request->body,
        ]);

        return back()->with('success', 'Reseña actualizada.');
    }

    public function destroy(Review $review)
    {
        // Solo el dueño o admin puede eliminar
        if (auth()->id() !== $review->user_id && auth()->user()->email !== 'admin@turismo.ni') {
            return back()->with('error', 'No tienes permiso para eliminar esta reseña.');
        }

        $review->delete();

        return back()->with('success', 'Reseña eliminada.');
    }
}
