<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Empleo;
use Illuminate\Support\Facades\Auth;

class RestauranteDashboardController extends Controller
{
    public function index()
    {
        $restaurante = Auth::user()->restaurante;

        $totalEventos = Evento::where('restaurante_id', $restaurante->id)->count();
        $totalEmpleos = Empleo::where('restaurante_id', $restaurante->id)->where('activo', true)->count();
        $eventosProximos = Evento::where('restaurante_id', $restaurante->id)
            ->where('fecha_evento', '>=', now())
            ->orderBy('fecha_evento')
            ->take(5)
            ->get();

        return view('restaurantes.dashboardrestaurante', compact(
            'restaurante',
            'totalEventos',
            'totalEmpleos',
            'eventosProximos'
        ));
    }
}