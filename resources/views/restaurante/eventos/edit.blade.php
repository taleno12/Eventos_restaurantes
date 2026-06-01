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
    <div class="panel-alert panel-alert-error">
        <i class="bi bi-exclamation-circle-fill fs-5"></i>
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('restaurante.eventos.update', $evento) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="panel-card">
                <div class="card-header">Información del evento</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Título del evento *</label>
                        <input type="text" name="titulo" class="form-control"
                               placeholder="Ej: Festival de Mariscos 2026"
                               value="{{ old('titulo', $evento->titulo) }}" required>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Descripción</label>
                        <textarea name="descripcion" class="form-control"
                                  placeholder="Describe el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Precio (C$) *</label>
                            <input type="number" name="precio" class="form-control"
                                   placeholder="0" min="0" step="0.01"
                                   value="{{ old('precio', $evento->precio) }}" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Fecha del evento *</label>
                            <input type="date" name="fecha_evento" class="form-control"
                                   value="{{ old('fecha_evento', \Carbon\Carbon::parse($evento->fecha_evento)->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-header">Ubicación</div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Departamento *</label>
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
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Municipio *</label>
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
                <div class="card-header">Galería actual</div>
                <div class="card-body">
                    <div style="display:flex;flex-wrap:wrap;gap:10px;">
                        @foreach($evento->imagenes as $img)
                        <div style="position:relative;width:80px;height:80px;border-radius:10px;overflow:hidden;border:1px solid var(--card-border);">
                            <img src="{{ asset('storage/'.$img->ruta) }}" style="width:100%;height:100%;object-fit:cover;">
                            <form method="POST" action="{{ route('evento.imagenes.destroy', $img) }}"
                                  style="position:absolute;top:4px;right:4px;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('¿Eliminar esta foto?')"
                                        style="background:rgba(220,38,38,0.85);border:none;color:white;width:22px;height:22px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:10px;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Agregar más fotos --}}
            <div class="panel-card">
                <div class="card-header">
                    Agregar fotos a galería
                    <span style="font-weight:500;text-transform:none;letter-spacing:0;color:var(--muted);font-size:11px;">(opcional)</span>
                </div>
                <div class="card-body">
                    <div style="border:2px dashed var(--card-border);border-radius:12px;padding:28px;text-align:center;cursor:pointer;position:relative;transition:border-color 0.2s;"
                         onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--card-border)'">
                        <i class="bi bi-images" style="font-size:24px;color:var(--muted);display:block;margin-bottom:10px;"></i>
                        <p style="font-size:12px;color:var(--muted);">Arrastra fotos aquí o <span style="color:var(--primary);font-weight:600;">haz clic para seleccionar</span></p>
                        <p style="font-size:10px;color:var(--muted);margin-top:4px;">JPG, PNG, WEBP — máx. 2 MB por imagen</p>
                        <input type="file" name="galeria[]" id="galeria-input" multiple accept="image/*"
                               style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    </div>
                    <div id="galeria-preview" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:12px;"></div>
                </div>
            </div>

        </div>

        {{-- Columna derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="panel-card">
                <div class="card-header">Imagen principal</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">

                    @if($evento->imagen)
                    <div>
                        <p style="font-size:11px;color:var(--muted);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Imagen actual</p>
                        <img src="{{ asset('storage/'.$evento->imagen) }}"
                             style="width:100%;border-radius:10px;object-fit:cover;aspect-ratio:16/9;border:1px solid var(--card-border);">
                    </div>
                    @endif

                    <div id="imagen-drop" style="border:2px dashed var(--card-border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;"
                         onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--card-border)'">
                        <img id="imagen-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                        <div id="imagen-placeholder" style="text-align:center;">
                            <i class="bi bi-cloud-upload" style="font-size:24px;color:var(--muted);display:block;margin-bottom:8px;"></i>
                            <p style="font-size:11px;color:var(--muted);">{{ $evento->imagen ? 'Cambiar imagen' : 'Subir imagen' }}</p>
                        </div>
                        <input type="file" name="imagen" id="imagen-input" accept="image/*"
                               style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    </div>
                    <p style="font-size:11px;color:var(--muted);">Deja vacío para mantener la imagen actual</p>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <button type="submit" class="btn-primary-panel" style="width:100%;justify-content:center;padding:12px;">
                        <i class="bi bi-floppy"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('restaurante.eventos.index') }}" class="btn-secondary-panel" style="width:100%;justify-content:center;">
                        Cancelar
                    </a>
                </div>
            </div>

            {{-- Zona de peligro --}}
            <div class="panel-card" style="border-color:#fecaca;">
                <div class="card-header" style="color:#dc2626;border-color:#fecaca;">Zona de peligro</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('restaurante.eventos.destroy', $evento) }}"
                          onsubmit="return confirm('¿Estás seguro de eliminar este evento? Esta acción no se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-panel" style="width:100%;justify-content:center;">
                            <i class="bi bi-trash"></i> Eliminar Evento
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</form>
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

document.getElementById('galeria-input').addEventListener('change', function () {
    const container = document.getElementById('galeria-preview');
    container.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.cssText = 'width:70px;height:70px;border-radius:8px;overflow:hidden;border:1px solid var(--card-border);';
            div.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
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
</script>
@endsection
