<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Departamento;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    // Listado de restaurantes (Con filtro dinámico por URL integrado)
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta cargando de golpe las relaciones para optimizar la carga (Eager Loading)
        $query = Restaurante::with(['departamento', 'municipio']);

        // 2. Si la URL trae 'departamento_id', aplicamos el filtro completo en pantalla
        if ($request->has('departamento_id') && $request->departamento_id != '') {
            $query->where('departamento_id', $request->departamento_id);
        }

        // 3. Paginamos los resultados de la consulta
        $restaurantes = $query->latest()->paginate(10);
        
        // 4. Métricas globales para las tarjetas informativas superiores
        $activos = Restaurante::where('activo', true)->count();
        $totalDepartamentos = Departamento::count();

        return view('restaurantes.index', compact('restaurantes', 'activos', 'totalDepartamentos'));
    }

    // Formulario para agregar
    public function create()
    {
        $departamentos = Departamento::all();
        return view('restaurantes.create', compact('departamentos'));
    }

    // Guardar datos (MODIFICADO: Validación de Especialidad y Redes Sociales integradas)
    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'email'           => 'required|email|unique:restaurantes,email',
            'especialidad'    => 'required|string|max:100', // <-- Agregado para que no pase vacío
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id',
            'instagram'       => 'nullable|url|max:255',    // <-- Nuevos campos de Redes Sociales
            'tiktok'          => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'whatsapp'        => 'nullable|string|max:50',
        ]);

        Restaurante::create($request->all());

        return redirect()->route('restaurantes.index')
            ->with('success', 'Restaurante agregado correctamente.');
    }

    // Ver detalle
    public function show(Restaurante $restaurante)
    {
        $restaurante->load(['departamento', 'municipio']);
        return view('restaurantes.show', compact('restaurante'));
    }

    // Formulario para editar
    public function edit(Restaurante $restaurante)
    {
        $departamentos = Departamento::all();
        return view('restaurantes.edit', compact('restaurante', 'departamentos'));
    }

    // Actualizar datos (MODIFICADO: Validación de Especialidad y Redes Sociales integradas)
    public function update(Request $request, Restaurante $restaurante)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'email'           => 'required|email|unique:restaurantes,email,' . $restaurante->id,
            'especialidad'    => 'required|string|max:100', // <-- Agregado para la edición
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id',
            'instagram'       => 'nullable|url|max:255',    // <-- Nuevos campos de Redes Sociales
            'tiktok'          => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'whatsapp'        => 'nullable|string|max:50',
        ]);

        $restaurante->update($request->all());

        return redirect()->route('restaurantes.index')
            ->with('success', 'Datos actualizados correctamente.');
    }

    // Eliminar
    public function destroy(Restaurante $restaurante)
    {
        $restaurante->delete();

        return redirect()->route('restaurantes.index')
            ->with('success', 'Restaurante eliminado correctamente.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // NUEVO ENDPOINT DE API: Servir restaurantes trayendo explícitamente su especialidad
    // ─────────────────────────────────────────────────────────────────────────
    public function getPorMunicipio($municipio_id)
    {
        // Al incluir 'especialidad' aquí, el JavaScript de eventos la leerá sin problemas
        $restaurantes = Restaurante::where('municipio_id', $municipio_id)
            ->select('id', 'nombre', 'especialidad')
            ->orderBy('nombre')
            ->get();

        return response()->json($restaurantes);
    }
}