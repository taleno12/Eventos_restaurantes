@extends('gastrobar.layout')
@section('title', 'Nuevo Evento')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Nuevo Evento</div>
        <div class="page-sub">Publica un evento para {{ $gastrobar->nombre }}</div>
    </div>
    <a href="{{ route('gastrobar.eventos.index') }}" class="btn-secondary-panel">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
<div class="panel-alert panel-alert-error mb-4">
    <i class="bi bi-exclamation-circle-fill fs-5"></i>
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('gastrobar.eventos.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-4 align-items-start">

        {{-- Columna izquierda --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-4">

            <div class="panel-card">
                <div class="card-header"><i class="bi bi-info-circle me-1"></i> Información del evento</div>
                <div class="card-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Título del evento *</label>
                        <input type="text" name="titulo" class="form-control"
                               placeholder="Ej: Noche de Jazz 2026"
                               value="{{ old('titulo') }}" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4"
                                  placeholder="Describe el evento...">{{ old('descripcion') }}</textarea>
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

            <div class="panel-card">
                <div class="card-header"><i class="bi bi-geo-alt me-1"></i> Ubicación</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Departamento *</label>
                            <select name="departamento_id" id="select-departamento" class="form-select" required>
                                <option value="">Selecciona departamento</option>
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->id }}"
                                        {{ old('departamento_id', $gastrobar->departamento_id) == $dep->id ? 'selected' : '' }}>
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

            <div class="panel-card">
                <div class="card-header">
                    <i class="bi bi-cloud-upload me-1"></i> Galería de Imágenes Destacadas
                    <span class="fw-normal ms-1" style="text-transform:none;font-size:11px;color:var(--muted);">(opcional, máx. 4 imágenes)</span>
                </div>
                <div class="card-body">
                    <p class="small mb-3" style="color:var(--muted);">Agrega fotos adicionales del evento. Las imágenes se guardarán al publicar el evento.</p>
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

        </div>

        {{-- Columna derecha --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            <div class="panel-card">
                <div class="card-header"><i class="bi bi-image me-1"></i> Imagen principal *</div>
                <div class="card-body d-flex flex-column gap-3">
                    <div id="imagen-drop" class="border rounded-3 position-relative d-flex align-items-center justify-content-center flex-column gap-2"
                         style="border-style:dashed !important;border-color:var(--input-border);aspect-ratio:16/9;cursor:pointer;overflow:hidden;">
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

            <div class="panel-card">
                <div class="card-body d-flex flex-column gap-2">
                    <button type="submit" class="btn-primary-panel w-100 justify-content-center">
                        <i class="bi bi-rocket"></i> Publicar Evento
                    </button>
                    <a href="{{ route('gastrobar.eventos.index') }}" class="btn-secondary-panel w-100 justify-content-center">
                        Cancelar
                    </a>
                </div>
            </div>

        </div>
    </div>
</form>

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
const oldMunicipio = "{{ old('municipio_id', $gastrobar->municipio_id ?? '') }}";

function cargarMunicipios(depId, municipioSeleccionado = null) {
    if (!depId) { selectMun.innerHTML = '<option value="">Primero selecciona departamento</option>'; return; }
    fetch(`/mi-gastrobar/api/municipios/${depId}`)
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
