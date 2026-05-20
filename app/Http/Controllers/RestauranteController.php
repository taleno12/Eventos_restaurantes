<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Departamento;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS PÚBLICOS (Sin autenticación — para visitantes del sitio)
    // ─────────────────────────────────────────────────────────────────────────

    public function publicIndex(Request $request)
    {
        $query = Restaurante::with(['departamento', 'municipio'])
            ->withCount(['eventos' => function ($q) {
                $q->where('fecha_evento', '>=', now());
            }]);

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('departamento')) {
            $query->where('departamento_id', $request->departamento);
        }

        if ($request->filled('especialidad')) {
            $query->where('especialidad', 'like', '%' . $request->especialidad . '%');
        }

        $restaurantes  = $query->orderBy('nombre')->paginate(12);
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('restaurantes.public_index', compact('restaurantes', 'departamentos'));
    }

    public function publicShow(Restaurante $restaurante)
    {
        $restaurante->load(['departamento', 'municipio']);
        return view('restaurantes.show', compact('restaurante'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS DE ADMINISTRACIÓN (Requieren autenticación)
    // ─────────────────────────────────────────────────────────────────────────

    // Panel admin — apunta a indexadmin.blade.php
    public function index(Request $request)
    {
        $query = Restaurante::with(['departamento', 'municipio']);

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('departamento_id')) {
            $query->where('departamento_id', $request->departamento_id);
        }

        $restaurantes       = $query->latest()->paginate(10);
        $activos            = Restaurante::where('activo', true)->count();
        $totalDepartamentos = Departamento::count();
        $departamentos      = Departamento::orderBy('nombre')->get();

        return view('restaurantes.indexadmin', compact(
            'restaurantes',
            'activos',
            'totalDepartamentos',
            'departamentos'
        ));
    }

    public function create()
    {
        $departamentos = Departamento::all();
        return view('restaurantes.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'email'           => 'required|email|unique:restaurantes,email',
            'especialidad'    => 'required|string|max:100',
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id',
            'instagram'       => 'nullable|url|max:255',
            'tiktok'          => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'whatsapp'        => 'nullable|string|max:50',
        ]);

        Restaurante::create($request->all());

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante agregado correctamente.');
    }

    public function show(Restaurante $restaurante)
    {
        $restaurante->load(['departamento', 'municipio']);
        return view('restaurantes.show', compact('restaurante'));
    }

    public function edit(Restaurante $restaurante)
    {
        $departamentos = Departamento::all();
        return view('restaurantes.edit', compact('restaurante', 'departamentos'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'email'           => 'required|email|unique:restaurantes,email,' . $restaurante->id,
            'especialidad'    => 'required|string|max:100',
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id',
            'instagram'       => 'nullable|url|max:255',
            'tiktok'          => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'whatsapp'        => 'nullable|string|max:50',
        ]);

        $restaurante->update($request->all());

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy(Restaurante $restaurante)
    {
        $restaurante->delete();

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante eliminado correctamente.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // API ENDPOINT: Restaurantes por municipio
    // ─────────────────────────────────────────────────────────────────────────
    public function getPorMunicipio($municipio_id)
    {
        $restaurantes = Restaurante::where('municipio_id', $municipio_id)
            ->select('id', 'nombre', 'especialidad')
            ->orderBy('nombre')
            ->get();

        return response()->json($restaurantes);
    }
}