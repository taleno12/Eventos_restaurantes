{{-- resources/views/reportes/index.blade.php --}}
@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('header')<div></div>@endsection

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-bar-chart-line text-primary me-2"></i> Reportes y Estadísticas
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Resumen general de la plataforma Gastro Nicaragua.
            </p>
        </div>
    </div>

    {{-- ── Filtro por Departamento ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('reportes.index') }}" class="row g-3 align-items-center">
            <div class="col-12 col-sm-6">
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
            <div class="col-12 col-sm-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark fw-semibold d-flex align-items-center gap-2 px-4">
                    <i class="bi bi-funnel-fill text-warning"></i> Filtrar
                </button>
                @if(request('departamento_id'))
                    <a href="{{ route('reportes.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center px-3" title="Limpiar filtro">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
            @if(request('departamento_id'))
                <div class="col-12">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-semibold" style="font-size:0.8rem;">
                        <i class="bi bi-geo-alt-fill me-1"></i>
                        Mostrando datos de: {{ $departamentos->firstWhere('id', request('departamento_id'))?->nombre }}
                    </span>
                </div>
            @endif
        </form>
    </div>

    {{-- ── TABS ── --}}
    <ul class="nav nav-pills mb-4 gap-2" id="reportesTabs" style="flex-direction: row !important; flex-wrap: nowrap;">
        <li class="nav-item">
            <button class="nav-link active fw-semibold px-4" data-tab="restaurantes">
                <i class="bi bi-shop me-1"></i> Restaurantes
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold px-4" data-tab="gastrobares">
                <i class="bi bi-music-note-beamed me-1"></i> Gastrobares
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold px-4" data-tab="empleos">
                <i class="bi bi-briefcase me-1"></i> Empleos
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold px-4" data-tab="eventos">
                <i class="bi bi-calendar-event me-1"></i> Eventos
            </button>
        </li>
    </ul>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. RESTAURANTES                                           --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="tab-section" id="tab-restaurantes">
        <div class="d-flex align-items-center mb-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary bg-opacity-10 p-2 me-2" style="width:36px;height:36px;">
                <i class="bi bi-shop fs-5"></i>
            </div>
            <h5 class="fw-bold mb-0 text-dark">Restaurantes</h5>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6 col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Total</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalRestaurantes }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Activos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $restaurantesActivos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary bg-secondary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-slash-circle"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Inactivos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $restaurantesInactivos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-body">
                <p class="fw-semibold text-muted text-uppercase mb-3" style="font-size:0.75rem;letter-spacing:0.5px;">
                    <i class="bi bi-geo-alt me-1"></i> Por Departamento
                </p>
                <canvas id="chartRestaurantes" height="80"></canvas>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. GASTROBARES                                            --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="tab-section d-none" id="tab-gastrobares">
        <div class="d-flex align-items-center mb-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center p-2 me-2" style="width:36px;height:36px;background-color:#f3e8ff;">
                <i class="bi bi-music-note-beamed fs-5" style="color:#7c3aed;"></i>
            </div>
            <h5 class="fw-bold mb-0 text-dark">Gastrobares</h5>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6 col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center p-3 fs-4" style="width:50px;height:50px;background-color:#f3e8ff;color:#7c3aed;">
                            <i class="bi bi-music-note-beamed"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Total</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalGastrobares }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Activos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $gastrobaresActivos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary bg-secondary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-slash-circle"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Inactivos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $gastrobaresInactivos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-body">
                <p class="fw-semibold text-muted text-uppercase mb-3" style="font-size:0.75rem;letter-spacing:0.5px;">
                    <i class="bi bi-geo-alt me-1"></i> Por Departamento
                </p>
                <canvas id="chartGastrobares" height="80"></canvas>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. EMPLEOS                                                --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="tab-section d-none" id="tab-empleos">
        <div class="d-flex align-items-center mb-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10 p-2 me-2" style="width:36px;height:36px;">
                <i class="bi bi-briefcase fs-5"></i>
            </div>
            <h5 class="fw-bold mb-0 text-dark">Empleos</h5>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Total</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalEmpleos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Activos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $empleosActivos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary bg-secondary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-pause-circle"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Inactivos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $empleosInactivos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-danger bg-danger bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Vencidos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $empleosVencidos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-body">
                <p class="fw-semibold text-muted text-uppercase mb-3" style="font-size:0.75rem;letter-spacing:0.5px;">
                    <i class="bi bi-diagram-3 me-1"></i> Por Tipo de Contrato
                </p>
                <div style="max-width: 320px; margin: 0 auto;">
                    <canvas id="chartEmpleos"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. EVENTOS                                                --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="tab-section d-none" id="tab-eventos">
        <div class="d-flex align-items-center mb-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-info bg-info bg-opacity-10 p-2 me-2" style="width:36px;height:36px;">
                <i class="bi bi-calendar-event fs-5"></i>
            </div>
            <h5 class="fw-bold mb-0 text-dark">Eventos</h5>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-info bg-info bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Total</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $totalEventos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Próximos</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $eventosFuturos }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary bg-secondary bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Pasados</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $eventosPasados }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-warning bg-warning bg-opacity-10 p-3 fs-4" style="width:50px;height:50px;">
                            <i class="bi bi-star"></i>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.5px;">Destacados</p>
                            <h3 class="fw-black text-dark mb-0" style="font-size:1.5rem;">{{ $eventosDestacados }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-body">
                <p class="fw-semibold text-muted text-uppercase mb-3" style="font-size:0.75rem;letter-spacing:0.5px;">
                    <i class="bi bi-graph-up me-1"></i> Eventos últimos 6 meses
                </p>
                <canvas id="chartEventos" height="80"></canvas>
            </div>
        </div>
    </div>

</div>

<style>
    #reportesTabs .nav-link {
        color: #6b7280;
        background: #f3f4f6;
        border-radius: 8px;
    }
    #reportesTabs .nav-link.active {
        background: #0d6efd;
        color: #fff;
    }
    #reportesTabs .nav-link:hover:not(.active) {
        background: #e5e7eb;
        color: #374151;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const dataRestaurantes = {
    labels: {!! json_encode($restaurantesPorDepto->pluck('nombre')) !!},
    counts: {!! json_encode($restaurantesPorDepto->pluck('restaurantes_count')) !!},
};
const dataGastrobares = {
    labels: {!! json_encode($gastrobaresPorDepto->pluck('nombre')) !!},
    counts: {!! json_encode($gastrobaresPorDepto->pluck('gastrobares_count')) !!},
};
const dataEmpleos = {
    labels: {!! json_encode($empleosPorTipo->keys()) !!},
    counts: {!! json_encode($empleosPorTipo->values()) !!},
};
const dataEventos = {
    labels: {!! json_encode($eventosPorMes->keys()) !!},
    counts: {!! json_encode($eventosPorMes->values()) !!},
};

let charts = {};

function initChart(tab) {
    if (charts[tab]) return;

    if (tab === 'restaurantes') {
        charts.restaurantes = new Chart(document.getElementById('chartRestaurantes'), {
            type: 'bar',
            data: {
                labels: dataRestaurantes.labels,
                datasets: [{ label: 'Restaurantes', data: dataRestaurantes.counts,
                    backgroundColor: 'rgba(13,110,253,0.15)', borderColor: 'rgba(13,110,253,0.8)',
                    borderWidth: 2, borderRadius: 6 }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    }
    if (tab === 'gastrobares') {
        charts.gastrobares = new Chart(document.getElementById('chartGastrobares'), {
            type: 'bar',
            data: {
                labels: dataGastrobares.labels,
                datasets: [{ label: 'Gastrobares', data: dataGastrobares.counts,
                    backgroundColor: 'rgba(124,58,237,0.15)', borderColor: 'rgba(124,58,237,0.8)',
                    borderWidth: 2, borderRadius: 6 }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    }
    if (tab === 'empleos') {
        charts.empleos = new Chart(document.getElementById('chartEmpleos'), {
            type: 'doughnut',
            data: {
                labels: dataEmpleos.labels,
                datasets: [{ data: dataEmpleos.counts,
                    backgroundColor: ['rgba(255,193,7,0.7)','rgba(13,110,253,0.7)','rgba(25,135,84,0.7)','rgba(220,53,69,0.7)','rgba(108,117,125,0.7)'],
                    borderWidth: 2 }]
            },
            options: { maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
        });
    }
    if (tab === 'eventos') {
        charts.eventos = new Chart(document.getElementById('chartEventos'), {
            type: 'line',
            data: {
                labels: dataEventos.labels,
                datasets: [{ label: 'Eventos', data: dataEventos.counts,
                    borderColor: 'rgba(13,202,240,0.9)', backgroundColor: 'rgba(13,202,240,0.1)',
                    borderWidth: 2, pointRadius: 4, fill: true, tension: 0.4 }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    }
}

const tabs     = document.querySelectorAll('#reportesTabs .nav-link');
const sections = document.querySelectorAll('.tab-section');

tabs.forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.tab;
        tabs.forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        sections.forEach(s => s.classList.add('d-none'));
        document.getElementById('tab-' + target).classList.remove('d-none');
        initChart(target);
    });
});

initChart('restaurantes');
</script>
@endsection
