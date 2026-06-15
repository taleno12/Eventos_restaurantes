@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- ── ENCABEZADO ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color:#1a1a1a;">
                <i class="bi bi-credit-card-2-front me-2" style="color:#ea580c;"></i>Gestión de Pagos
            </h2>
            <p class="text-muted mb-0 small">Registro y seguimiento de pagos de membresías</p>
        </div>
        <button class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalNuevoPago"
                style="background:#ea580c; border-color:#ea580c;">
            <i class="bi bi-plus-lg me-1"></i> Registrar Pago
        </button>
    </div>

    {{-- ── ALERTAS ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── TARJETAS DE RESUMEN ── --}}
    <div class="row g-3 mb-4">

        {{-- Total Recaudado --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ea580c !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:46px;height:46px;background:#fff3ee;">
                        <i class="bi bi-cash-stack fs-5" style="color:#ea580c;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Total Recaudado</p>
                        <h5 class="fw-bold mb-0">C$ {{ number_format($totalRecaudado, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recaudado este mes --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0ea5e9 !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:46px;height:46px;background:#e0f2fe;">
                        <i class="bi bi-calendar-check fs-5" style="color:#0ea5e9;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Este Mes</p>
                        <h5 class="fw-bold mb-0">C$ {{ number_format($recaudadoMes, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagos pendientes --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:46px;height:46px;background:#fffbeb;">
                        <i class="bi bi-hourglass-split fs-5" style="color:#f59e0b;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Pendientes</p>
                        <h5 class="fw-bold mb-0">{{ $totalPendientes }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagos confirmados --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #22c55e !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:46px;height:46px;background:#f0fdf4;">
                        <i class="bi bi-check2-circle fs-5" style="color:#22c55e;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Confirmados</p>
                        <h5 class="fw-bold mb-0">{{ $totalPagados }}</h5>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── FILTROS ── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pagos.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted small"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0"
                               placeholder="N° pago, contrato, establecimiento..."
                               value="{{ request('buscar') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pagado"   {{ request('estado') === 'pagado'   ? 'selected' : '' }}>Pagado</option>
                        <option value="pendiente"{{ request('estado') === 'pendiente'? 'selected' : '' }}>Pendiente</option>
                        <option value="anulado"  {{ request('estado') === 'anulado'  ? 'selected' : '' }}>Anulado</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Método</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="">Todos</option>
                        <option value="efectivo"      {{ request('metodo_pago') === 'efectivo'      ? 'selected' : '' }}>Efectivo</option>
                        <option value="transferencia" {{ request('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="tarjeta"       {{ request('metodo_pago') === 'tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                        <option value="deposito"      {{ request('metodo_pago') === 'deposito'      ? 'selected' : '' }}>Depósito</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-12 col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"
                            style="background:#ea580c; border-color:#ea580c;">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLA DE PAGOS ── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-list-ul me-2" style="color:#ea580c;"></i>
                Historial de Pagos
                <span class="badge ms-2 text-white" style="background:#ea580c;">{{ $pagos->total() }}</span>
            </h6>
        </div>

        <div class="card-body p-0">
            @if($pagos->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-3" style="color:#d1d5db;"></i>
                    <p class="mb-1 fw-semibold">No hay pagos registrados</p>
                    <p class="small">Usa el botón "Registrar Pago" para agregar el primero.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3 small fw-semibold text-muted text-uppercase">N° Pago</th>
                                <th class="py-3 small fw-semibold text-muted text-uppercase">Establecimiento</th>
                                <th class="py-3 small fw-semibold text-muted text-uppercase">Contrato</th>
                                <th class="py-3 small fw-semibold text-muted text-uppercase">Monto</th>
                                <th class="py-3 small fw-semibold text-muted text-uppercase">Método</th>
                                <th class="py-3 small fw-semibold text-muted text-uppercase">Fecha</th>
                                <th class="py-3 small fw-semibold text-muted text-uppercase">Estado</th>
                                <th class="py-3 small fw-semibold text-muted text-uppercase text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagos as $pago)
                            <tr>
                                {{-- N° Pago --}}
                                <td class="ps-4">
                                    <span class="fw-bold" style="color:#ea580c;">{{ $pago->numero_pago }}</span>
                                </td>

                                {{-- Establecimiento --}}
                                <td>
                                    @if($pago->contrato)
                                        @php $est = $pago->contrato->establecimiento(); @endphp
                                        @if($est)
                                            <div class="fw-semibold text-dark">{{ $est->nombre }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-{{ $pago->contrato->gastrobar_id ? 'cup-straw' : 'shop' }} me-1"></i>
                                                {{ $pago->contrato->gastrobar_id ? 'Gastrobar' : 'Restaurante' }}
                                            </small>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">Sin contrato</span>
                                    @endif
                                </td>

                                {{-- Contrato --}}
                                <td>
                                    @if($pago->contrato)
                                        <a href="{{ route('contratos.show', $pago->contrato) }}"
                                           class="text-decoration-none small fw-semibold"
                                           style="color:#ea580c;">
                                            {{ $pago->contrato->numero_contrato }}
                                        </a>
                                        <br>
                                        <small class="text-muted text-capitalize">
                                            {{ $pago->contrato->plan ?? '—' }}
                                        </small>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- Monto --}}
                                <td>
                                    <span class="fw-bold">C$ {{ number_format($pago->monto, 2) }}</span>
                                </td>

                                {{-- Método --}}
                                <td>
                                    @php
                                        $iconoMetodo = match($pago->metodo_pago) {
                                            'efectivo'      => 'bi-cash',
                                            'transferencia' => 'bi-arrow-left-right',
                                            'tarjeta'       => 'bi-credit-card',
                                            'deposito'      => 'bi-bank',
                                            default         => 'bi-wallet2',
                                        };
                                    @endphp
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi {{ $iconoMetodo }} me-1"></i>
                                        {{ $pago->metodo_pago_label }}
                                    </span>
                                    @if($pago->referencia)
                                        <br><small class="text-muted">Ref: {{ $pago->referencia }}</small>
                                    @endif
                                </td>

                                {{-- Fecha --}}
                                <td>
                                    <span class="small">{{ $pago->fecha_pago->format('d/m/Y') }}</span>
                                    @if($pago->periodo_inicio && $pago->periodo_fin)
                                        <br>
                                        <small class="text-muted">
                                            {{ $pago->periodo_inicio->format('d/m') }} – {{ $pago->periodo_fin->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </td>

                                {{-- Estado --}}
                                <td>
                                    @if($pago->estado === 'pagado')
                                        <span class="badge" style="background:#dcfce7; color:#16a34a;">
                                            <i class="bi bi-check-circle me-1"></i>Pagado
                                        </span>
                                    @elseif($pago->estado === 'pendiente')
                                        <span class="badge" style="background:#fef9c3; color:#a16207;">
                                            <i class="bi bi-hourglass me-1"></i>Pendiente
                                        </span>
                                    @else
                                        <span class="badge" style="background:#fee2e2; color:#dc2626;">
                                            <i class="bi bi-x-circle me-1"></i>Anulado
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">

                                        {{-- Ver detalle --}}
                                        <a href="{{ route('pagos.show', $pago) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Ver detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Confirmar (si pendiente) --}}
                                        @if($pago->estado === 'pendiente')
                                            <form action="{{ route('pagos.updateEstado', $pago) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="estado" value="pagado">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Confirmar pago">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Anular --}}
                                        @if($pago->estado !== 'anulado')
                                            <form action="{{ route('pagos.updateEstado', $pago) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="estado" value="anulado">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Anular"
                                                        onclick="return confirm('¿Anular este pago?')">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Eliminar --}}
                                        <form action="{{ route('pagos.destroy', $pago) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                    onclick="return confirm('¿Eliminar este pago permanentemente?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if($pagos->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $pagos->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════
     MODAL — REGISTRAR NUEVO PAGO
════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalNuevoPago" tabindex="-1" aria-labelledby="modalNuevoPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom" style="background:#fff3ee;">
                <h5 class="modal-title fw-bold" id="modalNuevoPagoLabel">
                    <i class="bi bi-plus-circle me-2" style="color:#ea580c;"></i>Registrar Nuevo Pago
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('pagos.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- ── Filtro: Departamento ── --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Departamento</label>
                            <select id="filtroDepartamento" class="form-select">
                                <option value="">— Todos —</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}">{{ $depto->nombre }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted" style="min-height:1.25rem; display:block;">
                                Filtra la lista de contratos por ubicación
                            </small>
                        </div>

                        {{-- ── Filtro: Municipio ── --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Municipio</label>
                            <select id="filtroMunicipio" class="form-select" disabled>
                                <option value="">— Seleccionar municipio —</option>
                            </select>
                            <small class="text-muted" style="min-height:1.25rem; display:block;">&nbsp;</small>
                        </div>

                        {{-- ── Tipo ── --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Tipo</label>
                            <select id="filtroTipo" class="form-select" disabled>
                                <option value="">— Todos —</option>
                                <option value="restaurante">Solo Restaurantes</option>
                                <option value="gastrobar">Solo Gastrobares</option>
                            </select>
                            <small class="text-muted" style="min-height:1.25rem; display:block;">&nbsp;</small>
                        </div>

                        {{-- Contrato --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Contrato <span class="text-danger">*</span>
                            </label>
                            <select id="selectContrato" name="contrato_id" class="form-select @error('contrato_id') is-invalid @enderror" required>
                                <option value="">— Seleccionar contrato —</option>
                                @foreach($contratosActivos as $contrato)
                                    @php $est = $contrato->establecimiento(); @endphp
                                    <option value="{{ $contrato->id }}"
                                            data-tipo="{{ $contrato->tipoEstablecimiento() }}"
                                            {{ old('contrato_id') == $contrato->id ? 'selected' : '' }}>
                                        {{ $contrato->numero_contrato }}
                                        — {{ $est ? $est->nombre : 'Sin establecimiento' }}
                                        ({{ ucfirst($contrato->plan ?? '—') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('contrato_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="ayudaContrato" class="text-muted">
                                Mostrando todos los contratos activos/pendientes. Usa los filtros de arriba para ubicar más rápido un restaurante o gastrobar.
                            </small>
                        </div>

                        {{-- Monto --}}
                        <div class="col-6 col-md-4">
                            <label class="form-label fw-semibold">
                                Monto (C$) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">C$</span>
                                <input type="number" name="monto" step="0.01" min="0.01"
                                       class="form-control @error('monto') is-invalid @enderror"
                                       value="{{ old('monto') }}" required>
                            </div>
                            @error('monto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Método de pago --}}
                        <div class="col-6 col-md-4">
                            <label class="form-label fw-semibold">
                                Método <span class="text-danger">*</span>
                            </label>
                            <select name="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror" required>
                                <option value="efectivo"      {{ old('metodo_pago') === 'efectivo'      ? 'selected' : '' }}>Efectivo</option>
                                <option value="transferencia" {{ old('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                <option value="tarjeta"       {{ old('metodo_pago') === 'tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                                <option value="deposito"      {{ old('metodo_pago') === 'deposito'      ? 'selected' : '' }}>Depósito</option>
                            </select>
                            @error('metodo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Fecha de pago --}}
                        <div class="col-6 col-md-4">
                            <label class="form-label fw-semibold">
                                Fecha de Pago <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="fecha_pago"
                                   class="form-control @error('fecha_pago') is-invalid @enderror"
                                   value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
                            @error('fecha_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Referencia --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Referencia / N° de transacción</label>
                            <input type="text" name="referencia"
                                   class="form-control @error('referencia') is-invalid @enderror"
                                   placeholder="Ej: TRF-20250601-001"
                                   value="{{ old('referencia') }}">
                            @error('referencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="pagado"    {{ old('estado', 'pagado') === 'pagado'    ? 'selected' : '' }}>Pagado</option>
                                <option value="pendiente" {{ old('estado')           === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Período que cubre --}}
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-semibold">Período desde</label>
                            <input type="date" name="periodo_inicio"
                                   class="form-control @error('periodo_inicio') is-invalid @enderror"
                                   value="{{ old('periodo_inicio') }}">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-semibold">Período hasta</label>
                            <input type="date" name="periodo_fin"
                                   class="form-control @error('periodo_fin') is-invalid @enderror"
                                   value="{{ old('periodo_fin') }}">
                        </div>

                        {{-- Notas --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Notas adicionales</label>
                            <textarea name="notas" rows="2"
                                      class="form-control @error('notas') is-invalid @enderror"
                                      placeholder="Observaciones opcionales...">{{ old('notas') }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary fw-semibold"
                            style="background:#ea580c; border-color:#ea580c;">
                        <i class="bi bi-check-lg me-1"></i>Guardar Pago
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Abrir modal automáticamente si hay errores de validación --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('modalNuevoPago'));
        modal.show();
    });
</script>
@endif

{{-- ════════════════════════════════════════════════════════════
     FILTRO EN CASCADA: Departamento -> Municipio -> Contrato
════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectDepartamento = document.getElementById('filtroDepartamento');
    const selectMunicipio    = document.getElementById('filtroMunicipio');
    const selectTipo         = document.getElementById('filtroTipo');
    const selectContrato     = document.getElementById('selectContrato');
    const ayudaContrato      = document.getElementById('ayudaContrato');

    // Guardamos las opciones originales (todos los contratos activos/pendientes)
    const opcionesOriginales = Array.from(selectContrato.options)
        .filter(opt => opt.value !== '')
        .map(opt => ({
            value: opt.value,
            text:  opt.textContent.trim(),
            tipo:  opt.dataset.tipo || ''
        }));

    function resetContrato(mensaje) {
        selectContrato.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = mensaje;
        selectContrato.appendChild(placeholder);
    }

    function poblarContratos(lista, vacioMsg) {
        resetContrato('— Seleccionar contrato —');

        if (lista.length === 0) {
            resetContrato(vacioMsg);
            return;
        }

        lista.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.value;
            opt.textContent = c.text;
            if (c.tipo) opt.dataset.tipo = c.tipo;
            selectContrato.appendChild(opt);
        });
    }

    function aplicarFiltroTipo(lista) {
        const tipo = selectTipo.value;
        if (!tipo) return lista;
        return lista.filter(c => c.tipo === tipo);
    }

    // ── Cambio de Departamento → recargar Municipios ──
    selectDepartamento.addEventListener('change', function () {
        const deptoId = this.value;

        selectTipo.value = '';
        selectTipo.disabled = true;
        delete selectMunicipio.dataset.contratos;

        if (!deptoId) {
            selectMunicipio.innerHTML = '<option value="">— Selecciona un departamento —</option>';
            selectMunicipio.disabled = true;
            poblarContratos(opcionesOriginales, '— No hay contratos disponibles —');
            ayudaContrato.textContent = 'Mostrando todos los contratos activos/pendientes. Usa los filtros de arriba para ubicar más rápido un restaurante o gastrobar.';
            return;
        }

        selectMunicipio.innerHTML = '<option value="">— Cargando... —</option>';
        selectMunicipio.disabled = true;

        fetch(`/pagos/ajax/municipios/${deptoId}`)
            .then(res => res.json())
            .then(data => {
                selectMunicipio.innerHTML = '<option value="">— Seleccionar municipio —</option>';
                data.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m.id;
                    opt.textContent = m.nombre;
                    selectMunicipio.appendChild(opt);
                });
                selectMunicipio.disabled = false;

                resetContrato('— Selecciona un municipio para ver los contratos —');
                ayudaContrato.textContent = 'Selecciona un municipio para ver los restaurantes/gastrobares de esa zona.';
            })
            .catch(() => {
                selectMunicipio.innerHTML = '<option value="">— Error al cargar municipios —</option>';
            });
    });

    // ── Cambio de Municipio → recargar Contratos ──
    selectMunicipio.addEventListener('change', function () {
        const municipioId = this.value;

        if (!municipioId) {
            selectTipo.value = '';
            selectTipo.disabled = true;
            delete selectMunicipio.dataset.contratos;
            resetContrato('— Selecciona un municipio para ver los contratos —');
            return;
        }

        resetContrato('— Cargando contratos... —');
        selectTipo.disabled = false;

        fetch(`/pagos/ajax/contratos?municipio_id=${municipioId}`)
            .then(res => res.json())
            .then(data => {
                const lista = data.map(c => ({
                    value: c.id,
                    text:  `${c.numero_contrato} — ${c.nombre} (${c.tipo === 'gastrobar' ? 'Gastrobar' : 'Restaurante'}${c.plan ? ' - ' + c.plan.charAt(0).toUpperCase() + c.plan.slice(1) : ''})`,
                    tipo:  c.tipo
                }));

                selectMunicipio.dataset.contratos = JSON.stringify(lista);

                const filtrada = aplicarFiltroTipo(lista);
                poblarContratos(filtrada, '— No hay contratos en este municipio —');

                ayudaContrato.textContent = `Mostrando ${filtrada.length} contrato(s) en este municipio.`;
            })
            .catch(() => {
                resetContrato('— Error al cargar contratos —');
            });
    });

    // ── Cambio de Tipo (restaurante / gastrobar) dentro del municipio ya cargado ──
    selectTipo.addEventListener('change', function () {
        if (!selectMunicipio.dataset.contratos) return;

        const lista = JSON.parse(selectMunicipio.dataset.contratos);
        const filtrada = aplicarFiltroTipo(lista);
        poblarContratos(filtrada, '— No hay contratos de este tipo en este municipio —');
        ayudaContrato.textContent = `Mostrando ${filtrada.length} contrato(s) en este municipio.`;
    });

});
</script>

@endsection
