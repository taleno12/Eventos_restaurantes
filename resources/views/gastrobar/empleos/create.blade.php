@extends('gastrobar.layout')
@section('title', 'Nueva Oferta de Empleo')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#2d3748;">
                <i class="bi bi-briefcase me-2" style="color:var(--primary);"></i> Nueva Oferta de Empleo
            </h1>
            <p class="text-muted mb-0 small">Publica una vacante para {{ $gastrobar->nombre }}</p>
        </div>
        <a href="{{ route('gastrobar.empleos.index') }}" class="btn-secondary-panel">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if($errors->any())
    <div class="panel-alert panel-alert-error mb-4">
        <i class="bi bi-exclamation-circle-fill fs-5"></i>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('gastrobar.empleos.store') }}">
        @csrf

        <div class="row g-4 align-items-start">

            {{-- Columna izquierda --}}
            <div class="col-12 col-lg-8 d-flex flex-column gap-4">

                <div class="panel-card">
                    <div class="card-header"><i class="bi bi-info-circle me-1"></i> Información del puesto</div>
                    <div class="card-body d-flex flex-column gap-3">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Título del puesto *</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ej: Bartender, DJ, Mesero..."
                                   value="{{ old('titulo') }}" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Descripción del puesto *</label>
                            <textarea name="descripcion" class="form-control" rows="4"
                                      placeholder="Describe las responsabilidades y funciones...">{{ old('descripcion') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                Requisitos <span class="text-muted fw-normal">(opcional)</span>
                            </label>
                            <textarea name="requisitos" class="form-control" rows="3"
                                      placeholder="Experiencia mínima, habilidades, estudios requeridos...">{{ old('requisitos') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="panel-card">
                    <div class="card-header"><i class="bi bi-file-text me-1"></i> Condiciones</div>
                    <div class="card-body d-flex flex-column gap-3">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;">Tipo de contrato</label>
                                <select name="tipo_contrato" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['Tiempo completo','Medio tiempo','Por horas','Temporal','Freelance'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo_contrato') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;">Salario mensual (C$)</label>
                                <input type="number" name="salario" class="form-control"
                                       placeholder="0" min="0" step="0.01"
                                       value="{{ old('salario') }}">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;">Municipio *</label>
                                <select name="municipio_id" class="form-select" required>
                                    <option value="">Selecciona municipio</option>
                                    @foreach($municipios as $mun)
                                        <option value="{{ $mun->id }}" {{ old('municipio_id', $gastrobar->municipio_id) == $mun->id ? 'selected' : '' }}>
                                            {{ $mun->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;">Fecha límite de aplicación</label>
                                <input type="date" name="fecha_limite" class="form-control"
                                       value="{{ old('fecha_limite') }}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Columna derecha --}}
            <div class="col-12 col-lg-4 d-flex flex-column gap-4">

                <div class="panel-card">
                    <div class="card-header"><i class="bi bi-toggle-on me-1"></i> Estado</div>
                    <div class="card-body">
                        <input type="hidden" name="activo" value="0">
                        <label class="d-flex align-items-center gap-3" style="cursor:pointer;">
                            <input type="checkbox" name="activo" value="1"
                                   class="form-check-input mt-0" style="width:20px;height:20px;"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <div class="fw-bold" style="font-size:13px;">Publicar activo</div>
                                <div class="text-muted" style="font-size:11px;">Visible para los candidatos</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="panel-card">
                    <div class="card-body d-flex flex-column gap-2">
                        <button type="submit" class="btn-primary-panel w-100 justify-content-center">
                            <i class="bi bi-send"></i> Publicar Oferta
                        </button>
                        <a href="{{ route('gastrobar.empleos.index') }}" class="btn-secondary-panel w-100 justify-content-center">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection
