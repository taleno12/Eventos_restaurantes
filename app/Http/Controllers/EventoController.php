<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function welcome(Request $request)
    {
        $departamentos = Departamento::all();
        $restaurantes  = Restaurante::orderBy('nombre')->get();

        $eventosDestacados = Evento::with(['restaurante', 'departamento'])
            ->where('is_destacado', true)
            ->latest()
            ->get();

        $query = Evento::with(['restaurante', 'departamento']);

        if ($request->filled('departamento')) {
            $query->where('departamento_id', $request->departamento);
        }

        if ($request->filled('restaurante_id')) {
            $query->where('restaurante_id', $request->restaurante_id);
        }

        if ($request->filled('especialidad')) {
            $query->whereHas('restaurante', function ($q) use ($request) {
                $q->where('especialidad', 'LIKE', '%' . $request->especialidad . '%');
            });
        }

        $eventos = $query->latest()->get();

        if ($eventos->isEmpty() && !$request->anyFilled(['departamento', 'restaurante_id', 'especialidad'])) {
            $eventos = Evento::with(['restaurante', 'departamento'])->latest()->take(6)->get();
        }

        return view('welcome', compact('eventos', 'eventosDestacados', 'departamentos', 'restaurantes'));
    }

    public function show(Evento $evento)
    {
        $evento->load(['restaurante', 'departamento', 'municipio', 'imagenes']);
        return view('eventos-show', compact('evento'));
    }

    public function index()
    {
        $this->soloAdmin();
        $eventos = Evento::with(['restaurante', 'departamento'])->latest()->paginate(10);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $this->soloAdmin();
        $departamentos = Departamento::orderBy('nombre')->get();
        $restaurantes  = Restaurante::orderBy('nombre')->get();
        return view('eventos.create', compact('departamentos', 'restaurantes'));
    }

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
            'municipio_id'    => 'required|exists:municipios,id',
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'is_destacado'    => 'nullable|boolean',
            'galeria.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = $request->except(['imagen', 'galeria']);
        $datos['is_destacado'] = $request->boolean('is_destacado', false);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $evento = Evento::create($datos);

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                if ($img && $img->isValid()) {
                    $path = $img->store('eventos/galeria', 'public');
                    $evento->imagenes()->create(['ruta' => $path]);
                }
            }
        }

        return redirect()->route('eventos.index')
            ->with('success', 'Evento publicado con exito.');
    }

    public function edit(Evento $evento)
    {
        $this->soloAdmin();

        $evento->load('imagenes');

        $departamentos = Departamento::orderBy('nombre')->get();

        $municipios = Municipio::where('departamento_id', $evento->departamento_id)
                        ->orderBy('nombre')
                        ->get();

        $restaurantes = Restaurante::where('departamento_id', $evento->departamento_id)
                        ->orderBy('nombre')
                        ->get();

        return view('eventos.edit', compact('evento', 'departamentos', 'municipios', 'restaurantes'));
    }

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
            'municipio_id'    => 'required|exists:municipios,id',
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'is_destacado'    => 'nullable|boolean',
            'galeria.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // ← AÑADIDO
        ]);

        $datos = $request->except(['imagen', 'galeria']); // ← 'galeria' también excluido
        $datos['is_destacado'] = $request->boolean('is_destacado', false);

        if ($request->hasFile('imagen')) {
            if ($evento->imagen) {
                Storage::disk('public')->delete($evento->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $evento->update($datos);

        // ← AÑADIDO: guardar fotos de galería al editar
        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                if ($img && $img->isValid()) {
                    $path = $img->store('eventos/galeria', 'public');
                    $evento->imagenes()->create(['ruta' => $path]);
                }
            }
        }

        return redirect()->route('eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        $this->soloAdmin();

        if ($evento->imagen) {
            Storage::disk('public')->delete($evento->imagen);
        }

        foreach ($evento->imagenes as $img) {
            Storage::disk('public')->delete($img->ruta);
            $img->delete();
        }

        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', 'Evento eliminado.');
    }

    private function soloAdmin(): void
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@turismo.ni') {
            abort(403, 'Acceso no autorizado.');
        }
    }
}