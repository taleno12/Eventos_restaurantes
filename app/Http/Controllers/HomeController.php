<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento; // Importante para usar tu modelo [cite: 3]


class HomeController extends Controller
{
    public function index() {
        
        // 1. Obtenemos todos los eventos marcados como destacados que no han vencido
        $eventosDestacados = Evento::with(['restaurante', 'departamento'])
                               ->where('is_destacado', true)
                               ->where('fecha_evento', '>=', now())
                               ->latest()
                               ->take(5) // Limitamos a 5 para el slider
                               ->get();

                       // 2. Si no hay ninguno marcado como destacado, usamos los 3 más próximos como respaldo
        
        if ($eventosDestacados->isEmpty()) {
            $eventosDestacados = Evento::where('fecha_evento', '>=', now())
                                   ->orderBy('fecha_evento', 'asc')
                                   ->take(3)
                                   ->get();
        }
        
        // 3. Obtenemos el resto de eventos para la sección de abajo (excluyendo los del banner)
   
        $idsDestacados = $eventosDestacados->pluck('id');
        $eventos = Evento::with(['restaurante', 'departamento'])
                     ->whereNotIn('id', $idsDestacados)
                     ->where('fecha_evento', '>=', now())
                     ->orderBy('fecha_evento', 'asc')
                     ->get();

                     // 4. Enviamos AMBAS variables a la vista
         return view('welcome', compact('eventosDestacados', 'eventos'));
    }
}


