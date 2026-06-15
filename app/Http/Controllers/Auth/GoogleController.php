<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private $allowedEmails = [
        'kevintaleno17@gmail.com',  // ← Tu Gmail
        'admin2@gmail.com',          // ← Segundo admin (cámbialo)
    ];

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!in_array($googleUser->email, $this->allowedEmails)) {
                return redirect('/login')->with('error', 'Acceso denegado. Este correo no tiene permisos.');
            }

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                ]);

                Auth::login($user);
                return redirect('/dashboard');
            }

            return redirect('/login')->with('error', 'Usuario no encontrado. Contacta al administrador.');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
