@extends('restaurante.layout')
@section('title', 'Nuevo Evento')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-calendar-plus text-primary me-2"></i> Nuevo Evento
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Publica un evento para {{ $restaurante->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.eventos.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Errores --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2) !important;color:#ef4444;">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-circle-fill fs-5 mt-1"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('restaurante.eventos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4 align-items-start">

            {{-- Columna izquierda --}}
            <div class="col-12 col-lg-8 d-flex flex-column gap-4">

                {{-- Información del evento --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                            <i class="bi bi-info-circle me-1"></i> Información del evento
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Título del evento *</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ej: Festival de Mariscos 2026"
                                   value="{{ old('titulo') }}" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"
                                      placeholder="Describe el evento, qué incluye, a quién va dirigido...">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Precio (C$) *</label>
                                <input type="number" name="precio" class="form-control"
                                       placeholder="0" min="0" step="0.01"
                                       value="{{ old('precio') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Fecha del evento *</label>
                                <input type="date" name="fecha_evento" class="form-control"
                                       value="{{ old('fecha_evento') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ubicación --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                            <i class="bi bi-geo-alt me-1"></i> Ubicación
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Departamento *</label>
                                <select name="departamento_id" id="select-departamento" class="form-select" required>
                                    <option value="">Selecciona departamento</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id }}"
                                            {{ old('departamento_id', $restaurante->departamento_id) == $dep->id ? 'selected' : '' }}>
                                            {{ $dep->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Municipio *</label>
                                <select name="municipio_id" id="select-municipio" class="form-select" required>
                                    <option value="">Primero selecciona departamento</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Galería adicional --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                            <i class="bi bi-cloud-upload me-1"></i> Galería adicional
                            <span class="fw-normal ms-1" style="text-transform:none;font-size:11px;color:var(--muted);">(opcional, máx. 4 imágenes)</span>
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @for ($i = 1; $i <= 4; $i++)
                            <div class="col-6 col-md-3">
                                <div class="galeria-slot" data-slot="{{ $i }}">
                                    <input type="file" name="galeria[]" accept="image/*" class="galeria-slot-input" style="display:none;">
                                    <div class="galeria-slot-box">
                                        <img class="galeria-slot-preview" src="" alt="" style="display:none;">
                                        <button type="button" class="galeria-slot-remove" style="display:none;" title="Quitar">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <div class="galeria-slot-placeholder">
                                            <i class="bi bi-plus-lg"></i>
                                            <span>Foto {{ $i }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

            </div>{{-- fin col izquierda --}}

            {{-- Columna derecha --}}
            <div class="col-12 col-lg-4 d-flex flex-column gap-4">

                {{-- Imagen principal --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                            <i class="bi bi-image me-1"></i> Imagen principal *
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div id="imagen-drop" class="border rounded-3 position-relative d-flex align-items-center justify-content-center flex-column gap-2"
                             style="border-style:dashed !important;border-color:var(--input-border);aspect-ratio:16/9;cursor:pointer;overflow:hidden;transition:border-color 0.2s;">
                            <img id="imagen-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                            <div id="imagen-placeholder" class="text-center px-3">
                                <i class="bi bi-cloud-upload d-block mb-2 fs-3" style="color:var(--muted);"></i>
                                <p class="small mb-0" style="color:var(--muted);">Haz clic para subir<br>imagen principal</p>
                            </div>
                            <input type="file" name="imagen" id="imagen-input" accept="image/*" required
                                   style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                        </div>
                        <p class="mb-0" style="font-size:11px;color:var(--muted);">JPG, PNG, WEBP — máx. 2 MB</p>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-body p-4 d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill py-2">
                            <i class="bi bi-rocket me-1"></i> Publicar Evento
                        </button>
                        <a href="{{ route('restaurante.eventos.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-pill py-2">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>{{-- fin col derecha --}}
        </div>{{-- fin row --}}
    </form>

</div>{{-- fin container --}}

<style>
    #imagen-drop:hover  { border-color: var(--primary) !important; }

    .galeria-slot-box {
        position: relative;
        aspect-ratio: 1/1;
        border: 1px dashed var(--input-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
        transition: border-color .15s ease;
    }
    .galeria-slot-box:hover { border-color: var(--primary); }
    .galeria-slot-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }
    .galeria-slot-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        color: var(--muted);
        font-size: 11px;
        text-align: center;
    }
    .galeria-slot-placeholder i { font-size: 1.1rem; }
    .galeria-slot-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        border: none;
        background: rgba(220,38,38,0.85);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        cursor: pointer;
        z-index: 2;
    }
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
        preview.style.display = 'block';
        document.getElementById('imagen-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});

// Slots individuales de galería (máx. 4)
document.querySelectorAll('.galeria-slot').forEach(slot => {
    const input       = slot.querySelector('.galeria-slot-input');
    const box          = slot.querySelector('.galeria-slot-box');
    const preview      = slot.querySelector('.galeria-slot-preview');
    const placeholder  = slot.querySelector('.galeria-slot-placeholder');
    const removeBtn    = slot.querySelector('.galeria-slot-remove');

    box.addEventListener('click', () => input.click());

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            removeBtn.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    });

    removeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        input.value = '';
        preview.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
        removeBtn.style.display = 'none';
    });
});

const selectDep = document.getElementById('select-departamento');
const selectMun = document.getElementById('select-municipio');
const oldMunicipio = "{{ old('municipio_id', $restaurante->municipio_id ?? '') }}";

function cargarMunicipios(depId, municipioSeleccionado = null) {
    if (!depId) { selectMun.innerHTML = '<option value="">Primero selecciona departamento</option>'; return; }
    fetch(`/mi-restaurante/api/municipios/${depId}`)
        .then(r => r.json())
        .then(data => {
            selectMun.innerHTML = '<option value="">Selecciona municipio</option>';
            data.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nombre;
                if (municipioSeleccionado && m.id == municipioSeleccionado) opt.selected = true;
                selectMun.appendChild(opt);
            });
        });
}

selectDep.addEventListener('change', () => cargarMunicipios(selectDep.value));

if (selectDep.value) cargarMunicipios(selectDep.value, oldMunicipio);
</script>
@endsection
