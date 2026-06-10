@extends('restaurante.layout')
@section('title', 'Menú')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#2d3748;">
                <i class="bi bi-egg-fried text-primary me-2"></i> Menú
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Platos de {{ $restaurante->nombre }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('restaurante.categorias.index') }}"
               class="btn btn-light border fw-semibold rounded-pill px-3"
               style="font-size:13px;">
                <i class="bi bi-tags me-1 text-primary"></i> Categorías
            </a>
            <a href="{{ route('restaurante.platos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Plato
            </a>
        </div>
    </div>

    {{-- ── Barra de filtros ── --}}
    @if(!$platos->isEmpty() || (isset($categorias) && $categorias->isNotEmpty()))
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">

                {{-- Buscador por nombre --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;">
                        <i class="bi bi-search me-1"></i> Buscar
                    </label>
                    <input type="text" id="filtro-nombre" class="form-control form-control-sm rounded-pill"
                           placeholder="Nombre del plato...">
                </div>

                {{-- Filtro por categoría --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;">
                        <i class="bi bi-tag me-1"></i> Categoría
                    </label>
                    <select id="filtro-categoria" class="form-select form-select-sm rounded-pill">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                        @endforeach
                        @if($platos->has('Sin categoría'))
                            <option value="Sin categoría">Sin categoría</option>
                        @endif
                    </select>
                </div>

                {{-- Filtro por precio máximo --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;">
                        <i class="bi bi-currency-dollar me-1"></i> Precio máx. (C$)
                    </label>
                    <input type="number" id="filtro-precio" class="form-control form-control-sm rounded-pill"
                           placeholder="Ej: 150" min="0" step="1">
                </div>

                {{-- Filtro estado --}}
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;">
                        <i class="bi bi-toggle-on me-1"></i> Estado
                    </label>
                    <select id="filtro-estado" class="form-select form-select-sm rounded-pill">
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>

            </div>

            {{-- Contador resultados --}}
            <div class="mt-2 d-flex align-items-center justify-content-between">
                <span id="contador-resultados" class="text-muted" style="font-size:11px;"></span>
                <button id="btn-limpiar" class="btn btn-link btn-sm text-muted p-0" style="font-size:11px;display:none;">
                    <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Estado vacío total --}}
    @if($platos->isEmpty())
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-egg-fried d-block display-6 text-muted mb-3"></i>
            <span class="fs-6 d-block mb-2">No tienes platos en tu menú aún.</span>
            <a href="{{ route('restaurante.platos.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Añadir primer plato
            </a>
        </div>
    </div>

    @else

    {{-- Estado vacío de filtros --}}
    <div id="sin-resultados" class="card border-0 shadow-sm rounded-3 bg-white" style="display:none;">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-search d-block display-6 text-muted mb-3"></i>
            <span class="fs-6 d-block mb-2">Ningún plato coincide con los filtros.</span>
            <button id="btn-limpiar-2" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                <i class="bi bi-x-circle me-1"></i> Limpiar filtros
            </button>
        </div>
    </div>

    @foreach($platos as $categoria => $items)
    <div class="mb-4 grupo-categoria" data-categoria="{{ $categoria }}">

        {{-- Header categoría --}}
        <div class="d-flex align-items-center gap-3 mb-3 header-categoria">
            <span class="fw-black text-primary text-uppercase" style="font-size:11px;letter-spacing:0.18em;">
                {{ $categoria ?: 'Sin categoría' }}
            </span>
            <div class="flex-grow-1" style="height:1px;background:#e2e8f0;"></div>
            <span class="text-muted contador-cat" style="font-size:11px;">
                {{ $items->count() }} plato{{ $items->count() !== 1 ? 's' : '' }}
            </span>
        </div>

        {{-- Grid de platos --}}
        <div class="row g-3">
            @foreach($items as $plato)
            <div class="col-12 col-sm-6 col-xl-4 item-plato"
                 data-nombre="{{ strtolower($plato->nombre) }}"
                 data-categoria="{{ $categoria }}"
                 data-precio="{{ $plato->precio }}"
                 data-activo="{{ $plato->activo ? '1' : '0' }}">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 card-plato">
                    {{-- Imagen --}}
                    <div class="position-relative" style="aspect-ratio:16/9;overflow:hidden;background:#f1f5f9;">
                        @if($plato->imagen)
                            <img src="{{ asset('storage/'.$plato->imagen) }}"
                                 class="w-100 h-100" style="object-fit:cover;">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-egg-fried text-muted" style="font-size:32px;"></i>
                            </div>
                        @endif
                        {{-- Badge activo/inactivo --}}
                        <div class="position-absolute top-0 end-0 p-2">
                            @if($plato->activo)
                                <span class="badge rounded-pill fw-semibold d-inline-flex align-items-center gap-1"
                                      style="background-color:#e6fffa;color:#047481;border:1px solid #b2f5ea;font-size:0.7rem;">
                                    <span class="bg-success rounded-circle" style="width:5px;height:5px;"></span> Activo
                                </span>
                            @else
                                <span class="badge rounded-pill bg-danger fw-semibold d-inline-flex align-items-center gap-1"
                                      style="font-size:0.7rem;">
                                    <span class="rounded-circle bg-white" style="width:5px;height:5px;opacity:0.7;"></span> Inactivo
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="fw-bold text-dark" style="font-size:14px;">{{ $plato->nombre }}</span>
                            <span class="text-primary fw-black text-nowrap" style="font-size:15px;">
                                C$ {{ number_format($plato->precio, 0) }}
                            </span>
                        </div>
                        @if($plato->descripcion)
                            <p class="text-muted mb-0" style="font-size:12px;line-height:1.5;">
                                {{ Str::limit($plato->descripcion, 70) }}
                            </p>
                        @endif
                    </div>

                    {{-- Acciones --}}
                    <div class="card-footer bg-white border-top d-flex gap-2 px-3 py-2">
                        <form method="POST" action="{{ route('restaurante.platos.toggle', $plato) }}" class="flex-grow-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-light btn-sm w-100 fw-semibold" style="font-size:11px;">
                                @if($plato->activo)
                                    <i class="bi bi-eye-slash me-1"></i> Ocultar
                                @else
                                    <i class="bi bi-eye me-1"></i> Activar
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('restaurante.platos.edit', $plato) }}"
                           class="btn btn-light btn-sm px-3 action-icon-edit" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="submit" form="form-delete-plato-{{ $plato->id }}"
                                class="btn btn-light btn-sm px-3 action-icon-delete" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                <form id="form-delete-plato-{{ $plato->id }}"
                      method="POST"
                      action="{{ route('restaurante.platos.destroy', $plato) }}"
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
    .card-plato:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important; transform: translateY(-2px); }
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
