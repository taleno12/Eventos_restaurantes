<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Empleo;
use App\Models\Evento;
use App\Models\GastrobarFoto;
use Illuminate\Support\Facades\Auth;

class GastrobarEstadisticasController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index()
    {
        $gastrobar = $this->gastrobar();
        $desde     = now()->subDays(30)->startOfDay();

        // Reseñas
        $totalReviews  = $gastrobar->reviews()->count();
        $avgRating     = $totalReviews > 0
            ? round($gastrobar->reviews()->avg('rating'), 1)
            : 0;
        $reviewsRecientes = $gastrobar->reviews()->where('created_at', '>=', $desde)->count();

        $distribucionRating = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribucionRating[$i] = $gastrobar->reviews()->where('rating', $i)->count();
        }

        // Eventos
        $totalEventos    = Evento::where('gastrobar_id', $gastrobar->id)->count();
        $eventosProximos = Evento::where('gastrobar_id', $gastrobar->id)
            ->where('fecha_evento', '>=', now()->toDateString())
            ->count();
        $eventosRecientes = Evento::where('gastrobar_id', $gastrobar->id)
            ->where('created_at', '>=', $desde)
            ->count();

        // Empleos
        $totalEmpleos    = Empleo::where('gastrobar_id', $gastrobar->id)->count();
        $empleosActivos  = Empleo::where('gastrobar_id', $gastrobar->id)
            ->where('activo', true)->count();

        // Fotos
        $totalFotos = GastrobarFoto::where('gastrobar_id', $gastrobar->id)->count();

        // Reseñas recientes para tabla
        $recentReviews = $gastrobar->reviews()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Arrays para gráfico de reseñas
        $labelsRating    = ['5 ★', '4 ★', '3 ★', '2 ★', '1 ★'];
        $cantidadRating  = [
            $distribucionRating[5],
            $distribucionRating[4],
            $distribucionRating[3],
            $distribucionRating[2],
            $distribucionRating[1],
        ];

        return view('gastrobar.estadisticas.index', compact(
            'gastrobar',
            'totalReviews',
            'avgRating',
            'reviewsRecientes',
            'distribucionRating',
            'totalEventos',
            'eventosProximos',
            'eventosRecientes',
            'totalEmpleos',
            'empleosActivos',
            'totalFotos',
            'recentReviews',
            'labelsRating',
            'cantidadRating'
        ));
    }
}
