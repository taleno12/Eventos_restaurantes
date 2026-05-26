<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // ❌ Elimina el __construct con middleware — no funciona en Laravel 11+

    /** Guardar nueva reseña */
    public function store(Request $request, Restaurante $restaurante): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:100',
            'body'   => 'nullable|string|max:1000',
        ]);

        // ✅ Variable corregida: $restaurante (no $restaurant)
        $exists = Review::where('restaurante_id', $restaurante->id)
                        ->where('user_id', Auth::id())
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Ya dejaste una reseña para este restaurante.');
        }

        $restaurante->reviews()->create([
            ...$validated,
            'user_id'        => Auth::id(),
            'restaurante_id' => $restaurante->id,
        ]);

        return back()->with('success', '¡Gracias por tu reseña!');
    }

    /** Actualizar reseña propia */
    public function update(Request $request, Review $review): RedirectResponse
    {
        Gate::authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:100',
            'body'   => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Reseña actualizada.');
    }

    /** Eliminar reseña propia */
    public function destroy(Review $review): RedirectResponse
    {
        Gate::authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'Reseña eliminada.');
    }
}