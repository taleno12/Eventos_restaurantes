@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold">
                <i class="bi bi-file-earmark-text-fill text-warning me-2"></i>
                Contrato #{{ $contrato->numero_contrato }}
            </h1>
            <p class="text-muted mb-0 small">Detalle completo del contrato del establecimiento.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('contratos.edit', $contrato) }}" class="btn btn-warning rounded-pill px-4 fw-semibold text-dark">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <a href="{{ route('contratos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Datos del Establecimiento --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <p class="text-uppercase fw-bold text-muted mb-3" style="font-size:0.7rem;letter-spacing:0.8px;">
                        <i class="bi bi-building me-1"></i> Establecimiento
                    </p>
                    <table class="table table-borderless mb-0 small">
                        <tr>
                            <td class="text-muted fw-semibold ps-0" style="width:40%;">Tipo</td>
                            <td class="text-dark text-capitalize">{{ $contrato->tipoEstablecimiento() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-0">Nombre</td>
                            <td class="text-dark fw-semibold">{{ $contrato->establecimiento()?->nombre ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-0">Representante</td>
                            <td class="text-dark">{{ $contrato->representante }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-0">Dirección</td>
                            <td class="text-dark">{{ $contrato->direccion ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Estado y Plan --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <p class="text-uppercase fw-bold text-muted mb-3" style="font-size:0.7rem;letter-spacing:0.8px;">
                        <i class="bi bi-credit-card me-1"></i> Plan y Estado
                    </p>
                    <table class="table table-borderless mb-0 small">
                        <tr>
                            <td class="text-muted fw-semibold ps-0" style="width:40%;">Plan</td>
                            <td>
                                @php
                                    $planColor = match($contrato->plan) {
                                        'premium' => ['bg' => '#fff8e1', 'color' => '#b45309'],
                                        'basico'  => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                        default   => ['bg' => '#f3f4f6', 'color' => '#6b7280'],
                                    };
                                @endphp
                                <span class="badge px-2 py-1 text-uppercase fw-bold"
                                      style="background:{{ $planColor['bg'] }};color:{{ $planColor['color'] }};font-size:0.7rem;">
                                    {{ ucfirst($contrato->plan) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-0">Estado</td>
                            <td>
                                @php
                                    $estadoStyle = match($contrato->estado) {
                                        'activo'    => ['bg' => '#e6fffa', 'color' => '#047481', 'border' => '#b2f5ea', 'dot' => 'bg-success'],
                                        'vencido'   => ['bg' => '#fff5f5', 'color' => '#c53030', 'border' => '#fed7d7', 'dot' => 'bg-danger'],
                                        'pendiente' => ['bg' => '#ebf8ff', 'color' => '#2b6cb0', 'border' => '#bee3f8', 'dot' => 'bg-info'],
                                        default     => ['bg' => '#f7fafc', 'color' => '#4a5568', 'border' => '#e2e8f0', 'dot' => 'bg-secondary'],
                                    };
                                @endphp
                                <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                      style="background-color:{{ $estadoStyle['bg'] }};color:{{ $estadoStyle['color'] }};border:1px solid {{ $estadoStyle['border'] }};font-size:0.72rem;">
                                    <span class="{{ $estadoStyle['dot'] }} rounded-circle" style="width:5px;height:5px;"></span>
                                    {{ strtoupper($contrato->estado) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-0">Forma de Pago</td>
                            <td class="text-dark text-capitalize">{{ $contrato->forma_pago ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-0">Monto</td>
                            <td class="text-dark fw-semibold">C$ {{ number_format($contrato->monto, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Vigencia --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <p class="text-uppercase fw-bold text-muted mb-3" style="font-size:0.7rem;letter-spacing:0.8px;">
                        <i class="bi bi-calendar-range me-1"></i> Vigencia del Contrato
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Fecha de Inicio</p>
                            <p class="text-dark fw-bold mb-0">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Fecha de Vencimiento</p>
                            <p class="text-dark fw-bold mb-0">{{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Tiempo Restante</p>
                            <p class="mb-0">
                                @if($contrato->fecha_fin->isFuture())
                                    <span class="text-success fw-bold">{{ (int) now()->diffInDays($contrato->fecha_fin) }} días restantes</span>
                                @else
                                    <span class="text-danger fw-bold">Vencido hace {{ (int) $contrato->fecha_fin->diffInDays(now()) }} días</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
