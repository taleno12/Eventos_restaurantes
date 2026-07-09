<?php

namespace App\Http\Controllers;

use App\Models\NegociacionPedido;
use App\Models\MensajeNegociacion;
use App\Models\Pedido;
use App\Models\PedidoGastrobar;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class NegociacionController extends Controller
{
    /**
     * Mapea el tipo de pedido recibido (string corto) a la clase del modelo real.
     * Evita que el cliente mande namespaces completos por la API.
     */
    private const TIPOS_PEDIDO = [
        'restaurante' => Pedido::class,
        'gastrobar'   => PedidoGastrobar::class,
    ];

    /**
     * Inicia una negociacion: el dueño (restaurante/gastrobar) elige un motorizado
     * para un pedido especifico y opcionalmente propone una tarifa de envio.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pedido_tipo'             => ['required', Rule::in(array_keys(self::TIPOS_PEDIDO))],
            'pedido_id'               => ['required', 'integer'],
            'motorizado_id'           => ['required', 'exists:users,id'],
            'tarifa_propuesta_dueno'  => ['nullable', 'numeric', 'min:0'],
            'mensaje'                 => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $pedidoClass = self::TIPOS_PEDIDO[$data['pedido_tipo']];
        $pedido = $pedidoClass::findOrFail($data['pedido_id']);

        // Verificar que el pedido pertenece al negocio del usuario autenticado
        $this->verificarPropietarioDelPedido($user, $pedido);

        // Si el pedido ya tiene un motorizado asignado, no se permite iniciar
        // una nueva negociacion (evita reasignarlo por error a otro motorizado)
        if (!empty($pedido->motorizado_id)) {
            return response()->json([
                'message' => 'Este pedido ya tiene un motorizado asignado y no se puede reasignar.',
            ], 422);
        }

        // Evitar duplicar negociaciones activas para el mismo pedido
        $existente = NegociacionPedido::where('pedido_type', $pedidoClass)
            ->where('pedido_id', $pedido->id)
            ->where('motorizado_id', $data['motorizado_id'])
            ->whereIn('estado', ['pendiente', 'aceptado'])
            ->first();

        if ($existente) {
            return response()->json([
                'message' => 'Ya existe una negociacion activa con este motorizado para este pedido.',
                'negociacion' => $existente->load('mensajes'),
            ], 200);
        }

        $negociacion = NegociacionPedido::create([
            'pedido_type'            => $pedidoClass,
            'pedido_id'              => $pedido->id,
            'motorizado_id'          => $data['motorizado_id'],
            'iniciado_por_id'        => $user->id,
            'estado'                 => 'pendiente',
            'tarifa_propuesta_dueno' => $data['tarifa_propuesta_dueno'] ?? null,
        ]);

        // Si mandaron un mensaje inicial, lo guardamos ya como el primer mensaje del chat
        if (!empty($data['mensaje'])) {
            $negociacion->mensajes()->create([
                'user_id'          => $user->id,
                'mensaje'          => $data['mensaje'],
                'tarifa_propuesta' => $data['tarifa_propuesta_dueno'] ?? null,
            ]);
        }

        return response()->json([
            'negociacion' => $negociacion->load(['mensajes', 'motorizado:id,name,vehiculo,placa,avatar']),
        ], 201);
    }

    /**
     * Muestra una negociacion con todos sus mensajes (para polling desde el chat).
     */
    public function show(Request $request, NegociacionPedido $negociacion): JsonResponse
    {
        $this->verificarParticipante($request->user(), $negociacion);

        $negociacion->load([
            'mensajes' => fn ($q) => $q->orderBy('created_at'),
            'motorizado:id,name,vehiculo,placa,avatar',
            'iniciadoPor:id,name',
            'pedido' => function ($morphTo) {
                $morphTo->morphWith([
                    // ✅ AGREGADO: 'user:id,name,telefono' — el cliente que hizo
                    // el pedido, para que el motorizado sepa a quién le entrega
                    // (y su teléfono, para el aviso por WhatsApp más adelante).
                    Pedido::class          => ['restaurante:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                    PedidoGastrobar::class => ['gastrobar:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                ]);
            },
        ]);

        return response()->json(['negociacion' => $negociacion]);
    }

    /**
     * Envia un mensaje dentro de una negociacion. Puede incluir una nueva
     * propuesta de tarifa, que se guarda tanto en el mensaje como en la
     * negociacion (segun quien lo envie).
     */
    public function enviarMensaje(Request $request, NegociacionPedido $negociacion): JsonResponse
    {
        $this->verificarParticipante($request->user(), $negociacion);

        $data = $request->validate([
            'mensaje'          => ['required_without:tarifa_propuesta', 'nullable', 'string', 'max:500'],
            'tarifa_propuesta' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = $request->user();

        $mensaje = $negociacion->mensajes()->create([
            'user_id'          => $user->id,
            'mensaje'          => $data['mensaje'] ?? null,
            'tarifa_propuesta' => $data['tarifa_propuesta'] ?? null,
        ]);

        // Si viene una nueva tarifa, actualizamos la propuesta correspondiente
        // y reseteamos las aceptaciones (porque cambio el numero sobre la mesa)
        if (!empty($data['tarifa_propuesta'])) {
            if ($user->id === $negociacion->motorizado_id) {
                $negociacion->tarifa_propuesta_motorizado = $data['tarifa_propuesta'];
                $negociacion->aceptado_motorizado = false;
                $negociacion->aceptado_dueno = false;
            } else {
                $negociacion->tarifa_propuesta_dueno = $data['tarifa_propuesta'];
                $negociacion->aceptado_dueno = false;
                $negociacion->aceptado_motorizado = false;
            }
            $negociacion->save();
        }

        return response()->json(['mensaje' => $mensaje], 201);
    }

    /**
     * Marca que el usuario autenticado (dueño o motorizado) acepta la tarifa
     * actualmente propuesta. Si ambas partes aceptaron, cierra la negociacion.
     */
    public function aceptar(Request $request, NegociacionPedido $negociacion): JsonResponse
    {
        $this->verificarParticipante($request->user(), $negociacion);

        $user = $request->user();

        if ($user->id === $negociacion->motorizado_id) {
            $negociacion->aceptado_motorizado = true;
        } else {
            $negociacion->aceptado_dueno = true;
        }
        $negociacion->save();

        $cerrada = $negociacion->verificarAcuerdo();

        // Si se cerro el acuerdo, actualizamos el pedido con el motorizado y el costo de envio
        if ($cerrada) {
            $pedido = $negociacion->pedido;
            $pedido->update([
                'motorizado_id' => $negociacion->motorizado_id,
                'costo_envio'   => $negociacion->tarifa_acordada,
            ]);
        }

        return response()->json([
            'negociacion' => $negociacion->fresh(['mensajes', 'motorizado:id,name,vehiculo,placa']),
            'cerrada'     => $cerrada,
        ]);
    }

    /**
     * El motorizado asignado marca el pedido como entregado al cliente.
     */
    public function entregar(Request $request, NegociacionPedido $negociacion): JsonResponse
    {
        $user = $request->user();

        abort_unless(
            $user->id === $negociacion->motorizado_id,
            403,
            'Solo el motorizado asignado puede marcar este pedido como entregado.'
        );

        if ($negociacion->estado !== 'aceptado') {
            return response()->json([
                'message' => 'Esta negociacion todavia no tiene una tarifa acordada.',
            ], 422);
        }

        $pedido = $negociacion->pedido;

        if (!$pedido) {
            return response()->json([
                'message' => 'No se encontro el pedido asociado a esta negociacion.',
            ], 404);
        }

        if ($pedido->estado === 'entregado') {
            return response()->json([
                'message' => 'Este pedido ya habia sido marcado como entregado.',
            ], 422);
        }

        $pedido->update(['estado' => 'entregado']);

        return response()->json([
            'message'       => 'Pedido marcado como entregado.',
            'pedido_estado' => 'entregado',
            'negociacion'   => $negociacion->fresh([
                'mensajes',
                'motorizado:id,name,vehiculo,placa',
                'pedido' => function ($morphTo) {
                    $morphTo->morphWith([
                        // ✅ AGREGADO: mismo user:id,name,telefono que en show(),
                        // para que la tarjeta de cliente no desaparezca al
                        // recargar la negociacion luego de marcar entregado.
                        Pedido::class          => ['restaurante:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                        PedidoGastrobar::class => ['gastrobar:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                    ]);
                },
            ]),
        ]);
    }

    /**
     * Lista las negociaciones del usuario autenticado, sea dueño (via el pedido)
     * o motorizado. Usado tanto en el panel web como en la app Flutter (modo motorizado).
     */
    public function misNegociaciones(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = NegociacionPedido::with([
            'motorizado:id,name,vehiculo,placa,avatar',
            'iniciadoPor:id,name',
            'pedido' => function ($morphTo) {
                $morphTo->morphWith([
                    Pedido::class          => ['restaurante:id,nombre'],
                    PedidoGastrobar::class => ['gastrobar:id,nombre'],
                ]);
            },
        ])->latest();

        if ($user->isMotorizado()) {
            $query->where('motorizado_id', $user->id);
        } else {
            $query->where('iniciado_por_id', $user->id);
        }

        return response()->json(['negociaciones' => $query->get()]);
    }

    /**
     * Verifica que el pedido pertenece al restaurante/gastrobar del usuario autenticado.
     */
    private function verificarPropietarioDelPedido($user, $pedido): void
    {
        $esDueno = ($user->isRestaurante() && $user->restaurante_id === $pedido->restaurante_id)
            || ($user->isGastrobar() && $user->gastrobar_id === $pedido->gastrobar_id);

        abort_unless($esDueno || $user->isAdmin(), 403, 'No tenes permiso sobre este pedido.');
    }

    /**
     * Verifica que el usuario autenticado sea parte de la negociacion
     * (el motorizado o quien la inicio).
     */
    private function verificarParticipante($user, NegociacionPedido $negociacion): void
    {
        $esParticipante = $user->id === $negociacion->motorizado_id
            || $user->id === $negociacion->iniciado_por_id;

        abort_unless($esParticipante || $user->isAdmin(), 403, 'No tenes acceso a esta negociacion.');
    }
}
