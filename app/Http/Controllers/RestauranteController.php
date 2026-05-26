<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Departamento;
use App\Models\Municipio;                          // ← AÑADIDO
use App\Models\RestauranteFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if ($request->filled('municipio')) {        // ← AÑADIDO
            $query->where('municipio_id', $request->municipio);
        }

        if ($request->filled('especialidad')) {
            $query->where('especialidad', 'like', '%' . $request->especialidad . '%');
        }

        $restaurantes  = $query->orderBy('nombre')->paginate(12);
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::orderBy('nombre')->get(); // ← AÑADIDO

        return view('restaurantes.public_index', compact(
            'restaurantes',
            'departamentos',
            'municipios'                            // ← AÑADIDO
        ));
    }

    public function publicShow(Restaurante $restaurante)
    {
        // Se cargan las imágenes utilizando la relación del modelo
        $restaurante->load(['departamento', 'municipio', 'imagenes']);
        
        return view('restaurantes.public_show', compact('restaurante'));
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
            'descripcion'     => 'nullable|string',
            // Validación de imágenes (Máximo 3MB por archivo)
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'galeria'          => 'nullable|array|max:4',
            'galeria.*'        => 'image|mimes:jpeg,png,jpg,webp|max:3072',
            // Validacion de mapa 
            'direccion' => 'nullable|string|max:255',
            'latitud'   => 'nullable|numeric|between:-90,90',
            'longitud'  => 'nullable|numeric|between:-180,180',
        ]);

        // 1. Instanciar el restaurante omitiendo los archivos temporales
        $restaurante = new Restaurante($request->except(['galeria', 'imagen_principal']));

        // 2. Guardar imagen principal si se sube una
        if ($request->hasFile('imagen_principal')) {
            $pathPrincipal = $request->file('imagen_principal')->store('restaurantes/principales', 'public');
            $restaurante->foto_portada = $pathPrincipal; 
        }

        $restaurante->save();

        // 3. Guardar las fotos opcionales de la galería (Hasta 4 fotos)
        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $foto) {
                $pathFoto = $foto->store('restaurantes/galerias', 'public');
                
                // CORRECCIÓN: Usamos 'ruta_foto' para coincidir con tu BD
                RestauranteFoto::create([
                    'restaurante_id' => $restaurante->id,
                    'ruta_foto'      => $pathFoto
                ]);
            }
        }

        // REDIRECCIÓN ACTUALIZADA: coincide con name('restaurantes.index')
        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante agregado correctamente.');
    }

    public function adminShow(Restaurante $restaurante)
    {
        $restaurante->load(['departamento', 'municipio', 'imagenes']);
        
        return view('restaurantes.show', compact('restaurante'));
    }

    public function edit(Restaurante $restaurante)
    {
        $departamentos = Departamento::all();
        $restaurante->load('imagenes'); 
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
            'descripcion'     => 'nullable|string',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'galeria'          => 'nullable|array|max:4',
            'galeria.*'        => 'image|mimes:jpeg,png,jpg,webp|max:3072',
            // Validacion de mapa
            'direccion' => 'nullable|string|max:255',
            'latitud'   => 'nullable|numeric|between:-90,90',
            'longitud'  => 'nullable|numeric|between:-180,180',
        ]);

        // 1. Asignar los campos básicos
        $restaurante->fill($request->except(['galeria', 'imagen_principal']));

        // 2. Si sube una nueva imagen principal, reemplazar la anterior
        if ($request->hasFile('imagen_principal')) {
            if ($restaurante->foto_portada) {
                Storage::disk('public')->delete($restaurante->foto_portada);
            }
            $pathPrincipal = $request->file('imagen_principal')->store('restaurantes/principales', 'public');
            $restaurante->foto_portada = $pathPrincipal;
        }

        $restaurante->save();

        // 3. Agregar nuevas fotos a la galería
        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $foto) {
                $pathFoto = $foto->store('restaurantes/galerias', 'public');
                
                // CORRECCIÓN: Usamos 'ruta_foto' para coincidir con tu BD
                RestauranteFoto::create([
                    'restaurante_id' => $restaurante->id,
                    'ruta_foto'      => $pathFoto
                ]);
            }
        }

        // REDIRECCIÓN ACTUALIZADA: coincide con name('restaurantes.index')
        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy(Restaurante $restaurante)
    {
        if ($restaurante->foto_portada) {
            Storage::disk('public')->delete($restaurante->foto_portada);
        }

        $restaurante->delete();

        // REDIRECCIÓN ACTUALIZADA: coincide con name('restaurantes.index')
        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante eliminado correctamente.');
    }

    public function getPorMunicipio($municipio_id)
    {
        $restaurantes = Restaurante::where('municipio_id', $municipio_id)
            ->select('id', 'nombre', 'especialidad')
            ->orderBy('nombre')
            ->get();

        return response()->json($restaurantes);
    }
}