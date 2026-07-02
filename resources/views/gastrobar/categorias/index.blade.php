@extends('gastrobar.layout')
@section('title', 'Mis Categorías')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#f8fafc;">
                <i class="bi bi-tags me-2" style="color:#818cf8;"></i> Mis Categorías
            </h1>
            <p class="mb-0 small" style="color:#cbd5e1;">
                Organiza las categorías del menú de {{ $gastrobar->nombre }}
            </p>
        </div>
        <a href="{{ route('gastrobar.platos.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver al Menú
        </a>
    </div>

    {{-- Mensaje de éxito - UN SOLO BLOQUE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2) !important;color:#4ade80;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2) !important;color:#ef4444;">
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

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                    <span class="fw-bold" style="color:#94a3b8;">
                        <i class="bi bi-list-ul me-1"></i> Categorías
                        <span class="badge rounded-pill ms-1" style="background:rgba(99,102,241,0.15);color:#a5b4fc;font-size:0.7rem;">{{ $categorias->count() }}</span>
                    </span>
                    <span style="color:#94a3b8;font-size:11px;">
                        <i class="bi bi-grip-vertical me-1"></i> Arrastra para reordenar
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($categorias->count() > 0)
                    <ul id="sortable-list" class="list-unstyled mb-0">
                        @foreach($categorias as $cat)
                        <li class="d-flex align-items-center gap-3 px-4 py-3 border-bottom sortable-item"
                            data-id="{{ $cat->id }}"
                            style="border-color:#334155 !important;cursor:default;">

                            <div class="drag-handle" style="cursor:grab;font-size:1.1rem;color:#94a3b8;">
                                <i class="bi bi-grip-vertical"></i>
                            </div>

                            <div class="rounded-circle fw-black d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:28px;height:28px;font-size:11px;background:rgba(99,102,241,0.15);color:#a5b4fc;">
                                {{ $loop->iteration }}
                            </div>

                            <div class="flex-grow-1">
                                <span class="fw-semibold cat-nombre" style="font-size:0.9rem;color:#f8fafc;">{{ $cat->nombre }}</span>
                                <small class="ms-2" style="font-size:11px;color:#94a3b8;">
                                    {{ $cat->platos_count }} plato{{ $cat->platos_count !== 1 ? 's' : '' }}
                                </small>

                                <form method="POST" action="{{ route('gastrobar.categorias.update', $cat) }}"
                                      class="form-editar mt-2 d-none">
                                    @csrf @method('PUT')
                                    <div class="d-flex gap-2">
                                        <input type="text" name="nombre" value="{{ $cat->nombre }}"
                                               class="form-control form-control-sm" required
                                               style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                                            Guardar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill btn-cancelar-editar">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="d-flex gap-1 flex-shrink-0 acciones-cat">
                                <button type="button"
                                        class="btn btn-sm px-2 action-icon-edit btn-editar-cat"
                                        style="background:transparent;color:#94a3b8;"
                                        title="Editar nombre">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="submit"
                                        form="form-delete-cat-{{ $cat->id }}"
                                        class="btn btn-sm px-2 action-icon-delete"
                                        style="background:transparent;color:#94a3b8;"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </li>

                        <form id="form-delete-cat-{{ $cat->id }}"
                              method="POST"
                              action="{{ route('gastrobar.categorias.destroy', $cat) }}"
                              onsubmit="return confirm('¿Eliminar la categoría {{ addslashes($cat->nombre) }}? Los platos asociados quedarán sin categoría.')">
                            @csrf @method('DELETE')
                        </form>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center py-5" style="color:#94a3b8;">
                        <i class="bi bi-tags d-block display-6 mb-3"></i>
                        <span class="fs-6 d-block">Aún no tienes categorías.</span>
                        <span class="small">Crea la primera desde el formulario.</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                    <span class="fw-bold" style="color:#94a3b8;">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
                    </span>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('gastrobar.categorias.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Nombre *</label>
                            <input type="text" name="nombre" class="form-control"
                                   placeholder="Ej: Entradas, Cocteles, Tapas..."
                                   value="{{ old('nombre') }}" required autofocus
                                   style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                            <div class="form-text" style="font-size:11px;color:#94a3b8;">
                                Se agregará al final de la lista y podrás reordenarla.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill py-2">
                            <i class="bi bi-plus-lg me-1"></i> Crear Categoría
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 mt-3" style="background:rgba(99,102,241,0.1) !important;border:1px solid rgba(99,102,241,0.3) !important;">
                <div class="card-body p-4">
                    <p class="mb-1 fw-semibold" style="font-size:12px;color:#a5b4fc;">
                        <i class="bi bi-lightbulb me-1"></i> Tip
                    </p>
                    <p class="mb-0" style="font-size:11px;line-height:1.5;color:#cbd5e1;">
                        Al crear o editar un plato, podrás elegir una de estas categorías desde un selector. El orden que definas aquí es el mismo en que aparecen en el menú público.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .action-icon-edit:hover   { color: #fbbf24 !important; }
    .action-icon-delete:hover { color: #ef4444 !important; }
    .sortable-item.dragging   { opacity: 0.5; background: rgba(99,102,241,0.1) !important; }
    .drag-handle:active       { cursor: grabbing; }

    .form-control:focus {
        background-color: #0f172a !important;
        border-color: #818cf8 !important;
        color: #f8fafc !important;
        box-shadow: 0 0 0 0.2rem rgba(129, 140, 248, 0.25) !important;
    }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
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

const list = document.getElementById('sortable-list');
if (list) {
    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'dragging',
        onEnd: function () {
            const ids = [...list.querySelectorAll('.sortable-item')].map(el => el.dataset.id);
            fetch('{{ route('gastrobar.categorias.reorder') }}', {
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
