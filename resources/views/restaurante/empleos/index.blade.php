@extends('restaurante.layout')
@section('title', 'Mis Empleos')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:#2d3748;">
                <i class="bi bi-briefcase text-primary me-2"></i> Mis Empleos
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Ofertas de trabajo de {{ $restaurante->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.empleos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nueva Oferta
        </a>
    </div>

    {{-- ── Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Total</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $empleos->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Activos</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $empleos->where('activo', true)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary bg-secondary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.75rem;letter-spacing:0.5px;">Inactivos</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $empleos->where('activo', false)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabla ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            @if($empleos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Puesto</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Contrato</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Salario</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Fecha límite</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Estado</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;width:120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($empleos as $empleo)
                        <tr class="border-bottom" style="border-color:#edf2f7 !important;">

                            {{-- Puesto --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="color:#2d3748 !important;font-size:0.9rem;">{{ $empleo->titulo }}</span>
                                        <small class="text-muted" style="font-size:0.75rem;">{{ Str::limit($empleo->descripcion, 50) }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Contrato --}}
                            <td class="py-3">
                                @if($empleo->tipo_contrato)
                                    <span class="badge text-secondary bg-secondary bg-opacity-10 border border-secondary border-opacity-20 px-2 py-1 fw-semibold" style="font-size:0.72rem;">
                                        {{ $empleo->tipo_contrato }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Salario --}}
                            <td class="py-3">
                                @if($empleo->salario)
                                    <span class="badge text-primary bg-primary bg-opacity-10 border border-primary border-opacity-20 px-2 py-1 fw-bold" style="font-size:0.78rem;">
                                        C$ {{ number_format($empleo->salario, 0) }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Fecha límite --}}
                            <td class="py-3" style="white-space:nowrap;">
                                @if($empleo->fecha_limite)
                                    <span class="text-dark small fw-semibold d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar3 text-primary" style="font-size:0.8rem;"></i>
                                        {{ \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="py-3 text-center">
                                @if($empleo->activo)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background-color:#e6fffa;color:#047481;border:1px solid #b2f5ea;font-size:0.72rem;">
                                        <span class="bg-success rounded-circle" style="width:5px;height:5px;"></span> Activo
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2 py-1 fw-normal d-inline-flex align-items-center gap-1" style="font-size:0.72rem;">
                                        <span class="bg-secondary rounded-circle" style="width:5px;height:5px;"></span> Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('restaurante.empleos.edit', $empleo) }}"
                                       class="text-secondary p-1 action-icon-edit" title="Editar">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    <form method="POST" action="{{ route('restaurante.empleos.destroy', $empleo) }}"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar esta oferta?')">
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

            <div class="px-4 py-3">
                {{ $empleos->links('pagination::bootstrap-5') }}
            </div>

            @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-briefcase d-block display-6 text-muted mb-3"></i>
                <span class="fs-6 d-block mb-2">No tienes ofertas de empleo publicadas aún.</span>
                <a href="{{ route('restaurante.empleos.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Crear primera oferta
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    .action-icon-edit:hover   { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

@endsection
