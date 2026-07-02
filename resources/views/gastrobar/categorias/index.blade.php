@extends('gastrobar.layout')
@section('title', 'Mis Categorías')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Mis Categorías</div>
        <div class="page-sub">Organiza las categorías del menú de {{ $gastrobar->nombre }}</div>
    </div>
    <a href="{{ route('gastrobar.platos.index') }}" class="btn-secondary-panel">
        <i class="bi bi-arrow-left"></i> Volver al Menú
    </a>
</div>

@if($errors->any())
<div class="panel-alert panel-alert-error mb-4">
    <i class="bi bi-exclamation-circle-fill fs-5"></i>
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="row g-4 align-items-start">

    <div class="col-12 col-lg-8">
        <div class="panel-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-list-ul me-1"></i> Categorías
                    <span class="badge rounded-pill ms-1" style="background:var(--primary-light);color:var(--primary);font-size:0.7rem;">{{ $categorias->count() }}</span>
                </span>
                <span style="color:var(--muted);font-size:11px;">
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

                        <div class="drag-handle" style="cursor:grab;font-size:1.1rem;color:var(--muted);">
                            <i class="bi bi-grip-vertical"></i>
                        </div>

                        <div class="rounded-circle fw-black d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:28px;height:28px;font-size:11px;background:var(--primary-light);color:var(--primary);">
                            {{ $loop->iteration }}
                        </div>

                        <div class="flex-grow-1">
                            <span class="fw-semibold cat-nombre" style="font-size:0.9rem;color:var(--text);">{{ $cat->nombre }}</span>
                            <small class="ms-2" style="font-size:11px;color:var(--muted);">
                                {{ $cat->platos_count }} plato{{ $cat->platos_count !== 1 ? 's' : '' }}
                            </small>

                            <form method="POST" action="{{ route('gastrobar.categorias.update', $cat) }}"
                                  class="form-editar mt-2 d-none">
                                @csrf @method('PUT')
                                <div class="d-flex gap-2">
                                    <input type="text" name="nombre" value="{{ $cat->nombre }}"
                                           class="form-control form-control-sm" required>
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
                                    style="background:transparent;color:var(--muted);"
                                    title="Editar nombre">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="submit"
                                    form="form-delete-cat-{{ $cat->id }}"
                                    class="btn btn-sm px-2 action-icon-delete"
                                    style="background:transparent;color:var(--muted);"
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
                <div class="empty-state">
                    <i class="bi bi-tags"></i>
                    <p>Aún no tienes categorías.</p>
                    <span class="small">Crea la primera desde el formulario.</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="panel-card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('gastrobar.categorias.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Nombre *</label>
                        <input type="text" name="nombre" class="form-control"
                               placeholder="Ej: Entradas, Cocteles, Tapas..."
                               value="{{ old('nombre') }}" required autofocus>
                        <div class="form-text" style="font-size:11px;color:var(--muted);">
                            Se agregará al final de la lista y podrás reordenarla.
                        </div>
                    </div>
                    <button type="submit" class="btn-primary-panel w-100 justify-content-center">
                        <i class="bi bi-plus-lg"></i> Crear Categoría
                    </button>
                </form>
            </div>
        </div>

        <div class="panel-card mt-3" style="background:var(--primary-light) !important;border:1px solid var(--primary) !important;">
            <div class="card-body p-3">
                <p class="mb-1 fw-semibold" style="font-size:12px;color:var(--primary);">
                    <i class="bi bi-lightbulb me-1"></i> Tip
                </p>
                <p class="mb-0" style="font-size:11px;line-height:1.5;color:var(--text);">
                    Al crear o editar un plato, podrás elegir una de estas categorías desde un selector. El orden que definas aquí es el mismo en que aparecen en el menú público.
                </p>
            </div>
        </div>
    </div>

</div>

<style>
    .action-icon-edit:hover   { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .sortable-item.dragging   { opacity: 0.5; background: var(--primary-light) !important; }
    .drag-handle:active       { cursor: grabbing; }
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
