<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    /**
     * ✅ AGREGADO: guarda o actualiza el fcm_token del dispositivo del
     * usuario autenticado. Flutter llama esto al hacer login y cada vez
     * que Firebase rota el token (onTokenRefresh), para que el backend
     * siempre tenga el token vigente y pueda mandarle notificaciones push
     * individuales (ej: "Tu pedido ya está aquí").
     */
    public function actualizarFcmToken(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required|string|max:255',
        ]);

        $request->user()->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json(['message' => 'Token FCM actualizado.']);
    }
}
