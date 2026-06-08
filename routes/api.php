<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evento;
use App\Models\Restaurante;
use App\Models\Departamento;
use App\Models\Empleo;
use App\Models\Gastrobar;
use App\Models\Municipio;
use App\Models\Review;

// ── FUNCIÓN PARA ENVIAR NOTIFICACIONES PUSH ──
function enviarNotificacionFCM(string $titulo, string $cuerpo): void
{
    try {
        $credencialesPath = storage_path('app/firebase-credentials.json');
        $credenciales = json_decode(file_get_contents($credencialesPath), true);

        // Obtener Access Token de Google
        $tokenResponse = Http::post('https://oauth2.googleapis.com/token', [
            'grant_type'            => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'             => generarJwtFirebase($credenciales),
        ]);

        $accessToken = $tokenResponse->json('access_token');
        $projectId   = $credenciales['project_id'];

        // Enviar notificación a todos los usuarios (topic)
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
        // No interrumpir la respuesta si falla la notificación
        \Log::error('FCM error: ' . $e->getMessage());
    }
}

function generarJwtFirebase(array $credenciales): string
{
    $ahora    = time();
    $expira   = $ahora + 3600;

    $header  = base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $payload = base64UrlEncode(json_encode([
        'iss'   => $credenciales['client_email'],
        'sub'   => $credenciales['client_email'],
        'aud'   => 'https://oauth2.googleapis.com/token',
        'iat'   => $ahora,
        'exp'   => $expira,
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    ]));

    $firma = '';
    openssl_sign(
        "$header.$payload",
        $firma,
        $credenciales['private_key'],
        'SHA256'
    );

    return "$header.$payload." . base64UrlEncode($firma);
}

function base64UrlEncode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

Route::get('/mensaje-prueba', function () {
    return response()->json(['texto' => '¡Hola desde mi base de datos de Laravel!']);
});

// Departamentos
Route::get('/departamentos', function () {
    return Departamento::orderBy('nombre')->get();
});

// Municipios por departamento
Route::get('/municipios/{departamento_id}', function ($departamento_id) {
    return Municipio::where('departamento_id', $departamento_id)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
});

// Restaurantes por municipio
Route::get('/restaurantes/por-municipio/{municipio_id}', function ($municipio_id) {
    return Restaurante::where('municipio_id', $municipio_id)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
});

// Restaurantes (todos)
Route::get('/restaurantes', function () {
    return Restaurante::with('departamento')->get();
});

// Restaurantes con filtros
Route::get('/restaurantes/buscar', function (Request $request) {
    $query = Restaurante::with('departamento');

    if ($request->filled('search')) {
        $query->where('nombre', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('departamento')) {
        $query->where('departamento_id', $request->departamento);
    }
    if ($request->filled('municipio')) {
        $query->where('municipio_id', $request->municipio);
    }
    if ($request->filled('especialidad')) {
        $query->where('especialidad', 'like', '%' . $request->especialidad . '%');
    }

    return $query->orderBy('nombre')->paginate(12);
});

// Detalle de un restaurante
Route::get('/restaurantes/{id}', function ($id) {
    return Restaurante::with([
        'departamento',
        'municipio',
        'imagenes',
        'reviews.user'
    ])->findOrFail($id);
});

// ── ENVIAR RESEÑA ──
Route::post('/restaurantes/{id}/reviews', function (Request $request, $id) {
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'body'   => 'nullable|string|max:1000',
    ]);

    $restaurante = Restaurante::findOrFail($id);

    $existe = $restaurante->reviews()
        ->where('user_id', $request->user()->id)
        ->exists();

    if ($existe) {
        return response()->json(['message' => 'Ya dejaste una reseña.'], 422);
    }

    $restaurante->reviews()->create([
        'rating'         => $request->rating,
        'body'           => $request->body,
        'user_id'        => $request->user()->id,
        'restaurante_id' => $id,
    ]);

    return response()->json(['message' => '¡Reseña publicada!']);
})->middleware('auth:sanctum');

// Gastrobares (todos)
Route::get('/gastrobares', function () {
    return Gastrobar::with('departamento')->get();
});

// Gastrobares con filtros
Route::get('/gastrobares/buscar', function (Request $request) {
    $query = Gastrobar::with(['departamento']);

    if ($request->filled('search')) {
        $query->where('nombre', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('departamento')) {
        $query->where('departamento_id', $request->departamento);
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

    return $query->latest()->paginate(12);
});

// ── DETALLE DE UN GASTROBAR ──
Route::get('/gastrobares/{id}', function ($id) {
    return Gastrobar::with([
        'departamento',
        'municipio',
    ])->findOrFail($id);
});

// Eventos destacados
Route::get('/eventos/destacados', function () {
    return Evento::with(['restaurante', 'departamento'])
        ->where('is_destacado', true)
        ->limit(5)
        ->get();
});

// Eventos con filtros
Route::get('/eventos', function (Request $request) {
    $query = Evento::with(['restaurante', 'departamento']);

    if ($request->filled('departamento')) {
        $query->where('departamento_id', $request->departamento);
    }
    if ($request->filled('especialidad')) {
        $query->whereHas('restaurante', fn($q) =>
            $q->where('especialidad', 'like', '%' . $request->especialidad . '%')
        );
    }
    if ($request->filled('restaurante_id')) {
        $query->where('restaurante_id', $request->restaurante_id);
    }

    return $query->orderBy('fecha_evento')->paginate(10);
});

// ── DETALLE DE UN EVENTO ──
Route::get('/eventos/{id}', function ($id) {
    return Evento::with([
        'restaurante',
        'departamento',
        'municipio',
    ])->findOrFail($id);
});

// Empleos con filtros
Route::get('/empleos', function (Request $request) {
    $query = Empleo::with(['restaurante', 'departamento'])
        ->where('activo', true);

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('titulo', 'like', '%' . $request->search . '%')
              ->orWhere('descripcion', 'like', '%' . $request->search . '%');
        });
    }
    if ($request->filled('departamento')) {
        $query->where('departamento_id', $request->departamento);
    }

    return $query->orderByDesc('created_at')->paginate(9);
});

// ── DETALLE DE UN EMPLEO ──
Route::get('/empleos/{id}', function ($id) {
    return Empleo::with([
        'restaurante',
        'departamento',
        'municipio',
    ])->findOrFail($id);
});

// ── LOGIN ──
Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        return response()->json(['message' => 'Credenciales incorrectas.'], 401);
    }

    $user = Auth::user();

    return response()->json([
        'user' => [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'role'            => $user->role,
            'departamento_id' => $user->departamento_id,
        ],
        'token' => $user->createToken('react-app')->plainTextToken,
    ]);
});

// ── REGISTER ──
Route::post('/register', function (Request $request) {
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'usuario',
    ]);

    return response()->json([
        'user' => [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'role'            => $user->role,
            'departamento_id' => $user->departamento_id,
        ],
        'token' => $user->createToken('react-app')->plainTextToken,
    ], 201);
});

// ── LOGIN CON GOOGLE (sin librería extra) ──
Route::post('/auth/google', function (Request $request) {
    $request->validate([
        'idToken' => 'required|string',
    ]);

    $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
        'id_token' => $request->idToken,
    ]);

    if ($response->failed() || $response->json('aud') !== env('GOOGLE_CLIENT_ID')) {
        return response()->json(['message' => 'Token de Google inválido.'], 401);
    }

    $payload = $response->json();

    $user = User::firstOrCreate(
        ['email' => $payload['email']],
        [
            'name'     => $payload['name'],
            'password' => Hash::make(str()->random(24)),
            'role'     => 'usuario',
        ]
    );

    return response()->json([
        'user' => [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'role'            => $user->role,
            'departamento_id' => $user->departamento_id,
        ],
        'token' => $user->createToken('android-app')->plainTextToken,
    ]);
});

// ── NOTIFICAR NUEVO RESTAURANTE ──
Route::post('/notificar/restaurante', function (Request $request) {
    $request->validate(['nombre' => 'required|string']);
    enviarNotificacionFCM(
        '🍽️ Nuevo restaurante',
        "¡{$request->nombre} ya está en GastroNicaragua!"
    );
    return response()->json(['message' => 'Notificación enviada.']);
})->middleware('auth:sanctum');

// ── NOTIFICAR NUEVO EVENTO ──
Route::post('/notificar/evento', function (Request $request) {
    $request->validate(['nombre' => 'required|string']);
    enviarNotificacionFCM(
        '🎉 Nuevo evento',
        "¡{$request->nombre} ya está disponible!"
    );
    return response()->json(['message' => 'Notificación enviada.']);
})->middleware('auth:sanctum');

// ── NOTIFICAR NUEVO EMPLEO ──
Route::post('/notificar/empleo', function (Request $request) {
    $request->validate(['titulo' => 'required|string']);
    enviarNotificacionFCM(
        '💼 Nueva oferta de empleo',
        "¡{$request->titulo} está buscando personal!"
    );
    return response()->json(['message' => 'Notificación enviada.']);
})->middleware('auth:sanctum');

// ── NOTIFICAR NUEVO GASTROBAR ──
Route::post('/notificar/gastrobar', function (Request $request) {
    $request->validate(['nombre' => 'required|string']);
    enviarNotificacionFCM(
        '🍹 Nuevo gastrobar',
        "¡{$request->nombre} ya está en GastroNicaragua!"
    );
    return response()->json(['message' => 'Notificación enviada.']);
})->middleware('auth:sanctum');

// ── LOGOUT ──
Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Sesión cerrada.']);
})->middleware('auth:sanctum');

// ── USUARIO AUTENTICADO ──
Route::get('/me', function (Request $request) {
    return response()->json($request->user());
})->middleware('auth:sanctum');

// ── GUARDAR DEPARTAMENTO DEL USUARIO ──
Route::post('/usuario/departamento', function (Request $request) {
    $request->validate([
        'departamento_id' => 'required|exists:departamentos,id',
    ]);

    $request->user()->update([
        'departamento_id' => $request->departamento_id,
    ]);

    return response()->json([
        'message' => 'Departamento guardado correctamente.',
        'user' => [
            'id'              => $request->user()->id,
            'name'            => $request->user()->name,
            'email'           => $request->user()->email,
            'role'            => $request->user()->role,
            'departamento_id' => $request->user()->departamento_id,
        ],
    ]);
})->middleware('auth:sanctum');
