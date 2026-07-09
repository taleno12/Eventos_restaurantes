<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IncidentePedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class IncidentePedidoController extends Controller
{
    /**
     * Panel admin: listado de incidentes, filtrable por estado.
     */
    public function index(Request $request): View
    {
        $estado = $request->query('estado');

        $incidentes = IncidentePedido::with(['pedido', 'reportante'])
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('incidentes.index', [
            'incidentes'      => $incidentes,
            'estado'          => $estado,
            'totalIncidentes' => IncidentePedido::count(),
            'totalAbiertos'   => IncidentePedido::where('estado', 'abierto')->count(),
            'totalResueltos'  => IncidentePedido::where('estado', 'resuelto')->count(),
        ]);
    }

    /**
     * Panel admin: resolver un incidente y decidir qué pasa con el pedido.
     */
    public function resolver(Request $request, IncidentePedido $incidente): RedirectResponse
    {
        $data = $request->validate([
            'resolucion'    => ['required', 'string', 'min:5', 'max:1000'],
            'estado_pedido' => ['required', Rule::in(['en_preparacion', 'cancelado', 'entregado'])],
        ]);

        $incidente->update([
            'estado'     => 'resuelto',
            'resolucion' => $data['resolucion'],
        ]);

        $incidente->pedido->update(['estado' => $data['estado_pedido']]);

        return back()->with('success', 'Incidente resuelto correctamente.');
    }
}
