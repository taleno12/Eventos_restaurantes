<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('name', 'like', "%$b%")
                  ->orWhere('email', 'like', "%$b%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $usuarios = $query->latest()->paginate(15)->withQueryString();

        $totalUsuarios     = User::count();
        $totalAdmins       = User::where('role', 'admin')->count();
        $totalRestaurantes = User::where('role', 'restaurante')->count();
        $totalGastrobares  = User::where('role', 'gastrobar')->count();
        $totalClientes     = User::where('role', 'usuario')->count();
        $totalHoy          = User::whereDate('created_at', today())->count();

        return view('usuarios.index', compact(
            'usuarios',
            'totalUsuarios',
            'totalAdmins',
            'totalRestaurantes',
            'totalGastrobares',
            'totalClientes',
            'totalHoy'
        ));
    }

    // ── CREATE ────────────────────────────────────────────────────
    public function create()
    {
        return view('usuarios.create');
    }

    // ── STORE ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,restaurante,gastrobar,usuario',
            'telefono' => 'nullable|string|max:20',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'Ingresa un correo electrónico válido.',
            'email.unique'       => 'Este correo ya está registrado en el sistema.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role.required'      => 'Debes seleccionar un rol.',
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'telefono'          => $request->telefono,
            'email_verified_at' => now(), // activo por defecto al crearlo desde admin
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario creado correctamente.');
    }

    // ── SHOW ──────────────────────────────────────────────────────
    public function show(User $user)
    {
        return view('usuarios.show', compact('user'));
    }

    // ── EDIT ──────────────────────────────────────────────────────
    public function edit(User $user)
    {
        return view('usuarios.edit', compact('user'));
    }

    // ── UPDATE ────────────────────────────────────────────────────
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,restaurante,gastrobar,usuario',
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'Ingresa un correo electrónico válido.',
            'email.unique'       => 'Este correo ya está en uso por otro usuario.',
            'role.required'      => 'Debes seleccionar un rol.',
            'password.min'       => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $datos = [
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'telefono' => $request->telefono,
        ];

        // Solo actualizar contraseña si se ingresó una nueva
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $user->update($datos);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    // ── TOGGLE ACTIVO/INACTIVO ────────────────────────────────────
    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes suspender tu propia cuenta.');
        }

        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $msg = 'Usuario suspendido correctamente.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $msg = 'Usuario activado correctamente.';
        }

        return back()->with('success', $msg);
    }

    // ── DESTROY ───────────────────────────────────────────────────
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario eliminado correctamente.');
    }
}
