{{-- resources/views/soporte/index.blade.php --}}
@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-headset text-primary me-2"></i> Soporte Técnico
            </h1>
            <p class="text-muted mb-0 small">Centro de ayuda y reportes enviados desde la app móvil.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tarjetas resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width:44px;height:44px;font-size:1.2rem;">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $totalReportes }}</div>
                        <small class="text-muted">Total reportes</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width:44px;height:44px;font-size:1.2rem;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $totalNuevos }}</div>
                        <small class="text-muted">Nuevos</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning" style="width:44px;height:44px;font-size:1.2rem;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $totalEnRevision }}</div>
                        <small class="text-muted">En revisión</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width:44px;height:44px;font-size:1.2rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $totalResueltos }}</div>
                        <small class="text-muted">Resueltos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Tabla de reportes --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-flag text-primary me-2"></i> Reportes de Usuarios
                        </h6>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('soporte.index') }}" class="btn btn-outline-secondary {{ !$estado ? 'active' : '' }}">Todos</a>
                            <a href="{{ route('soporte.index', ['estado' => 'nuevo']) }}" class="btn btn-outline-danger {{ $estado === 'nuevo' ? 'active' : '' }}">Nuevos</a>
                            <a href="{{ route('soporte.index', ['estado' => 'en_revision']) }}" class="btn btn-outline-warning {{ $estado === 'en_revision' ? 'active' : '' }}">En revisión</a>
                            <a href="{{ route('soporte.index', ['estado' => 'resuelto']) }}" class="btn btn-outline-success {{ $estado === 'resuelto' ? 'active' : '' }}">Resueltos</a>
                        </div>
                    </div>

                    @if ($reportes->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <p class="small mb-0">No hay reportes para mostrar.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="text-muted small text-uppercase">
                                        <th>Usuario</th>
                                        <th>Asunto</th>
                                        <th>Mensaje</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportes as $reporte)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold small">{{ $reporte->user->name ?? 'Usuario eliminado' }}</div>
                                                <div class="text-muted" style="font-size:0.75rem;">{{ $reporte->user->email ?? '—' }}</div>
                                            </td>
                                            <td class="small">{{ $reporte->asunto }}</td>
                                            <td class="small text-muted" style="max-width:260px;">
                                                {{ \Illuminate\Support\Str::limit($reporte->mensaje, 80) }}
                                            </td>
                                            <td class="small text-muted">{{ $reporte->created_at->format('d/m/Y h:i A') }}</td>
                                            <td>
                                                @if ($reporte->estado === 'nuevo')
                                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">Nuevo</span>
                                                @elseif ($reporte->estado === 'en_revision')
                                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">En revisión</span>
                                                @else
                                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">Resuelto</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Cambiar
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                        @foreach (['nuevo' => 'Nuevo', 'en_revision' => 'En revisión', 'resuelto' => 'Resuelto'] as $valor => $etiqueta)
                                                            <li>
                                                                <form action="{{ route('soporte.estado', $reporte) }}" method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="estado" value="{{ $valor }}">
                                                                    <button type="submit" class="dropdown-item small {{ $reporte->estado === $valor ? 'fw-bold' : '' }}">
                                                                        {{ $etiqueta }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $reportes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info de contacto + FAQ --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary bg-opacity-10" style="width:48px;height:48px;font-size:1.3rem;">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Contacto Directo</h6>
                            <small class="text-muted">Soporte disponible 24/7</small>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center gap-2 py-2 border-bottom">
                            <i class="bi bi-envelope text-primary"></i>
                            <span class="small">soporte@gastro.ni</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-2 border-bottom">
                            <i class="bi bi-whatsapp text-success"></i>
                            <span class="small">+505 8888-0000</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-clock text-warning"></i>
                            <span class="small">Lun–Vie 8:00am – 6:00pm</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-question-circle text-primary me-2"></i> Preguntas Frecuentes
                    </h6>
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item border rounded-3 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="font-size:0.9rem;">
                                    ¿Cómo agrego un nuevo restaurante o gastrobar?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    Ve a <strong>Restaurantes</strong> o <strong>Gastrobares</strong> en el menú lateral y haz clic en el botón <strong>"Nuevo"</strong> en la esquina superior derecha. Completa el formulario y guarda.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="font-size:0.9rem;">
                                    ¿Cómo activo o desactivo un establecimiento?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    En la tabla de restaurantes o gastrobares, usa el ícono de <strong>toggle</strong> (interruptor) en la columna de acciones. Al desactivar, el establecimiento se oculta del sitio público junto con sus eventos y empleos.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="font-size:0.9rem;">
                                    ¿Cómo funcionan las notificaciones de contratos?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    El sistema corre automáticamente todos los días a las 7:00am el comando <code>notificaciones:verificar</code>. Este revisa contratos activos que vencen en los próximos 7 días y genera alertas visibles en el menú de Notificaciones.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="font-size:0.9rem;">
                                    ¿Cómo genero reportes por departamento?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    Ve a <strong>Reportes y Estadísticas</strong>, selecciona un departamento en el filtro superior y haz clic en <strong>Filtrar</strong>. Las gráficas y contadores se actualizarán automáticamente con los datos de ese departamento.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
