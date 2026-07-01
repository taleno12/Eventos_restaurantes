@extends('gastrobar.layout')
@section('title', 'Pedidos')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-bag text-primary me-2"></i> Pedidos
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Gestión en tiempo real de {{ $gastrobar->nombre }}
            </p>
        </div>
        <div id="live-indicator" class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill"
            style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);font-size:11px;font-weight:700;color:#22c55e;">
            <span
                style="width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block;animation:pulse 2s infinite;"></span>
            En vivo
        </div>
    </div>

    {{-- Métricas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                        style="width:50px;height:50px;background:rgba(245,158,11,0.12);color:#f59e0b;flex-shrink:0;">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0"
                            style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Pendientes</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);" id="metrica-pendientes">{{
                            $pendientes }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                        style="width:50px;height:50px;background:rgba(34,197,94,0.12);color:#22c55e;flex-shrink:0;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0"
                            style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Pedidos hoy</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);" id="metrica-total-hoy">{{
                            $totalHoy }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                        style="width:50px;height:50px;background:rgba(249,115,22,0.12);color:#f97316;flex-shrink:0;">
                        <i class="bi bi-coin"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0"
                            style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Ingresos hoy</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);" id="metrica-ingreso-hoy">
                            C$ {{ number_format($ingresoHoy, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4" style="background:var(--card-bg) !important;">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1"
                        style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                        <i class="bi bi-search me-1"></i> Buscar
                    </label>
                    <input type="text" id="filtro-buscar" class="form-control form-control-sm rounded-pill"
                        placeholder="# pedido o cliente...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1"
                        style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                        <i class="bi bi-funnel me-1"></i> Estado
                    </label>
                    <select id="filtro-estado" class="form-select form-select-sm rounded-pill">
                        <option value="">Todos (activos)</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="en_preparacion">En preparación</option>
                        <option value="listo">Listo</option>
                        <option value="entregado">Entregado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1"
                        style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                        <i class="bi bi-tag me-1"></i> Tipo
                    </label>
                    <select id="filtro-tipo" class="form-select form-select-sm rounded-pill">
                        <option value="">Todos</option>
                        <option value="envio">Envío</option>
                        <option value="retiro">Retiro en el local</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <button id="btn-limpiar" class="btn btn-link btn-sm p-0"
                        style="font-size:11px;display:none;color:var(--muted);">
                        <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <span id="contador-resultados" style="font-size:11px;color:var(--muted);"></span>
            </div>
        </div>
    </div>

    {{-- Tabla de pedidos --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="background:var(--card-bg) !important;">
        <div class="card-body p-0">

            @php
            $estadosInfo = \App\Models\PedidoGastrobar::ESTADOS;
            $todosLosPedidos = collect();
            foreach($pedidos as $grupo) {
            $todosLosPedidos = $todosLosPedidos->merge($grupo);
            }
            $todosLosPedidos = $todosLosPedidos->sortByDesc('created_at');
            @endphp

            @if($todosLosPedidos->count() > 0)
            <div id="sin-resultados" class="text-center py-5" style="display:none;color:var(--muted);">
                <i class="bi bi-search d-block display-6 mb-3" style="opacity:0.3;"></i>
                <span class="fs-6 d-block" style="color:var(--text);">Ningún pedido coincide con los filtros.</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="tabla-pedidos">
                    <thead class="border-bottom" style="background:var(--table-header) !important;">
                        <tr>
                            <th class="ps-4 py-3 border-0"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                #</th>
                            <th class="py-3 border-0"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                Cliente</th>
                            <th class="py-3 border-0"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                Items</th>
                            <th class="py-3 border-0"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                Total</th>
                            <th class="py-3 border-0"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                Tipo</th>
                            <th class="py-3 border-0 text-center"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                Estado</th>
                            <th class="py-3 border-0"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                Hora</th>
                            <th class="py-3 border-0 text-center"
                                style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">
                                Cambiar estado</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($todosLosPedidos as $pedido)
                        @php
                        $esFinal = in_array($pedido->estado, ['entregado', 'cancelado']);
                        @endphp
                        <tr class="fila-pedido border-bottom {{ $esFinal ? 'fila-entregado' : '' }}"
                            style="border-color:var(--card-border) !important;cursor:pointer; {{ $esFinal ? 'opacity:0.6;' : '' }}"
                            data-id="{{ $pedido->id }}" data-cliente="{{ strtolower($pedido->user->name) }}"
                            data-estado="{{ $pedido->estado }}" data-tipo="{{ $pedido->tipo }}"
                            data-total="{{ $pedido->total }}" onclick="verDetalle({{ $pedido->id }})">

                            {{-- # --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <span class="fw-bold" style="font-size:0.85rem;color:var(--text) !important;">#{{
                                        str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </td>

                            {{-- Cliente --}}
                            <td class="py-3">
                                <span class="fw-semibold d-block"
                                    style="font-size:0.875rem;color:var(--text) !important;">{{ $pedido->user->name
                                    }}</span>
                                @if($pedido->notas)
                                <small class="d-block" style="font-size:0.72rem;color:var(--muted) !important;">
                                    <i class="bi bi-sticky text-warning me-1"></i>{{ Str::limit($pedido->notas, 40) }}
                                </small>
                                @endif
                            </td>

                            {{-- Items --}}
                            <td class="py-3">
                                @foreach($pedido->items as $item)
                                <div style="font-size:0.78rem;color:var(--muted) !important;">
                                    {{ $item->cantidad }}x {{ $item->plato->nombre ?? 'Plato eliminado' }}
                                    @if($item->notas)
                                    <span style="font-size:0.7rem;color:var(--primary);"> · {{ $item->notas }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </td>

                            {{-- Total --}}
                            <td class="py-3">
                                <span class="badge px-2 py-1 fw-bold"
                                    style="font-size:0.78rem;background:var(--primary-light) !important;color:var(--primary) !important;border:1px solid var(--primary-border) !important;">
                                    C$ {{ number_format($pedido->total, 0) }}
                                </span>
                            </td>

                            {{-- Tipo --}}
                            <td class="py-3">
                                @if($pedido->tipo === 'envio')
                                <span class="badge px-2 py-1 fw-semibold"
                                    style="font-size:0.72rem;background:var(--badge-gray-bg) !important;color:var(--badge-gray-text) !important;border:1px solid var(--card-border) !important;">🛵
                                    Envío</span>
                                @else
                                <span class="badge px-2 py-1 fw-semibold"
                                    style="font-size:0.72rem;background:var(--badge-gray-bg) !important;color:var(--badge-gray-text) !important;border:1px solid var(--card-border) !important;">🏬
                                    Retiro en el local</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="py-3 text-center">
                                @php $info = $estadosInfo[$pedido->estado] ?? null; @endphp
                                @if($info)
                                <span
                                    class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                    style="background-color:{{ $info['color'] }}22;color:{{ $info['color'] }};border:1px solid {{ $info['color'] }}44;font-size:0.72rem;">
                                    <span class="rounded-circle"
                                        style="width:5px;height:5px;background:{{ $info['color'] }};"></span>
                                    {{ $info['label'] }}
                                </span>
                                @endif
                            </td>

                            {{-- Hora --}}
                            <td class="py-3">
                                <span class="small" style="color:var(--muted) !important;">{{
                                    $pedido->created_at->format('H:i') }}</span>
                                <span class="d-block" style="font-size:0.7rem;color:var(--muted) !important;">{{
                                    $pedido->created_at->format('d/m') }}</span>
                            </td>

                            {{-- Cambiar estado / Eliminar --}}
                            <td class="py-3 text-center" onclick="event.stopPropagation()">
                                @if(!$esFinal)
                                <form method="POST" action="{{ route('gastrobar.pedidos.estado', $pedido) }}"
                                    class="form-estado">
                                    @csrf @method('PATCH')
                                    <select name="estado" class="form-select form-select-sm rounded-pill select-estado"
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
                                <form method="POST" action="{{ route('gastrobar.pedidos.destroy', $pedido) }}"
                                    class="form-eliminar-pedido">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm rounded-pill px-3"
                                        style="font-size:11px;font-weight:700;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
                                        <i class="bi bi-trash3 me-1"></i> Eliminar
                                    </button>
                                </form>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @else
            <div class="text-center py-5" style="color:var(--muted);">
                <i class="bi bi-bag d-block display-6 mb-3" style="opacity:0.3;"></i>
                <span class="fs-6 d-block mb-2" style="color:var(--text);">No hay pedidos registrados aún.</span>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Modal detalle pedido --}}
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden"
            style="background:var(--card-bg) !important;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="modal-titulo" style="color:var(--text);">Detalle del pedido
                    </h5>
                    <small style="color:var(--muted);" id="modal-meta"></small>
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

{{-- Datos de pedidos en JSON --}}
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

    window.estadosInfo = @json(\App\Models\PedidoGastrobar::ESTADOS);
</script>

<style>
    /* Forzar fondo oscuro en tabla Bootstrap */
    #tabla-pedidos.table {
        --bs-table-bg: transparent !important;
        --bs-table-color: var(--text) !important;
        --bs-table-border-color: var(--card-border) !important;
    }

    #tabla-pedidos thead th {
        background-color: var(--table-header) !important;
        color: var(--muted) !important;
        border-bottom-color: var(--card-border) !important;
    }

    #tabla-pedidos tbody td {
        color: var(--text) !important;
        background-color: var(--card-bg) !important;
        border-bottom-color: var(--card-border) !important;
    }

    #tabla-pedidos tbody tr:hover td {
        background-color: var(--table-hover) !important;
    }

    .fila-pedido {
        background-color: var(--card-bg) !important;
    }

    .fila-entregado {
        background-color: rgba(34, 197, 94, 0.05) !important;
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
        }

        50% {
            box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.05);
        }
    }

    .item-detalle-row {
        background: var(--table-hover);
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 8px;
    }

    .item-nombre {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .item-opciones {
        font-size: 12px;
        color: var(--primary);
        margin-top: 3px;
    }

    .item-precio {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary);
        white-space: nowrap;
    }

    .item-cantidad-badge {
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 12px;
        font-weight: 800;
    }
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
    const inputBuscar = document.getElementById('filtro-buscar');
    const selectEstado = document.getElementById('filtro-estado');
    const selectTipo = document.getElementById('filtro-tipo');
    const btnLimpiar = document.getElementById('btn-limpiar');
    const contador = document.getElementById('contador-resultados');
    const sinRes = document.getElementById('sin-resultados');

    if (inputBuscar) {
        function filtrar() {
            const buscar = inputBuscar.value.toLowerCase().trim();
            const estado = selectEstado.value;
            const tipo = selectTipo.value;

            const hayFiltro = buscar !== '' || estado !== '' || tipo !== '';
            btnLimpiar.style.display = hayFiltro ? 'inline-block' : 'none';

            let visibles = 0;
            document.querySelectorAll('.fila-pedido').forEach(fila => {
                // Por defecto, ocultar entregados/cancelados a menos que se filtre explícitamente
                if ((fila.dataset.estado === 'entregado' || fila.dataset.estado === 'cancelado') && estado !== fila.dataset.estado) {
                    fila.style.display = 'none';
                    return;
                }

                const coincideId = buscar === '' || ('#' + fila.dataset.id.padStart(4, '0')).includes(buscar);
                const coincideCliente = buscar === '' || fila.dataset.cliente.includes(buscar);
                const coincideEstado = estado === '' || fila.dataset.estado === estado;
                const coincideTipo = tipo === '' || fila.dataset.tipo === tipo;

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
            inputBuscar.value = '';
            selectEstado.value = '';
            selectTipo.value = '';
            filtrar();
        });
        filtrar();
    }

    // ── CAMBIAR ESTADO ──
    document.querySelectorAll('.select-estado').forEach(select => {
        select.addEventListener('change', function (e) {
            e.preventDefault();

            const fila = this.closest('.fila-pedido');
            const form = this.closest('.form-estado');
            const nuevoEstado = this.value;
            const estadoAnterior = fila.dataset.estado;
            const formData = new FormData(form);

            this.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    this.disabled = false;

                    if (!data || !data.success) {
                        console.error('No se pudo actualizar el estado del pedido.');
                        this.value = estadoAnterior;
                        return;
                    }

                    fila.dataset.estado = nuevoEstado;

                    // Actualizar el badge visual de "Estado"
                    const info = window.estadosInfo[nuevoEstado];
                    if (info) {
                        const celdaEstado = fila.children[5];
                        const badgeEstado = celdaEstado ? celdaEstado.querySelector('.badge') : null;
                        if (badgeEstado) {
                            badgeEstado.style.backgroundColor = info.color + '22';
                            badgeEstado.style.color = info.color;
                            badgeEstado.style.borderColor = info.color + '44';
                            badgeEstado.innerHTML =
                                `<span class="rounded-circle" style="width:5px;height:5px;background:${info.color};"></span> ${info.label}`;
                        }
                    }

                    if (estadoAnterior === 'pendiente' && nuevoEstado !== 'pendiente') {
                        const badgePendientes = document.getElementById('metrica-pendientes');
                        if (badgePendientes) {
                            const actual = parseInt(badgePendientes.textContent) || 0;
                            if (actual > 0) badgePendientes.textContent = actual - 1;
                        }
                    }

                    // Si pasó a "entregado" o "cancelado": reemplazar el select por el botón Eliminar.
                    // La fila se queda visible (con opacidad reducida) hasta que el propietario le dé
                    // clic a "Eliminar", o hasta que el job de los 30 días la borre solo.
                    if (nuevoEstado === 'entregado' || nuevoEstado === 'cancelado') {
                        const celdaCambiar = fila.children[7];
                        if (celdaCambiar) {
                            celdaCambiar.innerHTML = `
                                <form method="POST" action="/mi-gastrobar/pedidos/${fila.dataset.id}" class="form-eliminar-pedido">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]')?.content || form.querySelector('[name=_token]').value}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm rounded-pill px-3"
                                        style="font-size:11px;font-weight:700;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
                                        <i class="bi bi-trash3 me-1"></i> Eliminar
                                    </button>
                                </form>
                            `;
                        }

                        fila.style.opacity = '0.6';
                        fila.classList.add('fila-entregado');
                    }

                    if (window.__pedidos && window.__pedidos[fila.dataset.id]) {
                        window.__pedidos[fila.dataset.id].estado = nuevoEstado;
                    }
                })
                .catch(err => {
                    this.disabled = false;
                    this.value = estadoAnterior;
                    console.error('Error al actualizar estado:', err);
                    alert('Error al actualizar el estado. Inténtalo de nuevo.');
                });
        });
    });

    // ── ELIMINAR PEDIDO (delegado, cubre botones renderizados dinámicamente) ──
    document.addEventListener('submit', function (e) {
        if (!e.target.classList.contains('form-eliminar-pedido')) return;
        e.preventDefault();

        if (!confirm('¿Eliminar este pedido de forma permanente?')) return;

        const form = e.target;
        const fila = form.closest('.fila-pedido');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(data => {
                if (data && data.success && fila) {
                    fila.style.transition = 'all 0.4s ease';
                    fila.style.opacity = '0';
                    setTimeout(() => fila.remove(), 400);
                } else {
                    alert(data?.message || 'No se pudo eliminar el pedido.');
                }
            })
            .catch(() => alert('Error al eliminar el pedido. Inténtalo de nuevo.'));
    });

    // ── Modal detalle ──
    function verDetalle(id) {
        const p = window.__pedidos[id];
        if (!p) return;

        const estadoInfo = window.estadosInfo[p.estado] || { label: p.estado, color: '#6b7280' };
        const tipoLabel = p.tipo === 'envio' ? '🛵 Envío' : '🏬 Retiro en el local';

        document.getElementById('modal-titulo').innerHTML =
            `Pedido <span style="color:var(--primary);">#${String(p.id).padStart(4, '0')}</span>`;
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
        <div class="mt-3 p-3 rounded-3" style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);">
            <div class="fw-bold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.05em;color:#f59e0b;">
                <i class="bi bi-sticky me-1"></i> Nota del cliente
            </div>
            <div style="font-size:13px;color:var(--text);">${p.notas}</div>
        </div>`;
        }

        const totalHtml = `
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color:var(--card-border) !important;">
            <span class="fw-bold" style="color:var(--text);">Total</span>
            <span class="fw-black" style="font-size:1.1rem;color:var(--primary);">C$ ${Number(p.total).toLocaleString()}</span>
        </div>`;

        document.getElementById('modal-body').innerHTML = itemsHtml + notasHtml + totalHtml;

        new bootstrap.Modal(document.getElementById('modalDetalle')).show();
    }
</script>
@endsection
