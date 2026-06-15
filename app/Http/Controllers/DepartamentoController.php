<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
{
    /**
     * Mostrar lista de departamentos
     */
    public function index()
    {
        $departamentos = Departamento::withCount(['restaurantes', 'gastrobares'])->get();
        return view('departamentos.index', compact('departamentos'));
    }

    /**
     * Formulario para crear un nuevo departamento
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->email !== 'admin@turismo.ni') {
            abort(403);
        }
        return view('departamentos.create');
    }

    /**
     * Guardar el departamento y sus municipios iniciales
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:departamentos,nombre|max:255',
        ]);

        $departamento = Departamento::create(['nombre' => $request->nombre]);

        // Procesar municipios separados por coma
        if ($request->filled('municipios_lista')) {
            $municipios = array_filter(
                array_map('trim', explode(',', $request->municipios_lista))
            );

            foreach ($municipios as $nombreMunicipio) {
                if ($nombreMunicipio !== '') {
                    Municipio::create([
                        'nombre'          => $nombreMunicipio,
                        'departamento_id' => $departamento->id,
                    ]);
                }
            }
        }

        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento creado correctamente.');
    }

    /**
     * Redirige al index (no se necesita vista individual)
     */
    public function show(Departamento $departamento)
    {
        return redirect()->route('departamentos.index');
    }

    /**
     * Formulario para editar un departamento
     */
    public function edit(Departamento $departamento)
    {
        if (Auth::check() && Auth::user()->email !== 'admin@turismo.ni') {
            abort(403);
        }
        return view('departamentos.edit', compact('departamento'));
    }

    /**
     * Actualizar el departamento
     */
    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|max:255|unique:departamentos,nombre,' . $departamento->id,
        ]);

        $departamento->update(['nombre' => $request->nombre]);

        // Si se pasan municipios adicionales en el update, también se crean
        if ($request->filled('municipios_lista')) {
            $municipios = array_filter(
                array_map('trim', explode(',', $request->municipios_lista))
            );

            foreach ($municipios as $nombreMunicipio) {
                if ($nombreMunicipio !== '') {
                    // Solo crea si no existe ya en este departamento
                    Municipio::firstOrCreate([
                        'nombre'          => $nombreMunicipio,
                        'departamento_id' => $departamento->id,
                    ]);
                }
            }
        }

        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento actualizado.');
    }

    /**
     * Eliminar un departamento
     */
    public function destroy(Departamento $departamento)
    {
        if (Auth::check() && Auth::user()->email !== 'admin@turismo.ni') {
            abort(403);
        }

        $departamento->delete();

        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento eliminado correctamente.');
    }
}
