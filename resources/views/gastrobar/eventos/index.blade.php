@extends('gastrobar.layout')
@section('title', 'Mis Eventos')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#2d3748;">
                <i class="bi bi-calendar-event me-2" style="color:var(--primary);"></i> Mis Eventos
            </h1>
            <p class="text-muted mb-0 small">Gestiona los eventos de {{ $gastrobar->nombre }}</p>
        </div>
        <a href="{{ route('gastrobar.eventos.create') }}" class="btn-primary-panel">
            <i class="bi bi-plus-lg"></i> Nuevo Evento
        </a>
    </div>

    {{-- Métricas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="metric-card d-flex align-items-center gap-3">
                <div class="metric-icon purple"><i class="bi bi-calendar3"></i></div>
                <div>
                    <div class="metric-label">Total</div>
                    <div class="metric-value">{{ $eventos->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="metric-card d-flex align-items-center gap-3">
                <div class="metric-icon orange"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="metric-label">Destacados</div>
                    <div class="metric-value">{{ $eventos->where('is_destacado', true)->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="metric-card d-flex align-items-center gap-3">
                <div class="metric-icon green"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="metric-label">Próximos</div>
                    <div class="metric-value">{{ $eventos->where('fecha_evento', '>=', now())->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="panel-card">
        <div class="card-body p-0">
            @if($eventos->count() > 0)
            <div class="table-responsive">
                <table class="panel-table">
                    <thead>
                        <tr>
                            <th class="ps-4">Evento</th>
                            <th>Fecha</th>
                            <th>Precio</th>
                            <th>Destacado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $evento)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                                        @if($evento->imagen)
                                            <img src="{{ asset('storage/'.$evento->imagen) }}" class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            <i class="bi bi-calendar-event text-muted"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.9rem;">{{ $evento->titulo }}</div>
                                        <small class="text-muted">{{ Str::limit($evento->descripcion, 50) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="white-space:nowrap;">
                                <span class="small fw-semibold d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar3" style="color:var(--primary);font-size:0.8rem;"></i>
                                    {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M, Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="panel-badge badge-purple">C$ {{ number_format($evento->precio, 0) }}</span>
                            </td>
                            <td>
                                @if($evento->is_destacado)
                                    <span class="panel-badge badge-orange"><i class="bi bi-star-fill" style="font-size:9px;"></i> Destacado</span>
                                @else
                                    <span class="panel-badge badge-gray">Normal</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('gastrobar.eventos.edit', $evento) }}" class="action-btn action-btn-edit" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('gastrobar.eventos.destroy', $evento) }}" onsubmit="return confirm('¿Eliminar este evento?')">
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
                {{ $eventos->links('pagination::bootstrap-5') }}
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <p>No tienes eventos publicados aún.</p>
                <a href="{{ route('gastrobar.eventos.create') }}" class="btn-primary-panel">
                    <i class="bi bi-plus-lg"></i> Crear primer evento
                </a>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
