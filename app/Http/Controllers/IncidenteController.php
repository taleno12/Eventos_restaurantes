<?php

namespace App\Http\Controllers;

use App\Models\IncidentePedido;
use App\Models\Notificacion;
use App\Models\Pedido;
use App\Models\PedidoGastrobar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class IncidenteController extends Controller
{
    private const TIPOS_PEDIDO = [
        'restaurante' => Pedido::class,
        'gastrobar'   => PedidoGastrobar::class,
    ];

    /**
     * Reportar un incidente sobre un pedido (cliente, negocio o motorizado).
     */
    public function store(Request $request, string $tipoPedido, int $pedidoId): JsonResponse
    {
        $modelClass = self::TIPOS_PEDIDO[$tipoPedido] ?? null;

        if (!$modelClass) {
            return response()->json(['message' => 'Tipo de pedido inválido.'], 422);
        }

        $pedido = $modelClass::findOrFail($pedidoId);

        $data = $request->validate([
            'tipo'        => ['required', Rule::in(['cliente', 'negocio', 'motorizado'])],
            'descripcion' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $incidente = $pedido->incidentes()->create([
            'reportado_por' => $request->user()->id,
            'tipo'          => $data['tipo'],
            'descripcion'   => $data['descripcion'],
            'estado'        => 'abierto',
        ]);

        // Congela el pedido para que no siga avanzando solo
        $pedido->update(['estado' => 'incidente']);

        // Notifica internamente a todos los admins (campanita del panel).
        // ⚠️ Asumí las columnas 'user_id', 'titulo', 'mensaje', 'tipo' y 'leida'
        // según el uso que ya tenías en el sidebar (Notificacion::noLeidas()
        // ->where('user_id', ...)). Si tu tabla usa otros nombres, avisame
        // el error exacto y ajusto esta parte.
        $numeroPedido = str_pad((string) $pedido->id, 4, '0', STR_PAD_LEFT);
        $tipoLugar    = $tipoPedido === 'gastrobar' ? 'Gastrobar' : 'Restaurante';

        User::where('role', 'admin')->get()->each(function (User $admin) use ($incidente, $numeroPedido, $tipoLugar, $data) {
            Notificacion::create([
                'user_id' => $admin->id,
                'titulo'  => 'Nuevo incidente reportado',
                'mensaje' => "Se reportó un incidente ({$data['tipo']}) en el pedido #{$numeroPedido} de {$tipoLugar}.",
                'tipo'    => 'incidente',
                'leida'   => false,
            ]);
        });

        // TODO: además de la notificación interna, si más adelante quieren
        // push FCM al celular del admin, se agrega acá con el mismo patrón
        // que ya usás en los otros controladores.

        return response()->json([
            'message'   => 'Incidente reportado. El equipo de soporte revisará el caso.',
            'incidente' => $incidente,
        ], 201);
    }

    /**
     * Panel admin: listado de incidentes, filtrable por estado.
     */
    public function index(Request $request): JsonResponse
    {
        $incidentes = IncidentePedido::with(['pedido', 'reportante'])
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->latest()
            ->paginate(20);

        return response()->json($incidentes);
    }

    /**
     * Panel admin: resolver un incidente y decidir qué pasa con el pedido.
     */
    public function resolver(Request $request, IncidentePedido $incidente): JsonResponse
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

        return response()->json(['message' => 'Incidente resuelto correctamente.']);
    }
}
