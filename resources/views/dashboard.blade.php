@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4 animate-fade-in" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Header ── --}}
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-5">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <span class="badge rounded-pill fw-black text-white px-3 py-2"
                      style="background:#4f46e5;font-size:0.65rem;letter-spacing:0.12em;">
                    v2.0 PRO
                </span>
                <span class="text-uppercase fw-bold text-secondary" style="font-size:0.7rem;letter-spacing:0.3em;">
                    Sistema de Control Integral
                </span>
            </div>
            <h1 class="fw-black text-dark mb-1" style="font-size:2.2rem;letter-spacing:-0.02em;line-height:1;">
                Panel Central
            </h1>
            <p class="text-secondary fw-medium mb-0" style="font-size:0.9rem;">
                Analíticas de rendimiento para <span class="fw-bold" style="color:#4f46e5;">Gastro.ni</span>
            </p>
        </div>

        <div class="card border-0 shadow-sm rounded-3 flex-shrink-0">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:40px;height:40px;background:#eef2ff;">
                    <i class="bi bi-lightning-charge-fill" style="color:#4f46e5;"></i>
                </div>
                <div>
                    <span class="d-block text-uppercase fw-black text-secondary"
                          style="font-size:0.65rem;letter-spacing:0.1em;">Estado Global</span>
                    <span class="fw-bold d-flex align-items-center gap-2" style="color:#10b981;font-size:0.85rem;">
                        <span class="rounded-circle pulse-dot" style="width:8px;height:8px;background:#10b981;display:inline-block;"></span>
                        Sistema Activo
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Estadísticas ── --}}
    <div class="row g-4 mb-5">

        {{-- Unidades de Negocio --}}
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-card-indigo">
                <div class="card-body p-4 d-flex flex-column justify-content-between" style="min-height:150px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase fw-black text-secondary mb-1"
                               style="font-size:0.65rem;letter-spacing:0.15em;">Unidades de Negocio</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:2rem;letter-spacing:-0.02em;">
                                {{ $totalRestaurantes }}
                                <span class="fw-semibold text-secondary" style="font-size:0.85rem;">Locales</span>
                            </h3>
                        </div>
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px;height:48px;background:#f8fafc;">
                            <i class="bi bi-shop" style="font-size:1.2rem;color:#475569;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge rounded-pill fw-bold px-3 py-1"
                                  style="background:#ecfdf5;color:#059669;font-size:0.7rem;">
                                <i class="bi bi-caret-up-fill me-1"></i>+12%
                            </span>
                            <span class="text-secondary" style="font-size:0.75rem;">Rendimiento mensual</span>
                        </div>
                        <div class="progress rounded-pill" style="height:6px;background:#f1f5f9;">
                            <div class="progress-bar stat-bar rounded-pill" role="progressbar"
                                 style="width:66%;background:#4f46e5;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actividad Reciente --}}
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-card-emerald">
                <div class="card-body p-4 d-flex flex-column justify-content-between" style="min-height:150px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase fw-black text-secondary mb-1"
                               style="font-size:0.65rem;letter-spacing:0.15em;">Actividad Reciente</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:2rem;letter-spacing:-0.02em;">
                                {{ $totalEventos }}
                                <span class="fw-semibold text-secondary" style="font-size:0.85rem;">Publicaciones</span>
                            </h3>
                        </div>
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px;height:48px;background:#f8fafc;">
                            <i class="bi bi-calendar-check" style="font-size:1.2rem;color:#475569;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge rounded-pill fw-bold px-3 py-1"
                                  style="background:#eff6ff;color:#3b82f6;font-size:0.7rem;">
                                Al día
                            </span>
                            <span class="text-secondary" style="font-size:0.75rem;">Actualizado hoy</span>
                        </div>
                        <div class="progress rounded-pill" style="height:6px;background:#f1f5f9;">
                            <div class="progress-bar stat-bar rounded-pill" role="progressbar"
                                 style="width:50%;background:#10b981;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cobertura Nacional --}}
        <div class="col-12 col-sm-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-card-amber">
                <div class="card-body p-4 d-flex flex-column justify-content-between" style="min-height:150px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase fw-black text-secondary mb-1"
                               style="font-size:0.65rem;letter-spacing:0.15em;">Cobertura Nacional</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:2rem;letter-spacing:-0.02em;">
                                {{ $totalDepartamentos }}
                                <span class="fw-semibold text-secondary" style="font-size:0.85rem;">Regiones</span>
                            </h3>
                        </div>
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px;height:48px;background:#f8fafc;">
                            <i class="bi bi-map" style="font-size:1.2rem;color:#475569;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge rounded-pill fw-bold px-3 py-1"
                                  style="background:#fffbeb;color:#d97706;font-size:0.7rem;">
                                Regional
                            </span>
                            <span class="text-secondary" style="font-size:0.75rem;">Departamentos activos</span>
                        </div>
                        <div class="progress rounded-pill" style="height:6px;background:#f1f5f9;">
                            <div class="progress-bar stat-bar rounded-pill" role="progressbar"
                                 style="width:80%;background:#f59e0b;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Banner Centro de Operaciones ── --}}
    <div class="position-relative rounded-4 overflow-hidden shadow-sm"
         style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">

        {{-- Ícono decorativo de fondo --}}
        <div class="position-absolute top-0 end-0 pointer-events-none" style="opacity:0.04;overflow:hidden;">
            <i class="bi bi-rocket-takeoff" style="font-size:12rem;color:white;transform:rotate(-12deg) translate(20px, -10px);display:block;"></i>
        </div>

        <div class="position-relative p-4 p-sm-5 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4"
             style="z-index:2;">
            <div>
                <h4 class="fw-black text-white mb-1" style="font-size:1.5rem;letter-spacing:-0.01em;">
                    Centro de Operaciones
                </h4>
                <p class="mb-0" style="color:#94a3b8;font-size:0.88rem;max-width:440px;">
                    "La excelencia no es un acto, sino un hábito." Gestiona tu red con precisión.
                </p>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-3 w-100 w-md-auto">
                <a href="{{ route('admin.restaurantes.create') }}"
                   class="btn fw-black text-dark px-4 py-3 text-decoration-none d-flex align-items-center justify-content-center gap-2"
                   style="background:white;border-radius:12px;font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;white-space:nowrap;transition:all 0.2s;"
                   onmouseover="this.style.background='#4f46e5';this.style.color='white';"
                   onmouseout="this.style.background='white';this.style.color='#0f172a';">
                    <i class="bi bi-plus-lg"></i> Nuevo Local
                </a>
                <a href="{{ route('eventos.create') }}"
                   class="btn text-white px-4 py-3 text-decoration-none d-flex align-items-center justify-content-center gap-2 fw-black"
                   style="background:#1e293b;border:1px solid #334155;border-radius:12px;font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;white-space:nowrap;transition:background 0.2s;"
                   onmouseover="this.style.background='#334155';"
                   onmouseout="this.style.background='#1e293b';">
                    <i class="bi bi-megaphone"></i> Publicar Evento
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Pulse dot */
    .pulse-dot {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* Hover en stat cards */
    .stat-card { transition: all 0.3s ease; border: 1px solid #f1f5f9; }
    .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important; }
    .stat-card-indigo:hover { border-color: #c7d2fe; }
    .stat-card-emerald:hover { border-color: #a7f3d0; }
    .stat-card-amber:hover { border-color: #fde68a; }

    /* Hover en ícono de stat */
    .stat-card-indigo:hover .stat-icon { background:#4f46e5 !important; }
    .stat-card-indigo:hover .stat-icon i { color:white !important; }
    .stat-card-emerald:hover .stat-icon { background:#10b981 !important; }
    .stat-card-emerald:hover .stat-icon i { color:white !important; }
    .stat-card-amber:hover .stat-icon { background:#f59e0b !important; }
    .stat-card-amber:hover .stat-icon i { color:white !important; }

    /* Barra de progreso hover */
    .stat-card:hover .stat-bar { width: 100% !important; transition: width 0.7s ease-out; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
