@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('eventos.index') }}"
           class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
           style="width: 38px; height: 38px;">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Anuncio Gastronómico
            </h1>
            <p class="text-muted small mb-0">Modifica la información de <span class="fw-semibold text-dark">{{ $evento->titulo }}</span>.</p>
        </div>
    </div>

    {{-- Errores globales --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <div>
                    <p class="fw-semibold mb-1">Corrige los siguientes errores:</p>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ══════════════════════════════════════════ --}}
    {{-- CARD PRINCIPAL: Datos del Evento          --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">

        {{-- Header oscuro --}}
        <div class="rounded-top-3 px-4 py-4 d-flex align-items-center gap-3"
             style="background: linear-gradient(to right, #18181b, #27272a);">
            <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                 style="width:46px;height:46px;background:rgba(234,179,8,0.2);border:1px solid rgba(234,179,8,0.3);">
                <i class="bi bi-pencil-square" style="color:#fbbf24;font-size:1.1rem;"></i>
            </div>
            <div>
                <p class="text-white fw-bold mb-0">
                    Modificar Evento: <span style="color:#fbbf24;">{{ $evento->titulo }}</span>
                </p>
                <p class="mb-0" style="color:#9ca3af;font-size:0.75rem;">Actualiza la información necesaria para tus clientes.</p>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('eventos.update', $evento->id) }}" method="POST" enctype="multipart/form-data" id="edit-form">
                @csrf
                @method('PUT')

                {{-- Tipo actual guardado (para JS) --}}
                @php
                    $tipoActual = $evento->gastrobar_id ? 'gastrobar' : 'restaurante';
                @endphp
                <input type="hidden" id="tipo_establecimiento" value="{{ old('tipo_establecimiento', $tipoActual) }}">

                <div class="row g-4">

                    {{-- ── Columna Izquierda ── --}}
                    <div class="col-12 col-md-6">

                        {{-- Título --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">
                                Título del Evento <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-type"></i></span>
                                <input type="text" name="titulo" value="{{ old('titulo', $evento->titulo) }}" required maxlength="100"
                                       placeholder="Ej: Festival del Vigorón"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                        {{-- Fecha y Precio --}}
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark small">
                                    Fecha <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" name="fecha_evento"
                                           value="{{ old('fecha_evento', \Carbon\Carbon::parse($evento->fecha_evento)->format('Y-m-d')) }}"
                                           required class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark small">
                                    Precio (C$) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-currency-dollar"></i></span>
                                    <input type="number" name="precio" step="0.01"
                                           value="{{ old('precio', $evento->precio) }}"
                                           required placeholder="0.00"
                                           class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                                </div>
                            </div>
                        </div>

                        {{-- Departamento --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">
                                Departamento <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                                <select id="departamento_id" name="departamento_id" required
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled>Seleccionar...</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id }}" {{ $evento->departamento_id == $dep->id ? 'selected' : '' }}>
                                            {{ $dep->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Municipio --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">
                                Municipio <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                                <select id="municipio_id" name="municipio_id" required
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled>Seleccionar...</option>
                                    @foreach($municipios as $mun)
                                        <option value="{{ $mun->id }}" {{ $evento->municipio_id == $mun->id ? 'selected' : '' }}>
                                            {{ $mun->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- ── TIPO DE ESTABLECIMIENTO ── --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">
                                Tipo de Establecimiento <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" id="btn-tipo-restaurante"
                                        class="btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn"
                                        onclick="setTipo('restaurante')">
                                    <i class="bi bi-shop me-1"></i> Restaurante
                                </button>
                                <button type="button" id="btn-tipo-gastrobar"
                                        class="btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn"
                                        onclick="setTipo('gastrobar')">
                                    <i class="bi bi-cup-straw me-1"></i> Gastrobar
                                </button>
                            </div>
                        </div>

                        {{-- Restaurante --}}
                        <div class="mb-3" id="wrapper-restaurante">
                            <label class="form-label fw-semibold text-dark small">
                                Restaurante <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                                <select id="restaurante_id" name="restaurante_id"
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled selected>Seleccionar restaurante...</option>
                                    @foreach($restaurantes as $rest)
                                        <option value="{{ $rest->id }}" {{ $evento->restaurante_id == $rest->id ? 'selected' : '' }}>
                                            {{ $rest->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Gastrobar --}}
                        <div class="mb-3 d-none" id="wrapper-gastrobar">
                            <label class="form-label fw-semibold text-dark small">
                                Gastrobar <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="color:#f59e0b;"><i class="bi bi-cup-straw"></i></span>
                                <select id="gastrobar_id" name="gastrobar_id"
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled selected>Seleccionar gastrobar...</option>
                                    @foreach($gastrobares as $gb)
                                        <option value="{{ $gb->id }}" {{ $evento->gastrobar_id == $gb->id ? 'selected' : '' }}>
                                            {{ $gb->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Evento Destacado --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold text-dark small">¿Es un evento destacado?</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-star-fill"></i></span>
                                <select name="is_destacado"
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="0" {{ !$evento->is_destacado ? 'selected' : '' }}>Evento Normal</option>
                                    <option value="1" {{ $evento->is_destacado ? 'selected' : '' }}>Evento Destacado (Banner Principal)</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- ── Columna Derecha ── --}}
                    <div class="col-12 col-md-6">

                        {{-- Imagen Principal --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">Imagen Principal</label>
                            <div class="mb-2">
                                <p class="small fw-semibold text-muted mb-2">Imagen actual:</p>
                                @if($evento->imagen)
                                    <img id="img-portada-actual"
                                         src="{{ asset('storage/' . $evento->imagen) }}"
                                         alt="Imagen actual"
                                         class="w-100 rounded-3 border shadow-sm" style="height:180px;object-fit:cover;">
                                @else
                                    <div id="img-portada-actual"
                                         class="w-100 rounded-3 d-flex flex-column align-items-center justify-content-center text-muted gap-2"
                                         style="height:180px;border:2px dashed #dee2e6;">
                                        <i class="bi bi-image fs-2 text-secondary"></i>
                                        <span class="small">Sin imagen principal</span>
                                    </div>
                                @endif
                            </div>
                            <p class="small fw-semibold text-muted mb-2">Reemplazar imagen <span class="fw-normal">(opcional)</span></p>
                            <label for="imagen" class="w-100" style="cursor:pointer;">
                                <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                     style="height:120px;border:2px dashed #dee2e6;transition:border-color 0.2s;"
                                     onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                                    <input type="file" name="imagen" id="imagen" accept="image/*"
                                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;z-index:10;">
                                    <div id="preview-placeholder" class="text-center">
                                        <i class="bi bi-cloud-upload fs-3 text-secondary"></i>
                                        <p class="small text-muted mt-1 mb-0">Haz clic para seleccionar</p>
                                        <p class="text-secondary mb-0" style="font-size:0.72rem;">JPG, PNG o WEBP (Máx. 2MB)</p>
                                    </div>
                                    <img id="nueva-preview-portada" src="" alt=""
                                         class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                                </div>
                            </label>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold text-dark small">Descripción del Evento</label>
                            <textarea name="descripcion" rows="6" maxlength="500"
                                      class="form-control bg-light" style="box-shadow:none;resize:none;"
                                      placeholder="Cuéntanos más sobre el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- ── Botones ── --}}
                <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('eventos.index') }}"
                       class="text-muted text-decoration-none small fw-medium">
                        <i class="bi bi-chevron-left me-1"></i> Cancelar y volver
                    </a>
                    <button type="submit" id="btn-submit"
                            class="btn btn-warning fw-semibold px-5 py-2 rounded-pill shadow-sm text-dark">
                        <i class="bi bi-check2-circle me-1"></i> Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- CARD GALERÍA DE FOTOS ADICIONALES         --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:44px;height:44px;background:#fff7ed;border:1px solid #fed7aa;">
                    <i class="bi bi-images" style="color:#ea580c;font-size:1.1rem;"></i>
                </div>
                <div>
                    <p class="fw-bold text-dark mb-0">Galería de Fotos Adicionales</p>
                    <p class="text-muted mb-0" style="font-size:0.75rem;">Las fotos adicionales se muestran en la página pública del evento.</p>
                </div>
            </div>

            @if($evento->imagenes && $evento->imagenes->count() > 0)
                <p class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.5px;">Fotos actuales</p>
                <div class="row g-3 mb-4">
                    @foreach($evento->imagenes as $foto)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <div class="position-relative rounded-3 overflow-hidden border shadow-sm foto-thumb"
                                 style="aspect-ratio:1/1;">
                                <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto del evento"
                                     class="w-100 h-100" style="object-fit:cover;">
                                <form action="{{ route('evento.imagenes.destroy', $foto->id) }}" method="POST"
                                      class="position-absolute top-0 end-0 m-1"
                                      onsubmit="return confirm('¿Eliminar esta foto?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm d-flex align-items-center justify-content-center p-0 delete-foto-btn"
                                            style="width:26px;height:26px;border-radius:6px;font-size:0.7rem;opacity:0;transition:opacity 0.2s;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <label style="cursor:pointer;" class="w-100 h-100">
                            <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative"
                                 style="aspect-ratio:1/1;border:2px dashed #dee2e6;transition:border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#ea580c';this.style.background='#fff7ed';"
                                 onmouseout="this.style.borderColor='#dee2e6';this.style.background='';">
                                <input type="file" id="add-more-photos" accept="image/*" multiple
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;z-index:10;">
                                <i class="bi bi-plus-lg fs-4 text-secondary"></i>
                                <span class="small text-muted mt-1" style="font-size:0.7rem;">Añadir</span>
                            </div>
                        </label>
                    </div>
                </div>
            @else
                <p class="text-muted small fst-italic mb-3">
                    <i class="bi bi-info-circle text-secondary me-1"></i>
                    Este evento aún no tiene fotos adicionales.
                </p>
                <label id="upload-zone-label" class="w-100 mb-3" style="cursor:pointer;">
                    <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center text-center p-5"
                         style="border:2px dashed #dee2e6;transition:border-color 0.2s;"
                         onmouseover="this.style.borderColor='#ea580c'" onmouseout="this.style.borderColor='#dee2e6'">
                        <input type="file" id="add-more-photos" accept="image/*" multiple class="d-none">
                        <i class="bi bi-cloud-upload fs-1 text-secondary mb-2"></i>
                        <p class="fw-bold text-secondary mb-1">Arrastra o haz clic para subir fotos</p>
                        <p class="text-muted mb-0" style="font-size:0.78rem;">JPG, PNG o WEBP — Puedes subir varias a la vez</p>
                    </div>
                </label>
            @endif

            <div id="new-photos-preview" class="d-flex flex-wrap gap-2 mt-2"></div>

            <form action="{{ route('evento.imagenes.store', $evento->id) }}" method="POST"
                  enctype="multipart/form-data" id="photos-form" class="mt-3">
                @csrf
                <input type="file" name="fotos[]" id="fotos-hidden" accept="image/*" multiple class="d-none">
                <div id="upload-actions" class="d-none align-items-center gap-3">
                    <span id="files-count" class="small fw-semibold text-muted"></span>
                    <button type="submit"
                            class="btn fw-bold text-white px-4"
                            style="background:#ea580c;border:none;border-radius:10px;font-size:0.85rem;"
                            onmouseover="this.style.background='#c2410c'" onmouseout="this.style.background='#ea580c'">
                        <i class="bi bi-upload me-1"></i> Subir fotos seleccionadas
                    </button>
                    <button type="button" id="cancel-upload"
                            class="btn btn-outline-secondary fw-semibold px-4"
                            style="border-radius:10px;font-size:0.85rem;">
                        Cancelar
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<style>
    .foto-thumb:hover .delete-foto-btn { opacity: 1 !important; }
    .tipo-btn { transition: all 0.2s; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Referencias DOM ──
    const tipoInput      = document.getElementById('tipo_establecimiento');
    const btnRestaurante = document.getElementById('btn-tipo-restaurante');
    const btnGastrobar   = document.getElementById('btn-tipo-gastrobar');
    const wrapRest       = document.getElementById('wrapper-restaurante');
    const wrapGastro     = document.getElementById('wrapper-gastrobar');
    const restSelect     = document.getElementById('restaurante_id');
    const gastroSelect   = document.getElementById('gastrobar_id');
    const depSelect      = document.getElementById('departamento_id');
    const munSelect      = document.getElementById('municipio_id');

    // ── Cambio de tipo ──
    window.setTipo = function (tipo) {
        tipoInput.value = tipo;

        if (tipo === 'restaurante') {
            btnRestaurante.className = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-primary';
            btnGastrobar.className   = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-outline-warning';
            wrapRest.classList.remove('d-none');
            wrapGastro.classList.add('d-none');
            restSelect.name   = 'restaurante_id';
            gastroSelect.name = '';
        } else {
            btnGastrobar.className   = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-warning text-dark';
            btnRestaurante.className = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-outline-primary';
            wrapGastro.classList.remove('d-none');
            wrapRest.classList.add('d-none');
            gastroSelect.name = 'gastrobar_id';
            restSelect.name   = '';
        }
    };

    // Inicializar con el tipo actual del evento
    setTipo(tipoInput.value || 'restaurante');

    // ── Encadenamiento departamento → municipio → establecimientos ──
    depSelect.addEventListener('change', function () {
        const depId = this.value;
        munSelect.innerHTML = '<option>Cargando municipios...</option>';
        restSelect.innerHTML = '<option value="" disabled selected>Primero elige municipio...</option>';
        gastroSelect.innerHTML = '<option value="" disabled selected>Primero elige municipio...</option>';

        if (!depId) return;
        fetch(`/api/departamentos/${depId}/municipios`)
            .then(r => r.json())
            .then(data => {
                munSelect.innerHTML = '<option value="" disabled selected>Seleccionar municipio...</option>';
                data.forEach(mun => {
                    const opt = document.createElement('option');
                    opt.value = mun.id;
                    opt.textContent = mun.nombre;
                    munSelect.appendChild(opt);
                });
            });
    });

    munSelect.addEventListener('change', function () {
        const munId = this.value;
        if (!munId) return;

        fetch(`/api/municipios/${munId}/restaurantes`)
            .then(r => r.json())
            .then(data => {
                restSelect.innerHTML = '<option value="" disabled selected>Seleccionar restaurante...</option>';
                data.forEach(rest => {
                    const opt = document.createElement('option');
                    opt.value = rest.id;
                    opt.textContent = rest.nombre;
                    restSelect.appendChild(opt);
                });
            });

        fetch(`/api/municipios/${munId}/gastrobares`)
            .then(r => r.json())
            .then(data => {
                gastroSelect.innerHTML = '<option value="" disabled selected>Seleccionar gastrobar...</option>';
                data.forEach(gb => {
                    const opt = document.createElement('option');
                    opt.value = gb.id;
                    opt.textContent = gb.nombre;
                    gastroSelect.appendChild(opt);
                });
            });
    });

    // ── Preview imagen principal ──
    const imgInput     = document.getElementById('imagen');
    const imgActual    = document.getElementById('img-portada-actual');
    const nuevaPreview = document.getElementById('nueva-preview-portada');
    const placeholder  = document.getElementById('preview-placeholder');

    if (imgInput) {
        imgInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                if (imgActual && imgActual.tagName === 'IMG') {
                    imgActual.src = e.target.result;
                }
                if (nuevaPreview) {
                    nuevaPreview.src = e.target.result;
                    nuevaPreview.classList.remove('d-none');
                }
                if (placeholder) placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    // ── Galería: preview fotos adicionales ──
    const addPhotosInput = document.getElementById('add-more-photos');
    const fotosHidden    = document.getElementById('fotos-hidden');
    const previewWrap    = document.getElementById('new-photos-preview');
    const uploadActions  = document.getElementById('upload-actions');
    const filesCount     = document.getElementById('files-count');
    const cancelUpload   = document.getElementById('cancel-upload');
    let selectedFiles    = [];

    function syncFilesToHidden() {
        if (!fotosHidden) return;
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fotosHidden.files = dt.files;
    }

    function renderPreviews() {
        if (!previewWrap) return;
        previewWrap.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.className = 'position-relative rounded-3 overflow-hidden border';
                wrap.style.cssText = 'width:80px;height:80px;border-color:#fed7aa!important;flex-shrink:0;';
                wrap.innerHTML = `
                    <img src="${e.target.result}" alt="" style="width:100%;height:100%;object-fit:cover;">
                    <button type="button" class="btn btn-danger position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center remove-new"
                            data-idx="${idx}" style="width:18px;height:18px;border-radius:4px;font-size:0.6rem;margin:3px;">
                        <i class="bi bi-x"></i>
                    </button>`;
                previewWrap.appendChild(wrap);
                wrap.querySelector('.remove-new').addEventListener('click', function () {
                    selectedFiles.splice(parseInt(this.dataset.idx), 1);
                    syncFilesToHidden();
                    renderPreviews();
                    updateActions();
                });
            };
            reader.readAsDataURL(file);
        });
    }

    function updateActions() {
        if (!uploadActions) return;
        if (selectedFiles.length > 0) {
            uploadActions.classList.remove('d-none');
            uploadActions.classList.add('d-flex');
            if (filesCount) filesCount.textContent = `${selectedFiles.length} foto${selectedFiles.length > 1 ? 's' : ''} lista${selectedFiles.length > 1 ? 's' : ''}`;
        } else {
            uploadActions.classList.add('d-none');
            uploadActions.classList.remove('d-flex');
        }
    }

    if (addPhotosInput) {
        addPhotosInput.addEventListener('change', function () {
            Array.from(this.files).forEach(f => selectedFiles.push(f));
            syncFilesToHidden();
            renderPreviews();
            updateActions();
            this.value = '';
        });
    }

    if (cancelUpload) {
        cancelUpload.addEventListener('click', function () {
            selectedFiles = [];
            syncFilesToHidden();
            renderPreviews();
            updateActions();
        });
    }

    // ── Spinner en submit ──
    document.getElementById('edit-form').addEventListener('submit', function () {
        // Habilitar el select activo para que se envíe
        const tipo = tipoInput.value;
        if (tipo === 'restaurante') {
            restSelect.disabled = false;
            restSelect.name = 'restaurante_id';
        } else {
            gastroSelect.disabled = false;
            gastroSelect.name = 'gastrobar_id';
        }
        const btn = document.getElementById('btn-submit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
        }
    });

});
</script>

@endsection
