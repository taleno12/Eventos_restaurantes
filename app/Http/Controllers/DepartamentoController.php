<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
{
    /**
     * Mostrar lista de departamentos
     */
    public function index()
    {
        $departamentos = Departamento::all();
        return view('departamentos.index', compact('departamentos'));
    }

    /**
     * Formulario para crear un nuevo departamento
     */
    public function create()
    {
        // Solo el admin puede crear
        if (Auth::check() && Auth::user()->email !== 'admin@turismo.ni') {
            abort(403);
        }
        return view('departamentos.create');
    }

    /**
     * Guardar el departamento en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:departamentos,nombre|max:255'
        ]);

        Departamento::create($request->all());

        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento creado correctamente.');
    }

    /**
     * Método Show: Evita el error "Undefined method" de la imagen image_9eb511.png
     */
    public function show(Departamento $departamento)
    {
        // Redirigimos al index porque no necesitamos una página individual para cada departamento
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
     * Actualizar el nombre del departamento
     */
    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|max:255|unique:departamentos,nombre,' . $departamento->id
        ]);

        $departamento->update($request->all());

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

        // Nota: Esto fallará si el departamento tiene restaurantes asociados (por la llave foránea)
        $departamento->delete();

        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento eliminado correctamente.');
    }
}