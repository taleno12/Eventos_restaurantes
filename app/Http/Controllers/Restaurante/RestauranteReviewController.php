<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class RestauranteReviewController extends Controller
{
    public function index()
    {
        $restaurante = Auth::user()->restaurante;

        $reviews = Review::where('restaurante_id', $restaurante->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        $totalReviews = Review::where('restaurante_id', $restaurante->id)->count();
        $avgRating    = $totalReviews > 0
            ? round(Review::where('restaurante_id', $restaurante->id)->avg('rating'), 1)
            : null;

        // Conteo por estrella para la barra de distribución
        $conteoEstrellas = [];
        for ($i = 5; $i >= 1; $i--) {
            $cantidad = Review::where('restaurante_id', $restaurante->id)
                ->where('rating', $i)->count();
            $porcentaje = $totalReviews > 0 ? round(($cantidad / $totalReviews) * 100) : 0;

            $conteoEstrellas[$i] = [
                'cantidad'   => $cantidad,
                'porcentaje' => $porcentaje,
            ];
        }

        return view('restaurante.reviews.index', compact(
            'restaurante',
            'reviews',
            'totalReviews',
            'avgRating',
            'conteoEstrellas'
        ));
    }
}
