@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-geo-alt-fill text-primary me-2"></i> Gestión de Departamentos
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Regiones geográficas registradas en la plataforma Gastro.ni
            </p>
        </div>
        <a href="{{ route('departamentos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Departamento
        </a>
    </div>

    {{-- ── Mensajes ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary bg-opacity-10" style="width:50px;height:50px;font-size:1.4rem;">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Total</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $departamentos->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10" style="width:50px;height:50px;font-size:1.4rem;">
                        <i class="bi bi-pin-map"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Con Restaurantes</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $departamentos->where('restaurantes_count', '>', 0)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-info bg-info bg-opacity-10" style="width:50px;height:50px;font-size:1.4rem;">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Con Gastrobares</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $departamentos->where('gastrobares_count', '>', 0)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabla ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.72rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Departamento</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.72rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Municipios</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.72rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Restaurantes</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.72rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Gastrobares</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.72rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;">Trabajadores</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.72rem;letter-spacing:0.5px;font-weight:600;text-transform:uppercase;width:130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($departamentos as $departamento)
                        <tr class="border-bottom" style="border-color:#edf2f7 !important;">

                            {{-- Nombre --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 bg-primary bg-opacity-10 text-primary flex-shrink-0"
                                         style="width:40px;height:40px;font-size:1rem;">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-uppercase" style="font-size:0.9rem;">
                                            {{ $departamento->nombre }}
                                        </span>
                                        <small class="text-muted" style="font-size:0.72rem;">ID: #{{ $departamento->id }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Municipios --}}
                            <td class="py-3 text-center">
                                <span class="badge rounded-pill px-3 py-1 fw-semibold"
                                      style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.75rem;">
                                    {{ $departamento->municipios_count }}
                                </span>
                            </td>

                            {{-- Restaurantes --}}
                            <td class="py-3 text-center">
                                @if($departamento->restaurantes_count > 0)
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold"
                                          style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;font-size:0.75rem;">
                                        {{ $departamento->restaurantes_count }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Gastrobares --}}
                            <td class="py-3 text-center">
                                @if($departamento->gastrobares_count > 0)
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold"
                                          style="background:#fdf4ff;color:#7e22ce;border:1px solid #e9d5ff;font-size:0.75rem;">
                                        {{ $departamento->gastrobares_count }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Trabajadores --}}
                            <td class="py-3 text-center">
                                @if($departamento->trabajadores_count > 0)
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold"
                                          style="background:#fffbeb;color:#92400e;border:1px solid #fde68a;font-size:0.75rem;">
                                        {{ $departamento->trabajadores_count }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('departamentos.edit', $departamento->id) }}"
                                       class="text-secondary p-1 action-icon-edit" title="Editar">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    <form action="{{ route('departamentos.destroy', $departamento->id) }}" method="POST"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar {{ $departamento->nombre }}? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete"
                                                title="Eliminar" style="box-shadow:none;text-decoration:none;">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-geo-alt d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block mb-2">No hay departamentos registrados aún.</span>
                                <a href="{{ route('departamentos.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                                    Agregar Primer Departamento
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    .action-icon-edit:hover  { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
