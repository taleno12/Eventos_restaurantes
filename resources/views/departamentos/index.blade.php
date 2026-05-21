{{-- resources/views/departamentos/index.blade.php --}}
@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    
    {{-- ── Encabezado Principal ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold" style="color: #2d3748 !important;">
                <i class="bi bi-geo text-primary me-2"></i> Gestión de Departamentos
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i> Panel de control geográfico y densidad de establecimientos.
            </p>
        </div>
        <a href="{{ route('departamentos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Agregar Departamento
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

    {{-- ── Barra de Búsqueda y Herramientas ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white p-3">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="busquedaDepartamento" class="form-control bg-light border-start-0 ps-0" placeholder="Buscar por nombre o país..." style="box-shadow: none;">
                </div>
            </div>
            <div class="col-12 col-md-auto text-md-end">
                <button class="btn btn-white border bg-white text-secondary fw-bold shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-filetype-pdf text-danger fs-6"></i> EXPORTAR PDF
                </button>
            </div>
        </div>
    </div>

    {{-- ── Tabla Principal de Departamentos ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="tablaDepartamentos">
                    <thead class="bg-light text-uppercase text-muted border-bottom" style="background-color: #f8f9fa !important;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600; width: 100px;">ID</th>
                            <th class="py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Información Regional</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600; width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($departamentos as $depto)
                        <tr class="border-bottom" style="border-color: #edf2f7 !important;">
                            
                            {{-- ID --}}
                            <td class="ps-4 py-3">
                                <span class="text-muted fw-semibold" style="font-size: 0.85rem;">#{{ $depto->id }}</span>
                            </td>
                            
                            {{-- Información Regional --}}
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-pill me-3" style="width: 4px; height: 32px;"></div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-uppercase tracking-wide" style="color: #2d3748 !important; font-size: 0.95rem;">
                                            {{ $depto->nombre }}
                                        </span>
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            <span class="text-muted fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                                                <i class="bi bi-flag-fill text-secondary"></i> NICARAGUA
                                            </span>
                                            
                                            <a href="{{ route('admin.restaurantes.index', ['departamento_id' => $depto->id]) }}" 
                                               class="badge text-primary bg-primary bg-opacity-10 border border-primary border-opacity-20 px-2 py-1 text-decoration-none d-inline-flex align-items-center gap-1 fw-bold transition-all btn-badge" style="font-size: 0.7rem;">
                                                <i class="bi bi-egg-fried"></i> 
                                                <span>{{ $depto->restaurantes_count }} Restaurantes inscritos</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Panel de Acciones --}}
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <a href="{{ route('departamentos.edit', $depto->id) }}" class="text-secondary p-1 action-icon-edit" title="Editar Departamento" style="transition: color 0.2s;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ route('departamentos.destroy', $depto->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este departamento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete" title="Eliminar Departamento" style="box-shadow: none; text-decoration: none;">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <i class="bi bi-geo-alt-fill d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block italic">No hay datos disponibles en este momento.</span>
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
    .action-icon-edit:hover { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .btn-badge:hover { background-color: rgba(13, 110, 253, 0.2) !important; }
</style>

{{-- Scripts de Filtro Funcional --}}
<script>
    document.getElementById('busquedaDepartamento').addEventListener('input', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tablaDepartamentos tbody tr').forEach(row => {
            // Asegurarse de que no evalúe la fila vacía
            if(row.querySelector('td[colspan]')) return;
            
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection