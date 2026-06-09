<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Restaurante;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // ── Departamento y Municipio predefinidos por login ──
        $departamentoPredefinido = Auth::check() ? Auth::user()->departamento_id : null;
        $municipioPredefinido    = Auth::check() ? Auth::user()->municipio_id    : null;

        $hayFiltroActivo = $request->hasAny(['departamento', 'municipio', 'especialidad', 'restaurante_id']);

        $deptoFiltro = $hayFiltroActivo
            ? ($request->filled('departamento') ? $request->departamento : null)
            : $departamentoPredefinido;

        $munFiltro = $hayFiltroActivo
            ? ($request->filled('municipio') ? $request->municipio : null)
            : $municipioPredefinido;

        // ── Eventos destacados para el hero ──
        $eventosDestacados = Evento::with(['restaurante', 'departamento'])
            ->where('is_destacado', true)
            ->where('fecha_evento', '>=', now())
            ->latest()
            ->take(5)
            ->get();

        if ($eventosDestacados->isEmpty()) {
            $eventosDestacados = Evento::with(['restaurante', 'departamento'])
                ->where('fecha_evento', '>=', now())
                ->orderBy('fecha_evento', 'asc')
                ->take(3)
                ->get();
        }

        // ── Query principal de eventos con filtros ──
        $idsDestacados = $eventosDestacados->pluck('id');

        $query = Evento::with(['restaurante', 'departamento', 'municipio'])
            ->whereNotIn('id', $idsDestacados)
            ->where('fecha_evento', '>=', now());

        if ($deptoFiltro) {
            $query->where('departamento_id', $deptoFiltro);
        }

        if ($munFiltro) {
            $query->where('municipio_id', $munFiltro);
        }

        if ($request->filled('especialidad')) {
            $query->whereHas('restaurante', fn($q) =>
                $q->where('especialidad', 'like', '%' . $request->especialidad . '%')
            );
        }

        if ($request->filled('restaurante_id')) {
            $query->where('restaurante_id', $request->restaurante_id);
        }

        $eventos = $query->orderBy('fecha_evento', 'asc')->paginate(12);

        // ── Datos para los filtros ──
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::orderBy('nombre')->get();
        $restaurantes  = Restaurante::orderBy('nombre')->get();

        return view('welcome', compact(
            'eventosDestacados',
            'eventos',
            'departamentos',
            'municipios',
            'restaurantes',
            'departamentoPredefinido',
            'municipioPredefinido'
        ));
    }
}
