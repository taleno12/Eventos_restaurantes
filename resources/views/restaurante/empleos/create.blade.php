@extends('restaurante.layout')
@section('title', 'Nueva Oferta de Empleo')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-briefcase text-primary me-2"></i> Nueva Oferta de Empleo
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Publica una vacante para {{ $restaurante->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.empleos.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Errores --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2) !important;color:#ef4444;">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-circle-fill fs-5 mt-1"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('restaurante.empleos.store') }}">
        @csrf

        <div class="row g-4 align-items-start">

            {{-- Columna izquierda --}}
            <div class="col-12 col-lg-8 d-flex flex-column gap-4">

                {{-- Información del puesto --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                            <i class="bi bi-info-circle me-1"></i> Información del puesto
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Título del puesto *</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ej: Mesero, Chef de cocina, Bartender..."
                                   value="{{ old('titulo') }}" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Descripción del puesto *</label>
                            <textarea name="descripcion" class="form-control" rows="4"
                                      placeholder="Describe las responsabilidades y funciones del puesto...">{{ old('descripcion') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">
                                Requisitos <span style="color:var(--muted);font-weight:normal;">(opcional)</span>
                            </label>
                            <textarea name="requisitos" class="form-control" rows="3"
                                      placeholder="Experiencia mínima, habilidades, estudios requeridos...">{{ old('requisitos') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Condiciones --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                            <i class="bi bi-file-text me-1"></i> Condiciones
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Tipo de contrato</label>
                                <select name="tipo_contrato" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['Tiempo completo','Medio tiempo','Por horas','Temporal','Freelance'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo_contrato') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Salario mensual (C$)</label>
                                <input type="number" name="salario" class="form-control"
                                       placeholder="0" min="0" step="0.01"
                                       value="{{ old('salario') }}">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Municipio *</label>
                                <select name="municipio_id" class="form-select" required>
                                    <option value="">Selecciona municipio</option>
                                    @foreach($municipios as $mun)
                                        <option value="{{ $mun->id }}" {{ old('municipio_id', $restaurante->municipio_id) == $mun->id ? 'selected' : '' }}>
                                            {{ $mun->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Fecha límite de aplicación</label>
                                <input type="date" name="fecha_limite" class="form-control"
                                       value="{{ old('fecha_limite') }}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- fin col izquierda --}}

            {{-- Columna derecha --}}
            <div class="col-12 col-lg-4 d-flex flex-column gap-4">

                {{-- Estado --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">
                            <i class="bi bi-toggle-on me-1"></i> Estado
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <input type="hidden" name="activo" value="0">
                        <label class="d-flex align-items-center gap-3" style="cursor:pointer;">
                            <input type="checkbox" name="activo" value="1" id="activo-check"
                                   class="form-check-input mt-0"
                                   style="width:20px;height:20px;"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <div class="fw-bold" style="font-size:13px;color:var(--text);">Publicar activo</div>
                                <div style="font-size:11px;color:var(--muted);">Visible para los candidatos</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-body p-4 d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill py-2">
                            <i class="bi bi-send me-1"></i> Publicar Oferta
                        </button>
                        <a href="{{ route('restaurante.empleos.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-pill py-2">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>{{-- fin col derecha --}}
        </div>{{-- fin row --}}
    </form>

</div>
@endsection
