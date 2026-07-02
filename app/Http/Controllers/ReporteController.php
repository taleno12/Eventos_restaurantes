<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Gastrobar;
use App\Models\Empleo;
use App\Models\Evento;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $deptoId = $request->input('departamento_id');

        // ── Restaurantes ─────────────────────────────────────────
        $restQuery = Restaurante::query();
        if ($deptoId) $restQuery->where('departamento_id', $deptoId);

        $totalRestaurantes     = $restQuery->count();
        $restaurantesActivos   = (clone $restQuery)->where('activo', true)->count();
        $restaurantesInactivos = (clone $restQuery)->where('activo', false)->count();

        $restaurantesPorDepto = Departamento::withCount(['restaurantes' => function ($q) use ($deptoId) {
            if ($deptoId) $q->where('departamento_id', $deptoId);
        }])->get()
            ->filter(fn($d) => $d->restaurantes_count > 0)
            ->sortByDesc('restaurantes_count')
            ->values();

        // ── Gastrobares ──────────────────────────────────────────
        $gastroQuery = Gastrobar::query();
        if ($deptoId) $gastroQuery->where('departamento_id', $deptoId);

        $totalGastrobares     = $gastroQuery->count();
        $gastrobaresActivos   = (clone $gastroQuery)->where('activo', true)->count();
        $gastrobaresInactivos = (clone $gastroQuery)->where('activo', false)->count();

        $gastrobaresPorDepto = Departamento::withCount(['gastrobares' => function ($q) use ($deptoId) {
            if ($deptoId) $q->where('departamento_id', $deptoId);
        }])->get()
            ->filter(fn($d) => $d->gastrobares_count > 0)
            ->sortByDesc('gastrobares_count')
            ->values();

        // ── Empleos ──────────────────────────────────────────────
        $empleoQuery = Empleo::query();
        if ($deptoId) $empleoQuery->where('departamento_id', $deptoId);

        $totalEmpleos    = $empleoQuery->count();
        $empleosActivos  = (clone $empleoQuery)->where('activo', true)->count();
        $empleosInactivos = (clone $empleoQuery)->where('activo', false)->count();
        $empleosVencidos = (clone $empleoQuery)
            ->whereNotNull('fecha_limite')
            ->where('fecha_limite', '<', Carbon::today())
            ->count();

        $empleosPorTipo = (clone $empleoQuery)
            ->selectRaw('tipo_contrato, count(*) as total')
            ->whereNotNull('tipo_contrato')
            ->groupBy('tipo_contrato')
            ->pluck('total', 'tipo_contrato');

        // ── Eventos ──────────────────────────────────────────────
        $eventoQuery = Evento::query();
        if ($deptoId) $eventoQuery->where('departamento_id', $deptoId);

        $totalEventos      = $eventoQuery->count();
        $eventosFuturos    = (clone $eventoQuery)->where('fecha_evento', '>=', Carbon::today())->count();
        $eventosPasados    = (clone $eventoQuery)->where('fecha_evento', '<', Carbon::today())->count();
        $eventosDestacados = (clone $eventoQuery)->where('is_destacado', true)->count();

        $eventosPorMes = (clone $eventoQuery)
            ->selectRaw("TO_CHAR(fecha_evento, 'Mon YYYY') as mes, count(*) as total, MIN(fecha_evento) as primer_fecha")
            ->where('fecha_evento', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw("TO_CHAR(fecha_evento, 'Mon YYYY')")
            ->orderBy('primer_fecha')
            ->pluck('total', 'mes');

        // ── Departamentos para el filtro ─────────────────────────
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('reportes.index', compact(
            'totalRestaurantes', 'restaurantesActivos', 'restaurantesInactivos', 'restaurantesPorDepto',
            'totalGastrobares',  'gastrobaresActivos',  'gastrobaresInactivos',  'gastrobaresPorDepto',
            'totalEmpleos',      'empleosActivos',      'empleosInactivos',      'empleosVencidos',     'empleosPorTipo',
            'totalEventos',      'eventosFuturos',      'eventosPasados',        'eventosDestacados',   'eventosPorMes',
            'departamentos'
        ));
    }
}
