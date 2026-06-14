@extends('gastrobar.layout')
@section('title', 'Mis Empleos')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#2d3748;">
                <i class="bi bi-briefcase me-2" style="color:var(--primary);"></i> Mis Empleos
            </h1>
            <p class="text-muted mb-0 small">Ofertas de trabajo de {{ $gastrobar->nombre }}</p>
        </div>
        <a href="{{ route('gastrobar.empleos.create') }}" class="btn-primary-panel">
            <i class="bi bi-plus-lg"></i> Nueva Oferta
        </a>
    </div>

    {{-- Métricas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="metric-card d-flex align-items-center gap-3">
                <div class="metric-icon purple"><i class="bi bi-briefcase"></i></div>
                <div>
                    <div class="metric-label">Total</div>
                    <div class="metric-value">{{ $empleos->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="metric-card d-flex align-items-center gap-3">
                <div class="metric-icon green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="metric-label">Activos</div>
                    <div class="metric-value">{{ $empleos->where('activo', true)->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="metric-card d-flex align-items-center gap-3">
                <div class="metric-icon orange"><i class="bi bi-pause-circle"></i></div>
                <div>
                    <div class="metric-label">Inactivos</div>
                    <div class="metric-value">{{ $empleos->where('activo', false)->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="panel-card">
        <div class="card-body p-0">
            @if($empleos->count() > 0)
            <div class="table-responsive">
                <table class="panel-table">
                    <thead>
                        <tr>
                            <th class="ps-4">Puesto</th>
                            <th>Contrato</th>
                            <th>Salario</th>
                            <th>Fecha límite</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empleos as $empleo)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div style="width:4px;height:32px;background:var(--primary);border-radius:4px;margin-right:12px;flex-shrink:0;"></div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.9rem;color:#2d3748;">{{ $empleo->titulo }}</div>
                                        <small class="text-muted">{{ Str::limit($empleo->descripcion, 50) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($empleo->tipo_contrato)
                                    <span class="panel-badge badge-gray">{{ $empleo->tipo_contrato }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($empleo->salario)
                                    <span class="panel-badge badge-purple">C$ {{ number_format($empleo->salario, 0) }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">
                                @if($empleo->fecha_limite)
                                    <span class="small fw-semibold d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar3" style="color:var(--primary);font-size:0.8rem;"></i>
                                        {{ \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($empleo->activo)
                                    <span class="panel-badge badge-green"><span class="bg-success rounded-circle" style="width:5px;height:5px;display:inline-block;"></span> Activo</span>
                                @else
                                    <span class="panel-badge badge-gray"><span class="bg-secondary rounded-circle" style="width:5px;height:5px;display:inline-block;"></span> Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('gastrobar.empleos.edit', $empleo) }}" class="action-btn action-btn-edit" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('gastrobar.empleos.destroy', $empleo) }}"
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
            <div class="px-4 py-3">
                {{ $empleos->links('pagination::bootstrap-5') }}
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-briefcase"></i>
                <p>No tienes ofertas de empleo publicadas aún.</p>
                <a href="{{ route('gastrobar.empleos.create') }}" class="btn-primary-panel">
                    <i class="bi bi-plus-lg"></i> Crear primera oferta
                </a>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
