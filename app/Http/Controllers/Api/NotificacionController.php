<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificacionController extends Controller
{
    /**
     * Lista las notificaciones del usuario autenticado, mas recientes primero.
     */
    public function index(Request $request): JsonResponse
    {
        $notificaciones = Notificacion::delUsuario($request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($notificaciones);
    }

    /**
     * Cantidad de notificaciones no leidas, para el badge de la campanita.
     */
    public function noLeidasCount(Request $request): JsonResponse
    {
        $count = Notificacion::delUsuario($request->user()->id)->noLeidas()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Marca una notificacion especifica como leida.
     */
    public function marcarLeida(Request $request, Notificacion $notificacion): JsonResponse
    {
        abort_unless(
            $notificacion->user_id === $request->user()->id,
            403,
            'No tenes acceso a esta notificacion.'
        );

        $notificacion->update(['leida' => true]);

        return response()->json(['message' => 'Notificacion marcada como leida.']);
    }

    /**
     * Marca todas las notificaciones del usuario como leidas de una vez.
     */
    public function marcarTodasLeidas(Request $request): JsonResponse
    {
        Notificacion::delUsuario($request->user()->id)
            ->noLeidas()
            ->update(['leida' => true]);

        return response()->json(['message' => 'Todas las notificaciones marcadas como leidas.']);
    }
}
