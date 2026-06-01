@extends('restaurante.layout')
@section('title', 'Nuevo Evento')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Nuevo Evento</div>
        <div class="page-sub">Publica un evento para {{ $restaurante->nombre }}</div>
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

<form method="POST" action="{{ route('restaurante.eventos.store') }}" enctype="multipart/form-data">
    @csrf

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
                               value="{{ old('titulo') }}" required>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Descripción</label>
                        <textarea name="descripcion" class="form-control"
                                  placeholder="Describe el evento, qué incluye, a quién va dirigido...">{{ old('descripcion') }}</textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Precio (C$) *</label>
                            <input type="number" name="precio" class="form-control"
                                   placeholder="0" min="0" step="0.01"
                                   value="{{ old('precio') }}" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Fecha del evento *</label>
                            <input type="date" name="fecha_evento" class="form-control"
                                   value="{{ old('fecha_evento') }}" required>
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
                                <option value="">Primero selecciona departamento</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-header">
                    Galería adicional
                    <span style="font-weight:500;text-transform:none;letter-spacing:0;color:var(--muted);font-size:11px;">(opcional)</span>
                </div>
                <div class="card-body">
                    <div id="galeria-drop" style="border:2px dashed var(--card-border);border-radius:12px;padding:28px;text-align:center;cursor:pointer;transition:border-color 0.2s;position:relative;"
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
                <div class="card-header">Imagen principal *</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">

                    <div id="imagen-drop" style="border:2px dashed var(--card-border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;"
                         onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--card-border)'">
                        <img id="imagen-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                        <div id="imagen-placeholder" style="text-align:center;">
                            <i class="bi bi-cloud-upload" style="font-size:28px;color:var(--muted);display:block;margin-bottom:8px;"></i>
                            <p style="font-size:12px;color:var(--muted);">Haz clic para subir<br>imagen principal</p>
                        </div>
                        <input type="file" name="imagen" id="imagen-input" accept="image/*" required
                               style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    </div>
                    <p style="font-size:11px;color:var(--muted);">JPG, PNG, WEBP — máx. 2 MB</p>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <button type="submit" class="btn-primary-panel" style="width:100%;justify-content:center;padding:12px;">
                        <i class="bi bi-rocket"></i> Publicar Evento
                    </button>
                    <a href="{{ route('restaurante.eventos.index') }}" class="btn-secondary-panel" style="width:100%;justify-content:center;">
                        Cancelar
                    </a>
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
