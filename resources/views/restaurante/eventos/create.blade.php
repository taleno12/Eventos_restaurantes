@extends('restaurante.layout')
@section('title', 'Nuevo Evento')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Nuevo Evento</div>
        <div class="page-sub">Publica un evento para {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.eventos.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:6px 0 0 16px;padding:0;">
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

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del evento</div>

                <div class="form-group">
                    <label class="form-label">Título del evento *</label>
                    <input type="text" name="titulo" class="form-control"
                           placeholder="Ej: Festival de Mariscos 2026"
                           value="{{ old('titulo') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control"
                              placeholder="Describe el evento, qué incluye, a quién va dirigido...">{{ old('descripcion') }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Precio (C$) *</label>
                        <input type="number" name="precio" class="form-control"
                               placeholder="0" min="0" step="0.01"
                               value="{{ old('precio') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha del evento *</label>
                        <input type="date" name="fecha_evento" class="form-control"
                               value="{{ old('fecha_evento') }}" required>
                    </div>
                </div>
            </div>

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Ubicación</div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Departamento *</label>
                        <select name="departamento_id" id="select-departamento" class="form-control" required>
                            <option value="">Selecciona departamento</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}"
                                    {{ old('departamento_id', $restaurante->departamento_id) == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Municipio *</label>
                        <select name="municipio_id" id="select-municipio" class="form-control" required>
                            <option value="">Primero selecciona departamento</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Galería adicional <span style="color:#444;font-weight:600;">(opcional)</span></div>
                <div id="galeria-drop" style="border:2px dashed var(--border);border-radius:12px;padding:28px;text-align:center;cursor:pointer;transition:border-color 0.2s;position:relative;">
                    <i class="fas fa-images" style="font-size:24px;color:#333;margin-bottom:10px;display:block;"></i>
                    <p style="font-size:12px;color:var(--muted);">Arrastra fotos aquí o <span style="color:var(--orange);">haz clic para seleccionar</span></p>
                    <p style="font-size:10px;color:#444;margin-top:4px;">JPG, PNG, WEBP — máx. 2 MB por imagen</p>
                    <input type="file" name="galeria[]" id="galeria-input" multiple accept="image/*"
                           style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                </div>
                <div id="galeria-preview" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:12px;"></div>
            </div>

        </div>

        {{-- Columna derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Imagen principal *</div>

                <div id="imagen-drop" style="border:2px dashed var(--border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;">
                    <img id="imagen-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                    <div id="imagen-placeholder">
                        <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:#333;display:block;text-align:center;margin-bottom:8px;"></i>
                        <p style="font-size:12px;color:var(--muted);text-align:center;">Haz clic para subir<br>imagen principal</p>
                    </div>
                    <input type="file" name="imagen" id="imagen-input" accept="image/*" required
                           style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                </div>
                <p style="font-size:10px;color:#444;margin-top:8px;">JPG, PNG, WEBP — máx. 2 MB</p>
            </div>

            <div class="card card-body">
                <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-rocket"></i> Publicar Evento
                </button>
                <a href="{{ route('restaurante.eventos.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px;">
                    Cancelar
                </a>
            </div>

        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
// ── Preview imagen principal ──────────────────────────────────────────────────
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

// ── Preview galería ───────────────────────────────────────────────────────────
document.getElementById('galeria-input').addEventListener('change', function () {
    const container = document.getElementById('galeria-preview');
    container.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.cssText = 'width:70px;height:70px;border-radius:8px;overflow:hidden;border:1px solid var(--border);';
            div.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

// ── Cargar municipios al cambiar departamento ─────────────────────────────────
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

// Cargar municipios al iniciar si hay departamento preseleccionado
if (selectDep.value) cargarMunicipios(selectDep.value, oldMunicipio);
</script>
@endsection