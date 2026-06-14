<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $evento->titulo }} | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900|instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange:    #ea580c;
            --orange-lt: #fff7ed;
            --border:    #e5e7eb;
            --text:      #1f2937;
            --muted:     #6b7280;
            --bg:        #f9fafb;
            --white:     #ffffff;
            --radius:    12px;
            --shadow:    0 1px 4px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04);
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }

        /* ══ TOPBAR ══ */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
            padding: 0 1.5rem;
        }
        .topbar-inner {
            max-width: 1280px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 56px; gap: 16px;
        }
        .topbar-logo {
            font-family: 'Playfair Display', serif;
            font-style: italic; font-weight: 700;
            font-size: 18px; color: var(--text);
            display: flex; align-items: center; gap: 8px;
        }
        .topbar-logo .dot { color: var(--orange); }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }
        .btn-sm {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; padding: 7px 14px;
            border-radius: 999px; border: 1.5px solid var(--border);
            background: var(--white); color: var(--muted);
            cursor: pointer; transition: all 0.18s;
        }
        .btn-sm:hover { border-color: var(--orange); color: var(--orange); background: var(--orange-lt); }
        .btn-sm.primary { background: var(--orange); color: white; border-color: var(--orange); }
        .btn-sm.primary:hover { background: #c2410c; border-color: #c2410c; }

        /* ══ HERO BANNER ══ */
        .hero-banner {
            position: relative; width: 100%;
            height: 260px; overflow: hidden; background: #1f2937;
        }
        @media (min-width: 768px) { .hero-banner { height: 320px; } }
        .hero-banner img {
            width: 100%; height: 100%; object-fit: cover;
            opacity: 0.75; display: block;
            cursor: zoom-in; transition: opacity 0.2s;
        }
        .hero-banner img:hover { opacity: 0.85; }
        .hero-banner-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.52) 100%);
            pointer-events: none;
        }
        .hero-zoom-hint {
            position: absolute; bottom: 16px; right: 16px;
            background: rgba(0,0,0,0.45); backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.15);
            color: white; font-size: 12px; font-weight: 700;
            padding: 7px 14px; border-radius: 999px;
            display: flex; align-items: center; gap: 7px;
            pointer-events: none; letter-spacing: 0.02em;
        }

        /* ══ EVENT HEADER ══ */
        .evt-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
        }
        .evt-header-inner {
            max-width: 1280px; margin: 0 auto; padding: 0 1.5rem;
        }
        .evt-header-top {
            display: flex; align-items: flex-end; gap: 20px;
            transform: translateY(-40px); margin-bottom: -40px;
        }
        .evt-thumb-wrap {
            width: 100px; height: 100px; border-radius: 18px;
            border: 4px solid var(--white); overflow: hidden;
            background: var(--orange-lt); flex-shrink: 0;
            box-shadow: var(--shadow);
            display: flex; align-items: center; justify-content: center;
        }
        .evt-thumb-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .evt-thumb-placeholder { font-size: 2.5rem; color: var(--orange); opacity: 0.5; }

        .evt-info { padding-bottom: 8px; min-width: 0; flex: 1; }
        .evt-info h1 {
            font-size: clamp(1.3rem, 3vw, 1.9rem);
            font-weight: 800; color: var(--text); line-height: 1.2;
            margin-bottom: 6px;
        }
        .evt-meta {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
            font-size: 13px; color: var(--muted);
        }
        .evt-meta-item { display: flex; align-items: center; gap: 5px; font-weight: 600; }
        .evt-meta-item i { color: var(--orange); font-size: 11px; }

        .evt-header-bottom {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 0 16px; gap: 12px; flex-wrap: wrap;
        }
        .evt-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 700; padding: 5px 12px;
            border-radius: 999px; border: 1.5px solid;
        }
        .badge-orange { background: var(--orange-lt); color: var(--orange); border-color: #fed7aa; }
        .badge-gray   { background: #f9fafb; color: #6b7280; border-color: #e5e7eb; }
        .badge-green  { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .badge-red    { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

        /* ══ STATS BAR ══ */
        .stats-bar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
        }
        .stats-bar-inner {
            max-width: 1280px; margin: 0 auto; padding: 0 1.5rem;
            display: flex; align-items: stretch; gap: 0;
            overflow-x: auto; scrollbar-width: none;
        }
        .stats-bar-inner::-webkit-scrollbar { display: none; }
        .stat-item {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 14px 28px; min-width: 120px; border-right: 1px solid var(--border);
            flex-shrink: 0;
        }
        .stat-item:last-child { border-right: none; }
        .stat-val { font-size: 18px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-val .accent { color: var(--orange); }
        .stat-lbl { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: var(--muted); margin-top: 3px; }

        /* ══ LAYOUT ══ */
        .page-body {
            max-width: 1280px; margin: 0 auto; padding: 28px 1.5rem 80px;
            display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;
        }
        @media (max-width: 1024px) {
            .page-body { grid-template-columns: 1fr; }
            .sidebar { order: -1; }
        }

        /* ══ SECTION CARDS ══ */
        .section-card {
            background: var(--white); border-radius: var(--radius);
            border: 1px solid var(--border); box-shadow: var(--shadow);
            margin-bottom: 16px; overflow: hidden;
        }
        .section-head {
            padding: 18px 22px 14px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .section-title {
            font-size: 15px; font-weight: 800; color: var(--text);
            display: flex; align-items: center; gap: 8px;
        }
        .section-title i { color: var(--orange); font-size: 13px; }
        .section-body { padding: 20px 22px; }

        .desc-text { font-size: 14.5px; color: #4b5563; line-height: 1.8; }

        /* ══ GALERÍA ══ */
        .gallery-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 110px; gap: 8px;
        }
        @media (max-width: 600px) { .gallery-grid { grid-template-columns: repeat(3, 1fr); grid-auto-rows: 85px; } }
        @media (max-width: 400px) { .gallery-grid { grid-template-columns: repeat(2, 1fr); } }
        .gallery-grid .g-first { grid-column: span 2; grid-row: span 2; }
        .g-item { border-radius: 10px; overflow: hidden; cursor: zoom-in; position: relative; background: #f3f4f6; }
        .g-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease; }
        .g-item:hover img { transform: scale(1.07); }
        .g-item::after {
            content: '\f00e'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white; background: rgba(0,0,0,0); opacity: 0; transition: all 0.25s;
        }
        .g-item:hover::after { background: rgba(0,0,0,0.35); opacity: 1; }

        /* ══ SIDEBAR ══ */
        .sidebar { display: flex; flex-direction: column; gap: 14px; }
        .sidebar-card {
            background: var(--white); border-radius: var(--radius);
            border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;
        }
        .sidebar-card-head {
            padding: 14px 18px; border-bottom: 1px solid var(--border);
            font-size: 13px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.1em; color: var(--muted);
            display: flex; align-items: center; gap: 8px;
        }
        .sidebar-card-head i { color: var(--orange); }
        .sidebar-card-body { padding: 16px 18px; }

        /* info rows */
        .info-row {
            display: flex; align-items: flex-start; justify-content: space-between;
            padding: 10px 14px; border-radius: 10px; background: var(--bg);
            border: 1px solid var(--border); margin-bottom: 8px;
        }
        .info-row:last-child { margin-bottom: 0; }
        .info-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); display: flex; align-items: center; gap: 6px; }
        .info-value { font-size: 14px; font-weight: 800; color: var(--text); text-align: right; max-width: 180px; }

        /* countdown badge */
        .countdown-badge {
            font-size: 12px; font-weight: 800;
            padding: 7px 14px; border-radius: 999px; letter-spacing: 0.04em;
        }
        .countdown-active  { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
        .countdown-ended   { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

        /* redes */
        .social-grid { display: flex; flex-wrap: wrap; gap: 10px; }
        .social-btn {
            width: 46px; height: 46px; border-radius: 12px; font-size: 17px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: all 0.22s cubic-bezier(0.34,1.56,0.64,1);
            border: 1.5px solid transparent;
        }
        .social-btn:hover { transform: translateY(-3px) scale(1.07); }
        .social-wa { background: #dcfce7; color: #16a34a; border-color: #bbf7d0; }
        .social-wa:hover { background: #22c55e; color: white; box-shadow: 0 6px 18px rgba(34,197,94,0.35); }
        .social-ig { background: #fce7f3; color: #db2777; border-color: #fbcfe8; }
        .social-ig:hover { background: #ec4899; color: white; box-shadow: 0 6px 18px rgba(236,72,153,0.35); }
        .social-tt { background: #f1f5f9; color: #0f172a; border-color: #e2e8f0; }
        .social-tt:hover { background: #0f172a; color: white; box-shadow: 0 6px 18px rgba(15,23,42,0.25); }
        .social-fb { background: #dbeafe; color: #2563eb; border-color: #bfdbfe; }
        .social-fb:hover { background: #2563eb; color: white; box-shadow: 0 6px 18px rgba(37,99,235,0.35); }

        /* CTA */
        .btn-cta-primary {
            display: flex; align-items: center; justify-content: center; gap: 9px;
            width: 100%; background: var(--orange); color: white;
            font-size: 14px; font-weight: 700; padding: 14px 20px;
            border-radius: 12px; border: none; cursor: pointer; transition: all 0.18s;
            text-decoration: none; letter-spacing: 0.02em;
        }
        .btn-cta-primary:hover { background: #c2410c; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(234,88,12,0.3); }

        .btn-cta-secondary {
            display: flex; align-items: center; justify-content: center; gap: 9px;
            width: 100%; background: #1f2937; color: white;
            font-size: 14px; font-weight: 700; padding: 14px 20px;
            border-radius: 12px; border: none; cursor: pointer; transition: all 0.18s;
            text-decoration: none; letter-spacing: 0.02em;
        }
        .btn-cta-secondary:hover { background: #111827; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }

        /* ══ LIGHTBOX ══ */
        #lightbox {
            position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.95);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.25s ease; padding: 20px;
        }
        #lightbox.active { opacity: 1; pointer-events: all; }
        #lightbox-img {
            max-width: min(90vw, 960px); max-height: 85vh; border-radius: 16px; object-fit: contain;
            transform: scale(0.93); transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        #lightbox.active #lightbox-img { transform: scale(1); }
        .lb-btn {
            position: fixed; top: 50%; transform: translateY(-50%);
            width: 44px; height: 44px; border-radius: 50%;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(8px); color: white; font-size: 15px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background 0.18s; z-index: 10001;
        }
        .lb-btn:hover { background: rgba(234,88,12,0.65); }
        #lb-prev { left: 14px; } #lb-next { right: 14px; }
        #lb-close {
            position: fixed; top: 14px; right: 14px; width: 38px; height: 38px; border-radius: 50%;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(8px); color: white; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background 0.18s; z-index: 10001;
        }
        #lb-close:hover { background: rgba(239,68,68,0.65); }
        #lb-counter {
            position: fixed; bottom: 18px; left: 50%; transform: translateX(-50%);
            color: rgba(255,255,255,0.45); font-size: 12px; font-weight: 600;
            letter-spacing: 0.08em; z-index: 10001; display: flex; gap: 8px; align-items: center;
        }
        .lb-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.25); cursor: pointer; transition: all 0.18s; }
        .lb-dot.on { background: var(--orange); transform: scale(1.35); }

        /* ══ FOOTER ══ */
        .footer-main { background: #1c1917; color: #a8a29e; border-top: 1px solid #292524; font-family: 'Instrument Sans', sans-serif; }
        .footer-main a { text-decoration: none; }
        .footer-main h4 { font-family: 'Instrument Sans', sans-serif; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #fff; margin: 0 0 16px; }
        .footer-main p  { margin: 0; }
        .footer-main ul { list-style: none; padding: 0; margin: 0; }
        .footer-main li { margin-bottom: 10px; }
        .footer-main li a { color: #a8a29e; font-size: 14px; transition: color 0.2s; }
        .footer-main li a:hover { color: #f97316; }
        .footer-brand-name { font-family: 'Playfair Display', serif; font-style: italic; font-weight: 700; font-size: 20px; color: #fff; }
        .footer-brand-name span { color: #ea580c; }
        .footer-social-btn {
            width: 32px; height: 32px; border-radius: 50%;
            background: #292524; display: flex; align-items: center; justify-content: center;
            color: #a8a29e; font-size: 12px; transition: all 0.2s; text-decoration: none;
        }
        .footer-social-btn:hover { background: #ea580c; color: #fff; }
        .footer-destino {
            color: #a8a29e; font-size: 14px; font-weight: 300;
            cursor: pointer; transition: color 0.2s; display: block;
        }
        .footer-destino:hover { color: #fff; }
        .footer-bottom-link { font-size: 12px; color: #57534e; transition: color 0.2s; }
        .footer-bottom-link:hover { color: #a8a29e; }

        /* ══ MOBILE ══ */
        @media (max-width: 640px) {
            .stats-bar-inner { padding: 0 1rem; }
            .evt-header-inner { padding: 0 1rem; }
            .page-body { padding: 16px 1rem 60px; gap: 16px; }
            .topbar { padding: 0 1rem; }
            .hero-banner { height: 200px; }
            .evt-thumb-wrap { width: 80px; height: 80px; }
            .evt-header-top { transform: translateY(-30px); margin-bottom: -30px; }
        }
    </style>
</head>
<body>

@php $restaurante = $evento->restaurante; @endphp

{{-- ══ TOPBAR ══ --}}
<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('home') }}" class="topbar-logo">
            Gastro<span class="dot">Nicaragua</span>
        </a>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" class="btn-sm">
                <i class="fas fa-arrow-left" style="font-size:10px;"></i> Volver
            </a>
        </div>
    </div>
</header>

{{-- ══ HERO BANNER ══ --}}
<div class="hero-banner" onclick="openHeroLightbox('{{ asset('storage/' . $evento->imagen) }}')">
    <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->titulo }}">
    <div class="hero-banner-overlay"></div>
    <div class="hero-zoom-hint">
        <i class="fas fa-expand-alt" style="font-size:10px;"></i> Ver imagen completa
    </div>
</div>

{{-- ══ EVENT HEADER ══ --}}
<div class="evt-header">
    <div class="evt-header-inner">

        <div class="evt-header-top">
            <div class="evt-thumb-wrap">
                <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->titulo }}">
            </div>
            <div class="evt-info">
                <h1>{{ $evento->titulo }}</h1>
                <div class="evt-meta">
                    <span class="evt-meta-item">
                        <i class="fas fa-store"></i>
                        <strong>{{ $restaurante->nombre }}</strong>
                    </span>
                    <span style="color:var(--border);">·</span>
                    <span class="evt-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $evento->departamento->nombre }}
                        @if($evento->municipio)
                            · {{ $evento->municipio->nombre }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="evt-header-bottom">
            @php
                $fechaEvento = \Carbon\Carbon::parse($evento->fecha_evento);
                $isPast      = $fechaEvento->isPast();
                $isToday     = $fechaEvento->isToday();
            @endphp
            <div class="evt-badges">
                @if($isToday)
                    <span class="badge badge-orange">
                        <i class="fas fa-circle" style="font-size:7px;"></i> Hoy
                    </span>
                @elseif($isPast)
                    <span class="badge badge-gray">
                        <i class="fas fa-check" style="font-size:9px;"></i> Finalizado
                    </span>
                @else
                    <span class="badge badge-green">
                        <i class="fas fa-calendar-check" style="font-size:9px;"></i> Próximo
                    </span>
                @endif

                @if($evento->precio > 0)
                    <span class="badge badge-orange">
                        <i class="fas fa-ticket-alt" style="font-size:9px;"></i>
                        C$ {{ number_format($evento->precio, 0) }}
                    </span>
                @else
                    <span class="badge badge-green">
                        <i class="fas fa-ticket-alt" style="font-size:9px;"></i> Entrada libre
                    </span>
                @endif

                @if($restaurante->especialidad)
                    <span class="badge badge-gray">
                        <i class="fas fa-tag" style="font-size:9px;"></i> {{ $restaurante->especialidad }}
                    </span>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ══ STATS BAR ══ --}}
<div class="stats-bar">
    <div class="stats-bar-inner">
        <div class="stat-item">
            <div class="stat-val" style="font-size:14px;">
                {{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('d M Y') }}
            </div>
            <div class="stat-lbl">Fecha</div>
        </div>
        <div class="stat-item">
            <div class="stat-val" style="font-size:15px;">
                @if($evento->precio > 0)
                    <span class="accent">C$</span> {{ number_format($evento->precio, 0) }}
                @else
                    <span style="color:#16a34a;font-size:13px;">Gratis</span>
                @endif
            </div>
            <div class="stat-lbl">Entrada</div>
        </div>
        <div class="stat-item">
            <div class="stat-val" style="font-size:13px;">{{ $restaurante->nombre }}</div>
            <div class="stat-lbl">Sede</div>
        </div>
        @if($evento->municipio)
            <div class="stat-item">
                <div class="stat-val" style="font-size:14px;">{{ $evento->municipio->nombre }}</div>
                <div class="stat-lbl">Municipio</div>
            </div>
        @endif
        @if(!$isPast)
            <div class="stat-item">
                <span class="countdown countdown-badge countdown-active" data-expire="{{ $evento->fecha_evento }}">
                    Calculando…
                </span>
                <div class="stat-lbl" style="margin-top:6px;">Vigencia</div>
            </div>
        @endif
    </div>
</div>

{{-- ══ CUERPO PRINCIPAL ══ --}}
@php
    $galeriaImages = [];
    if($evento->imagenes) {
        foreach($evento->imagenes as $foto) {
            $galeriaImages[] = asset('storage/' . $foto->ruta);
        }
    }
@endphp

<div class="page-body">

    {{-- ── COLUMNA PRINCIPAL ── --}}
    <main>

        {{-- Descripción --}}
        <div class="section-card">
            <div class="section-head">
                <div class="section-title">
                    <i class="fas fa-align-left"></i> Sobre el evento
                </div>
            </div>
            <div class="section-body">
                <p class="desc-text" style="white-space:pre-line;">
                    {{ $evento->descripcion ?? 'Sin descripción detallada disponible por el momento.' }}
                </p>
            </div>
        </div>

        {{-- Galería --}}
        @if($evento->imagenes && $evento->imagenes->count() > 0)
            <div class="section-card">
                <div class="section-head">
                    <div class="section-title">
                        <i class="fas fa-images"></i> Galería de fotos
                    </div>
                    <span style="font-size:11px;color:var(--muted);">
                        {{ $evento->imagenes->count() }} {{ $evento->imagenes->count() == 1 ? 'foto' : 'fotos' }} · clic para ampliar
                    </span>
                </div>
                <div class="section-body">
                    <div class="gallery-grid">
                        @foreach($evento->imagenes as $index => $foto)
                            <div class="g-item {{ $index === 0 ? 'g-first' : '' }}"
                                 onclick="openLightbox('{{ asset('storage/' . $foto->ruta) }}', {{ $index }})">
                                <img src="{{ asset('storage/' . $foto->ruta) }}"
                                     alt="Foto {{ $index + 1 }} del evento"
                                     loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Detalles del establecimiento --}}
        <div class="section-card">
            <div class="section-head">
                <div class="section-title">
                    <i class="fas fa-store"></i> Establecimiento organizador
                </div>
                <a href="{{ route('restaurantes.show', $restaurante) }}" class="btn-sm">
                    Ver perfil <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                </a>
            </div>
            <div class="section-body">
                <p class="desc-text">
                    Evento organizado por <strong>{{ $restaurante->nombre }}</strong>
                    @if($restaurante->especialidad)
                        , especialistas en <strong>{{ $restaurante->especialidad }}</strong>
                    @endif
                    , ubicado en {{ $restaurante->municipio?->nombre ?? $restaurante->departamento->nombre }},
                    {{ $restaurante->departamento->nombre }}.
                </p>
            </div>
        </div>

    </main>

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">

        {{-- Información del evento --}}
        <div class="sidebar-card">
            <div class="sidebar-card-head"><i class="far fa-calendar-alt"></i> Información</div>
            <div class="sidebar-card-body">
                <div class="info-row">
                    <span class="info-label"><i class="far fa-calendar-alt" style="color:#d97706;font-size:11px;"></i> Fecha</span>
                    <span class="info-value" style="font-size:13px;">
                        {{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('d \d\e F, Y') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-ticket-alt" style="color:var(--orange);font-size:11px;"></i> Entrada</span>
                    <span class="info-value">
                        @if($evento->precio > 0)
                            C$ {{ number_format($evento->precio, 0) }}
                        @else
                            <span style="color:#16a34a;">Libre</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-building" style="color:#15803d;font-size:11px;"></i> Sede</span>
                    <span class="info-value" style="font-size:13px;">{{ $restaurante->nombre }}</span>
                </div>
                <div class="info-row" style="margin-bottom:0;">
                    <span class="info-label"><i class="fas fa-map-marker-alt" style="color:var(--orange);font-size:11px;"></i> Lugar</span>
                    <span class="info-value" style="font-size:13px;">
                        {{ $evento->municipio?->nombre ?? '—' }},
                        {{ $evento->departamento->nombre }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Vigencia --}}
        @if(!$isPast)
            <div class="sidebar-card">
                <div class="sidebar-card-head"><i class="far fa-clock"></i> Vigencia</div>
                <div class="sidebar-card-body" style="text-align:center;padding:20px 18px;">
                    <span class="countdown countdown-badge countdown-active"
                          data-expire="{{ $evento->fecha_evento }}"
                          style="font-size:14px;">
                        Calculando…
                    </span>
                </div>
            </div>
        @endif

        {{-- Contacto --}}
        @if($restaurante->email)
            <div class="sidebar-card">
                <div class="sidebar-card-head"><i class="fas fa-envelope"></i> Contacto</div>
                <div class="sidebar-card-body">
                    <div style="background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:13px;font-weight:600;color:#374151;word-break:break-all;">
                        <i class="fas fa-envelope" style="color:var(--orange);margin-right:8px;"></i>
                        {{ $restaurante->email }}
                    </div>
                </div>
            </div>
        @endif

        {{-- Redes sociales --}}
        @if(!empty($restaurante->whatsapp) || !empty($restaurante->instagram) || !empty($restaurante->tiktok) || !empty($restaurante->facebook))
            <div class="sidebar-card">
                <div class="sidebar-card-head"><i class="fas fa-share-alt"></i> Redes sociales</div>
                <div class="sidebar-card-body">
                    <div class="social-grid">
                        @if(!empty($restaurante->whatsapp))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurante->whatsapp) }}" target="_blank" class="social-btn social-wa" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                        @if(!empty($restaurante->instagram))
                            <a href="{{ $restaurante->instagram }}" target="_blank" class="social-btn social-ig" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if(!empty($restaurante->tiktok))
                            <a href="{{ $restaurante->tiktok }}" target="_blank" class="social-btn social-tt" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        @endif
                        @if(!empty($restaurante->facebook))
                            <a href="{{ $restaurante->facebook }}" target="_blank" class="social-btn social-fb" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- CTAs --}}
        <a href="mailto:{{ $restaurante->email ?? 'contacto@gastronicaragua.com' }}?subject=Consulta sobre evento: {{ $evento->titulo }}"
           class="btn-cta-secondary">
            <i class="fas fa-paper-plane" style="font-size:13px;"></i>
            Consultar al local
        </a>

        @if(!empty($restaurante->whatsapp))
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurante->whatsapp) }}?text={{ urlencode('Hola, me interesa el evento: ' . $evento->titulo) }}"
               target="_blank"
               style="display:flex;align-items:center;justify-content:center;gap:9px;width:100%;background:#22c55e;color:white;font-size:14px;font-weight:700;padding:14px 20px;border-radius:12px;text-decoration:none;transition:all 0.18s;letter-spacing:0.02em;"
               onmouseover="this.style.background='#16a34a';this.style.transform='translateY(-1px)'"
               onmouseout="this.style.background='#22c55e';this.style.transform='none'">
                <i class="fab fa-whatsapp" style="font-size:18px;"></i>
                Consultar por WhatsApp
            </a>
        @endif

    </aside>
</div>

{{-- ══ FOOTER ══ --}}
<footer class="footer-main">
    <div style="max-width:1280px;margin:0 auto;padding:48px 1.5rem 32px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:32px;margin-bottom:40px;align-items:start;">

            {{-- Columna marca --}}
            <div style="grid-column:span 2 / span 2;max-width:380px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                    <span class="footer-brand-name">Gastro<span>Nicaragua</span></span>
                </div>
                <p style="color:#a8a29e;font-size:14px;line-height:1.7;font-weight:300;margin-bottom:16px;">
                    La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                    Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                </p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <a href="#" class="footer-social-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="footer-social-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="footer-social-btn"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            {{-- Columna Portal --}}
            <div>
                <h4>Portal</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('restaurantes.index') }}" style="color:#ea580c;font-weight:600;">Restaurantes</a></li>
                    <li><a href="{{ route('gastrobares.index') }}" style="color:#a8a29e;" onmouseover="this.style.color='#a855f7'" onmouseout="this.style.color='#a8a29e'">Gastrobares</a></li>
                    <li><a href="{{ route('empleos.index') }}">Bolsa de Empleos</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>

            {{-- Columna Destinos --}}
            <div>
                <h4>Destinos Destacados</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    @foreach(['Masaya','Granada','León','San Juan del Sur','Estelí','Matagalpa'] as $destino)
                        <span class="footer-destino">
                            <i class="fas fa-chevron-right" style="font-size:9px;color:#ea580c;margin-right:6px;"></i>{{ $destino }}
                        </span>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Línea inferior --}}
        <div style="border-top:1px solid #292524;padding-top:24px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px;">
            <p style="font-size:12px;color:#57534e;">&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
            <div style="display:flex;gap:16px;">
                <a href="#" class="footer-bottom-link">Política de Privacidad</a>
                <a href="#" class="footer-bottom-link">Términos de Servicio</a>
            </div>
        </div>
    </div>
</footer>

{{-- ══ LIGHTBOX ══ --}}
<div id="lightbox" onclick="handleLbClick(event)">
    <button id="lb-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
    <button id="lb-prev" class="lb-btn" onclick="navLightbox(-1)"><i class="fas fa-chevron-left"></i></button>
    <button id="lb-next" class="lb-btn" onclick="navLightbox(1)"><i class="fas fa-chevron-right"></i></button>
    <img id="lightbox-img" src="" alt="" style="transition:opacity 0.16s ease, transform 0.16s ease;">
    <div id="lb-counter">
        <span id="lb-text"></span>
        <div id="lb-dots"></div>
    </div>
</div>

<script>
    // ── Countdown ────────────────────────────────────────
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(el => {
            const exp      = new Date(el.getAttribute('data-expire')).getTime();
            const distance = exp - Date.now();
            if (distance < 0) {
                el.textContent = 'FINALIZADO';
                el.classList.remove('countdown-active');
                el.classList.add('countdown-ended');
                return;
            }
            const d = Math.floor(distance / 86400000);
            const h = Math.floor((distance % 86400000) / 3600000);
            const m = Math.floor((distance % 3600000)  / 60000);
            el.textContent = `Faltan ${d}d ${h}h ${m}m`;
        });
    }
    setInterval(updateCountdowns, 60000);
    updateCountdowns();

    // ── Lightbox ─────────────────────────────────────────
    const galeriaImages = @json($galeriaImages);
    let curIdx = 0, isHero = false;
    const lb    = document.getElementById('lightbox');
    const lbImg = document.getElementById('lightbox-img');
    const lbText = document.getElementById('lb-text');
    const lbDots = document.getElementById('lb-dots');

    function openLightbox(src, index) {
        isHero   = false; curIdx = index;
        lbImg.src = src;
        lb.classList.add('active');
        document.body.style.overflow = 'hidden';
        updateLb();
    }

    function openHeroLightbox(src) {
        isHero    = true;
        lbImg.src = src;
        lb.classList.add('active');
        document.body.style.overflow = 'hidden';
        updateLb();
    }

    function closeLightbox() {
        lb.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => { lbImg.src = ''; }, 300);
    }

    function navLightbox(dir) {
        if (isHero || galeriaImages.length === 0) return;
        curIdx = (curIdx + dir + galeriaImages.length) % galeriaImages.length;
        lbImg.style.opacity = '0'; lbImg.style.transform = 'scale(0.95)';
        setTimeout(() => {
            lbImg.src = galeriaImages[curIdx];
            lbImg.style.opacity = '1'; lbImg.style.transform = 'scale(1)';
            updateLb();
        }, 160);
    }

    function updateLb() {
        const showNav = !isHero && galeriaImages.length > 1;
        document.getElementById('lb-prev').style.display = showNav ? 'flex' : 'none';
        document.getElementById('lb-next').style.display = showNav ? 'flex' : 'none';
        if (!isHero && galeriaImages.length > 0) {
            lbText.textContent = `${curIdx + 1} / ${galeriaImages.length}`;
            lbDots.innerHTML   = galeriaImages.map((_,i) =>
                `<span class="lb-dot ${i===curIdx?'on':''}" onclick="jumpLb(${i})"></span>`
            ).join('');
        } else {
            lbText.textContent = 'Imagen del evento';
            lbDots.innerHTML   = '';
        }
    }

    function jumpLb(i) { curIdx = i; lbImg.src = galeriaImages[i]; updateLb(); }
    function handleLbClick(e) { if (e.target === lb) closeLightbox(); }

    document.addEventListener('keydown', e => {
        if (!lb.classList.contains('active')) return;
        if (e.key === 'Escape')     closeLightbox();
        if (e.key === 'ArrowRight') navLightbox(1);
        if (e.key === 'ArrowLeft')  navLightbox(-1);
    });
</script>

</body>
</html>
