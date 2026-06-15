{{-- resources/views/admin/empleos/index.blade.php --}}
@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado Principal ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold" style="color: #2d3748 !important;">
                <i class="bi bi-briefcase text-primary me-2"></i> Ofertas de Empleo
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Gestiona las vacantes publicadas por restaurantes y gastrobares.
            </p>
        </div>
        <a href="{{ route('admin.empleos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nueva Oferta
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

    {{-- ── Bloque de Tarjetas de Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary bg-opacity-10 p-3 fs-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Ofertas</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size: 1.5rem;">{{ $empleos->total() }}</h3>
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
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Vacantes Activas</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size: 1.5rem;">{{ $activas }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10 p-3 fs-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Con Ofertas</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size: 1.5rem;">{{ $restaurantesConOfertas }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Barra de filtros ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('admin.empleos.index') }}" class="row g-3 align-items-center">

            <div class="col-12 col-sm-auto">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.empleos.index', array_merge(request()->except(['tipo','page']), ['tipo' => ''])) }}"
                       class="btn btn-sm rounded-pill fw-semibold px-3
                              {{ !request('tipo') || request('tipo') === '' ? 'btn-dark' : 'btn-outline-secondary' }}">
                        <i class="bi bi-grid-fill me-1"></i> Todos
                    </a>
                    <a href="{{ route('admin.empleos.index', array_merge(request()->except(['tipo','page']), ['tipo' => 'restaurante'])) }}"
                       class="btn btn-sm rounded-pill fw-semibold px-3
                              {{ request('tipo') === 'restaurante' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-shop me-1"></i> Restaurantes
                    </a>
                    <a href="{{ route('admin.empleos.index', array_merge(request()->except(['tipo','page']), ['tipo' => 'gastrobar'])) }}"
                       class="btn btn-sm rounded-pill fw-semibold px-3
                              {{ request('tipo') === 'gastrobar' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                        <i class="bi bi-cup-straw me-1"></i> Gastrobares
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control bg-light border-start-0 ps-0"
                           placeholder="Buscar por título..."
                           style="box-shadow: none;">
                    @if(request('tipo'))
                        <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                    @endif
                </div>
            </div>

            <div class="col-12 col-sm-auto d-flex gap-2">
                <button type="submit" class="btn btn-dark fw-semibold d-flex align-items-center gap-2 px-3">
                    <i class="bi bi-funnel-fill text-primary"></i> Filtrar
                </button>
                @if(request('search') || request('tipo'))
                    <a href="{{ route('admin.empleos.index') }}"
                       class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                       title="Limpiar filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- ── Tabla Principal de Empleos ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-uppercase text-muted border-bottom" style="background-color: #f8f9fa !important;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Puesto</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Establecimiento</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Tipo</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Salario</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Estado</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Publicado</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600; width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($empleos as $empleo)
                        <tr class="border-bottom" style="border-color: #edf2f7 !important;">

                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-pill me-2"
                                         style="width: 4px; height: 32px;
                                                background-color: {{ $empleo->gastrobar_id ? '#f59e0b' : '#3b82f6' }};">
                                    </div>
                                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2 fw-bold"
                                         style="width: 38px; height: 38px; font-size: 0.9rem;">
                                        {{ strtoupper(substr($empleo->titulo, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-capitalize"
                                              style="color: #2d3748 !important; font-size: 0.95rem;">
                                            {{ $empleo->titulo }}
                                        </span>
                                        <small class="text-muted" style="font-size: 0.75rem;">ID: #{{ $empleo->id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3" style="font-size: 0.9rem;">
                                @if($empleo->gastrobar_id)
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-cup-straw text-warning"></i>
                                        <span class="text-secondary">{{ $empleo->gastrobar->nombre ?? 'N/A' }}</span>
                                        <span class="badge ms-1 px-1 py-0"
                                              style="background:#fff8e1; color:#b45309; font-size:0.65rem;">
                                            gastrobar
                                        </span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-shop text-secondary"></i>
                                        <span class="text-secondary">{{ $empleo->restaurante->nombre ?? 'N/A' }}</span>
                                    </div>
                                @endif
                            </td>

                            <td class="py-3">
                                <span class="badge text-primary bg-primary bg-opacity-10 border border-primary border-opacity-20 px-2 py-1 fw-bold"
                                      style="font-size: 0.72rem;">
                                    <i class="bi bi-clock me-1"></i> {{ $empleo->tipo_contrato ?? 'No especificado' }}
                                </span>
                            </td>

                            <td class="py-3 fw-bold text-dark" style="font-size: 0.9rem; color: #2d3748 !important;">
                                {{ $empleo->salario ? 'C$ ' . number_format($empleo->salario, 2) : 'A convenir' }}
                            </td>

                            <td class="py-3 text-center">
                                @if($empleo->activo)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background-color: #e6fffa; color: #047481; border: 1px solid #b2f5ea; font-size: 0.72rem;">
                                        <span class="bg-success rounded-circle" style="width: 5px; height: 5px;"></span> Activa
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2 py-1 fw-normal"
                                          style="font-size: 0.72rem;">
                                        Inactiva
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 text-muted" style="font-size: 0.82rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $empleo->created_at->format('d M Y') }}
                            </td>

                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <a href="{{ route('admin.empleos.edit', $empleo) }}"
                                       class="text-secondary p-1 action-icon-edit"
                                       title="Editar Oferta"
                                       style="transition: color 0.2s;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    <form action="{{ route('admin.empleos.destroy', $empleo) }}" method="POST"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta oferta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete"
                                                title="Eliminar Oferta"
                                                style="box-shadow: none; text-decoration: none;">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-briefcase-fill d-block display-6 text-muted mb-3" style="opacity: 0.4;"></i>
                                <span class="fs-6 d-block mb-1">
                                    @if(request('tipo') === 'restaurante')
                                        No hay ofertas de empleo de restaurantes.
                                    @elseif(request('tipo') === 'gastrobar')
                                        No hay ofertas de empleo de gastrobares.
                                    @else
                                        No se encontraron ofertas de empleo registradas.
                                    @endif
                                </span>
                                <a href="{{ route('admin.empleos.create') }}"
                                   class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold mt-2">
                                    Crear primera oferta
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($empleos->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $empleos->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

<style>
    .action-icon-edit:hover   { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
