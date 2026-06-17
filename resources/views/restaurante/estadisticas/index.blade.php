@extends('restaurante.layout')
@section('title', 'Estadísticas')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Estadísticas</div>
        <div class="page-sub">Rendimiento de los últimos 30 días</div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;background:white;border:1px solid var(--card-border);border-radius:10px;padding:7px 14px;font-size:12px;font-weight:700;color:var(--muted);">
        <i class="bi bi-calendar3" style="color:var(--primary);"></i>
        {{ now()->subDays(30)->format('d M') }} — {{ now()->format('d M, Y') }}
    </div>
</div>

{{-- Stats rápidas --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:28px;">
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-bag-check-fill"></i></div>
        <div>
            <div class="metric-value">{{ $totalPedidos }}</div>
            <div class="metric-label">Pedidos completados</div>
        </div>
    </div>
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-currency-dollar"></i></div>
        <div>
            <div class="metric-value" style="font-size:20px;">C$ {{ number_format($totalIngresos, 0) }}</div>
            <div class="metric-label">Ingresos totales</div>
        </div>
    </div>
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-egg-fried"></i></div>
        <div>
            <div class="metric-value">{{ $totalPlatos }}</div>
            <div class="metric-label">Platillos vendidos</div>
        </div>
    </div>
</div>

@if($platosMasVendidos->isEmpty())
<div class="panel-card">
    <div class="card-body">
        <div class="empty-state">
            <i class="bi bi-bar-chart" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.25;"></i>
            <p style="font-size:14px;font-weight:700;margin-bottom:6px;">Sin datos aún</p>
            <p style="font-size:13px;color:var(--muted);">Cuando recibas pedidos completados, aquí verás qué platos se venden más.</p>
        </div>
    </div>
</div>
@else

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    {{-- Gráfico de barras --}}
    <div class="panel-card">
        <div class="card-header">Platos más vendidos — unidades</div>
        <div class="card-body">
            <canvas id="chart-barras" height="280"></canvas>
        </div>
    </div>

    {{-- Gráfico de dona --}}
    <div class="panel-card">
        <div class="card-header">Distribución de ventas</div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="chart-dona" height="280"></canvas>
        </div>
    </div>

</div>

{{-- Tabla de ranking --}}
<div class="panel-card" style="margin-top:20px;">
    <div class="card-header">Ranking completo</div>
    <div style="overflow-x:auto;">
        <table class="panel-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Plato</th>
                    <th>Categoría</th>
                    <th style="text-align:right;">Unidades vendidas</th>
                    <th style="text-align:right;">Ingresos generados</th>
                    <th style="text-align:right;">% del total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($platosMasVendidos as $i => $plato)
                @php $porcentaje = $totalPlatos > 0 ? round(($plato->total_vendido / $totalPlatos) * 100, 1) : 0; @endphp
                <tr>
                    <td>
                        @if($i === 0)
                        <span style="font-size:18px;">🥇</span>
                        @elseif($i === 1)
                        <span style="font-size:18px;">🥈</span>
                        @elseif($i === 2)
                        <span style="font-size:18px;">🥉</span>
                        @else
                        <span style="font-size:13px;font-weight:700;color:var(--muted);">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:8px;overflow:hidden;background:#f5f6fa;flex-shrink:0;">
                                @if($plato->imagen)
                                <img src="{{ asset('storage/'.$plato->imagen) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-egg-fried" style="color:#c4bdb8;font-size:14px;"></i>
                                </div>
                                @endif
                            </div>
                            <span style="font-weight:700;color:var(--text);">{{ $plato->nombre }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="panel-badge badge-gray">{{ $plato->categoria ?: 'Sin categoría' }}</span>
                    </td>
                    <td style="text-align:right;font-weight:800;font-size:15px;color:var(--text);">
                        {{ number_format($plato->total_vendido) }}
                    </td>
                    <td style="text-align:right;font-weight:700;color:#16a34a;">
                        C$ {{ number_format($plato->total_ingresos, 0) }}
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex;align-items:center;gap:8px;justify-content:flex-end;">
                            <div style="width:80px;height:6px;background:#f0f1f4;border-radius:999px;overflow:hidden;">
                                <div style="height:100%;width:{{ $porcentaje }}%;background:#2563eb;border-radius:999px;transition:width 1s ease;"></div>
                            </div>
                            <span style="font-size:12px;font-weight:700;color:var(--muted);min-width:36px;">{{ $porcentaje }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endif
@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@if($platosMasVendidos->count() > 0)
<script>
    // @ts-nocheck
    const labels = @json($nombresPlatos);
    const vendidos = @json($cantidadesPlatos);

    const colores = [
        '#ea580c', '#16a34a', '#2563eb', '#9333ea', '#db2777',
        '#0891b2', '#ca8a04', '#dc2626', '#059669', '#4f46e5'
    ];

    new Chart(document.getElementById('chart-barras'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Unidades vendidas',
                data: vendidos,
                backgroundColor: colores.slice(0, labels.length),
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.x} unidades`
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: '#f0f1f4'
                    },
                    ticks: {
                        color: '#8b92a5',
                        font: {
                            size: 11,
                            weight: '600'
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#1a1d23',
                        font: {
                            size: 12,
                            weight: '700'
                        }
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('chart-dona'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: vendidos,
                backgroundColor: colores.slice(0, labels.length),
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
                        font: {
                            size: 11,
                            weight: '600'
                        },
                        padding: 14,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} uds`
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
