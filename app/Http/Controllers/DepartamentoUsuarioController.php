<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartamentoUsuarioController extends Controller
{
    /** Mostrar pantalla de selección */
    public function show()
    {
        if (Auth::user()->departamento_id) {
            return redirect()->route('home');
        }

        $departamentos = Departamento::orderBy('nombre')->get();
        return view('auth.select-departamento', compact('departamentos'));
    }

    /** Guardar departamento elegido */
    public function save(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        Auth::user()->update([
            'departamento_id' => $request->departamento_id,
        ]);

        return redirect()->route('home')
            ->with('success', '¡Bienvenido! Ya tienes tu región configurada.');
    }

    /** Saltar la selección */
    public function skip()
    {
        return redirect()->route('home');
    }
}