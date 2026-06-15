{{-- resources/views/configuracion/index.blade.php --}}
@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
            <i class="bi bi-gear text-primary me-2"></i> Configuración
        </h1>
        <p class="text-muted mb-0 small">Acciones de mantenimiento del sistema Gastro Nicaragua.</p>
    </div>

    {{-- ── Acciones de mantenimiento ── --}}
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-danger bg-danger bg-opacity-10" style="width:48px;height:48px;font-size:1.3rem;">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Verificar Vencimientos</h6>
                    </div>
                    <p class="text-muted mb-3" style="font-size:0.82rem;">
                        Revisa los contratos próximos a vencer y genera las notificaciones correspondientes para los propietarios.
                    </p>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill d-inline-flex align-items-center" style="font-size:0.72rem;">
                        <i class="bi bi-terminal me-1"></i> php artisan notificaciones:verificar
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-info bg-info bg-opacity-10" style="width:48px;height:48px;font-size:1.3rem;">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Limpiar Caché</h6>
                    </div>
                    <p class="text-muted mb-3" style="font-size:0.82rem;">
                        Limpia la caché de configuración, rutas y vistas. Útil después de actualizar el sistema o cambiar ajustes.
                    </p>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill d-inline-flex align-items-center" style="font-size:0.72rem;">
                        <i class="bi bi-terminal me-1"></i> php artisan optimize:clear
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10" style="width:48px;height:48px;font-size:1.3rem;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Tareas Programadas</h6>
                    </div>
                    <p class="text-muted mb-3" style="font-size:0.82rem;">
                        Ejecuta manualmente el scheduler de Laravel para procesar las tareas programadas pendientes.
                    </p>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill d-inline-flex align-items-center" style="font-size:0.72rem;">
                        <i class="bi bi-terminal me-1"></i> php artisan schedule:run
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="alert border-0 mt-4 d-flex align-items-center gap-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
        <i class="bi bi-info-circle text-secondary"></i>
        <span class="text-muted small mb-0">Estos comandos se ejecutan desde la terminal del servidor.</span>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
