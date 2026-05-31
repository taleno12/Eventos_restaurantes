@extends('restaurante.layout')
@section('title', 'Nueva Oferta de Empleo')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Nueva Oferta de Empleo</div>
        <div class="page-sub">Publica una vacante para {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.empleos.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:6px 0 0 16px;padding:0;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('restaurante.empleos.store') }}">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del puesto</div>

                <div class="form-group">
                    <label class="form-label">Título del puesto *</label>
                    <input type="text" name="titulo" class="form-control"
                           placeholder="Ej: Mesero, Chef de cocina, Bartender..."
                           value="{{ old('titulo') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción del puesto *</label>
                    <textarea name="descripcion" class="form-control" style="min-height:120px;"
                              placeholder="Describe las responsabilidades y funciones del puesto...">{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Requisitos <span style="color:#444;font-weight:600;">(opcional)</span></label>
                    <textarea name="requisitos" class="form-control"
                              placeholder="Experiencia mínima, habilidades, estudios requeridos...">{{ old('requisitos') }}</textarea>
                </div>
            </div>

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Condiciones</div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Tipo de contrato</label>
                        <select name="tipo_contrato" class="form-control">
                            <option value="">Seleccionar...</option>
                            @foreach(['Tiempo completo','Medio tiempo','Por horas','Temporal','Freelance'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_contrato') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salario mensual (C$)</label>
                        <input type="number" name="salario" class="form-control"
                               placeholder="0" min="0" step="0.01"
                               value="{{ old('salario') }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Municipio *</label>
                        <select name="municipio_id" class="form-control" required>
                            <option value="">Selecciona municipio</option>
                            @foreach($municipios as $mun)
                                <option value="{{ $mun->id }}" {{ old('municipio_id', $restaurante->municipio_id) == $mun->id ? 'selected' : '' }}>
                                    {{ $mun->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha límite de aplicación</label>
                        <input type="date" name="fecha_limite" class="form-control"
                               value="{{ old('fecha_limite') }}">
                    </div>
                </div>
            </div>

        </div>

        {{-- Columna derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Estado</div>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="hidden" name="activo" value="0">
                    <input type="checkbox" name="activo" value="1" id="activo-check"
                           {{ old('activo', '1') == '1' ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--orange);cursor:pointer;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:white;">Publicar activo</div>
                        <div style="font-size:11px;color:var(--muted);">Visible para los candidatos</div>
                    </div>
                </label>
            </div>

            <div class="card card-body">
                <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-paper-plane"></i> Publicar Oferta
                </button>
                <a href="{{ route('restaurante.empleos.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px;">
                    Cancelar
                </a>
            </div>

        </div>
    </div>
</form>
@endsection