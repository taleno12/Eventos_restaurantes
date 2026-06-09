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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RestauranteController extends Controller
{
    private function enviarNotificacionFCM(string $titulo, string $cuerpo): void
    {
        try {
            $credencialesPath = storage_path('app/firebase-credentials.json');
            $credenciales     = json_decode(file_get_contents($credencialesPath), true);

            $ahora   = time();
            $expira  = $ahora + 3600;

            $header  = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = $this->base64UrlEncode(json_encode([
                'iss'   => $credenciales['client_email'],
                'sub'   => $credenciales['client_email'],
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $ahora,
                'exp'   => $expira,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            ]));

            $firma = '';
            openssl_sign("$header.$payload", $firma, $credenciales['private_key'], 'SHA256');
            $jwt = "$header.$payload." . $this->base64UrlEncode($firma);

            $tokenResponse = Http::post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            $accessToken = $tokenResponse->json('access_token');
            $projectId   = $credenciales['project_id'];

            Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'topic'        => 'gastronic_todos',
                        'notification' => [
                            'title' => $titulo,
                            'body'  => $cuerpo,
                        ],
                    ],
                ]);
        } catch (\Exception $e) {
            \Log::error('FCM error: ' . $e->getMessage());
        }
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS PÚBLICOS
    // ─────────────────────────────────────────────────────────────────────────

    public function publicIndex(Request $request)
    {
        $departamentoPredefinido = Auth::check() ? Auth::user()->departamento_id : null;
        $municipioPredefinido    = Auth::check() ? Auth::user()->municipio_id    : null;

        $hayFiltroActivo = $request->hasAny(['departamento', 'municipio', 'especialidad', 'search']);

        $deptoFiltro = $hayFiltroActivo
            ? ($request->filled('departamento') ? $request->departamento : null)
            : $departamentoPredefinido;

        $munFiltro = $hayFiltroActivo
            ? ($request->filled('municipio') ? $request->municipio : null)
            : $municipioPredefinido;

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

        if ($munFiltro) {
            $query->where('municipio_id', $munFiltro);
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
            'departamentoPredefinido',
            'municipioPredefinido'
        ));
    }

    public function publicShow(Restaurante $restaurante)
    {
        $restaurante->load(['departamento', 'municipio', 'imagenes']);

        return view('restaurantes.public_show', compact('restaurante'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS DE ADMINISTRACIÓN
    // ─────────────────────────────────────────────────────────────────────────

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
            'dias_laborales'    => 'nullable|array',
            'dias_laborales.*'  => 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_apertura'     => 'nullable|date_format:H:i',
            'hora_cierre'       => 'nullable|date_format:H:i',
            'propietario_nombre'   => 'required|string|max:255',
            'propietario_email'    => 'required|email|unique:users,email',
            'propietario_password' => 'required|string|min:8|confirmed',
        ]);

        $restaurante = new Restaurante($request->except([
            'galeria', 'imagen_principal',
            'propietario_nombre', 'propietario_email',
            'propietario_password', 'propietario_password_confirmation'
        ]));

        if ($request->hasFile('imagen_principal')) {
            $pathPrincipal = $request->file('imagen_principal')->store('restaurantes/principales', 'public');
            $restaurante->foto_portada = $pathPrincipal;
        }

        $restaurante->save();

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $foto) {
                $pathFoto = $foto->store('restaurantes/galerias', 'public');
                RestauranteFoto::create([
                    'restaurante_id' => $restaurante->id,
                    'ruta_foto'      => $pathFoto
                ]);
            }
        }

        User::create([
            'name'           => $request->propietario_nombre,
            'email'          => $request->propietario_email,
            'password'       => Hash::make($request->propietario_password),
            'role'           => 'restaurante',
            'restaurante_id' => $restaurante->id,
        ]);

        $this->enviarNotificacionFCM(
            '🍽️ Nuevo restaurante',
            "¡{$restaurante->nombre} ya está en GastroNicaragua!"
        );

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
        $restaurante->load(['imagenes', 'propietario']);
        return view('restaurantes.edit', compact('restaurante', 'departamentos'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        $propietario = User::where('restaurante_id', $restaurante->id)
            ->where('role', 'restaurante')
            ->first();

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
            'dias_laborales'   => 'nullable|array',
            'dias_laborales.*' => 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_apertura'    => 'nullable|date_format:H:i',
            'hora_cierre'      => 'nullable|date_format:H:i',
            'propietario_nombre'   => 'required|string|max:255',
            'propietario_email'    => 'required|email|unique:users,email,' . ($propietario->id ?? 'NULL'),
            'propietario_password' => 'nullable|string|min:8|confirmed',
        ]);

        $restaurante->fill($request->except([
            'galeria', 'imagen_principal',
            'propietario_nombre', 'propietario_email',
            'propietario_password', 'propietario_password_confirmation'
        ]));

        if ($request->hasFile('imagen_principal')) {
            if ($restaurante->foto_portada) {
                Storage::disk('public')->delete($restaurante->foto_portada);
            }
            $pathPrincipal = $request->file('imagen_principal')->store('restaurantes/principales', 'public');
            $restaurante->foto_portada = $pathPrincipal;
        }

        $restaurante->save();

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $foto) {
                $pathFoto = $foto->store('restaurantes/galerias', 'public');
                RestauranteFoto::create([
                    'restaurante_id' => $restaurante->id,
                    'ruta_foto'      => $pathFoto
                ]);
            }
        }

        if ($propietario) {
            $propietario->name  = $request->propietario_nombre;
            $propietario->email = $request->propietario_email;
            if ($request->filled('propietario_password')) {
                $propietario->password = Hash::make($request->propietario_password);
            }
            $propietario->save();
        }

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy(Restaurante $restaurante)
    {
        if ($restaurante->foto_portada) {
            Storage::disk('public')->delete($restaurante->foto_portada);
        }

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
