@extends('gastrobar.layout')
@section('title', 'Menú')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-egg-fried text-primary me-2"></i> Menú
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Platos de {{ $gastrobar->nombre }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('gastrobar.categorias.index') }}"
               class="btn btn-light border fw-semibold rounded-pill px-3"
               style="font-size:13px;background:var(--card-bg);color:var(--text);border-color:var(--card-border) !important;">
                <i class="bi bi-tags me-1 text-primary"></i> Categorías
            </a>
            <a href="{{ route('gastrobar.platos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Plato
            </a>
        </div>
    </div>

    @if(!$platos->isEmpty() || (isset($categorias) && $categorias->isNotEmpty()))
    <div class="card border-0 shadow-sm rounded-3 mb-4" style="background:var(--card-bg) !important;">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                        <i class="bi bi-search me-1"></i> Buscar
                    </label>
                    <input type="text" id="filtro-nombre" class="form-control form-control-sm rounded-pill"
                           placeholder="Nombre del plato...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                        <i class="bi bi-tag me-1"></i> Categoría
                    </label>
                    <select id="filtro-categoria" class="form-select form-select-sm rounded-pill">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                        @endforeach
                        @if($platos->has('Sin categoria'))
                            <option value="Sin categoria">Sin categoría</option>
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                        <i class="bi bi-currency-dollar me-1"></i> Precio máx. (C$)
                    </label>
                    <input type="number" id="filtro-precio" class="form-control form-control-sm rounded-pill"
                           placeholder="Ej: 150" min="0" step="1">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);">
                        <i class="bi bi-toggle-on me-1"></i> Estado
                    </label>
                    <select id="filtro-estado" class="form-select form-select-sm rounded-pill">
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>
            <div class="mt-2 d-flex align-items-center justify-content-between">
                <span id="contador-resultados" style="font-size:11px;color:var(--muted);"></span>
                <button id="btn-limpiar" class="btn btn-link btn-sm p-0" style="font-size:11px;display:none;color:var(--muted);">
                    <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($platos->isEmpty())
    <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
        <div class="card-body text-center py-5" style="color:var(--muted);">
            <i class="bi bi-egg-fried d-block display-6 mb-3" style="opacity:0.4;"></i>
            <span class="fs-6 d-block mb-2" style="color:var(--text);">No tienes platos en tu menú aún.</span>
            <a href="{{ route('gastrobar.platos.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Añadir primer plato
            </a>
        </div>
    </div>

    @else

    <div id="sin-resultados" class="card border-0 shadow-sm rounded-3" style="display:none;background:var(--card-bg) !important;">
        <div class="card-body text-center py-5" style="color:var(--muted);">
            <i class="bi bi-search d-block display-6 mb-3" style="opacity:0.4;"></i>
            <span class="fs-6 d-block mb-2" style="color:var(--text);">Ningún plato coincide con los filtros.</span>
            <button id="btn-limpiar-2" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                <i class="bi bi-x-circle me-1"></i> Limpiar filtros
            </button>
        </div>
    </div>

    @foreach($platos as $categoria => $items)
    <div class="mb-4 grupo-categoria" data-categoria="{{ $categoria }}">
        <div class="d-flex align-items-center gap-3 mb-3 header-categoria">
            <span class="fw-black text-primary text-uppercase" style="font-size:11px;letter-spacing:0.18em;">
                {{ $categoria ?: 'Sin categoría' }}
            </span>
            <div class="flex-grow-1" style="height:1px;background:var(--card-border);"></div>
            <span class="contador-cat" style="font-size:11px;color:var(--muted);">
                {{ $items->count() }} plato{{ $items->count() !== 1 ? 's' : '' }}
            </span>
        </div>

        <div class="row g-3">
            @foreach($items as $plato)
            <div class="col-12 col-sm-6 col-xl-4 item-plato"
                 data-nombre="{{ strtolower($plato->nombre) }}"
                 data-categoria="{{ $categoria }}"
                 data-precio="{{ $plato->precio }}"
                 data-activo="{{ $plato->activo ? '1' : '0' }}">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 card-plato" style="background:var(--card-bg) !important;">
                    <div class="position-relative" style="aspect-ratio:16/9;overflow:hidden;background:var(--hover-bg);">
                        @if($plato->imagen)
                            <img src="{{ asset('storage/'.$plato->imagen) }}" class="w-100 h-100" style="object-fit:cover;">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-egg-fried" style="font-size:32px;color:var(--muted);"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 p-2">
                            @if($plato->activo)
                                <span class="badge rounded-pill fw-semibold d-inline-flex align-items-center gap-1"
                                      style="background-color:rgba(22,163,74,0.1);color:#22c55e;border:1px solid rgba(22,163,74,0.2);font-size:0.7rem;">
                                    <span class="rounded-circle" style="width:5px;height:5px;background:#22c55e;"></span> Activo
                                </span>
                            @else
                                <span class="badge rounded-pill fw-semibold d-inline-flex align-items-center gap-1"
                                      style="font-size:0.7rem;background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.2);">
                                    <span class="rounded-circle" style="width:5px;height:5px;background:#ef4444;"></span> Inactivo
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="fw-bold" style="font-size:14px;color:var(--text);">{{ $plato->nombre }}</span>
                            <span class="fw-black text-nowrap" style="font-size:15px;color:var(--primary);">
                                C$ {{ number_format($plato->precio, 0) }}
                            </span>
                        </div>
                        @if($plato->descripcion)
                            <p class="mb-0" style="font-size:12px;line-height:1.5;color:var(--muted);">
                                {{ Str::limit($plato->descripcion, 70) }}
                            </p>
                        @endif
                    </div>

                    <div class="card-footer border-top d-flex gap-2 px-3 py-2" style="background:var(--card-bg) !important;border-color:var(--card-border) !important;">
                        <form method="POST" action="{{ route('gastrobar.platos.toggle', $plato) }}" class="flex-grow-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm w-100 fw-semibold" style="font-size:11px;background:var(--hover-bg);color:var(--text);border:1px solid var(--card-border);">
                                @if($plato->activo)
                                    <i class="bi bi-eye-slash me-1"></i> Ocultar
                                @else
                                    <i class="bi bi-eye me-1"></i> Activar
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('gastrobar.platos.edit', $plato) }}"
                           class="btn btn-sm px-3 action-icon-edit" title="Editar" style="background:var(--hover-bg);color:var(--muted);border:1px solid var(--card-border);">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="submit" form="form-delete-plato-{{ $plato->id }}"
                                class="btn btn-sm px-3 action-icon-delete" title="Eliminar" style="background:var(--hover-bg);color:var(--muted);border:1px solid var(--card-border);">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                <form id="form-delete-plato-{{ $plato->id }}"
                      method="POST"
                      action="{{ route('gastrobar.platos.destroy', $plato) }}"
                      onsubmit="return confirm('¿Eliminar este plato del menú?')">
                    @csrf @method('DELETE')
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif

</div>

<style>
    .card-plato { transition: box-shadow 0.2s, transform 0.2s; }
    .card-plato:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important; transform: translateY(-2px); }
    .action-icon-edit:hover   { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
</style>

@endsection

@section('scripts')
<script>
(function () {
    const inputNombre   = document.getElementById('filtro-nombre');
    const selectCat     = document.getElementById('filtro-categoria');
    const inputPrecio   = document.getElementById('filtro-precio');
    const selectEstado  = document.getElementById('filtro-estado');
    const sinResultados = document.getElementById('sin-resultados');
    const btnLimpiar    = document.getElementById('btn-limpiar');
    const btnLimpiar2   = document.getElementById('btn-limpiar-2');
    const contador      = document.getElementById('contador-resultados');

    if (!inputNombre) return;

    function filtrar() {
        const nombre = inputNombre.value.toLowerCase().trim();
        const cat    = selectCat.value;
        const precio = inputPrecio.value !== '' ? parseFloat(inputPrecio.value) : null;
        const estado = selectEstado.value;

        const hayFiltro = nombre !== '' || cat !== '' || precio !== null || estado !== '';
        btnLimpiar.style.display = hayFiltro ? 'inline-block' : 'none';

        let totalVisibles = 0;

        document.querySelectorAll('.grupo-categoria').forEach(grupo => {
            const items = grupo.querySelectorAll('.item-plato');
            let visiblesEnGrupo = 0;

            const catGrupo     = grupo.dataset.categoria;
            const grupoVisible = cat === '' || catGrupo === cat;

            items.forEach(item => {
                const coincideNombre = nombre === '' || item.dataset.nombre.includes(nombre);
                const itemPrecio     = parseFloat(item.dataset.precio);
                const coincidePrecio = precio === null || itemPrecio <= precio;
                const coincideEstado = estado === '' || item.dataset.activo === estado;
                const visible = grupoVisible && coincideNombre && coincidePrecio && coincideEstado;

                item.style.display = visible ? '' : 'none';
                if (visible) visiblesEnGrupo++;
            });

            grupo.style.display = visiblesEnGrupo > 0 ? '' : 'none';

            const contCat = grupo.querySelector('.contador-cat');
            if (contCat) {
                contCat.textContent = visiblesEnGrupo + ' plato' + (visiblesEnGrupo !== 1 ? 's' : '');
            }

            totalVisibles += visiblesEnGrupo;
        });

        sinResultados.style.display = totalVisibles === 0 ? '' : 'none';
        contador.textContent = totalVisibles > 0
            ? totalVisibles + ' plato' + (totalVisibles !== 1 ? 's' : '') + ' encontrado' + (totalVisibles !== 1 ? 's' : '')
            : '';
    }

    inputNombre.addEventListener('input', filtrar);
    selectCat.addEventListener('change', filtrar);
    inputPrecio.addEventListener('input', filtrar);
    selectEstado.addEventListener('change', filtrar);

    function limpiar() {
        inputNombre.value  = '';
        selectCat.value    = '';
        inputPrecio.value  = '';
        selectEstado.value = '';
        filtrar();
    }

    btnLimpiar.addEventListener('click', limpiar);
    if (btnLimpiar2) btnLimpiar2.addEventListener('click', limpiar);

    filtrar();
})();
</script>
@endsection
