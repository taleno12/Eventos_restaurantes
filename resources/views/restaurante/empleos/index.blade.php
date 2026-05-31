@extends('restaurante.layout')
@section('title', 'Mis Empleos')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Mis Empleos</div>
        <div class="page-sub">Ofertas de trabajo de {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.empleos.create') }}" class="btn btn-orange">
        <i class="fas fa-plus"></i> Nueva Oferta
    </a>
</div>

<div class="card">
    @if($empleos->count() > 0)
        <div style="overflow-x:auto;">
            <table class="table">
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
                            <div style="font-weight:700;color:white;">{{ $empleo->titulo }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ Str::limit($empleo->descripcion, 50) }}</div>
                        </td>
                        <td style="color:var(--muted);">
                            {{ $empleo->tipo_contrato ?? '—' }}
                        </td>
                        <td style="color:var(--orange);font-weight:700;">
                            {{ $empleo->salario ? 'C$ '.number_format($empleo->salario, 0) : '—' }}
                        </td>
                        <td style="color:var(--muted);white-space:nowrap;">
                            {{ $empleo->fecha_limite ? \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') : '—' }}
                        </td>
                        <td>
                            @if($empleo->activo)
                                <span class="badge badge-green"><i class="fas fa-circle" style="font-size:7px;"></i> Activo</span>
                            @else
                                <span class="badge badge-red"><i class="fas fa-circle" style="font-size:7px;"></i> Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;">
                                <a href="{{ route('restaurante.empleos.edit', $empleo) }}" class="btn btn-ghost" style="padding:6px 12px;">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('restaurante.empleos.destroy', $empleo) }}"
                                      onsubmit="return confirm('¿Eliminar esta oferta?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:6px 12px;">
                                        <i class="fas fa-trash"></i>
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
            <i class="fas fa-briefcase"></i>
            <p>No tienes ofertas de empleo publicadas aún.</p>
            <a href="{{ route('restaurante.empleos.create') }}" class="btn btn-orange">
                <i class="fas fa-plus"></i> Crear primera oferta
            </a>
        </div>
    @endif
</div>
@endsection