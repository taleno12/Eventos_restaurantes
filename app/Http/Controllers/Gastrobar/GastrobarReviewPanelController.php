<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class GastrobarReviewPanelController extends Controller
{
    public function index()
    {
        $gastrobar = Auth::user()->gastrobar;

        Review::where('gastrobar_id', $gastrobar->id)
            ->where('vista', false)
            ->update(['vista' => true]);

        $reviews = Review::where('gastrobar_id', $gastrobar->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        $totalReviews = Review::where('gastrobar_id', $gastrobar->id)->count();
        $avgRating    = $totalReviews > 0
            ? round(Review::where('gastrobar_id', $gastrobar->id)->avg('rating'), 1)
            : null;

        $conteoEstrellas = [];
        for ($i = 5; $i >= 1; $i--) {
            $cantidad = Review::where('gastrobar_id', $gastrobar->id)
                ->where('rating', $i)->count();
            $porcentaje = $totalReviews > 0 ? round(($cantidad / $totalReviews) * 100) : 0;

            $conteoEstrellas[$i] = [
                'cantidad'   => $cantidad,
                'porcentaje' => $porcentaje,
            ];
        }

        return view('gastrobar.reviews.index', compact(
            'gastrobar',
            'reviews',
            'totalReviews',
            'avgRating',
            'conteoEstrellas'
        ));
    }
}
