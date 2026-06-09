<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestauranteEventoController extends Controller
{
    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    public function index()
    {
        $restaurante = $this->restaurante();
        $eventos = Evento::where('restaurante_id', $restaurante->id)
            ->latest()->paginate(10);
        return view('restaurante.eventos.index', compact('restaurante', 'eventos'));
    }

    public function create()
    {
        $restaurante   = $this->restaurante();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('restaurante.eventos.create', compact('restaurante', 'departamentos'));
    }

    public function store(Request $request)
    {
        $restaurante = $this->restaurante();

        $request->validate([
            'titulo'       => 'required|max:255',
            'descripcion'  => 'nullable|string',
            'imagen'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'galeria.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = $request->except(['imagen', 'galeria']);
        $datos['restaurante_id']  = $restaurante->id;
        $datos['departamento_id'] = $restaurante->departamento_id;
        $datos['is_destacado']    = false;

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

        return redirect()->route('restaurante.eventos.index')
            ->with('success', '¡Evento publicado exitosamente!');
    }

    public function edit(Evento $evento)
    {
        $restaurante = $this->restaurante();
        abort_unless($evento->restaurante_id === $restaurante->id, 403);

        $evento->load('imagenes');
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $restaurante->departamento_id)->get();

        return view('restaurante.eventos.edit', compact('restaurante', 'evento', 'departamentos', 'municipios'));
    }

    public function update(Request $request, Evento $evento)
{
    $restaurante = $this->restaurante();
    abort_unless($evento->restaurante_id === $restaurante->id, 403);

    $validated = $request->validate([
        'titulo'       => 'required|max:255',
        'descripcion'  => 'nullable|string',
        'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'precio'       => 'required|numeric|min:0',
        'fecha_evento' => 'required|date',
        'municipio_id' => 'required|exists:municipios,id',
        'galeria.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    // Imagen: solo actualizar si se subió una nueva
    if ($request->hasFile('imagen')) {
        if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
        $validated['imagen'] = $request->file('imagen')->store('anuncios', 'public');
    } else {
        unset($validated['imagen']); // mantener la imagen existente
    }

    // Galería adicional
    unset($validated['galeria']);
    $evento->update($validated);

    if ($request->hasFile('galeria')) {
        foreach ($request->file('galeria') as $img) {
            if ($img && $img->isValid()) {
                $path = $img->store('eventos/galeria', 'public');
                $evento->imagenes()->create(['ruta' => $path]);
            }
        }
    }

    return redirect()->route('restaurante.eventos.index')
        ->with('success', 'Evento actualizado correctamente.');
}

    public function destroy(Evento $evento)
    {
        $restaurante = $this->restaurante();
        abort_unless($evento->restaurante_id === $restaurante->id, 403);

        if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
        foreach ($evento->imagenes as $img) {
            Storage::disk('public')->delete($img->ruta);
            $img->delete();
        }
        $evento->delete();

        return redirect()->route('restaurante.eventos.index')
            ->with('success', 'Evento eliminado.');
    }
}