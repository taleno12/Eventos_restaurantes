@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('trabajadores.index') }}"
           class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
           style="width: 38px; height: 38px;">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Trabajador
            </h1>
            <p class="text-muted small mb-0">Actualizando datos de <strong>{{ $trabajador->nombre_completo }}</strong></p>
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

    <form id="form-trabajador" action="{{ route('trabajadores.update', $trabajador->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 1: Datos Personales                  --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-person text-warning"></i> Datos Personales
                </h6>

                <div class="row g-4">

                    {{-- Nombre --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="nombre" value="{{ old('nombre', $trabajador->nombre) }}" required maxlength="100"
                                   placeholder="Nombre del trabajador"
                                   class="form-control bg-light border-start-0 ps-0 @error('nombre') is-invalid @enderror" style="box-shadow:none;">
                        </div>
                        @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Apellido --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Apellido <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="apellido" value="{{ old('apellido', $trabajador->apellido) }}" required maxlength="100"
                                   placeholder="Apellido del trabajador"
                                   class="form-control bg-light border-start-0 ps-0 @error('apellido') is-invalid @enderror" style="box-shadow:none;">
                        </div>
                        @error('apellido')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Cédula --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Cédula <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-card-text"></i></span>
                            <input type="text" name="cedula" value="{{ old('cedula', $trabajador->cedula) }}" required maxlength="20"
                                   placeholder="Ej: 001-010190-0001A"
                                   class="form-control bg-light border-start-0 ps-0 @error('cedula') is-invalid @enderror" style="box-shadow:none;">
                        </div>
                        @error('cedula')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Correo Electrónico <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email', $trabajador->email) }}" required maxlength="150"
                                   placeholder="correo@ejemplo.com"
                                   class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" style="box-shadow:none;">
                        </div>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Teléfono <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#25d366;"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="telefono" value="{{ old('telefono', $trabajador->telefono) }}" maxlength="20"
                                   placeholder="+505 8888-8888"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    {{-- Fecha de Ingreso --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Fecha de Ingreso <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" name="fecha_ingreso"
                                   value="{{ old('fecha_ingreso', $trabajador->fecha_ingreso?->format('Y-m-d')) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 2: Datos Laborales                   --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-briefcase text-warning"></i> Datos Laborales
                </h6>

                <div class="row g-4">

                    {{-- Cargo --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Cargo <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-briefcase"></i></span>
                            <input type="text" name="cargo" value="{{ old('cargo', $trabajador->cargo) }}" maxlength="100"
                                   placeholder="Ej: Mesero, Chef, Administrador..."
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    {{-- Salario --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Salario <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted fw-bold">C$</span>
                            <input type="number" name="salario" value="{{ old('salario', $trabajador->salario) }}" min="0" step="0.01"
                                   placeholder="0.00"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Estado <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-toggle-on"></i></span>
                            <select name="estado" required class="form-select bg-light border-start-0 ps-0" style="box-shadow:none; cursor:pointer;">
                                <option value="activo"   @selected(old('estado', $trabajador->estado) == 'activo')>Activo</option>
                                <option value="inactivo" @selected(old('estado', $trabajador->estado) == 'inactivo')>Inactivo</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 3: Asignación de Departamentos       --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-geo-alt text-warning"></i> Asignación de Departamentos <span class="text-danger">*</span>
                </h6>
                <p class="text-muted small mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Selecciona uno o más departamentos. Al marcarlos, se cargarán automáticamente los restaurantes y gastrobares disponibles.
                </p>

                <div class="row g-3 mb-4">
                    @foreach($departamentos as $depto)
                    @php
                        $asignados = old('departamentos', $departamentosAsignados);
                        $checked   = in_array($depto->id, $asignados);
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="form-check border rounded-3 p-3 bg-light depto-check-card {{ $checked ? 'border-warning' : '' }}"
                             style="cursor:pointer; transition: border-color 0.2s; {{ $checked ? 'background-color:#fff8e1 !important;' : '' }}">
                            <input class="form-check-input depto-checkbox" type="checkbox"
                                   name="departamentos[]"
                                   value="{{ $depto->id }}"
                                   id="depto_{{ $depto->id }}"
                                   {{ $checked ? 'checked' : '' }}
                                   style="accent-color:#ffc107; cursor:pointer;">
                            <label class="form-check-label fw-semibold text-dark w-100" for="depto_{{ $depto->id }}" style="cursor:pointer;">
                                <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 0.8rem;"></i>
                                {{ $depto->nombre }}
                                <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;">
                                    {{ $depto->restaurantes_count }} rest.
                                </span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>

                @error('departamentos')
                    <div class="text-danger small mb-3">{{ $message }}</div>
                @enderror

                {{-- Restaurantes cargados dinámicamente --}}
                <div id="restaurantes-container" class="d-none mb-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-shop text-warning"></i> Restaurantes en los departamentos seleccionados
                    </h6>
                    <div id="restaurantes-lista" class="row g-2"></div>
                </div>

                <div id="restaurantes-empty" class="d-none mb-3">
                    <p class="text-muted small fst-italic">
                        <i class="bi bi-info-circle me-1"></i>
                        No hay restaurantes registrados en los departamentos seleccionados.
                    </p>
                </div>

                {{-- Gastrobares cargados dinámicamente --}}
                <div id="gastrobares-container" class="d-none mb-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-cup-straw text-warning"></i> Gastrobares en los departamentos seleccionados
                    </h6>
                    <div id="gastrobares-lista" class="row g-2"></div>
                </div>

                <div id="gastrobares-empty" class="d-none mb-3">
                    <p class="text-muted small fst-italic">
                        <i class="bi bi-info-circle me-1"></i>
                        No hay gastrobares registrados en los departamentos seleccionados.
                    </p>
                </div>

                <div id="establecimientos-loading" class="d-none">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <span class="spinner-border spinner-border-sm text-warning"></span> Cargando establecimientos...
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 4: Foto del Trabajador               --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-image text-warning"></i> Foto del Trabajador <span class="text-muted fw-normal text-capitalize">(opcional)</span>
                </h6>

                <label for="foto" class="w-100 cursor-pointer">
                    <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                         style="min-height: 180px; border: 2px dashed #dee2e6; cursor: pointer; transition: border-color 0.2s;"
                         onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                        <input type="file" name="foto" id="foto" accept="image/*"
                               class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer; z-index:10;">

                        @if($trabajador->foto)
                            <div id="preview-container" class="d-none text-center py-3">
                                <i class="bi bi-person-circle fs-2 text-secondary"></i>
                                <p class="small text-muted mt-1 mb-0">Haz clic para cambiar la foto</p>
                            </div>
                            <img id="image-preview" src="{{ asset('storage/' . $trabajador->foto) }}" alt="Foto actual"
                                 class="position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                        @else
                            <div id="preview-container" class="text-center py-3">
                                <i class="bi bi-person-circle fs-2 text-secondary"></i>
                                <p class="small text-muted mt-1 mb-0">Haz clic para seleccionar</p>
                                <p class="small text-secondary" style="font-size:0.75rem;">JPG, PNG o WEBP — Máx. 2MB</p>
                            </div>
                            <img id="image-preview" src="" alt=""
                                 class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                        @endif
                    </div>
                </label>

                @if($trabajador->foto)
                    <p class="text-muted small mt-2 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Selecciona una nueva imagen solo si deseas reemplazar la actual.
                    </p>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 5: Observaciones                     --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-text-paragraph text-warning"></i> Observaciones <span class="text-muted fw-normal text-capitalize">(opcional)</span>
                </h6>
                <textarea name="observaciones" rows="3"
                          class="form-control bg-light" style="box-shadow:none; resize:none;"
                          placeholder="Notas internas sobre el trabajador...">{{ old('observaciones', $trabajador->observaciones) }}</textarea>
            </div>
        </div>

        {{-- ── Botones de Control ── --}}
        <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
            <a href="{{ route('trabajadores.show', $trabajador->id) }}"
               class="text-muted text-decoration-none small fw-medium">
                <i class="bi bi-chevron-left me-1"></i> Volver al detalle
            </a>
            <button type="submit" id="btn-submit"
                    class="btn btn-warning fw-semibold px-5 py-2 rounded-pill shadow-sm text-dark">
                <i class="bi bi-check2-circle me-1"></i> Actualizar Trabajador
            </button>
        </div>

    </form>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .depto-check-card:has(.depto-checkbox:checked) {
        border-color: #ffc107 !important;
        background-color: #fff8e1 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Preview Foto ──
    document.getElementById('foto').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview     = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-container');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });

    // ── AJAX: Cargar restaurantes y gastrobares ──
    const checkboxes      = document.querySelectorAll('.depto-checkbox');
    const loadingMsg      = document.getElementById('establecimientos-loading');

    const restContainer   = document.getElementById('restaurantes-container');
    const restLista       = document.getElementById('restaurantes-lista');
    const restEmpty       = document.getElementById('restaurantes-empty');

    const gastroContainer = document.getElementById('gastrobares-container');
    const gastroLista     = document.getElementById('gastrobares-lista');
    const gastroEmpty     = document.getElementById('gastrobares-empty');

    function getDeptoSeleccionados() {
        return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
    }

    function cargarEstablecimientos() {
        const ids = getDeptoSeleccionados();

        restContainer.classList.add('d-none');
        restEmpty.classList.add('d-none');
        gastroContainer.classList.add('d-none');
        gastroEmpty.classList.add('d-none');

        if (ids.length === 0) return;

        loadingMsg.classList.remove('d-none');

        const csrfToken = '{{ csrf_token() }}';

        const fetchRestaurantes = fetch('{{ route("trabajadores.restaurantes.por.departamentos") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ departamentos: ids })
        }).then(r => r.json());

        const fetchGastrobares = fetch('{{ route("trabajadores.gastrobares.por.departamentos") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ departamentos: ids })
        }).then(r => r.json());

        Promise.all([fetchRestaurantes, fetchGastrobares])
            .then(([restaurantes, gastrobares]) => {
                loadingMsg.classList.add('d-none');

                // Restaurantes
                restLista.innerHTML = '';
                if (restaurantes.length === 0) {
                    restEmpty.classList.remove('d-none');
                } else {
                    restaurantes.forEach(rest => {
                        restLista.innerHTML += `
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="d-flex align-items-center gap-2 border rounded-3 p-2 bg-light">
                                    <i class="bi bi-shop text-warning fs-5"></i>
                                    <div>
                                        <span class="fw-semibold text-dark small d-block">${rest.nombre}</span>
                                        <span class="text-muted" style="font-size:0.72rem;">${rest.especialidad ?? ''}</span>
                                    </div>
                                </div>
                            </div>`;
                    });
                    restContainer.classList.remove('d-none');
                }

                // Gastrobares
                gastroLista.innerHTML = '';
                if (gastrobares.length === 0) {
                    gastroEmpty.classList.remove('d-none');
                } else {
                    gastrobares.forEach(gastro => {
                        gastroLista.innerHTML += `
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="d-flex align-items-center gap-2 border rounded-3 p-2 bg-light">
                                    <i class="bi bi-cup-straw text-warning fs-5"></i>
                                    <div>
                                        <span class="fw-semibold text-dark small d-block">${gastro.nombre}</span>
                                        <span class="text-muted" style="font-size:0.72rem;">${gastro.tipo_bar ?? ''}</span>
                                    </div>
                                </div>
                            </div>`;
                    });
                    gastroContainer.classList.remove('d-none');
                }
            })
            .catch(() => {
                loadingMsg.classList.add('d-none');
            });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', cargarEstablecimientos));

    // Cargar al abrir la página con los departamentos ya asignados
    if (getDeptoSeleccionados().length > 0) cargarEstablecimientos();

    // ── Submit: deshabilitar botón para evitar doble envío ──
    document.getElementById('form-trabajador').addEventListener('submit', function () {
        const btn = document.getElementById('btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
    });

});
</script>

@endsection
