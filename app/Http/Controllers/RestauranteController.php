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

    /**
     * Crea el usuario propietario según el método de acceso elegido
     * en el formulario: 'google' o 'telefono'.
     */
    private function crearUsuarioPropietario(Request $request, Restaurante $restaurante): void
    {
        if ($request->metodo_acceso === 'telefono') {
            User::create([
                'name'           => $request->propietario_nombre,
                'email'          => $request->propietario_telefono . '@telefono.gastronicaragua.local',
                'telefono'       => $request->propietario_telefono,
                'password'       => Hash::make($request->propietario_password),
                'role'           => 'restaurante',
                'restaurante_id' => $restaurante->id,
            ]);
        } else {
            User::create([
                'name'           => $request->propietario_nombre,
                'email'          => $request->propietario_email,
                'password'       => Hash::make(uniqid()),
                'role'           => 'restaurante',
                'restaurante_id' => $restaurante->id,
            ]);
        }
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

        $query = Restaurante::activos()
            ->with(['departamento', 'municipio'])
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
        if (!$restaurante->activo) {
            abort(404);
        }

        $restaurante->load(['departamento', 'municipio', 'imagenes']);

        $platos = \App\Models\Plato::where('restaurante_id', $restaurante->id)
            ->where('activo', true)
            ->with('categoriaPlato')
            ->orderBy('orden')
            ->get()
            ->groupBy(fn($plato) => $plato->categoriaPlato?->nombre ?? 'Sin categoría');

        return view('restaurantes.public_show', compact('restaurante', 'platos'));
    }

    public function ordenar(Restaurante $restaurante)
    {
        if (!$restaurante->activo) {
            abort(404);
        }

        $platos = $restaurante->platos()
            ->where('activo', true)
            ->with(['categoriaPlato', 'opciones.valores'])
            ->orderBy('orden')
            ->get()
            ->groupBy(fn($plato) => $plato->categoriaPlato?->nombre ?? 'Sin categoría');

        return view('restaurantes.ordenar', compact('restaurante', 'platos'));
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
            'telefono'         => 'nullable|string|max:50',
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

            // ── Propietario: método de acceso ──
            'propietario_nombre' => 'required|string|max:255',
            'metodo_acceso'      => 'required|in:google,telefono',

            'propietario_email' => 'required_if:metodo_acceso,google|nullable|email|unique:users,email',

            'propietario_telefono' => 'required_if:metodo_acceso,telefono|nullable|string|max:20|unique:users,telefono',
            'propietario_password' => 'required_if:metodo_acceso,telefono|nullable|string|min:8',
        ], [
            'propietario_email.required_if'    => 'El correo es obligatorio si el acceso es por Google.',
            'propietario_telefono.required_if' => 'El teléfono es obligatorio si el acceso es por teléfono.',
            'propietario_password.required_if' => 'La contraseña es obligatoria si el acceso es por teléfono.',
        ]);

        $restaurante = new Restaurante($request->except([
            'galeria', 'imagen_principal',
            'propietario_nombre', 'propietario_email',
            'metodo_acceso', 'propietario_telefono', 'propietario_password',
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

        $this->crearUsuarioPropietario($request, $restaurante);

        $this->enviarNotificacionFCM(
            'Nuevo restaurante',
            "¡{$restaurante->nombre} ya está en GastroNicaragua!"
        );

        $mensajeAcceso = $request->metodo_acceso === 'telefono'
            ? "El propietario accederá con su número de teléfono y la contraseña asignada."
            : "El propietario accederá con su cuenta de Google.";

        return redirect()->route('admin.restaurantes.index')
            ->with('success', "Restaurante y usuario propietario creados correctamente. {$mensajeAcceso}");
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
            'telefono'        => 'nullable|string|max:50',
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

            // ── Propietario: método de acceso ──
            'propietario_nombre' => 'required|string|max:255',
            'metodo_acceso'      => 'required|in:google,telefono',

            'propietario_email' => 'required_if:metodo_acceso,google|nullable|email|unique:users,email,' . ($propietario->id ?? 'NULL'),

            'propietario_telefono' => 'required_if:metodo_acceso,telefono|nullable|string|max:20|unique:users,telefono,' . ($propietario->id ?? 'NULL'),
            'propietario_password' => 'nullable|string|min:8',
        ], [
            'propietario_email.required_if'    => 'El correo es obligatorio si el acceso es por Google.',
            'propietario_telefono.required_if' => 'El teléfono es obligatorio si el acceso es por teléfono.',
        ]);

        $restaurante->fill($request->except([
            'galeria', 'imagen_principal',
            'propietario_nombre', 'propietario_email',
            'metodo_acceso', 'propietario_telefono', 'propietario_password',
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
            $propietario->name = $request->propietario_nombre;

            if ($request->metodo_acceso === 'telefono') {
                $propietario->telefono = $request->propietario_telefono;
                $propietario->email    = $request->propietario_telefono . '@telefono.gastronicaragua.local';

                if ($request->filled('propietario_password')) {
                    $propietario->password = Hash::make($request->propietario_password);
                }
            } else {
                $propietario->email    = $request->propietario_email;
                $propietario->telefono = null;
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

    public function toggleActivo(Restaurante $restaurante)
    {
        $restaurante->update(['activo' => !$restaurante->activo]);

        User::where('restaurante_id', $restaurante->id)
            ->where('role', 'restaurante')
            ->update(['estado' => $restaurante->activo ? 'activo' : 'suspendido']);

        return redirect()->back()->with('success',
            $restaurante->activo
                ? 'Restaurante activado correctamente.'
                : 'Restaurante desactivado. Ya no aparecerá en la vista pública y su propietario no podrá acceder al panel.'
        );
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
