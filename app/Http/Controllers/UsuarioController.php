<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $rolActivo = $request->get('role', '');

        // ── Métricas siempre ────────────────────────────────────
        $totalUsuarios     = User::whereIn('role', ['admin','restaurante','gastrobar'])->count();
        $totalAdmins       = User::where('role', 'admin')->count();
        $totalRestaurantes = User::where('role', 'restaurante')->count();
        $totalGastrobares  = User::where('role', 'gastrobar')->count();
        $totalHoy          = User::whereIn('role', ['admin','restaurante','gastrobar'])
                                 ->whereDate('created_at', today())->count();
        $totalTrabajadores = Trabajador::count();

        // ── Usuarios ────────────────────────────────────────────
        $queryU = User::whereIn('role', ['admin','restaurante','gastrobar'])
                      ->with(['restaurante', 'gastrobar'])  // ← CARGAR RELACIONES
                      ->latest();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $queryU->where(fn($q) => $q->where('name','like',"%$b%")->orWhere('email','like',"%$b%"));
        }
        if ($rolActivo && $rolActivo !== 'personal') {
            $queryU->where('role', $rolActivo);
        }
        $usuarios = $queryU->paginate(15);

        // ── Trabajadores (solo cuando role=personal) ────────────
        $queryT = Trabajador::with('departamentos')->latest();
        if ($rolActivo === 'personal' && $request->filled('buscar')) {
            $b = $request->buscar;
            $queryT->where(fn($q) => $q->where('nombre','like',"%$b%")
                                       ->orWhere('apellido','like',"%$b%")
                                       ->orWhere('cedula','like',"%$b%")
                                       ->orWhere('cargo','like',"%$b%"));
        }
        $trabajadores = $queryT->paginate(15);

        return view('usuarios.index', compact(
            'usuarios','trabajadores',
            'totalUsuarios','totalAdmins','totalRestaurantes',
            'totalGastrobares','totalHoy','totalTrabajadores'
        ));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,restaurante,gastrobar',
            'telefono' => 'nullable|string|max:20',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'telefono' => $request->telefono,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $usuario)
    {
        $user = $usuario;
        return view('usuarios.show', compact('usuario', 'user'));
    }

    public function edit(User $usuario)
    {
        $user = $usuario;
        return view('usuarios.edit', compact('usuario', 'user'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$usuario->id,
            'role'     => 'required|in:admin,restaurante,gastrobar',
            'telefono' => 'nullable|string|max:20',
        ]);

        $usuario->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'telefono' => $request->telefono,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }

    public function toggle(User $usuario)
    {
        $nuevo = ($usuario->estado ?? 'activo') === 'activo' ? 'suspendido' : 'activo';
        $usuario->update(['estado' => $nuevo]);
        $msg = $nuevo === 'suspendido' ? 'Usuario suspendido.' : 'Usuario reactivado.';
        return redirect()->back()->with('success', $msg);
    }
}
