<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\RestauranteFoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RestauranteController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS PÚBLICOS (Sin autenticación — para visitantes del sitio)
    // ─────────────────────────────────────────────────────────────────────────

    public function publicIndex(Request $request)
    {
        $departamentoPredefinido = Auth::check()
            ? Auth::user()->departamento_id
            : null;

        $hayFiltroActivo = $request->hasAny(['departamento', 'municipio', 'especialidad', 'search']);

        $deptoFiltro = $hayFiltroActivo
            ? ($request->filled('departamento') ? $request->departamento : null)
            : $departamentoPredefinido;

        $query = Restaurante::with(['departamento', 'municipio'])
            ->withCount(['eventos' => function ($q) {
                $q->where('fecha_evento', '>=', now());
            }]);

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        if ($deptoFiltro) {
            $query->where('departamento_id', $deptoFiltro);
        }

        if ($request->filled('municipio')) {
            $query->where('municipio_id', $request->municipio);
        }

        if ($request->filled('especialidad')) {
            $query->where('especialidad', 'like', '%' . $request->especialidad . '%');
        }

        $restaurantes  = $query->orderBy('nombre')->paginate(12);
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::orderBy('nombre')->get();

        return view('restaurantes.public_index', compact(
            'restaurantes',
            'departamentos',
            'municipios',
            'departamentoPredefinido'
        ));
    }

    public function publicShow(Restaurante $restaurante)
    {
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
            'nombre'           => 'required|string|max:255',
            'email'            => 'required|email|unique:restaurantes,email',
            'especialidad'     => 'required|string|max:100',
            'departamento_id'  => 'required|exists:departamentos,id',
            'municipio_id'     => 'required|exists:municipios,id',
            'instagram'        => 'nullable|url|max:255',
            'tiktok'           => 'nullable|url|max:255',
            'facebook'         => 'nullable|url|max:255',
            'whatsapp'         => 'nullable|string|max:50',
            'descripcion'      => 'nullable|string',
            'imagen_principal'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'galeria'           => 'nullable|array|max:4',
            'galeria.*'         => 'image|mimes:jpeg,png,jpg,webp|max:3072',
            'direccion'         => 'nullable|string|max:255',
            'latitud'           => 'nullable|numeric|between:-90,90',
            'longitud'          => 'nullable|numeric|between:-180,180',
            // ← USUARIO PROPIETARIO
            'propietario_nombre'   => 'required|string|max:255',
            'propietario_email'    => 'required|email|unique:users,email',
            'propietario_password' => 'required|string|min:8|confirmed',
        ]);

        // 1. Crear el restaurante
        $restaurante = new Restaurante($request->except([
            'galeria', 'imagen_principal',
            'propietario_nombre', 'propietario_email',
            'propietario_password', 'propietario_password_confirmation'
        ]));

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

                RestauranteFoto::create([
                    'restaurante_id' => $restaurante->id,
                    'ruta_foto'      => $pathFoto
                ]);
            }
        }

        // 4. Crear el usuario propietario y vincularlo al restaurante
        User::create([
            'name'           => $request->propietario_nombre,
            'email'          => $request->propietario_email,
            'password'       => Hash::make($request->propietario_password),
            'role'           => 'restaurante',
            'restaurante_id' => $restaurante->id,
        ]);

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante y usuario propietario creados correctamente.');
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

        // Usuarios con rol restaurante (sin asignar o el actual)
        $usuarios = User::where('role', 'restaurante')
            ->where(function ($q) use ($restaurante) {
                $q->whereNull('restaurante_id')
                  ->orWhere('restaurante_id', $restaurante->id);
            })
            ->get();

        return view('restaurantes.edit', compact('restaurante', 'departamentos', 'usuarios'));
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
            'direccion'        => 'nullable|string|max:255',
            'latitud'          => 'nullable|numeric|between:-90,90',
            'longitud'         => 'nullable|numeric|between:-180,180',
            'user_id'          => 'nullable|exists:users,id',
        ]);

        // 1. Asignar los campos básicos
        $restaurante->fill($request->except(['galeria', 'imagen_principal', 'user_id']));

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

                RestauranteFoto::create([
                    'restaurante_id' => $restaurante->id,
                    'ruta_foto'      => $pathFoto
                ]);
            }
        }

        // 4. Actualizar el usuario vinculado al restaurante
        if ($request->filled('user_id')) {
            User::where('restaurante_id', $restaurante->id)
                ->where('id', '!=', $request->user_id)
                ->update(['restaurante_id' => null]);

            User::where('id', $request->user_id)
                ->update(['restaurante_id' => $restaurante->id]);
        }

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy(Restaurante $restaurante)
    {
        if ($restaurante->foto_portada) {
            Storage::disk('public')->delete($restaurante->foto_portada);
        }

        // ✅ Eliminar el usuario propietario al borrar el restaurante
        User::where('restaurante_id', $restaurante->id)->delete();

        $restaurante->delete();

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
