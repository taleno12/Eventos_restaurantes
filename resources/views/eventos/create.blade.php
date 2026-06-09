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
                <i class="bi bi-megaphone text-warning me-2"></i> Publicar Nuevo Anuncio Gastronómico
            </h1>
            <p class="text-muted small mb-0">Atrae a más clientes con una descripción llamativa.</p>
        </div>
    </div>

    {{-- Errores globales --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <div>
                    <p class="fw-semibold mb-1">Por favor corrige los siguientes campos:</p>
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

    <form id="form-evento" action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 1: Información del Anuncio           --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">

            {{-- Header oscuro --}}
            <div class="rounded-top-3 px-4 py-4 d-flex align-items-center gap-3"
                 style="background: linear-gradient(to right, #18181b, #27272a);">
                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:46px;height:46px;background:rgba(59,130,246,0.2);border:1px solid rgba(96,165,250,0.3);">
                    <i class="bi bi-megaphone" style="color:#60a5fa;font-size:1.1rem;"></i>
                </div>
                <div>
                    <p class="text-white fw-bold mb-0">Detalles del Anuncio</p>
                    <p class="mb-0" style="color:#9ca3af;font-size:0.75rem;">Atrae a más clientes con una descripción llamativa.</p>
                </div>
            </div>

            <div class="card-body p-4">
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
                                <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="100"
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
                                    <input type="date" name="fecha_evento" value="{{ old('fecha_evento') }}" required
                                           class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark small">
                                    Precio (C$) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-currency-dollar"></i></span>
                                    <input type="number" name="precio" step="0.01" value="{{ old('precio') }}" required
                                           placeholder="0.00"
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
                                    <option value="" disabled selected>Seleccionar...</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id }}" @selected(old('departamento_id') == $dep->id)>{{ $dep->nombre }}</option>
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
                                <select id="municipio_id" name="municipio_id" required disabled
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled selected>Primero elige departamento...</option>
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
                                        class="btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-primary"
                                        onclick="setTipo('restaurante')">
                                    <i class="bi bi-shop me-1"></i> Restaurante
                                </button>
                                <button type="button" id="btn-tipo-gastrobar"
                                        class="btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-outline-warning"
                                        onclick="setTipo('gastrobar')">
                                    <i class="bi bi-cup-straw me-1"></i> Gastrobar
                                </button>
                            </div>
                            <input type="hidden" id="tipo_establecimiento" value="{{ old('tipo_establecimiento', 'restaurante') }}">
                        </div>

                        {{-- Restaurante (visible por defecto) --}}
                        <div class="mb-3" id="wrapper-restaurante">
                            <label class="form-label fw-semibold text-dark small">
                                Restaurante <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                                <select id="restaurante_id" name="restaurante_id" disabled
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled selected>Primero elige municipio...</option>
                                </select>
                            </div>
                        </div>

                        {{-- Gastrobar (oculto por defecto) --}}
                        <div class="mb-3 d-none" id="wrapper-gastrobar">
                            <label class="form-label fw-semibold text-dark small">
                                Gastrobar <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="color:#f59e0b;"><i class="bi bi-cup-straw"></i></span>
                                <select id="gastrobar_id" name="gastrobar_id" disabled
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled selected>Primero elige municipio...</option>
                                </select>
                            </div>
                        </div>

                        {{-- Especialidad dinámica --}}
                        <div id="wrapper-especialidad" class="mb-3 d-none">
                            <label class="form-label fw-semibold small" style="color:#f97316;">
                                <i class="bi bi-egg-fried me-1"></i> Especialidad Gastronómica
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="color:#f97316;"><i class="bi bi-star"></i></span>
                                <input type="text" id="info-especialidad" readonly
                                       placeholder="Especialidad del local seleccionado"
                                       class="form-control border-start-0 ps-0"
                                       style="box-shadow:none;background:#fff7ed;color:#9a3412;font-weight:600;cursor:not-allowed;">
                            </div>
                        </div>

                        {{-- Evento Destacado --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold text-dark small">¿Es un evento destacado?</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-star-fill"></i></span>
                                <select name="is_destacado" id="is_destacado"
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="0" @selected(old('is_destacado') == '0')>Evento Normal</option>
                                    <option value="1" @selected(old('is_destacado') == '1')>Evento Destacado (Banner Principal)</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- ── Columna Derecha: Multimedia ── --}}
                    <div class="col-12 col-md-6">

                        {{-- Imagen Principal --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">
                                Imagen Principal <span class="text-danger">*</span>
                            </label>
                            <label for="imagen" class="w-100" style="cursor:pointer;">
                                <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                     style="min-height:200px;border:2px dashed #dee2e6;transition:border-color 0.2s;"
                                     onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                                    <input type="file" name="imagen" id="imagen" accept="image/*" required
                                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;z-index:10;">
                                    <div id="preview-placeholder" class="text-center">
                                        <i class="bi bi-cloud-upload fs-2 text-secondary"></i>
                                        <p class="small text-muted mt-1 mb-0">Haz clic para seleccionar</p>
                                        <p class="text-secondary mb-0" style="font-size:0.75rem;">JPG, PNG o WEBP (Máx. 2MB)</p>
                                    </div>
                                    <img id="image-preview" src="" alt=""
                                         class="d-none position-absolute top-0 start-0 w-100 h-100"
                                         style="object-fit:cover;">
                                </div>
                            </label>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold text-dark small">
                                Descripción del Evento <span class="text-danger">*</span>
                            </label>
                            <textarea name="descripcion" rows="6" required maxlength="500"
                                      class="form-control bg-light" style="box-shadow:none;resize:none;"
                                      placeholder="Cuéntanos más sobre el evento...">{{ old('descripcion') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 2: Galería de Imágenes               --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                         style="width:40px;height:40px;background:rgba(168,85,247,0.1);border:1px solid rgba(168,85,247,0.2);">
                        <i class="bi bi-images" style="color:#a855f7;"></i>
                    </div>
                    <div>
                        <p class="fw-bold text-dark mb-0 small">Galería de Imágenes Destacadas</p>
                        <p class="text-muted mb-0" style="font-size:0.75rem;">Agrega fotos adicionales del evento (opcional, máx. 6 imágenes).</p>
                    </div>
                </div>

                <div class="row g-3" id="galeria-grid">
                    @for($i = 0; $i < 6; $i++)
                    <div class="col-6 col-sm-4 col-md-2 galeria-slot">
                        <label class="w-100" style="cursor:pointer;">
                            <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                 style="aspect-ratio:1/1;border:2px dashed #dee2e6;transition:border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#a855f7'" onmouseout="this.style.borderColor='#dee2e6'">
                                <input type="file" name="galeria[]" accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0 galeria-input" style="cursor:pointer;z-index:10;">
                                <div class="galeria-placeholder text-center">
                                    <i class="bi bi-plus-lg fs-5 text-secondary"></i>
                                    <p class="mb-0 text-muted" style="font-size:0.7rem;">Foto {{ $i + 1 }}</p>
                                </div>
                                <img class="galeria-preview d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                            </div>
                        </label>
                        <button type="button"
                                class="galeria-clear d-none btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center p-0"
                                style="width:24px;height:24px;font-size:0.65rem;z-index:20;margin:6px;">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    @endfor
                </div>

                <p class="small text-muted mt-3 mb-0">
                    <i class="bi bi-info-circle text-primary me-1"></i>
                    Las imágenes de galería se guardarán al publicar el evento.
                </p>
            </div>
        </div>

        {{-- ── Botones de Control ── --}}
        <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
            <a href="{{ route('eventos.index') }}"
               class="text-muted text-decoration-none small fw-medium">
                <i class="bi bi-chevron-left me-1"></i> Volver al listado
            </a>
            <button type="submit" id="btn-submit"
                    class="btn btn-warning fw-semibold px-5 py-2 rounded-pill shadow-sm text-dark">
                <i class="bi bi-megaphone me-1"></i> Publicar Evento
            </button>
        </div>

    </form>
</div>

<style>
    .galeria-slot { position: relative; }
    .tipo-btn { transition: all 0.2s; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Preview imagen principal ──
    const inputImg    = document.getElementById('imagen');
    const previewImg  = document.getElementById('image-preview');
    const placeholder = document.getElementById('preview-placeholder');

    inputImg.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            previewImg.classList.remove('d-none');
            placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });

    // ── Galería: preview por slot ──
    document.querySelectorAll('.galeria-slot').forEach(slot => {
        const fileInput      = slot.querySelector('.galeria-input');
        const imgPreview     = slot.querySelector('.galeria-preview');
        const imgPlaceholder = slot.querySelector('.galeria-placeholder');
        const clearBtn       = slot.querySelector('.galeria-clear');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                imgPreview.src = e.target.result;
                imgPreview.classList.remove('d-none');
                imgPlaceholder.classList.add('d-none');
                clearBtn.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });

        clearBtn.addEventListener('click', function () {
            fileInput.value = '';
            imgPreview.src  = '';
            imgPreview.classList.add('d-none');
            imgPlaceholder.classList.remove('d-none');
            clearBtn.classList.add('d-none');
        });
    });

    // ─────────────────────────────────────────────
    // REFERENCIAS DOM
    // ─────────────────────────────────────────────
    const tipoInput      = document.getElementById('tipo_establecimiento');
    const btnRestaurante = document.getElementById('btn-tipo-restaurante');
    const btnGastrobar   = document.getElementById('btn-tipo-gastrobar');
    const wrapRest       = document.getElementById('wrapper-restaurante');
    const wrapGastro     = document.getElementById('wrapper-gastrobar');
    const restSelect     = document.getElementById('restaurante_id');
    const gastroSelect   = document.getElementById('gastrobar_id');
    const depSelect      = document.getElementById('departamento_id');
    const muniSelect     = document.getElementById('municipio_id');
    const wrapEspec      = document.getElementById('wrapper-especialidad');
    const inputEspec     = document.getElementById('info-especialidad');

    // ─────────────────────────────────────────────
    // CAMBIO DE TIPO: Restaurante / Gastrobar
    // ─────────────────────────────────────────────
    window.setTipo = function (tipo) {
        tipoInput.value = tipo;
        const muniElegido = muniSelect.value && !muniSelect.disabled;

        if (tipo === 'restaurante') {
            btnRestaurante.className = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-primary';
            btnGastrobar.className   = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-outline-warning';
            wrapRest.classList.remove('d-none');
            wrapGastro.classList.add('d-none');
            restSelect.name    = 'restaurante_id';
            gastroSelect.name  = '';
            restSelect.disabled   = !muniElegido;
            gastroSelect.disabled = true;
        } else {
            btnGastrobar.className   = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-warning text-dark';
            btnRestaurante.className = 'btn btn-sm rounded-pill fw-semibold px-3 flex-fill tipo-btn btn-outline-primary';
            wrapGastro.classList.remove('d-none');
            wrapRest.classList.add('d-none');
            gastroSelect.name = 'gastrobar_id';
            restSelect.name   = '';
            gastroSelect.disabled = !muniElegido;
            restSelect.disabled   = true;
        }

        // Limpiar especialidad al cambiar tipo
        wrapEspec.classList.add('d-none');
        inputEspec.value = '';
    };

    // Inicializar con valor guardado (old) o defecto
    setTipo(tipoInput.value || 'restaurante');

    // ─────────────────────────────────────────────
    // ENCADENAMIENTO GEOGRÁFICO
    // ─────────────────────────────────────────────
    depSelect.addEventListener('change', function () {
        const depId = this.value;
        muniSelect.disabled = true;
        muniSelect.innerHTML = '<option value="">Cargando municipios...</option>';
        restSelect.disabled = true;
        restSelect.innerHTML = '<option value="" disabled selected>Primero elige municipio...</option>';
        gastroSelect.disabled = true;
        gastroSelect.innerHTML = '<option value="" disabled selected>Primero elige municipio...</option>';
        wrapEspec.classList.add('d-none');

        if (!depId) return;

        fetch(`/api/departamentos/${depId}/municipios`)
            .then(r => r.json())
            .then(data => {
                muniSelect.innerHTML = '<option value="" disabled selected>Seleccionar municipio...</option>';
                if (data.length > 0) {
                    data.forEach(muni => {
                        const opt = document.createElement('option');
                        opt.value = muni.id;
                        opt.textContent = muni.nombre;
                        muniSelect.appendChild(opt);
                    });
                    muniSelect.disabled = false;
                } else {
                    muniSelect.innerHTML = '<option value="">Sin municipios registrados</option>';
                }
            })
            .catch(e => console.error(e));
    });

    muniSelect.addEventListener('change', function () {
        const muniId = this.value;
        const tipo   = tipoInput.value;

        restSelect.disabled = true;
        restSelect.innerHTML = '<option value="" disabled selected>Cargando...</option>';
        gastroSelect.disabled = true;
        gastroSelect.innerHTML = '<option value="" disabled selected>Cargando...</option>';
        wrapEspec.classList.add('d-none');

        if (!muniId) return;

        // Cargar restaurantes
        fetch(`/api/municipios/${muniId}/restaurantes`)
            .then(r => r.json())
            .then(data => {
                restSelect.innerHTML = '<option value="" disabled selected>Seleccionar restaurante...</option>';
                data.forEach(rest => {
                    const opt = document.createElement('option');
                    opt.value = rest.id;
                    opt.textContent = rest.nombre;
                    opt.setAttribute('data-especialidad', rest.especialidad || 'Variada / Comida General');
                    restSelect.appendChild(opt);
                });
                if (tipo === 'restaurante') restSelect.disabled = false;
            })
            .catch(e => console.error(e));

        // Cargar gastrobares
        fetch(`/api/municipios/${muniId}/gastrobares`)
            .then(r => r.json())
            .then(data => {
                gastroSelect.innerHTML = '<option value="" disabled selected>Seleccionar gastrobar...</option>';
                data.forEach(gb => {
                    const opt = document.createElement('option');
                    opt.value = gb.id;
                    opt.textContent = gb.nombre;
                    opt.setAttribute('data-especialidad', gb.tipo_bar || 'Bar');
                    gastroSelect.appendChild(opt);
                });
                if (tipo === 'gastrobar') gastroSelect.disabled = false;
            })
            .catch(e => console.error(e));
    });

    // Especialidad al elegir restaurante
    restSelect.addEventListener('change', function () {
        const sel = this.options[this.selectedIndex];
        const esp = sel.getAttribute('data-especialidad');
        if (this.value && esp) {
            inputEspec.value = esp;
            wrapEspec.classList.remove('d-none');
        } else {
            wrapEspec.classList.add('d-none');
        }
    });

    // Especialidad al elegir gastrobar
    gastroSelect.addEventListener('change', function () {
        const sel = this.options[this.selectedIndex];
        const esp = sel.getAttribute('data-especialidad');
        if (this.value && esp) {
            inputEspec.value = esp;
            wrapEspec.classList.remove('d-none');
        } else {
            wrapEspec.classList.add('d-none');
        }
    });

    // ── Habilitar selects deshabilitados antes de enviar ──
    document.getElementById('form-evento').addEventListener('submit', function () {
        muniSelect.disabled = false;
        const tipo = tipoInput.value;
        if (tipo === 'restaurante') {
            restSelect.disabled = false;
        } else {
            gastroSelect.disabled = false;
        }
        const btn = document.getElementById('btn-submit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Publicando...';
        }
    });

});
</script>

@endsection
