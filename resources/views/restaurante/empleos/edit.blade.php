@extends('restaurante.layout')
@section('title', 'Editar Oferta')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Editar Oferta</div>
        <div class="page-sub">{{ $empleo->titulo }}</div>
    </div>
    <a href="{{ route('restaurante.empleos.index') }}" class="btn-secondary-panel">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="panel-alert panel-alert-error">
        <i class="bi bi-exclamation-circle-fill fs-5"></i>
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('restaurante.empleos.update', $empleo) }}">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="panel-card">
                <div class="card-header">Información del puesto</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Título del puesto *</label>
                        <input type="text" name="titulo" class="form-control"
                               placeholder="Ej: Mesero, Chef de cocina..."
                               value="{{ old('titulo', $empleo->titulo) }}" required>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Descripción del puesto *</label>
                        <textarea name="descripcion" class="form-control" style="min-height:120px;"
                                  placeholder="Describe las responsabilidades...">{{ old('descripcion', $empleo->descripcion) }}</textarea>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Requisitos <span style="color:var(--muted);font-weight:500;">(opcional)</span>
                        </label>
                        <textarea name="requisitos" class="form-control"
                                  placeholder="Experiencia mínima, habilidades...">{{ old('requisitos', $empleo->requisitos) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-header">Condiciones</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Tipo de contrato</label>
                            <select name="tipo_contrato" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach(['Tiempo completo','Medio tiempo','Por horas','Temporal','Freelance'] as $tipo)
                                    <option value="{{ $tipo }}" {{ old('tipo_contrato', $empleo->tipo_contrato) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Salario mensual (C$)</label>
                            <input type="number" name="salario" class="form-control"
                                   placeholder="0" min="0" step="0.01"
                                   value="{{ old('salario', $empleo->salario) }}">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Municipio *</label>
                            <select name="municipio_id" class="form-select" required>
                                <option value="">Selecciona municipio</option>
                                @foreach($municipios as $mun)
                                    <option value="{{ $mun->id }}" {{ old('municipio_id', $empleo->municipio_id) == $mun->id ? 'selected' : '' }}>
                                        {{ $mun->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Fecha límite de aplicación</label>
                            <input type="date" name="fecha_limite" class="form-control"
                                   value="{{ old('fecha_limite', $empleo->fecha_limite ? \Carbon\Carbon::parse($empleo->fecha_limite)->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Columna derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="panel-card">
                <div class="card-header">Estado</div>
                <div class="card-body">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1" id="activo-check"
                               {{ old('activo', $empleo->activo) ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--primary);cursor:pointer;">
                        <div>
                            <div style="font-size:13px;font-weight:700;">Oferta activa</div>
                            <div style="font-size:11px;color:var(--muted);">Visible para los candidatos</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <button type="submit" class="btn-primary-panel" style="width:100%;justify-content:center;padding:12px;">
                        <i class="bi bi-floppy"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('restaurante.empleos.index') }}" class="btn-secondary-panel" style="width:100%;justify-content:center;">
                        Cancelar
                    </a>
                </div>
            </div>

        </div>{{-- fin columna derecha --}}
    </div>{{-- fin grid --}}
</form>{{-- fin form PUT — zona de peligro va FUERA --}}

{{-- Zona de peligro FUERA del form principal --}}
<div style="display:flex;justify-content:flex-end;margin-top:16px;">
    <div class="panel-card" style="border-color:#fecaca;width:300px;">
        <div class="card-header" style="color:#dc2626;border-color:#fecaca;">Zona de peligro</div>
        <div class="card-body">
            <button type="button"
                    onclick="eliminarEmpleo('{{ route('restaurante.empleos.destroy', $empleo) }}')"
                    class="btn-danger-panel" style="width:100%;justify-content:center;">
                <i class="bi bi-trash"></i> Eliminar Oferta
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function eliminarEmpleo(url) {
    if (!confirm('¿Eliminar esta oferta de empleo? Esta acción no se puede deshacer.')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection