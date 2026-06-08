<?php

namespace App\Http\Controllers;

use App\Models\Gastrobar;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class GastrobarController extends Controller
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

    // ── INDEX ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Gastrobar::with(['departamento', 'municipio'])->latest();

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('departamento_id')) {
            $query->where('departamento_id', $request->departamento_id);
        }

        $gastrobares        = $query->paginate(10);
        $activos            = Gastrobar::where('activo', true)->count();
        $totalDepartamentos = Gastrobar::distinct('departamento_id')->count('departamento_id');
        $departamentos      = Departamento::orderBy('nombre')->get();

        return view('gastrobares.index', compact(
            'gastrobares',
            'activos',
            'totalDepartamentos',
            'departamentos'
        ));
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

        if ($request->hasFile('imagen_principal')) {
            $data['imagen_principal'] = $request->file('imagen_principal')
                ->store('gastrobares/portadas', 'public');
        }

        if ($request->hasFile('galeria')) {
            $galeria = [];
            foreach ($request->file('galeria') as $foto) {
                if ($foto) {
                    $galeria[] = $foto->store('gastrobares/galeria', 'public');
                }
            }
            $data['galeria'] = $galeria;
        }

        $gastrobar = Gastrobar::create($data);

        // ── Notificar a todos los usuarios de la app ──
        $this->enviarNotificacionFCM(
            '🍹 Nuevo gastrobar',
            "¡{$gastrobar->nombre} ya está en GastroNicaragua!"
        );

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

        if ($request->hasFile('imagen_principal')) {
            if ($gastrobar->imagen_principal) {
                Storage::disk('public')->delete($gastrobar->imagen_principal);
            }
            $data['imagen_principal'] = $request->file('imagen_principal')
                ->store('gastrobares/portadas', 'public');
        }

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
        $departamentoPredefinido = null;
        if (auth()->check() && auth()->user()->departamento_id) {
            $departamentoPredefinido = auth()->user()->departamento_id;
        }

        $query = Gastrobar::with(['departamento', 'municipio'])->latest();

        $deptoActivo = $request->departamento ?? $departamentoPredefinido;
        if ($deptoActivo) {
            $query->where('departamento_id', $deptoActivo);
        }
        if ($request->filled('municipio')) {
            $query->where('municipio_id', $request->municipio);
        }
        if ($request->filled('tipo_bar')) {
            $query->where('tipo_bar', $request->tipo_bar);
        }
        if ($request->filled('ambiente')) {
            $query->where('ambiente', $request->ambiente);
        }
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $gastrobares   = $query->paginate(12)->withQueryString();
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::orderBy('nombre')->get();

        return view('gastrobares.public_index', compact(
            'gastrobares',
            'departamentos',
            'municipios',
            'departamentoPredefinido'
        ));
    }

    // ── PUBLIC SHOW ──────────────────────────────────────────────
    public function publicShow(Gastrobar $gastrobar)
    {
        $gastrobar->load(['departamento', 'municipio']);
        return view('gastrobares.public_show', compact('gastrobar'));
    }
}
