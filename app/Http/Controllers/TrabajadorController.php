<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use App\Models\Departamento;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrabajadorController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Trabajador::with('departamentos')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre',   'like', '%' . $request->search . '%')
                  ->orWhere('apellido', 'like', '%' . $request->search . '%')
                  ->orWhere('cedula',   'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('departamento_id')) {
            $query->whereHas('departamentos', fn($q) =>
                $q->where('departamentos.id', $request->departamento_id)
            );
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $trabajadores  = $query->paginate(10)->withQueryString();
        $departamentos = Departamento::orderBy('nombre')->get();
        $totalActivos  = Trabajador::where('estado', 'activo')->count();

        return view('trabajadores.index', compact(
            'trabajadores',
            'departamentos',
            'totalActivos'
        ));
    }

    // ── CREATE ───────────────────────────────────────────────────
    public function create()
    {
        $departamentos = Departamento::with('restaurantes')->orderBy('nombre')->get();
        return view('trabajadores.create', compact('departamentos'));
    }

    // ── STORE ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'cedula'           => 'required|string|max:20|unique:trabajadores,cedula',
            'email'            => 'required|email|max:150|unique:trabajadores,email',
            'telefono'         => 'nullable|string|max:20',
            'salario'          => 'nullable|numeric|min:0',
            'cargo'            => 'nullable|string|max:100',
            'estado'           => 'required|in:activo,inactivo',
            'fecha_ingreso'    => 'nullable|date',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'observaciones'    => 'nullable|string',
            'departamentos'    => 'required|array|min:1',
            'departamentos.*'  => 'exists:departamentos,id',
        ]);

        $data = $request->except(['foto', 'departamentos']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('trabajadores/fotos', 'public');
        }

        $trabajador = Trabajador::create($data);

        // Asignar los departamentos seleccionados (Many to Many)
        $trabajador->departamentos()->sync($request->departamentos);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador registrado correctamente.');
    }

    // ── SHOW ─────────────────────────────────────────────────────
    public function show(Trabajador $trabajador)
    {
        $trabajador->load('departamentos');
        $restaurantes = $trabajador->restaurantesDisponibles();

        return view('trabajadores.show', compact('trabajador', 'restaurantes'));
    }

    // ── EDIT ─────────────────────────────────────────────────────
    public function edit(Trabajador $trabajador)
    {
        $trabajador->load('departamentos');
        $departamentos          = Departamento::with('restaurantes')->orderBy('nombre')->get();
        $departamentosAsignados = $trabajador->departamentos->pluck('id')->toArray();

        return view('trabajadores.edit', compact(
            'trabajador',
            'departamentos',
            'departamentosAsignados'
        ));
    }

    // ── UPDATE ───────────────────────────────────────────────────
    public function update(Request $request, Trabajador $trabajador)
    {
        $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'cedula'           => 'required|string|max:20|unique:trabajadores,cedula,' . $trabajador->id,
            'email'            => 'required|email|max:150|unique:trabajadores,email,' . $trabajador->id,
            'telefono'         => 'nullable|string|max:20',
            'salario'          => 'nullable|numeric|min:0',
            'cargo'            => 'nullable|string|max:100',
            'estado'           => 'required|in:activo,inactivo',
            'fecha_ingreso'    => 'nullable|date',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'observaciones'    => 'nullable|string',
            'departamentos'    => 'required|array|min:1',
            'departamentos.*'  => 'exists:departamentos,id',
        ]);

        $data = $request->except(['foto', 'departamentos']);

        if ($request->hasFile('foto')) {
            if ($trabajador->foto) {
                Storage::disk('public')->delete($trabajador->foto);
            }
            $data['foto'] = $request->file('foto')->store('trabajadores/fotos', 'public');
        }

        $trabajador->update($data);

        // sync() agrega los nuevos y elimina los que ya no están
        $trabajador->departamentos()->sync($request->departamentos);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador actualizado correctamente.');
    }

    // ── DESTROY ──────────────────────────────────────────────────
    public function destroy(Trabajador $trabajador)
    {
        if ($trabajador->foto) {
            Storage::disk('public')->delete($trabajador->foto);
        }

        // La tabla pivote se limpia automáticamente por el cascade
        $trabajador->delete();

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador eliminado correctamente.');
    }

    // ── AJAX: restaurantes por departamento(s) ───────────────────
    /**
     * Recibe un array de departamento_ids y devuelve los restaurantes de esos departamentos.
     * Usado en el formulario cuando el usuario selecciona departamentos.
     */
    public function getRestaurantesPorDepartamentos(Request $request)
    {
        $ids = $request->input('departamentos', []);

        $restaurantes = Restaurante::whereIn('departamento_id', $ids)
            ->select('id', 'nombre', 'especialidad', 'departamento_id')
            ->orderBy('nombre')
            ->get();

        return response()->json($restaurantes);
    }
}
