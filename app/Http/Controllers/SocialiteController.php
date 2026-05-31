<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'No se pudo autenticar con Google.');
    }

    // Buscar por google_id primero
    $user = User::where('google_id', $googleUser->getId())->first();

    if (!$user) {
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
            ]);
        } else {
            // Usuario nuevo — verificar si su email está registrado como restaurante
            $restaurante = \App\Models\Restaurante::where('email', $googleUser->getEmail())->first();

            $user = User::create([
                'name'           => $googleUser->getName(),
                'email'          => $googleUser->getEmail(),
                'google_id'      => $googleUser->getId(),
                'avatar'         => $googleUser->getAvatar(),
                'role'           => $restaurante ? 'restaurante' : 'usuario',
                'restaurante_id' => $restaurante?->id,
            ]);
        }
    }

    // Si ya existe pero no tiene rol de restaurante y su email coincide con uno
    if ($user->role === 'usuario') {
        $restaurante = \App\Models\Restaurante::where('email', $user->email)->first();
        if ($restaurante) {
            $user->update([
                'role'           => 'restaurante',
                'restaurante_id' => $restaurante->id,
            ]);
        }
    }

    Auth::login($user, true);

    // Redirigir según rol
    return match($user->role) {
        'admin'       => redirect()->route('dashboard'),
        'restaurante' => redirect()->route('restaurante.dashboard'),
        default       => $user->departamento_id
                            ? redirect()->route('home')
                            : redirect()->route('usuario.departamento.show'),
    };
}
}