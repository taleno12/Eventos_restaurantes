<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEntidadActiva
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            $entidad = null;

            if ($user->isRestaurante() && $user->restaurante_id) {
                $entidad = $user->restaurante;
            } elseif ($user->isGastrobar() && $user->gastrobar_id) {
                $entidad = $user->gastrobar;
            }

            if ($entidad && !$entidad->activo) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador para más información.');
            }
        }

        return $next($request);
    }
}
