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
                <i class="bi bi-plus-circle text-primary me-2"></i> Nueva Oferta de Empleo
            </h1>
            <p class="text-muted small mb-0">Completa los campos para publicar una vacante.</p>
        </div>
    </div>

    <form action="{{ route('admin.empleos.store') }}" method="POST" id="form-empleo">
        @csrf

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

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD: Información de la Vacante           --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">

                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2"
                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-briefcase text-primary"></i> Información de la Vacante
                </h6>

                {{-- Departamento --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Departamento de la Vacante <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                        <select id="select-departamento" name="departamento_id" required
                                class="form-select bg-light border-start-0 ps-0 @error('departamento_id') is-invalid @enderror"
                                style="box-shadow:none;">
                            <option value="">— Seleccionar departamento —</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ old('departamento_id') == $depto->id ? 'selected' : '' }}>
                                    {{ $depto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('departamento_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Municipio + Restaurante --}}
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">
                            Municipio de la Vacante <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                            <select id="select-municipio" name="municipio_id" required disabled
                                    class="form-select bg-light border-start-0 ps-0 @error('municipio_id') is-invalid @enderror"
                                    style="box-shadow:none;">
                                <option value="">— Primero elige departamento —</option>
                            </select>
                            @error('municipio_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">
                            Restaurante <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                            <select id="select-restaurante" name="restaurante_id" required disabled
                                    class="form-select bg-light border-start-0 ps-0 @error('restaurante_id') is-invalid @enderror"
                                    style="box-shadow:none;">
                                <option value="">— Primero elige municipio —</option>
                            </select>
                            @error('restaurante_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <input type="text" name="titulo" value="{{ old('titulo') }}" required
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
                              style="box-shadow:none;resize:none;">{{ old('descripcion') }}</textarea>
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
                              style="box-shadow:none;resize:none;">{{ old('requisitos') }}</textarea>
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
                                    <option value="{{ $tipo }}" {{ old('tipo_contrato') == $tipo ? 'selected' : '' }}>
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
                            <input type="number" name="salario" value="{{ old('salario') }}"
                                   placeholder="Ej: 8000  (vacío = En entrevista)"
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
                            <input type="date" name="fecha_limite" value="{{ old('fecha_limite') }}"
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
                               {{ old('activo', '1') ? 'checked' : '' }}
                               class="d-none" onchange="actualizarToggle(this)">

                        <label for="activo" id="toggle-label"
                               class="d-inline-flex align-items-center gap-3 rounded-3 px-3 py-2 w-100"
                               style="cursor:pointer;user-select:none;border:2px solid;transition:all 0.2s;">
                            {{-- Track --}}
                            <div id="toggle-track"
                                 class="position-relative flex-shrink-0"
                                 style="width:48px;height:26px;border-radius:999px;transition:background 0.2s;">
                                <div id="toggle-thumb"
                                     class="position-absolute"
                                     style="top:3px;width:20px;height:20px;background:white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,0.3);transition:left 0.2s;"></div>
                            </div>
                            {{-- Texto --}}
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

        {{-- ── Botones ── --}}
        <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
            <a href="{{ route('admin.empleos.index') }}"
               class="text-muted text-decoration-none small fw-medium">
                <i class="bi bi-chevron-left me-1"></i> Cancelar
            </a>
            <button type="submit" id="btn-submit"
                    class="btn btn-primary fw-semibold px-5 py-2 rounded-pill shadow-sm">
                <i class="bi bi-send me-1"></i> Publicar Oferta
            </button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
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

        // Inicializar toggle
        actualizarToggle(document.getElementById('activo'));

        // ── Encadenamiento departamento → municipio → restaurante ──
        const deptoSelect = document.getElementById('select-departamento');
        const muniSelect  = document.getElementById('select-municipio');
        const restSelect  = document.getElementById('select-restaurante');

        deptoSelect.addEventListener('change', function () {
            const deptoId = this.value;
            muniSelect.disabled = true;
            muniSelect.innerHTML = '<option value="">Cargando municipios...</option>';
            restSelect.disabled = true;
            restSelect.innerHTML = '<option value="">— Primero elige municipio —</option>';

            if (!deptoId) {
                muniSelect.innerHTML = '<option value="">— Primero elige departamento —</option>';
                return;
            }
            fetch(`/api/departamentos/${deptoId}/municipios`)
                .then(r => r.json())
                .then(data => {
                    muniSelect.innerHTML = '<option value="">— Seleccionar municipio —</option>';
                    if (data.length > 0) {
                        data.forEach(muni => {
                            const opt = document.createElement('option');
                            opt.value = muni.id;
                            opt.textContent = muni.nombre;
                            muniSelect.appendChild(opt);
                        });
                        muniSelect.disabled = false;
                    } else {
                        muniSelect.innerHTML = '<option value="">Sin municipios registrados</option>';
                    }
                })
                .catch(() => { muniSelect.innerHTML = '<option value="">Error de servidor</option>'; });
        });

        muniSelect.addEventListener('change', function () {
            const muniId = this.value;
            restSelect.disabled = true;
            restSelect.innerHTML = '<option value="">Cargando establecimientos...</option>';

            if (!muniId) {
                restSelect.innerHTML = '<option value="">— Primero elige municipio —</option>';
                return;
            }
            fetch(`/api/municipios/${muniId}/restaurantes`)
                .then(r => r.json())
                .then(data => {
                    restSelect.innerHTML = '<option value="">— Selecciona un restaurante —</option>';
                    if (data.length > 0) {
                        data.forEach(rest => {
                            const opt = document.createElement('option');
                            opt.value = rest.id;
                            opt.textContent = rest.nombre;
                            restSelect.appendChild(opt);
                        });
                        restSelect.disabled = false;
                    } else {
                        restSelect.innerHTML = '<option value="">No hay restaurantes en este municipio</option>';
                    }
                })
                .catch(() => { restSelect.innerHTML = '<option value="">Error de servidor</option>'; });
        });

        // ── Spinner submit ──
        document.getElementById('form-empleo').addEventListener('submit', function () {
            muniSelect.disabled = false;
            restSelect.disabled = false;
            const btn = document.getElementById('btn-submit');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Publicando...';
            }
        });
    });
</script>

@endsection
