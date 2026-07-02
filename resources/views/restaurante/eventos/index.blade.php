@extends('restaurante.layout')
@section('title', 'Mis Eventos')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Mis Eventos</div>
        <div class="page-sub">Gestiona los eventos de {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.eventos.create') }}" class="btn-primary-panel">
        <i class="bi bi-plus-lg"></i> Nuevo Evento
    </a>
</div>

{{-- Aviso de límite mensual --}}
<div class="panel-alert {{ $visiblesEsteMes >= 12 ? 'panel-alert-error' : 'panel-alert-info' }} mb-4 d-flex align-items-center gap-2">
    <i class="bi {{ $visiblesEsteMes >= 12 ? 'bi-exclamation-circle-fill' : 'bi-info-circle-fill' }} fs-5"></i>
    <div>
        @if($visiblesEsteMes >= 12)
            Alcanzaste el límite de <strong>12 eventos visibles</strong> este mes. Oculta uno para poder mostrar otro, o esperá al próximo mes.
        @else
            Llevas <strong>{{ $visiblesEsteMes }}/12</strong> eventos visibles al público este mes.
        @endif
    </div>
</div>

{{-- Métricas --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="metric-card d-flex align-items-center gap-3">
            <div class="metric-icon blue"><i class="bi bi-calendar3"></i></div>
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
                        <th>Visible al público</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventos as $evento)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                <div class="rounded-3 border overflow-hidden d-flex align-items-center justify-content-center me-2 shadow-sm"
                                     style="width:44px;height:44px;flex-shrink:0;">
                                    @if($evento->imagen)
                                        <img src="{{ asset('storage/'.$evento->imagen) }}" class="w-100 h-100" style="object-fit:cover;">
                                    @else
                                        <i class="bi bi-calendar-event fs-5 text-muted"></i>
                                    @endif
                                </div>
                                <div>
                                    <span class="fw-bold d-block" style="font-size:0.9rem;color:var(--text);">{{ $evento->titulo }}</span>
                                    <small style="font-size:0.75rem;color:var(--muted);">{{ Str::limit($evento->descripcion, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="white-space:nowrap;">
                            <span class="small fw-semibold d-flex align-items-center gap-1" style="color:var(--text);">
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
                        <td>
                            <form method="POST" action="{{ route('restaurante.eventos.toggleVisibilidad', $evento) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm rounded-pill px-3"
                                        style="border:1px solid {{ $evento->visible_publico ? 'rgba(22,163,74,0.3)' : 'var(--card-border)' }};background:{{ $evento->visible_publico ? 'rgba(22,163,74,0.1)' : 'transparent' }};color:{{ $evento->visible_publico ? '#22c55e' : 'var(--muted)' }};font-size:12px;font-weight:600;">
                                    <i class="bi {{ $evento->visible_publico ? 'bi-eye-fill' : 'bi-eye-slash-fill' }} me-1"></i>
                                    {{ $evento->visible_publico ? 'Visible' : 'Oculto' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('restaurante.eventos.edit', $evento) }}" class="action-btn action-btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('restaurante.eventos.destroy', $evento) }}" onsubmit="return confirm('¿Eliminar este evento?')">
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
            <a href="{{ route('restaurante.eventos.create') }}" class="btn-primary-panel">
                <i class="bi bi-plus-lg"></i> Crear primer evento
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
