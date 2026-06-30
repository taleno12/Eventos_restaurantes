<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthTelefonoController extends Controller
{
    // ───────────────────────────────
    // REGISTRO
    // ───────────────────────────────
    public function showRegisterForm()
    {
        return view('auth.registro-telefono');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'telefono'            => 'required|string|max:20|unique:users,telefono',
            'password'            => 'required|string|min:8|confirmed',
            'pregunta_seguridad'  => 'required|string|max:255',
            'respuesta_seguridad' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name'                => $request->name,
            'telefono'            => $request->telefono,
            'email'               => $request->telefono . '@telefono.gastronicaragua.local',
            'password'            => Hash::make($request->password),
            'pregunta_seguridad'  => $request->pregunta_seguridad,
            'respuesta_seguridad' => Hash::make(strtolower(trim($request->respuesta_seguridad))),
            'role'                => 'usuario',
        ]);

        Auth::login($user, true);

        return $user->departamento_id
            ? redirect()->route('home')
            : redirect()->route('usuario.departamento.show');
    }

    // ───────────────────────────────
    // LOGIN
    // ───────────────────────────────
    public function showLoginForm()
    {
        return view('auth.login-telefono');
    }

    public function login(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('telefono', $request->telefono)->first();

        if (!$user || !$user->password || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['telefono' => 'Telefono o contrasena incorrectos.'])->withInput();
        }

        Auth::login($user, true);

        return match($user->role) {
            'admin'       => redirect()->route('dashboard'),
            'restaurante' => redirect()->route('restaurante.dashboard'),
            'gastrobar'   => redirect()->route('gastrobar.dashboard'),
            default       => $user->departamento_id
                                ? redirect()->route('home')
                                : redirect()->route('usuario.departamento.show'),
        };
    }

    // ───────────────────────────────
    // RECUPERAR CONTRASENA - Paso 1: pedir telefono
    // ───────────────────────────────
    public function showForgotForm()
    {
        return view('auth.olvide-telefono');
    }

    public function buscarPregunta(Request $request)
    {
        $request->validate(['telefono' => 'required|string']);

        $user = User::where('telefono', $request->telefono)->first();

        if (!$user || !$user->pregunta_seguridad) {
            return back()->withErrors(['telefono' => 'No encontramos una cuenta con ese telefono.'])->withInput();
        }

        return view('auth.responder-pregunta', [
            'telefono' => $user->telefono,
            'pregunta' => $user->pregunta_seguridad,
        ]);
    }

    // ───────────────────────────────
    // RECUPERAR CONTRASENA - Paso 2: validar respuesta y cambiar password
    // ───────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'telefono'            => 'required|string',
            'respuesta_seguridad' => 'required|string',
            'password'            => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('telefono', $request->telefono)->first();

        if (!$user || !Hash::check(strtolower(trim($request->respuesta_seguridad)), $user->respuesta_seguridad)) {
            return back()->withErrors(['respuesta_seguridad' => 'Respuesta incorrecta.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('login.telefono')->with('success', 'Contrasena actualizada. Ya puedes iniciar sesion.');
    }
}
