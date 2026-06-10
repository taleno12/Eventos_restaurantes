@extends('restaurante.layout')
@section('title', 'Pedidos')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Pedidos</div>
        <div class="page-sub">Gestión en tiempo real — actualiza cada 30 seg</div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;">
        <div id="live-indicator" style="display:flex;align-items:center;gap:6px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);padding:6px 12px;border-radius:999px;font-size:11px;font-weight:700;color:#22c55e;">
            <span style="width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block;animation:pulse 2s infinite;"></span>
            En vivo
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Stats rápidas --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
    <div class="card card-body" style="display:flex;align-items:center;gap:14px;">
        <div style="width:40px;height:40px;background:rgba(245,158,11,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-clock" style="color:#f59e0b;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:24px;font-weight:800;color:white;">{{ $pendientes }}</div>
            <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;">Pendientes</div>
        </div>
    </div>
    <div class="card card-body" style="display:flex;align-items:center;gap:14px;">
        <div style="width:40px;height:40px;background:rgba(34,197,94,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-receipt" style="color:#22c55e;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:24px;font-weight:800;color:white;">{{ $totalHoy }}</div>
            <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;">Pedidos hoy</div>
        </div>
    </div>
    <div class="card card-body" style="display:flex;align-items:center;gap:14px;">
        <div style="width:40px;height:40px;background:var(--orange-dim);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-coins" style="color:var(--orange);font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:24px;font-weight:800;color:white;">C$ {{ number_format($ingresoHoy, 0) }}</div>
            <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;">Ingresos hoy</div>
        </div>
    </div>
</div>

{{-- Columnas por estado --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;" id="pedidos-board">

    @php
        $estadosOrden = ['pendiente','confirmado','en_preparacion','listo','entregado'];
        $estadosInfo  = \App\Models\Pedido::ESTADOS;
    @endphp

    @foreach($estadosOrden as $estado)
    <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
        {{-- Header columna --}}
        <div style="padding:12px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $estadosInfo[$estado]['color'] }};display:inline-block;"></span>
                <span style="font-size:12px;font-weight:800;color:white;text-transform:uppercase;letter-spacing:0.1em;">{{ $estadosInfo[$estado]['label'] }}</span>
            </div>
            <span style="font-size:11px;color:var(--muted);font-weight:700;">
                {{ isset($pedidos[$estado]) ? $pedidos[$estado]->count() : 0 }}
            </span>
        </div>

        {{-- Pedidos de esta columna --}}
        <div style="padding:10px;display:flex;flex-direction:column;gap:8px;min-height:80px;" id="col-{{ $estado }}">
            @if(isset($pedidos[$estado]) && $pedidos[$estado]->count() > 0)
                @foreach($pedidos[$estado] as $pedido)
                <div class="pedido-card" style="background:#1a1a1a;border:1px solid #262626;border-radius:12px;padding:12px;transition:border-color 0.2s;"
                     onmouseover="this.style.borderColor='var(--orange)'" onmouseout="this.style.borderColor='#262626'">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                        <div>
                            <div style="font-size:12px;font-weight:800;color:white;">#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $pedido->user->name }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:13px;font-weight:800;color:var(--orange);">C$ {{ number_format($pedido->total, 0) }}</div>
                            <div style="font-size:10px;color:var(--muted);">{{ $pedido->created_at->format('H:i') }}</div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div style="margin-bottom:10px;">
                        @foreach($pedido->items as $item)
                            <div style="font-size:11px;color:#888;display:flex;justify-content:space-between;">
                                <span>{{ $item->cantidad }}x {{ $item->plato->nombre ?? 'Plato eliminado' }}</span>
                                <span>C$ {{ number_format($item->subtotal, 0) }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($pedido->notas)
                        <div style="font-size:11px;color:#666;background:#111;border-radius:6px;padding:6px 8px;margin-bottom:8px;">
                            <i class="fas fa-sticky-note" style="color:#f59e0b;font-size:9px;margin-right:4px;"></i>{{ $pedido->notas }}
                        </div>
                    @endif

                    {{-- Tipo --}}
                    <div style="margin-bottom:8px;">
                        <span style="font-size:10px;font-weight:700;padding:3px 8px;border-radius:999px;background:#222;color:#888;">
                            {{ $pedido->tipo === 'mesa' ? '🍽 Mesa' : '🥡 Para llevar' }}
                        </span>
                    </div>

                    {{-- Cambiar estado --}}
                    @if($estado !== 'entregado' && $estado !== 'cancelado')
                    <form method="POST" action="{{ route('restaurante.pedidos.estado', $pedido) }}">
                        @csrf @method('PATCH')
                        <select name="estado" onchange="this.form.submit()"
                                style="width:100%;background:#111;border:1px solid #333;color:white;border-radius:8px;padding:6px 10px;font-size:11px;font-weight:700;cursor:pointer;outline:none;">
                            @foreach($estadosInfo as $key => $info)
                                @if($key !== 'cancelado' || $estado === 'pendiente')
                                <option value="{{ $key }}" {{ $pedido->estado === $key ? 'selected' : '' }}>
                                    {{ $info['label'] }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </form>
                    @else
                        <div style="font-size:11px;color:#444;text-align:center;padding:4px;">
                            {{ $estado === 'entregado' ? '✓ Completado' : '✗ Cancelado' }}
                        </div>
                    @endif
                </div>
                @endforeach
            @else
                <div style="text-align:center;padding:20px;color:#333;font-size:12px;">
                    Sin pedidos
                </div>
            @endif
        </div>
    </div>
    @endforeach

</div>
@endsection

@section('scripts')
<style>
@keyframes pulse {
    0%,100% { box-shadow: 0 0 0 2px rgba(34,197,94,0.2); }
    50%      { box-shadow: 0 0 0 5px rgba(34,197,94,0.05); }
}
</style>
<script>
// Auto-refresh cada 30 segundos
setInterval(() => { window.location.reload(); }, 30000);

// Countdown visual
let segundos = 30;
const indicator = document.getElementById('live-indicator');
setInterval(() => {
    segundos--;
    if (segundos <= 0) segundos = 30;
    indicator.title = `Actualiza en ${segundos}s`;
}, 1000);
</script>
@endsection