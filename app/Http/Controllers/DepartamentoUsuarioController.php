<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartamentoUsuarioController extends Controller
{
    /** Pantalla 1 — Elegir departamento */
    public function show()
    {
        if (Auth::user()->departamento_id && Auth::user()->municipio_id) {
            return redirect()->route('home');
        }

        $departamentos = Departamento::orderBy('nombre')->get();
        return view('auth.select-departamento', compact('departamentos'));
    }

    /** Guardar departamento y redirigir a municipio */
    public function save(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        User::where('id', Auth::id())->update([
            'departamento_id' => $request->departamento_id,
        ]);

        return redirect()->route('usuario.municipio.show');
    }

    /** Pantalla 2 — Elegir municipio */
    public function showMunicipio()
    {
        $user = Auth::user();

        if (!$user->departamento_id) {
            return redirect()->route('usuario.departamento.show');
        }

        if ($user->municipio_id) {
            return redirect()->route('home');
        }

        $municipios = Municipio::where('departamento_id', $user->departamento_id)
            ->orderBy('nombre')
            ->get();

        return view('auth.select-municipio', compact('municipios'));
    }

    /** Guardar municipio */
    public function saveMunicipio(Request $request)
    {
        $request->validate([
            'municipio_id' => 'required|exists:municipios,id',
        ]);

        User::where('id', Auth::id())->update([
            'municipio_id' => $request->municipio_id,
        ]);

        return redirect()->route('home')
            ->with('success', '¡Bienvenido! Ya tienes tu región configurada.');
    }

    /** Saltar todo */
    public function skip()
    {
        return redirect()->route('home');
    }
}
