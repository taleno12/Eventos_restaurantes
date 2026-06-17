<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
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
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:platos,id',
            'items.*.cantidad' => 'required|integer|min:1|max:20',
            'notas'         => 'nullable|string|max:500',
            'tipo'          => 'required|in:mesa,para_llevar',
        ]);

        // Verificar que todos los platos pertenecen a este restaurante y están activos
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

    // Historial de pedidos del usuario
    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', Auth::id())
            ->with(['restaurante', 'items.plato'])
            ->latest()
            ->paginate(10);

        return view('pedidos.mis-pedidos', compact('pedidos'));
    }

    // Ver detalle de un pedido del usuario
    public function show(Pedido $pedido)
    {
        abort_unless($pedido->user_id === Auth::id(), 403);
        $pedido->load(['restaurante', 'items.plato']);
        return view('pedidos.show', compact('pedido'));
    }

    // Eliminar pedido confirmado
    public function destroy(Pedido $pedido)
    {
        abort_unless($pedido->user_id === Auth::id(), 403);
        abort_unless($pedido->estado === 'confirmado', 403);

        $pedido->delete();

        return redirect()->route('pedidos.mis')
            ->with('success', 'Pedido eliminado correctamente.');
    }
}
