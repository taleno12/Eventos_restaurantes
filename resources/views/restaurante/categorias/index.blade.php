@extends('restaurante.layout')
@section('title', 'Mis Categorías')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-tags text-primary me-2"></i> Mis Categorías
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Organiza las categorías del menú de {{ $restaurante->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.platos.index') }}"
           class="btn border rounded-pill px-4 fw-semibold"
           style="background:var(--card-bg);color:var(--text);border-color:var(--card-border) !important;">
            <i class="bi bi-arrow-left me-1"></i> Volver al Menú
        </a>
    </div>

    {{-- Alerta --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-circle-fill fs-5 mt-1"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4 align-items-start">

        {{-- ── Columna izquierda: lista + reorder ── --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                <div class="card-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center"
                     style="background:var(--hover-bg);border-color:var(--card-border) !important;">
                    <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                        <i class="bi bi-list-ul me-1"></i> Categorías
                        <span class="badge bg-primary bg-opacity-10 text-primary ms-1 fw-bold" style="font-size:0.7rem;">{{ $categorias->count() }}</span>
                    </span>
                    <span style="font-size:11px;color:var(--muted);">
                        <i class="bi bi-grip-vertical me-1"></i> Arrastra para reordenar
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($categorias->count() > 0)
                    <ul id="sortable-list" class="list-unstyled mb-0">
                        @foreach($categorias as $cat)
                        <li class="d-flex align-items-center gap-3 px-4 py-3 border-bottom sortable-item"
                            data-id="{{ $cat->id }}"
                            style="border-color:var(--card-border) !important;cursor:default;">

                            {{-- Handle drag --}}
                            <div class="drag-handle" style="cursor:grab;font-size:1.1rem;color:var(--muted);">
                                <i class="bi bi-grip-vertical"></i>
                            </div>

                            {{-- Número orden --}}
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-black d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:28px;height:28px;font-size:11px;">
                                {{ $loop->iteration }}
                            </div>

                            {{-- Nombre (editable inline) --}}
                            <div class="flex-grow-1">
                                <span class="fw-semibold cat-nombre" style="font-size:0.9rem;color:var(--text);">{{ $cat->nombre }}</span>
                                <small class="ms-2" style="font-size:11px;color:var(--muted);">
                                    {{ $cat->platos_count }} plato{{ $cat->platos_count !== 1 ? 's' : '' }}
                                </small>

                                {{-- Form editar (oculto por defecto) --}}
                                <form method="POST" action="{{ route('restaurante.categorias.update', $cat) }}"
                                      class="form-editar mt-2 d-none">
                                    @csrf @method('PUT')
                                    <div class="d-flex gap-2">
                                        <input type="text" name="nombre" value="{{ $cat->nombre }}"
                                               class="form-control form-control-sm input-tema" required>
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                                            Guardar
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill btn-cancelar-editar"
                                                style="background:var(--hover-bg);color:var(--text);border:1px solid var(--card-border);">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Acciones --}}
                            <div class="d-flex gap-1 flex-shrink-0 acciones-cat">
                                <button type="button"
                                        class="btn btn-sm px-2 action-icon-edit btn-editar-cat"
                                        title="Editar nombre"
                                        style="background:var(--hover-bg);color:var(--muted);border:1px solid var(--card-border);">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="submit"
                                        form="form-delete-cat-{{ $cat->id }}"
                                        class="btn btn-sm px-2 action-icon-delete"
                                        title="Eliminar"
                                        style="background:var(--hover-bg);color:var(--muted);border:1px solid var(--card-border);">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </li>

                        {{-- Form DELETE --}}
                        <form id="form-delete-cat-{{ $cat->id }}"
                              method="POST"
                              action="{{ route('restaurante.categorias.destroy', $cat) }}"
                              onsubmit="return confirm('¿Eliminar la categoría {{ addslashes($cat->nombre) }}? Los platos asociados quedarán sin categoría.')">
                            @csrf @method('DELETE')
                        </form>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center py-5" style="color:var(--muted);">
                        <i class="bi bi-tags d-block display-6 mb-3" style="opacity:0.4;"></i>
                        <span class="fs-6 d-block" style="color:var(--text);">Aún no tienes categorías.</span>
                        <span class="small">Crea la primera desde el formulario.</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Columna derecha: nueva categoría ── --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                <div class="card-header border-bottom py-3 px-4" style="background:var(--hover-bg);border-color:var(--card-border) !important;">
                    <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
                    </span>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('restaurante.categorias.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Nombre *</label>
                            <input type="text" name="nombre" class="form-control input-tema"
                                   placeholder="Ej: Entradas, Postres, Bebidas..."
                                   value="{{ old('nombre') }}" required autofocus>
                            <div class="form-text" style="font-size:11px;color:var(--muted);">
                                Se agregará al final de la lista y podrás reordenarla.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill py-2">
                            <i class="bi bi-plus-lg me-1"></i> Crear Categoría
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tip --}}
            <div class="card border-0 rounded-3 mt-3" style="background-color:rgba(37,99,235,0.08);border:1px solid rgba(37,99,235,0.25) !important;">
                <div class="card-body p-3">
                    <p class="mb-1 fw-semibold text-primary" style="font-size:12px;">
                        <i class="bi bi-lightbulb me-1"></i> Tip
                    </p>
                    <p class="mb-0" style="font-size:11px;line-height:1.5;color:var(--muted);">
                        Al crear o editar un plato, podrás elegir una de estas categorías desde un selector. El orden que definas aquí es el mismo en que aparecen en el menú público.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .action-icon-edit:hover   { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .sortable-item.dragging   { opacity: 0.5; background: var(--hover-bg) !important; }
    .drag-handle:active       { cursor: grabbing; }

    .input-tema {
        background: var(--card-bg) !important;
        color: var(--text) !important;
        border: 1px solid var(--card-border) !important;
    }
    .input-tema::placeholder {
        color: var(--muted) !important;
        opacity: 0.8;
    }
    .input-tema:focus {
        background: var(--card-bg) !important;
        color: var(--text) !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.15) !important;
    }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// ── Edición inline ──
document.querySelectorAll('.btn-editar-cat').forEach(btn => {
    btn.addEventListener('click', function () {
        const item = this.closest('.sortable-item');
        item.querySelector('.cat-nombre').classList.add('d-none');
        item.querySelector('.form-editar').classList.remove('d-none');
        item.querySelector('.acciones-cat').classList.add('d-none');
        item.querySelector('.drag-handle').classList.add('invisible');
    });
});

document.querySelectorAll('.btn-cancelar-editar').forEach(btn => {
    btn.addEventListener('click', function () {
        const item = this.closest('.sortable-item');
        item.querySelector('.cat-nombre').classList.remove('d-none');
        item.querySelector('.form-editar').classList.add('d-none');
        item.querySelector('.acciones-cat').classList.remove('d-none');
        item.querySelector('.drag-handle').classList.remove('invisible');
    });
});

// ── Drag & drop reorder ──
const list = document.getElementById('sortable-list');
if (list) {
    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'dragging',
        onEnd: function () {
            const ids = [...list.querySelectorAll('.sortable-item')].map(el => el.dataset.id);
            fetch('{{ route('restaurante.categorias.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orden: ids })
            });
        }
    });
}
</script>
@endsection
