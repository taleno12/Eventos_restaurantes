@extends('gastrobar.layout')
@section('title', 'Mis Empleos')

@push('styles')
<style>
/* ===========================
   MIS EMPLEOS - ESTILOS
   =========================== */

.empleos-page .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.empleos-page .page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text, #1e293b);
    line-height: 1.2;
}

.empleos-page .page-sub {
    margin-top: .25rem;
    font-size: .92rem;
    color: var(--muted, #64748b);
}

.empleos-page .page-sub strong {
    color: var(--text, #1e293b);
    font-weight: 600;
}

/* Métricas */
.empleos-page .metric-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.15rem 1.35rem;
    background: #fff;
    border: 1px solid #e8ecf1;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
    transition: transform .2s ease, box-shadow .2s ease;
    height: 100%;
}

.empleos-page .metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
}

.empleos-page .metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.empleos-page .metric-icon.purple { background: rgba(124, 58, 237, .12); color: #7c3aed; }
.empleos-page .metric-icon.green  { background: rgba(34, 197, 94, .12);  color: #16a34a; }
.empleos-page .metric-icon.orange { background: rgba(249, 115, 22, .12); color: #ea580c; }

.empleos-page .metric-content {
    display: flex;
    flex-direction: column;
    gap: .1rem;
}

.empleos-page .metric-label {
    font-size: .78rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .03em;
}

.empleos-page .metric-value {
    font-size: 1.65rem;
    font-weight: 800;
    line-height: 1;
    color: #0f172a;
}

/* Panel principal */
.empleos-page .empleos-panel {
    background: #fff;
    border: 1px solid #e8ecf1;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
}

.empleos-page .panel-card__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.15rem 1.35rem;
    border-bottom: 1px solid #eef2f6;
    background: #fff;
}

.empleos-page .panel-card__title {
    display: flex;
    align-items: center;
    gap: .55rem;
    font-weight: 700;
    font-size: .95rem;
    color: #1e293b;
}

.empleos-page .panel-card__title i {
    color: #7c3aed;
}

.empleos-page .panel-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .75rem;
}

/* Buscador */
.empleos-page .search-box {
    position: relative;
    min-width: 280px;
}

.empleos-page .search-box i {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: .9rem;
    pointer-events: none;
}

.empleos-page .search-input {
    width: 100%;
    padding: .62rem .95rem .62rem 2.45rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: .88rem;
    color: #1e293b;
    transition: all .15s ease;
}

.empleos-page .search-input:focus {
    outline: none;
    border-color: #7c3aed;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
}

.empleos-page .search-input::placeholder {
    color: #94a3b8;
}

/* Filtros */
.empleos-page .filter-pills {
    display: flex;
    gap: .45rem;
    background: #f1f5f9;
    padding: .25rem;
    border-radius: 999px;
}

.empleos-page .filter-pill {
    border: none;
    background: transparent;
    color: #64748b;
    padding: .45rem .9rem;
    border-radius: 999px;
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s ease;
}

.empleos-page .filter-pill:hover {
    color: #7c3aed;
}

.empleos-page .filter-pill.active {
    background: #7c3aed;
    color: #fff;
    box-shadow: 0 2px 8px rgba(124, 58, 237, .25);
}

/* Tabla */
.empleos-page .panel-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.empleos-page .panel-table thead th {
    padding: .9rem 1rem;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #94a3b8;
    background: #fafbfc;
    border-bottom: 1px solid #eef2f6;
    white-space: nowrap;
}

.empleos-page .panel-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: .88rem;
    color: #334155;
}

.empleos-page .panel-table tbody tr:hover {
    background: #fafbff;
}

.empleos-page .panel-table tbody tr:last-child td {
    border-bottom: none;
}

/* Celda puesto */
.empleos-page .empleo-cell {
    display: flex;
    align-items: center;
    gap: .85rem;
}

.empleos-page .empleo-accent {
    width: 4px;
    height: 38px;
    border-radius: 999px;
    flex-shrink: 0;
}

.empleos-page .empleo-accent.is-active   { background: #7c3aed; }
.empleos-page .empleo-accent.is-inactive { background: #cbd5e1; }

.empleos-page .empleo-title {
    font-size: .92rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: .15rem;
}

.empleos-page .empleo-desc {
    font-size: .8rem;
    color: #64748b;
    line-height: 1.35;
}

/* Badges */
.empleos-page .panel-badge {
    display: inline-flex;
    align-items: center;
    padding: .32rem .65rem;
    border-radius: 999px;
    font-size: .76rem;
    font-weight: 600;
    white-space: nowrap;
}

.empleos-page .badge-gray   { background: #f1f5f9; color: #475569; }
.empleos-page .badge-purple  { background: rgba(124, 58, 237, .1); color: #6d28d9; }

.empleos-page .date-chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .82rem;
    font-weight: 600;
    color: #334155;
    white-space: nowrap;
}

.empleos-page .date-chip i {
    color: #7c3aed;
    font-size: .78rem;
}

.empleos-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .35rem .75rem;
    border-radius: 999px;
    font-size: .76rem;
    font-weight: 700;
}

.empleos-page .status-badge--active {
    background: rgba(34, 197, 94, .12);
    color: #15803d;
}

.empleos-page .status-badge--inactive {
    background: rgba(100, 116, 139, .12);
    color: #475569;
}

.empleos-page .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* Acciones */
.empleos-page .action-group {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: .35rem;
}

.empleos-page .action-btn {
    position: relative;
    width: 36px;
    height: 36px;
    display: inline-grid;
    place-items: center;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    transition: all .15s ease;
    text-decoration: none;
    cursor: pointer;
}

.empleos-page .action-btn:hover {
    transform: translateY(-1px);
}

.empleos-page .action-btn--ghost:hover {
    background: rgba(124, 58, 237, .08);
    border-color: rgba(124, 58, 237, .2);
    color: #7c3aed;
}

.empleos-page .action-btn-edit:hover {
    background: rgba(59, 130, 246, .08);
    border-color: rgba(59, 130, 246, .2);
    color: #2563eb;
}

.empleos-page .action-btn-delete:hover {
    background: rgba(239, 68, 68, .08);
    border-color: rgba(239, 68, 68, .2);
    color: #dc2626;
}

.empleos-page .action-group form {
    margin: 0;
}

.empleos-page .action-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    border-radius: 999px;
    background: #ef4444;
    color: #fff;
    font-size: .62rem;
    font-weight: 700;
    display: grid;
    place-items: center;
    border: 2px solid #fff;
}

/* Footer paginación */
.empleos-page .panel-footer {
    padding: 1rem 1.35rem;
    border-top: 1px solid #eef2f6;
    background: #fafbfc;
}

/* Cards móviles */
.empleos-page .empleos-cards {
    display: grid;
    gap: 1rem;
    padding: 1rem;
}

.empleos-page .empleo-card {
    border: 1px solid #e8ecf1;
    border-radius: 14px;
    padding: 1rem;
    background: #fff;
}

.empleos-page .empleo-card__top {
    display: flex;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .85rem;
}

.empleos-page .empleo-card__meta {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-bottom: 1rem;
}

.empleos-page .empleo-card__actions {
    display: flex;
    gap: .5rem;
}

.empleos-page .btn-soft {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    padding: .62rem .85rem;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-size: .84rem;
    font-weight: 600;
    text-decoration: none;
    position: relative;
    transition: all .15s ease;
}

.empleos-page .btn-soft:hover {
    background: rgba(124, 58, 237, .08);
    border-color: rgba(124, 58, 237, .2);
    color: #7c3aed;
}

/* Empty state */
.empleos-page .empty-state--empleos {
    text-align: center;
    padding: 4rem 1.5rem;
}

.empleos-page .empty-state__icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 1rem;
    border-radius: 18px;
    background: rgba(124, 58, 237, .1);
    color: #7c3aed;
    display: grid;
    place-items: center;
    font-size: 1.8rem;
}

.empleos-page .empty-state--empleos h3 {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: .35rem;
    color: #0f172a;
}

.empleos-page .empty-state--empleos p {
    color: #64748b;
    margin-bottom: 1.25rem;
    font-size: .92rem;
}

/* Responsive */
@media (max-width: 991px) {
    .empleos-page .panel-card__header {
        flex-direction: column;
        align-items: stretch;
    }

    .empleos-page .panel-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .empleos-page .search-box {
        min-width: 100%;
    }

    .empleos-page .filter-pills {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .empleos-page .page-title {
        font-size: 1.45rem;
    }

    .empleos-page .metric-value {
        font-size: 1.4rem;
    }
}
</style>
@endpush

@section('content')
@php
    $total = $empleos->total();
    $activos = $empleos->where('activo', true)->count();
    $inactivos = $empleos->where('activo', false)->count();
@endphp

<div class="empleos-page">

    {{-- Header --}}
    <div class="page-header">
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
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="metric-card">
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
            <div class="metric-card">
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
            <div class="metric-card">
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

    {{-- Panel --}}
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

            {{-- Tabla desktop --}}
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
                        @php
                            $nuevas = $empleo->nuevas_count ?? $empleo->solicitudes()->where('estado', 'nueva')->count();
                        @endphp
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

            {{-- Cards móvil --}}
            <div class="empleos-cards d-lg-none">
                @foreach($empleos as $empleo)
                @php
                    $nuevas = $empleo->nuevas_count ?? $empleo->solicitudes()->where('estado', 'nueva')->count();
                @endphp
                <article class="empleo-card empleo-row"
                         data-status="{{ $empleo->activo ? 'active' : 'inactive' }}"
                         data-search="{{ Str::lower($empleo->titulo . ' ' . $empleo->descripcion) }}">
                    <div class="empleo-card__top">
                        <div>
                            <h3 class="empleo-title">{{ $empleo->titulo }}</h3>
                            <p class="empleo-desc mb-0">{{ Str::limit($empleo->descripcion, 90) }}</p>
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
</div>
@endsection

@if($empleos->count() > 0)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('empleosSearch');
    const pills = document.querySelectorAll('.filter-pill');
    const rows = document.querySelectorAll('.empleo-row');
    let currentFilter = 'all';

    function applyFilters() {
        const term = (search?.value || '').trim().toLowerCase();

        rows.forEach(function (row) {
            const matchesSearch = !term || row.dataset.search.includes(term);
            const matchesFilter = currentFilter === 'all' || row.dataset.status === currentFilter;
            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    if (search) {
        search.addEventListener('input', applyFilters);
    }

    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            pills.forEach(function (p) { p.classList.remove('active'); });
            pill.classList.add('active');
            currentFilter = pill.dataset.filter;
            applyFilters();
        });
    });
});
</script>
@endpush
@endif
