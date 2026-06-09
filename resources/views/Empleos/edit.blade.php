@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.empleos.index') }}"
           class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
           style="width: 38px; height: 38px;">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-pencil text-primary me-2"></i> Editar Oferta
            </h1>
            <p class="text-muted small mb-0">
                Modificando: <span class="fw-semibold text-dark">{{ $empleo->titulo }}</span>
            </p>
        </div>
    </div>

    {{-- Formulario oculto para eliminar --}}
    <form id="form-eliminar"
          action="{{ route('admin.empleos.destroy', $empleo) }}"
          method="POST"
          onsubmit="return confirm('¿Eliminar esta oferta permanentemente?')"
          style="display: none;">
        @csrf @method('DELETE')
    </form>

    <form action="{{ route('admin.empleos.update', $empleo) }}" method="POST" id="form-empleo">
        @csrf @method('PUT')

        {{-- Errores globales --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                    <div>
                        <p class="fw-semibold mb-1">Corrige los siguientes errores:</p>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">

                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2"
                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-briefcase text-primary"></i> Información de la Vacante
                </h6>

                {{-- ── Tipo de establecimiento ── --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark small">
                        Tipo de establecimiento <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex gap-2">
                        @php $tipoActual = old('tipo_establecimiento', $empleo->gastrobar_id ? 'gastrobar' : 'restaurante'); @endphp

                        <input type="radio" class="btn-check" name="tipo_establecimiento"
                               id="tipo-restaurante" value="restaurante"
                               {{ $tipoActual === 'restaurante' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary rounded-pill px-4 fw-semibold" for="tipo-restaurante">
                            <i class="bi bi-shop me-1"></i> Restaurante
                        </label>

                        <input type="radio" class="btn-check" name="tipo_establecimiento"
                               id="tipo-gastrobar" value="gastrobar"
                               {{ $tipoActual === 'gastrobar' ? 'checked' : '' }}>
                        <label class="btn btn-outline-warning rounded-pill px-4 fw-semibold" for="tipo-gastrobar">
                            <i class="bi bi-cup-straw me-1"></i> Gastrobar
                        </label>
                    </div>
                </div>

                {{-- Departamento --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Departamento <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                        <select id="select-departamento" name="departamento_id" required
                                class="form-select bg-light border-start-0 ps-0 @error('departamento_id') is-invalid @enderror"
                                style="box-shadow:none;">
                            <option value="">— Seleccionar departamento —</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}" {{ old('departamento_id', $empleo->departamento_id) == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('departamento_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Municipio + Establecimiento --}}
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">
                            Municipio <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                            <select id="select-municipio" name="municipio_id" required
                                    class="form-select bg-light border-start-0 ps-0 @error('municipio_id') is-invalid @enderror"
                                    style="box-shadow:none;">
                                <option value="">— Seleccionar municipio —</option>
                                @foreach($municipios as $mun)
                                    <option value="{{ $mun->id }}" {{ old('municipio_id', $empleo->municipio_id) == $mun->id ? 'selected' : '' }}>
                                        {{ $mun->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('municipio_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        {{-- Campo Restaurante --}}
                        <div id="campo-restaurante">
                            <label class="form-label fw-semibold text-dark small">
                                Restaurante <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                                <select id="select-restaurante" name="restaurante_id"
                                        class="form-select bg-light border-start-0 ps-0 @error('restaurante_id') is-invalid @enderror"
                                        style="box-shadow:none;">
                                    <option value="">— Selecciona un restaurante —</option>
                                    @foreach($restaurantes as $r)
                                        <option value="{{ $r->id }}" {{ old('restaurante_id', $empleo->restaurante_id) == $r->id ? 'selected' : '' }}>
                                            {{ $r->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('restaurante_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Campo Gastrobar --}}
                        <div id="campo-gastrobar" style="display:none;">
                            <label class="form-label fw-semibold text-dark small">
                                Gastrobar <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-cup-straw"></i></span>
                                <select id="select-gastrobar" name="gastrobar_id"
                                        class="form-select bg-light border-start-0 ps-0 @error('gastrobar_id') is-invalid @enderror"
                                        style="box-shadow:none;">
                                    <option value="">— Selecciona un gastrobar —</option>
                                    @foreach($gastrobares as $g)
                                        <option value="{{ $g->id }}" {{ old('gastrobar_id', $empleo->gastrobar_id) == $g->id ? 'selected' : '' }}>
                                            {{ $g->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gastrobar_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Título del puesto --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Título del puesto <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-workspace"></i></span>
                        <input type="text" name="titulo" value="{{ old('titulo', $empleo->titulo) }}" required
                               placeholder="Ej: Mesero, Chef de cocina, Cajero..."
                               class="form-control bg-light border-start-0 ps-0 @error('titulo') is-invalid @enderror"
                               style="box-shadow:none;">
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Descripción del puesto <span class="text-danger">*</span>
                    </label>
                    <textarea name="descripcion" rows="4" required
                              placeholder="Describe las responsabilidades y funciones del puesto..."
                              class="form-control bg-light @error('descripcion') is-invalid @enderror"
                              style="box-shadow:none;resize:none;">{{ old('descripcion', $empleo->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Requisitos --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Requisitos <span class="text-muted fw-normal">(opcional)</span>
                    </label>
                    <textarea name="requisitos" rows="3"
                              placeholder="Experiencia requerida, estudios, habilidades..."
                              class="form-control bg-light"
                              style="box-shadow:none;resize:none;">{{ old('requisitos', $empleo->requisitos) }}</textarea>
                </div>

                {{-- Tipo contrato + Salario --}}
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Tipo de contrato</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-file-earmark-text"></i></span>
                            <select name="tipo_contrato"
                                    class="form-select bg-light border-start-0 ps-0"
                                    style="box-shadow:none;">
                                <option value="">— Seleccionar —</option>
                                @foreach(['Tiempo completo','Medio tiempo','Por horas','Temporal','Fines de semana'] as $tipo)
                                    <option value="{{ $tipo }}" {{ old('tipo_contrato', $empleo->tipo_contrato) == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">
                            Salario mensual (C$) <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-cash-coin"></i></span>
                            <input type="number" name="salario" value="{{ old('salario', $empleo->salario) }}"
                                   placeholder="Dejar vacío = A convenir"
                                   min="0" step="0.01"
                                   class="form-control bg-light border-start-0 ps-0 @error('salario') is-invalid @enderror"
                                   style="box-shadow:none;">
                            @error('salario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Fecha límite + Toggle estado --}}
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">
                            Fecha límite de aplicación <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" name="fecha_limite"
                                   value="{{ old('fecha_limite', $empleo->fecha_limite?->format('Y-m-d')) }}"
                                   class="form-control bg-light border-start-0 ps-0 @error('fecha_limite') is-invalid @enderror"
                                   style="box-shadow:none;">
                            @error('fecha_limite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Toggle estado --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small d-block">Estado de la oferta</label>
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1" id="activo"
                               @if(old('activo', $empleo->activo)) checked @endif
                               class="d-none" onchange="actualizarToggle(this)">

                        <label for="activo" id="toggle-label"
                               class="d-inline-flex align-items-center gap-3 rounded-3 px-3 py-2 w-100"
                               style="cursor:pointer;user-select:none;border:2px solid;transition:all 0.2s;">
                            <div id="toggle-track"
                                 class="position-relative flex-shrink-0"
                                 style="width:48px;height:26px;border-radius:999px;transition:background 0.2s;">
                                <div id="toggle-thumb"
                                     class="position-absolute"
                                     style="top:3px;width:20px;height:20px;background:white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,0.3);transition:left 0.2s;"></div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i id="toggle-icon" class="bi" style="font-size:1rem;"></i>
                                <div>
                                    <div id="toggle-texto" class="fw-bold" style="font-size:0.875rem;line-height:1.2;"></div>
                                    <div id="toggle-subtexto" style="font-size:0.72rem;line-height:1.2;opacity:0.75;"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        {{-- Meta info --}}
        <div class="text-muted small mb-4 px-1 d-flex align-items-center gap-2">
            <i class="bi bi-info-circle"></i>
            Creada el {{ $empleo->created_at->format('d M Y \a \l\a\s H:i') }}
            &middot; Última actualización: {{ $empleo->updated_at->diffForHumans() }}
        </div>

        {{-- ── Botones ── --}}
        <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
            <button type="button"
                    onclick="document.getElementById('form-eliminar').submit()"
                    class="btn btn-link text-danger text-decoration-none fw-semibold small p-0">
                <i class="bi bi-trash me-1"></i> Eliminar oferta
            </button>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.empleos.index') }}"
                   class="btn btn-light border fw-semibold small px-4">
                    Cancelar
                </a>
                <button type="submit" id="btn-submit"
                        class="btn btn-primary fw-semibold px-5 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar cambios
                </button>
            </div>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Valores actuales del empleo para precargar
    const empleoActual = {
        tipo:          '{{ $empleo->gastrobar_id ? "gastrobar" : "restaurante" }}',
        departamento:  '{{ $empleo->departamento_id }}',
        municipio:     '{{ $empleo->municipio_id }}',
        restaurante:   '{{ $empleo->restaurante_id }}',
        gastrobar:     '{{ $empleo->gastrobar_id }}',
    };

    // ── Toggle visual ──
    function actualizarToggle(checkbox) {
        const label    = document.getElementById('toggle-label');
        const track    = document.getElementById('toggle-track');
        const thumb    = document.getElementById('toggle-thumb');
        const icon     = document.getElementById('toggle-icon');
        const texto    = document.getElementById('toggle-texto');
        const subtexto = document.getElementById('toggle-subtexto');

        if (checkbox.checked) {
            label.style.borderColor     = '#2563eb';
            label.style.backgroundColor = '#eff6ff';
            track.style.backgroundColor = '#2563eb';
            thumb.style.left            = '25px';
            icon.className              = 'bi bi-check-circle-fill';
            icon.style.color            = '#2563eb';
            texto.textContent           = 'Oferta Activa';
            texto.style.color           = '#1d4ed8';
            subtexto.textContent        = 'Visible para los postulantes';
            subtexto.style.color        = '#3b82f6';
        } else {
            label.style.borderColor     = '#d1d5db';
            label.style.backgroundColor = '#f9fafb';
            track.style.backgroundColor = '#9ca3af';
            thumb.style.left            = '3px';
            icon.className              = 'bi bi-x-circle-fill';
            icon.style.color            = '#6b7280';
            texto.textContent           = 'Oferta Inactiva';
            texto.style.color           = '#374151';
            subtexto.textContent        = 'No visible para postulantes';
            subtexto.style.color        = '#9ca3af';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        actualizarToggle(document.getElementById('activo'));

        const deptoSelect  = document.getElementById('select-departamento');
        const muniSelect   = document.getElementById('select-municipio');
        const restSelect   = document.getElementById('select-restaurante');
        const gastroSelect = document.getElementById('select-gastrobar');

        // ── Mostrar campo correcto según tipo actual ──
        function mostrarCampoEstablecimiento(tipo) {
            const esGastrobar = tipo === 'gastrobar';
            document.getElementById('campo-restaurante').style.display = esGastrobar ? 'none' : 'block';
            document.getElementById('campo-gastrobar').style.display   = esGastrobar ? 'block' : 'none';
        }

        // Inicializar visibilidad
        mostrarCampoEstablecimiento(empleoActual.tipo);

        // ── Cambio de tipo ──
        document.querySelectorAll('input[name="tipo_establecimiento"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                mostrarCampoEstablecimiento(this.value);
            });
        });

        // ── Departamento → Municipios ──
        deptoSelect.addEventListener('change', function () {
            const deptoId = this.value;
            muniSelect.innerHTML = '<option value="">Cargando municipios...</option>';
            restSelect.innerHTML = '<option value="">— Primero elige municipio —</option>';
            gastroSelect.innerHTML = '<option value="">— Primero elige municipio —</option>';

            if (!deptoId) {
                muniSelect.innerHTML = '<option value="">— Primero elige departamento —</option>';
                return;
            }

            fetch(`/api/departamentos/${deptoId}/municipios`)
                .then(r => r.json())
                .then(data => {
                    muniSelect.innerHTML = '<option value="">— Seleccionar municipio —</option>';
                    data.forEach(mun => {
                        const opt = document.createElement('option');
                        opt.value = mun.id;
                        opt.textContent = mun.nombre;
                        muniSelect.appendChild(opt);
                    });
                })
                .catch(() => { muniSelect.innerHTML = '<option value="">Error de servidor</option>'; });
        });

        // ── Municipio → Restaurantes o Gastrobares ──
        muniSelect.addEventListener('change', function () {
            const muniId = this.value;
            const esGastrobar = document.querySelector('input[name="tipo_establecimiento"]:checked').value === 'gastrobar';

            if (!muniId) return;

            cargarEstablecimientos(muniId, esGastrobar);
        });

        function cargarEstablecimientos(muniId, esGastrobar, preselect = null) {
            const url          = esGastrobar ? `/api/municipios/${muniId}/gastrobares` : `/api/municipios/${muniId}/restaurantes`;
            const targetSelect = esGastrobar ? gastroSelect : restSelect;
            const placeholder  = esGastrobar ? 'gastrobar' : 'restaurante';

            targetSelect.innerHTML = `<option value="">Cargando...</option>`;

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    targetSelect.innerHTML = `<option value="">— Selecciona un ${placeholder} —</option>`;
                    data.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.nombre;
                        if (preselect && item.id == preselect) opt.selected = true;
                        targetSelect.appendChild(opt);
                    });
                })
                .catch(() => { targetSelect.innerHTML = '<option value="">Error de servidor</option>'; });
        }

        // ── Spinner al guardar ──
        document.getElementById('form-empleo').addEventListener('submit', function () {
            const btn = document.getElementById('btn-submit');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
            }
        });
    });
</script>

@endsection
