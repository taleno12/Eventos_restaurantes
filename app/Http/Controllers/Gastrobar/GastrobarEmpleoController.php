<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Empleo;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastrobarEmpleoController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index()
    {
        $gastrobar = $this->gastrobar();
        $empleos   = Empleo::where('gastrobar_id', $gastrobar->id)
            ->latest()->paginate(10);

        return view('gastrobar.empleos.index', compact('gastrobar', 'empleos'));
    }

    public function create()
    {
        $gastrobar  = $this->gastrobar();
        $municipios = Municipio::where('departamento_id', $gastrobar->departamento_id)
            ->orderBy('nombre')->get();

        return view('gastrobar.empleos.create', compact('gastrobar', 'municipios'));
    }

    public function store(Request $request)
    {
        $gastrobar = $this->gastrobar();

        $request->validate([
            'titulo'         => 'required|string|max:255',
            'descripcion'    => 'required|string',
            'requisitos'     => 'nullable|string',
            'tipo_contrato'  => 'nullable|string|max:100',
            'salario'        => 'nullable|numeric|min:0',
            'municipio_id'   => 'required|exists:municipios,id',
            'fecha_limite'   => 'nullable|date',
            'activo'         => 'boolean',
        ]);

        Empleo::create([
            'titulo'          => $request->titulo,
            'descripcion'     => $request->descripcion,
            'requisitos'      => $request->requisitos,
            'tipo_contrato'   => $request->tipo_contrato,
            'salario'         => $request->salario,
            'municipio_id'    => $request->municipio_id,
            'departamento_id' => $gastrobar->departamento_id,
            'fecha_limite'    => $request->fecha_limite,
            'activo'          => $request->boolean('activo'),
            'gastrobar_id'    => $gastrobar->id,
        ]);

        return redirect()->route('gastrobar.empleos.index')
            ->with('success', '¡Oferta publicada exitosamente!');
    }

    public function show(Empleo $empleo)
    {
        return redirect()->route('gastrobar.empleos.edit', $empleo);
    }

    public function edit(Empleo $empleo)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($empleo->gastrobar_id === $gastrobar->id, 403);

        $municipios = Municipio::where('departamento_id', $gastrobar->departamento_id)
            ->orderBy('nombre')->get();

        return view('gastrobar.empleos.edit', compact('gastrobar', 'empleo', 'municipios'));
    }

    public function update(Request $request, Empleo $empleo)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($empleo->gastrobar_id === $gastrobar->id, 403);

        $request->validate([
            'titulo'        => 'required|string|max:255',
            'descripcion'   => 'required|string',
            'requisitos'    => 'nullable|string',
            'tipo_contrato' => 'nullable|string|max:100',
            'salario'       => 'nullable|numeric|min:0',
            'municipio_id'  => 'required|exists:municipios,id',
            'fecha_limite'  => 'nullable|date',
            'activo'        => 'boolean',
        ]);

        $empleo->update([
            'titulo'        => $request->titulo,
            'descripcion'   => $request->descripcion,
            'requisitos'    => $request->requisitos,
            'tipo_contrato' => $request->tipo_contrato,
            'salario'       => $request->salario,
            'municipio_id'  => $request->municipio_id,
            'fecha_limite'  => $request->fecha_limite,
            'activo'        => $request->boolean('activo'),
        ]);

        return redirect()->route('gastrobar.empleos.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    public function destroy(Empleo $empleo)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($empleo->gastrobar_id === $gastrobar->id, 403);

        $empleo->delete();

        return redirect()->route('gastrobar.empleos.index')
            ->with('success', 'Oferta eliminada.');
    }
}
