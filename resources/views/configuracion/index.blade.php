{{-- resources/views/configuracion/index.blade.php --}}
@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-gear text-primary me-2"></i> Configuración
            </h1>
            <p class="text-muted mb-0 small">Ajustes generales de la plataforma Gastro Nicaragua.</p>
        </div>
    </div>

    <div class="row g-4">

        {{-- Información del sistema --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-info-circle text-primary me-2"></i> Información del Sistema
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Aplicación</span>
                            <span class="fw-semibold small">{{ config('app.name', 'Gastro.ni') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Entorno</span>
                            <span class="badge bg-success bg-opacity-10 text-success fw-semibold" style="font-size:0.72rem;">{{ config('app.env') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Versión Laravel</span>
                            <span class="fw-semibold small">{{ app()->version() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Versión PHP</span>
                            <span class="fw-semibold small">{{ PHP_VERSION }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small">Zona Horaria</span>
                            <span class="fw-semibold small">{{ config('app.timezone') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Acciones del sistema --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-tools text-warning me-2"></i> Acciones de Mantenimiento
                    </h6>
                    <div class="d-grid gap-3">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div>
                                <p class="fw-semibold mb-0 small">Verificar Vencimientos</p>
                                <p class="text-muted mb-0" style="font-size:0.75rem;">Genera notificaciones de contratos por vencer</p>
                            </div>
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill" style="font-size:0.72rem;">
                                <i class="bi bi-terminal me-1"></i> php artisan notificaciones:verificar
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div>
                                <p class="fw-semibold mb-0 small">Limpiar Caché</p>
                                <p class="text-muted mb-0" style="font-size:0.75rem;">Limpia la caché de configuración y rutas</p>
                            </div>
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill" style="font-size:0.72rem;">
                                <i class="bi bi-terminal me-1"></i> php artisan optimize:clear
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div>
                                <p class="fw-semibold mb-0 small">Tareas Programadas</p>
                                <p class="text-muted mb-0" style="font-size:0.75rem;">Ejecuta el scheduler manualmente</p>
                            </div>
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill" style="font-size:0.72rem;">
                                <i class="bi bi-terminal me-1"></i> php artisan schedule:run
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
