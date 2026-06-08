<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class EventoController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // HELPER FCM
    // ─────────────────────────────────────────────────────────────────────────

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

    public function welcome(Request $request)
    {
        $departamentos = Departamento::all();

        $departamentoPredefinido = auth()->check()
            ? auth()->user()->departamento_id
            : null;

        $hayFiltroActivo = $request->hasAny(['departamento', 'restaurante_id', 'especialidad']);

        $deptoFiltro = $hayFiltroActivo
            ? ($request->filled('departamento') ? $request->departamento : null)
            : $departamentoPredefinido;

        $restaurantes = Restaurante::orderBy('nombre')
            ->when($deptoFiltro, fn($q) => $q->where('departamento_id', $deptoFiltro))
            ->when($request->filled('especialidad'), fn($q) =>
                $q->where('especialidad', 'LIKE', '%' . $request->especialidad . '%')
            )
            ->get();

        $eventosDestacados = Evento::with(['restaurante', 'departamento'])
            ->where('is_destacado', true)
            ->when($deptoFiltro, fn($q) => $q->where('departamento_id', $deptoFiltro))
            ->latest()
            ->get();

        $query = Evento::with(['restaurante', 'departamento']);

        if ($deptoFiltro) {
            $query->where('departamento_id', $deptoFiltro);
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

        if ($eventos->isEmpty() && !$hayFiltroActivo && !$departamentoPredefinido) {
            $eventos = Evento::with(['restaurante', 'departamento'])->latest()->take(6)->get();
        }

        return view('welcome', compact(
            'eventos',
            'eventosDestacados',
            'departamentos',
            'restaurantes',
            'departamentoPredefinido'
        ));
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

        // ── Notificar a todos los usuarios de la app ──
        $this->enviarNotificacionFCM(
            '📅 Nuevo evento',
            "¡{$evento->titulo} ya está disponible en GastroNicaragua!"
        );

        return redirect()->route('eventos.index')
            ->with('success', 'Evento publicado con éxito.');
    }

    public function edit(Evento $evento)
    {
        $this->soloAdmin();
        $evento->load('imagenes');
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $evento->departamento_id)->orderBy('nombre')->get();
        $restaurantes  = Restaurante::where('departamento_id', $evento->departamento_id)->orderBy('nombre')->get();
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
            'galeria.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = $request->except(['imagen', 'galeria']);
        $datos['is_destacado'] = $request->boolean('is_destacado', false);

        if ($request->hasFile('imagen')) {
            if ($evento->imagen) {
                Storage::disk('public')->delete($evento->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $evento->update($datos);

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
