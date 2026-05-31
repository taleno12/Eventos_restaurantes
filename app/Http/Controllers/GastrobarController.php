<?php

namespace App\Http\Controllers;

use App\Models\Gastrobar;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GastrobarController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────
    public function index()
    {
        $gastrobares = Gastrobar::with(['departamento', 'municipio'])
            ->latest()
            ->paginate(10);

        return view('gastrobares.index', compact('gastrobares'));
    }

    // ── CREATE ───────────────────────────────────────────────────
    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('gastrobares.create', compact('departamentos'));
    }

    // ── STORE ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:100',
            'email'           => 'nullable|email|max:150',
            'tipo_cocina'     => 'nullable|string|max:100',
            'tipo_bar'        => 'nullable|string|max:100',
            'descripcion'     => 'nullable|string|max:500',
            'hora_apertura'   => 'nullable|date_format:H:i',
            'hora_cierre'     => 'nullable|date_format:H:i',
            'dias_atencion'   => 'nullable|array',
            'tipo_musica'     => 'nullable|string|max:100',
            'capacidad'       => 'nullable|integer|min:1',
            'ambiente'        => 'nullable|string|max:50',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'municipio_id'    => 'nullable|exists:municipios,id',
            'direccion'       => 'nullable|string|max:255',
            'latitud'         => 'nullable|numeric',
            'longitud'        => 'nullable|numeric',
            'whatsapp'        => 'nullable|string|max:20',
            'instagram'       => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'tiktok'          => 'nullable|url|max:255',
            'imagen_principal'=> 'nullable|image|max:3072',
            'galeria'         => 'nullable|array|max:4',
            'galeria.*'       => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['imagen_principal', 'galeria']);

        // Imagen principal
        if ($request->hasFile('imagen_principal')) {
            $data['imagen_principal'] = $request->file('imagen_principal')
                ->store('gastrobares/portadas', 'public');
        }

        // Galería
        if ($request->hasFile('galeria')) {
            $galeria = [];
            foreach ($request->file('galeria') as $foto) {
                if ($foto) {
                    $galeria[] = $foto->store('gastrobares/galeria', 'public');
                }
            }
            $data['galeria'] = $galeria;
        }

        Gastrobar::create($data);

        return redirect()->route('admin.gastrobares.index')
            ->with('success', 'Gastrobar registrado correctamente.');
    }

    // ── SHOW (ADMIN) ─────────────────────────────────────────────
    public function adminShow(Gastrobar $gastrobar)
    {
        $gastrobar->load(['departamento', 'municipio']);
        return view('gastrobares.show', compact('gastrobar'));
    }

    // ── EDIT ─────────────────────────────────────────────────────
    public function edit(Gastrobar $gastrobar)
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $gastrobar->departamento_id)
                            ->orderBy('nombre')->get();

        return view('gastrobares.edit', compact('gastrobar', 'departamentos', 'municipios'));
    }

    // ── UPDATE ───────────────────────────────────────────────────
    public function update(Request $request, Gastrobar $gastrobar)
    {
        $request->validate([
            'nombre'          => 'required|string|max:100',
            'email'           => 'nullable|email|max:150',
            'tipo_cocina'     => 'nullable|string|max:100',
            'tipo_bar'        => 'nullable|string|max:100',
            'descripcion'     => 'nullable|string|max:500',
            'hora_apertura'   => 'nullable|date_format:H:i',
            'hora_cierre'     => 'nullable|date_format:H:i',
            'dias_atencion'   => 'nullable|array',
            'tipo_musica'     => 'nullable|string|max:100',
            'capacidad'       => 'nullable|integer|min:1',
            'ambiente'        => 'nullable|string|max:50',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'municipio_id'    => 'nullable|exists:municipios,id',
            'direccion'       => 'nullable|string|max:255',
            'latitud'         => 'nullable|numeric',
            'longitud'        => 'nullable|numeric',
            'whatsapp'        => 'nullable|string|max:20',
            'instagram'       => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'tiktok'          => 'nullable|url|max:255',
            'imagen_principal'=> 'nullable|image|max:3072',
            'galeria'         => 'nullable|array|max:4',
            'galeria.*'       => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['imagen_principal', 'galeria']);

        // Imagen principal
        if ($request->hasFile('imagen_principal')) {
            if ($gastrobar->imagen_principal) {
                Storage::disk('public')->delete($gastrobar->imagen_principal);
            }
            $data['imagen_principal'] = $request->file('imagen_principal')
                ->store('gastrobares/portadas', 'public');
        }

        // Galería
        if ($request->hasFile('galeria')) {
            if ($gastrobar->galeria) {
                foreach ($gastrobar->galeria as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }
            $galeria = [];
            foreach ($request->file('galeria') as $foto) {
                if ($foto) {
                    $galeria[] = $foto->store('gastrobares/galeria', 'public');
                }
            }
            $data['galeria'] = $galeria;
        }

        $gastrobar->update($data);

        return redirect()->route('admin.gastrobares.index')
            ->with('success', 'Gastrobar actualizado correctamente.');
    }

    // ── DESTROY ──────────────────────────────────────────────────
    public function destroy(Gastrobar $gastrobar)
    {
        if ($gastrobar->imagen_principal) {
            Storage::disk('public')->delete($gastrobar->imagen_principal);
        }
        if ($gastrobar->galeria) {
            foreach ($gastrobar->galeria as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $gastrobar->delete();

        return redirect()->route('admin.gastrobares.index')
            ->with('success', 'Gastrobar eliminado correctamente.');
    }

    // ── PUBLIC INDEX ─────────────────────────────────────────────
    public function publicIndex(Request $request)
    {
        $query = Gastrobar::with(['departamento', 'municipio'])->latest();

        if ($request->departamento) {
            $query->where('departamento_id', $request->departamento);
        }
        if ($request->tipo_bar) {
            $query->where('tipo_bar', $request->tipo_bar);
        }
        if ($request->ambiente) {
            $query->where('ambiente', $request->ambiente);
        }
        if ($request->search) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $gastrobares   = $query->paginate(12)->withQueryString();
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('gastrobares.public_index', compact('gastrobares', 'departamentos'));
    }

    // ── PUBLIC SHOW ──────────────────────────────────────────────
    public function publicShow(Gastrobar $gastrobar)
    {
        $gastrobar->load(['departamento', 'municipio']);
        return view('gastrobares.public_show', compact('gastrobar'));
    }
}