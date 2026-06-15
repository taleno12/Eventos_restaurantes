<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado y tiene rol admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Si no es admin, redirigir al home
        return redirect('/');
    }
}
