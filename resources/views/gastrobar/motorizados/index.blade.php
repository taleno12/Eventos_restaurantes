@extends('gastrobar.layout')
@section('title', 'Motorizados')

@section('content')
<input type="hidden" id="csrf-token" value="{{ csrf_token() }}">

<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-bicycle text-primary me-2"></i> Motorizados
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                Elegí un pedido y negociá el envío con un motorizado disponible cerca.
            </p>
        </div>
    </div>

    {{-- Selector de pedido --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4" style="background:var(--card-bg) !important;">
        <div class="card-body py-3">
            <label class="form-label fw-semibold mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                <i class="bi bi-bag me-1"></i> Pedido a asignar
            </label>
            <select id="select-pedido" class="form-select rounded-pill">
                <option value="">— Selecciona un pedido con envío pendiente —</option>
                @foreach($pedidosPendientes as $pedido)
                    <option
                        value="{{ $pedido->id }}"
                        data-asignado="{{ $pedido->motorizado_id ? '1' : '0' }}"
                        data-motorizado-nombre="{{ $pedido->motorizado->name ?? '' }}"
                    >
                        #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }} — {{ $pedido->user->name ?? 'Cliente' }} — C$ {{ number_format($pedido->total, 0) }}
                        @if($pedido->motorizado)
                            — Asignado a {{ $pedido->motorizado->name }}
                        @endif
                    </option>
                @endforeach
            </select>
            @if($pedidosPendientes->isEmpty())
                <small class="d-block mt-2" style="color:var(--muted);">
                    No tenés pedidos de envío pendientes de motorizado ahora mismo.
                </small>
            @endif
        </div>
    </div>

    {{-- Aviso de pedido ya asignado (no se puede reasignar) --}}
    <div id="aviso-ya-asignado" class="alert border-0 rounded-3 mb-4" style="display:none;background:rgba(34,197,94,0.12);color:#16a34a;">
        <i class="bi bi-check-circle-fill me-2"></i>
        Este pedido ya tiene un motorizado asignado (<strong id="aviso-motorizado-nombre"></strong>).
        No se puede reasignar desde aquí.
    </div>

    {{-- Lista de motorizados --}}
    <div id="lista-motorizados-wrapper" style="display:none;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0" style="color:var(--text);">Motorizados disponibles cerca</h6>
            <span id="motorizados-count" style="font-size:12px;color:var(--muted);"></span>
        </div>
        <div class="row g-3" id="lista-motorizados"></div>
    </div>

    <div id="estado-vacio" class="text-center py-5" style="color:var(--muted);">
        <i class="bi bi-signpost-split d-block display-6 mb-3" style="opacity:0.3;"></i>
        <span class="fs-6 d-block">Seleccioná un pedido arriba para ver los motorizados disponibles.</span>
    </div>

</div>

{{-- Modal de chat / negociación --}}
<div class="modal fade" id="modalNegociacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background:var(--card-bg) !important;">
            <div class="modal-header border-0 pb-2 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="chat-titulo" style="color:var(--text);">Negociación</h5>
                    <small style="color:var(--muted);" id="chat-subtitulo"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="px-4">
                <div id="chat-tarifa-actual" class="d-none p-2 mb-2 rounded-3 text-center fw-bold"
                     style="background:var(--primary-light);color:var(--primary);font-size:14px;"></div>
            </div>

            <div class="modal-body px-4 py-2" id="chat-mensajes"
                 style="max-height:320px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;">
                <div class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                </div>
            </div>

            <div class="px-4 pb-2 pt-2 border-top" style="border-color:var(--card-border) !important;">
                <div class="d-flex gap-2 mb-2">
                    <input type="number" step="0.01" min="0" id="chat-tarifa-input"
                           class="form-control form-control-sm rounded-pill" placeholder="Proponer tarifa C$ (opcional)">
                    <button id="btn-aceptar-tarifa" class="btn btn-sm rounded-pill px-3"
                            style="background:#22c55e;color:white;font-weight:700;white-space:nowrap;">
                        <i class="bi bi-check-circle me-1"></i> Aceptar
                    </button>
                </div>
                <form id="form-mensaje" class="d-flex gap-2">
                    <input type="text" id="chat-mensaje-input" class="form-control rounded-pill"
                           placeholder="Escribí un mensaje..." maxlength="500">
                    <button type="submit" class="btn rounded-pill px-3" style="background:var(--primary);color:white;">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.motorizado-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 14px;
    padding: 16px;
    transition: all 0.2s ease;
}
.motorizado-card:hover {
    box-shadow: 0 4px 16px var(--shadow-lg);
    transform: translateY(-2px);
}
.motorizado-avatar {
    width: 46px; height: 46px; border-radius: 12px;
    background: var(--primary-light); color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.chat-msg {
    max-width: 78%;
    padding: 8px 13px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.4;
}
.chat-msg-mio {
    align-self: flex-end;
    background: var(--primary);
    color: white;
    border-bottom-right-radius: 4px;
}
.chat-msg-otro {
    align-self: flex-start;
    background: var(--badge-gray-bg);
    color: var(--text);
    border-bottom-left-radius: 4px;
}
.chat-msg-tarifa {
    font-size: 11px;
    font-weight: 800;
    display: block;
    margin-top: 3px;
    opacity: 0.9;
}
</style>

@endsection

@section('scripts')
<script>
const CSRF = document.getElementById('csrf-token').value;
const MI_USER_ID = {{ auth()->id() }};

const selectPedido      = document.getElementById('select-pedido');
const wrapperLista       = document.getElementById('lista-motorizados-wrapper');
const listaMotorizados   = document.getElementById('lista-motorizados');
const motorizadosCount   = document.getElementById('motorizados-count');
const estadoVacio        = document.getElementById('estado-vacio');
const avisoYaAsignado    = document.getElementById('aviso-ya-asignado');
const avisoMotorizadoNombre = document.getElementById('aviso-motorizado-nombre');

let negociacionActual = null;
let pollingInterval    = null;

// ── 1. Al elegir un pedido, cargar motorizados disponibles cerca ──
selectPedido.addEventListener('change', async () => {
    const pedidoId = selectPedido.value;

    if (!pedidoId) {
        wrapperLista.style.display = 'none';
        estadoVacio.style.display = 'block';
        avisoYaAsignado.style.display = 'none';
        return;
    }

    const opcionSeleccionada = selectPedido.options[selectPedido.selectedIndex];
    const yaAsignado = opcionSeleccionada.dataset.asignado === '1';

    // Si el pedido ya tiene motorizado, no mostramos la lista para negociar:
    // solo el aviso informativo, para que no se pueda reasignar por error.
    if (yaAsignado) {
        wrapperLista.style.display = 'none';
        estadoVacio.style.display = 'none';
        avisoYaAsignado.style.display = 'block';
        avisoMotorizadoNombre.textContent = opcionSeleccionada.dataset.motorizadoNombre || 'motorizado';
        return;
    }

    avisoYaAsignado.style.display = 'none';
    listaMotorizados.innerHTML = `<div class="text-center py-4 w-100"><div class="spinner-border spinner-border-sm text-primary"></div></div>`;
    wrapperLista.style.display = 'block';
    estadoVacio.style.display = 'none';

    try {
        const res = await fetch('/motorizados/disponibles-cerca?radio=8');
        const data = await res.json();

        motorizadosCount.textContent = data.motorizados.length + ' encontrado(s)';
        listaMotorizados.innerHTML = '';

        if (data.motorizados.length === 0) {
            listaMotorizados.innerHTML = `<div class="text-center py-4 w-100" style="color:var(--muted);">
                No hay motorizados disponibles cerca en este momento.
            </div>`;
            return;
        }

        data.motorizados.forEach(m => {
            const distancia = m.distancia_km ? Number(m.distancia_km).toFixed(2) + ' km' : '';
            const card = document.createElement('div');
            card.className = 'col-12 col-md-6 col-lg-4';
            card.innerHTML = `
                <div class="motorizado-card d-flex align-items-center gap-3">
                    <div class="motorizado-avatar"><i class="bi bi-bicycle"></i></div>
                    <div style="flex:1;min-width:0;">
                        <div class="fw-bold" style="font-size:14px;color:var(--text);">${m.name}</div>
                        <div style="font-size:12px;color:var(--muted);">${m.vehiculo ?? ''} ${m.placa ? '· ' + m.placa : ''}</div>
                        <div style="font-size:11px;color:var(--primary);font-weight:700;">${distancia}</div>
                    </div>
                    <button class="btn btn-sm rounded-pill px-3 btn-negociar"
                            data-motorizado-id="${m.id}" data-motorizado-nombre="${m.name}"
                            style="background:var(--primary);color:white;font-weight:700;white-space:nowrap;">
                        <i class="bi bi-chat-dots-fill me-1"></i> Negociar
                    </button>
                </div>`;
            listaMotorizados.appendChild(card);
        });
    } catch (err) {
        listaMotorizados.innerHTML = `<div class="text-center py-4 w-100" style="color:var(--muted);">Error al cargar motorizados.</div>`;
    }
});

// ── 2. Al hacer clic en "Negociar", crear/abrir la negociación ──
listaMotorizados.addEventListener('click', async (e) => {
    const btn = e.target.closest('.btn-negociar');
    if (!btn) return;

    const pedidoId     = selectPedido.value;
    const motorizadoId = btn.dataset.motorizadoId;
    const motorizadoNombre = btn.dataset.motorizadoNombre;

    document.getElementById('chat-titulo').textContent = 'Negociación con ' + motorizadoNombre;
    document.getElementById('chat-subtitulo').textContent =
        'Pedido #' + String(pedidoId).padStart(4, '0');
    document.getElementById('chat-mensajes').innerHTML =
        `<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>`;

    const modal = new bootstrap.Modal(document.getElementById('modalNegociacion'));
    modal.show();

    const formData = new FormData();
    formData.append('_token', CSRF);
    formData.append('pedido_tipo', 'gastrobar');
    formData.append('pedido_id', pedidoId);
    formData.append('motorizado_id', motorizadoId);

    try {
        const res = await fetch('{{ route("gastrobar.negociaciones.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await res.json();

        // El backend puede rechazar si el pedido ya fue asignado a otro motorizado
        // mientras tanto (carrera entre pestañas, refresh atrasado, etc.)
        if (!res.ok) {
            modal.hide();
            alert(data.message || 'No se pudo iniciar la negociación.');
            window.location.reload();
            return;
        }

        negociacionActual = data.negociacion.id;
        renderChat(data.negociacion);
        iniciarPolling();
    } catch (err) {
        document.getElementById('chat-mensajes').innerHTML =
            `<div class="text-center py-4" style="color:var(--muted);">Error al iniciar la negociación.</div>`;
    }
});

// ── 3. Polling cada 4s mientras el modal esté abierto ──
function iniciarPolling() {
    detenerPolling();
    pollingInterval = setInterval(async () => {
        if (!negociacionActual) return;
        try {
            const res = await fetch(`/mi-gastrobar/negociaciones/${negociacionActual}`);
            const data = await res.json();
            renderChat(data.negociacion);
        } catch (err) { /* silencioso */ }
    }, 4000);
}

function detenerPolling() {
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = null;
}

document.getElementById('modalNegociacion').addEventListener('hidden.bs.modal', () => {
    detenerPolling();
    negociacionActual = null;
});

// ── 4. Renderizar mensajes + estado de tarifa ──
function renderChat(negociacion) {
    const cont = document.getElementById('chat-mensajes');
    cont.innerHTML = '';

    negociacion.mensajes.forEach(m => {
        const esMio = m.user_id === MI_USER_ID;
        const div = document.createElement('div');
        div.className = 'chat-msg ' + (esMio ? 'chat-msg-mio' : 'chat-msg-otro');
        let html = m.mensaje ? m.mensaje : '';
        if (m.tarifa_propuesta) {
            html += `<span class="chat-msg-tarifa">💰 Propuso C$ ${Number(m.tarifa_propuesta).toFixed(2)}</span>`;
        }
        div.innerHTML = html;
        cont.appendChild(div);
    });
    cont.scrollTop = cont.scrollHeight;

    const tarifaBox = document.getElementById('chat-tarifa-actual');
    const btnAceptar = document.getElementById('btn-aceptar-tarifa');

    if (negociacion.estado === 'aceptado') {
        tarifaBox.classList.remove('d-none');
        tarifaBox.style.background = 'rgba(34,197,94,0.12)';
        tarifaBox.style.color = '#22c55e';
        tarifaBox.innerHTML = `✅ Tarifa acordada: C$ ${Number(negociacion.tarifa_acordada).toFixed(2)}`;
        btnAceptar.disabled = true;
        btnAceptar.innerHTML = 'Acordado';
    } else {
        const tarifaMotorizado = negociacion.tarifa_propuesta_motorizado;
        const tarifaDueno = negociacion.tarifa_propuesta_dueno;

        if (tarifaMotorizado || tarifaDueno) {
            tarifaBox.classList.remove('d-none');
            tarifaBox.style.background = 'var(--primary-light)';
            tarifaBox.style.color = 'var(--primary)';
            tarifaBox.innerHTML = `Última propuesta: C$ ${Number(tarifaMotorizado ?? tarifaDueno).toFixed(2)}`;

            // ✅ FIX: antes, cuando el dueño aceptaba pero el motorizado todavía
            // no había aceptado, la pantalla se quedaba EXACTAMENTE igual que
            // antes de dar clic (mismo texto "Última propuesta..."), dando la
            // sensación de que el botón "no respondía". En realidad sí se
            // guardaba en el backend (aceptado_dueno = true), solo que no había
            // ningún indicio visual del cambio. Ahora se muestra el estado real.
            if (negociacion.aceptado_dueno && !negociacion.aceptado_motorizado) {
                tarifaBox.innerHTML += `<br><span style="font-size:11px;font-weight:600;">
                    ✅ Vos ya aceptaste. Esperando confirmación del motorizado...
                </span>`;
                btnAceptar.disabled = true;
                btnAceptar.innerHTML = 'Esperando...';
            } else if (!negociacion.aceptado_dueno && negociacion.aceptado_motorizado) {
                tarifaBox.innerHTML += `<br><span style="font-size:11px;font-weight:600;">
                    🏍️ El motorizado ya aceptó. Confirmá vos para cerrar el trato.
                </span>`;
                btnAceptar.disabled = false;
                btnAceptar.innerHTML = '<i class="bi bi-check-circle me-1"></i> Aceptar';
            } else {
                btnAceptar.disabled = false;
                btnAceptar.innerHTML = '<i class="bi bi-check-circle me-1"></i> Aceptar';
            }
        } else {
            tarifaBox.classList.add('d-none');
            btnAceptar.disabled = false;
            btnAceptar.innerHTML = '<i class="bi bi-check-circle me-1"></i> Aceptar';
        }
    }
}

// ── 5. Enviar mensaje (con o sin propuesta de tarifa) ──
document.getElementById('form-mensaje').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!negociacionActual) return;

    const input = document.getElementById('chat-mensaje-input');
    const tarifaInput = document.getElementById('chat-tarifa-input');
    const mensaje = input.value.trim();
    const tarifa  = tarifaInput.value.trim();

    if (!mensaje && !tarifa) return;

    const formData = new FormData();
    formData.append('_token', CSRF);
    if (mensaje) formData.append('mensaje', mensaje);
    if (tarifa)  formData.append('tarifa_propuesta', tarifa);

    input.value = '';
    tarifaInput.value = '';

    try {
        await fetch(`/mi-gastrobar/negociaciones/${negociacionActual}/mensajes`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        const res = await fetch(`/mi-gastrobar/negociaciones/${negociacionActual}`);
        const data = await res.json();
        renderChat(data.negociacion);
    } catch (err) {
        alert('No se pudo enviar el mensaje. Revisá tu conexión e intentá de nuevo.');
    }
});

// ── 6. Aceptar la tarifa propuesta actual ──
document.getElementById('btn-aceptar-tarifa').addEventListener('click', async () => {
    if (!negociacionActual) return;

    const btnAceptar = document.getElementById('btn-aceptar-tarifa');
    btnAceptar.disabled = true;

    const formData = new FormData();
    formData.append('_token', CSRF);
    formData.append('_method', 'PATCH');

    try {
        const res = await fetch(`/mi-gastrobar/negociaciones/${negociacionActual}/aceptar`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!res.ok) {
            const errData = await res.json().catch(() => null);
            alert(errData?.message || 'No se pudo aceptar la tarifa (error ' + res.status + ').');
            btnAceptar.disabled = false;
            return;
        }

        const data = await res.json();
        renderChat(data.negociacion);

        if (data.cerrada) {
            setTimeout(() => {
                alert('¡Motorizado asignado! El pedido ya tiene el costo de envío confirmado.');
                window.location.reload();
            }, 800);
        }
    } catch (err) {
        alert('Error de conexión al intentar aceptar la tarifa.');
        btnAceptar.disabled = false;
    }
});
</script>
@endsection
