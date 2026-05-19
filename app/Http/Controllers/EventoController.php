<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    /**
     * LOGICA PÚBLICA: Se usa para la vista 'welcome' (Carrusel y Galería)
     */
    public function welcome(Request $request)
    {
        $departamentos = Departamento::all();
        
        // Cargamos todos los restaurantes para renderizar el listado inicial
        $restaurantes = Restaurante::orderBy('nombre')->get();

        // 1. Carrusel: Eventos destacados
        $eventosDestacados = Evento::with(['restaurante', 'departamento']) 
            ->where('is_destacado', true)
            ->latest()
            ->get();

        // 2. Galería: Todos los eventos con filtros en cascada
        $query = Evento::with(['restaurante', 'departamento']);

        // Filtrar por el Departamento seleccionado
        if ($request->filled('departamento')) {
            $query->where('departamento_id', $request->departamento);
        }

        // NUEVO: Filtrar directamente por el ID del Restaurante seleccionado en el selector
        if ($request->filled('restaurante_id')) {
            $query->where('restaurante_id', $request->restaurante_id);
        }

        // Filtro por Especialidad Gastronómica
        if ($request->filled('especialidad')) {
            $query->whereHas('restaurante', function($q) use ($request) {
                $q->where('especialidad', 'LIKE', '%' . $request->especialidad . '%');
            });
        }

        $eventos = $query->latest()->get();

        // Fallback si no hay eventos activos tras usar los selectores
        if ($eventos->isEmpty() && !$request->anyFilled(['departamento', 'restaurante_id', 'especialidad'])) {
            $eventos = Evento::with(['restaurante', 'departamento'])->latest()->take(6)->get();
        }

        return view('welcome', compact('eventos', 'eventosDestacados', 'departamentos', 'restaurantes'));
    }

    /**
     * DETALLE PÚBLICO: Muestra la información extendida de un evento sin pedir login
     */
    public function show(Evento $evento)
    {
        $evento->load(['restaurante', 'departamento', 'municipio']);
        
        return view('eventos-show', compact('evento'));
    }

    /**
     * INDEX ADMINISTRATIVO: Lista todos los eventos para editar/eliminar con Paginación
     */
    public function index()
    {
        $this->soloAdmin();

        $eventos = Evento::with(['restaurante', 'departamento'])->latest()->paginate(10);

        return view('eventos.index', compact('eventos'));
    }

    /**
     * Formulario para crear un evento (Solo Admin)
     */
    public function create()
    {
        $this->soloAdmin();

        $departamentos = Departamento::orderBy('nombre')->get();
        $restaurantes = Restaurante::orderBy('nombre')->get();

        return view('eventos.create', compact('departamentos', 'restaurantes'));
    }

    /**
     * Guardar nuevo evento
     */
    public function store(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'titulo'          => 'required|max:255',
            'descripcion'     => 'nullable|string',
            'imagen'          => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'          => 'required|numeric|min:0',
            'fecha_evento'    => 'required|date',
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id', // <-- Añadido tras corregir la base de datos
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'is_destacado'    => 'nullable|boolean',
        ]);

        $datos = $request->all();
        $datos['is_destacado'] = $request->boolean('is_destacado', false);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        Evento::create($datos);

        return redirect()->route('eventos.index')
            ->with('success', '✅ Evento publicado con éxito.');
    }

    /**
     * Formulario para editar un evento (Solo Admin)
     */
    public function edit(Evento $evento)
    {
        $this->soloAdmin();

        $departamentos = Departamento::orderBy('nombre')->get();
        $restaurantes  = Restaurante::where('departamento_id', $evento->departamento_id)
                            ->orderBy('nombre')
                            ->get();

        return view('eventos.edit', compact('evento', 'departamentos', 'restaurantes'));
    }

    /**
     * Actualizar evento existente
     */
    public function update(Request $request, Evento $evento)
    {
        $this->soloAdmin();

        $request->validate([
            'titulo'          => 'required|max:255',
            'descripcion'     => 'nullable|string',
            'imagen'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'          => 'required|numeric|min:0',
            'fecha_evento'    => 'required|date',
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id', // <-- Añadido tras corregir la base de datos
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'is_destacado'    => 'nullable|boolean',
        ]);

        $datos = $request->except('imagen');
        $datos['is_destacado'] = $request->boolean('is_destacado', false);

        if ($request->hasFile('imagen')) {
            if ($evento->imagen) {
                Storage::disk('public')->delete($evento->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $evento->update($datos);

        return redirect()->route('eventos.index')
            ->with('success', '✅ Evento actualizado correctamente.');
    }

    /**
     * Eliminar evento
     */
    public function destroy(Evento $evento)
    {
        $this->soloAdmin();

        if ($evento->imagen) {
            Storage::disk('public')->delete($evento->imagen);
        }

        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', '🗑️ Evento eliminado.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS PRIVADOS
    // ─────────────────────────────────────────────────────────────────────────

    private function soloAdmin(): void
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@turismo.ni') {
            abort(403, 'Acceso no autorizado.');
        }
    }
}