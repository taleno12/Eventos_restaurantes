<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleoController;
use App\Http\Controllers\ReviewController;

use App\Models\Restaurante;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Empleo;
use App\Models\User;

// ── REVIEWS (requieren auth) ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/restaurantes/{restaurante}/reviews', [ReviewController::class, 'store'])
         ->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])
         ->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
         ->name('reviews.destroy');
});

// ── PÁGINA PRINCIPAL (PÚBLICA) ────────────────────────────────────────────────
Route::get('/', [EventoController::class, 'welcome'])->name('home');

// ── API PÚBLICA ───────────────────────────────────────────────────────────────
Route::get('/api/public/departamentos/{id}/restaurantes', function ($id) {
    return App\Models\Restaurante::where('departamento_id', $id)->get(['id', 'nombre']);
})->name('api.public.departamentos.restaurantes');

// ── RESTAURANTES PÚBLICOS (listado) ──────────────────────────────────────────
Route::get('/restaurantes', [RestauranteController::class, 'publicIndex'])->name('restaurantes.index');

// ── CONTACTO ─────────────────────────────────────────────────────────────────
Route::get('/contacto', function () {
    $departamentos = Departamento::all();
    return view('contacto', compact('departamentos'));
})->name('contacto');

Route::post('/contacto', function (\Illuminate\Http\Request $request) {
    return back()->with('success', '¡Mensaje enviado con éxito!');
})->name('contacto.store');

// ── EMPLEOS (PÚBLICAS) ───────────────────────────────────────────────────────
Route::get('/empleos', [EmpleoController::class, 'publicIndex'])->name('empleos.index');
Route::get('/empleos/{empleo}', [EmpleoController::class, 'show'])->name('empleos.show');
Route::post('/empleos/{empleo}/aplicar', [EmpleoController::class, 'aplicar'])->name('empleos.aplicar');

// ── DASHBOARD ─────────────────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard', [
        'totalRestaurantes'  => Restaurante::count(),
        'totalEventos'       => Evento::count(),
        'totalDepartamentos' => Departamento::count(),
        'totalPersonal'      => User::count(),
        'totalEmpleos'       => Empleo::where('activo', true)->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// ── ÁREA PROTEGIDA (SOLO ADMIN/AUTH) ──────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // RESTAURANTES ADMIN
    Route::get('/admin/restaurantes', [RestauranteController::class, 'index'])->name('admin.restaurantes.index');
    Route::resource('restaurantes', RestauranteController::class)->except(['index', 'show']);

    // EVENTOS
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::resource('eventos', EventoController::class)->except(['index', 'show']);

    // DEPARTAMENTOS
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');
    Route::resource('departamentos', DepartamentoController::class);

    // EMPLEOS (Panel Admin)
    Route::prefix('admin/empleos')->name('admin.empleos.')->group(function () {
        Route::get('/',                 [EmpleoController::class, 'index'])->name('index');
        Route::get('/crear',            [EmpleoController::class, 'create'])->name('create');
        Route::post('/',                [EmpleoController::class, 'store'])->name('store');
        Route::get('/{empleo}',         [EmpleoController::class, 'adminShow'])->name('show');
        Route::get('/{empleo}/editar',  [EmpleoController::class, 'edit'])->name('edit');
        Route::put('/{empleo}',         [EmpleoController::class, 'update'])->name('update');
        Route::delete('/{empleo}',      [EmpleoController::class, 'destroy'])->name('destroy');
    });

    // TRABAJADORES
    Route::get('/trabajadores', function () {
        return view('admin.trabajadores.form');
    })->name('trabajadores.index');

    // CONTRATOS
    Route::get('/contratos', function () {
        return view('admin.contratos.form');
    })->name('contratos.index');

    // SOPORTE
    Route::get('/soporte', function () {
        return view('admin.soporte.form');
    })->name('soporte.index');

    // CONFIGURACIÓN
    Route::get('/configuracion', function () {
        return view('admin.configuracion.form');
    })->name('configuracion.index');

    // API INTERNA
    Route::get('/api/departamentos/{id}/municipios', function ($id) {
        return App\Models\Municipio::where('departamento_id', $id)->get(['id', 'nombre']);
    })->name('api.departamentos.municipios');

    Route::get('/api/municipios/{id}/restaurantes', function ($id) {
        return App\Models\Restaurante::where('municipio_id', $id)->get(['id', 'nombre', 'especialidad']);
    })->name('api.municipios.restaurantes');
});

// ── DETALLE DE EVENTO (PÚBLICA) ───────────────────────────────────────────────
Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');

// ── RESTAURANTE DETALLE PÚBLICO (al final para no capturar /create ni /edit) ──
Route::get('/restaurantes/{restaurante}', [RestauranteController::class, 'publicShow'])->name('restaurantes.show');

require __DIR__ . '/auth.php';