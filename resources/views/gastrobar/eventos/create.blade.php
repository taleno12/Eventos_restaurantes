@extends('gastrobar.layout')
@section('title', 'Nuevo Evento')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#f8fafc;">
                <i class="bi bi-calendar-plus me-2" style="color:#818cf8;"></i> Nuevo Evento
            </h1>
            <p class="mb-0 small" style="color:#cbd5e1;">Publica un evento para {{ $gastrobar->nombre }}</p>
        </div>
        <a href="{{ route('gastrobar.eventos.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

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

    <form method="POST" action="{{ route('gastrobar.eventos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4 align-items-start">

            {{-- Columna izquierda --}}
            <div class="col-12 col-lg-8 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">
                            <i class="bi bi-info-circle me-1"></i> Información del evento
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Título del evento *</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ej: Noche de Jazz 2026"
                                   value="{{ old('titulo') }}" required
                                   style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"
                                      placeholder="Describe el evento..."
                                      style="background:#0f172a;border-color:#475569;color:#f8fafc;">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Precio (C$) *</label>
                                <input type="number" name="precio" class="form-control"
                                       placeholder="0" min="0" step="0.01"
                                       value="{{ old('precio') }}" required
                                       style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Fecha del evento *</label>
                                <input type="date" name="fecha_evento" class="form-control"
                                       value="{{ old('fecha_evento') }}" required
                                       style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">
                            <i class="bi bi-geo-alt me-1"></i> Ubicación
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Departamento *</label>
                                <select name="departamento_id" id="select-departamento" class="form-select" required
                                        style="background:#0f172a;border-color:#475569;color:#f8fafc;">
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
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Municipio *</label>
                                <select name="municipio_id" id="select-municipio" class="form-select" required
                                        style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                                    <option value="">Primero selecciona departamento</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">
                            <i class="bi bi-cloud-upload me-1"></i> Galería adicional
                            <span class="fw-normal ms-1" style="text-transform:none;font-size:11px;color:#94a3b8;">(opcional)</span>
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="border rounded-3 p-4 text-center position-relative galeria-drop"
                             style="border-style:dashed !important;border-color:#475569;cursor:pointer;background:#0f172a;">
                            <i class="bi bi-images d-block mb-2 fs-4" style="color:#94a3b8;"></i>
                            <p class="small mb-1" style="color:#cbd5e1;">Arrastra fotos aquí o <span style="color:#818cf8;font-weight:600;">haz clic para seleccionar</span></p>
                            <p class="mb-0" style="font-size:11px;color:#94a3b8;">JPG, PNG, WEBP — máx. 2 MB por imagen</p>
                            <input type="file" name="galeria[]" id="galeria-input" multiple accept="image/*"
                                   style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                        </div>
                        <div id="galeria-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                    </div>
                </div>

            </div>

            {{-- Columna derecha --}}
            <div class="col-12 col-lg-4 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">
                            <i class="bi bi-image me-1"></i> Imagen principal *
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div id="imagen-drop" class="border rounded-3 position-relative d-flex align-items-center justify-content-center flex-column gap-2"
                             style="border-style:dashed !important;border-color:#475569;aspect-ratio:16/9;cursor:pointer;overflow:hidden;background:#0f172a;">
                            <img id="imagen-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                            <div id="imagen-placeholder" class="text-center px-3">
                                <i class="bi bi-cloud-upload d-block mb-2 fs-3" style="color:#94a3b8;"></i>
                                <p class="small mb-0" style="color:#cbd5e1;">Haz clic para subir<br>imagen principal</p>
                            </div>
                            <input type="file" name="imagen" id="imagen-input" accept="image/*" required
                                   style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                        </div>
                        <p class="mb-0" style="font-size:11px;color:#94a3b8;">JPG, PNG, WEBP — máx. 2 MB</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-body p-4 d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill py-2">
                            <i class="bi bi-rocket me-1"></i> Publicar Evento
                        </button>
                        <a href="{{ route('gastrobar.eventos.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-pill py-2">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>

</div>

<style>
    .galeria-drop:hover { border-color: #818cf8 !important; }
    #imagen-drop:hover  { border-color: #818cf8 !important; }

    .form-control:focus, .form-select:focus {
        background-color: #0f172a !important;
        border-color: #818cf8 !important;
        color: #f8fafc !important;
        box-shadow: 0 0 0 0.2rem rgba(129, 140, 248, 0.25) !important;
    }

    .form-control::placeholder {
        color: #64748b !important;
    }

    option {
        background-color: #0f172a !important;
        color: #f8fafc !important;
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

document.getElementById('galeria-input').addEventListener('change', function () {
    const container = document.getElementById('galeria-preview');
    container.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.cssText = 'width:70px;height:70px;border-radius:8px;overflow:hidden;border:1px solid #475569;';
            div.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
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
