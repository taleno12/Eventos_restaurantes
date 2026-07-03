<?php

namespace App\Http\Controllers;

use App\Models\PedidoGastrobar;
use App\Models\PedidoGastrobarItem;
use App\Models\Plato;
use App\Models\Gastrobar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoGastrobarController extends Controller
{
    public function store(Request $request, Gastrobar $gastrobar)
    {
        $request->validate([
            'items'             => 'required|array|min:1',
            'items.*.id'        => 'required|exists:platos,id',
            'items.*.cantidad'  => 'required|integer|min:1|max:20',
            'notas'             => 'nullable|string|max:500',
            'tipo'              => 'required|in:envio,retiro',
            'direccion_entrega' => 'required_if:tipo,envio|nullable|string|max:500',
        ]);

        // El formulario usa 'envio'/'retiro', pero la columna en BD usa 'delivery'/'para_llevar'
        $tipoMap = [
            'envio'  => 'delivery',
            'retiro' => 'para_llevar',
        ];

        $itemsValidados = [];
        $total = 0;

        foreach ($request->items as $item) {
            $plato = Plato::where('id', $item['id'])
                ->where('gastrobar_id', $gastrobar->id)
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

        DB::transaction(function () use ($gastrobar, $request, $itemsValidados, $total, $tipoMap) {
            $pedido = PedidoGastrobar::create([
                'gastrobar_id'      => $gastrobar->id,
                'user_id'           => Auth::id(),
                'estado'            => 'pendiente',
                'total'             => $total,
                'notas'             => $request->notas,
                'tipo'              => $tipoMap[$request->tipo],
                'direccion_entrega' => $request->tipo === 'envio' ? $request->direccion_entrega : null,
            ]);

            foreach ($itemsValidados as $item) {
                $pedido->items()->create($item);
            }
        });

        return redirect()->route('gastrobares.show', $gastrobar)
            ->with('pedido_success', '¡Tu pedido fue enviado! El gastrobar lo confirmará en breve.');
    }
}
