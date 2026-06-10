@extends('restaurante.layout')
@section('title', 'Mis Eventos')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado Principal ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-calendar-event text-primary me-2"></i> Mis Eventos
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Gestiona los eventos de {{ $restaurante->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.eventos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Evento
        </a>
    </div>

    {{-- ── Métricas rápidas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Total</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $eventos->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Destacados</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $eventos->where('is_destacado', true)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Próximos</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $eventos->where('fecha_evento', '>=', now())->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabla de Eventos ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            @if($eventos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Evento</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Fecha</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Precio</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Destacado</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;width:130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($eventos as $evento)
                        <tr class="border-bottom" style="border-color:#edf2f7 !important;">

                            {{-- Evento: imagen + título --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center me-2 shadow-sm" style="width:44px;height:44px;flex-shrink:0;">
                                        @if($evento->imagen)
                                            <img src="{{ asset('storage/'.$evento->imagen) }}" class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            <i class="bi bi-calendar-event text-muted fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="color:#2d3748 !important;font-size:0.9rem;">{{ $evento->titulo }}</span>
                                        <small class="text-muted" style="font-size:0.75rem;">{{ Str::limit($evento->descripcion, 50) }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Fecha --}}
                            <td class="py-3" style="white-space:nowrap;">
                                <span class="text-dark small fw-semibold d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar3 text-primary" style="font-size:0.8rem;"></i>
                                    {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M, Y') }}
                                </span>
                            </td>

                            {{-- Precio --}}
                            <td class="py-3">
                                <span class="badge text-primary bg-primary bg-opacity-10 border border-primary border-opacity-20 px-2 py-1 fw-bold" style="font-size:0.78rem;">
                                    C$ {{ number_format($evento->precio, 0) }}
                                </span>
                            </td>

                            {{-- Destacado --}}
                            <td class="py-3">
                                @if($evento->is_destacado)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background-color:#fff7ed;color:#c2410c;border:1px solid #fed7aa;font-size:0.72rem;">
                                        <i class="bi bi-star-fill" style="font-size:9px;"></i> Destacado
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2 py-1 fw-normal" style="font-size:0.72rem;">
                                        Normal
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('restaurante.eventos.edit', $evento) }}"
                                       class="text-secondary p-1 action-icon-edit" title="Editar">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    <form method="POST" action="{{ route('restaurante.eventos.destroy', $evento) }}"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar este evento?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete"
                                                title="Eliminar" style="box-shadow:none;text-decoration:none;">
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
            <div class="text-center text-muted py-5">
                <i class="bi bi-calendar-x d-block display-6 text-muted mb-3"></i>
                <span class="fs-6 d-block mb-2">No tienes eventos publicados aún.</span>
                <a href="{{ route('restaurante.eventos.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Crear primer evento
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    .action-icon-edit:hover  { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

@endsection
