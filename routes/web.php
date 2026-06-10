<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventoImagenController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleoController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GastrobarReviewController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\DepartamentoUsuarioController;
use App\Http\Controllers\GastrobarController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\Restaurante\CategoriaController;

use App\Models\Restaurante;
use App\Models\Evento;
use App\Models\Departamento;
use App\Models\Empleo;
use App\Models\User;

// departamento
Route::middleware('auth')->group(function () {
    Route::get('/seleccionar-departamento',  [DepartamentoUsuarioController::class, 'show'])->name('usuario.departamento.show');
    Route::post('/seleccionar-departamento', [DepartamentoUsuarioController::class, 'save'])->name('usuario.departamento.save');
    Route::get('/seleccionar-municipio',     [DepartamentoUsuarioController::class, 'showMunicipio'])->name('usuario.municipio.show');
    Route::post('/seleccionar-municipio',    [DepartamentoUsuarioController::class, 'saveMunicipio'])->name('usuario.municipio.save');
    Route::get('/saltar-departamento',       [DepartamentoUsuarioController::class, 'skip'])->name('usuario.departamento.skip');
});

// ── PÁGINA PRINCIPAL (PÚBLICA) ────────────────────────────────────────────────
Route::get('/', function () {
    return Auth::check()
        ? app(HomeController::class)->index(request())
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
Route::get('/restaurantes',                          [RestauranteController::class, 'publicIndex'])->name('restaurantes.index');
Route::get('/restaurantes/{restaurante}',            [RestauranteController::class, 'publicShow'])->name('restaurantes.show');
Route::get('/restaurantes/{restaurante}/ordenar',    [RestauranteController::class, 'ordenar'])->name('restaurantes.ordenar');

// ── GASTROBARES PÚBLICOS ─────────────────────────────────────────────────────
Route::get('/gastrobares',             [GastrobarController::class, 'publicIndex'])->name('gastrobares.index');
Route::get('/gastrobares/{gastrobar}', [GastrobarController::class, 'publicShow'])->name('gastrobares.show');

// ── EMPLEOS PÚBLICOS ─────────────────────────────────────────────────────────
Route::get('/empleos',                   [EmpleoController::class, 'publicIndex'])->name('empleos.index');
Route::get('/empleos/{empleo}',          [EmpleoController::class, 'show'])->name('empleos.show');
Route::post('/empleos/{empleo}/aplicar', [EmpleoController::class, 'aplicar'])->name('empleos.aplicar');

// ── API PÚBLICA ───────────────────────────────────────────────────────────────
Route::get('/api/public/departamentos/{id}/restaurantes', function ($id) {
    return App\Models\Restaurante::where('departamento_id', $id)->get(['id', 'nombre']);
})->name('api.public.departamentos.restaurantes');

Route::middleware('auth')->get('/mi-restaurante/api/municipios/{id}', function ($id) {
    return response()->json(
        App\Models\Municipio::where('departamento_id', $id)->get(['id', 'nombre'])
    );
});

// ── REVIEWS Y SISTEMA DE COMENTARIOS (REQUIERE AUTH) ─────────────────────────
Route::middleware('auth')->group(function () {
    // Reviews de Restaurantes
    Route::post('/restaurantes/{restaurante}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}',                    [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}',                 [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Reviews de Gastrobares
    Route::post('/gastrobares/{gastrobar}/reviews', [GastrobarReviewController::class, 'store'])->name('gastrobar.reviews.store');
    Route::put('/gastrobares/reviews/{review}',     [GastrobarReviewController::class, 'update'])->name('gastrobar.reviews.update');
    Route::delete('/gastrobares/reviews/{review}',  [GastrobarReviewController::class, 'destroy'])->name('gastrobar.reviews.destroy');
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

    // ── ADMINISTRACIÓN DE TRABAJADORES ────────────────────────────────────────
    Route::prefix('trabajadores')->name('trabajadores.')->group(function () {
        Route::get('/',                  [TrabajadorController::class, 'index'])->name('index');
        Route::get('/create',            [TrabajadorController::class, 'create'])->name('create');
        Route::post('/',                 [TrabajadorController::class, 'store'])->name('store');
        Route::get('/{trabajador}',      [TrabajadorController::class, 'show'])->name('show');
        Route::get('/{trabajador}/edit', [TrabajadorController::class, 'edit'])->name('edit');
        Route::put('/{trabajador}',      [TrabajadorController::class, 'update'])->name('update');
        Route::delete('/{trabajador}',   [TrabajadorController::class, 'destroy'])->name('destroy');

        Route::post('/restaurantes-por-departamentos', [TrabajadorController::class, 'getRestaurantesPorDepartamentos'])
            ->name('restaurantes.por.departamentos');
        Route::post('/gastrobares-por-departamentos',  [TrabajadorController::class, 'getGastrobaresPorDepartamentos'])
            ->name('gastrobares.por.departamentos');
    });

    // ── CONTRATOS ─────────────────────────────────────────────────────────────
    Route::prefix('contratos')->name('contratos.')->group(function () {
        Route::get('/',                [ContratoController::class, 'index'])->name('index');
        Route::get('/create',          [ContratoController::class, 'create'])->name('create');
        Route::post('/',               [ContratoController::class, 'store'])->name('store');
        Route::get('/{contrato}',      [ContratoController::class, 'show'])->name('show');
        Route::get('/{contrato}/edit', [ContratoController::class, 'edit'])->name('edit');
        Route::put('/{contrato}',      [ContratoController::class, 'update'])->name('update');
        Route::delete('/{contrato}',   [ContratoController::class, 'destroy'])->name('destroy');

        Route::get('/ajax/municipios',       [ContratoController::class, 'getMunicipiosPorDepartamento'])
            ->name('ajax.municipios');
        Route::get('/ajax/establecimientos', [ContratoController::class, 'getEstablecimientosPorMunicipio'])
            ->name('ajax.establecimientos');
    });

    // ── MÓDULOS DE GESTIÓN INTERNA ────────────────────────────────────────────
    Route::get('/soporte',       function () { return view('soporte.index'); })->name('soporte.index');
    Route::get('/configuracion', function () { return view('configuracion.index'); })->name('configuracion.index');

    // ── MEMBRESÍAS ────────────────────────────────────────────────────────────
    Route::get('/membresias', function () {
        $membresiasActivas = \App\Models\Contrato::with(['gastrobar', 'restaurante'])
                                ->where('estado', 'activo')
                                ->latest()
                                ->paginate(10, ['*'], 'page_activas');

        $membresiasPendientes = \App\Models\Contrato::with(['gastrobar', 'restaurante'])
                                ->where('estado', 'pendiente')
                                ->latest()
                                ->paginate(10, ['*'], 'page_pendientes');

        $membresiasVencidas = \App\Models\Contrato::with(['gastrobar', 'restaurante'])
                                ->where('estado', 'vencido')
                                ->latest()
                                ->paginate(10, ['*'], 'page_vencidas');

        $membresiasCanceladas = \App\Models\Contrato::with(['gastrobar', 'restaurante'])
                                ->where('estado', 'cancelado')
                                ->latest()
                                ->paginate(10, ['*'], 'page_canceladas');

        $totalActivas    = \App\Models\Contrato::where('estado', 'activo')->count();
        $totalPendientes = \App\Models\Contrato::where('estado', 'pendiente')->count();
        $totalVencidas   = \App\Models\Contrato::where('estado', 'vencido')->count();
        $totalCanceladas = \App\Models\Contrato::where('estado', 'cancelado')->count();
        $totalPremium    = \App\Models\Contrato::where('estado', 'activo')->where('plan', 'premium')->count();
        $totalBasico     = \App\Models\Contrato::where('estado', 'activo')->where('plan', 'basico')->count();
        $porVencer       = \App\Models\Contrato::where('estado', 'activo')
                                ->whereBetween('fecha_fin', [now(), now()->addDays(7)])
                                ->count();

        $membresias = $membresiasActivas;

        return view('membresias.index', compact(
            'membresias',
            'membresiasActivas',
            'membresiasPendientes',
            'membresiasVencidas',
            'membresiasCanceladas',
            'totalActivas',
            'totalPendientes',
            'totalVencidas',
            'totalCanceladas',
            'totalPremium',
            'totalBasico',
            'porVencer'
        ));
    })->name('membresias.index');

    // ── PAGOS ─────────────────────────────────────────────────────────────────
    Route::prefix('pagos')->name('pagos.')->group(function () {
        Route::get('/',                  [PagoController::class, 'index'])->name('index');
        Route::post('/',                 [PagoController::class, 'store'])->name('store');
        Route::get('/{pago}/pdf',        [PagoController::class, 'descargarPdf'])->name('pdf');
        Route::get('/{pago}',            [PagoController::class, 'show'])->name('show');
        Route::patch('/{pago}/estado',   [PagoController::class, 'updateEstado'])->name('updateEstado');
        Route::delete('/{pago}',         [PagoController::class, 'destroy'])->name('destroy');
    });

    // ── USUARIOS DEL SISTEMA ──────────────────────────────────────────────────
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/',             [\App\Http\Controllers\UsuarioController::class, 'index'])->name('index');
        Route::get('/{user}/edit',  [\App\Http\Controllers\UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{user}',       [\App\Http\Controllers\UsuarioController::class, 'update'])->name('update');
        Route::delete('/{user}',    [\App\Http\Controllers\UsuarioController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle', [\App\Http\Controllers\UsuarioController::class, 'toggle'])->name('toggle');
    });

    // ── REPORTES Y ESTADÍSTICAS ───────────────────────────────────────────────
    Route::get('/reportes', function () {
        return view('reportes.index');
    })->name('reportes.index');

    // ── APIs INTERNAS ─────────────────────────────────────────────────────────
    Route::get('/api/departamentos/{id}/municipios', function ($id) {
        return App\Models\Municipio::where('departamento_id', $id)->get(['id', 'nombre']);
    })->name('api.departamentos.municipios');

    Route::get('/api/municipios/{id}/restaurantes', function ($id) {
        return App\Models\Restaurante::where('municipio_id', $id)->get(['id', 'nombre', 'especialidad']);
    })->name('api.municipios.restaurantes');

    Route::get('/api/municipios/{id}/gastrobares', function ($id) {
        return App\Models\Gastrobar::where('municipio_id', $id)->get(['id', 'nombre', 'tipo_bar']);
    })->name('api.municipios.gastrobares');

    Route::get('/api/departamentos/{id}/restaurantes', function ($id) {
        return App\Models\Restaurante::where('departamento_id', $id)->get(['id', 'nombre', 'especialidad']);
    })->name('api.departamentos.restaurantes');
});

// ── PANEL DEL RESTAURANTE ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:restaurante,admin'])
    ->prefix('mi-restaurante')
    ->name('restaurante.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Restaurante\RestauranteDashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/perfil', [\App\Http\Controllers\Restaurante\RestaurantePerfilController::class, 'edit'])
            ->name('perfil.edit');
        Route::put('/perfil', [\App\Http\Controllers\Restaurante\RestaurantePerfilController::class, 'update'])
            ->name('perfil.update');
        Route::resource('eventos', \App\Http\Controllers\Restaurante\RestauranteEventoController::class);
        Route::resource('empleos', \App\Http\Controllers\Restaurante\RestauranteEmpleoController::class);
        Route::get('/galeria', [\App\Http\Controllers\Restaurante\RestauranteGaleriaController::class, 'index'])
            ->name('galeria.index');
        Route::post('/galeria', [\App\Http\Controllers\Restaurante\RestauranteGaleriaController::class, 'store'])
            ->name('galeria.store');
        Route::delete('/galeria/{foto}', [\App\Http\Controllers\Restaurante\RestauranteGaleriaController::class, 'destroy'])
            ->name('galeria.destroy');

        // Menú - Platos
        Route::resource('platos', \App\Http\Controllers\Restaurante\RestaurantePlatoController::class);
        Route::patch('platos/{plato}/toggle', [\App\Http\Controllers\Restaurante\RestaurantePlatoController::class, 'toggleActivo'])
            ->name('platos.toggle');

        // ── CATEGORÍAS DE PLATOS ──────────────────────────────────────────────
        Route::post('categorias/reorder', [CategoriaController::class, 'reorder'])
            ->name('categorias.reorder');
        Route::resource('categorias', CategoriaController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Pedidos del restaurante
        Route::get('/pedidos', [\App\Http\Controllers\Restaurante\RestaurantePedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/{pedido}', [\App\Http\Controllers\Restaurante\RestaurantePedidoController::class, 'show'])->name('pedidos.show');
        Route::patch('/pedidos/{pedido}/estado', [\App\Http\Controllers\Restaurante\RestaurantePedidoController::class, 'cambiarEstado'])->name('pedidos.estado');
        Route::get('/pedidos-polling', [\App\Http\Controllers\Restaurante\RestaurantePedidoController::class, 'polling'])->name('pedidos.polling');
    });

// ── RUTAS DE PEDIDOS PÚBLICOS ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/restaurantes/{restaurante}/pedido', [App\Http\Controllers\PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/mis-pedidos', [App\Http\Controllers\PedidoController::class, 'misPedidos'])->name('pedidos.mis');
    Route::get('/mis-pedidos/{pedido}', [App\Http\Controllers\PedidoController::class, 'show'])->name('pedidos.detalle');
});

// ── EVENTOS PÚBLICOS ──────────────────────────────────────────────────────────
Route::get('/eventos/{evento}', [EventoController::class, 'show'])
    ->name('eventos.show')
    ->whereNumber('evento');

require __DIR__ . '/auth.php';
