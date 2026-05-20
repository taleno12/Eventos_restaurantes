<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleoController;

use App\Models\Restaurante;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Empleo;
use App\Models\User;

// ── PÁGINA PRINCIPAL (PÚBLICA) ────────────────────────────────────────────────
// Corregido: Llama a 'welcome' que es el método que procesa el carrusel y filtros
Route::get('/', [EventoController::class, 'welcome'])->name('home');

// ── NUEVO: API PÚBLICA PARA EL NAVBAR EN CASCADA DE LA PORTADA ────────────────
// Necesaria para que los usuarios no autenticados puedan usar los selectores cruzados
Route::get('/api/public/departamentos/{id}/restaurantes', function ($id) {
    return App\Models\Restaurante::where('departamento_id', $id)->get(['id', 'nombre']);
})->name('api.public.departamentos.restaurantes');

// ── SECCIÓN DE CONTACTO INDEPENDIENTE (PÚBLICA) ───────────────────────────────
// Esta ruta carga la vista independiente que contiene tu formulario premium
Route::get('/contacto', function () {
    $departamentos = Departamento::all(); // Por si los necesitas en el footer/nav
    return view('contacto', compact('departamentos'));
})->name('contacto');

// Ruta opcional por si vas a procesar el envío del formulario mediante un controlador
Route::post('/contacto', function (\Illuminate\Http\Request $request) {
    // Aquí procesas el formulario (Enviar mail, guardar en BD, etc.)
    return back()->with('success', '¡Mensaje enviado con éxito!');
})->name('contacto.store');

// ── OFERTAS DE EMPLEO (Públicas) ──────────────────────────────────────────────
// Asegurado el método 'publicIndex' para inyectar correctamente las variables estadísticas a la vista
Route::get('/empleos', [EmpleoController::class, 'publicIndex'])->name('empleos.index');
Route::get('/empleos/{empleo}', [EmpleoController::class, 'show'])->name('empleos.show');

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


//empleo ruta
Route::post('/empleos/{empleo}/aplicar', [EmpleoController::class, 'aplicar'])
    ->name('empleos.aplicar');

// ── ÁREA PROTEGIDA (SOLO ADMIN/AUTH) ──────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // RESTAURANTES
    Route::resource('restaurantes', RestauranteController::class);

    // EVENTOS (NOMBRES DE MÉTODOS SINCRONIZADOS)
    // El index de administración ahora apunta a 'index' que es tu tabla de control
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    // El resto del CRUD protegido (create, store, edit, update, destroy)
    // MODIFICADO: Se añade 'show' a los exceptuados para que no choque con la ruta libre de abajo
    Route::resource('eventos', EventoController::class)->except(['index', 'show']);

    // DEPARTAMENTOS
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');
    // Corregido: Se eliminó el /nuevo duplicado de abajo usando resource correctamente
    Route::resource('departamentos', DepartamentoController::class);

    // EMPLEOS (Panel Admin)
    Route::prefix('admin/empleos')->name('admin.empleos.')->group(function () {
        Route::get('/',                     [EmpleoController::class, 'index'])->name('index');
        Route::get('/crear',               [EmpleoController::class, 'create'])->name('create');
        Route::post('/',                   [EmpleoController::class, 'store'])->name('store');
        Route::get('/{empleo}',            [EmpleoController::class, 'adminShow'])->name('show');
        Route::get('/{empleo}/editar',     [EmpleoController::class, 'edit'])->name('edit');
        Route::put('/{empleo}',            [EmpleoController::class, 'update'])->name('update');
        Route::delete('/{empleo}',         [EmpleoController::class, 'destroy'])->name('destroy');
    });

    // TRABAJADORES (Corregido para coincidir exactamente con el nombre de ruta del menú)
    Route::get('/trabajadores', function () {
        return view('admin.trabajadores.form'); // Retorna el formulario de trabajadores
    })->name('trabajadores.index');

    // CONTRATOS (Nueva sección agregada)
    Route::get('/contratos', function () {
        return view('admin.contratos.form'); // Retorna el formulario de contratos
    })->name('contratos.index');

    // SOPORTE (Nueva sección agregada)
    Route::get('/soporte', function () {
        return view('admin.soporte.form'); // Retorna el formulario de soporte
    })->name('soporte.index');

    // CONFIGURACIÓN (Habilitado para coincidir exactamente con el nombre de ruta del menú)
    Route::get('/configuracion', function () {
        return view('admin.configuracion.form'); // Retorna el formulario de configuración
    })->name('configuracion.index');

    // API INTERNA ACTUALIZADA (CON ENCADENAMIENTO TRIPLE GEOGRÁFICO PARA EL PANEL DE ADMINISTRACIÓN)
    
    // 1. Obtiene los municipios del departamento seleccionado
    Route::get('/api/departamentos/{id}/municipios', function ($id) {
        return App\Models\Municipio::where('departamento_id', $id)->get(['id', 'nombre']);
    })->name('api.departamentos.municipios');

    // 2. Obtiene los restaurantes del municipio seleccionado (CORREGIDO: Sincronizada la variable interna con $id)
    Route::get('/api/municipios/{id}/restaurantes', function ($id) {
        return App\Models\Restaurante::where('municipio_id', $id)->get(['id', 'nombre', 'especialidad']);
    })->name('api.municipios.restaurantes');

    /* Nota opcional: Si prefieres usar el método que creamos en tu Controlador en el paso anterior,
    puedes comentar la ruta de arriba y descomentar esta línea de abajo:
    
    Route::get('/api/municipios/{id}/restaurantes', [RestauranteController::class, 'getPorMunicipio'])->name('api.municipios.restaurantes');
    */
});

// ── REUBICADO AL FINAL: RUTA DE DETALLE DE EVENTO ACCESIBLE PARA TODO EL MUNDO ──
// Al estar abajo del grupo auth, Laravel evaluará primero '/eventos/create' correctamente
Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');

require __DIR__ . '/auth.php';