@extends('restaurante.layout')
@section('title', 'Editar Evento')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Editar Evento</div>
        <div class="page-sub">{{ $evento->titulo }}</div>
    </div>
    <a href="{{ route('restaurante.eventos.index') }}" class="btn-secondary-panel">
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

<form method="POST" action="{{ route('restaurante.eventos.update', $evento) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4 align-items-start">

        {{-- Columna izquierda --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-4">

            <div class="panel-card">
                <div class="card-header"><i class="bi bi-info-circle me-1"></i> Información del evento</div>
                <div class="card-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Título del evento *</label>
                        <input type="text" name="titulo" class="form-control"
                               placeholder="Ej: Festival de Mariscos 2026"
                               value="{{ old('titulo', $evento->titulo) }}" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4"
                                  placeholder="Describe el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Precio (C$) *</label>
                            <input type="number" name="precio" class="form-control"
                                   placeholder="0" min="0" step="0.01"
                                   value="{{ old('precio', $evento->precio) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Fecha del evento *</label>
                            <input type="date" name="fecha_evento" class="form-control"
                                   value="{{ old('fecha_evento', \Carbon\Carbon::parse($evento->fecha_evento)->format('Y-m-d')) }}" required>
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
                                        {{ old('departamento_id', $restaurante->departamento_id) == $dep->id ? 'selected' : '' }}>
                                        {{ $dep->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Municipio *</label>
                            <select name="municipio_id" id="select-municipio" class="form-select" required>
                                @foreach($municipios as $mun)
                                    <option value="{{ $mun->id }}"
                                        {{ old('municipio_id', $evento->municipio_id) == $mun->id ? 'selected' : '' }}>
                                        {{ $mun->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Galería existente --}}
            @if($evento->imagenes->count() > 0)
            <div class="panel-card">
                <div class="card-header"><i class="bi bi-images me-1"></i> Galería actual</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($evento->imagenes as $img)
                        <div style="position:relative;width:80px;height:80px;border-radius:10px;overflow:hidden;border:1px solid var(--card-border);">
                            <img src="{{ asset('storage/'.$img->ruta) }}" style="width:100%;height:100%;object-fit:cover;">
                            <button type="button"
                                    onclick="eliminarImagen({{ $img->id }}, '{{ route('restaurante.evento.imagenes.destroy', $img) }}', this)"
                                    style="position:absolute;top:4px;right:4px;background:rgba(220,38,38,0.85);border:none;color:white;width:22px;height:22px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:10px;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="panel-card">
                <div class="card-header">
                    <i class="bi bi-cloud-upload me-1"></i> Agregar fotos a galería
                    <span class="fw-normal ms-1" style="text-transform:none;font-size:11px;color:var(--muted);">(opcional, máx. 4 imágenes)</span>
                </div>
                <div class="card-body">
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
                <div class="card-header"><i class="bi bi-image me-1"></i> Imagen principal</div>
                <div class="card-body d-flex flex-column gap-3">
                    @if($evento->imagen)
                    <div>
                        <p class="fw-bold mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">Imagen actual</p>
                        <img src="{{ asset('storage/'.$evento->imagen) }}"
                             class="w-100 rounded-3 border"
                             style="object-fit:cover;aspect-ratio:16/9;">
                    </div>
                    @endif
                    <div id="imagen-drop" class="border rounded-3 position-relative d-flex align-items-center justify-content-center flex-column gap-2"
                         style="border-style:dashed !important;border-color:var(--input-border);aspect-ratio:16/9;cursor:pointer;overflow:hidden;">
                        <img id="imagen-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                        <div id="imagen-placeholder" class="text-center px-3">
                            <i class="bi bi-cloud-upload d-block mb-2 fs-4" style="color:var(--muted);"></i>
                            <p class="small mb-0" style="color:var(--muted);">{{ $evento->imagen ? 'Cambiar imagen' : 'Subir imagen' }}</p>
                        </div>
                        <input type="file" name="imagen" id="imagen-input" accept="image/*"
                               style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    </div>
                    <p class="mb-0" style="font-size:11px;color:var(--muted);">Deja vacío para mantener la imagen actual</p>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-body d-flex flex-column gap-2">
                    <button type="submit" class="btn-primary-panel w-100 justify-content-center">
                        <i class="bi bi-floppy"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('restaurante.eventos.index') }}" class="btn-secondary-panel w-100 justify-content-center">
                        Cancelar
                    </a>
                </div>
            </div>

            <div class="panel-card" style="border:1px solid #fecaca !important;">
                <div class="card-header" style="background:#fff5f5;color:#dc2626;border-color:#fecaca !important;">
                    <i class="bi bi-exclamation-triangle me-1"></i> Zona de peligro
                </div>
                <div class="card-body">
                    <p class="small mb-3" style="color:var(--muted);">Esta acción no se puede deshacer.</p>
                    <button type="submit" form="form-delete-evento"
                            class="btn-danger-panel w-100 justify-content-center">
                        <i class="bi bi-trash"></i> Eliminar Evento
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

<form id="form-delete-evento"
      method="POST"
      action="{{ route('restaurante.eventos.destroy', $evento) }}"
      onsubmit="return confirm('¿Estás seguro de eliminar este evento? Esta acción no se puede deshacer.')">
    @csrf @method('DELETE')
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
const currentMunicipio = {{ $evento->municipio_id ?? 'null' }};

selectDep.addEventListener('change', function () {
    const depId = this.value;
    if (!depId) { selectMun.innerHTML = '<option value="">Selecciona municipio</option>'; return; }
    fetch(`/api/departamentos/${depId}/municipios`)
        .then(r => r.json())
        .then(data => {
            selectMun.innerHTML = '<option value="">Selecciona municipio</option>';
            data.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nombre;
                if (m.id == currentMunicipio) opt.selected = true;
                selectMun.appendChild(opt);
            });
        });
});

function eliminarImagen(id, url, btn) {
    if (!confirm('¿Eliminar esta foto?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
