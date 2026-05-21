{{-- resources/views/restaurantes/indexadmin.blade.php --}}
@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    
    {{-- ── Encabezado Principal ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold" style="color: #2d3748 !important;">
                <i class="bi bi-shop text-primary me-2"></i> Gestión de Restaurantes
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i> Control, estados y localización de establecimientos en la plataforma.
            </p>
        </div>
        {{-- RUTA REPARADA --}}
        <a href="{{ route('admin.restaurantes.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Restaurante
        </a>
    </div>

    {{-- ── Mensaje de Éxito ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
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
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size: 1.5rem;">{{ $restaurantes->total() }}</h3>
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
        <form method="GET" action="{{ route('admin.restaurantes.index') }}" class="row g-3 align-items-center">
            <div class="col-12 col-sm-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0 ps-0" placeholder="Buscar por nombre..." style="box-shadow: none;">
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
                    <a href="{{ route('admin.restaurantes.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center" title="Limpiar Filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Tabla Principal de Restaurantes ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-uppercase text-muted border-bottom" style="background-color: #f8f9fa !important;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Restaurante</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Especialidad</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Ubicación</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Contacto</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Estado</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600; width: 160px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($restaurantes as $restaurante)
                        <tr class="border-bottom" style="border-color: #edf2f7 !important;">
                            
                            {{-- ID, Imagen y Nombre --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width: 4px; height: 32px;"></div>
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 40px; height: 40px; flex-shrink: 0;">
                                        @if($restaurante->foto_portada)
                                            <img src="{{ asset('storage/' . $restaurante->foto_portada) }}" alt="{{ $restaurante->nombre }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <i class="bi bi-shop text-muted fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-uppercase tracking-wide" style="color: #2d3748 !important; font-size: 0.9rem;">{{ $restaurante->nombre }}</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">ID: #{{ $restaurante->id }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Especialidad --}}
                            <td class="py-3">
                                @if($restaurante->especialidad)
                                    <span class="badge text-primary bg-primary bg-opacity-10 border border-primary border-opacity-20 px-2 py-1 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                                        {{ $restaurante->especialidad }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            
                            {{-- Ubicación --}}
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    @if($restaurante->departamento)
                                        <span class="text-dark fw-semibold small d-flex align-items-center gap-1 text-uppercase">
                                            <i class="bi bi-geo-alt-fill text-danger" style="font-size: 0.8rem;"></i>
                                            {{ $restaurante->departamento->nombre }}
                                        </span>
                                    @endif
                                    @if($restaurante->municipio)
                                        <small class="text-muted ps-3" style="font-size: 0.75rem;">
                                            <i class="bi bi-building me-1"></i>{{ $restaurante->municipio->nombre }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            
                            {{-- Canales de Contacto --}}
                            <td class="py-3">
                                <div class="d-flex flex-column gap-1">
                                    @if($restaurante->email)
                                        <span class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                            <i class="bi bi-envelope text-secondary"></i> {{ Str::limit($restaurante->email, 22) }}
                                        </span>
                                    @endif
                                    @if($restaurante->whatsapp)
                                        <span class="text-dark small fw-medium d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                            <i class="bi bi-whatsapp text-success"></i> {{ $restaurante->whatsapp }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            {{-- Estado Activo/Inactivo --}}
                            <td class="py-3 text-center">
                                @if($restaurante->activo ?? true)
                                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background-color: #e6fffa; color: #047481; border: 1px solid #b2f5ea; font-size: 0.72rem;">
                                        <span class="bg-success rounded-circle" style="width: 5px; height: 5px;"></span> ACTIVO
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1 fw-normal" style="font-size: 0.72rem;">
                                        INACTIVO
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Panel de Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    {{-- RUTAS REPARADAS --}}
                                    <a href="{{ route('admin.restaurantes.show', $restaurante->id) }}" class="text-secondary p-1 action-icon-view" title="Ver Detalle" style="transition: color 0.2s;">
                                        <i class="bi bi-eye fs-5"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.restaurantes.edit', $restaurante->id) }}" class="text-secondary p-1 action-icon-edit" title="Editar Restaurante" style="transition: color 0.2s;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.restaurantes.destroy', $restaurante->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este restaurante? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete" title="Eliminar Restaurante" style="box-shadow: none; text-decoration: none;">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-shop-window d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block mb-2">No se encontraron establecimientos registrados.</span>
                                <a href="{{ route('admin.restaurantes.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                                    Agregar Primer Restaurante
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
        {{ $restaurantes->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .action-icon-view:hover { color: #0d6efd !important; }
    .action-icon-edit:hover { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection