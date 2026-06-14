@extends('gastrobar.layout')
@section('title', 'Estadísticas')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="page-header">
        <div>
            <div class="page-title">Estadísticas</div>
            <div class="page-sub">Actividad de {{ $gastrobar->nombre }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;background:white;border:1px solid var(--card-border);border-radius:10px;padding:7px 14px;font-size:12px;font-weight:700;color:var(--muted);">
            <i class="bi bi-calendar3" style="color:var(--primary);"></i>
            {{ now()->subDays(30)->format('d M') }} — {{ now()->format('d M, Y') }}
        </div>
    </div>

    {{-- Stats rápidas --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:28px;">
        <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
            <div class="metric-icon yellow"><i class="bi bi-star-fill"></i></div>
            <div>
                <div class="metric-value">{{ $avgRating > 0 ? $avgRating : '—' }}</div>
                <div class="metric-label">Calificación promedio</div>
            </div>
        </div>
        <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
            <div class="metric-icon purple"><i class="bi bi-chat-square-text"></i></div>
            <div>
                <div class="metric-value">{{ $totalReviews }}</div>
                <div class="metric-label">Reseñas totales</div>
            </div>
        </div>
        <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
            <div class="metric-icon blue"><i class="bi bi-calendar-event"></i></div>
            <div>
                <div class="metric-value">{{ $totalEventos }}</div>
                <div class="metric-label">Eventos publicados</div>
            </div>
        </div>
    </div>

    @if($totalReviews === 0 && $totalEventos === 0 && $totalEmpleos === 0)
    <div class="panel-card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-bar-chart" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.25;"></i>
                <p style="font-size:14px;font-weight:700;margin-bottom:6px;">Sin datos aún</p>
                <p style="font-size:13px;color:var(--muted);">Cuando publiques eventos, empleos o recibas reseñas, aquí verás las estadísticas.</p>
            </div>
        </div>
    </div>
    @else

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;margin-bottom:20px;">

        {{-- Gráfico dona reseñas --}}
        <div class="panel-card">
            <div class="card-header"><i class="bi bi-star me-1"></i> Distribución de reseñas</div>
            <div class="card-body">
                @if($totalReviews > 0)
                    <div class="text-center mb-3">
                        <div style="font-size:3rem;font-weight:900;color:var(--primary);line-height:1;">{{ $avgRating }}</div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#718096;margin-top:4px;">de 5 estrellas</div>
                        <div class="mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color:{{ $i <= round($avgRating) ? '#f6c90e' : '#e2e8f0' }};font-size:20px;">★</span>
                            @endfor
                        </div>
                    </div>
                    <canvas id="chart-dona" height="220"></canvas>
                @else
                    <div class="empty-state">
                        <i class="bi bi-star"></i>
                        <p>Aún no tienes reseñas.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Gráfico barras actividad --}}
        <div class="panel-card">
            <div class="card-header"><i class="bi bi-bar-chart me-1"></i> Resumen de actividad</div>
            <div class="card-body">
                <canvas id="chart-barras" height="220"></canvas>
            </div>
        </div>

    </div>

    {{-- Tarjetas de resumen --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
        <div style="background:#f7f5ff;border:1px solid #e9d5ff;border-radius:12px;padding:16px;text-align:center;">
            <i class="bi bi-calendar-event" style="font-size:24px;color:var(--primary);display:block;margin-bottom:8px;"></i>
            <div style="font-size:22px;font-weight:800;color:#2d3748;">{{ $totalEventos }}</div>
            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Eventos</div>
            <div style="font-size:12px;color:#48bb78;font-weight:600;margin-top:4px;">{{ $eventosProximos }} próximos</div>
        </div>
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:16px;text-align:center;">
            <i class="bi bi-briefcase" style="font-size:24px;color:#f59e0b;display:block;margin-bottom:8px;"></i>
            <div style="font-size:22px;font-weight:800;color:#2d3748;">{{ $totalEmpleos }}</div>
            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Empleos</div>
            <div style="font-size:12px;color:#48bb78;font-weight:600;margin-top:4px;">{{ $empleosActivos }} activos</div>
        </div>
        <div style="background:#f0fff4;border:1px solid #9ae6b4;border-radius:12px;padding:16px;text-align:center;">
            <i class="bi bi-images" style="font-size:24px;color:#48bb78;display:block;margin-bottom:8px;"></i>
            <div style="font-size:22px;font-weight:800;color:#2d3748;">{{ $totalFotos }}</div>
            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Fotos</div>
            <div style="font-size:12px;color:#718096;font-weight:600;margin-top:4px;">
                <a href="{{ route('gastrobar.galeria.index') }}" style="color:var(--primary);">Gestionar →</a>
            </div>
        </div>
        <div style="background:#fff5f5;border:1px solid #fed7d7;border-radius:12px;padding:16px;text-align:center;">
            <i class="bi bi-chat-square-heart" style="font-size:24px;color:#fc8181;display:block;margin-bottom:8px;"></i>
            <div style="font-size:22px;font-weight:800;color:#2d3748;">{{ $totalReviews }}</div>
            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Reseñas</div>
            <div style="font-size:12px;color:#718096;font-weight:600;margin-top:4px;">
                Promedio: {{ $avgRating > 0 ? $avgRating . ' ★' : '—' }}
            </div>
        </div>
    </div>

    {{-- Tabla reseñas recientes --}}
    @if($recentReviews->count() > 0)
    <div class="panel-card">
        <div class="card-header"><i class="bi bi-chat-square-text me-1"></i> Reseñas recientes</div>
        <div style="overflow-x:auto;">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Calificación</th>
                        <th>Título</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentReviews as $review)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold" style="font-size:13px;color:#2d3748;">{{ $review->user->name }}</div>
                        </td>
                        <td>
                            <div style="display:flex;gap:2px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color:{{ $i <= $review->rating ? '#f6c90e' : '#e2e8f0' }};font-size:14px;">★</span>
                                @endfor
                            </div>
                        </td>
                        <td>
                            @if($review->title)
                                <span class="fw-semibold" style="font-size:13px;">{{ $review->title }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($review->body)
                                <span style="font-size:12px;color:#718096;">{{ Str::limit($review->body, 80) }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">
                            <span class="small text-muted">{{ $review->created_at->diffForHumans() }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const colores = [
    '#f97316','#fb923c','#fdba74','#fcd34d','#86efac',
    '#67e8f9','#93c5fd','#c4b5fd','#f9a8d4','#d1d5db'
];

@if($totalReviews > 0)
new Chart(document.getElementById('chart-dona'), {
    type: 'doughnut',
    data: {
        labels: @json($labelsRating),
        datasets: [{
            data: @json($cantidadRating),
            backgroundColor: ['#f97316','#fb923c','#fdba74','#fcd34d','#d1d5db'],
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#5a6175',
                    font: { size: 11, weight: '600' },
                    padding: 14,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} reseñas` }
            }
        }
    }
});
@endif

new Chart(document.getElementById('chart-barras'), {
    type: 'bar',
    data: {
        labels: ['Reseñas', 'Eventos', 'Ev. próximos', 'Empleos', 'Emp. activos', 'Fotos'],
        datasets: [{
            label: 'Total',
            data: [
                {{ $totalReviews }},
                {{ $totalEventos }},
                {{ $eventosProximos }},
                {{ $totalEmpleos }},
                {{ $empleosActivos }},
                {{ $totalFotos }}
            ],
            backgroundColor: colores,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: { label: ctx => ` ${ctx.parsed.x}` }
            }
        },
        scales: {
            x: {
                grid: { color: '#f0f1f4' },
                ticks: { color: '#8b92a5', font: { size: 11, weight: '600' } }
            },
            y: {
                grid: { display: false },
                ticks: { color: '#1a1d23', font: { size: 12, weight: '700' } }
            }
        }
    }
});
</script>
@endsection
