<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventoImagenController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleoController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\DepartamentoUsuarioController;
use App\Http\Controllers\GastrobarController;


use App\Models\Restaurante;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Empleo;
use App\Models\User;

// departamento
Route::middleware('auth')->group(function () {
    Route::get('/seleccionar-departamento',  [DepartamentoUsuarioController::class, 'show'])->name('usuario.departamento.show');
    Route::post('/seleccionar-departamento', [DepartamentoUsuarioController::class, 'save'])->name('usuario.departamento.save');
    Route::get('/saltar-departamento',       [DepartamentoUsuarioController::class, 'skip'])->name('usuario.departamento.skip');
});

// ── PÁGINA PRINCIPAL (PÚBLICA) ────────────────────────────────────────────────
Route::get('/', function () {
    return auth()->check()
        ? app(App\Http\Controllers\EventoController::class)->welcome(request())
        : redirect()->route('login');
})->name('home');

// ── LOGIN CON GOOGLE ──────────────────────────────────────────────────────────
Route::get('/auth/google',          [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ── CONTACTO ─────────────────────────────────────────────────────────────────
Route::get('/contacto', function () {
    $departamentos = Departamento::all();
    return view('contacto', compact('departamentos'));
})->name('contacto');

Route::post('/contacto', function (\Illuminate\Http\Request $request) {
    return back()->with('success', '¡Mensaje enviado con éxito!');
})->name('contacto.store');

// ── RESTAURANTES PÚBLICOS ────────────────────────────────────────────────────
Route::get('/restaurantes', [RestauranteController::class, 'publicIndex'])->name('restaurantes.index');
Route::get('/restaurantes/{restaurante}', [RestauranteController::class, 'publicShow'])->name('restaurantes.show');

// ── GASTROBARES PÚBLICOS ─────────────────────────────────────────────────────
Route::get('/gastrobares', [GastrobarController::class, 'publicIndex'])->name('gastrobares.index');
Route::get('/gastrobares/{gastrobar}', [GastrobarController::class, 'publicShow'])->name('gastrobares.show');

// ── EMPLEOS PÚBLICOS ─────────────────────────────────────────────────────────
Route::get('/empleos', [EmpleoController::class, 'publicIndex'])->name('empleos.index');
Route::get('/empleos/{empleo}', [EmpleoController::class, 'show'])->name('empleos.show');
Route::post('/empleos/{empleo}/aplicar', [EmpleoController::class, 'aplicar'])->name('empleos.aplicar');

// ── EVENTOS PÚBLICOS ─────────────────────────────────────────────────────────
// ⚠️ MOVIDA al final, después de las rutas admin, para no capturar /eventos/create
// Se define abajo con whereNumber para que solo acepte IDs numéricos

// ── API PÚBLICA ───────────────────────────────────────────────────────────────
Route::get('/api/public/departamentos/{id}/restaurantes', function ($id) {
    return App\Models\Restaurante::where('departamento_id', $id)->get(['id', 'nombre']);
})->name('api.public.departamentos.restaurantes');

// ── REVIEWS Y SISTEMA DE COMENTARIOS (REQUIERE AUTH) ─────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/restaurantes/{restaurante}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}',                    [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}',                 [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// ── PANEL DE CONTROL / DASHBOARD (SOLO ADMIN) ────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard', [
        'totalRestaurantes'  => Restaurante::count(),
        'totalEventos'       => Evento::count(),
        'totalDepartamentos' => Departamento::count(),
        'totalPersonal'      => User::count(),
        'totalEmpleos'       => Empleo::where('activo', true)->count(),
    ]);
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

// ── ÁREA ADMINISTRATIVA PROTEGIDA (SOLO ADMIN) ───────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {

    // PERFIL DE USUARIO
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMINISTRACIÓN DE RESTAURANTES
    Route::prefix('admin/restaurantes')->name('admin.restaurantes.')->group(function () {
        Route::get('/',                   [RestauranteController::class, 'index'])->name('index');
        Route::get('/create',             [RestauranteController::class, 'create'])->name('create');
        Route::post('/',                  [RestauranteController::class, 'store'])->name('store');
        Route::get('/{restaurante}/edit', [RestauranteController::class, 'edit'])->name('edit');
        Route::put('/{restaurante}',      [RestauranteController::class, 'update'])->name('update');
        Route::delete('/{restaurante}',   [RestauranteController::class, 'destroy'])->name('destroy');
        Route::get('/{restaurante}',      [RestauranteController::class, 'adminShow'])->name('show');
    });

    // ADMINISTRACIÓN DE GASTROBARES
    Route::prefix('admin/gastrobares')->name('admin.gastrobares.')->group(function () {
        Route::get('/',                  [GastrobarController::class, 'index'])->name('index');
        Route::get('/create',            [GastrobarController::class, 'create'])->name('create');
        Route::post('/',                 [GastrobarController::class, 'store'])->name('store');
        Route::get('/{gastrobar}/edit',  [GastrobarController::class, 'edit'])->name('edit');
        Route::put('/{gastrobar}',       [GastrobarController::class, 'update'])->name('update');
        Route::delete('/{gastrobar}',    [GastrobarController::class, 'destroy'])->name('destroy');
        Route::get('/{gastrobar}',       [GastrobarController::class, 'adminShow'])->name('show');
    });

    // ADMINISTRACIÓN DE EVENTOS
    Route::get('/eventos',               [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/create',        [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos',              [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}',      [EventoController::class, 'update'])->name('eventos.update');
    Route::patch('/eventos/{evento}',    [EventoController::class, 'update']);
    Route::delete('/eventos/{evento}',   [EventoController::class, 'destroy'])->name('eventos.destroy');

    // GALERÍA DE FOTOS DE EVENTOS
    Route::post('/eventos/{evento}/imagenes',  [EventoImagenController::class, 'store'])->name('evento.imagenes.store');
    Route::delete('/evento-imagenes/{imagen}', [EventoImagenController::class, 'destroy'])->name('evento.imagenes.destroy');

    // ADMINISTRACIÓN DE DEPARTAMENTOS
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');
    Route::resource('departamentos', DepartamentoController::class);

    // PANEL DE EMPLEOS ADMIN
    Route::prefix('admin/empleos')->name('admin.empleos.')->group(function () {
        Route::get('/',                [EmpleoController::class, 'index'])->name('index');
        Route::get('/crear',           [EmpleoController::class, 'create'])->name('create');
        Route::post('/',               [EmpleoController::class, 'store'])->name('store');
        Route::get('/{empleo}',        [EmpleoController::class, 'adminShow'])->name('show');
        Route::get('/{empleo}/editar', [EmpleoController::class, 'edit'])->name('edit');
        Route::put('/{empleo}',        [EmpleoController::class, 'update'])->name('update');
        Route::delete('/{empleo}',     [EmpleoController::class, 'destroy'])->name('destroy');
    });

    // MÓDULOS DE GESTIÓN INTERNA
    Route::get('/trabajadores',  function () { return view('admin.trabajadores.form'); })->name('trabajadores.index');
    Route::get('/contratos',     function () { return view('admin.contratos.form'); })->name('contratos.index');
    Route::get('/soporte',       function () { return view('admin.soporte.form'); })->name('soporte.index');
    Route::get('/configuracion', function () { return view('admin.configuracion.form'); })->name('configuracion.index');

    // APIs INTERNAS
    Route::get('/api/departamentos/{id}/municipios', function ($id) {
        return App\Models\Municipio::where('departamento_id', $id)->get(['id', 'nombre']);
    })->name('api.departamentos.municipios');

    Route::get('/api/municipios/{id}/restaurantes', function ($id) {
        return App\Models\Restaurante::where('municipio_id', $id)->get(['id', 'nombre', 'especialidad']);
    })->name('api.municipios.restaurantes');

    Route::get('/api/departamentos/{id}/restaurantes', function ($id) {
        return App\Models\Restaurante::where('departamento_id', $id)->get(['id', 'nombre', 'especialidad']);
    })->name('api.departamentos.restaurantes');
});

// ── EVENTOS PÚBLICOS (al final, con whereNumber para evitar capturar "create") ─
Route::get('/eventos/{evento}', [EventoController::class, 'show'])
    ->name('eventos.show')
    ->whereNumber('evento');

require __DIR__ . '/auth.php';