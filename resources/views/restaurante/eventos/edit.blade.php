@extends('restaurante.layout')
@section('title', 'Editar Evento')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Editar Evento</div>
        <div class="page-sub">{{ $evento->titulo }}</div>
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

<form method="POST" action="{{ route('restaurante.eventos.update', $evento) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del evento</div>

                <div class="form-group">
                    <label class="form-label">Título del evento *</label>
                    <input type="text" name="titulo" class="form-control"
                           placeholder="Ej: Festival de Mariscos 2026"
                           value="{{ old('titulo', $evento->titulo) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control"
                              placeholder="Describe el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Precio (C$) *</label>
                        <input type="number" name="precio" class="form-control"
                               placeholder="0" min="0" step="0.01"
                               value="{{ old('precio', $evento->precio) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha del evento *</label>
                        <input type="date" name="fecha_evento" class="form-control"
                               value="{{ old('fecha_evento', \Carbon\Carbon::parse($evento->fecha_evento)->format('Y-m-d')) }}" required>
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

            {{-- Galería existente --}}
            @if($evento->imagenes->count() > 0)
            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Galería actual</div>
                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                    @foreach($evento->imagenes as $img)
                    <div style="position:relative;width:80px;height:80px;border-radius:10px;overflow:hidden;border:1px solid var(--border);">
                        <img src="{{ asset('storage/'.$img->ruta) }}" style="width:100%;height:100%;object-fit:cover;">
                        <form method="POST" action="{{ route('evento.imagenes.destroy', $img) }}"
                              style="position:absolute;top:4px;right:4px;">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('¿Eliminar esta foto?')"
                                    style="background:rgba(0,0,0,0.7);border:none;color:#e74c3c;width:22px;height:22px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:10px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Agregar más fotos a galería --}}
            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Agregar fotos a galería <span style="color:#444;font-weight:600;">(opcional)</span></div>
                <div style="border:2px dashed var(--border);border-radius:12px;padding:28px;text-align:center;cursor:pointer;position:relative;">
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
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Imagen principal</div>

                {{-- Imagen actual --}}
                @if($evento->imagen)
                <div style="margin-bottom:12px;">
                    <p style="font-size:10px;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.1em;">Imagen actual</p>
                    <img src="{{ asset('storage/'.$evento->imagen) }}"
                         style="width:100%;border-radius:10px;object-fit:cover;aspect-ratio:16/9;border:1px solid var(--border);">
                </div>
                @endif

                <div id="imagen-drop" style="border:2px dashed var(--border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;">
                    <img id="imagen-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                    <div id="imagen-placeholder" style="text-align:center;">
                        <i class="fas fa-cloud-upload-alt" style="font-size:24px;color:#333;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:11px;color:var(--muted);">{{ $evento->imagen ? 'Cambiar imagen' : 'Subir imagen' }}</p>
                    </div>
                    <input type="file" name="imagen" id="imagen-input" accept="image/*"
                           style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                </div>
                <p style="font-size:10px;color:#444;margin-top:6px;">Deja vacío para mantener la imagen actual</p>
            </div>

            <div class="card card-body">
                <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('restaurante.eventos.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px;">
                    Cancelar
                </a>
            </div>

            {{-- Zona de peligro --}}
            <div class="card card-body" style="border-color:#e74c3c22;">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:#e74c3c55;margin-bottom:12px;">Zona de peligro</div>
                <form method="POST" action="{{ route('restaurante.eventos.destroy', $evento) }}"
                      onsubmit="return confirm('¿Estás seguro de eliminar este evento? Esta acción no se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                        <i class="fas fa-trash"></i> Eliminar Evento
                    </button>
                </form>
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

// ── Preview galería nueva ─────────────────────────────────────────────────────
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

// ── Recargar municipios al cambiar departamento ───────────────────────────────
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