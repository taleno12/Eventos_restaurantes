<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\PedidoGastrobar;
use App\Models\Plato;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // Crear pedido desde la vista pública del restaurante
    public function store(Request $request, Restaurante $restaurante)
    {
        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|exists:platos,id',
            'items.*.cantidad' => 'required|integer|min:1|max:20',
            'notas'            => 'nullable|string|max:500',
            'tipo'             => 'required|in:envio,retiro',
        ]);

        $itemsValidados = [];
        $total = 0;

        foreach ($request->items as $item) {
            $plato = Plato::where('id', $item['id'])
                ->where('restaurante_id', $restaurante->id)
                ->where('activo', true)
                ->first();

            if (!$plato) {
                return back()->withErrors(['items' => 'Uno de los platos no está disponible.']);
            }

            $subtotal = $plato->precio * $item['cantidad'];
            $total   += $subtotal;

            $itemsValidados[] = [
                'plato_id'        => $plato->id,
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $plato->precio,
                'subtotal'        => $subtotal,
                'notas'           => $item['notas'] ?? null,
            ];
        }

        DB::transaction(function () use ($restaurante, $request, $itemsValidados, $total) {
            $pedido = Pedido::create([
                'restaurante_id' => $restaurante->id,
                'user_id'        => Auth::id(),
                'estado'         => 'pendiente',
                'total'          => $total,
                'notas'          => $request->notas,
                'tipo'           => $request->tipo,
            ]);

            foreach ($itemsValidados as $item) {
                $pedido->items()->create($item);
            }
        });

        return redirect()->route('restaurantes.show', $restaurante)
            ->with('pedido_success', '¡Tu pedido fue enviado! El restaurante lo confirmará en breve.');
    }

    // Historial de pedidos del usuario (restaurante + gastrobar unificados)
    public function misPedidos()
    {
        $pedidosRestaurante = Pedido::where('user_id', Auth::id())
            ->with(['restaurante', 'items.plato'])
            ->get()
            ->map(function ($p) {
                $p->tipo_negocio    = 'restaurante';
                $p->establecimiento = $p->restaurante;
                return $p;
            });

        $pedidosGastrobar = PedidoGastrobar::where('user_id', Auth::id())
            ->with(['gastrobar', 'items.plato'])
            ->get()
            ->map(function ($p) {
                $p->tipo_negocio    = 'gastrobar';
                $p->establecimiento = $p->gastrobar;
                return $p;
            });

        $todos = $pedidosRestaurante->concat($pedidosGastrobar)
            ->sortByDesc('created_at')
            ->values();

        $page    = request()->get('page', 1);
        $perPage = 10;
        $items   = $todos->slice(($page - 1) * $perPage, $perPage)->values();

        $pedidos = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $todos->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('pedidos.mis-pedidos', compact('pedidos'));
    }

    // Ver detalle de un pedido del usuario
    public function show(Pedido $pedido)
    {
        abort_unless($pedido->user_id === Auth::id(), 403);
        $pedido->load(['restaurante', 'items.plato']);
        return view('pedidos.show', compact('pedido'));
    }

    // Eliminar pedido cancelado (restaurante)
    public function destroy(Request $request, $id)
    {
        // Detectar si es gastrobar o restaurante por el parámetro 'tipo'
        $tipo = $request->input('tipo', 'restaurante');

        if ($tipo === 'gastrobar') {
            $pedido = PedidoGastrobar::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        } else {
            $pedido = Pedido::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }

        abort_unless($pedido->estado === 'cancelado', 403);

        $pedido->items()->delete();
        $pedido->delete();

        return redirect()->route('pedidos.mis')
            ->with('success', 'Pedido eliminado correctamente.');
    }
}
