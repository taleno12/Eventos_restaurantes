<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->email !== 'admin@turismo.ni') {
            return redirect('/');
        }

        return $next($request);
    }
}