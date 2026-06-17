@extends('restaurante.layout')
@section('title', 'Pedidos')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#2d3748;">
                <i class="bi bi-bag text-primary me-2"></i> Pedidos
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Gestión en tiempo real de {{ $restaurante->nombre }}
            </p>
        </div>
        <div id="live-indicator" class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill"
             style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);font-size:11px;font-weight:700;color:#16a34a;">
            <span style="width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block;animation:pulse 2s infinite;"></span>
            En vivo
        </div>
    </div>

    {{-- ── Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(245,158,11,0.12);color:#f59e0b;flex-shrink:0;">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Pendientes</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $pendientes }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(34,197,94,0.12);color:#22c55e;flex-shrink:0;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Pedidos hoy</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalHoy }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(249,115,22,0.12);color:#f97316;flex-shrink:0;">
                        <i class="bi bi-coin"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Ingresos hoy</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">C$ {{ number_format($ingresoHoy, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filtros ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;">
                        <i class="bi bi-search me-1"></i> Buscar
                    </label>
                    <input type="text" id="filtro-buscar" class="form-control form-control-sm rounded-pill"
                           placeholder="# pedido o cliente...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;">
                        <i class="bi bi-funnel me-1"></i> Estado
                    </label>
                    <select id="filtro-estado" class="form-select form-select-sm rounded-pill">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="en_preparacion">En preparación</option>
                        <option value="listo">Listo</option>
                        <option value="entregado">Entregado</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;">
                        <i class="bi bi-tag me-1"></i> Tipo
                    </label>
                    <select id="filtro-tipo" class="form-select form-select-sm rounded-pill">
                        <option value="">Todos</option>
                        <option value="mesa">Mesa</option>
                        <option value="para_llevar">Para llevar</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <button id="btn-limpiar" class="btn btn-link btn-sm text-muted p-0" style="font-size:11px;display:none;">
                        <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <span id="contador-resultados" class="text-muted" style="font-size:11px;"></span>
            </div>
        </div>
    </div>

    {{-- ── Tabla de pedidos ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">

            @php
                $estadosInfo = \App\Models\Pedido::ESTADOS;
                $todosLosPedidos = collect();
                foreach($pedidos as $grupo) {
                    $todosLosPedidos = $todosLosPedidos->merge($grupo);
                }
                $todosLosPedidos = $todosLosPedidos->sortByDesc('created_at');
            @endphp

            @if($todosLosPedidos->count() > 0)
            <div id="sin-resultados" class="text-center text-muted py-5" style="display:none;">
                <i class="bi bi-search d-block display-6 mb-3" style="opacity:0.3;"></i>
                <span class="fs-6 d-block">Ningún pedido coincide con los filtros.</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="tabla-pedidos">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">#</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Cliente</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Items</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Total</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Tipo</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Estado</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Hora</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Cambiar estado</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($todosLosPedidos as $pedido)
                        <tr class="fila-pedido border-bottom"
                            style="border-color:#edf2f7 !important;cursor:pointer;"
                            data-id="{{ $pedido->id }}"
                            data-cliente="{{ strtolower($pedido->user->name) }}"
                            data-estado="{{ $pedido->estado }}"
                            data-tipo="{{ $pedido->tipo }}"
                            onclick="verDetalle({{ $pedido->id }})">

                            {{-- # --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <span class="fw-bold text-dark" style="font-size:0.85rem;">#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </td>

                            {{-- Cliente --}}
                            <td class="py-3">
                                <span class="fw-semibold text-dark d-block" style="font-size:0.875rem;">{{ $pedido->user->name }}</span>
                                @if($pedido->notas)
                                    <small class="text-muted d-block" style="font-size:0.72rem;">
                                        <i class="bi bi-sticky text-warning me-1"></i>{{ Str::limit($pedido->notas, 40) }}
                                    </small>
                                @endif
                            </td>

                            {{-- Items (resumen) --}}
                            <td class="py-3">
                                @foreach($pedido->items as $item)
                                    <div class="text-muted" style="font-size:0.78rem;">
                                        {{ $item->cantidad }}x {{ $item->plato->nombre ?? 'Plato eliminado' }}
                                        @if($item->notas)
                                            <span class="text-primary" style="font-size:0.7rem;"> · {{ $item->notas }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>

                            {{-- Total --}}
                            <td class="py-3">
                                <span class="badge text-primary bg-primary bg-opacity-10 border border-primary border-opacity-20 px-2 py-1 fw-bold" style="font-size:0.78rem;">
                                    C$ {{ number_format($pedido->total, 0) }}
                                </span>
                            </td>

                            {{-- Tipo --}}
                            <td class="py-3">
                                @if($pedido->tipo === 'mesa')
                                    <span class="badge text-secondary bg-secondary bg-opacity-10 border border-secondary border-opacity-20 px-2 py-1 fw-semibold" style="font-size:0.72rem;">
                                        🍽 Mesa
                                    </span>
                                @else
                                    <span class="badge text-secondary bg-secondary bg-opacity-10 border border-secondary border-opacity-20 px-2 py-1 fw-semibold" style="font-size:0.72rem;">
                                        🥡 Para llevar
                                    </span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="py-3 text-center">
                                @php $info = $estadosInfo[$pedido->estado] ?? null; @endphp
                                @if($info)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background-color:{{ $info['color'] }}22;color:{{ $info['color'] }};border:1px solid {{ $info['color'] }}44;font-size:0.72rem;">
                                        <span class="rounded-circle" style="width:5px;height:5px;background:{{ $info['color'] }};"></span>
                                        {{ $info['label'] }}
                                    </span>
                                @endif
                            </td>

                            {{-- Hora --}}
                            <td class="py-3">
                                <span class="text-muted small">{{ $pedido->created_at->format('H:i') }}</span>
                                <span class="text-muted d-block" style="font-size:0.7rem;">{{ $pedido->created_at->format('d/m') }}</span>
                            </td>

                            {{-- Cambiar estado --}}
                            <td class="py-3 text-center" onclick="event.stopPropagation()">
                                @if($pedido->estado !== 'entregado' && $pedido->estado !== 'cancelado')
                                <form method="POST" action="{{ route('restaurante.pedidos.estado', $pedido) }}">
                                    @csrf @method('PATCH')
                                    <select name="estado" onchange="this.form.submit()"
                                            class="form-select form-select-sm rounded-pill"
                                            style="font-size:11px;font-weight:700;min-width:130px;">
                                        @foreach($estadosInfo as $key => $est)
                                            @if($key !== 'cancelado' || $pedido->estado === 'pendiente')
                                            <option value="{{ $key }}" {{ $pedido->estado === $key ? 'selected' : '' }}>
                                                {{ $est['label'] }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </form>
                                @else
                                    <span class="text-muted small">
                                        {{ $pedido->estado === 'entregado' ? '✓ Completado' : '✗ Cancelado' }}
                                    </span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-bag d-block display-6 text-muted mb-3"></i>
                <span class="fs-6 d-block mb-2">No hay pedidos registrados aún.</span>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ── Modal detalle pedido ── --}}
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="modal-titulo">Detalle del pedido</h5>
                    <small class="text-muted" id="modal-meta"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3" id="modal-body">
                <div class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Datos de pedidos en JSON para el modal --}}
<script>
window.__pedidos = {
    @foreach($todosLosPedidos as $pedido)
    "{{ $pedido->id }}": {
        id: {{ $pedido->id }},
        cliente: @json($pedido->user->name),
        tipo: @json($pedido->tipo),
        estado: @json($pedido->estado),
        notas: @json($pedido->notas),
        total: {{ $pedido->total }},
        hora: @json($pedido->created_at->format('H:i d/m/Y')),
        items: [
            @foreach($pedido->items as $item)
            {
                nombre: @json($item->plato->nombre ?? 'Plato eliminado'),
                cantidad: {{ $item->cantidad }},
                precio_unitario: {{ $item->precio_unitario }},
                subtotal: {{ $item->subtotal }},
                opciones: @json($item->notas),
            },
            @endforeach
        ],
    },
    @endforeach
};

const estadosInfo = @json(\App\Models\Pedido::ESTADOS);
</script>

<style>
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    @keyframes pulse {
        0%,100% { box-shadow: 0 0 0 2px rgba(34,197,94,0.2); }
        50%      { box-shadow: 0 0 0 5px rgba(34,197,94,0.05); }
    }
    .item-detalle-row { background: #f8fafc; border-radius: 10px; padding: 12px 14px; margin-bottom: 8px; }
    .item-nombre { font-size: 14px; font-weight: 700; color: #1a202c; }
    .item-opciones { font-size: 12px; color: #3b82f6; margin-top: 3px; }
    .item-precio { font-size: 13px; font-weight: 700; color: #2563eb; white-space: nowrap; }
    .item-cantidad-badge { background: #e0e7ff; color: #3730a3; border-radius: 6px; padding: 2px 8px; font-size: 12px; font-weight: 800; }
</style>

@endsection

@section('scripts')
<script>
// ── Auto-refresh cada 30 segundos ──
let segundos = 30;
const indicator = document.getElementById('live-indicator');
setInterval(() => {
    segundos--;
    if (segundos <= 0) { window.location.reload(); }
    if (indicator) indicator.title = `Actualiza en ${segundos}s`;
}, 1000);

// ── Filtros ──
const inputBuscar  = document.getElementById('filtro-buscar');
const selectEstado = document.getElementById('filtro-estado');
const selectTipo   = document.getElementById('filtro-tipo');
const btnLimpiar   = document.getElementById('btn-limpiar');
const contador     = document.getElementById('contador-resultados');
const sinRes       = document.getElementById('sin-resultados');

if (inputBuscar) {
    function filtrar() {
        const buscar = inputBuscar.value.toLowerCase().trim();
        const estado = selectEstado.value;
        const tipo   = selectTipo.value;

        const hayFiltro = buscar !== '' || estado !== '' || tipo !== '';
        btnLimpiar.style.display = hayFiltro ? 'inline-block' : 'none';

        let visibles = 0;
        document.querySelectorAll('.fila-pedido').forEach(fila => {
            const coincideId      = buscar === '' || ('#' + fila.dataset.id.padStart(4,'0')).includes(buscar);
            const coincideCliente = buscar === '' || fila.dataset.cliente.includes(buscar);
            const coincideEstado  = estado === '' || fila.dataset.estado === estado;
            const coincideTipo    = tipo   === '' || fila.dataset.tipo   === tipo;

            const visible = (coincideId || coincideCliente) && coincideEstado && coincideTipo;
            fila.style.display = visible ? '' : 'none';
            if (visible) visibles++;
        });

        if (sinRes) sinRes.style.display = visibles === 0 ? 'block' : 'none';
        contador.textContent = visibles > 0
            ? visibles + ' pedido' + (visibles !== 1 ? 's' : '') + ' encontrado' + (visibles !== 1 ? 's' : '')
            : '';
    }

    inputBuscar.addEventListener('input', filtrar);
    selectEstado.addEventListener('change', filtrar);
    selectTipo.addEventListener('change', filtrar);
    btnLimpiar.addEventListener('click', () => {
        inputBuscar.value  = '';
        selectEstado.value = '';
        selectTipo.value   = '';
        filtrar();
    });
    filtrar();
}

// ── Modal detalle ──
function verDetalle(id) {
    const p = window.__pedidos[id];
    if (!p) return;

    const estadoInfo = estadosInfo[p.estado] || { label: p.estado, color: '#6b7280' };
    const tipoLabel  = p.tipo === 'mesa' ? '🍽 Mesa' : '🥡 Para llevar';

    document.getElementById('modal-titulo').innerHTML =
        `Pedido <span class="text-primary">#${String(p.id).padStart(4,'0')}</span>`;
    document.getElementById('modal-meta').innerHTML =
        `${p.cliente} · ${tipoLabel} · ${p.hora} &nbsp;
         <span class="badge rounded-pill px-2 py-1 fw-semibold"
               style="background:${estadoInfo.color}22;color:${estadoInfo.color};border:1px solid ${estadoInfo.color}44;font-size:11px;">
             ${estadoInfo.label}
         </span>`;

    let itemsHtml = '';
    p.items.forEach(item => {
        itemsHtml += `
        <div class="item-detalle-row d-flex align-items-start justify-content-between gap-3">
            <div style="flex:1;min-width:0;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="item-cantidad-badge">${item.cantidad}x</span>
                    <span class="item-nombre">${item.nombre}</span>
                </div>
                ${item.opciones ? `<div class="item-opciones"><i class="bi bi-sliders me-1"></i>${item.opciones}</div>` : ''}
            </div>
            <div class="item-precio">C$ ${Number(item.subtotal).toLocaleString()}</div>
        </div>`;
    });

    let notasHtml = '';
    if (p.notas) {
        notasHtml = `
        <div class="mt-3 p-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a;">
            <div class="fw-bold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.05em;color:#92400e;">
                <i class="bi bi-sticky me-1"></i> Nota del cliente
            </div>
            <div style="font-size:13px;color:#78350f;">${p.notas}</div>
        </div>`;
    }

    const totalHtml = `
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
            <span class="fw-bold text-dark">Total</span>
            <span class="fw-black text-primary" style="font-size:1.1rem;">C$ ${Number(p.total).toLocaleString()}</span>
        </div>`;

    document.getElementById('modal-body').innerHTML = itemsHtml + notasHtml + totalHtml;

    new bootstrap.Modal(document.getElementById('modalDetalle')).show();
}
</script>
@endsection
