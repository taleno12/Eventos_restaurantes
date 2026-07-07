@extends('gastrobar.layout')
@section('title', 'Mis Empleos')

@section('content')
<div class="page-header mb-4">
    <div>
        <div class="page-title">Mis Empleos</div>
        <div class="page-sub">
            Gestiona las ofertas de trabajo de <strong>{{ $gastrobar->nombre }}</strong>
        </div>
    </div>
    <a href="{{ route('gastrobar.empleos.create') }}" class="btn-primary-panel">
        <i class="bi bi-plus-lg"></i>
        <span>Nueva Oferta</span>
    </a>
</div>

{{-- Métricas --}}
@php
    $total = $empleos->total();
    $activos = $empleos->where('activo', true)->count();
    $inactivos = $empleos->where('activo', false)->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="metric-card metric-card--total">
            <div class="metric-icon purple">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div class="metric-content">
                <span class="metric-label">Total ofertas</span>
                <span class="metric-value">{{ $total }}</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="metric-card metric-card--active">
            <div class="metric-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="metric-content">
                <span class="metric-label">Activas</span>
                <span class="metric-value">{{ $activos }}</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="metric-card metric-card--inactive">
            <div class="metric-icon orange">
                <i class="bi bi-pause-circle-fill"></i>
            </div>
            <div class="metric-content">
                <span class="metric-label">Inactivas</span>
                <span class="metric-value">{{ $inactivos }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Panel principal --}}
<div class="panel-card empleos-panel">
    <div class="panel-card__header">
        <div class="panel-card__title">
            <i class="bi bi-list-ul"></i>
            Listado de ofertas
        </div>

        @if($empleos->count() > 0)
        <div class="panel-toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="search"
                       id="empleosSearch"
                       class="search-input"
                       placeholder="Buscar por puesto o descripción..."
                       autocomplete="off">
            </div>

            <div class="filter-pills" role="group" aria-label="Filtrar por estado">
                <button type="button" class="filter-pill active" data-filter="all">Todos</button>
                <button type="button" class="filter-pill" data-filter="active">Activos</button>
                <button type="button" class="filter-pill" data-filter="inactive">Inactivos</button>
            </div>
        </div>
        @endif
    </div>

    <div class="card-body p-0">
        @if($empleos->count() > 0)

        {{-- Vista desktop --}}
        <div class="table-responsive d-none d-lg-block">
            <table class="panel-table empleos-table">
                <thead>
                    <tr>
                        <th class="ps-4">Puesto</th>
                        <th>Contrato</th>
                        <th>Salario</th>
                        <th>Fecha límite</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleos as $empleo)
                    @php $nuevas = $empleo->solicitudes()->where('estado', 'nueva')->count(); @endphp
                    <tr class="empleo-row"
                        data-status="{{ $empleo->activo ? 'active' : 'inactive' }}"
                        data-search="{{ Str::lower($empleo->titulo . ' ' . $empleo->descripcion) }}">
                        <td class="ps-4">
                            <div class="empleo-cell">
                                <span class="empleo-accent {{ $empleo->activo ? 'is-active' : 'is-inactive' }}"></span>
                                <div>
                                    <div class="empleo-title">{{ $empleo->titulo }}</div>
                                    <div class="empleo-desc">{{ Str::limit($empleo->descripcion, 60) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($empleo->tipo_contrato)
                                <span class="panel-badge badge-gray">{{ $empleo->tipo_contrato }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($empleo->salario)
                                <span class="panel-badge badge-purple">C$ {{ number_format($empleo->salario, 0) }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($empleo->fecha_limite)
                                <span class="date-chip">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($empleo->activo)
                                <span class="status-badge status-badge--active">
                                    <span class="status-dot"></span> Activo
                                </span>
                            @else
                                <span class="status-badge status-badge--inactive">
                                    <span class="status-dot"></span> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="action-group">
                                <a href="{{ route('gastrobar.solicitudes.index', $empleo) }}"
                                   class="action-btn action-btn--ghost"
                                   title="Ver solicitudes">
                                    <i class="bi bi-people"></i>
                                    @if($nuevas > 0)
                                        <span class="action-badge">{{ $nuevas }}</span>
                                    @endif
                                </a>

                                <a href="{{ route('gastrobar.empleos.edit', $empleo) }}"
                                   class="action-btn action-btn-edit"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('gastrobar.empleos.destroy', $empleo) }}"
                                      onsubmit="return confirm('¿Eliminar esta oferta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Vista móvil --}}
        <div class="empleos-cards d-lg-none">
            @foreach($empleos as $empleo)
            @php $nuevas = $empleo->solicitudes()->where('estado', 'nueva')->count(); @endphp
            <article class="empleo-card empleo-row"
                     data-status="{{ $empleo->activo ? 'active' : 'inactive' }}"
                     data-search="{{ Str::lower($empleo->titulo . ' ' . $empleo->descripcion) }}">
                <div class="empleo-card__top">
                    <div>
                        <h3 class="empleo-title">{{ $empleo->titulo }}</h3>
                        <p class="empleo-desc">{{ Str::limit($empleo->descripcion, 90) }}</p>
                    </div>
                    @if($empleo->activo)
                        <span class="status-badge status-badge--active">
                            <span class="status-dot"></span> Activo
                        </span>
                    @else
                        <span class="status-badge status-badge--inactive">
                            <span class="status-dot"></span> Inactivo
                        </span>
                    @endif
                </div>

                <div class="empleo-card__meta">
                    @if($empleo->tipo_contrato)
                        <span class="panel-badge badge-gray">{{ $empleo->tipo_contrato }}</span>
                    @endif
                    @if($empleo->salario)
                        <span class="panel-badge badge-purple">C$ {{ number_format($empleo->salario, 0) }}</span>
                    @endif
                    @if($empleo->fecha_limite)
                        <span class="date-chip">
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($empleo->fecha_limite)->format('d M, Y') }}
                        </span>
                    @endif
                </div>

                <div class="empleo-card__actions">
                    <a href="{{ route('gastrobar.solicitudes.index', $empleo) }}" class="btn-soft">
                        <i class="bi bi-people"></i>
                        Solicitudes
                        @if($nuevas > 0)
                            <span class="action-badge">{{ $nuevas }}</span>
                        @endif
                    </a>
                    <a href="{{ route('gastrobar.empleos.edit', $empleo) }}" class="btn-soft">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        <div class="panel-footer">
            {{ $empleos->links('pagination::bootstrap-5') }}
        </div>

        @else
        <div class="empty-state empty-state--empleos">
            <div class="empty-state__icon">
                <i class="bi bi-briefcase"></i>
            </div>
            <h3>Sin ofertas publicadas</h3>
            <p>Publica tu primera vacante para empezar a recibir solicitudes de candidatos.</p>
            <a href="{{ route('gastrobar.empleos.create') }}" class="btn-primary-panel">
                <i class="bi bi-plus-lg"></i>
                Crear primera oferta
            </a>
        </div>
        @endif
    </div>
</div>

@if($empleos->count() > 0)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('empleosSearch');
    const pills = document.querySelectorAll('.filter-pill');
    const rows = document.querySelectorAll('.empleo-row');
    let currentFilter = 'all';

    function applyFilters() {
        const term = (search?.value || '').trim().toLowerCase();

        rows.forEach(row => {
            const matchesSearch = !term || row.dataset.search.includes(term);
            const matchesFilter =
                currentFilter === 'all' ||
                row.dataset.status === currentFilter;

            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    search?.addEventListener('input', applyFilters);

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            currentFilter = pill.dataset.filter;
            applyFilters();
        });
    });
});
</script>
@endpush
@endif
@endsection
