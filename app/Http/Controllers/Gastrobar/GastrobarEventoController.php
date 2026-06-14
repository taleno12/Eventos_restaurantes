<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastrobarEventoController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index()
    {
        $gastrobar = $this->gastrobar();
        $eventos   = Evento::where('gastrobar_id', $gastrobar->id)
            ->latest()->paginate(10);
        return view('gastrobar.eventos.index', compact('gastrobar', 'eventos'));
    }

    public function create()
    {
        $gastrobar     = $this->gastrobar();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('gastrobar.eventos.create', compact('gastrobar', 'departamentos'));
    }

    public function store(Request $request)
    {
        $gastrobar = $this->gastrobar();

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
        $datos['gastrobar_id']    = $gastrobar->id;
        $datos['departamento_id'] = $gastrobar->departamento_id;
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

        return redirect()->route('gastrobar.eventos.index')
            ->with('success', '¡Evento publicado exitosamente!');
    }

    public function show(Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);
        return redirect()->route('gastrobar.eventos.edit', $evento);
    }

    public function edit(Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);

        $evento->load('imagenes');
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $gastrobar->departamento_id)->get();

        return view('gastrobar.eventos.edit', compact('gastrobar', 'evento', 'departamentos', 'municipios'));
    }

    public function update(Request $request, Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);

        $validated = $request->validate([
            'titulo'       => 'required|max:255',
            'descripcion'  => 'nullable|string',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'galeria.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
            $validated['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        } else {
            unset($validated['imagen']);
        }

        unset($validated['galeria']);
        $validated['is_destacado'] = false;
        $evento->update($validated);

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                if ($img && $img->isValid()) {
                    $path = $img->store('eventos/galeria', 'public');
                    $evento->imagenes()->create(['ruta' => $path]);
                }
            }
        }

        return redirect()->route('gastrobar.eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($evento->gastrobar_id === $gastrobar->id, 403);

        if ($evento->imagen) Storage::disk('public')->delete($evento->imagen);
        foreach ($evento->imagenes as $img) {
            Storage::disk('public')->delete($img->ruta);
            $img->delete();
        }
        $evento->delete();

        return redirect()->route('gastrobar.eventos.index')
            ->with('success', 'Evento eliminado.');
    }
}
