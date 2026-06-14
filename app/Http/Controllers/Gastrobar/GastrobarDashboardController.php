<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Evento;
use App\Models\Empleo;

class GastrobarDashboardController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $gastrobar = $user->gastrobar;

        $totalEventos = Evento::where('gastrobar_id', $gastrobar->id)->count();
        $totalEmpleos = Empleo::where('gastrobar_id', $gastrobar->id)->where('activo', true)->count();

        $eventosProximos = Evento::where('gastrobar_id', $gastrobar->id)
            ->where('fecha_evento', '>=', now())
            ->orderBy('fecha_evento')
            ->take(5)
            ->get();

        return view('gastrobar.dashboard', compact(
            'gastrobar',
            'totalEventos',
            'totalEmpleos',
            'eventosProximos'
        ));
    }
}
