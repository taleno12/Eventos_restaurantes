@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4 dashboard-root" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Header ── --}}
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-5">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <span class="badge rounded-pill fw-bold text-white px-3 py-2"
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
                    <span class="d-block text-uppercase fw-bold text-secondary"
                          style="font-size:0.65rem;letter-spacing:0.1em;">Estado Global</span>
                    <span class="fw-bold d-flex align-items-center gap-2" style="color:#10b981;font-size:0.85rem;">
                        <span class="rounded-circle pulse-dot" style="width:8px;height:8px;background:#10b981;display:inline-block;"></span>
                        Sistema Activo
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Fila 1: KPIs principales ── --}}
    <div class="row g-4 mb-4">

        {{-- Restaurantes --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-indigo">
                <div class="card-body p-4 d-flex flex-column justify-content-between" style="min-height:140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase fw-bold text-secondary mb-1" style="font-size:0.6rem;letter-spacing:0.15em;">Restaurantes</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:2rem;">
                                {{ $totalRestaurantes }}
                            </h3>
                            <span class="text-secondary" style="font-size:0.78rem;">
                                {{ $restaurantesActivos }} activos
                            </span>
                        </div>
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#eef2ff;flex-shrink:0;">
                            <i class="bi bi-shop" style="font-size:1.1rem;color:#4f46e5;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress rounded-pill" style="height:5px;background:#f1f5f9;">
                            <div class="progress-bar rounded-pill" style="width:{{ $totalRestaurantes > 0 ? round($restaurantesActivos/$totalRestaurantes*100) : 0 }}%;background:#4f46e5;transition:width 1s ease;"></div>
                        </div>
                        <span class="text-secondary" style="font-size:0.7rem;">
                            {{ $totalRestaurantes > 0 ? round($restaurantesActivos/$totalRestaurantes*100) : 0 }}% operativos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gastrobares --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-violet">
                <div class="card-body p-4 d-flex flex-column justify-content-between" style="min-height:140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase fw-bold text-secondary mb-1" style="font-size:0.6rem;letter-spacing:0.15em;">Gastrobares</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:2rem;">
                                {{ $totalGastrobares }}
                            </h3>
                            <span class="text-secondary" style="font-size:0.78rem;">
                                {{ $gastrobaresActivos }} activos
                            </span>
                        </div>
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#f5f3ff;flex-shrink:0;">
                            <i class="bi bi-cup-straw" style="font-size:1.1rem;color:#7c3aed;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress rounded-pill" style="height:5px;background:#f1f5f9;">
                            <div class="progress-bar rounded-pill" style="width:{{ $totalGastrobares > 0 ? round($gastrobaresActivos/$totalGastrobares*100) : 0 }}%;background:#7c3aed;transition:width 1s ease;"></div>
                        </div>
                        <span class="text-secondary" style="font-size:0.7rem;">
                            {{ $totalGastrobares > 0 ? round($gastrobaresActivos/$totalGastrobares*100) : 0 }}% operativos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Eventos --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-emerald">
                <div class="card-body p-4 d-flex flex-column justify-content-between" style="min-height:140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase fw-bold text-secondary mb-1" style="font-size:0.6rem;letter-spacing:0.15em;">Eventos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:2rem;">
                                {{ $totalEventos }}
                            </h3>
                            <span class="text-secondary" style="font-size:0.78rem;">
                                {{ $eventosProximos }} próximos
                            </span>
                        </div>
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#ecfdf5;flex-shrink:0;">
                            <i class="bi bi-calendar-event" style="font-size:1.1rem;color:#10b981;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress rounded-pill" style="height:5px;background:#f1f5f9;">
                            <div class="progress-bar rounded-pill" style="width:{{ $totalEventos > 0 ? min(round($eventosProximos/$totalEventos*100),100) : 0 }}%;background:#10b981;transition:width 1s ease;"></div>
                        </div>
                        <span class="text-secondary" style="font-size:0.7rem;">
                            {{ $eventosDestacados }} destacados
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Empleos --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-amber">
                <div class="card-body p-4 d-flex flex-column justify-content-between" style="min-height:140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase fw-bold text-secondary mb-1" style="font-size:0.6rem;letter-spacing:0.15em;">Empleos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:2rem;">
                                {{ $totalEmpleos }}
                            </h3>
                            <span class="text-secondary" style="font-size:0.78rem;">
                                {{ $empleosActivos }} activos
                            </span>
                        </div>
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#fffbeb;flex-shrink:0;">
                            <i class="bi bi-briefcase" style="font-size:1.1rem;color:#f59e0b;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress rounded-pill" style="height:5px;background:#f1f5f9;">
                            <div class="progress-bar rounded-pill" style="width:{{ $totalEmpleos > 0 ? round($empleosActivos/$totalEmpleos*100) : 0 }}%;background:#f59e0b;transition:width 1s ease;"></div>
                        </div>
                        <span class="text-secondary" style="font-size:0.7rem;">
                            {{ $totalEmpleos > 0 ? round($empleosActivos/$totalEmpleos*100) : 0 }}% vigentes
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Fila 2: Membresías + Usuarios + Cobertura ── --}}
    <div class="row g-4 mb-4">

        {{-- Membresías --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <p class="text-uppercase fw-bold text-secondary mb-0" style="font-size:0.62rem;letter-spacing:0.15em;">Membresías</p>
                            <h5 class="fw-black text-dark mb-0">Contratos activos</h5>
                        </div>
                        <a href="{{ route('membresias.index') }}" class="btn btn-sm rounded-3 fw-bold"
                           style="background:#f8fafc;color:#475569;font-size:0.72rem;">
                            Ver todos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#ecfdf5;">
                                <div class="fw-black text-dark" style="font-size:1.6rem;">{{ $contratosActivos }}</div>
                                <div class="fw-bold" style="font-size:0.7rem;color:#059669;">Activos</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#fffbeb;">
                                <div class="fw-black text-dark" style="font-size:1.6rem;">{{ $contratosPendientes }}</div>
                                <div class="fw-bold" style="font-size:0.7rem;color:#d97706;">Pendientes</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#fef2f2;">
                                <div class="fw-black text-dark" style="font-size:1.6rem;">{{ $contratosVencidos }}</div>
                                <div class="fw-bold" style="font-size:0.7rem;color:#dc2626;">Vencidos</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#f8fafc;">
                                <div class="fw-black text-dark" style="font-size:1.6rem;">{{ $contratosPorVencer }}</div>
                                <div class="fw-bold" style="font-size:0.7rem;color:#64748b;">Por vencer (7d)</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background:#eef2ff;color:#4f46e5;font-size:0.7rem;">
                            Premium: {{ $contratosPremium }}
                        </span>
                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background:#f1f5f9;color:#475569;font-size:0.7rem;">
                            Básico: {{ $contratosBasico }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Usuarios + Cobertura --}}
        <div class="col-12 col-lg-7">
            <div class="row g-4 h-100">

                {{-- Usuarios --}}
                <div class="col-12 col-sm-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <p class="text-uppercase fw-bold text-secondary mb-0" style="font-size:0.62rem;letter-spacing:0.15em;">Usuarios</p>
                                <div class="rounded-3 d-flex align-items-center justify-content-center"
                                     style="width:36px;height:36px;background:#fdf4ff;">
                                    <i class="bi bi-people" style="color:#a21caf;font-size:1rem;"></i>
                                </div>
                            </div>
                            <h3 class="fw-black text-dark mb-1" style="font-size:2rem;">{{ $totalUsuarios }}</h3>
                            <p class="text-secondary mb-3" style="font-size:0.78rem;">registrados en total</p>

                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Admins</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $usuariosAdmin }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Restaurantes</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $usuariosRestaurante }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Gastrobares</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $usuariosGastrobar }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Clientes</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $usuariosCliente }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cobertura --}}
                <div class="col-12 col-sm-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <p class="text-uppercase fw-bold text-secondary mb-0" style="font-size:0.62rem;letter-spacing:0.15em;">Cobertura</p>
                                <div class="rounded-3 d-flex align-items-center justify-content-center"
                                     style="width:36px;height:36px;background:#fffbeb;">
                                    <i class="bi bi-map" style="color:#f59e0b;font-size:1rem;"></i>
                                </div>
                            </div>
                            <h3 class="fw-black text-dark mb-1" style="font-size:2rem;">{{ $totalDepartamentos }}</h3>
                            <p class="text-secondary mb-3" style="font-size:0.78rem;">departamentos activos</p>

                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Municipios</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $totalMunicipios }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Con restaurantes</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $deptoConRestaurantes }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Con gastrobares</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $deptoConGastrobares }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size:0.75rem;">Con eventos activos</span>
                                    <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $deptoConEventos }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ── Fila 3: Actividad reciente + Accesos rápidos ── --}}
    <div class="row g-4 mb-4">

        {{-- Últimos eventos registrados --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <p class="text-uppercase fw-bold text-secondary mb-0" style="font-size:0.62rem;letter-spacing:0.15em;">Actividad Reciente</p>
                            <h5 class="fw-black text-dark mb-0">Últimos eventos publicados</h5>
                        </div>
                        <a href="{{ route('eventos.index') }}" class="btn btn-sm rounded-3 fw-bold"
                           style="background:#f8fafc;color:#475569;font-size:0.72rem;">
                            Ver todos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    @forelse($ultimosEventos as $evento)
                    <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#f8fafc;">
                            <i class="bi bi-calendar2-event" style="color:#4f46e5;font-size:0.95rem;"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold text-dark text-truncate" style="font-size:0.85rem;">
                                {{ $evento->titulo }}
                            </div>
                            <div class="text-secondary" style="font-size:0.75rem;">
                                {{ $evento->restaurante?->nombre ?? $evento->gastrobar?->nombre ?? 'Sin establecimiento' }}
                                &middot; {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M Y') }}
                            </div>
                        </div>
                        @if($evento->is_destacado)
                        <span class="badge rounded-pill fw-bold" style="background:#eef2ff;color:#4f46e5;font-size:0.65rem;white-space:nowrap;">
                            Destacado
                        </span>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-4 text-secondary" style="font-size:0.85rem;">
                        No hay eventos registrados aún.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-uppercase fw-bold text-secondary mb-3" style="font-size:0.62rem;letter-spacing:0.15em;">Accesos Rápidos</p>
                    <h5 class="fw-black text-dark mb-4">Gestión del sistema</h5>

                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('admin.restaurantes.create') }}"
                           class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none quick-link"
                           style="background:#f8fafc;">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;background:#eef2ff;">
                                <i class="bi bi-shop" style="color:#4f46e5;font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.82rem;">Nuevo restaurante</div>
                                <div class="text-secondary" style="font-size:0.72rem;">Registrar establecimiento</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary" style="font-size:0.75rem;"></i>
                        </a>

                        <a href="{{ route('admin.gastrobares.create') }}"
                           class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none quick-link"
                           style="background:#f8fafc;">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;background:#f5f3ff;">
                                <i class="bi bi-cup-straw" style="color:#7c3aed;font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.82rem;">Nuevo gastrobar</div>
                                <div class="text-secondary" style="font-size:0.72rem;">Registrar gastrobar</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary" style="font-size:0.75rem;"></i>
                        </a>

                        <a href="{{ route('eventos.create') }}"
                           class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none quick-link"
                           style="background:#f8fafc;">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;background:#ecfdf5;">
                                <i class="bi bi-megaphone" style="color:#10b981;font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.82rem;">Publicar evento</div>
                                <div class="text-secondary" style="font-size:0.72rem;">Crear nuevo evento</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary" style="font-size:0.75rem;"></i>
                        </a>

                        <a href="{{ route('admin.empleos.create') }}"
                           class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none quick-link"
                           style="background:#f8fafc;">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;background:#fffbeb;">
                                <i class="bi bi-briefcase" style="color:#f59e0b;font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.82rem;">Publicar empleo</div>
                                <div class="text-secondary" style="font-size:0.72rem;">Nueva oferta laboral</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary" style="font-size:0.75rem;"></i>
                        </a>

                        <a href="{{ route('usuarios.index') }}"
                           class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none quick-link"
                           style="background:#f8fafc;">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;background:#fdf4ff;">
                                <i class="bi bi-people" style="color:#a21caf;font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.82rem;">Gestionar usuarios</div>
                                <div class="text-secondary" style="font-size:0.72rem;">Ver todos los usuarios</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary" style="font-size:0.75rem;"></i>
                        </a>

                        <a href="{{ route('contratos.index') }}"
                           class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none quick-link"
                           style="background:#f8fafc;">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;background:#fef2f2;">
                                <i class="bi bi-file-earmark-text" style="color:#dc2626;font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.82rem;">Contratos</div>
                                <div class="text-secondary" style="font-size:0.72rem;">Administrar membresías</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary" style="font-size:0.75rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Banner Centro de Operaciones ── --}}
    <div class="position-relative rounded-4 overflow-hidden shadow-sm"
         style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
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
                    Gestiona restaurantes, gastrobares, eventos, empleos, contratos y usuarios desde un solo lugar.
                </p>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <a href="{{ route('admin.restaurantes.index') }}"
                   class="btn fw-bold text-dark px-4 py-3 text-decoration-none d-flex align-items-center justify-content-center gap-2"
                   style="background:white;border-radius:12px;font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;white-space:nowrap;transition:all 0.2s;"
                   onmouseover="this.style.background='#4f46e5';this.style.color='white';"
                   onmouseout="this.style.background='white';this.style.color='#0f172a';">
                    <i class="bi bi-grid-3x3-gap"></i> Ver Establecimientos
                </a>
                <a href="{{ route('reportes.index') }}"
                   class="btn text-white px-4 py-3 text-decoration-none d-flex align-items-center justify-content-center gap-2 fw-bold"
                   style="background:#1e293b;border:1px solid #334155;border-radius:12px;font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;white-space:nowrap;transition:background 0.2s;"
                   onmouseover="this.style.background='#334155';"
                   onmouseout="this.style.background='#1e293b';">
                    <i class="bi bi-bar-chart-line"></i> Ver Reportes
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    .dashboard-root { animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes fadeIn { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

    .pulse-dot { animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.35;} }

    .stat-card { transition: box-shadow 0.25s, border-color 0.25s; border: 1px solid #f1f5f9; }
    .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important; }
    .stat-indigo:hover  { border-color:#c7d2fe; }
    .stat-violet:hover  { border-color:#ddd6fe; }
    .stat-emerald:hover { border-color:#a7f3d0; }
    .stat-amber:hover   { border-color:#fde68a; }

    .stat-indigo:hover  .stat-icon { background:#4f46e5 !important; }
    .stat-indigo:hover  .stat-icon i { color:white !important; }
    .stat-violet:hover  .stat-icon { background:#7c3aed !important; }
    .stat-violet:hover  .stat-icon i { color:white !important; }
    .stat-emerald:hover .stat-icon { background:#10b981 !important; }
    .stat-emerald:hover .stat-icon i { color:white !important; }
    .stat-amber:hover   .stat-icon { background:#f59e0b !important; }
    .stat-amber:hover   .stat-icon i { color:white !important; }

    .quick-link { transition: background 0.18s, transform 0.18s; }
    .quick-link:hover { background:#eef2ff !important; transform:translateX(3px); }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
