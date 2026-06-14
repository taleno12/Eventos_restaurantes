{{-- resources/views/empleos/show.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $empleo->titulo }} | Gastro Nicaragua</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --orange:  #ea580c;
            --orange2: #c2410c;
            --orange-light: #fff7ed;
            --border:  #e5e7eb;
            --bg:      #f5f5f5;
            --text:    #222222;
            --muted:   #717171;
            --white:   #ffffff;
            --green:   #16a34a;
            --red:     #ef4444;
            --radius:  12px;
            --shadow:  0 2px 8px rgba(0,0,0,0.08);
        }
        html, body { height: 100%; }
        body {
            font-family: 'Instrument Sans', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex; flex-direction: column;
            font-size: 14px;
        }
        a { text-decoration: none; color: inherit; }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            height: 56px;
            display: flex; align-items: center;
            padding: 0 24px;
            position: sticky; top: 0; z-index: 100;
            gap: 16px;
        }
        .btn-back {
            width: 36px; height: 36px;
            border: 1.5px solid var(--border); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--text); font-size: 13px;
            transition: border-color 0.15s;
            flex-shrink: 0;
        }
        .btn-back:hover { border-color: var(--text); }
        .logo {
            display: flex; align-items: center; gap: 8px;
            font-weight: 800; font-size: 17px; color: var(--text);
        }
        .logo-dot { color: var(--orange); }
        .topbar-actions { margin-left: auto; display: flex; gap: 10px; }
        .icon-btn {
            width: 36px; height: 36px; border-radius: 50%;
            background: transparent; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--text); font-size: 16px;
            transition: background 0.15s;
        }
        .icon-btn:hover { background: var(--bg); }

        /* ── RESTAURANT HEADER ── */
        .rest-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 20px 24px 0;
        }
        .rest-header-inner { max-width: 1100px; margin: 0 auto; }
        .rest-top {
            display: flex; align-items: center; gap: 16px; margin-bottom: 14px;
        }
        .rest-logo {
            width: 72px; height: 72px; border-radius: 14px;
            border: 1px solid var(--border);
            background: #f3f4f6;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
        }
        .rest-logo-inner {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--orange) 0%, var(--orange2) 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .rest-info { flex: 1; }
        .rest-name { font-size: 22px; font-weight: 800; margin-bottom: 4px; line-height: 1.2; }
        .rest-meta {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        }
        .rest-rating {
            display: flex; align-items: center; gap: 4px;
            font-size: 13px; font-weight: 600;
        }
        .rest-rating i { color: #facc15; font-size: 12px; }
        .rest-badge {
            background: #fff0e6; color: var(--orange);
            border: 1.5px solid #fcd9b0;
            padding: 2px 10px; border-radius: 999px;
            font-size: 11px; font-weight: 700;
        }
        .rest-location {
            display: flex; align-items: center; gap: 5px;
            font-size: 12px; color: var(--muted);
        }
        .rest-location i { font-size: 11px; color: var(--orange); }

        /* ── TABS ── */
        .tabs-wrap {
            display: flex; gap: 4px; margin-top: 8px;
            overflow-x: auto; -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .tabs-wrap::-webkit-scrollbar { display: none; }
        .tab {
            padding: 10px 18px; border-radius: 999px 999px 0 0;
            font-size: 13px; font-weight: 600; cursor: pointer;
            white-space: nowrap; border: none; background: transparent;
            color: var(--muted); border-bottom: 3px solid transparent;
            transition: all 0.15s; font-family: inherit;
        }
        .tab.active {
            color: var(--text);
            border-bottom-color: var(--text);
        }
        .tab:hover:not(.active) { color: var(--text); background: var(--bg); }

        /* ── MAIN LAYOUT ── */
        .main-wrap {
            flex: 1;
            max-width: 1100px; margin: 0 auto; width: 100%;
            padding: 20px 24px 60px;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px; align-items: start;
        }
        @media (max-width: 860px) {
            .main-wrap { grid-template-columns: 1fr; }
            .sidebar { order: -1; }
        }

        /* ── CARDS ── */
        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 12px;
        }
        .card:last-child { margin-bottom: 0; }

        /* Section title inside card */
        .section-label {
            font-size: 11px; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 16px; font-weight: 700; margin-bottom: 12px;
            display: flex; align-items: center; gap: 8px;
        }
        .section-title i { color: var(--orange); font-size: 14px; }

        /* Product-style info row (like menu items) */
        .info-item {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 14px 0; border-bottom: 1px solid #f5f5f5;
        }
        .info-item:last-child { border-bottom: none; padding-bottom: 0; }
        .info-item-left { flex: 1; }
        .info-item-name {
            font-size: 14px; font-weight: 600; margin-bottom: 2px;
        }
        .info-item-desc { font-size: 13px; color: var(--muted); }
        .info-item-value {
            font-size: 15px; font-weight: 700; color: var(--text);
            display: flex; align-items: center; gap: 6px;
            flex-shrink: 0; margin-left: 12px;
        }
        .info-item-value.green { color: var(--green); font-size: 20px; }
        .info-item-value.red   { color: var(--red); font-size: 13px; }
        .info-item-value i { color: var(--orange); font-size: 12px; }

        /* Body text */
        .body-text {
            font-size: 14px; color: #444; line-height: 1.75;
            white-space: pre-line;
        }
        .empty-text {
            font-size: 13px; color: #9ca3af; font-style: italic;
        }

        /* ── SIDEBAR ── */
        .sidebar { position: sticky; top: 72px; }

        /* Pedido card = styled like PedidosYa order panel */
        .pedido-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .pedido-header {
            background: var(--text);
            padding: 14px 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .pedido-header-icon {
            width: 32px; height: 32px;
            background: var(--orange); border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .pedido-header-title {
            font-size: 14px; font-weight: 700; color: var(--white);
        }
        .pedido-header-sub {
            font-size: 11px; color: #a8a29e; margin-top: 1px;
        }
        .pedido-body { padding: 0 20px 20px; }

        /* Salary big display */
        .salary-display {
            text-align: center; padding: 20px 0 16px;
            border-bottom: 1px solid #f5f5f5; margin-bottom: 0;
        }
        .salary-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }
        .salary-amount { font-size: 32px; font-weight: 800; color: var(--green); line-height: 1; }
        .salary-period { font-size: 12px; color: var(--muted); margin-top: 3px; }

        /* Meta rows */
        .meta-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 0; border-bottom: 1px solid #f5f5f5; font-size: 13px;
        }
        .meta-row:last-of-type { border-bottom: none; }
        .meta-key { color: var(--muted); font-weight: 500; display: flex; align-items: center; gap: 6px; }
        .meta-key i { color: var(--orange); width: 14px; text-align: center; }
        .meta-val { font-weight: 700; color: var(--text); }
        .meta-val.red { color: var(--red); }

        /* Redes */
        .redes-section { padding: 14px 0 0; }
        .redes-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px; }
        .redes-wrap { display: flex; gap: 8px; flex-wrap: wrap; }
        .red-btn {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--bg); border: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: var(--muted); transition: all 0.18s;
        }
        .red-btn:hover.wa  { background: #dcfce7; color: #16a34a; border-color: #86efac; }
        .red-btn:hover.ig  { background: #fce7f3; color: #db2777; border-color: #f9a8d4; }
        .red-btn:hover.tt  { background: #f3f4f6; color: #111827; border-color: #d1d5db; }
        .red-btn:hover.fb  { background: #eff6ff; color: #2563eb; border-color: #93c5fd; }

        /* Botón postular */
        .btn-postular {
            width: 100%; padding: 14px; border-radius: 10px; border: none;
            background: var(--orange); color: white; font-size: 15px; font-weight: 800;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            gap: 8px; transition: all 0.18s; font-family: inherit; margin-top: 16px;
        }
        .btn-postular:hover {
            background: var(--orange2); transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(234,88,12,0.28);
        }

        /* alerts */
        .alerts-wrap { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
        .alert {
            padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 10px; margin-top: 16px;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* chips */
        .chip {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700;
            letter-spacing: 0.03em;
        }
        .chip-orange { background: #fff7ed; color: var(--orange); border: 1.5px solid #fed7aa; }
        .chip-gray   { background: var(--bg); color: var(--muted); border: 1.5px solid var(--border); }

        /* ── FOOTER ── */
        footer {
            background: #1c1917; color: #a8a29e;
            border-top: 1px solid #292524;
            padding: 20px 24px; margin-top: auto;
        }
        .footer-inner {
            max-width: 1100px; margin: 0 auto;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;
        }
        .footer-logo { font-weight: 800; font-size: 15px; color: var(--white); }
        .footer-logo span { color: var(--orange); }

        @media (max-width: 600px) {
            .topbar { padding: 0 16px; }
            .rest-header { padding: 16px 16px 0; }
            .main-wrap { padding: 16px 16px 48px; }
            .alerts-wrap { padding: 0 16px; }
            footer { padding: 16px; }
        }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<header class="topbar">
    <a href="{{ route('empleos.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i>
    </a>
    <a href="{{ route('home') }}" class="logo">
        Gastro<span class="logo-dot">Nicaragua</span>
    </a>
    <div class="topbar-actions">
        <button class="icon-btn" title="Compartir"><i class="fas fa-share-nodes"></i></button>
        <button class="icon-btn" title="Guardar"><i class="far fa-heart"></i></button>
    </div>
</header>

{{-- RESTAURANT HEADER (card-like, igual que PedidosYa) --}}
<div class="rest-header">
    <div class="rest-header-inner">
        <div class="rest-top">
            <div class="rest-logo">
                <div class="rest-logo-inner">
                    <i class="fas fa-utensils" style="color:white;font-size:22px;"></i>
                </div>
            </div>
            <div class="rest-info">
                <div class="rest-name">{{ $empleo->titulo }}</div>
                <div class="rest-meta">
                    <div class="rest-rating">
                        <i class="fas fa-star"></i>
                        <span>{{ $empleo->restaurante->nombre }}</span>
                    </div>
                    @if($empleo->tipo_contrato)
                    <span class="rest-badge">{{ $empleo->tipo_contrato }}</span>
                    @endif
                    <div class="rest-location">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $empleo->departamento->nombre }}@if($empleo->municipio), {{ $empleo->municipio->nombre }}@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs estilo PedidosYa --}}
        <div class="tabs-wrap">
            <button class="tab active">Descripción</button>
            <button class="tab">Requisitos</button>
            <button class="tab">Empresa</button>
        </div>
    </div>
</div>

{{-- ALERTS --}}
<div class="alerts-wrap">
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif
</div>

{{-- MAIN --}}
<div class="main-wrap">

    {{-- COLUMNA IZQUIERDA --}}
    <div>

        {{-- Descripción --}}
        <div class="card">
            <div class="section-title">
                <i class="fas fa-align-left"></i> Descripción del puesto
            </div>
            <p class="body-text">{{ $empleo->descripcion }}</p>
        </div>

        {{-- Requisitos --}}
        <div class="card">
            <div class="section-title">
                <i class="fas fa-clipboard-list"></i> Requisitos
            </div>
            @if($empleo->requisitos)
                <p class="body-text">{{ $empleo->requisitos }}</p>
            @else
                <p class="empty-text">Sin requisitos adicionales especificados.</p>
            @endif
        </div>

        {{-- Info extra (estilo items de menú) --}}
        <div class="card">
            <div class="section-title">
                <i class="fas fa-receipt"></i> Detalles de la oferta
            </div>

            <div class="info-item">
                <div class="info-item-left">
                    <div class="info-item-name">Remuneración mensual</div>
                    <div class="info-item-desc">Salario bruto estimado</div>
                </div>
                <div class="info-item-value green">
                    @if($empleo->salario)
                        C$ {{ number_format($empleo->salario, 0) }}
                    @else
                        <span style="font-size:14px;color:var(--muted);">A convenir</span>
                    @endif
                </div>
            </div>

            @if($empleo->fecha_limite)
            <div class="info-item">
                <div class="info-item-left">
                    <div class="info-item-name">Fecha límite</div>
                    <div class="info-item-desc">Última fecha para postularse</div>
                </div>
                <div class="info-item-value red">
                    <i class="fas fa-calendar-times"></i>
                    {{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d \d\e M, Y') }}
                </div>
            </div>
            @endif

            <div class="info-item">
                <div class="info-item-left">
                    <div class="info-item-name">Publicado</div>
                    <div class="info-item-desc">Tiempo desde la publicación</div>
                </div>
                <div class="info-item-value">
                    <i class="fas fa-clock"></i>
                    {{ $empleo->created_at->diffForHumans() }}
                </div>
            </div>

        </div>
    </div>

    {{-- SIDEBAR (estilo panel "Mi pedido" de PedidosYa) --}}
    <aside class="sidebar">
        <div class="pedido-card">

            {{-- Header oscuro --}}
            <div class="pedido-header">
                <div class="pedido-header-icon">
                    <i class="fas fa-briefcase" style="color:white;font-size:13px;"></i>
                </div>
                <div>
                    <div class="pedido-header-title">Postularse al puesto</div>
                    <div class="pedido-header-sub">{{ $empleo->restaurante->nombre }}</div>
                </div>
            </div>

            <div class="pedido-body">

                {{-- Salario destacado --}}
                <div class="salary-display">
                    <div class="salary-label">Remuneración mensual</div>
                    <div class="salary-amount">
                        @if($empleo->salario)
                            C$ {{ number_format($empleo->salario, 0) }}
                        @else
                            A convenir
                        @endif
                    </div>
                    <div class="salary-period">por mes</div>
                </div>

                {{-- Meta rows --}}
                <div class="meta-row">
                    <span class="meta-key"><i class="fas fa-map-marker-alt"></i> Ubicación</span>
                    <span class="meta-val">
                        {{ $empleo->departamento->nombre }}@if($empleo->municipio), {{ $empleo->municipio->nombre }}@endif
                    </span>
                </div>
                @if($empleo->tipo_contrato)
                <div class="meta-row">
                    <span class="meta-key"><i class="fas fa-clock"></i> Contrato</span>
                    <span class="meta-val">{{ $empleo->tipo_contrato }}</span>
                </div>
                @endif
                @if($empleo->fecha_limite)
                <div class="meta-row">
                    <span class="meta-key"><i class="fas fa-calendar-times"></i> Límite</span>
                    <span class="meta-val red">{{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d M, Y') }}</span>
                </div>
                @endif
                <div class="meta-row">
                    <span class="meta-key"><i class="fas fa-clock"></i> Publicado</span>
                    <span class="meta-val">{{ $empleo->created_at->diffForHumans() }}</span>
                </div>

                {{-- Redes --}}
                @php
                    $tieneRedes = !empty($empleo->restaurante->whatsapp) ||
                                  !empty($empleo->restaurante->instagram) ||
                                  !empty($empleo->restaurante->tiktok) ||
                                  !empty($empleo->restaurante->facebook);
                @endphp
                @if($tieneRedes)
                <div class="redes-section">
                    <div class="redes-label">Conoce el establecimiento</div>
                    <div class="redes-wrap">
                        @if(!empty($empleo->restaurante->whatsapp))
                            @php $p = preg_replace('/[^0-9]/', '', $empleo->restaurante->whatsapp); @endphp
                            <a href="https://wa.me/{{ $p }}" target="_blank" class="red-btn wa" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                        @if(!empty($empleo->restaurante->instagram))
                            <a href="{{ $empleo->restaurante->instagram }}" target="_blank" class="red-btn ig" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if(!empty($empleo->restaurante->tiktok))
                            <a href="{{ $empleo->restaurante->tiktok }}" target="_blank" class="red-btn tt" title="TikTok">
                                <i class="fab fa-tiktok" style="font-size:13px;"></i>
                            </a>
                        @endif
                        @if(!empty($empleo->restaurante->facebook))
                            <a href="{{ $empleo->restaurante->facebook }}" target="_blank" class="red-btn fb" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Botón postular --}}
                @include('components.apply-modal', ['empleo' => $empleo])

            </div>
        </div>
    </aside>

</div>

{{-- FOOTER --}}
<footer>
    <div class="footer-inner">
        <span class="footer-logo">Gastro<span>Nicaragua</span></span>
        <span style="font-size:12px;color:#57534e;">&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</span>
    </div>
</footer>

</body>
</html>
