<?php

namespace App\Http\Controllers;

use App\Models\NegociacionPedido;
use App\Models\MensajeNegociacion;
use App\Models\Notificacion;
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
                    Pedido::class          => ['restaurante:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                    PedidoGastrobar::class => ['gastrobar:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                ]);
            },
        ]);

        return response()->json(['negociacion' => $negociacion]);
    }

    /**
     * ✅ AGREGADO: para un pedido dado, devuelve el estado de TODAS las
     * negociaciones abiertas con los distintos motorizados contactados
     * (una por cada motorizado con quien se negoció ese pedido).
     *
     * El panel del restaurante/gastrobar usa esto para pintar en verde,
     * de un vistazo, las tarjetas de los motorizados que ya respondieron
     * o ya aceptaron — sin tener que abrir el chat de cada uno.
     *
     * "Respondió" se define como: hay al menos un mensaje enviado POR el
     * motorizado en esa negociación (mensaje de texto, contraoferta, o
     * aceptación). Si aceptado_motorizado es true pero todavía no mandó
     * ningún mensaje (por ejemplo aceptó la primera propuesta sin
     * escribir nada), también cuenta como respuesta.
     *
     * ✅ FIX: la relación 'mensajes' se cargaba pidiendo la columna
     * "negociacion_pedido_id", que no existe en la tabla
     * mensajes_negociacion (la FK real se llama "negociacion_id").
     * Eso rompía la consulta con un 500 (SQLSTATE 42703 - Undefined
     * column) y el JS del panel nunca recibía datos, por eso ninguna
     * card se pintaba en verde aunque el motorizado ya hubiera
     * respondido.
     */
    public function porPedido(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pedido_tipo' => ['required', Rule::in(array_keys(self::TIPOS_PEDIDO))],
            'pedido_id'   => ['required', 'integer'],
        ]);

        $user = $request->user();
        $pedidoClass = self::TIPOS_PEDIDO[$data['pedido_tipo']];
        $pedido = $pedidoClass::findOrFail($data['pedido_id']);

        // Mismo chequeo de propiedad que en store(): solo el dueño del
        // negocio (o admin) puede ver el estado de estas negociaciones.
        $this->verificarPropietarioDelPedido($user, $pedido);

        $negociaciones = NegociacionPedido::where('pedido_type', $pedidoClass)
            ->where('pedido_id', $pedido->id)
            ->with(['mensajes:id,negociacion_id,user_id'])
            ->get();

        $resultado = $negociaciones->map(function (NegociacionPedido $n) {
            $motorizadoRespondio = $n->mensajes->contains(
                fn (MensajeNegociacion $m) => $m->user_id === $n->motorizado_id
            );

            return [
                'negociacion_id'           => $n->id,
                'motorizado_id'            => $n->motorizado_id,
                'estado'                   => $n->estado,
                'aceptado_dueno'           => (bool) $n->aceptado_dueno,
                'aceptado_motorizado'      => (bool) $n->aceptado_motorizado,
                'motorizado_respondio'     => $motorizadoRespondio,
                'ultima_tarifa_motorizado' => $n->tarifa_propuesta_motorizado,
                'tarifa_acordada'          => $n->tarifa_acordada,
            ];
        })->values();

        return response()->json(['negociaciones' => $resultado]);
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
                        Pedido::class          => ['restaurante:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                        PedidoGastrobar::class => ['gastrobar:id,nombre', 'items.plato:id,nombre', 'user:id,name,telefono'],
                    ]);
                },
            ]),
        ]);
    }

    /**
     * El motorizado avisa manualmente que ya llego al punto de entrega.
     */
    public function avisarLlegada(Request $request, NegociacionPedido $negociacion): JsonResponse
    {
        $user = $request->user();

        abort_unless(
            $user->id === $negociacion->motorizado_id,
            403,
            'Solo el motorizado asignado puede avisar la llegada.'
        );

        if ($negociacion->estado !== 'aceptado') {
            return response()->json([
                'message' => 'Esta negociacion todavia no tiene una tarifa acordada.',
            ], 422);
        }

        $pedido = $negociacion->pedido()->with('user')->first();

        if (!$pedido) {
            return response()->json([
                'message' => 'No se encontro el pedido asociado a esta negociacion.',
            ], 404);
        }

        $cliente = $pedido->user;

        if (!$cliente) {
            return response()->json([
                'message' => 'No se encontro el cliente dueño de este pedido.',
            ], 404);
        }

        $titulo  = 'Tu pedido ya esta aqui';
        $mensaje = "El motorizado {$user->name} llego a tu punto de entrega.";

        // 1) Registro para la campanita (aparece siempre, llegue o no el push)
        Notificacion::create([
            'tipo'         => 'motorizado_llego',
            'titulo'       => $titulo,
            'mensaje'      => $mensaje,
            'user_id'      => $cliente->id,
            'leida'        => false,
            'fecha_evento' => now(),
        ]);

        // 2) Push individual, solo al celular de ese cliente
        enviarNotificacionFCMAUsuario($cliente->fcm_token, $titulo, $mensaje);

        return response()->json([
            'message' => 'Se avisó al cliente que llegaste.',
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
