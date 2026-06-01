@extends('restaurante.layout')
@section('title', 'Mis Empleos')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Mis Empleos</div>
        <div class="page-sub">Ofertas de trabajo de {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.empleos.create') }}" class="btn-primary-panel">
        <i class="bi bi-plus-lg"></i> Nueva Oferta
    </a>
</div>

<div class="panel-card">
    @if($empleos->count() > 0)
        <div style="overflow-x:auto;">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>Puesto</th>
                        <th>Contrato</th>
                        <th>Salario</th>
                        <th>Fecha límite</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleos as $empleo)
                    <tr>
                        <td>
                            <div style="font-weight:700;">{{ $empleo->titulo }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ Str::limit($empleo->descripcion, 50) }}</div>
                        </td>
                        <td style="color:var(--muted);">
                            {{ $empleo->tipo_contrato ?? '—' }}
                        </td>
                        <td style="color:var(--primary);font-weight:700;">
                            {{ $empleo->salario ? 'C$ '.number_format($empleo->salario, 0) : '—' }}
                        </td>
                        <td style="white-space:nowrap;">
                            {{ $empleo->fecha_limite ? \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') : '—' }}
                        </td>
                        <td>
                            @if($empleo->activo)
                                <span class="panel-badge badge-green">
                                    <i class="bi bi-circle-fill" style="font-size:6px;"></i> Activo
                                </span>
                            @else
                                <span class="panel-badge badge-gray">
                                    <i class="bi bi-circle-fill" style="font-size:6px;"></i> Inactivo
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('restaurante.empleos.edit', $empleo) }}"
                                   class="action-btn action-btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('restaurante.empleos.destroy', $empleo) }}"
                                      onsubmit="return confirm('¿Eliminar esta oferta?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $empleos->links() }}</div>
    @else
        <div class="empty-state">
            <i class="bi bi-briefcase"></i>
            <p>No tienes ofertas de empleo publicadas aún.</p>
            <a href="{{ route('restaurante.empleos.create') }}" class="btn-primary-panel">
                <i class="bi bi-plus-lg"></i> Crear primera oferta
            </a>
        </div>
    @endif
</div>
@endsection
