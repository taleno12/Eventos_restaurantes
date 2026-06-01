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

<div class="panel-card">
    @if($eventos->count() > 0)
        <div style="overflow-x:auto;">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Fecha</th>
                        <th>Precio</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventos as $evento)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                @if($evento->imagen)
                                    <img src="{{ asset('storage/'.$evento->imagen) }}"
                                         style="width:44px;height:44px;border-radius:10px;object-fit:cover;flex-shrink:0;">
                                @else
                                    <div style="width:44px;height:44px;border-radius:10px;background:#fff7ed;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-calendar-event" style="color:var(--primary);font-size:16px;"></i>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:700;">{{ $evento->titulo }}</div>
                                    <div style="font-size:11px;color:var(--muted);">{{ Str::limit($evento->descripcion, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M, Y') }}
                        </td>
                        <td style="color:var(--primary);font-weight:700;">
                            C$ {{ number_format($evento->precio, 0) }}
                        </td>
                        <td>
                            @if($evento->is_destacado)
                                <span class="panel-badge badge-orange">
                                    <i class="bi bi-star-fill" style="font-size:9px;"></i> Destacado
                                </span>
                            @else
                                <span class="panel-badge badge-gray">Normal</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('restaurante.eventos.edit', $evento) }}"
                                   class="action-btn action-btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('restaurante.eventos.destroy', $evento) }}"
                                      onsubmit="return confirm('¿Eliminar este evento?')">
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
        <div style="padding:16px;">{{ $eventos->links() }}</div>
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
@endsection
