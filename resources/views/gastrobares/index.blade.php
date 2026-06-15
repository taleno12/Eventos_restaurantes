@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado Principal ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold" style="color: #2d3748 !important;">
                <i class="bi bi-cup-straw text-warning me-2"></i> Gestión de Gastrobares
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Control, estados y localización de gastrobares en la plataforma.
            </p>
        </div>
        <a href="{{ route('admin.gastrobares.create') }}" class="btn btn-warning px-4 rounded-pill shadow-sm fw-semibold text-dark">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Gastrobar
        </a>
    </div>

    {{-- ── Mensajes ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ── Tarjetas de Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10 p-3 fs-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size: 1.5rem;">{{ $gastrobares->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Activos</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size: 1.5rem;">{{ $activos }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-info bg-info bg-opacity-10 p-3 fs-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-map"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Departamentos</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size: 1.5rem;">{{ $totalDepartamentos }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Barra de Búsqueda y Filtros ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('admin.gastrobares.index') }}" class="row g-3 align-items-center">
            <div class="col-12 col-sm-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control bg-light border-start-0 ps-0"
                           placeholder="Buscar por nombre..."
                           style="box-shadow: none;">
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                    <select name="departamento_id" class="form-select bg-light border-start-0 ps-0" style="box-shadow: none; cursor: pointer;">
                        <option value="">Todos los departamentos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" {{ request('departamento_id') == $depto->id ? 'selected' : '' }}>
                                {{ $depto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-funnel-fill text-warning"></i> Filtrar
                </button>
                @if(request('search') || request('departamento_id'))
                    <a href="{{ route('admin.gastrobares.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center" title="Limpiar Filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Tabla Principal ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-uppercase text-muted border-bottom" style="background-color: #f8f9fa !important;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Gastrobar</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Tipo</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Ubicación</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Horario</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Estado</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600; width: 180px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($gastrobares as $gastrobar)
                        <tr class="border-bottom" style="border-color: #edf2f7 !important;">

                            {{-- Imagen y Nombre --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-pill me-3" style="width: 4px; height: 32px;"></div>
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 40px; height: 40px; flex-shrink: 0;">
                                        @if($gastrobar->imagen_principal)
                                            <img src="{{ Storage::url($gastrobar->imagen_principal) }}" alt="{{ $gastrobar->nombre }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <i class="bi bi-cup-straw text-warning fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-uppercase" style="font-size: 0.9rem;">{{ $gastrobar->nombre }}</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $gastrobar->email ?? 'ID: #' . $gastrobar->id }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Tipo --}}
                            <td class="py-3">
                                @if($gastrobar->tipo_bar)
                                    <span class="badge px-2 py-1 text-uppercase fw-bold" style="background:#fff8e1; color:#b45309; font-size: 0.7rem;">
                                        {{ $gastrobar->tipo_bar }}
                                    </span>
                                @endif
                                @if($gastrobar->tipo_cocina)
                                    <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">{{ $gastrobar->tipo_cocina }}</p>
                                @endif
                                @if(!$gastrobar->tipo_bar && !$gastrobar->tipo_cocina)
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Ubicación --}}
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    @if($gastrobar->departamento)
                                        <span class="text-dark fw-semibold small d-flex align-items-center gap-1 text-uppercase">
                                            <i class="bi bi-geo-alt-fill text-danger" style="font-size: 0.8rem;"></i>
                                            {{ $gastrobar->departamento->nombre }}
                                        </span>
                                    @endif
                                    @if($gastrobar->municipio)
                                        <small class="text-muted ps-3" style="font-size: 0.75rem;">
                                            <i class="bi bi-building me-1"></i>{{ $gastrobar->municipio->nombre }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            {{-- Horario --}}
                            <td class="py-3">
                                <p class="text-dark mb-0" style="font-size: 0.8rem;">{{ $gastrobar->horario_texto }}</p>
                                @if($gastrobar->ambiente)
                                    <span class="badge bg-light text-secondary fw-normal mt-1" style="font-size: 0.72rem;">
                                        {{ $gastrobar->ambiente }}
                                    </span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="py-3 text-center">
                                @if($gastrobar->activo)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background-color: #e6fffa; color: #047481; border: 1px solid #b2f5ea; font-size: 0.72rem;">
                                        <span class="bg-success rounded-circle" style="width: 5px; height: 5px;"></span> ACTIVO
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2 py-1 fw-normal d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                        <span class="bg-secondary rounded-circle" style="width: 5px; height: 5px;"></span> INACTIVO
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">

                                    <a href="{{ route('admin.gastrobares.show', $gastrobar->id) }}"
                                       class="text-secondary p-1 action-icon-view" title="Ver Detalle">
                                        <i class="bi bi-eye fs-5"></i>
                                    </a>

                                    <a href="{{ route('admin.gastrobares.edit', $gastrobar->id) }}"
                                       class="text-secondary p-1 action-icon-edit" title="Editar Gastrobar">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>

                                    {{-- Toggle Activo/Inactivo --}}
                                    <form action="{{ route('admin.gastrobares.toggle', $gastrobar->id) }}" method="POST"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('{{ $gastrobar->activo ? '¿Desactivar este gastrobar? Se ocultará de la vista pública junto con sus eventos y empleos, y su propietario perderá acceso.' : '¿Activar este gastrobar?' }}')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-link p-1 m-0 border-0 align-baseline {{ $gastrobar->activo ? 'action-icon-toggle-off' : 'action-icon-toggle-on' }}"
                                                title="{{ $gastrobar->activo ? 'Desactivar' : 'Activar' }}"
                                                style="box-shadow: none; text-decoration: none;">
                                            <i class="bi {{ $gastrobar->activo ? 'bi-toggle-on fs-5 text-success' : 'bi-toggle-off fs-5 text-secondary' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('admin.gastrobares.destroy', $gastrobar->id) }}" method="POST"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar este gastrobar? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete"
                                                title="Eliminar Gastrobar"
                                                style="box-shadow: none; text-decoration: none;">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-cup-straw d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block mb-2">No se encontraron gastrobares registrados.</span>
                                <a href="{{ route('admin.gastrobares.create') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold text-dark">
                                    Agregar Primer Gastrobar
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-4 d-flex justify-content-end">
        {{ $gastrobares->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>

<style>
    .action-icon-view:hover   { color: #0d6efd !important; }
    .action-icon-edit:hover   { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .action-icon-toggle-off:hover i { color: #dc3545 !important; }
    .action-icon-toggle-on:hover i  { color: #198754 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
