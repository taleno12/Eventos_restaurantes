<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoGastrobar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MotorizadoController extends Controller
{
    /**
     * Devuelve los motorizados disponibles cerca del negocio del usuario autenticado
     * (restaurante o gastrobar). Radio configurable via query param ?radio=5
     */
    public function disponiblesCerca(Request $request): JsonResponse
    {
        $user = $request->user();

        // Determinar el negocio del usuario (restaurante o gastrobar) y su ubicacion
        $negocio = null;
        if ($user->isRestaurante() && $user->restaurante) {
            $negocio = $user->restaurante;
        } elseif ($user->isGastrobar() && $user->gastrobar) {
            $negocio = $user->gastrobar;
        }

        if (!$negocio || !$negocio->latitud || !$negocio->longitud) {
            return response()->json([
                'message' => 'Este negocio no tiene una ubicacion registrada todavia.',
            ], 422);
        }

        $radioKm = (float) $request->query('radio', 5);

        $motorizados = User::motorizadosCerca(
            (float) $negocio->latitud,
            (float) $negocio->longitud,
            $radioKm
        )->get(['id', 'name', 'telefono', 'vehiculo', 'placa', 'avatar', 'lat', 'lng']);

        return response()->json([
            'negocio' => [
                'lat' => $negocio->latitud,
                'lng' => $negocio->longitud,
            ],
            'radio_km' => $radioKm,
            'motorizados' => $motorizados,
        ]);
    }

    /**
     * ✅ AGREGADO: resumen de ganancias del motorizado autenticado para
     * el dia de HOY. Se define "ganado" como la suma de costo_envio de
     * los pedidos (restaurante + gastrobar) que este motorizado entrego
     * hoy (estado = 'entregado'), no de negociaciones simplemente
     * acordadas — asi refleja trabajo realmente completado.
     *
     * No existe un campo especifico "entregado_at" en las tablas de
     * pedidos, asi que se usa updated_at junto con estado = 'entregado'
     * como aproximacion del momento de entrega. Si en el futuro se agrega
     * un campo dedicado (ej. entregado_at), reemplazar aqui el filtro por
     * ese campo para mayor precision.
     *
     * NOTA IMPORTANTE: el corte de "hoy" usa la zona horaria configurada
     * en config/app.php ('timezone'). Si esa configuracion no es
     * America/Managua, el corte de dia puede no coincidir con el dia real
     * del motorizado (mismo tipo de problema que ya se corrigio del lado
     * Flutter con .toLocal() en _esHoy()). Verificar ese valor si las
     * ganancias de "hoy" aparecen corridas de horario.
     */
    public function gananciasHoy(Request $request): JsonResponse
    {
        $user = $request->user();

        $inicioHoy = now()->startOfDay();
        $finHoy    = now()->endOfDay();

        $pedidosRestaurante = Pedido::where('motorizado_id', $user->id)
            ->where('estado', 'entregado')
            ->whereBetween('updated_at', [$inicioHoy, $finHoy])
            ->with('restaurante:id,nombre')
            ->get();

        $pedidosGastrobar = PedidoGastrobar::where('motorizado_id', $user->id)
            ->where('estado', 'entregado')
            ->whereBetween('updated_at', [$inicioHoy, $finHoy])
            ->with('gastrobar:id,nombre')
            ->get();

        $entregas = collect();

        foreach ($pedidosRestaurante as $p) {
            $entregas->push([
                'id'             => $p->id,
                'tipo'           => 'restaurante',
                'negocio_nombre' => $p->restaurante->nombre ?? 'Restaurante',
                'costo_envio'    => (float) ($p->costo_envio ?? 0),
                'hora'           => $p->updated_at->format('H:i'),
                'ts'             => $p->updated_at->timestamp,
            ]);
        }

        foreach ($pedidosGastrobar as $p) {
            $entregas->push([
                'id'             => $p->id,
                'tipo'           => 'gastrobar',
                'negocio_nombre' => $p->gastrobar->nombre ?? 'Gastrobar',
                'costo_envio'    => (float) ($p->costo_envio ?? 0),
                'hora'           => $p->updated_at->format('H:i'),
                'ts'             => $p->updated_at->timestamp,
            ]);
        }

        // Mas reciente primero, para que el motorizado vea su ultima entrega arriba
        $entregas = $entregas->sortByDesc('ts')->values()->map(function ($e) {
            unset($e['ts']);
            return $e;
        });

        return response()->json([
            'fecha'              => now()->toDateString(),
            'total_ganado'       => (float) $entregas->sum('costo_envio'),
            'cantidad_entregas'  => $entregas->count(),
            'entregas'           => $entregas,
        ]);
    }

    /**
     * Panel admin: listado de motorizados con boton activar/desactivar.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'motorizado')->latest();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(fn($q) => $q->where('name', 'like', "%$b%")
                                       ->orWhere('telefono', 'like', "%$b%"));
        }

        $motorizados = $query->paginate(15);

        return view('motorizados.index', compact('motorizados'));
    }
}
