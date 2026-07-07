<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMotorizadoActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'motorizado') {
            $estado = $user->estado ?? 'activo';

            if ($estado === 'suspendido') {
                // Revocar el token actual para que no pueda seguir usando la app
                $token = $user->currentAccessToken();
                if ($token) {
                    $token->delete();
                }

                return response()->json([
                    'message' => 'Tu cuenta de motorizado ha sido desactivada. Contacta al administrador para mas informacion.',
                ], 403);
            }
        }

        return $next($request);
    }
}
