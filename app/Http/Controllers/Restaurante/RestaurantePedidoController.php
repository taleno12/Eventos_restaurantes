<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantePedidoController extends Controller
{
    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    public function index()
    {
        $restaurante = $this->restaurante();

        // Pedidos activos: SOLO excluir cancelados, entregados se quedan
        $pedidos = Pedido::where('restaurante_id', $restaurante->id)
            ->where('estado', '!=', 'cancelado')
            ->with(['user', 'items.plato'])
            ->orderByRaw("CASE estado
                WHEN 'pendiente' THEN 1
                WHEN 'confirmado' THEN 2
                WHEN 'en_preparacion' THEN 3
                WHEN 'listo' THEN 4
                WHEN 'entregado' THEN 5
                ELSE 6 END")
            ->latest()
            ->get()
            ->groupBy('estado');

        // Métricas sin cancelados
        $totalHoy = Pedido::where('restaurante_id', $restaurante->id)
            ->whereDate('created_at', today())
            ->where('estado', '!=', 'cancelado')
            ->count();

        $pendientes = Pedido::where('restaurante_id', $restaurante->id)
            ->where('estado', 'pendiente')
            ->count();

        $ingresoHoy = Pedido::where('restaurante_id', $restaurante->id)
            ->whereDate('created_at', today())
            ->whereIn('estado', ['confirmado','en_preparacion','listo','entregado'])
            ->sum('total');

        return view('restaurante.pedidos.index', compact(
            'restaurante', 'pedidos', 'totalHoy', 'pendientes', 'ingresoHoy'
        ));
    }

    public function show(Pedido $pedido)
    {
        $restaurante = $this->restaurante();
        abort_unless($pedido->restaurante_id === $restaurante->id, 403);
        $pedido->load(['user', 'items.plato']);
        return view('restaurante.pedidos.show', compact('restaurante', 'pedido'));
    }

    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $restaurante = $this->restaurante();
        abort_unless($pedido->restaurante_id === $restaurante->id, 403);

        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,en_preparacion,listo,entregado,cancelado'
        ]);

        $pedido->update(['estado' => $request->estado]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'estado' => $request->estado,
                'eliminado' => $request->estado === 'cancelado'
            ]);
        }

        return back()->with('success', 'Estado actualizado.');
    }

    public function polling(Request $request)
    {
        $restaurante = $this->restaurante();
        $desde = $request->get('desde', now()->subMinutes(1)->toISOString());

        $nuevos = Pedido::where('restaurante_id', $restaurante->id)
            ->where('created_at', '>=', $desde)
            ->where('estado', '!=', 'cancelado')
            ->with(['user', 'items.plato'])
            ->get()
            ->map(function ($p) {
                return [
                    'id'         => $p->id,
                    'estado'     => $p->estado,
                    'total'      => $p->total,
                    'user'       => $p->user->name,
                    'tipo'       => $p->tipo,
                    'created_at' => $p->created_at->format('H:i'),
                    'items'      => $p->items->map(fn($i) => [
                        'nombre'   => $i->plato->nombre,
                        'cantidad' => $i->cantidad,
                        'subtotal' => $i->subtotal,
                    ]),
                ];
            });

        return response()->json(['pedidos' => $nuevos, 'timestamp' => now()->toISOString()]);
    }
}
