<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Pedidos — Gastro Nicaragua</title>
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700,800" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --orange:  #ea580c;
            --orange2: #c2410c;
            --border:  #e5e7eb;
            --bg:      #f9fafb;
            --text:    #111827;
            --muted:   #6b7280;
            --white:   #ffffff;
            --shadow:  0 1px 4px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
        }
        html, body { height: 100%; }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            position: sticky; top: 0; z-index: 100;
        }
        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon {
            width: 34px; height: 34px; background: var(--orange);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
        }
        .logo-text {
            font-family: 'Playfair Display', serif;
            font-style: italic; font-weight: 700; font-size: 18px; color: var(--text);
        }
        .logo-text span { color: var(--orange); }
        .btn-volver {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 600; color: var(--muted);
            text-decoration: none; transition: color 0.18s;
        }
        .btn-volver:hover { color: var(--orange); }

        /* ── WRAP ── */
        .wrap {
            flex: 1;
            width: 100%;
            padding: 40px 40px 60px;
        }

        /* ── PAGE HEADER ── */
        .page-header { margin-bottom: 28px; }
        .page-title { font-size: 26px; font-weight: 900; color: var(--text); margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: var(--muted); }

        /* ── PEDIDO CARD ── */
        .pedido-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 16px;
            box-shadow: var(--shadow);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .pedido-card:hover { box-shadow: 0 6px 28px rgba(0,0,0,0.09); transform: translateY(-1px); }

        /* header */
        .pedido-head {
            padding: 18px 22px 16px;
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 12px;
        }
        .pedido-num {
            font-size: 16px; font-weight: 900; color: var(--text);
            display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        }
        .rest-link {
            font-size: 13px; font-weight: 600; color: var(--orange);
            text-decoration: none;
        }
        .rest-link:hover { text-decoration: underline; }
        .pedido-meta {
            font-size: 12px; color: var(--muted); margin-top: 4px;
            display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
        }
        .meta-dot { color: var(--border); }

        .head-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; flex-wrap: wrap; justify-content: flex-end; }
        .estado-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 13px; border-radius: 999px;
            font-size: 11px; font-weight: 800; letter-spacing: 0.06em;
            border: 1.5px solid transparent;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
        .pedido-total-head { font-size: 18px; font-weight: 900; color: var(--orange); }

        /* progress */
        .progress-wrap {
            padding: 14px 22px 16px;
            background: #f9fafb;
            border-top: 1px solid var(--border);
        }
        .progress-track {
            display: flex; align-items: flex-start;
        }
        .progress-step { display: flex; flex-direction: column; align-items: center; flex: 1; }
        .step-top { display: flex; align-items: center; width: 100%; }
        .step-circle {
            width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 900; transition: all 0.2s;
            position: relative; z-index: 1;
        }
        .step-line { flex: 1; height: 3px; margin-top: 0; border-radius: 2px; }
        .step-label {
            font-size: 10px; font-weight: 700; text-align: center;
            margin-top: 6px; line-height: 1.3; max-width: 60px;
        }

        /* items */
        .pedido-items { padding: 4px 22px 4px; }
        .item-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px solid #f3f4f6;
        }
        .item-row:last-child { border-bottom: none; }
        .item-left { display: flex; align-items: center; gap: 10px; }
        .item-qty {
            width: 26px; height: 26px; border-radius: 8px;
            background: #fff7ed; color: var(--orange);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 900; flex-shrink: 0;
        }
        .item-name { font-size: 14px; font-weight: 600; color: var(--text); }
        .item-price { font-size: 14px; font-weight: 800; color: var(--text); }

        .notas-box {
            margin: 4px 0 12px;
            padding: 10px 14px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            font-size: 12px; color: #92400e;
            display: flex; align-items: flex-start; gap: 8px;
        }

        /* footer */
        .pedido-foot {
            padding: 14px 22px;
            background: #fafaf9;
            border-top: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .foot-left { font-size: 12px; color: var(--muted); font-weight: 500; }
        .foot-total { font-size: 15px; font-weight: 900; color: var(--text); }
        .foot-total span { color: var(--orange); }

        /* cancelado */
        .cancelado-bar {
            padding: 10px 22px;
            background: #fef2f2;
            border-top: 1px solid #fecaca;
            font-size: 12px; color: #b91c1c;
            font-weight: 700; display: flex; align-items: center; gap: 8px;
        }

        /* empty */
        .empty-state {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 18px; padding: 64px 24px; text-align: center;
            box-shadow: var(--shadow);
        }
        .empty-icon {
            width: 72px; height: 72px; border-radius: 50%;
            background: #fff7ed; margin: 0 auto 18px;
            display: flex; align-items: center; justify-content: center;
        }
        .empty-icon i { font-size: 30px; color: var(--orange); }
        .empty-title { font-size: 18px; font-weight: 800; margin-bottom: 6px; }
        .empty-sub { font-size: 14px; color: var(--muted); margin-bottom: 22px; }
        .btn-orange {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--orange); color: white; text-decoration: none;
            padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 800;
            transition: all 0.2s;
        }
        .btn-orange:hover { background: var(--orange2); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(234,88,12,0.3); }

        /* pagination */
        .pagination-wrap { margin-top: 24px; }

        /* footer site */
        footer {
            background: #1c1917; color: #a8a29e;
            border-top: 1px solid #292524;
            padding: 28px 32px;
            margin-top: auto;
        }
        .footer-inner {
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
        }

        @media (max-width: 600px) {
            .topbar { padding: 0 16px; }
            .wrap { padding: 24px 16px 48px; }
            .topbar { padding: 0 16px; }
            .pedido-head { flex-direction: column; }
            .head-right { justify-content: flex-start; }
            footer { padding: 20px 16px; }
        }
    </style>
</head>
<body>

<header class="topbar">
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon"><i class="fas fa-utensils" style="color:white;font-size:12px;"></i></div>
        <span class="logo-text">Gastro<span>Nicaragua</span></span>
    </a>
    <a href="{{ route('home') }}" class="btn-volver">
        <i class="fas fa-arrow-left" style="font-size:11px;"></i> Volver al inicio
    </a>
</header>

<div class="wrap">
    <div class="page-header">
        <div class="page-title">Mis Pedidos</div>
        <div class="page-sub">Historial y seguimiento de tus pedidos</div>
    </div>

    @if($pedidos->count() > 0)
        @foreach($pedidos as $pedido)
        @php
            $estados     = \App\Models\Pedido::ESTADOS;
            $info        = $estados[$pedido->estado];
            $pasos       = ['pendiente','confirmado','en_preparacion','listo','entregado'];
            $indexActual = array_search($pedido->estado, $pasos);
            $cancelado   = $pedido->estado === 'cancelado';
        @endphp
        <div class="pedido-card">

            {{-- HEAD --}}
            <div class="pedido-head">
                <div>
                    <div class="pedido-num">
                        Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}
                        <span style="font-weight:500;color:var(--muted);font-size:13px;">en</span>
                        <a href="{{ route('restaurantes.show', $pedido->restaurante) }}" class="rest-link">
                            {{ $pedido->restaurante->nombre }}
                        </a>
                    </div>
                    <div class="pedido-meta">
                        <i class="fas fa-clock" style="font-size:10px;color:var(--orange);"></i>
                        {{ $pedido->created_at->format('d M Y, H:i') }}
                        <span class="meta-dot">·</span>
                        @if($pedido->tipo === 'mesa')
                            <i class="fas fa-chair" style="font-size:10px;color:var(--orange);"></i> Mesa
                        @else
                            <i class="fas fa-bag-shopping" style="font-size:10px;color:var(--orange);"></i> Para llevar
                        @endif
                    </div>
                </div>
                <div class="head-right">
                    <span class="estado-badge"
                          style="background:{{ $info['color'] }}18;color:{{ $info['color'] }};border-color:{{ $info['color'] }}33;">
                        <span class="badge-dot" style="background:{{ $info['color'] }};"></span>
                        {{ $info['label'] }}
                    </span>
                    <span class="pedido-total-head">C$ {{ number_format($pedido->total, 0) }}</span>
                </div>
            </div>

            {{-- PROGRESS --}}
            @if(!$cancelado)
            <div class="progress-wrap">
                <div class="progress-track">
                    @foreach($pasos as $i => $paso)
                    @php
                        $pasado  = $i < $indexActual;
                        $activo  = $i === $indexActual;
                        $color   = ($pasado || $activo) ? $estados[$paso]['color'] : '#d1d5db';
                        $lineColor = $i < $indexActual ? $estados[$paso]['color'] : '#e5e7eb';
                    @endphp
                    <div class="progress-step">
                        <div class="step-top">
                            <div class="step-circle"
                                 style="background:{{ ($pasado||$activo) ? $color : '#f3f4f6' }};
                                        color:{{ ($pasado||$activo) ? 'white' : '#9ca3af' }};
                                        {{ $activo ? 'box-shadow:0 0 0 4px '.$color.'28;' : '' }}">
                                @if($pasado)
                                    <i class="fas fa-check" style="font-size:9px;"></i>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </div>
                            @if($i < count($pasos) - 1)
                                <div class="step-line" style="background:{{ $lineColor }};"></div>
                            @endif
                        </div>
                        <div class="step-label" style="color:{{ ($pasado||$activo) ? $color : '#9ca3af' }};">
                            {{ $estados[$paso]['label'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($cancelado)
            <div class="cancelado-bar">
                <i class="fas fa-ban"></i> Este pedido fue cancelado
            </div>
            @endif

            {{-- ITEMS --}}
            <div class="pedido-items">
                @foreach($pedido->items as $item)
                <div class="item-row">
                    <div class="item-left">
                        <div class="item-qty">{{ $item->cantidad }}</div>
                        <div class="item-name">{{ $item->plato->nombre ?? 'Plato no disponible' }}</div>
                    </div>
                    <div class="item-price">C$ {{ number_format($item->subtotal, 0) }}</div>
                </div>
                @endforeach

                @if($pedido->notas)
                <div class="notas-box">
                    <i class="fas fa-sticky-note" style="color:#f59e0b;margin-top:1px;flex-shrink:0;"></i>
                    <span>{{ $pedido->notas }}</span>
                </div>
                @endif
            </div>

            {{-- FOOT --}}
            <div class="pedido-foot">
                <span class="foot-left">
                    <i class="fas fa-utensils" style="font-size:10px;margin-right:4px;color:var(--orange);"></i>
                    {{ $pedido->items->count() }} platillo{{ $pedido->items->count() !== 1 ? 's' : '' }}
                </span>
                <span class="foot-total">Total: <span>C$ {{ number_format($pedido->total, 0) }}</span></span>
            </div>

        </div>
        @endforeach

        <div class="pagination-wrap">{{ $pedidos->links() }}</div>

    @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-bag-shopping"></i></div>
            <div class="empty-title">Aún no tienes pedidos</div>
            <div class="empty-sub">Explora los restaurantes y haz tu primer pedido</div>
            <a href="{{ route('restaurantes.index') }}" class="btn-orange">
                <i class="fas fa-utensils" style="font-size:12px;"></i> Ver restaurantes
            </a>
        </div>
    @endif
</div>

<footer>
    <div class="footer-inner">
        <span style="font-size:13px;font-style:italic;font-weight:700;color:#fff;">
            Gastro<span style="color:#ea580c;">Nicaragua</span>
        </span>
        <span style="font-size:12px;color:#57534e;">&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</span>
    </div>
</footer>

</body>
</html>
