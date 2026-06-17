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
        $eventosDestacadosQuery = Evento::with(['restaurante', 'departamento'])
            ->where('is_destacado', true)
            ->where('fecha_evento', '>=', now());

        if ($deptoFiltro) {
            $eventosDestacadosQuery->where('departamento_id', $deptoFiltro);
        }

        if ($munFiltro) {
            $eventosDestacadosQuery->where('municipio_id', $munFiltro);
        }

        $eventosDestacados = $eventosDestacadosQuery
            ->latest()
            ->take(5)
            ->get();

        // ── Query principal de eventos (incluye destacados también) ──
        $query = Evento::with(['restaurante', 'departamento', 'municipio'])
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

        // ── Restaurantes para el carrusel ──
        $restaurantesQuery = Restaurante::orderBy('nombre');

        if ($deptoFiltro) {
            $restaurantesQuery->where('departamento_id', $deptoFiltro);
        }

        if ($munFiltro) {
            $restaurantesQuery->where('municipio_id', $munFiltro);
        }

        $restaurantes = $restaurantesQuery->get();

        // ── Datos para los filtros (siempre completos) ──
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::orderBy('nombre')->get();

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
