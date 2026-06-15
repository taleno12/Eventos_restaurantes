@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold" style="color: #2d3748 !important;">
                <i class="bi bi-calendar-event text-primary me-2"></i> Gestión de Eventos
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Administra y programa las actividades gastronómicas.
            </p>
        </div>
        <a href="{{ route('eventos.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Evento
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

    {{-- ── Barra de filtros ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('eventos.index') }}" class="row g-3 align-items-center">

            <div class="col-12 col-sm-auto">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('eventos.index', array_merge(request()->except(['tipo','page']), ['tipo' => ''])) }}"
                       class="btn btn-sm rounded-pill fw-semibold px-3
                              {{ !request('tipo') || request('tipo') === '' ? 'btn-dark' : 'btn-outline-secondary' }}">
                        <i class="bi bi-grid-fill me-1"></i> Todos
                    </a>
                    <a href="{{ route('eventos.index', array_merge(request()->except(['tipo','page']), ['tipo' => 'restaurante'])) }}"
                       class="btn btn-sm rounded-pill fw-semibold px-3
                              {{ request('tipo') === 'restaurante' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-shop me-1"></i> Restaurantes
                    </a>
                    <a href="{{ route('eventos.index', array_merge(request()->except(['tipo','page']), ['tipo' => 'gastrobar'])) }}"
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
                    <a href="{{ route('eventos.index') }}"
                       class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                       title="Limpiar filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- ── Tabla principal ── --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-uppercase text-muted border-bottom"
                           style="background-color: #f8f9fa !important;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0"
                                style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Evento</th>
                            <th class="py-3 text-secondary border-0"
                                style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Fecha / Hora</th>
                            <th class="py-3 text-secondary border-0"
                                style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Precio</th>
                            <th class="py-3 text-secondary border-0"
                                style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Ubicación</th>
                            <th class="py-3 text-secondary border-0"
                                style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Destacado</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0"
                                style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600; width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($eventos as $evento)
                        <tr class="border-bottom" style="border-color: #edf2f7 !important;">

                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-pill me-2"
                                         style="width: 4px; height: 26px;
                                                background-color: {{ $evento->gastrobar_id ? '#f59e0b' : '#3b82f6' }};">
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-capitalize"
                                              style="color: #2d3748 !important; font-size: 0.95rem;">
                                            {{ $evento->titulo }}
                                        </span>
                                        <small class="text-muted" style="font-size: 0.8rem;">ID: #{{ $evento->id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 text-secondary" style="font-size: 0.9rem;">
                                <i class="bi bi-clock me-1 text-muted"></i>
                                {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M Y, h:i A') }}
                            </td>

                            <td class="py-3 fw-bold text-dark"
                                style="font-size: 0.95rem; color: #2d3748 !important;">
                                C$ {{ number_format($evento->precio, 2) }}
                            </td>

                            <td class="py-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-geo-alt text-danger me-1" style="font-size: 0.85rem;"></i>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium"
                                          style="font-size: 0.75rem;">
                                        {{ $evento->departamento->nombre ?? '—' }}
                                    </span>
                                </div>
                                <small class="text-muted d-block ps-3" style="font-size: 0.8rem;">
                                    @if($evento->gastrobar_id)
                                        <i class="bi bi-cup-straw me-1 text-warning"></i>
                                        {{ $evento->gastrobar->nombre ?? 'N/A' }}
                                        <span class="badge ms-1 px-1 py-0"
                                              style="background:#fff8e1; color:#b45309; font-size:0.65rem;">
                                            gastrobar
                                        </span>
                                    @else
                                        <i class="bi bi-shop me-1 text-secondary"></i>
                                        {{ $evento->restaurante->nombre ?? 'N/A' }}
                                    @endif
                                </small>
                            </td>

                            <td class="py-3">
                                @if($evento->is_destacado)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold"
                                          style="background-color: #e6fffa; color: #047481;
                                                 border: 1px solid #b2f5ea; font-size: 0.75rem;">
                                        <i class="bi bi-star-fill me-1" style="font-size: 0.7rem;"></i> Destacado
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2 py-1 fw-normal"
                                          style="font-size: 0.75rem;">
                                        Normal
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <a href="{{ route('eventos.edit', $evento->id) }}"
                                       class="text-secondary p-1 action-icon"
                                       title="Editar Evento"
                                       style="transition: color 0.2s;">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                    <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete"
                                                title="Eliminar Evento"
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
                                <i class="bi bi-calendar-x d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block mb-1">
                                    @if(request('tipo') === 'restaurante')
                                        No hay eventos de restaurantes.
                                    @elseif(request('tipo') === 'gastrobar')
                                        No hay eventos de gastrobares.
                                    @else
                                        No se encontraron eventos registrados.
                                    @endif
                                </span>
                                <a href="{{ route('eventos.create') }}"
                                   class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold mt-2">
                                    Crear primer evento
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
        {{ $eventos->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>

<style>
    .action-icon:hover        { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
