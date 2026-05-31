@extends('restaurante.layout')
@section('title', 'Editar Oferta')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Editar Oferta</div>
        <div class="page-sub">{{ $empleo->titulo }}</div>
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

<form method="POST" action="{{ route('restaurante.empleos.update', $empleo) }}">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del puesto</div>

                <div class="form-group">
                    <label class="form-label">Título del puesto *</label>
                    <input type="text" name="titulo" class="form-control"
                           placeholder="Ej: Mesero, Chef de cocina..."
                           value="{{ old('titulo', $empleo->titulo) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción del puesto *</label>
                    <textarea name="descripcion" class="form-control" style="min-height:120px;"
                              placeholder="Describe las responsabilidades...">{{ old('descripcion', $empleo->descripcion) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Requisitos <span style="color:#444;font-weight:600;">(opcional)</span></label>
                    <textarea name="requisitos" class="form-control"
                              placeholder="Experiencia mínima, habilidades...">{{ old('requisitos', $empleo->requisitos) }}</textarea>
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
                                <option value="{{ $tipo }}" {{ old('tipo_contrato', $empleo->tipo_contrato) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salario mensual (C$)</label>
                        <input type="number" name="salario" class="form-control"
                               placeholder="0" min="0" step="0.01"
                               value="{{ old('salario', $empleo->salario) }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Municipio *</label>
                        <select name="municipio_id" class="form-control" required>
                            <option value="">Selecciona municipio</option>
                            @foreach($municipios as $mun)
                                <option value="{{ $mun->id }}" {{ old('municipio_id', $empleo->municipio_id) == $mun->id ? 'selected' : '' }}>
                                    {{ $mun->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha límite de aplicación</label>
                        <input type="date" name="fecha_limite" class="form-control"
                               value="{{ old('fecha_limite', $empleo->fecha_limite ? \Carbon\Carbon::parse($empleo->fecha_limite)->format('Y-m-d') : '') }}">
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
                           {{ old('activo', $empleo->activo) ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--orange);cursor:pointer;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:white;">Oferta activa</div>
                        <div style="font-size:11px;color:var(--muted);">Visible para los candidatos</div>
                    </div>
                </label>
            </div>

            <div class="card card-body">
                <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('restaurante.empleos.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px;">
                    Cancelar
                </a>
            </div>

            <div class="card card-body" style="border-color:#e74c3c22;">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:#e74c3c55;margin-bottom:12px;">Zona de peligro</div>
                <form method="POST" action="{{ route('restaurante.empleos.destroy', $empleo) }}"
                      onsubmit="return confirm('¿Eliminar esta oferta de empleo?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                        <i class="fas fa-trash"></i> Eliminar Oferta
                    </button>
                </form>
            </div>

        </div>
    </div>
</form>
@endsection