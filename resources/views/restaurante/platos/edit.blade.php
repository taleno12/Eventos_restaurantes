@extends('restaurante.layout')
@section('title', 'Editar Plato')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#2d3748;">
                <i class="bi bi-pencil-square text-primary me-2"></i> Editar Plato
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                {{ $plato->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.platos.index') }}"
           class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center gap-2 px-3"
           style="height:38px;font-size:13px;font-weight:600;">
            <i class="bi bi-arrow-left text-secondary"></i> Volver
        </a>
    </div>

    {{-- Errores --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <ul class="mb-0 ps-2">
                    @foreach($errors->all() as $error)<li class="small">{{ $error }}</li>@endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('restaurante.platos.update', $plato) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4 align-items-start">

            {{-- ── Columna izquierda ── --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted fw-bold mb-4" style="font-size:0.72rem;letter-spacing:0.12em;">
                            <i class="bi bi-info-circle text-primary me-1"></i> Información del plato
                        </h6>

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Nombre del plato <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control bg-light"
                                   style="box-shadow:none;"
                                   value="{{ old('nombre', $plato->nombre) }}" required>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Descripción</label>
                            <textarea name="descripcion" class="form-control bg-light"
                                      style="box-shadow:none;resize:none;" rows="3">{{ old('descripcion', $plato->descripcion) }}</textarea>
                        </div>

                        {{-- Precio + Categoría --}}
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold text-dark small">Precio (C$) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">C$</span>
                                    <input type="number" name="precio" class="form-control bg-light border-start-0"
                                           style="box-shadow:none;" min="0" step="0.01"
                                           value="{{ old('precio', $plato->precio) }}" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold text-dark small d-flex align-items-center gap-2">
                                    Categoría
                                    <a href="{{ route('restaurante.categorias.index') }}"
                                       class="text-primary" style="font-size:11px;font-weight:500;" target="_blank">
                                        <i class="bi bi-plus-circle me-1"></i>Gestionar
                                    </a>
                                </label>
                                <select name="categoria_id" class="form-select bg-light" style="box-shadow:none;">
                                    <option value="">Sin categoría</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('categoria_id', $plato->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Columna derecha ── --}}
            <div class="col-12 col-lg-4 d-flex flex-column gap-4">

                {{-- Foto --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.12em;">
                            <i class="bi bi-image text-primary me-1"></i> Foto del plato
                        </h6>

                        @if($plato->imagen)
                        <div class="mb-3">
                            <p class="small text-muted fw-semibold mb-2">Foto actual</p>
                            <img src="{{ asset('storage/'.$plato->imagen) }}"
                                 class="w-100 rounded-3 border" style="aspect-ratio:1;object-fit:cover;">
                        </div>
                        @endif

                        <label for="imagen-input" class="w-100 cursor-pointer">
                            <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                 style="aspect-ratio:1;border:2px dashed #dee2e6;cursor:pointer;transition:border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#0d6efd'" onmouseout="this.style.borderColor='#dee2e6'">
                                <div id="imagen-placeholder" class="text-center py-3">
                                    <i class="bi bi-camera fs-3 text-secondary d-block mb-2"></i>
                                    <p class="small text-muted mb-0">{{ $plato->imagen ? 'Cambiar foto' : 'Subir foto' }}</p>
                                </div>
                                <img id="imagen-preview" src=""
                                     class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                                <input type="file" name="imagen" id="imagen-input" accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;">
                            </div>
                        </label>
                        <p class="small text-muted mt-2 mb-0">Deja vacío para mantener la foto actual</p>
                    </div>
                </div>

                {{-- Estado + Guardar --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.12em;">
                            <i class="bi bi-toggle-on text-primary me-1"></i> Estado
                        </h6>
                        <div class="form-check mb-4">
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" name="activo" value="1" id="check-activo"
                                   class="form-check-input"
                                   {{ old('activo', $plato->activo) ? 'checked' : '' }}>
                            <label for="check-activo" class="form-check-label small fw-semibold text-dark">
                                Disponible
                                <span class="d-block text-muted fw-normal" style="font-size:11px;">Visible en el menú público</span>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill">
                            <i class="bi bi-save me-1"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('restaurante.platos.index') }}"
                           class="btn btn-light w-100 fw-semibold rounded-pill mt-2">
                            Cancelar
                        </a>
                    </div>
                </div>

                {{-- Zona de peligro --}}
                <div class="card border-0 shadow-sm rounded-3" style="border-color:#fecaca !important;border:1px solid #fecaca;">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.12em;color:#dc2626;">
                            <i class="bi bi-exclamation-triangle me-1"></i> Zona de peligro
                        </h6>
                        <button type="submit" form="form-delete-plato"
                                onclick="return confirm('¿Eliminar este plato del menú? Esta acción no se puede deshacer.')"
                                class="btn btn-outline-danger w-100 fw-semibold rounded-pill" style="font-size:13px;">
                            <i class="bi bi-trash me-1"></i> Eliminar plato
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{-- Form delete oculto --}}
    <form id="form-delete-plato" method="POST"
          action="{{ route('restaurante.platos.destroy', $plato) }}" style="display:none;">
        @csrf @method('DELETE')
    </form>

</div>

<style>
    .cursor-pointer { cursor: pointer; }
</style>

@endsection

@section('scripts')
<script>
document.getElementById('imagen-input').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('imagen-preview');
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        document.getElementById('imagen-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
