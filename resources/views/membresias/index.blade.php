@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold">
                <i class="bi bi-patch-check-fill text-warning me-2"></i> Membresías
            </h1>
            <p class="text-muted mb-0 small">
                Gestión de todas las membresías registradas en la plataforma.
            </p>
        </div>
    </div>

    {{-- ── Tarjetas de Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Activas</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalActivas }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-info bg-info bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Pendientes</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalPendientes }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-danger bg-danger bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Vencidas</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalVencidas }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary bg-secondary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Canceladas</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalCanceladas }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pestañas Bootstrap ── --}}
    <ul class="nav nav-tabs" id="membresiasTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="tab-activas" data-bs-toggle="tab"
                    data-bs-target="#pane-activas" type="button" role="tab"
                    aria-controls="pane-activas" aria-selected="true">
                <i class="bi bi-patch-check-fill text-success me-1"></i> Activas
                <span class="badge bg-success ms-1">{{ $totalActivas }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-pendientes" data-bs-toggle="tab"
                    data-bs-target="#pane-pendientes" type="button" role="tab"
                    aria-controls="pane-pendientes" aria-selected="false">
                <i class="bi bi-hourglass-split text-info me-1"></i> Pendientes
                <span class="badge bg-info ms-1">{{ $totalPendientes }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-vencidas" data-bs-toggle="tab"
                    data-bs-target="#pane-vencidas" type="button" role="tab"
                    aria-controls="pane-vencidas" aria-selected="false">
                <i class="bi bi-clock-history text-danger me-1"></i> Vencidas
                <span class="badge bg-danger ms-1">{{ $totalVencidas }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-canceladas" data-bs-toggle="tab"
                    data-bs-target="#pane-canceladas" type="button" role="tab"
                    aria-controls="pane-canceladas" aria-selected="false">
                <i class="bi bi-x-circle text-secondary me-1"></i> Canceladas
                <span class="badge bg-secondary ms-1">{{ $totalCanceladas }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="membresiasTabContent">

        {{-- ══════════════ TAB ACTIVAS ══════════════ --}}
        <div class="tab-pane fade show active" id="pane-activas" role="tabpanel" aria-labelledby="tab-activas">
            <div class="card border-0 shadow-sm rounded-bottom-3 rounded-top-0 overflow-hidden bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Establecimiento</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Tipo</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Plan</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Vigencia</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Días Restantes</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Representante</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Monto</th>
                                    <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Contrato</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($membresiasActivas as $contrato)
                                @php
                                    $diasRestantes = (int) now()->diffInDays($contrato->fecha_fin, false);
                                    $alertaColor   = $diasRestantes <= 7 ? 'text-danger fw-bold' : ($diasRestantes <= 30 ? 'text-warning fw-semibold' : 'text-success');
                                    $planColor     = match($contrato->plan) {
                                        'premium' => ['bg' => '#fff8e1', 'color' => '#b45309'],
                                        'basico'  => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                        default   => ['bg' => '#f3f4f6', 'color' => '#6b7280'],
                                    };
                                @endphp
                                <tr class="border-bottom" style="border-color:#edf2f7 !important;">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-pill me-3" style="width:4px;height:32px;"></div>
                                            <span class="fw-semibold text-dark">{{ $contrato->establecimiento()?->nombre ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3"><small class="text-muted text-capitalize">{{ $contrato->tipoEstablecimiento() }}</small></td>
                                    <td class="py-3">
                                        <span class="badge px-2 py-1 text-uppercase fw-bold"
                                              style="background:{{ $planColor['bg'] }};color:{{ $planColor['color'] }};font-size:0.7rem;">
                                            {{ ucfirst($contrato->plan) }}
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->fecha_inicio->format('d/m/Y') }} – {{ $contrato->fecha_fin->format('d/m/Y') }}</small></td>
                                    <td class="py-3">
                                        <span class="{{ $alertaColor }}">
                                            {{ $diasRestantes <= 0 ? 'Vencida' : $diasRestantes.' días' }}
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->representante }}</small></td>
                                    <td class="py-3">
                                        <small class="text-dark">C$ {{ number_format($contrato->monto, 2) }}</small>
                                        @if($contrato->forma_pago)<small class="text-muted d-block text-capitalize">{{ $contrato->forma_pago }}</small>@endif
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-patch-check d-block display-6 mb-3"></i>
                                        No hay membresías activas registradas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($membresiasActivas->hasPages())
                <div class="mt-3 d-flex justify-content-end">{{ $membresiasActivas->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>

        {{-- ══════════════ TAB PENDIENTES ══════════════ --}}
        <div class="tab-pane fade" id="pane-pendientes" role="tabpanel" aria-labelledby="tab-pendientes">
            <div class="card border-0 shadow-sm rounded-bottom-3 rounded-top-0 overflow-hidden bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Establecimiento</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Tipo</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Plan</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Vigencia</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Estado</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Representante</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Monto</th>
                                    <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Contrato</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($membresiasPendientes as $contrato)
                                @php
                                    $planColor = match($contrato->plan) {
                                        'premium' => ['bg' => '#fff8e1', 'color' => '#b45309'],
                                        'basico'  => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                        default   => ['bg' => '#f3f4f6', 'color' => '#6b7280'],
                                    };
                                @endphp
                                <tr class="border-bottom" style="border-color:#edf2f7 !important;">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info rounded-pill me-3" style="width:4px;height:32px;"></div>
                                            <span class="fw-semibold text-dark">{{ $contrato->establecimiento()?->nombre ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3"><small class="text-muted text-capitalize">{{ $contrato->tipoEstablecimiento() }}</small></td>
                                    <td class="py-3">
                                        <span class="badge px-2 py-1 text-uppercase fw-bold"
                                              style="background:{{ $planColor['bg'] }};color:{{ $planColor['color'] }};font-size:0.7rem;">
                                            {{ ucfirst($contrato->plan) }}
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->fecha_inicio->format('d/m/Y') }} – {{ $contrato->fecha_fin->format('d/m/Y') }}</small></td>
                                    <td class="py-3">
                                        <span class="badge px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                              style="background-color:#ebf8ff;color:#2b6cb0;border:1px solid #bee3f8;font-size:0.72rem;">
                                            <span class="bg-info rounded-circle" style="width:5px;height:5px;"></span>
                                            PENDIENTE
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->representante }}</small></td>
                                    <td class="py-3">
                                        <small class="text-dark">C$ {{ number_format($contrato->monto, 2) }}</small>
                                        @if($contrato->forma_pago)<small class="text-muted d-block text-capitalize">{{ $contrato->forma_pago }}</small>@endif
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-hourglass-split d-block display-6 mb-3"></i>
                                        No hay membresías pendientes.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($membresiasPendientes->hasPages())
                <div class="mt-3 d-flex justify-content-end">{{ $membresiasPendientes->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>

        {{-- ══════════════ TAB VENCIDAS ══════════════ --}}
        <div class="tab-pane fade" id="pane-vencidas" role="tabpanel" aria-labelledby="tab-vencidas">
            <div class="card border-0 shadow-sm rounded-bottom-3 rounded-top-0 overflow-hidden bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Establecimiento</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Tipo</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Plan</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Vigencia</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Estado</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Representante</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Monto</th>
                                    <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Contrato</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($membresiasVencidas as $contrato)
                                @php
                                    $planColor = match($contrato->plan) {
                                        'premium' => ['bg' => '#fff8e1', 'color' => '#b45309'],
                                        'basico'  => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                        default   => ['bg' => '#f3f4f6', 'color' => '#6b7280'],
                                    };
                                @endphp
                                <tr class="border-bottom" style="border-color:#edf2f7 !important; opacity:0.8;">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger rounded-pill me-3" style="width:4px;height:32px;"></div>
                                            <span class="fw-semibold text-dark">{{ $contrato->establecimiento()?->nombre ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3"><small class="text-muted text-capitalize">{{ $contrato->tipoEstablecimiento() }}</small></td>
                                    <td class="py-3">
                                        <span class="badge px-2 py-1 text-uppercase fw-bold"
                                              style="background:{{ $planColor['bg'] }};color:{{ $planColor['color'] }};font-size:0.7rem;">
                                            {{ ucfirst($contrato->plan) }}
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->fecha_inicio->format('d/m/Y') }} – {{ $contrato->fecha_fin->format('d/m/Y') }}</small></td>
                                    <td class="py-3">
                                        <span class="badge px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                              style="background-color:#fff5f5;color:#c53030;border:1px solid #fed7d7;font-size:0.72rem;">
                                            <span class="bg-danger rounded-circle" style="width:5px;height:5px;"></span>
                                            VENCIDA
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->representante }}</small></td>
                                    <td class="py-3">
                                        <small class="text-dark">C$ {{ number_format($contrato->monto, 2) }}</small>
                                        @if($contrato->forma_pago)<small class="text-muted d-block text-capitalize">{{ $contrato->forma_pago }}</small>@endif
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-clock-history d-block display-6 mb-3"></i>
                                        No hay membresías vencidas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($membresiasVencidas->hasPages())
                <div class="mt-3 d-flex justify-content-end">{{ $membresiasVencidas->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>

        {{-- ══════════════ TAB CANCELADAS ══════════════ --}}
        <div class="tab-pane fade" id="pane-canceladas" role="tabpanel" aria-labelledby="tab-canceladas">
            <div class="card border-0 shadow-sm rounded-bottom-3 rounded-top-0 overflow-hidden bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Establecimiento</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Tipo</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Plan</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Vigencia</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Estado</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Representante</th>
                                    <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Monto</th>
                                    <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Contrato</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($membresiasCanceladas as $contrato)
                                @php
                                    $planColor = match($contrato->plan) {
                                        'premium' => ['bg' => '#fff8e1', 'color' => '#b45309'],
                                        'basico'  => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                        default   => ['bg' => '#f3f4f6', 'color' => '#6b7280'],
                                    };
                                @endphp
                                <tr class="border-bottom" style="border-color:#edf2f7 !important; opacity:0.65;">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                            <span class="fw-semibold text-dark">{{ $contrato->establecimiento()?->nombre ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3"><small class="text-muted text-capitalize">{{ $contrato->tipoEstablecimiento() }}</small></td>
                                    <td class="py-3">
                                        <span class="badge px-2 py-1 text-uppercase fw-bold"
                                              style="background:{{ $planColor['bg'] }};color:{{ $planColor['color'] }};font-size:0.7rem;">
                                            {{ ucfirst($contrato->plan) }}
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->fecha_inicio->format('d/m/Y') }} – {{ $contrato->fecha_fin->format('d/m/Y') }}</small></td>
                                    <td class="py-3">
                                        <span class="badge px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                              style="background-color:#f7fafc;color:#4a5568;border:1px solid #e2e8f0;font-size:0.72rem;">
                                            <span class="bg-secondary rounded-circle" style="width:5px;height:5px;"></span>
                                            CANCELADA
                                        </span>
                                    </td>
                                    <td class="py-3"><small class="text-dark">{{ $contrato->representante }}</small></td>
                                    <td class="py-3">
                                        <small class="text-dark">C$ {{ number_format($contrato->monto, 2) }}</small>
                                        @if($contrato->forma_pago)<small class="text-muted d-block text-capitalize">{{ $contrato->forma_pago }}</small>@endif
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-x-circle d-block display-6 mb-3"></i>
                                        No hay membresías canceladas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($membresiasCanceladas->hasPages())
                <div class="mt-3 d-flex justify-content-end">{{ $membresiasCanceladas->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>

    </div>{{-- fin tab-content --}}

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
