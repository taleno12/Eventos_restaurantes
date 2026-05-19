<?php

namespace App\Http\Controllers;

use App\Models\Empleo;
use App\Models\Departamento;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class EmpleoController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // VISTAS PÚBLICAS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Vista pública: lista de ofertas de empleo con filtros locales.
     * Ruta: GET /empleos  →  name('empleos.index')
     */
    public function publicIndex(Request $request)
    {
        // Optimizamos la carga llamando directo a la relación geográfica directa del empleo
        $query = Empleo::with(['restaurante', 'departamento', 'municipio'])
            ->where('activo', true)
            ->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhereHas('restaurante', fn($r) => $r->where('nombre', 'like', "%{$search}%"));
            });
        }

        // Filtramos directamente por la nueva columna geográfica de la vacante
        if ($departamentoId = $request->input('departamento')) {
            $query->where('departamento_id', $departamentoId);
        }

        $empleos            = $query->paginate(9)->withQueryString();
        $departamentos      = Departamento::orderBy('nombre')->get();
        $totalRestaurantes  = Restaurante::count();
        $totalDepartamentos = Departamento::count();
        $totalActivos       = Empleo::where('activo', true)->count();

        return view('empleos', compact(
            'empleos',
            'departamentos',
            'totalRestaurantes',
            'totalDepartamentos',
            'totalActivos'
        ));
    }

    /**
     * Vista pública: detalle de una oferta específica.
     * Ruta: GET /empleos/{empleo}  →  name('empleos.show')
     */
    public function show(Empleo $empleo)
    {
        abort_unless($empleo->activo, 404);
        $empleo->load(['restaurante', 'departamento', 'municipio']);
        
        return view('empleos-show', compact('empleo'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PANEL DE ADMINISTRACIÓN
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Lista de todas las ofertas para el admin general.
     * Ruta: GET /admin/empleos  →  name('admin.empleos.index')
     */
    public function index()
    {
        // Cargamos las relaciones geográficas para la visualización en la tabla de control
        $empleos                = Empleo::with(['restaurante', 'departamento', 'municipio'])->latest()->paginate(15);
        $activas                = Empleo::where('activo', true)->count();
        $restaurantesConOfertas = Empleo::distinct('restaurante_id')->count('restaurante_id');

        return view('empleos.index', compact('empleos', 'activas', 'restaurantesConOfertas'));
    }

    /**
     * Formulario para crear una nueva oferta laboral.
     * Ruta: GET /admin/empleos/crear  →  name('admin.empleos.create')
     */
    public function create()
    {
        $restaurantes = Restaurante::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get(); // Enviamos los departamentos al selector administrativo
        
        return view('empleos.create', compact('restaurantes', 'departamentos'));
    }

    /**
     * Guardar nueva oferta en la base de datos con validaciones geográficas.
     * Ruta: POST /admin/empleos  →  name('admin.empleos.store')
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'departamento_id' => 'required|exists:departamentos,id', // Validación integrada
            'municipio_id'    => 'required|exists:municipios,id',    // Validación integrada
            'titulo'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'requisitos'      => 'nullable|string',
            'tipo_contrato'   => 'nullable|string|max:50',
            'salario'         => 'nullable|numeric|min:0',
            'fecha_limite'    => 'nullable|date|after:today',
            'activo'          => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->boolean('activo', true);

        Empleo::create($validated);

        return redirect()->route('admin.empleos.index')
            ->with('success', '✅ Oferta publicada exitosamente.');
    }

    /**
     * Vista de administración: detalle privado de una vacante.
     * Ruta: GET /admin/empleos/{empleo}  →  name('admin.empleos.show')
     */
    public function adminShow(Empleo $empleo)
    {
        // Cargamos todas las relaciones geográficas específicas de la vacante para el panel
        $empleo->load(['restaurante', 'departamento', 'municipio']);
        
        return view('empleos.show', compact('empleo'));
    }

    /**
     * Formulario para editar una oferta existente.
     * Ruta: GET /admin/empleos/{empleo}/editar  →  name('admin.empleos.edit')
     */
    public function edit(Empleo $empleo)
    {
        $restaurantes = Restaurante::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get(); // Enviamos los departamentos para la recarga de datos
        
        return view('empleos.edit', compact('empleo', 'restaurantes', 'departamentos'));
    }

    /**
     * Actualizar una oferta existente con su ubicación en cascada.
     * Ruta: PUT /admin/empleos/{empleo}  →  name('admin.empleos.update')
     */
    public function update(Request $request, Empleo $empleo)
    {
        $validated = $request->validate([
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'departamento_id' => 'required|exists:departamentos,id', // Validación integrada
            'municipio_id'    => 'required|exists:municipios,id',    // Validación integrada
            'titulo'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'requisitos'      => 'nullable|string',
            'tipo_contrato'   => 'nullable|string|max:50',
            'salario'         => 'nullable|numeric|min:0',
            'fecha_limite'    => 'nullable|date',
            'activo'          => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->boolean('activo', true);

        $empleo->update($validated);

        return redirect()->route('admin.empleos.index')
            ->with('success', '✅ Oferta actualizada correctamente.');
    }

    /**
     * Eliminar oferta de manera física.
     * Ruta: DELETE /admin/empleos/{empleo}  →  name('admin.empleos.destroy')
     */
    public function destroy(Empleo $empleo)
    {
        $empleo->delete();

        return redirect()->route('admin.empleos.index')
            ->with('success', '🗑️ Oferta eliminada.');
    }
}