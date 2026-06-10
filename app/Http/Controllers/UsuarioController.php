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

        $totalUsuarios = User::count();
        $totalAdmins   = User::where('role', 'admin')->count();
        $totalRestaurantes = User::where('role', 'restaurante')->count();
        $totalClientes = User::whereNotIn('role', ['admin', 'restaurante'])->count();

        return view('usuarios.index', compact(
            'usuarios',
            'totalUsuarios',
            'totalAdmins',
            'totalRestaurantes',
            'totalClientes'
        ));
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
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,restaurante,user',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    // ── TOGGLE ACTIVO/INACTIVO ────────────────────────────────────
    public function toggle(User $user)
    {
        // Usamos email_verified_at como indicador de activo/inactivo
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $msg = 'Usuario desactivado.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $msg = 'Usuario activado.';
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
