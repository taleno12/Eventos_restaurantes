@extends('gastrobar.layout')
@section('title', 'Editar Oferta')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#f8fafc;">
                <i class="bi bi-briefcase me-2" style="color:#818cf8;"></i> Editar Oferta
            </h1>
            <p class="mb-0 small" style="color:#cbd5e1;">{{ $empleo->titulo }}</p>
        </div>
        <a href="{{ route('gastrobar.empleos.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

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

    <form method="POST" action="{{ route('gastrobar.empleos.update', $empleo) }}">
        @csrf @method('PUT')

        <div class="row g-4 align-items-start">

            {{-- Columna izquierda --}}
            <div class="col-12 col-lg-8 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">
                            <i class="bi bi-info-circle me-1"></i> Información del puesto
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Título del puesto *</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ej: Bartender, DJ, Mesero..."
                                   value="{{ old('titulo', $empleo->titulo) }}" required
                                   style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Descripción del puesto *</label>
                            <textarea name="descripcion" class="form-control" rows="4"
                                      placeholder="Describe las responsabilidades..."
                                      style="background:#0f172a;border-color:#475569;color:#f8fafc;">{{ old('descripcion', $empleo->descripcion) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">
                                Requisitos <span class="fw-normal" style="color:#94a3b8;">(opcional)</span>
                            </label>
                            <textarea name="requisitos" class="form-control" rows="3"
                                      placeholder="Experiencia mínima, habilidades..."
                                      style="background:#0f172a;border-color:#475569;color:#f8fafc;">{{ old('requisitos', $empleo->requisitos) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">
                            <i class="bi bi-file-text me-1"></i> Condiciones
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Tipo de contrato</label>
                                <select name="tipo_contrato" class="form-select"
                                        style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['Tiempo completo','Medio tiempo','Por horas','Temporal','Freelance'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo_contrato', $empleo->tipo_contrato) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Salario mensual (C$)</label>
                                <input type="number" name="salario" class="form-control"
                                       placeholder="0" min="0" step="0.01"
                                       value="{{ old('salario', $empleo->salario) }}"
                                       style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Municipio *</label>
                                <select name="municipio_id" class="form-select" required
                                        style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                                    <option value="">Selecciona municipio</option>
                                    @foreach($municipios as $mun)
                                        <option value="{{ $mun->id }}" {{ old('municipio_id', $empleo->municipio_id) == $mun->id ? 'selected' : '' }}>
                                            {{ $mun->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:13px;color:#f8fafc;">Fecha límite de aplicación</label>
                                <input type="date" name="fecha_limite" class="form-control"
                                       value="{{ old('fecha_limite', $empleo->fecha_limite ? \Carbon\Carbon::parse($empleo->fecha_limite)->format('Y-m-d') : '') }}"
                                       style="background:#0f172a;border-color:#475569;color:#f8fafc;">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Columna derecha --}}
            <div class="col-12 col-lg-4 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-header border-bottom py-3 px-4" style="background:#0f172a;border-color:#334155;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">
                            <i class="bi bi-toggle-on me-1"></i> Estado
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <input type="hidden" name="activo" value="0">
                        <label class="d-flex align-items-center gap-3" style="cursor:pointer;padding:8px;border-radius:8px;background:#0f172a;border:1px solid #334155;">
                            <input type="checkbox" name="activo" value="1"
                                   class="form-check-input mt-0" style="width:20px;height:20px;accent-color:#818cf8;"
                                   {{ old('activo', $empleo->activo) ? 'checked' : '' }}>
                            <div>
                                <div class="fw-bold" style="font-size:13px;color:#f8fafc;">Oferta activa</div>
                                <div style="font-size:11px;color:#94a3b8;">Visible para los candidatos</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;">
                    <div class="card-body p-4 d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill py-2">
                            <i class="bi bi-floppy me-1"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('gastrobar.empleos.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-pill py-2">
                            Cancelar
                        </a>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:#1e293b;border:1px solid rgba(239,68,68,0.3) !important;">
                    <div class="card-header border-bottom py-3 px-4" style="background:rgba(239,68,68,0.1);border-color:rgba(239,68,68,0.2) !important;">
                        <span class="fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;color:#ef4444;">
                            <i class="bi bi-exclamation-triangle me-1"></i> Zona de peligro
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <p class="small mb-3" style="color:#cbd5e1;">Esta acción no se puede deshacer.</p>
                        <button type="submit" form="form-delete-empleo" class="btn btn-danger w-100 fw-semibold rounded-pill py-2">
                            <i class="bi bi-trash me-1"></i> Eliminar Oferta
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <form id="form-delete-empleo" method="POST"
          action="{{ route('gastrobar.empleos.destroy', $empleo) }}"
          onsubmit="return confirm('¿Eliminar esta oferta? Esta acción no se puede deshacer.')">
        @csrf @method('DELETE')
    </form>

</div>

<style>
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

    .form-check-input {
        background-color: #334155 !important;
        border-color: #475569 !important;
    }

    .form-check-input:checked {
        background-color: #818cf8 !important;
        border-color: #818cf8 !important;
    }
</style>
@endsection
