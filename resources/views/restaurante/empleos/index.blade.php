@extends('restaurante.layout')
@section('title', 'Mis Empleos')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-briefcase text-primary me-2"></i> Mis Empleos
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Ofertas de trabajo de {{ $restaurante->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.empleos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nueva Oferta
        </a>
    </div>

    {{-- Métricas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:var(--primary-light);color:var(--primary);">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Total</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);">{{ $empleos->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(22,163,74,0.12);color:#22c55e;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Activos</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);">{{ $empleos->where('activo', true)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3" style="background:var(--card-bg) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fs-4"
                         style="width:50px;height:50px;background:rgba(100,116,139,0.12);color:#64748b;">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;color:var(--muted);">Inactivos</p>
                        <h3 class="fw-black mb-0" style="font-size:1.5rem;color:var(--text);">{{ $empleos->where('activo', false)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="background:var(--card-bg) !important;">
        <div class="card-body p-0">
            @if($empleos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="tabla-empleos">
                    <thead class="border-bottom" style="background:var(--table-header) !important;">
                        <tr>
                            <th class="ps-4 py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Puesto</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Contrato</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Salario</th>
                            <th class="py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Fecha límite</th>
                            <th class="py-3 border-0 text-center" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;">Estado</th>
                            <th class="text-end pe-4 py-3 border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;color:var(--muted) !important;width:140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($empleos as $empleo)
                        <tr class="border-bottom fila-empleo" style="border-color:var(--card-border) !important;">

                            {{-- Puesto --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <div>
                                        <span class="fw-bold d-block" style="color:var(--text) !important;font-size:0.9rem;">{{ $empleo->titulo }}</span>
                                        <small style="font-size:0.75rem;color:var(--muted) !important;">{{ Str::limit($empleo->descripcion, 50) }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Contrato --}}
                            <td class="py-3">
                                @if($empleo->tipo_contrato)
                                    <span class="badge px-2 py-1 fw-semibold" style="font-size:0.72rem;background:var(--badge-gray-bg) !important;color:var(--badge-gray-text) !important;border:1px solid var(--card-border) !important;">
                                        {{ $empleo->tipo_contrato }}
                                    </span>
                                @else
                                    <span class="small" style="color:var(--muted) !important;">—</span>
                                @endif
                            </td>

                            {{-- Salario --}}
                            <td class="py-3">
                                @if($empleo->salario)
                                    <span class="badge px-2 py-1 fw-bold" style="font-size:0.78rem;background:var(--primary-light) !important;color:var(--primary) !important;border:1px solid var(--primary-border) !important;">
                                        C$ {{ number_format($empleo->salario, 0) }}
                                    </span>
                                @else
                                    <span class="small" style="color:var(--muted) !important;">—</span>
                                @endif
                            </td>

                            {{-- Fecha límite --}}
                            <td class="py-3" style="white-space:nowrap;">
                                @if($empleo->fecha_limite)
                                    <span class="small fw-semibold d-flex align-items-center gap-1" style="color:var(--text) !important;">
                                        <i class="bi bi-calendar3 text-primary" style="font-size:0.8rem;"></i>
                                        {{ \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') }}
                                    </span>
                                @else
                                    <span class="small" style="color:var(--muted) !important;">—</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="py-3 text-center">
                                @if($empleo->activo)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background-color:rgba(22,163,74,0.1) !important;color:#22c55e !important;border:1px solid rgba(22,163,74,0.2) !important;font-size:0.72rem;">
                                        <span class="rounded-circle" style="width:5px;height:5px;background:#22c55e;"></span> Activo
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1 fw-normal d-inline-flex align-items-center gap-1" style="font-size:0.72rem;background:var(--badge-gray-bg) !important;color:var(--badge-gray-text) !important;border:1px solid var(--card-border) !important;">
                                        <span class="rounded-circle" style="width:5px;height:5px;background:var(--muted);"></span> Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">

                                    @php $nuevas = $empleo->solicitudes()->where('estado', 'nueva')->count(); @endphp
                                    <a href="{{ route('restaurante.solicitudes.index', $empleo) }}"
                                       class="p-1 action-icon-solicitudes position-relative" title="Ver solicitudes" style="color:var(--muted) !important;">
                                        <i class="bi bi-people fs-5"></i>
                                        @if($nuevas > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                                                  style="font-size:0.6rem;background:#ef4444 !important;">{{ $nuevas }}</span>
                                        @endif
                                    </a>

                                    <a href="{{ route('restaurante.empleos.edit', $empleo) }}"
                                       class="p-1 action-icon-edit" title="Editar" style="color:var(--muted) !important;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>

                                    <form method="POST" action="{{ route('restaurante.empleos.destroy', $empleo) }}"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar esta oferta?')">
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

            <div class="px-4 py-3">
                {{ $empleos->links('pagination::bootstrap-5') }}
            </div>

            @else
            <div class="text-center py-5" style="color:var(--muted) !important;">
                <i class="bi bi-briefcase d-block display-6 mb-3" style="opacity:0.4;"></i>
                <span class="fs-6 d-block mb-2" style="color:var(--text) !important;">No tienes ofertas de empleo publicadas aún.</span>
                <a href="{{ route('restaurante.empleos.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Crear primera oferta
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    /* Forzar fondo oscuro en tabla Bootstrap */
    #tabla-empleos.table {
        --bs-table-bg: transparent !important;
        --bs-table-color: var(--text) !important;
        --bs-table-border-color: var(--card-border) !important;
    }
    #tabla-empleos thead th {
        background-color: var(--table-header) !important;
        color: var(--muted) !important;
        border-bottom-color: var(--card-border) !important;
    }
    #tabla-empleos tbody td {
        color: var(--text) !important;
        background-color: var(--card-bg) !important;
        border-bottom-color: var(--card-border) !important;
    }
    #tabla-empleos tbody tr:hover td {
        background-color: var(--table-hover) !important;
    }
    .fila-empleo {
        background-color: var(--card-bg) !important;
    }
    .action-icon-solicitudes:hover { color: #2563eb !important; }
    .action-icon-edit:hover        { color: #ffc107 !important; }
    .action-icon-delete:hover      { color: #dc3545 !important; }
</style>

@endsection
