@extends('restaurante.layout')
@section('title', 'Mis Eventos')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: var(--text);">
                <i class="bi bi-calendar-event text-primary me-2"></i> Mis Eventos
            </h1>
            <p class="mb-0 small" style="color: var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Gestiona los eventos de {{ $restaurante->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.eventos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Evento
        </a>
    </div>

    {{-- Métricas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background: var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:var(--primary-light);color:var(--primary);">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Total</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);">{{ $eventos->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background: var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(245,158,11,0.12);color:#f59e0b;">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Destacados</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);">{{ $eventos->where('is_destacado', true)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background: var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(34,197,94,0.12);color:#22c55e;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Próximos</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);">{{ $eventos->where('fecha_evento', '>=', now())->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="background: var(--card-bg) !important;">
        <div class="card-body p-0">
            @if($eventos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="tabla-eventos">
                    <thead class="border-bottom" style="background: var(--table-header) !important;">
                        <tr>
                            <th class="ps-4 py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Evento</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Fecha</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Precio</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Destacado</th>
                            <th class="text-end pe-4 py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;width:130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($eventos as $evento)
                        <tr class="border-bottom fila-evento" style="border-color:var(--card-border) !important;">

                            {{-- Evento --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <div class="rounded-3 border overflow-hidden d-flex align-items-center justify-content-center me-2 shadow-sm"
                                         style="width:44px;height:44px;flex-shrink:0;background:var(--hover-bg) !important;">
                                        @if($evento->imagen)
                                            <img src="{{ asset('storage/'.$evento->imagen) }}" class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            <i class="bi bi-calendar-event fs-5" style="color:var(--muted) !important;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block" style="color:var(--text) !important;font-size:0.9rem;">{{ $evento->titulo }}</span>
                                        <small style="font-size:0.75rem;color:var(--muted) !important;">{{ Str::limit($evento->descripcion, 50) }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Fecha --}}
                            <td class="py-3" style="white-space:nowrap;">
                                <span class="small fw-semibold d-flex align-items-center gap-1" style="color:var(--text) !important;">
                                    <i class="bi bi-calendar3 text-primary" style="font-size:0.8rem;"></i>
                                    {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M, Y') }}
                                </span>
                            </td>

                            {{-- Precio --}}
                            <td class="py-3">
                                <span class="badge px-2 py-1 fw-bold" style="font-size:0.78rem;background:var(--primary-light) !important;color:var(--primary) !important;border:1px solid var(--primary-border) !important;">
                                    C$ {{ number_format($evento->precio, 0) }}
                                </span>
                            </td>

                            {{-- Destacado --}}
                            <td class="py-3">
                                @if($evento->is_destacado)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background-color:#fff7ed !important;color:#c2410c !important;border:1px solid #fed7aa !important;font-size:0.72rem;">
                                        <i class="bi bi-star-fill" style="font-size:9px;"></i> Destacado
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1 fw-normal" style="font-size:0.72rem;background:var(--badge-gray-bg) !important;color:var(--badge-gray-text) !important;border:1px solid var(--card-border) !important;">
                                        Normal
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('restaurante.eventos.edit', $evento) }}"
                                       class="p-1 action-icon-edit" title="Editar" style="color:var(--muted) !important;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    <form method="POST" action="{{ route('restaurante.eventos.destroy', $evento) }}"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar este evento?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-link p-1 m-0 border-0 align-baseline action-icon-delete"
                                                title="Eliminar" style="box-shadow:none;text-decoration:none;color:var(--muted) !important;">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="px-4 py-3">
                {{ $eventos->links('pagination::bootstrap-5') }}
            </div>

            @else
            {{-- Estado vacío --}}
            <div class="text-center py-5" style="color:var(--muted) !important;">
                <i class="bi bi-calendar-x d-block display-6 mb-3" style="opacity:0.4;"></i>
                <span class="fs-6 d-block mb-2" style="color:var(--text) !important;">No tienes eventos publicados aún.</span>
                <a href="{{ route('restaurante.eventos.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Crear primer evento
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    /* Forzar fondo oscuro en tabla Bootstrap */
    #tabla-eventos.table {
        --bs-table-bg: transparent !important;
        --bs-table-color: var(--text) !important;
        --bs-table-border-color: var(--card-border) !important;
    }
    #tabla-eventos thead th {
        background-color: var(--table-header) !important;
        color: var(--muted) !important;
        border-bottom-color: var(--card-border) !important;
    }
    #tabla-eventos tbody td {
        color: var(--text) !important;
        background-color: var(--card-bg) !important;
        border-bottom-color: var(--card-border) !important;
    }
    #tabla-eventos tbody tr:hover td {
        background-color: var(--table-hover) !important;
    }
    .fila-evento {
        background-color: var(--card-bg) !important;
    }
    .action-icon-edit:hover  { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
</style>

@endsection
