<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Sin departamento → seleccionar primero
        if (!$user->departamento_id && $user->isUsuario()) {
            return redirect()->route('usuario.departamento.show');
        }

        // Redirigir según rol
        return match($user->role) {
            'admin'       => redirect()->route('dashboard'),
            'restaurante' => redirect()->route('restaurante.dashboard'),
            'gastrobar'   => redirect()->route('gastrobar.dashboard'),
            default       => redirect()->route('home'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::Guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
