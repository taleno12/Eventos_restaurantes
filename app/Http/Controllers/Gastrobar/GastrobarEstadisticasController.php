<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\PedidoGastrobar;
use App\Models\PedidoGastrobarItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GastrobarEstadisticasController extends Controller
{
    public function index()
    {
        $gastrobar = Auth::user()->gastrobar;
        $desde     = now()->subDays(30)->startOfDay();

        // IDs de pedidos del gastrobar en los últimos 30 días
        $pedidoIds = PedidoGastrobar::where('gastrobar_id', $gastrobar->id)
            ->whereIn('estado', ['confirmado', 'en_preparacion', 'listo', 'entregado'])
            ->where('created_at', '>=', $desde)
            ->pluck('id');

        // Platos más vendidos con categoría
        $platosMasVendidos = PedidoGastrobarItem::whereIn('pedido_gastrobar_items.pedido_gastrobar_id', $pedidoIds)
            ->join('platos', 'pedido_gastrobar_items.plato_id', '=', 'platos.id')
            ->leftJoin('categorias_plato', 'platos.categoria_id', '=', 'categorias_plato.id')
            ->select(
                'platos.id',
                'platos.nombre',
                'platos.imagen',
                DB::raw("COALESCE(categorias_plato.nombre, 'Sin categoría') as categoria"),
                DB::raw('SUM(pedido_gastrobar_items.cantidad) as total_vendido'),
                DB::raw('SUM(pedido_gastrobar_items.subtotal) as total_ingresos')
            )
            ->groupBy('platos.id', 'platos.nombre', 'platos.imagen', 'categorias_plato.nombre')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // Totales
        $totalPedidos  = $pedidoIds->count();
        $totalIngresos = PedidoGastrobar::whereIn('id', $pedidoIds)->sum('total');
        $totalPlatos   = PedidoGastrobarItem::whereIn('pedido_gastrobar_id', $pedidoIds)->sum('cantidad');

        // Arrays para gráficos
        $nombresPlatos    = $platosMasVendidos->pluck('nombre')->toArray();
        $cantidadesPlatos = $platosMasVendidos->pluck('total_vendido')->map(fn($v) => (int)$v)->toArray();

        return view('gastrobar.estadisticas.index', compact(
            'gastrobar',
            'platosMasVendidos',
            'totalPedidos',
            'totalIngresos',
            'totalPlatos',
            'nombresPlatos',
            'cantidadesPlatos'
        ));
    }
}
