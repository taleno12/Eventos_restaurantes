<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\PedidoItem;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestauranteEstadisticasController extends Controller
{
    public function index()
    {
        $restaurante = Auth::user()->restaurante;
        $desde       = now()->subDays(30)->startOfDay();

        // IDs de pedidos del restaurante en los últimos 30 días
        $pedidoIds = Pedido::where('restaurante_id', $restaurante->id)
            ->whereIn('estado', ['confirmado', 'en_preparacion', 'listo', 'entregado'])
            ->where('created_at', '>=', $desde)
            ->pluck('id');

        // Platos más vendidos: cantidad total vendida y total en C$
        $platosMasVendidos = PedidoItem::whereIn('pedido_id', $pedidoIds)
            ->join('platos', 'pedido_items.plato_id', '=', 'platos.id')
            ->select(
                'platos.nombre',
                'platos.imagen',
                'platos.categoria',
                DB::raw('SUM(pedido_items.cantidad) as total_vendido'),
                DB::raw('SUM(pedido_items.subtotal) as total_ingresos')
            )
            ->groupBy('platos.id', 'platos.nombre', 'platos.imagen', 'platos.categoria')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // Totales generales del período
        $totalPedidos  = $pedidoIds->count();
        $totalIngresos = Pedido::whereIn('id', $pedidoIds)->sum('total');
        $totalPlatos   = PedidoItem::whereIn('pedido_id', $pedidoIds)->sum('cantidad');



        // Preparar arrays para los gráficos (para evitar errores en Blade)
        $nombresPlatos   = $platosMasVendidos->pluck('nombre')->toArray();
        $cantidadesPlatos = $platosMasVendidos->pluck('total_vendido')->map(fn($v) => (int)$v)->toArray();

        return view('restaurante.estadisticas.index', compact(
            'restaurante',
            'platosMasVendidos',
            'totalPedidos',
            'totalIngresos',
            'totalPlatos',
            'nombresPlatos',
            'cantidadesPlatos'
        ));
    }
}
