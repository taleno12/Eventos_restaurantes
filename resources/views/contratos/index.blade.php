@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado Principal ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold">
                <i class="bi bi-file-earmark-text-fill text-warning me-2"></i> Gestión de Contratos
            </h1>
            <p class="text-muted mb-0 small">
                Administración de contratos y términos de uso de establecimientos registrados.
            </p>
        </div>
        <button class="btn btn-warning px-4 rounded-pill shadow-sm fw-semibold text-dark"
                data-bs-toggle="modal" data-bs-target="#modalNuevoContrato">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Contrato
        </button>
    </div>

    {{-- ── Mensaje de Éxito ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ── Tarjetas de Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Total</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $total }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Activos</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $activos }}</h3>
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
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Vencidos</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $vencidos }}</h3>
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
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $pendientes }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Barra de Búsqueda y Filtros ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('contratos.index') }}" class="row g-3 align-items-center">
            <div class="col-12 col-sm-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control bg-light border-start-0 ps-0"
                           placeholder="Buscar por gastrobar o número de contrato..."
                           style="box-shadow:none;">
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                    <select name="estado" class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;cursor:pointer;">
                        <option value="">Todos los estados</option>
                        <option value="activo"    {{ request('estado') == 'activo'    ? 'selected' : '' }}>Activo</option>
                        <option value="vencido"   {{ request('estado') == 'vencido'   ? 'selected' : '' }}>Vencido</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-credit-card"></i></span>
                    <select name="plan" class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;cursor:pointer;">
                        <option value="">Todos los planes</option>
                        <option value="gratuito" {{ request('plan') == 'gratuito' ? 'selected' : '' }}>Gratuito</option>
                        <option value="basico"   {{ request('plan') == 'basico'   ? 'selected' : '' }}>Básico</option>
                        <option value="premium"  {{ request('plan') == 'premium'  ? 'selected' : '' }}>Premium</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-funnel-fill text-warning"></i> Filtrar
                </button>
                @if(request('search') || request('estado') || request('plan'))
                    <a href="{{ route('contratos.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center" title="Limpiar Filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Tabla Principal de Contratos ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-uppercase text-muted border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;"># Contrato</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Establecimiento</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Plan</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Vigencia</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Representante</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Estado</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;width:140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($contratos as $contrato)
                        <tr class="border-bottom" style="border-color:#edf2f7 !important;">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <span class="fw-bold text-dark small">#{{ $contrato->numero_contrato }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="fw-semibold text-dark d-block" style="font-size:0.9rem;">
                                    {{ $contrato->establecimiento()?->nombre ?? '—' }}
                                </span>
                                <small class="text-muted text-capitalize">{{ $contrato->tipoEstablecimiento() }}</small>
                            </td>
                            <td class="py-3">
                                @php
                                    $planColor = match($contrato->plan) {
                                        'premium'  => ['bg' => '#fff8e1', 'color' => '#b45309'],
                                        'basico'   => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                        default    => ['bg' => '#f3f4f6', 'color' => '#6b7280'],
                                    };
                                @endphp
                                <span class="badge px-2 py-1 text-uppercase fw-bold"
                                      style="background:{{ $planColor['bg'] }};color:{{ $planColor['color'] }};font-size:0.7rem;">
                                    {{ ucfirst($contrato->plan) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <small class="text-dark d-block">
                                    {{ $contrato->fecha_inicio->format('d/m/Y') }} – {{ $contrato->fecha_fin->format('d/m/Y') }}
                                </small>
                                <small class="text-muted">
                                    @if($contrato->fecha_fin->isFuture())
                                        {{ (int) now()->diffInDays($contrato->fecha_fin) }} días restantes
                                    @else
                                        Vencido
                                    @endif
                                </small>
                            </td>
                            <td class="py-3">
                                <span class="text-dark small">{{ $contrato->representante }}</span>
                            </td>
                            <td class="py-3 text-center">
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
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('contratos.show', $contrato) }}" class="text-secondary p-1" title="Ver Contrato">
                                        <i class="bi bi-eye fs-5"></i>
                                    </a>
                                    <a href="{{ route('contratos.edit', $contrato) }}" class="text-secondary p-1" title="Editar">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    <form action="{{ route('contratos.destroy', $contrato) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar contrato {{ $contrato->numero_contrato }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-1 text-secondary border-0 bg-transparent" title="Eliminar">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-file-earmark-text d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block mb-2">No hay contratos registrados aún.</span>
                                <button class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold text-dark"
                                        data-bs-toggle="modal" data-bs-target="#modalNuevoContrato">
                                    Crear Primer Contrato
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Paginación ── --}}
    @if($contratos->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $contratos->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

{{-- ── Modal: Nuevo Contrato ── --}}
<div class="modal fade" id="modalNuevoContrato" tabindex="-1" aria-labelledby="modalNuevoContratoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="modalNuevoContratoLabel">
                        <i class="bi bi-file-earmark-plus-fill text-warning me-2"></i> Nuevo Contrato
                    </h5>
                    <p class="text-muted small mb-0">Completa los datos para registrar el contrato del establecimiento.</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <form id="formNuevoContrato" action="{{ route('contratos.store') }}" method="POST">
                    @csrf

                    <p class="text-uppercase fw-bold text-muted mb-2 mt-1" style="font-size:0.7rem;letter-spacing:0.8px;">
                        <i class="bi bi-building me-1"></i> Datos del Establecimiento
                    </p>
                    <div class="row g-3 mb-3">

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-dark">Tipo de Establecimiento <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_establecimiento"
                                           id="tipoGastrobar" value="gastrobar" checked>
                                    <label class="form-check-label small" for="tipoGastrobar">Gastrobar</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_establecimiento"
                                           id="tipoRestaurante" value="restaurante">
                                    <label class="form-check-label small" for="tipoRestaurante">Restaurante</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" id="selectGastrobarWrap">
                            <label class="form-label small fw-semibold text-dark">Gastrobar <span class="text-danger">*</span></label>
                            <select name="gastrobar_id" id="gastrobar_id" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                                <option value="">Seleccionar gastrobar</option>
                                @foreach($gastrobares as $g)
                                    <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 d-none" id="selectRestauranteWrap">
                            <label class="form-label small fw-semibold text-dark">Restaurante <span class="text-danger">*</span></label>
                            <select name="restaurante_id" id="restaurante_id" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                                <option value="">Seleccionar restaurante</option>
                                @foreach($restaurantes as $r)
                                    <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Representante Legal <span class="text-danger">*</span></label>
                            <input type="text" name="representante" class="form-control bg-light" placeholder="Nombre completo" style="box-shadow:none;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Dirección Física</label>
                            <input type="text" name="direccion" class="form-control bg-light" placeholder="Dirección del local" style="box-shadow:none;">
                        </div>
                    </div>

                    <hr class="my-3" style="border-color:#edf2f7;">

                    <p class="text-uppercase fw-bold text-muted mb-2" style="font-size:0.7rem;letter-spacing:0.8px;">
                        <i class="bi bi-credit-card me-1"></i> Plan y Condiciones Comerciales
                    </p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Plan <span class="text-danger">*</span></label>
                            <select name="plan" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                                <option value="">Seleccionar plan</option>
                                <option value="gratuito">Gratuito</option>
                                <option value="basico">Básico</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_inicio" class="form-control bg-light" style="box-shadow:none;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Fecha de Vencimiento <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_fin" class="form-control bg-light" style="box-shadow:none;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                                <option value="pendiente" selected>Pendiente</option>
                                <option value="activo">Activo</option>
                                <option value="vencido">Vencido</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Monto (C$)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">C$</span>
                                <input type="number" name="monto" class="form-control bg-light" placeholder="0.00" min="0" step="0.01" style="box-shadow:none;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Forma de Pago</label>
                            <select name="forma_pago" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                                <option value="">Seleccionar</option>
                                <option value="mensual">Mensual</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-3" style="border-color:#edf2f7;">

                    <p class="text-uppercase fw-bold text-muted mb-2" style="font-size:0.7rem;letter-spacing:0.8px;">
                        <i class="bi bi-shield-check me-1"></i> Términos y Condiciones
                    </p>
                    <div class="bg-light rounded-3 p-3 mb-3" style="max-height:120px;overflow-y:auto;font-size:0.82rem;color:#555;line-height:1.6;">
                        El establecimiento registrado acepta utilizar la plataforma únicamente para publicar información verídica,
                        eventos reales y gestionar su perfil de forma responsable. Queda prohibido publicar contenido falso,
                        eventos fraudulentos o violar derechos de terceros. El establecimiento es responsable de mantener
                        actualizados sus datos, eventos y disponibilidad. La plataforma actúa como vitrina digital; la
                        responsabilidad de la atención al cliente y la realización de los eventos corresponde al establecimiento.
                        Las credenciales de acceso son responsabilidad exclusiva del establecimiento. El incumplimiento
                        de estos términos podrá resultar en la suspensión o eliminación de la cuenta.
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="acepta_terminos" name="acepta_terminos">
                        <label class="form-check-label small text-dark" for="acepta_terminos">
                            El representante legal acepta los <a href="#" class="text-warning fw-semibold">Términos y Condiciones</a>. <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="acepta_privacidad" name="acepta_privacidad">
                        <label class="form-check-label small text-dark" for="acepta_privacidad">
                            Acepta la <a href="#" class="text-warning fw-semibold">Política de Privacidad</a> y el tratamiento de datos. <span class="text-danger">*</span>
                        </label>
                    </div>

                </form>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" form="formNuevoContrato" class="btn btn-warning rounded-pill px-5 fw-semibold text-dark shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar Contrato
                </button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelectorAll('input[name="tipo_establecimiento"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const esGastrobar = this.value === 'gastrobar';
            document.getElementById('selectGastrobarWrap').classList.toggle('d-none', !esGastrobar);
            document.getElementById('selectRestauranteWrap').classList.toggle('d-none', esGastrobar);
        });
    });
</script>

@endsection
