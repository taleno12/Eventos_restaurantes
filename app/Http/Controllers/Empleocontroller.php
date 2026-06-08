<?php

namespace App\Http\Controllers;

use App\Models\Empleo;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Mail\AplicacionEmpleo;

class EmpleoController extends Controller
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
    // VISTAS PÚBLICAS
    // ─────────────────────────────────────────────────────────────────────────

    public function publicIndex(Request $request)
    {
        $departamentoPredefinido = auth()->check()
            ? auth()->user()->departamento_id
            : null;

        $hayFiltroActivo = $request->hasAny(['departamento', 'search']);

        $deptoFiltro = $hayFiltroActivo
            ? ($request->filled('departamento') ? $request->input('departamento') : null)
            : $departamentoPredefinido;

        $query = Empleo::with(['restaurante', 'departamento', 'municipio'])
            ->where('activo', true)
            ->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhereHas('restaurante', fn($r) => $r->where('nombre', 'like', "%{$search}%"));
            });
        }

        if ($deptoFiltro) {
            $query->where('departamento_id', $deptoFiltro);
        }

        $empleos            = $query->paginate(9)->withQueryString();
        $departamentos      = Departamento::orderBy('nombre')->get();
        $totalRestaurantes  = Restaurante::count();
        $totalDepartamentos = Departamento::count();
        $totalActivos       = Empleo::where('activo', true)->count();

        return view('empleos', compact(
            'empleos',
            'departamentos',
            'totalRestaurantes',
            'totalDepartamentos',
            'totalActivos',
            'departamentoPredefinido'
        ));
    }

    public function show(Empleo $empleo)
    {
        abort_unless($empleo->activo, 404);
        $empleo->load(['restaurante', 'departamento', 'municipio']);
        return view('empleos-show', compact('empleo'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // APLICACIÓN A VACANTE (PÚBLICA)
    // ─────────────────────────────────────────────────────────────────────────

    public function aplicar(Request $request, Empleo $empleo)
    {
        abort_unless($empleo->activo, 404);

        $validated = $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'email'            => 'required|email|max:150',
            'telefono'         => 'required|string|max:20',
            'edad'             => 'required|integer|min:18|max:70',
            'municipio'        => 'required|string|max:100',
            'experiencia'      => 'nullable|string|max:1000',
            'disponibilidad'   => 'nullable|array',
            'disponibilidad.*' => 'string',
            'curriculum'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'mensaje'          => 'nullable|string|max:500',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido.required'  => 'El apellido es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'Ingresa un correo válido.',
            'telefono.required'  => 'El teléfono es obligatorio.',
            'edad.required'      => 'La edad es obligatoria.',
            'edad.min'           => 'Debes tener al menos 18 años.',
            'municipio.required' => 'El municipio es obligatorio.',
            'curriculum.mimes'   => 'El currículum debe ser PDF, DOC o DOCX.',
            'curriculum.max'     => 'El archivo no puede superar los 5MB.',
        ]);

        $empleo->loadMissing('restaurante');

        $aplicacion = [
            'nombre'         => $validated['nombre'],
            'apellido'       => $validated['apellido'],
            'email'          => $validated['email'],
            'telefono'       => $validated['telefono'],
            'edad'           => $validated['edad'],
            'municipio'      => $validated['municipio'],
            'experiencia'    => $validated['experiencia'] ?? 'No especificada',
            'disponibilidad' => $validated['disponibilidad'] ?? [],
            'mensaje'        => $validated['mensaje'] ?? '',
            'empleo_titulo'  => $empleo->titulo,
            'restaurante'    => $empleo->restaurante->nombre ?? 'Restaurante',
        ];

        $curriculumData   = null;
        $curriculumNombre = null;
        $curriculumMime   = null;

        if ($request->hasFile('curriculum') && $request->file('curriculum')->isValid()) {
            $archivo   = $request->file('curriculum');
            $extension = strtolower($archivo->getClientOriginalExtension());

            $mimeTypes = [
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];

            $curriculumData   = file_get_contents($archivo->getRealPath());
            $curriculumNombre = 'curriculum_' . str_replace(' ', '_', $validated['nombre']) . '.' . $extension;
            $curriculumMime   = $mimeTypes[$extension] ?? 'application/octet-stream';

            $archivo->store('curriculos', 'local');
        }

        $emailRestaurante = $empleo->restaurante->email ?? config('mail.from.address');

        try {
            Mail::to($emailRestaurante)
                ->send(new AplicacionEmpleo(
                    $aplicacion,
                    $curriculumData,
                    $curriculumNombre,
                    $curriculumMime,
                    'restaurante'
                ));

            Mail::to($validated['email'])
                ->send(new AplicacionEmpleo(
                    $aplicacion,
                    null,
                    null,
                    null,
                    'candidato'
                ));

        } catch (\Exception $e) {
            Log::error('Error enviando correo de aplicación: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al enviar tu aplicación. Por favor intenta de nuevo.');
        }

        return back()->with('success', '¡Tu aplicación fue enviada con éxito! Revisa tu correo para la confirmación.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PANEL DE ADMINISTRACIÓN
    // ─────────────────────────────────────────────────────────────────────────

    public function index()
    {
        $empleos                = Empleo::with(['restaurante', 'departamento', 'municipio'])->latest()->paginate(15);
        $activas                = Empleo::where('activo', true)->count();
        $restaurantesConOfertas = Empleo::distinct('restaurante_id')->count('restaurante_id');

        return view('empleos.index', compact('empleos', 'activas', 'restaurantesConOfertas'));
    }

    public function create()
    {
        $restaurantes  = Restaurante::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('empleos.create', compact('restaurantes', 'departamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id',
            'titulo'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'requisitos'      => 'nullable|string',
            'tipo_contrato'   => 'nullable|string|max:50',
            'salario'         => 'nullable|numeric|min:0',
            'fecha_limite'    => 'nullable|date|after:today',
            'activo'          => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->input('activo', 0) == 1 ? 1 : 0;

        $empleo = Empleo::create($validated);

        // ── Notificar a todos los usuarios de la app ──
        $this->enviarNotificacionFCM(
            '💼 Nueva oferta de empleo',
            "¡{$empleo->titulo} está disponible en GastroNicaragua!"
        );

        return redirect()->route('admin.empleos.index')
            ->with('success', '✅ Oferta publicada exitosamente.');
    }

    public function adminShow(Empleo $empleo)
    {
        $empleo->load(['restaurante', 'departamento', 'municipio']);
        return view('empleos.show', compact('empleo'));
    }

    public function edit(Empleo $empleo)
    {
        $restaurantes  = Restaurante::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $empleo->departamento_id)->orderBy('nombre')->get();
        return view('empleos.edit', compact('empleo', 'restaurantes', 'departamentos', 'municipios'));
    }

    public function update(Request $request, Empleo $empleo)
    {
        $validated = $request->validate([
            'restaurante_id'  => 'required|exists:restaurantes,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id'    => 'required|exists:municipios,id',
            'titulo'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'requisitos'      => 'nullable|string',
            'tipo_contrato'   => 'nullable|string|max:50',
            'salario'         => 'nullable|numeric|min:0',
            'fecha_limite'    => 'nullable|date',
            'activo'          => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->input('activo', 0) == 1 ? 1 : 0;

        $empleo->update($validated);

        return redirect()->route('admin.empleos.index')
            ->with('success', '✅ Oferta actualizada correctamente.');
    }

    public function destroy(Empleo $empleo)
    {
        $empleo->delete();
        return redirect()->route('admin.empleos.index')
            ->with('success', '🗑️ Oferta eliminada.');
    }
}
