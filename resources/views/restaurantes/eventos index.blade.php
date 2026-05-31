@extends('restaurante.layout')
@section('title', 'Mis Eventos')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Mis Eventos</div>
        <div class="page-sub">Gestiona los eventos de {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.eventos.create') }}" class="btn btn-orange">
        <i class="fas fa-plus"></i> Nuevo Evento
    </a>
</div>

<div class="card">
    @if($eventos->count() > 0)
        <div style="overflow-x:auto;">
            <table class="table">
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
                                    <div style="width:44px;height:44px;border-radius:10px;background:var(--orange-dim);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-calendar" style="color:var(--orange);font-size:16px;"></i>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:700;color:white;">{{ $evento->titulo }}</div>
                                    <div style="font-size:11px;color:var(--muted);">{{ Str::limit($evento->descripcion, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--muted);white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M, Y') }}
                        </td>
                        <td style="color:var(--orange);font-weight:700;">
                            C$ {{ number_format($evento->precio, 0) }}
                        </td>
                        <td>
                            @if($evento->is_destacado)
                                <span class="badge badge-orange"><i class="fas fa-star"></i> Destacado</span>
                            @else
                                <span class="badge" style="background:#1a1a1a;color:#555;border-color:#222;">Normal</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;">
                                <a href="{{ route('restaurante.eventos.edit', $evento) }}" class="btn btn-ghost" style="padding:6px 12px;">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('restaurante.eventos.destroy', $evento) }}"
                                      onsubmit="return confirm('¿Eliminar este evento?')">
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
        <div style="padding:16px;">{{ $eventos->links() }}</div>
    @else
        <div class="empty-state">
            <i class="fas fa-calendar-xmark"></i>
            <p>No tienes eventos publicados aún.</p>
            <a href="{{ route('restaurante.eventos.create') }}" class="btn btn-orange">
                <i class="fas fa-plus"></i> Crear primer evento
            </a>
        </div>
    @endif
</div>
@endsection