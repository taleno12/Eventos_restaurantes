@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- ── ENCABEZADO ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color:#1a1a1a;">
                <i class="bi bi-receipt me-2" style="color:#ea580c;"></i>Detalle del Pago
            </h2>
            <p class="text-muted mb-0 small">
                <a href="{{ route('pagos.index') }}" class="text-decoration-none" style="color:#ea580c;">
                    ← Volver a Pagos
                </a>
            </p>
        </div>

        {{-- Acciones --}}
        <div class="d-flex gap-2 flex-wrap">

            {{-- Descargar PDF --}}
            <a href="{{ route('pagos.pdf', $pago) }}" class="btn btn-outline-secondary fw-semibold">
                <i class="bi bi-file-earmark-pdf me-1"></i>Descargar PDF
            </a>

            {{-- WhatsApp --}}
            @php
                $est        = $pago->contrato?->establecimiento();
                $nombreEst  = $est?->nombre ?? 'Sin establecimiento';
                $nContrato  = $pago->contrato?->numero_contrato ?? '—';
                $plan       = ucfirst($pago->contrato?->plan ?? '—');
                $estadoPago = ucfirst($pago->estado);
                $monto      = 'C$ ' . number_format($pago->monto, 2);
                $fecha      = $pago->fecha_pago->format('d/m/Y');
                $metodo     = $pago->metodo_pago_label;
                $referencia = $pago->referencia ? "Ref: {$pago->referencia}" : '';
                $periodo    = ($pago->periodo_inicio && $pago->periodo_fin)
                              ? "Período: {$pago->periodo_inicio->format('d/m/Y')} al {$pago->periodo_fin->format('d/m/Y')}"
                              : '';

                $mensaje = "✅ *Recibo de Pago - GastroNicaragua*\n\n"
                         . "📋 *N° Pago:* {$pago->numero_pago}\n"
                         . "🏪 *Establecimiento:* {$nombreEst}\n"
                         . "📄 *Contrato:* {$nContrato} ({$plan})\n"
                         . "💰 *Monto:* {$monto}\n"
                         . "💳 *Método:* {$metodo}\n"
                         . "📅 *Fecha:* {$fecha}\n"
                         . ($referencia ? "🔖 *{$referencia}*\n" : '')
                         . ($periodo    ? "🗓️ *{$periodo}*\n"    : '')
                         . "✔️ *Estado:* {$estadoPago}\n\n"
                         . "_GastroNicaragua — Sistema de Gestión Gastronómica_";

                $waUrl = 'https://wa.me/?text=' . rawurlencode($mensaje);
            @endphp

            <a href="{{ $waUrl }}" target="_blank" class="btn fw-semibold text-white"
               style="background:#25d366; border-color:#25d366;">
                <i class="bi bi-whatsapp me-1"></i>Enviar por WhatsApp
            </a>

            {{-- Confirmar (si pendiente) --}}
            @if($pago->estado === 'pendiente')
                <form action="{{ route('pagos.updateEstado', $pago) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="estado" value="pagado">
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="bi bi-check-lg me-1"></i>Confirmar
                    </button>
                </form>
            @endif

            {{-- Anular --}}
            @if($pago->estado !== 'anulado')
                <form action="{{ route('pagos.updateEstado', $pago) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="estado" value="anulado">
                    <button type="submit" class="btn btn-outline-warning fw-semibold"
                            onclick="return confirm('¿Anular este pago?')">
                        <i class="bi bi-slash-circle me-1"></i>Anular
                    </button>
                </form>
            @endif

            {{-- Eliminar --}}
            <form action="{{ route('pagos.destroy', $pago) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger fw-semibold"
                        onclick="return confirm('¿Eliminar este pago permanentemente?')">
                    <i class="bi bi-trash3 me-1"></i>Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── COLUMNA IZQUIERDA: datos del pago ── --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-credit-card me-2" style="color:#ea580c;"></i>
                        Información del Pago
                    </h6>
                    @if($pago->estado === 'pagado')
                        <span class="badge fs-6 px-3 py-2" style="background:#dcfce7; color:#16a34a;">
                            <i class="bi bi-check-circle me-1"></i>Pagado
                        </span>
                    @elseif($pago->estado === 'pendiente')
                        <span class="badge fs-6 px-3 py-2" style="background:#fef9c3; color:#a16207;">
                            <i class="bi bi-hourglass me-1"></i>Pendiente
                        </span>
                    @else
                        <span class="badge fs-6 px-3 py-2" style="background:#fee2e2; color:#dc2626;">
                            <i class="bi bi-x-circle me-1"></i>Anulado
                        </span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">

                        <div class="col-6">
                            <p class="text-muted small mb-1">N° de Pago</p>
                            <p class="fw-bold mb-0 fs-5" style="color:#ea580c;">{{ $pago->numero_pago }}</p>
                        </div>

                        <div class="col-6">
                            <p class="text-muted small mb-1">Monto</p>
                            <p class="fw-bold mb-0 fs-5">C$ {{ number_format($pago->monto, 2) }}</p>
                        </div>

                        <div class="col-6">
                            <p class="text-muted small mb-1">Método de Pago</p>
                            @php
                                $iconoMetodo = match($pago->metodo_pago) {
                                    'efectivo'      => 'bi-cash',
                                    'transferencia' => 'bi-arrow-left-right',
                                    'tarjeta'       => 'bi-credit-card',
                                    'deposito'      => 'bi-bank',
                                    default         => 'bi-wallet2',
                                };
                            @endphp
                            <p class="fw-semibold mb-0">
                                <i class="bi {{ $iconoMetodo }} me-1"></i>
                                {{ $pago->metodo_pago_label }}
                            </p>
                        </div>

                        <div class="col-6">
                            <p class="text-muted small mb-1">Fecha de Pago</p>
                            <p class="fw-semibold mb-0">{{ $pago->fecha_pago->format('d/m/Y') }}</p>
                        </div>

                        @if($pago->referencia)
                        <div class="col-12">
                            <p class="text-muted small mb-1">Referencia / N° de Transacción</p>
                            <p class="fw-semibold mb-0">
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="bi bi-hash me-1"></i>{{ $pago->referencia }}
                                </span>
                            </p>
                        </div>
                        @endif

                        @if($pago->periodo_inicio && $pago->periodo_fin)
                        <div class="col-12">
                            <p class="text-muted small mb-1">Período que Cubre</p>
                            <p class="fw-semibold mb-0">
                                <i class="bi bi-calendar-range me-1" style="color:#ea580c;"></i>
                                {{ $pago->periodo_inicio->format('d/m/Y') }}
                                &nbsp;→&nbsp;
                                {{ $pago->periodo_fin->format('d/m/Y') }}
                            </p>
                        </div>
                        @endif

                        @if($pago->notas)
                        <div class="col-12">
                            <p class="text-muted small mb-1">Notas</p>
                            <p class="mb-0 p-3 rounded" style="background:#f9fafb; border-left: 3px solid #ea580c;">
                                {{ $pago->notas }}
                            </p>
                        </div>
                        @endif

                        <div class="col-6">
                            <p class="text-muted small mb-1">Registrado</p>
                            <p class="small mb-0">{{ $pago->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        @if($pago->registradoPor)
                        <div class="col-6">
                            <p class="text-muted small mb-1">Registrado por</p>
                            <p class="small mb-0 fw-semibold">{{ $pago->registradoPor->name }}</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- ── COLUMNA DERECHA: datos del contrato ── --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-file-earmark-text me-2" style="color:#ea580c;"></i>
                        Contrato Asociado
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($pago->contrato)
                        @php $est = $pago->contrato->establecimiento(); @endphp

                        <div class="mb-3 pb-3 border-bottom">
                            <p class="text-muted small mb-1">N° Contrato</p>
                            <a href="{{ route('contratos.show', $pago->contrato) }}"
                               class="fw-bold text-decoration-none fs-6" style="color:#ea580c;">
                                {{ $pago->contrato->numero_contrato }}
                            </a>
                        </div>

                        @if($est)
                        <div class="mb-3 pb-3 border-bottom">
                            <p class="text-muted small mb-1">Establecimiento</p>
                            <p class="fw-bold mb-0">{{ $est->nombre }}</p>
                            <small class="text-muted">
                                <i class="bi bi-{{ $pago->contrato->gastrobar_id ? 'cup-straw' : 'shop' }} me-1"></i>
                                {{ $pago->contrato->gastrobar_id ? 'Gastrobar' : 'Restaurante' }}
                            </small>
                        </div>
                        @endif

                        <div class="mb-3 pb-3 border-bottom">
                            <p class="text-muted small mb-1">Plan</p>
                            <span class="badge text-capitalize px-3 py-2"
                                  style="background:#fff3ee; color:#ea580c; border: 1px solid #ea580c;">
                                {{ $pago->contrato->plan ?? '—' }}
                            </span>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <p class="text-muted small mb-1">Estado del Contrato</p>
                            @php $estadoContrato = $pago->contrato->estado; @endphp
                            @if($estadoContrato === 'activo')
                                <span class="badge" style="background:#dcfce7; color:#16a34a;">Activo</span>
                            @elseif($estadoContrato === 'pendiente')
                                <span class="badge" style="background:#fef9c3; color:#a16207;">Pendiente</span>
                            @elseif($estadoContrato === 'vencido')
                                <span class="badge" style="background:#fee2e2; color:#dc2626;">Vencido</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($estadoContrato) }}</span>
                            @endif
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <p class="text-muted small mb-1">Representante</p>
                            <p class="fw-semibold mb-0">{{ $pago->contrato->representante ?? '—' }}</p>
                        </div>

                        <div>
                            <p class="text-muted small mb-1">Vigencia del Contrato</p>
                            <p class="small mb-0">
                                {{ $pago->contrato->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                &nbsp;→&nbsp;
                                {{ $pago->contrato->fecha_fin?->format('d/m/Y') ?? '—' }}
                            </p>
                        </div>

                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-file-earmark-x display-5 d-block mb-2" style="color:#d1d5db;"></i>
                            <p class="small">Contrato no disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
