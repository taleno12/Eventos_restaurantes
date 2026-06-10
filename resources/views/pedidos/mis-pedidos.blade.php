<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Pedidos — Gastro Nicaragua</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700,800" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --orange: #ea580c; --dark: #0c0a09; --stone: #fafaf9;
            --border: #e7e5e4; --muted: #78716c; --text: #1c1917;
        }
        body { font-family: 'Instrument Sans', sans-serif; background: var(--stone); color: var(--text); min-height: 100vh; }

        .topbar {
            background: var(--dark); border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 16px 40px; display: flex; align-items: center; justify-content: space-between;
        }
        @media (max-width: 600px) { .topbar { padding: 14px 16px; } }

        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon { width: 32px; height: 32px; background: var(--orange); border-radius: 9px; display: flex; align-items: center; justify-content: center; }

        .wrap { max-width: 860px; margin: 0 auto; padding: 40px; }
        @media (max-width: 600px) { .wrap { padding: 20px 16px; } }

        .page-title { font-size: 24px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: var(--muted); margin-bottom: 28px; }

        .pedido-card {
            background: white; border: 1px solid var(--border); border-radius: 16px;
            overflow: hidden; margin-bottom: 14px; transition: box-shadow 0.2s;
        }
        .pedido-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.06); }

        .pedido-header {
            padding: 16px 20px; display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 10px;
            border-bottom: 1px solid var(--border);
        }

        .estado-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 12px; border-radius: 999px;
            font-size: 11px; font-weight: 800; letter-spacing: 0.08em;
        }

        .pedido-items { padding: 14px 20px; }
        .item-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 6px 0; border-bottom: 1px solid #f5f5f4; font-size: 13px;
        }
        .item-row:last-child { border-bottom: none; }

        .pedido-footer {
            padding: 12px 20px; background: #fafaf9; border-top: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }

        .empty-state {
            text-align: center; padding: 60px 20px; color: var(--muted);
        }
        .empty-state i { font-size: 40px; margin-bottom: 16px; display: block; opacity: 0.3; }

        .btn-orange {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--orange); color: white; text-decoration: none;
            padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700;
            transition: background 0.2s;
        }
        .btn-orange:hover { background: #c2410c; }

        .progress-bar {
            display: flex; align-items: center; gap: 0; padding: 10px 20px;
            background: #f9fafb; border-top: 1px solid var(--border);
            overflow-x: auto;
        }
        .progress-step {
            display: flex; align-items: center; gap: 0; flex-shrink: 0;
        }
        .step-dot {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 800; flex-shrink: 0;
        }
        .step-line { width: 32px; height: 2px; flex-shrink: 0; }
        .step-label { font-size: 10px; font-weight: 700; text-align: center; margin-top: 4px; }
    </style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon"><i class="fas fa-utensils" style="color:white;font-size:12px;"></i></div>
        <span style="font-size:17px;font-weight:800;color:white;font-style:italic;">Gastro<span style="color:#fb923c;">Nicaragua</span></span>
    </a>
    <a href="{{ route('home') }}" style="font-size:13px;color:rgba(255,255,255,0.6);text-decoration:none;font-weight:600;">
        <i class="fas fa-arrow-left" style="font-size:11px;margin-right:6px;"></i> Volver al inicio
    </a>
</div>

<div class="wrap">
    <div class="page-title">Mis Pedidos</div>
    <div class="page-sub">Historial y seguimiento de tus pedidos</div>

    @if($pedidos->count() > 0)
        @foreach($pedidos as $pedido)
        @php
            $estados = \App\Models\Pedido::ESTADOS;
            $info    = $estados[$pedido->estado];
            $pasos   = ['pendiente','confirmado','en_preparacion','listo','entregado'];
            $indexActual = array_search($pedido->estado, $pasos);
        @endphp
        <div class="pedido-card">
            <div class="pedido-header">
                <div>
                    <div style="font-size:15px;font-weight:800;color:var(--text);">
                        Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}
                        <span style="font-size:12px;color:var(--muted);font-weight:600;margin-left:6px;">
                            en <a href="{{ route('restaurantes.show', $pedido->restaurante) }}"
                                  style="color:var(--orange);text-decoration:none;">{{ $pedido->restaurante->nombre }}</a>
                        </span>
                    </div>
                    <div style="font-size:12px;color:var(--muted);margin-top:2px;">
                        {{ $pedido->created_at->format('d M Y, H:i') }} ·
                        {{ $pedido->tipo === 'mesa' ? '🍽 Mesa' : '🥡 Para llevar' }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span class="estado-badge" style="background:{{ $info['color'] }}22;color:{{ $info['color'] }};border:1px solid {{ $info['color'] }}44;">
                        <span style="width:6px;height:6px;border-radius:50%;background:{{ $info['color'] }};display:inline-block;"></span>
                        {{ $info['label'] }}
                    </span>
                    <span style="font-size:16px;font-weight:900;color:var(--orange);">C$ {{ number_format($pedido->total, 0) }}</span>
                </div>
            </div>

            {{-- Barra de progreso --}}
            @if($pedido->estado !== 'cancelado')
            <div class="progress-bar">
                @foreach($pasos as $i => $paso)
                    @php $activo = $i <= $indexActual; @endphp
                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                        <div style="display:flex;align-items:center;">
                            <div class="step-dot" style="background:{{ $activo ? $estados[$paso]['color'] : '#e7e5e4' }};color:{{ $activo ? 'white' : '#a8a29e' }};">
                                @if($activo && $i < $indexActual)
                                    <i class="fas fa-check" style="font-size:9px;"></i>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </div>
                            @if($i < count($pasos) - 1)
                                <div class="step-line" style="background:{{ $i < $indexActual ? $estados[$paso]['color'] : '#e7e5e4' }};"></div>
                            @endif
                        </div>
                        <div class="step-label" style="color:{{ $activo ? $estados[$paso]['color'] : '#a8a29e' }};">
                            {{ $estados[$paso]['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Items --}}
            <div class="pedido-items">
                @foreach($pedido->items as $item)
                <div class="item-row">
                    <span style="color:var(--text);font-weight:600;">
                        {{ $item->cantidad }}x {{ $item->plato->nombre ?? 'Plato no disponible' }}
                    </span>
                    <span style="color:var(--orange);font-weight:700;">C$ {{ number_format($item->subtotal, 0) }}</span>
                </div>
                @endforeach
                @if($pedido->notas)
                <div style="margin-top:8px;padding:8px 10px;background:#fff7ed;border-radius:8px;font-size:12px;color:#78716c;">
                    <i class="fas fa-sticky-note" style="color:#f59e0b;margin-right:4px;"></i>{{ $pedido->notas }}
                </div>
                @endif
            </div>

            <div class="pedido-footer">
                <span style="font-size:12px;color:var(--muted);">{{ $pedido->items->count() }} platillo{{ $pedido->items->count() !== 1 ? 's' : '' }}</span>
                <span style="font-size:15px;font-weight:900;color:var(--text);">Total: <span style="color:var(--orange);">C$ {{ number_format($pedido->total, 0) }}</span></span>
            </div>
        </div>
        @endforeach

        <div style="margin-top:20px;">{{ $pedidos->links() }}</div>

    @else
        <div class="empty-state">
            <i class="fas fa-bag-shopping"></i>
            <p style="font-size:15px;font-weight:700;margin-bottom:8px;">Aún no tienes pedidos</p>
            <p style="font-size:13px;margin-bottom:20px;">Explora los restaurantes y haz tu primer pedido</p>
            <a href="{{ route('restaurantes.index') }}" class="btn-orange">
                <i class="fas fa-utensils"></i> Ver restaurantes
            </a>
        </div>
    @endif
</div>

</body>
</html>