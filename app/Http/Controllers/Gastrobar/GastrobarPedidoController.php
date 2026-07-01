<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\PedidoGastrobar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastrobarPedidoController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index()
    {
        $gastrobar = $this->gastrobar();

        $pedidos = PedidoGastrobar::where('gastrobar_id', $gastrobar->id)
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

        $totalHoy = PedidoGastrobar::where('gastrobar_id', $gastrobar->id)
            ->whereDate('created_at', today())
            ->count();

        $pendientes = PedidoGastrobar::where('gastrobar_id', $gastrobar->id)
            ->where('estado', 'pendiente')
            ->count();

        $ingresoHoy = PedidoGastrobar::where('gastrobar_id', $gastrobar->id)
            ->whereDate('created_at', today())
            ->whereIn('estado', ['confirmado','en_preparacion','listo','entregado'])
            ->sum('total');

        return view('gastrobar.pedidos.index', compact(
            'gastrobar', 'pedidos', 'totalHoy', 'pendientes', 'ingresoHoy'
        ));
    }

    public function show(PedidoGastrobar $pedidoGastrobar)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($pedidoGastrobar->gastrobar_id === $gastrobar->id, 403);
        $pedidoGastrobar->load(['user', 'items.plato']);
        return view('gastrobar.pedidos.show', compact('gastrobar', 'pedidoGastrobar'));
    }

    public function cambiarEstado(Request $request, PedidoGastrobar $pedidoGastrobar)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($pedidoGastrobar->gastrobar_id === $gastrobar->id, 403);

        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,en_preparacion,listo,entregado,cancelado'
        ]);

        $pedidoGastrobar->update(['estado' => $request->estado]);

        // ── Cancelado o entregado: se mantiene el registro.
        // El propietario puede borrarlo desde el panel (destroy) o el cliente desde
        // la app, y si nadie lo borra se elimina solo a los 30 días
        // (comando pedidos:limpiar-cancelados). ──
        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'estado'    => $request->estado,
                'eliminado' => false,
            ]);
        }

        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy(PedidoGastrobar $pedidoGastrobar)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($pedidoGastrobar->gastrobar_id === $gastrobar->id, 403);

        if (!in_array($pedidoGastrobar->estado, ['cancelado', 'entregado'])) {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes eliminar pedidos cancelados o entregados.',
            ], 403);
        }

        $pedidoGastrobar->items()->delete();
        $pedidoGastrobar->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Pedido eliminado correctamente.']);
        }

        return redirect()->route('gastrobar.pedidos.index')
            ->with('success', 'Pedido eliminado correctamente.');
    }

    public function polling(Request $request)
    {
        $gastrobar = $this->gastrobar();
        $desde = $request->get('desde', now()->subMinutes(1)->toISOString());

        $nuevos = PedidoGastrobar::where('gastrobar_id', $gastrobar->id)
            ->where('created_at', '>=', $desde)
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
