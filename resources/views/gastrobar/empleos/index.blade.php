@extends('gastrobar.layout')
@section('title', 'Mis Empleos')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#f8fafc;">
                <i class="bi bi-briefcase me-2" style="color:#818cf8;"></i> Mis Empleos
            </h1>
            <p class="mb-0 small" style="color:#cbd5e1;">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Ofertas de trabajo de {{ $gastrobar->nombre }}
            </p>
        </div>
        <a href="{{ route('gastrobar.empleos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nueva Oferta
        </a>
    </div>

    {{-- Métricas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:#1e293b !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(99,102,241,0.15);color:#818cf8;">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">Total</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:#f8fafc;">{{ $empleos->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:#1e293b !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(34,197,94,0.15);color:#4ade80;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">Activos</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:#f8fafc;">{{ $empleos->where('activo', true)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:#1e293b !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(245,158,11,0.15);color:#fbbf24;">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:#94a3b8;">Inactivos</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:#f8fafc;">{{ $empleos->where('activo', false)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="background:#1e293b !important;">
        <div class="card-body p-0">
            @if($empleos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="tabla-empleos">
                    <thead class="border-bottom" style="background:#0f172a !important;">
                        <tr>
                            <th class="ps-4 py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:#94a3b8 !important;">Puesto</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:#94a3b8 !important;">Contrato</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:#94a3b8 !important;">Salario</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:#94a3b8 !important;">Fecha límite</th>
                            <th class="text-center py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:#94a3b8 !important;">Estado</th>
                            <th class="text-end pe-4 py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:#94a3b8 !important;width:130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($empleos as $empleo)
                        <tr class="border-bottom fila-empleo" style="border-color:#334155 !important;">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div style="width:4px;height:32px;background:#818cf8;border-radius:4px;margin-right:12px;flex-shrink:0;"></div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.9rem;color:#f8fafc;">{{ $empleo->titulo }}</div>
                                        <small style="color:#cbd5e1;">{{ Str::limit($empleo->descripcion, 50) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($empleo->tipo_contrato)
                                    <span class="badge rounded-pill px-2 py-1 fw-normal" style="font-size:0.72rem;background:#334155 !important;color:#cbd5e1 !important;border:1px solid #475569 !important;">
                                        {{ $empleo->tipo_contrato }}
                                    </span>
                                @else
                                    <span class="small" style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($empleo->salario)
                                    <span class="badge px-2 py-1 fw-bold" style="font-size:0.78rem;background:rgba(99,102,241,0.15) !important;color:#a5b4fc !important;border:1px solid rgba(99,102,241,0.3) !important;">
                                        C$ {{ number_format($empleo->salario, 0) }}
                                    </span>
                                @else
                                    <span class="small" style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td class="py-3" style="white-space:nowrap;">
                                @if($empleo->fecha_limite)
                                    <span class="small fw-semibold d-flex align-items-center gap-1" style="color:#f8fafc;">
                                        <i class="bi bi-calendar3" style="color:#818cf8;font-size:0.8rem;"></i>
                                        {{ \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') }}
                                    </span>
                                @else
                                    <span class="small" style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td class="text-center py-3">
                                @if($empleo->activo)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background-color:rgba(34,197,94,0.15) !important;color:#4ade80 !important;border:1px solid rgba(34,197,94,0.3) !important;font-size:0.72rem;">
                                        <span style="width:5px;height:5px;background:#4ade80;border-radius:50%;display:inline-block;"></span> Activo
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1 fw-normal" style="font-size:0.72rem;background:#334155 !important;color:#94a3b8 !important;border:1px solid #475569 !important;">
                                        <span style="width:5px;height:5px;background:#94a3b8;border-radius:50%;display:inline-block;"></span> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">

                                    @php $nuevas = $empleo->solicitudes()->where('estado', 'nueva')->count(); @endphp
                                    <a href="{{ route('gastrobar.solicitudes.index', $empleo) }}"
                                       class="p-1 action-icon-people position-relative" title="Ver solicitudes"
                                       style="color:#94a3b8 !important;">
                                        <i class="bi bi-people fs-5"></i>
                                        @if($nuevas > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                                                  style="font-size:0.6rem;background:#ef4444 !important;">{{ $nuevas }}</span>
                                        @endif
                                    </a>

                                    <a href="{{ route('gastrobar.empleos.edit', $empleo) }}" class="p-1 action-icon-edit" title="Editar" style="color:#94a3b8 !important;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>

                                    <form method="POST" action="{{ route('gastrobar.empleos.destroy', $empleo) }}"
                                          onsubmit="return confirm('¿Eliminar esta oferta?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link p-1 m-0 border-0 align-baseline action-icon-delete"
                                                title="Eliminar" style="box-shadow:none;text-decoration:none;color:#94a3b8 !important;">
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
            <div class="px-4 py-3">
                {{ $empleos->links('pagination::bootstrap-5') }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-briefcase d-block display-6 mb-3" style="color:#94a3b8;opacity:0.4;"></i>
                <p class="fs-6 mb-3" style="color:#f8fafc;">No tienes ofertas de empleo publicadas aún.</p>
                <a href="{{ route('gastrobar.empleos.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Crear primera oferta
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    #tabla-empleos.table {
        --bs-table-bg: transparent !important;
        --bs-table-color: #f8fafc !important;
        --bs-table-border-color: #334155 !important;
    }
    #tabla-empleos thead th {
        background-color: #0f172a !important;
        color: #94a3b8 !important;
        border-bottom-color: #334155 !important;
    }
    #tabla-empleos tbody td {
        color: #f8fafc !important;
        background-color: #1e293b !important;
        border-bottom-color: #334155 !important;
    }
    #tabla-empleos tbody tr:hover td {
        background-color: #334155 !important;
    }
    .fila-empleo {
        background-color: #1e293b !important;
    }
    .action-icon-edit:hover   { color: #fbbf24 !important; }
    .action-icon-delete:hover { color: #ef4444 !important; }
    .action-icon-people:hover { color: #818cf8 !important; }
</style>
@endsection
