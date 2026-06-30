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
use App\Models\Plato;
use App\Models\Pedido;
use App\Models\PedidoGastrobar;
use App\Models\SolicitudEmpleo;
use App\Models\Reporte;
use Illuminate\Support\Facades\DB;

// ── FUNCIÓN PARA ENVIAR NOTIFICACIONES PUSH ──
function enviarNotificacionFCM(string $titulo, string $cuerpo): void
{
    try {
        $credencialesPath = storage_path('app/firebase-credentials.json');
        $credenciales = json_decode(file_get_contents($credencialesPath), true);

        $tokenResponse = Http::post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => generarJwtFirebase($credenciales),
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

function generarJwtFirebase(array $credenciales): string
{
    $ahora  = time();
    $expira = $ahora + 3600;

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
    openssl_sign("$header.$payload", $firma, $credenciales['private_key'], 'SHA256');

    return "$header.$payload." . base64UrlEncode($firma);
}

function base64UrlEncode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

Route::get('/mensaje-prueba', function () {
    return response()->json(['texto' => '¡Hola desde mi base de datos de Laravel!']);
});

// ── DEPARTAMENTOS ──
Route::get('/departamentos', function () {
    return Departamento::orderBy('nombre')->get();
});

// ── MUNICIPIOS POR DEPARTAMENTO ──
Route::get('/municipios/{departamento_id}', function ($departamento_id) {
    return Municipio::where('departamento_id', $departamento_id)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
});

// ── RESTAURANTES POR MUNICIPIO ──
Route::get('/restaurantes/por-municipio/{municipio_id}', function ($municipio_id) {
    return Restaurante::where('municipio_id', $municipio_id)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
});

// ── RESTAURANTES (todos) ──
Route::get('/restaurantes', function (Request $request) {
    $query = Restaurante::with('departamento')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews');

    $user = $request->user('sanctum');
    if ($user && $user->departamento_id) {
        $query->where('departamento_id', $user->departamento_id);
    }

    return $query->get();
});

// ── RESTAURANTES CON FILTROS MANUALES ──
Route::get('/restaurantes/buscar', function (Request $request) {
    $query = Restaurante::with('departamento')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews');

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

// ── DETALLE DE UN RESTAURANTE ──
Route::get('/restaurantes/{id}', function ($id) {
    return Restaurante::with([
        'departamento',
        'municipio',
        'imagenes',
        'reviews.user'
    ])->findOrFail($id);
});

// ── MENÚ DE UN RESTAURANTE (con opciones y categoría) ──
Route::get('/restaurantes/{id}/platos', function ($id) {
    $platos = Plato::where('restaurante_id', $id)
        ->where('activo', true)
        ->with(['opciones.valores', 'categoriaPlato'])
        ->orderBy('categoria')
        ->orderBy('orden')
        ->get();

    return response()->json($platos);
});

// ── CREAR PEDIDO RESTAURANTE (API) ──
Route::post('/restaurantes/{id}/pedidos', function (Request $request, $id) {
    $request->validate([
        'items'            => 'required|array|min:1',
        'items.*.id'       => 'required|exists:platos,id',
        'items.*.cantidad' => 'required|integer|min:1|max:20',
        'items.*.notas'    => 'nullable|string|max:500',
        'notas'            => 'nullable|string|max:500',
        'tipo'             => 'required|in:envio,retiro',
    ]);

    $restaurante = Restaurante::findOrFail($id);

    $itemsValidados = [];
    $total = 0;

    foreach ($request->items as $item) {
        $plato = Plato::where('id', $item['id'])
            ->where('restaurante_id', $restaurante->id)
            ->where('activo', true)
            ->first();

        if (!$plato) {
            return response()->json(['message' => 'Uno de los platos no está disponible.'], 422);
        }

        $subtotal = $plato->precio * $item['cantidad'];
        $total   += $subtotal;

        $itemsValidados[] = [
            'plato_id'        => $plato->id,
            'cantidad'        => $item['cantidad'],
            'precio_unitario' => $plato->precio,
            'subtotal'        => $subtotal,
            'notas'           => $item['notas'] ?? null,
        ];
    }

    $pedido = null;

    DB::transaction(function () use ($restaurante, $request, $itemsValidados, $total, &$pedido) {
        $pedido = Pedido::create([
            'restaurante_id' => $restaurante->id,
            'user_id'        => $request->user()->id,
            'estado'         => 'pendiente',
            'total'          => $total,
            'notas'          => $request->notas,
            'tipo'           => $request->tipo,
        ]);

        foreach ($itemsValidados as $item) {
            $pedido->items()->create($item);
        }
    });

    return response()->json([
        'message' => '¡Tu pedido fue enviado! El restaurante lo confirmará en breve.',
        'pedido'  => $pedido->load('items.plato'),
    ], 201);
})->middleware('auth:sanctum');

// ── MENÚ DE UN GASTROBAR (con opciones y categoría) ──
Route::get('/gastrobares/{id}/platos', function ($id) {
    $platos = Plato::where('gastrobar_id', $id)
        ->where('activo', true)
        ->with(['opciones.valores'])
        ->orderBy('categoria')
        ->orderBy('orden')
        ->get();

    $categoriasVistas = [];
    $platos = $platos->map(function ($plato) use (&$categoriasVistas) {
        $nombreCat = $plato->categoria;
        if ($nombreCat) {
            if (!isset($categoriasVistas[$nombreCat])) {
                $categoriasVistas[$nombreCat] = count($categoriasVistas) + 1;
            }
            $plato->categoria_plato = [
                'id'     => $categoriasVistas[$nombreCat],
                'nombre' => $nombreCat,
            ];
        } else {
            $plato->categoria_plato = null;
        }
        return $plato;
    });

    return response()->json($platos);
});

// ── CREAR PEDIDO GASTROBAR (API) ──
Route::post('/gastrobares/{id}/pedidos', function (Request $request, $id) {
    $request->validate([
        'items'            => 'required|array|min:1',
        'items.*.id'       => 'required|exists:platos,id',
        'items.*.cantidad' => 'required|integer|min:1|max:20',
        'items.*.notas'    => 'nullable|string|max:500',
        'notas'            => 'nullable|string|max:500',
        'tipo'             => 'required|in:envio,retiro',
    ]);

    $gastrobar = Gastrobar::findOrFail($id);

    $itemsValidados = [];
    $total = 0;

    foreach ($request->items as $item) {
        $plato = Plato::where('id', $item['id'])
            ->where('gastrobar_id', $gastrobar->id)
            ->where('activo', true)
            ->first();

        if (!$plato) {
            return response()->json(['message' => 'Uno de los platos no está disponible.'], 422);
        }

        $subtotal = $plato->precio * $item['cantidad'];
        $total   += $subtotal;

        $itemsValidados[] = [
            'plato_id'        => $plato->id,
            'cantidad'        => $item['cantidad'],
            'precio_unitario' => $plato->precio,
            'subtotal'        => $subtotal,
            'notas'           => $item['notas'] ?? null,
        ];
    }

    $pedido = null;

    DB::transaction(function () use ($gastrobar, $request, $itemsValidados, $total, &$pedido) {
        $pedido = PedidoGastrobar::create([
            'gastrobar_id' => $gastrobar->id,
            'user_id'      => $request->user()->id,
            'estado'       => 'pendiente',
            'total'        => $total,
            'notas'        => $request->notas,
            'tipo'         => $request->tipo,
        ]);

        foreach ($itemsValidados as $item) {
            $pedido->items()->create($item);
        }
    });

    return response()->json([
        'message' => '¡Tu pedido fue enviado! El gastrobar lo confirmará en breve.',
        'pedido'  => $pedido->load('items.plato'),
    ], 201);
})->middleware('auth:sanctum');

// ── MIS PEDIDOS DE RESTAURANTE (API) ──
Route::get('/pedidos/mis', function (Request $request) {
    $pedidos = Pedido::where('user_id', $request->user()->id)
        ->with(['restaurante', 'items.plato'])
        ->orderByDesc('created_at')
        ->get();

    return response()->json($pedidos);
})->middleware('auth:sanctum');

// ── MIS PEDIDOS DE GASTROBAR (API) ──
Route::get('/pedidos-gastrobar/mis', function (Request $request) {
    $pedidos = PedidoGastrobar::where('user_id', $request->user()->id)
        ->with(['gastrobar', 'items.plato'])
        ->orderByDesc('created_at')
        ->get();

    return response()->json($pedidos);
})->middleware('auth:sanctum');

// ── ELIMINAR PEDIDO RESTAURANTE (solo si está cancelado) ──
Route::delete('/pedidos/{id}', function (Request $request, $id) {
    $pedido = Pedido::where('id', $id)
        ->where('user_id', $request->user()->id)
        ->first();

    if (!$pedido) {
        return response()->json(['message' => 'Pedido no encontrado.'], 404);
    }

    if ($pedido->estado !== 'cancelado') {
        return response()->json(['message' => 'Solo puedes eliminar pedidos cancelados.'], 403);
    }

    $pedido->items()->delete();
    $pedido->delete();

    return response()->json(['message' => 'Pedido eliminado correctamente.']);
})->middleware('auth:sanctum');

// ── ELIMINAR PEDIDO GASTROBAR (solo si está cancelado) ──
Route::delete('/pedidos-gastrobar/{id}', function (Request $request, $id) {
    $pedido = PedidoGastrobar::where('id', $id)
        ->where('user_id', $request->user()->id)
        ->first();

    if (!$pedido) {
        return response()->json(['message' => 'Pedido no encontrado.'], 404);
    }

    if ($pedido->estado !== 'cancelado') {
        return response()->json(['message' => 'Solo puedes eliminar pedidos cancelados.'], 403);
    }

    $pedido->items()->delete();
    $pedido->delete();

    return response()->json(['message' => 'Pedido eliminado correctamente.']);
})->middleware('auth:sanctum');

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

// ── GASTROBARES (todos) ──
Route::get('/gastrobares', function (Request $request) {
    $query = Gastrobar::with('departamento')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews');

    $user = $request->user('sanctum');
    if ($user && $user->departamento_id) {
        $query->where('departamento_id', $user->departamento_id);
    }

    return $query->get();
});

// ── GASTROBARES CON FILTROS MANUALES ──
Route::get('/gastrobares/buscar', function (Request $request) {
    $query = Gastrobar::with(['departamento'])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews');

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
        'reviews.user',
    ])->findOrFail($id);
});

// ── ENVIAR RESEÑA GASTROBAR ──
Route::post('/gastrobares/{id}/reviews', function (Request $request, $id) {
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'body'   => 'nullable|string|max:1000',
    ]);

    $gastrobar = Gastrobar::findOrFail($id);

    $existe = $gastrobar->reviews()
        ->where('user_id', $request->user()->id)
        ->exists();

    if ($existe) {
        return response()->json(['message' => 'Ya dejaste una reseña.'], 422);
    }

    $gastrobar->reviews()->create([
        'rating'       => $request->rating,
        'body'         => $request->body,
        'user_id'      => $request->user()->id,
        'gastrobar_id' => $id,
    ]);

    return response()->json(['message' => '¡Reseña publicada!']);
})->middleware('auth:sanctum');

// ── EVENTOS DESTACADOS ──
Route::get('/eventos/destacados', function (Request $request) {
    $query = Evento::with(['restaurante', 'gastrobar', 'departamento'])
        ->where('is_destacado', true)
        ->where('fecha_evento', '>=', now());

    $user = $request->user('sanctum');
    if ($user && $user->departamento_id) {
        $query->where('departamento_id', $user->departamento_id);
    }

    return $query->limit(5)->get();
});

// ── EVENTOS CON FILTROS MANUALES ──
Route::get('/eventos', function (Request $request) {
    $query = Evento::with(['restaurante', 'gastrobar', 'departamento'])
        ->where('fecha_evento', '>=', now());

    if ($request->filled('departamento')) {
        $query->where('departamento_id', $request->departamento);
    } else {
        $user = $request->user('sanctum');
        if ($user && $user->departamento_id) {
            $query->where('departamento_id', $user->departamento_id);
        }
    }

    if ($request->filled('municipio')) {
        $query->where('municipio_id', $request->municipio);
    }

    if ($request->filled('especialidad')) {
        $query->whereHas('restaurante', fn($q) =>
            $q->where('especialidad', 'like', '%' . $request->especialidad . '%')
        );
    }
    if ($request->filled('restaurante_id')) {
        $query->where('restaurante_id', $request->restaurante_id);
    }
    if ($request->filled('gastrobar_id')) {
        $query->where('gastrobar_id', $request->gastrobar_id);
    }

    return $query->orderBy('fecha_evento')->paginate(10);
});

// ── DETALLE DE UN EVENTO ──
Route::get('/eventos/{id}', function ($id) {
    return Evento::with([
        'restaurante',
        'gastrobar',
        'departamento',
        'municipio',
    ])->findOrFail($id);
});

// ── EMPLEOS CON FILTROS ──
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
    } else {
        $user = $request->user('sanctum');
        if ($user && $user->departamento_id) {
            $query->where('departamento_id', $user->departamento_id);
        }
    }

    if ($request->filled('municipio')) {
        $query->where('municipio_id', $request->municipio);
    }

    if ($request->filled('tipo_contrato')) {
        $query->where('tipo_contrato', $request->tipo_contrato);
    }

    return $query->orderByDesc('created_at')->paginate(9);
});

// ── DETALLE DE UN EMPLEO ──
Route::get('/empleos/{id}', function ($id) {
    return Empleo::with([
        'restaurante',
        'gastrobar',
        'departamento',
        'municipio',
    ])->findOrFail($id);
});

// ── POSTULAR A UN EMPLEO (API) ──
Route::post('/empleos/{id}/aplicar', function (Request $request, $id) {
    $empleo = Empleo::findOrFail($id);

    if (!$empleo->activo) {
        return response()->json(['message' => 'Esta vacante ya no está disponible.'], 404);
    }

    $yaAplico = SolicitudEmpleo::where('empleo_id', $empleo->id)
        ->where('email', $request->input('email'))
        ->exists();

    if ($yaAplico) {
        return response()->json([
            'message' => 'Ya enviaste una solicitud para esta vacante. Solo puedes aplicar una vez.'
        ], 422);
    }

    $validated = $request->validate([
        'nombre'      => 'required|string|max:100',
        'apellido'    => 'required|string|max:100',
        'email'       => 'required|email|max:150',
        'telefono'    => 'required|string|max:20',
        'edad'        => 'required|integer|min:18|max:70',
        'municipio'   => 'required|string|max:100',
        'experiencia' => 'nullable|string|max:1000',
        'curriculum'  => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'mensaje'     => 'nullable|string|max:500',
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

    $empleo->loadMissing(['restaurante', 'gastrobar']);

    $nombreEstablecimiento = $empleo->gastrobar->nombre
        ?? $empleo->restaurante->nombre
        ?? 'Establecimiento';

    $aplicacion = [
        'nombre'         => $validated['nombre'],
        'apellido'       => $validated['apellido'],
        'email'          => $validated['email'],
        'telefono'       => $validated['telefono'],
        'edad'           => $validated['edad'],
        'municipio'      => $validated['municipio'],
        'experiencia'    => $validated['experiencia'] ?? 'No especificada',
        'disponibilidad' => [],
        'mensaje'        => $validated['mensaje'] ?? '',
        'empleo_titulo'  => $empleo->titulo,
        'restaurante'    => $nombreEstablecimiento,
    ];

    $curriculumData   = null;
    $curriculumNombre = null;
    $curriculumMime   = null;
    $curriculumPath   = null;

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

        $curriculumPath = $archivo->store('curriculos', 'public');
    }

    SolicitudEmpleo::create([
        'empleo_id'      => $empleo->id,
        'restaurante_id' => $empleo->restaurante_id,
        'gastrobar_id'   => $empleo->gastrobar_id,
        'nombre'         => $validated['nombre'],
        'apellido'       => $validated['apellido'],
        'email'          => $validated['email'],
        'telefono'       => $validated['telefono'],
        'edad'           => $validated['edad'],
        'municipio'      => $validated['municipio'],
        'experiencia'    => $validated['experiencia'] ?? null,
        'disponibilidad' => [],
        'mensaje'        => $validated['mensaje'] ?? null,
        'curriculum'     => $curriculumPath,
        'estado'         => 'nueva',
    ]);

    $emailEstablecimiento = $empleo->gastrobar->email
        ?? $empleo->restaurante->email
        ?? config('mail.from.address');

    try {
        \Illuminate\Support\Facades\Mail::to($emailEstablecimiento)
            ->send(new \App\Mail\AplicacionEmpleo(
                $aplicacion,
                $curriculumData,
                $curriculumNombre,
                $curriculumMime,
                'restaurante'
            ));

        \Illuminate\Support\Facades\Mail::to($validated['email'])
            ->send(new \App\Mail\AplicacionEmpleo(
                $aplicacion,
                null,
                null,
                null,
                'candidato'
            ));
    } catch (\Exception $e) {
        \Log::error('Error enviando correo de aplicación (API): ' . $e->getMessage());
        return response()->json([
            'message' => '¡Tu solicitud fue enviada! (El correo de confirmación no pudo enviarse.)'
        ], 201);
    }

    return response()->json([
        'message' => '¡Tu solicitud fue enviada con éxito!'
    ], 201);
})->middleware('auth:sanctum');

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
            'municipio_id'    => $user->municipio_id,
            'idioma'          => $user->idioma ?? 'es',
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
        'idioma'   => 'es',
    ]);

    return response()->json([
        'user' => [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'role'            => $user->role,
            'departamento_id' => $user->departamento_id,
            'municipio_id'    => $user->municipio_id,
            'idioma'          => $user->idioma,
        ],
        'token' => $user->createToken('react-app')->plainTextToken,
    ], 201);
});

// ── LOGIN CON GOOGLE ──
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
            'idioma'   => 'es',
        ]
    );

    return response()->json([
        'user' => [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'role'            => $user->role,
            'departamento_id' => $user->departamento_id,
            'municipio_id'    => $user->municipio_id,
            'restaurante_id'  => $user->restaurante_id,
            'gastrobar_id'    => $user->gastrobar_id,
            'es_propietario'  => in_array($user->role, ['restaurante', 'gastrobar']),
            'idioma'          => $user->idioma ?? 'es',
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
    $user = $request->user();

    return response()->json([
        'id'              => $user->id,
        'name'            => $user->name,
        'email'           => $user->email,
        'role'            => $user->role,
        'departamento_id' => $user->departamento_id,
        'municipio_id'    => $user->municipio_id,
        'restaurante_id'  => $user->restaurante_id,
        'gastrobar_id'    => $user->gastrobar_id,
        'avatar_url'      => $user->avatar ? asset('storage/' . $user->avatar) : null,
        'idioma'          => $user->idioma ?? 'es',
    ]);
})->middleware('auth:sanctum');

// ── GUARDAR IDIOMA DEL USUARIO ──
Route::put('/usuario/idioma', function (Request $request) {
    $request->validate([
        'idioma' => 'required|in:es,en',
    ]);

    $request->user()->update(['idioma' => $request->idioma]);

    return response()->json(['message' => 'Idioma actualizado.', 'idioma' => $request->idioma]);
})->middleware('auth:sanctum');

// ── GUARDAR DEPARTAMENTO DEL USUARIO ──
Route::post('/usuario/departamento', function (Request $request) {
    $request->validate([
        'departamento_id' => 'required|exists:departamentos,id',
    ]);

    $request->user()->update([
        'departamento_id' => $request->departamento_id,
        'municipio_id'    => null,
    ]);

    return response()->json([
        'message' => 'Departamento guardado correctamente.',
        'user' => [
            'id'              => $request->user()->id,
            'name'            => $request->user()->name,
            'email'           => $request->user()->email,
            'role'            => $request->user()->role,
            'departamento_id' => $request->user()->departamento_id,
            'municipio_id'    => null,
        ],
    ]);
})->middleware('auth:sanctum');

// ── GUARDAR MUNICIPIO DEL USUARIO ──
Route::post('/usuario/municipio', function (Request $request) {
    $request->validate([
        'municipio_id' => 'required|exists:municipios,id',
    ]);

    $request->user()->update([
        'municipio_id' => $request->municipio_id,
    ]);

    return response()->json([
        'message' => 'Municipio guardado correctamente.',
        'user' => [
            'id'              => $request->user()->id,
            'name'            => $request->user()->name,
            'email'           => $request->user()->email,
            'role'            => $request->user()->role,
            'departamento_id' => $request->user()->departamento_id,
            'municipio_id'    => $request->user()->municipio_id,
        ],
    ]);
})->middleware('auth:sanctum');

// ── SUBIR AVATAR DEL USUARIO ──
Route::post('/usuario/avatar', function (Request $request) {
    $request->validate([
        'avatar' => 'required|image|max:2048',
    ]);

    $user = $request->user();

    if ($user->avatar) {
        $rutaAnterior = storage_path('app/public/' . $user->avatar);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }
    }

    $ruta = $request->file('avatar')->store('avatars', 'public');
    $user->update(['avatar' => $ruta]);

    return response()->json([
        'message'    => 'Avatar actualizado.',
        'avatar_url' => asset('storage/' . $ruta),
    ]);
})->middleware('auth:sanctum');

// ── OBTENER PERFIL DEL USUARIO ──
Route::get('/usuario/perfil', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'id'              => $user->id,
        'name'            => $user->name,
        'email'           => $user->email,
        'role'            => $user->role,
        'departamento_id' => $user->departamento_id,
        'municipio_id'    => $user->municipio_id,
        'avatar_url'      => $user->avatar ? asset('storage/' . $user->avatar) : null,
        'es_google'       => !is_null($user->google_id),
        'idioma'          => $user->idioma ?? 'es',
    ]);
})->middleware('auth:sanctum');

// ── ACTUALIZAR NOMBRE DEL USUARIO ──
Route::post('/usuario/nombre', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $request->user()->update(['name' => $request->name]);

    return response()->json([
        'message' => 'Nombre actualizado.',
        'name'    => $request->user()->fresh()->name,
    ]);
})->middleware('auth:sanctum');

// ── REPORTAR UN PROBLEMA (desde la app móvil) ──
Route::post('/reportes', function (Request $request) {
    $request->validate([
        'asunto'  => 'nullable|string|max:255',
        'mensaje' => 'required|string|max:1000',
    ]);

    Reporte::create([
        'user_id' => $request->user()->id,
        'asunto'  => $request->asunto ?? 'Sin asunto',
        'mensaje' => $request->mensaje,
        'estado'  => 'nuevo',
    ]);

    return response()->json(['message' => 'Reporte enviado correctamente.']);
})->middleware('auth:sanctum');

// ════════════════════════════════════════════════════════════
// ── GESTIÓN DEL PROPIETARIO (requiere auth:sanctum) ─────────
// ════════════════════════════════════════════════════════════

Route::middleware('auth:sanctum')->prefix('propietario')->group(function () {

    // ────────────────────────────────────────────────────────
    // EVENTOS
    // ────────────────────────────────────────────────────────

    // Listar mis eventos
    Route::get('/eventos', function (Request $request) {
        $user = $request->user();

        $query = Evento::latest();

        if ($user->role === 'restaurante') {
            $query->where('restaurante_id', $user->restaurante_id);
        } elseif ($user->role === 'gastrobar') {
            $query->where('gastrobar_id', $user->gastrobar_id);
        } else {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($query->paginate(10));
    });

    // Detalle de un evento
    Route::get('/eventos/{id}', function (Request $request, $id) {
        $user   = $request->user();
        $evento = Evento::findOrFail($id);

        if ($user->role === 'restaurante' && $evento->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $evento->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($evento->load(['municipio', 'departamento']));
    });

    // Crear evento
    Route::post('/eventos', function (Request $request) {
        $user = $request->user();

        if (!in_array($user->role, ['restaurante', 'gastrobar'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($user->role === 'restaurante') {
            $entidad = Restaurante::findOrFail($user->restaurante_id);
            $datos = [
                'restaurante_id'  => $user->restaurante_id,
                'departamento_id' => $entidad->departamento_id,
            ];
        } else {
            $entidad = Gastrobar::findOrFail($user->gastrobar_id);
            $datos = [
                'gastrobar_id'    => $user->gastrobar_id,
                'departamento_id' => $entidad->departamento_id,
            ];
        }

        $datos['titulo']       = $request->titulo;
        $datos['descripcion']  = $request->descripcion;
        $datos['precio']       = $request->precio;
        $datos['fecha_evento'] = $request->fecha_evento;
        $datos['municipio_id'] = $request->municipio_id;
        $datos['is_destacado'] = false;

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $evento = Evento::create($datos);

        return response()->json([
            'message' => '¡Evento publicado exitosamente!',
            'evento'  => $evento,
        ], 201);
    });

    // Actualizar evento
    Route::post('/eventos/{id}', function (Request $request, $id) {
        $user   = $request->user();
        $evento = Evento::findOrFail($id);

        if ($user->role === 'restaurante' && $evento->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $evento->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'fecha_evento' => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = [
            'titulo'       => $request->titulo,
            'descripcion'  => $request->descripcion,
            'precio'       => $request->precio,
            'fecha_evento' => $request->fecha_evento,
            'municipio_id' => $request->municipio_id,
            'is_destacado' => false,
        ];

        if ($request->hasFile('imagen')) {
            if ($evento->imagen) \Illuminate\Support\Facades\Storage::disk('public')->delete($evento->imagen);
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $evento->update($datos);

        return response()->json([
            'message' => 'Evento actualizado correctamente.',
            'evento'  => $evento->fresh(),
        ]);
    });

    // Eliminar evento
    Route::delete('/eventos/{id}', function (Request $request, $id) {
        $user   = $request->user();
        $evento = Evento::findOrFail($id);

        if ($user->role === 'restaurante' && $evento->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $evento->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if ($evento->imagen) \Illuminate\Support\Facades\Storage::disk('public')->delete($evento->imagen);
        foreach ($evento->imagenes as $img) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($img->ruta);
            $img->delete();
        }
        $evento->delete();

        return response()->json(['message' => 'Evento eliminado.']);
    });

    // ────────────────────────────────────────────────────────
    // EMPLEOS
    // ────────────────────────────────────────────────────────

    // Listar mis empleos (con conteo de solicitudes nuevas para el badge)
    Route::get('/empleos', function (Request $request) {
        $user = $request->user();

        $query = Empleo::latest()
            ->withCount(['solicitudes as solicitudes_nuevas_count' => function ($q) {
                $q->where('estado', 'nueva');
            }]);

        if ($user->role === 'restaurante') {
            $query->where('restaurante_id', $user->restaurante_id);
        } elseif ($user->role === 'gastrobar') {
            $query->where('gastrobar_id', $user->gastrobar_id);
        } else {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($query->paginate(10));
    });

    // Detalle de un empleo
    Route::get('/empleos/{id}', function (Request $request, $id) {
        $user   = $request->user();
        $empleo = Empleo::findOrFail($id);

        if ($user->role === 'restaurante' && $empleo->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $empleo->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($empleo->load(['municipio', 'departamento']));
    });

    // Crear empleo
    Route::post('/empleos', function (Request $request) {
        $user = $request->user();

        if (!in_array($user->role, ['restaurante', 'gastrobar'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'titulo'        => 'required|string|max:255',
            'descripcion'   => 'required|string',
            'requisitos'    => 'nullable|string',
            'tipo_contrato' => 'nullable|string|max:100',
            'salario'       => 'nullable|numeric|min:0',
            'municipio_id'  => 'required|exists:municipios,id',
            'fecha_limite'  => 'nullable|date',
            'activo'        => 'nullable|boolean',
        ]);

        if ($user->role === 'restaurante') {
            $entidad = Restaurante::findOrFail($user->restaurante_id);
            $datos = [
                'restaurante_id'  => $user->restaurante_id,
                'departamento_id' => $entidad->departamento_id,
            ];
        } else {
            $entidad = Gastrobar::findOrFail($user->gastrobar_id);
            $datos = [
                'gastrobar_id'    => $user->gastrobar_id,
                'departamento_id' => $entidad->departamento_id,
            ];
        }

        $datos['titulo']        = $request->titulo;
        $datos['descripcion']   = $request->descripcion;
        $datos['requisitos']    = $request->requisitos;
        $datos['tipo_contrato'] = $request->tipo_contrato;
        $datos['salario']       = $request->salario;
        $datos['municipio_id']  = $request->municipio_id;
        $datos['fecha_limite']  = $request->fecha_limite;
        $datos['activo']        = $request->boolean('activo', true);

        $empleo = Empleo::create($datos);

        return response()->json([
            'message' => '¡Oferta publicada exitosamente!',
            'empleo'  => $empleo,
        ], 201);
    });

    // Actualizar empleo
    Route::put('/empleos/{id}', function (Request $request, $id) {
        $user   = $request->user();
        $empleo = Empleo::findOrFail($id);

        if ($user->role === 'restaurante' && $empleo->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $empleo->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'titulo'        => 'required|string|max:255',
            'descripcion'   => 'required|string',
            'requisitos'    => 'nullable|string',
            'tipo_contrato' => 'nullable|string|max:100',
            'salario'       => 'nullable|numeric|min:0',
            'municipio_id'  => 'required|exists:municipios,id',
            'fecha_limite'  => 'nullable|date',
            'activo'        => 'nullable|boolean',
        ]);

        $empleo->update([
            'titulo'        => $request->titulo,
            'descripcion'   => $request->descripcion,
            'requisitos'    => $request->requisitos,
            'tipo_contrato' => $request->tipo_contrato,
            'salario'       => $request->salario,
            'municipio_id'  => $request->municipio_id,
            'fecha_limite'  => $request->fecha_limite,
            'activo'        => $request->boolean('activo', false),
        ]);

        return response()->json([
            'message' => 'Oferta actualizada correctamente.',
            'empleo'  => $empleo->fresh(),
        ]);
    });

    // Eliminar empleo
    Route::delete('/empleos/{id}', function (Request $request, $id) {
        $user   = $request->user();
        $empleo = Empleo::findOrFail($id);

        if ($user->role === 'restaurante' && $empleo->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $empleo->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $empleo->delete();

        return response()->json(['message' => 'Oferta eliminada.']);
    });

    // ────────────────────────────────────────────────────────
    // SOLICITUDES DE EMPLEO — gestión del propietario
    // ────────────────────────────────────────────────────────

    // Listar solicitudes de una vacante (y marcar 'nueva' como 'vista')
    Route::get('/empleos/{id}/solicitudes', function (Request $request, $id) {
        $user   = $request->user();
        $empleo = Empleo::findOrFail($id);

        if ($user->role === 'restaurante' && $empleo->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $empleo->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if (!in_array($user->role, ['restaurante', 'gastrobar'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $solicitudes = $empleo->solicitudes()->latest()->get();

        $empleo->solicitudes()->where('estado', 'nueva')->update(['estado' => 'vista']);

        return response()->json([
            'empleo'      => $empleo,
            'solicitudes' => $solicitudes,
        ]);
    });

    // Cambiar el estado de una solicitud
    Route::patch('/empleos/solicitudes/{id}', function (Request $request, $id) {
        $user      = $request->user();
        $solicitud = SolicitudEmpleo::with('empleo')->findOrFail($id);

        if ($user->role === 'restaurante' && $solicitud->empleo->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $solicitud->empleo->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if (!in_array($user->role, ['restaurante', 'gastrobar'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'estado' => 'required|in:nueva,vista,contactado,descartado',
        ]);

        $solicitud->update(['estado' => $request->estado]);

        return response()->json([
            'message'   => 'Estado actualizado correctamente.',
            'solicitud' => $solicitud->fresh(),
        ]);
    });

    // Eliminar una solicitud
    Route::delete('/empleos/solicitudes/{id}', function (Request $request, $id) {
        $user      = $request->user();
        $solicitud = SolicitudEmpleo::with('empleo')->findOrFail($id);

        if ($user->role === 'restaurante' && $solicitud->empleo->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $solicitud->empleo->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if (!in_array($user->role, ['restaurante', 'gastrobar'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if ($solicitud->curriculum) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($solicitud->curriculum);
        }

        $solicitud->delete();

        return response()->json(['message' => 'Solicitud eliminada correctamente.']);
    });

    // ────────────────────────────────────────────────────────
    // MENÚ (Platos) — gestión del propietario
    // ────────────────────────────────────────────────────────

    // Listar mis platos
    Route::get('/menu', function (Request $request) {
        $user = $request->user();

        if ($user->role === 'restaurante') {
            $platos = Plato::where('restaurante_id', $user->restaurante_id)
                ->with(['categoriaPlato'])
                ->orderBy('categoria')->orderBy('orden')->get();
        } elseif ($user->role === 'gastrobar') {
            $platos = Plato::where('gastrobar_id', $user->gastrobar_id)
                ->with(['categoriaPlato'])
                ->orderBy('categoria')->orderBy('orden')->get();
        } else {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($platos);
    });

    // Activar/desactivar un plato
    Route::patch('/menu/{id}/toggle', function (Request $request, $id) {
        $user  = $request->user();
        $plato = Plato::findOrFail($id);

        if ($user->role === 'restaurante' && $plato->restaurante_id !== $user->restaurante_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        if ($user->role === 'gastrobar' && $plato->gastrobar_id !== $user->gastrobar_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $plato->update(['activo' => !$plato->activo]);

        return response()->json([
            'message' => $plato->activo ? 'Plato activado.' : 'Plato ocultado.',
            'activo'  => $plato->activo,
        ]);
    });

    // ────────────────────────────────────────────────────────
    // PEDIDOS — gestión del propietario
    // ────────────────────────────────────────────────────────

    // Listar mis pedidos (restaurante o gastrobar, según el rol)
    Route::get('/pedidos', function (Request $request) {
        $user = $request->user();

        if ($user->role === 'restaurante') {
            $pedidos = Pedido::where('restaurante_id', $user->restaurante_id)
                ->with(['user', 'items.plato'])
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($p) {
                    $p->origen  = 'restaurante';
                    $p->cliente = $p->user->name ?? 'Cliente';
                    return $p;
                });
        } elseif ($user->role === 'gastrobar') {
            $pedidos = PedidoGastrobar::where('gastrobar_id', $user->gastrobar_id)
                ->with(['user', 'items.plato'])
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($p) {
                    $p->origen  = 'gastrobar';
                    $p->cliente = $p->user->name ?? 'Cliente';
                    return $p;
                });
        } else {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($pedidos);
    });

    // Cambiar el estado de un pedido
    Route::patch('/pedidos/{origen}/{id}', function (Request $request, $origen, $id) {
        $user = $request->user();

        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,en_preparacion,listo,entregado,cancelado',
        ]);

        if ($origen === 'restaurante') {
            if ($user->role !== 'restaurante') {
                return response()->json(['message' => 'No autorizado.'], 403);
            }
            $pedido = Pedido::where('id', $id)
                ->where('restaurante_id', $user->restaurante_id)
                ->first();
        } elseif ($origen === 'gastrobar') {
            if ($user->role !== 'gastrobar') {
                return response()->json(['message' => 'No autorizado.'], 403);
            }
            $pedido = PedidoGastrobar::where('id', $id)
                ->where('gastrobar_id', $user->gastrobar_id)
                ->first();
        } else {
            return response()->json(['message' => 'Origen inválido.'], 422);
        }

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado.'], 404);
        }

        $pedido->update(['estado' => $request->estado]);

        return response()->json([
            'message' => 'Estado actualizado correctamente.',
            'pedido'  => $pedido->fresh(['user', 'items.plato']),
        ]);
    });

    // Eliminar pedido (cancelar desde el panel del propietario)
    Route::delete('/pedidos/{origen}/{id}', function (Request $request, $origen, $id) {
        $user = $request->user();

        if ($origen === 'restaurante') {
            if ($user->role !== 'restaurante') {
                return response()->json(['message' => 'No autorizado.'], 403);
            }
            $pedido = Pedido::where('id', $id)
                ->where('restaurante_id', $user->restaurante_id)
                ->first();
        } elseif ($origen === 'gastrobar') {
            if ($user->role !== 'gastrobar') {
                return response()->json(['message' => 'No autorizado.'], 403);
            }
            $pedido = PedidoGastrobar::where('id', $id)
                ->where('gastrobar_id', $user->gastrobar_id)
                ->first();
        } else {
            return response()->json(['message' => 'Origen inválido.'], 422);
        }

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado.'], 404);
        }

        $pedido->items()->delete();
        $pedido->delete();

        return response()->json(['message' => 'Pedido cancelado y eliminado correctamente.']);
    });

});
