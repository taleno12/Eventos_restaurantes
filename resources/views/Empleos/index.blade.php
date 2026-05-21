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
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i> Gestiona las vacantes publicadas por los restaurantes.
            </p>
        </div>
        <a href="{{ route('admin.empleos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nueva Oferta
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

    {{-- ── Tabla Principal de Empleos ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-uppercase text-muted border-bottom" style="background-color: #f8f9fa !important;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Puesto</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Restaurante</th>
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
                            
                            {{-- Puesto & ID --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width: 4px; height: 32px;"></div>
                                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                        {{ strtoupper(substr($empleo->titulo, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-capitalize" style="color: #2d3748 !important; font-size: 0.95rem;">{{ $empleo->titulo }}</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">ID: #{{ $empleo->id }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Restaurante --}}
                            <td class="py-3 text-secondary" style="font-size: 0.9rem;">
                                <span>
                                    <i class="bi bi-shop me-1 text-muted"></i> {{ $empleo->restaurante->nombre ?? 'N/A' }}
                                </span>
                            </td>
                            
                            {{-- Tipo de Contrato --}}
                            <td class="py-3">
                                <span class="badge text-primary bg-primary bg-opacity-10 border border-primary border-opacity-20 px-2.5 py-1 fw-bold" style="font-size: 0.72rem;">
                                    <i class="bi bi-clock me-1"></i> {{ $empleo->tipo_contrato ?? 'No especificado' }}
                                </span>
                            </td>
                            
                            {{-- Salario --}}
                            <td class="py-3 fw-bold text-dark" style="font-size: 0.9rem; color: #2d3748 !important;">
                                {{ $empleo->salario ? 'C$ ' . number_format($empleo->salario, 2) : 'A convenir' }}
                            </td>
                            
                            {{-- Estado Activa / Inactiva --}}
                            <td class="py-3 text-center">
                                @if($empleo->activo)
                                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background-color: #e6fffa; color: #047481; border: 1px solid #b2f5ea; font-size: 0.72rem;">
                                        <span class="bg-success rounded-circle animate-pulse" style="width: 5px; height: 5px;"></span> Activa
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1 fw-normal" style="font-size: 0.72rem;">
                                        Inactiva
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Fecha de Publicación --}}
                            <td class="py-3 text-muted" style="font-size: 0.82rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $empleo->created_at->format('d M Y') }}
                            </td>
                            
                            {{-- Panel de Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <a href="{{ route('admin.empleos.edit', $empleo) }}" class="text-secondary p-1 action-icon-edit" title="Editar Oferta" style="transition: color 0.2s;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.empleos.destroy', $empleo) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta oferta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete" title="Eliminar Oferta" style="box-shadow: none; text-decoration: none;">
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
                                <span class="fs-6 d-block">No se encontraron ofertas de empleo registradas.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Paginación Impecable --}}
    @if($empleos->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $empleos->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .action-icon-edit:hover { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection