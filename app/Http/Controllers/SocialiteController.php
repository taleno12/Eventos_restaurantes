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
            // Si falla, redirigir al login de React con error
            return redirect('http://localhost:5173/login?error=google_failed');
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

        // Generar token para React
        $token = $user->createToken('react-app')->plainTextToken;

        // Datos del usuario para pasar a React
        $userData = urlencode(json_encode([
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'role'            => $user->role,
            'departamento_id' => $user->departamento_id,
            'avatar'          => $user->avatar,
        ]));

        // Redirigir a React según rol — todo se queda en React
        return match($user->role) {
            'admin'       => redirect("http://localhost:5173/dashboard?token={$token}&user={$userData}"),
            'restaurante' => redirect("http://localhost:5173/restaurante/dashboard?token={$token}&user={$userData}"),
            default       => $user->departamento_id
                                ? redirect("http://localhost:5173/?token={$token}&user={$userData}")
                                : redirect("http://localhost:5173/seleccionar-departamento?token={$token}&user={$userData}"),
        };
    }
}
