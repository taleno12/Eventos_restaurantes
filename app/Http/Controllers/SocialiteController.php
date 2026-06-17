<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurante;
use App\Models\Gastrobar;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // Emails que tendrán rol de admin automáticamente
    private $adminEmails = [
        'kevintaleno17@gmail.com',
        '15ulisesramirez@gmail.com',
    ];

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['google' => 'Error al iniciar sesion con Google.']);
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
                // Usuario nuevo — verificar si su email esta registrado como restaurante o gastrobar
                $restaurante = Restaurante::where('email', $googleUser->getEmail())->first();
                $gastrobar   = Gastrobar::where('email', $googleUser->getEmail())->first();

                // Si el email está en la lista de admins, asignar rol admin
                $role = 'usuario';
                if (in_array($googleUser->getEmail(), $this->adminEmails)) {
                    $role = 'admin';
                } elseif ($restaurante) {
                    $role = 'restaurante';
                } elseif ($gastrobar) {
                    $role = 'gastrobar';
                }

                $user = User::create([
                    'name'           => $googleUser->getName(),
                    'email'          => $googleUser->getEmail(),
                    'google_id'      => $googleUser->getId(),
                    'avatar'         => $googleUser->getAvatar(),
                    'role'           => $role,
                    'restaurante_id' => $restaurante?->id,
                    'gastrobar_id'   => $gastrobar?->id,
                ]);
            }
        }

        // Si ya existe pero no tiene rol de admin y su email está en la lista
        if (in_array($user->email, $this->adminEmails) && $user->role !== 'admin') {
            $user->update(['role' => 'admin']);
        }

        // Si ya existe pero no tiene rol de restaurante/gastrobar y su email coincide con uno
        if ($user->role === 'usuario') {
            $restaurante = Restaurante::where('email', $user->email)->first();
            $gastrobar   = Gastrobar::where('email', $user->email)->first();

            if ($restaurante) {
                $user->update([
                    'role'           => 'restaurante',
                    'restaurante_id' => $restaurante->id,
                ]);
            } elseif ($gastrobar) {
                $user->update([
                    'role'         => 'gastrobar',
                    'gastrobar_id' => $gastrobar->id,
                ]);
            }
        }

        Auth::login($user, true);

        // Redirigir segun rol y estado del usuario
        return match($user->role) {
            'admin'       => redirect()->route('dashboard'),
            'restaurante' => redirect()->route('restaurante.dashboard'),
            'gastrobar'   => redirect()->route('gastrobar.dashboard'),
            default       => $user->departamento_id
                                ? redirect()->route('home')
                                : redirect()->route('usuario.departamento.show'),
        };
    }
}
