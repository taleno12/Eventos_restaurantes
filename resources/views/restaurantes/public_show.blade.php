<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurante->nombre }} | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900|instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:    #2563eb;
            --blue-lt: #eff6ff;
            --border:  #e2e8f0;
            --text:    #0f172a;
            --muted:   #64748b;
            --bg:      #f8fafc;
            --white:   #ffffff;
            --radius:  12px;
            --shadow:  0 1px 4px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04);
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
        .topbar-logo .dot { color: var(--blue); }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }
        .btn-sm {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; padding: 7px 14px;
            border-radius: 999px; border: 1.5px solid var(--border);
            background: var(--white); color: var(--muted);
            cursor: pointer; transition: all 0.18s;
        }
        .btn-sm:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-lt); }
        .btn-sm.primary { background: var(--blue); color: white; border-color: var(--blue); }
        .btn-sm.primary:hover { background: #1d4ed8; border-color: #1d4ed8; }

        /* ══ HERO ══ */
        .hero-banner {
            position: relative; width: 100%;
            height: 240px; overflow: hidden; background: #1f2937;
        }
        @media (min-width: 768px) { .hero-banner { height: 300px; } }
        .hero-banner img {
            width: 100%; height: 100%; object-fit: cover;
            opacity: 0.75; display: block;
        }
        .hero-banner-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.55) 100%);
        }

        /* ══ RESTAURANT HEADER ══ */
        .rest-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
        }
        .rest-header-inner {
            max-width: 1280px; margin: 0 auto; padding: 0 1.5rem;
        }
        .rest-header-top {
            display: flex; align-items: flex-end; gap: 20px;
            padding-top: 0; transform: translateY(-40px); margin-bottom: -40px;
        }
        .rest-logo-wrap {
            width: 100px; height: 100px; border-radius: 18px;
            border: 4px solid var(--white); overflow: hidden;
            background: var(--blue-lt); flex-shrink: 0;
            box-shadow: var(--shadow);
            display: flex; align-items: center; justify-content: center;
        }
        .rest-logo-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .rest-logo-placeholder {
            font-size: 2.5rem; color: var(--blue); opacity: 0.5;
        }
        .rest-info { padding-bottom: 8px; min-width: 0; flex: 1; }
        .rest-info h1 {
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 800; color: var(--text); line-height: 1.15;
            margin-bottom: 6px;
        }
        .rest-meta {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
            font-size: 13px; color: var(--muted);
        }
        .rest-meta-item { display: flex; align-items: center; gap: 5px; font-weight: 600; }
        .rest-meta-item i { color: var(--blue); font-size: 11px; }
        .rest-meta-sep { color: var(--border); }

        .rest-header-bottom {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 0 16px; gap: 12px; flex-wrap: wrap;
        }
        .rest-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 700; padding: 5px 12px;
            border-radius: 999px; border: 1.5px solid;
        }
        .badge-green  { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .badge-red    { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .badge-gray   { background: #f9fafb; color: #64748b; border-color: #e2e8f0; }
        .badge-blue   { background: var(--blue-lt); color: var(--blue); border-color: #bfdbfe; }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
        .badge-dot.green { background: #22c55e; }
        .badge-dot.red   { background: #ef4444; }

        .btn-ordenar {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--blue); color: white;
            font-size: 14px; font-weight: 700; padding: 10px 24px;
            border-radius: 999px; border: none; cursor: pointer;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            transition: all 0.18s; white-space: nowrap;
        }
        .btn-ordenar:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }

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
        .stat-val .accent { color: var(--blue); }
        .stat-lbl { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: var(--muted); margin-top: 3px; }

        /* ══ LAYOUT PRINCIPAL ══ */
        .page-body {
            max-width: 1280px; margin: 0 auto; padding: 28px 1.5rem 80px;
            display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;
        }
        @media (max-width: 1024px) {
            .page-body { grid-template-columns: 1fr; }
            .sidebar { order: -1; }
        }

        /* ══ SECCIÓN CONTENIDO ══ */
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
        .section-title i { color: var(--blue); font-size: 13px; }
        .section-body { padding: 20px 22px; }

        /* descripción */
        .desc-text { font-size: 14.5px; color: #475569; line-height: 1.8; }

        /* galería */
        .gallery-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 110px; gap: 8px;
        }
        @media (max-width: 600px) { .gallery-grid { grid-template-columns: repeat(3, 1fr); grid-auto-rows: 85px; } }
        @media (max-width: 400px) { .gallery-grid { grid-template-columns: repeat(2, 1fr); } }
        .gallery-grid .g-first { grid-column: span 2; grid-row: span 2; }
        .g-item { border-radius: 10px; overflow: hidden; cursor: zoom-in; position: relative; background: #f1f5f9; }
        .g-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease; }
        .g-item:hover img { transform: scale(1.07); }
        .g-item::after {
            content: '\f00e'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white; background: rgba(0,0,0,0); opacity: 0; transition: all 0.25s;
        }
        .g-item:hover::after { background: rgba(0,0,0,0.35); opacity: 1; }

        /* mapa */
        #mapa-publico { height: 250px; border-radius: 10px; overflow: hidden; border: 1px solid var(--border); }
        .dir-box {
            display: flex; align-items: flex-start; gap: 10px;
            background: var(--blue-lt); border: 1px solid #bfdbfe;
            border-radius: 10px; padding: 12px 16px; margin-bottom: 14px;
            font-size: 13.5px; color: #475569; line-height: 1.6;
        }
        .dir-box i { color: var(--blue); margin-top: 2px; flex-shrink: 0; }
        .btn-gmaps {
            display: inline-flex; align-items: center; gap: 8px;
            background: #1f2937; color: white; font-size: 13px; font-weight: 700;
            padding: 10px 20px; border-radius: 10px; margin-top: 14px; transition: all 0.18s;
        }
        .btn-gmaps:hover { background: var(--blue); transform: translateY(-1px); }

        /* reseñas */
        .rating-summary {
            display: flex; align-items: center; gap: 20px;
            background: var(--blue-lt); border: 1px solid #bfdbfe;
            border-radius: 12px; padding: 18px 22px; margin-bottom: 20px;
        }
        .rating-big { font-size: 3rem; font-weight: 900; color: var(--blue); line-height: 1; text-align: center; }
        .rating-lbl { font-size: 10px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 2px; }
        .bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
        .bar-track { flex: 1; height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden; }
        .bar-fill  { height: 100%; background: var(--blue); border-radius: 999px; transition: width 0.5s ease; }

        .review-item { border-bottom: 1px solid #f1f5f9; padding-bottom: 18px; margin-bottom: 18px; }
        .review-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .review-avatar {
            width: 38px; height: 38px; border-radius: 50%; background: #bfdbfe;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 800; color: var(--blue); flex-shrink: 0; overflow: hidden;
        }
        .review-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .form-input {
            width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
            border-radius: 10px; font-size: 14px; outline: none; font-family: inherit;
            background: var(--white); transition: border-color 0.2s; color: var(--text);
        }
        .form-input:focus { border-color: var(--blue); }
        .form-textarea { resize: vertical; }
        .form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); display: block; margin-bottom: 6px; }

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
        .sidebar-card-head i { color: var(--blue); }
        .sidebar-card-body { padding: 16px 18px; }

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

        /* horario */
        .dias-grid { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 16px; }
        .dia-pill { font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 999px; letter-spacing: 0.04em; }
        .dia-pill.on  { background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe; }
        .dia-pill.off { background: #f9fafb; color: #cbd5e1; border: 1.5px solid #e2e8f0; }
        .hor-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 14px; border-radius: 10px; background: var(--bg);
            border: 1px solid var(--border); margin-bottom: 8px;
        }
        .hor-row:last-child { margin-bottom: 0; }
        .hor-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); display: flex; align-items: center; gap: 6px; }
        .hor-value { font-size: 14px; font-weight: 800; color: var(--text); }

        /* CTAs de contacto */
        .btn-tel-cta {
            display: flex; align-items: center; justify-content: center; gap: 9px;
            width: 100%; background: var(--blue); color: white;
            font-size: 14px; font-weight: 700; padding: 14px 20px;
            border-radius: 12px; border: none; cursor: pointer; transition: all 0.18s;
            text-decoration: none; letter-spacing: 0.02em;
        }
        .btn-tel-cta:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(37,99,235,0.3); }

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
        .lb-btn:hover { background: rgba(37,99,235,0.65); }
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
        .lb-dot.on { background: var(--blue); transform: scale(1.35); }

        @keyframes pulseDot { 0%,100%{box-shadow:0 0 0 2px rgba(34,197,94,.25)} 50%{box-shadow:0 0 0 4px rgba(34,197,94,.1)} }

        /* ══ FLASH MESSAGES ══ */
        .alert { border-radius: 10px; padding: 12px 16px; font-size: 13px; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        /* mobile */
        @media (max-width: 640px) {
            .stats-bar-inner { padding: 0 1rem; }
            .rest-header-inner { padding: 0 1rem; }
            .page-body { padding: 16px 1rem 60px; gap: 16px; }
            .topbar { padding: 0 1rem; }
            .hero-banner { height: 180px; }
            .rest-logo-wrap { width: 80px; height: 80px; }
            .rest-header-top { transform: translateY(-30px); margin-bottom: -30px; }
        }
    </style>
</head>
<body>

{{-- ══ TOPBAR ══ --}}
<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('home') }}" class="topbar-logo">
            Gastro<span class="dot">Nicaragua</span>
        </a>
        <div class="topbar-actions">
            <a href="{{ route('restaurantes.index') }}" class="btn-sm">
                <i class="fas fa-arrow-left" style="font-size:10px;"></i> Volver
            </a>
            @auth
                <a href="{{ route('pedidos.mis') }}" class="btn-sm">
                    <i class="fas fa-bag-shopping" style="font-size:10px;"></i>
                    <span class="hidden-xs">Mis Pedidos</span>
                </a>
                @if(auth()->user()->restaurante && auth()->user()->restaurante->id === $restaurante->id)
                    <a href="{{ route('restaurante.dashboard') }}" class="btn-sm primary">
                        <i class="fas fa-chart-pie" style="font-size:10px;"></i> Mi Panel
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-sm primary">
                    <i class="fas fa-sign-in-alt" style="font-size:10px;"></i> Ingresar
                </a>
            @endauth
        </div>
    </div>
</header>
{{-- ══ HERO BANNER ══ --}}
@php
    $bgUrl = '';
    if($restaurante->foto_portada)
        $bgUrl = asset('storage/' . $restaurante->foto_portada);
    elseif($restaurante->imagenes && $restaurante->imagenes->count() > 0)
        $bgUrl = asset('storage/' . $restaurante->imagenes->first()->ruta_foto);
@endphp
<div class="hero-banner">
    @if($bgUrl)
        <img src="{{ $bgUrl }}" alt="{{ $restaurante->nombre }}">
    @endif
    <div class="hero-banner-overlay"></div>
</div>

{{-- ══ RESTAURANT HEADER ══ --}}
<div class="rest-header">
    <div class="rest-header-inner">

        <div class="rest-header-top">
            <div class="rest-logo-wrap">
                @if($restaurante->foto_portada)
                    <img src="{{ asset('storage/' . $restaurante->foto_portada) }}" alt="{{ $restaurante->nombre }}">
                @elseif($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                    <img src="{{ asset('storage/' . $restaurante->imagenes->first()->ruta_foto) }}" alt="{{ $restaurante->nombre }}">
                @else
                    <span class="rest-logo-placeholder"><i class="fas fa-utensils"></i></span>
                @endif
            </div>
            <div class="rest-info">
                <h1>{{ $restaurante->nombre }}</h1>
                <div class="rest-meta">
                    <span class="rest-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>{{ $restaurante->departamento->nombre }}</strong>
                        @if($restaurante->municipio)
                            · {{ $restaurante->municipio->nombre }}
                        @endif
                    </span>
                    @if($restaurante->especialidad)
                        <span class="rest-meta-sep">·</span>
                        <span class="rest-meta-item">
                            <i class="fas fa-utensils"></i> {{ $restaurante->especialidad }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="rest-header-bottom">
            @php
                $totalReviews = $restaurante->reviews()->count();
                $avgRating    = $totalReviews > 0 ? round($restaurante->reviews()->avg('rating'), 1) : null;
                $diasActivos  = $restaurante->dias_laborales ?? [];
                if (is_string($diasActivos)) $diasActivos = json_decode($diasActivos, true) ?? [];
                $diasMap = ['lunes'=>1,'martes'=>2,'miercoles'=>3,'jueves'=>4,'viernes'=>5,'sabado'=>6,'domingo'=>0];
                $tieneHorario    = !empty($restaurante->hora_apertura) && !empty($restaurante->hora_cierre);
                $estaAbierto = false;
                $diaHoyNum   = (int) now()->setTimezone('America/Managua')->format('w');
                $horaActual  = now()->setTimezone('America/Managua')->format('H:i');
                if ($tieneHorario) {
                    $hoyEsLaboral = empty($diasActivos) ? true : collect($diasActivos)->contains(fn($d) => ($diasMap[$d] ?? -1) === $diaHoyNum);
                    if ($hoyEsLaboral) {
                        $ap = substr($restaurante->hora_apertura, 0, 5);
                        $ci = substr($restaurante->hora_cierre,   0, 5);
                        $estaAbierto = $ci > $ap ? ($horaActual >= $ap && $horaActual < $ci) : ($horaActual >= $ap || $horaActual < $ci);
                    }
                }
            @endphp

            <div class="rest-badges">
                @if($avgRating)
                    <span class="badge badge-blue">
                        <i class="fas fa-star" style="font-size:10px;"></i>
                        {{ $avgRating }} · {{ $totalReviews }} {{ $totalReviews === 1 ? 'reseña' : 'reseñas' }}
                    </span>
                @endif

                @if($tieneHorario)
                    @if($estaAbierto)
                        <span class="badge badge-green">
                            <span class="badge-dot green" style="animation:pulseDot 2s infinite;"></span>
                            Abierto ahora
                        </span>
                    @else
                        <span class="badge badge-red">
                            <span class="badge-dot red"></span>
                            Cerrado ahora
                        </span>
                    @endif
                @else
                    <span class="badge badge-gray">
                        <i class="fas fa-clock" style="font-size:10px;"></i> Horario no configurado
                    </span>
                @endif

                @if($restaurante->especialidad)
                    <span class="badge badge-gray">
                        <i class="fas fa-tag" style="font-size:10px;"></i> {{ $restaurante->especialidad }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══ STATS BAR ══ --}}
<div class="stats-bar">
    <div class="stats-bar-inner">
        @if($avgRating)
            <div class="stat-item">
                <div class="stat-val"><span class="accent">★</span> {{ $avgRating }}</div>
                <div class="stat-lbl">Calificación</div>
            </div>
        @endif
        <div class="stat-item">
            <div class="stat-val">{{ $totalReviews }}</div>
            <div class="stat-lbl">Reseñas</div>
        </div>
        @if($restaurante->municipio)
            <div class="stat-item">
                <div class="stat-val" style="font-size:14px;">{{ $restaurante->municipio->nombre }}</div>
                <div class="stat-lbl">Municipio</div>
            </div>
        @endif
        @if($restaurante->especialidad)
            <div class="stat-item">
                <div class="stat-val" style="font-size:13px;color:var(--blue);">{{ $restaurante->especialidad }}</div>
                <div class="stat-lbl">Especialidad</div>
            </div>
        @endif
        @if($tieneHorario)
            <div class="stat-item">
                <div class="stat-val" style="font-size:14px;">
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $restaurante->hora_apertura)->format('h:i A') }}
                    –
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $restaurante->hora_cierre)->format('h:i A') }}
                </div>
                <div class="stat-lbl">Horario</div>
            </div>
        @endif
    </div>
</div>

{{-- ══ CUERPO PRINCIPAL ══ --}}
@php
    $todasLasImagenes = [];
    if($restaurante->imagenes) {
        foreach($restaurante->imagenes as $foto) {
            $todasLasImagenes[] = asset('storage/' . $foto->ruta_foto);
        }
    }
@endphp

<div class="page-body">

    {{-- COLUMNA PRINCIPAL --}}
    <main>

        {{-- Botón "Ordenar ahora" --}}
        <div class="section-card">
            <div class="section-head">
                <div class="section-title"><i class="fas fa-align-left"></i> Sobre el restaurante</div>
                @if(isset($platos) && !$platos->isEmpty())
                    <a href="{{ route('restaurantes.ordenar', $restaurante) }}" class="btn-ordenar">
                        <i class="fas fa-utensils" style="font-size:11px;"></i> Ordenar ahora
                    </a>
                @endif
            </div>
            <div class="section-body">
                <p class="desc-text">
                    Bienvenidos a <strong>{{ $restaurante->nombre }}</strong>.
                    @if($restaurante->descripcion)
                        {{ $restaurante->descripcion }}
                    @else
                        Somos especialistas en ofrecer la mejor experiencia culinaria en la hermosa localidad
                        de {{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}.
                        ¡Visítanos y disfruta de una sazón inigualable!
                    @endif
                </p>
            </div>
        </div>

        {{-- Galería --}}
        @if($restaurante->imagenes && $restaurante->imagenes->count() > 0)
            <div class="section-card">
                <div class="section-head">
                    <div class="section-title"><i class="fas fa-images"></i> Galería de fotos</div>
                    <span style="font-size:11px;color:var(--muted);">clic para ampliar</span>
                </div>
                <div class="section-body">
                    <div class="gallery-grid">
                        @foreach($restaurante->imagenes as $index => $foto)
                            <div class="g-item {{ $index === 0 ? 'g-first' : '' }}"
                                 onclick="openLightbox('{{ asset('storage/' . $foto->ruta_foto) }}', {{ $index }})">
                                <img src="{{ asset('storage/' . $foto->ruta_foto) }}"
                                     alt="Foto {{ $index + 1 }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Mapa --}}
        @if($restaurante->latitud && $restaurante->longitud)
            <div class="section-card">
                <div class="section-head">
                    <div class="section-title"><i class="fas fa-map-marker-alt"></i> Cómo llegar</div>
                </div>
                <div class="section-body">
                    @if($restaurante->direccion)
                        <div class="dir-box">
                            <i class="fas fa-map-pin"></i>
                            <p>{{ $restaurante->direccion }}</p>
                        </div>
                    @endif
                    <div id="mapa-publico"></div>
                    <a href="https://www.google.com/maps?q={{ $restaurante->latitud }},{{ $restaurante->longitud }}"
                       target="_blank" class="btn-gmaps">
                        <i class="fas fa-directions"></i> Abrir en Google Maps
                    </a>
                </div>
            </div>
        @endif

        {{-- Reseñas --}}
        <div class="section-card" id="resenas">
            <div class="section-head">
                <div class="section-title">
                    <i class="fas fa-star"></i> Reseñas
                </div>
                <span style="font-size:12px;color:var(--muted);font-weight:600;">
                    {{ $totalReviews }} {{ $totalReviews === 1 ? 'reseña' : 'reseñas' }}
                </span>
            </div>
            <div class="section-body">

                @if(session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
                @endif

                @if($totalReviews > 0)
                    <div class="rating-summary">
                        <div>
                            <div class="rating-big">{{ $avgRating }}</div>
                            <div class="rating-lbl">de 5</div>
                        </div>
                        <div style="flex:1;">
                            @for($s = 5; $s >= 1; $s--)
                                @php $cnt = $restaurante->reviews()->where('rating',$s)->count(); @endphp
                                <div class="bar-row">
                                    <span style="font-size:11px;font-weight:700;color:var(--muted);min-width:10px;">{{ $s }}</span>
                                    <i class="fas fa-star" style="font-size:10px;color:#f59e0b;"></i>
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width:{{ $totalReviews > 0 ? round($cnt/$totalReviews*100) : 0 }}%;"></div>
                                    </div>
                                    <span style="font-size:11px;color:var(--muted);font-weight:600;min-width:18px;">{{ $cnt }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endif

                {{-- Formulario nueva reseña --}}
                @auth
                    @php $miResena = $restaurante->reviews()->where('user_id', auth()->id())->first(); @endphp
                    @if(!$miResena)
                        <div style="background:var(--bg);border:1.5px solid var(--border);border-radius:12px;padding:20px;margin-bottom:24px;">
                            <p style="font-size:12px;font-weight:800;color:var(--text);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:14px;">
                                <i class="fas fa-pencil-alt" style="color:var(--blue);margin-right:6px;"></i> Deja tu reseña
                            </p>
                            <form action="{{ route('reviews.store', $restaurante) }}" method="POST">
                                @csrf
                                <div style="margin-bottom:14px;">
                                    <label class="form-label">Calificación</label>
                                    <div style="display:flex;gap:4px;" id="star-selector">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" onclick="setRating({{ $i }})" data-star="{{ $i }}"
                                                    style="font-size:28px;background:none;border:none;cursor:pointer;color:#d1d5db;transition:color 0.15s,transform 0.15s;padding:0 2px;"
                                                    onmouseover="hoverRating({{ $i }})" onmouseout="resetHover()">★</button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating-input" value="">
                                    @error('rating')<<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                                </div>
                                <div style="margin-bottom:10px;">
                                    <label class="form-label">Título <span style="color:#d1d5db;font-weight:400;">(opcional)</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="Ej: Excelente experiencia" maxlength="100">
                                    @error('title')<<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                                </div>
                                <div style="margin-bottom:14px;">
                                    <label class="form-label">Comentario <span style="color:#d1d5db;font-weight:400;">(opcional)</span></label>
                                    <textarea name="body" rows="3" class="form-input form-textarea" placeholder="Cuéntanos tu experiencia..." maxlength="1000">{{ old('body') }}</textarea>
                                    @error('body')<<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                                </div>
                                <button type="submit" class="btn-ordenar" style="font-size:13px;padding:9px 22px;">
                                    <i class="fas fa-paper-plane" style="font-size:11px;"></i> Publicar reseña
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div style="background:var(--blue-lt);border:1px solid #bfdbfe;border-radius:12px;padding:18px 20px;text-align:center;margin-bottom:24px;">
                        <i class="fas fa-lock" style="color:var(--blue);font-size:20px;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:14px;color:#475569;font-weight:600;margin-bottom:12px;">Inicia sesión para dejar tu reseña</p>
                        <a href="{{ route('login') }}" class="btn-ordenar" style="font-size:13px;padding:9px 22px;display:inline-flex;">
                            <i class="fas fa-sign-in-alt" style="font-size:11px;"></i> Iniciar sesión
                        </a>
                    </div>
                @endauth

                {{-- Lista reseñas --}}
                @php $reviews = $restaurante->reviews()->with('user')->latest()->get(); @endphp

                @forelse($reviews as $review)
                    <div class="review-item">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">
                            <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                                <div class="review-avatar">
                                    @if($review->user->avatar)
                                        <img src="{{ $review->user->avatar }}" alt="">
                                    @else
                                        {{ strtoupper(substr($review->user->name,0,1)) }}
                                    @endif
                                </div>
                                <div>
                                    <p style="font-size:14px;font-weight:700;color:var(--text);margin:0;">{{ $review->user->name }}</p>
                                    <p style="font-size:11px;color:var(--muted);margin:0;">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div style="display:flex;gap:2px;flex-shrink:0;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color:{{ $i <= $review->rating ? '#f59e0b' : '#e2e8f0' }};font-size:14px;">★</span>
                                @endfor
                            </div>
                        </div>
                        @if($review->title)<p style="font-size:14px;font-weight:700;margin-bottom:4px;">{{ $review->title }}</p>@endif
                        @if($review->body)<p style="font-size:14px;color:#475569;line-height:1.7;margin:0;">{{ $review->body }}</p>@endif

                        @auth
                            @if(auth()->id() === $review->user_id || auth()->user()->email === 'admin@turismo.ni')
                                <div style="display:flex;gap:7px;margin-top:10px;">
                                    <button onclick="toggleEdit({{ $review->id }})" class="btn-sm" style="font-size:11px;padding:5px 11px;">
                                        <i class="fas fa-pencil-alt" style="font-size:9px;"></i> Editar
                                    </button>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('¿Eliminar esta reseña?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-sm" style="font-size:11px;padding:5px 11px;">
                                            <i class="fas fa-trash" style="font-size:9px;"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                                <div id="edit-form-{{ $review->id }}" style="display:none;margin-top:12px;background:var(--bg);border:1.5px solid var(--border);border-radius:12px;padding:16px;">
                                    <form action="{{ route('reviews.update', $review) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div style="margin-bottom:12px;">
                                            <label class="form-label">Calificación</label>
                                            <div style="display:flex;gap:4px;" id="edit-stars-{{ $review->id }}">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" onclick="setEditRating({{ $review->id }},{{ $i }})" data-star="{{ $i }}"
                                                            style="font-size:22px;background:none;border:none;cursor:pointer;padding:0 2px;color:{{ $i <= $review->rating ? '#f59e0b' : '#d1d5db' }};transition:color 0.15s;">★</button>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="edit-rating-{{ $review->id }}" value="{{ $review->rating }}">
                                        </div>
                                        <input type="text" name="title" value="{{ $review->title }}" class="form-input" placeholder="Título" maxlength="100" style="margin-bottom:8px;">
                                        <textarea name="body" rows="3" maxlength="1000" class="form-input form-textarea" style="margin-bottom:12px;">{{ $review->body }}</textarea>
                                        <button type="submit" class="btn-ordenar" style="font-size:12px;padding:8px 18px;">Guardar cambios</button>
                                        <button type="button" onclick="toggleEdit({{ $review->id }})" class="btn-sm" style="margin-left:6px;">Cancelar</button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                @empty
                    <div style="text-align:center;padding:36px 20px;">
                        <i class="far fa-comment-dots" style="font-size:32px;color:#d1d5db;display:block;margin-bottom:10px;"></i>
                        <p style="color:var(--muted);font-size:14px;font-weight:600;">Aún no hay reseñas. ¡Sé el primero!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <script>
            let selectedRating = 0;
            function setRating(val) { selectedRating = val; document.getElementById('rating-input').value = val; updateStars('star-selector', val, '#f59e0b'); }
            function hoverRating(val) { updateStars('star-selector', val, '#60a5fa'); }
            function resetHover() { updateStars('star-selector', selectedRating, '#f59e0b'); }
            function updateStars(id, val, color) {
                document.querySelectorAll(`#${id} button`).forEach(btn => {
                    btn.style.color = parseInt(btn.dataset.star) <= val ? color : '#d1d5db';
                });
            }
            function toggleEdit(id) { const el = document.getElementById(`edit-form-${id}`); el.style.display = el.style.display === 'none' ? 'block' : 'none'; }
            function setEditRating(rid, val) {
                document.getElementById(`edit-rating-${rid}`).value = val;
                document.querySelectorAll(`#edit-stars-${rid} button`).forEach(btn => {
                    btn.style.color = parseInt(btn.dataset.star) <= val ? '#f59e0b' : '#d1d5db';
                });
            }
        </script>
    </main>

    {{-- ══ SIDEBAR ══ --}}
    <aside class="sidebar">

        {{-- Redes sociales --}}
        @if(!empty($restaurante->whatsapp) || !empty($restaurante->instagram) || !empty($restaurante->tiktok) || !empty($restaurante->facebook))
            <div class="sidebar-card">
                <div class="sidebar-card-head"><i class="fas fa-share-alt"></i> Redes sociales</div>
                <div class="sidebar-card-body">
                    <div class="social-grid">
                        @if(!empty($restaurante->whatsapp))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$restaurante->whatsapp) }}" target="_blank" class="social-btn social-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        @endif
                        @if(!empty($restaurante->instagram))
                            <a href="{{ $restaurante->instagram }}" target="_blank" class="social-btn social-ig" title="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($restaurante->tiktok))
                            <a href="{{ $restaurante->tiktok }}" target="_blank" class="social-btn social-tt" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        @endif
                        @if(!empty($restaurante->facebook))
                            <a href="{{ $restaurante->facebook }}" target="_blank" class="social-btn social-fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Horario --}}
        <div class="sidebar-card">
            <div class="sidebar-card-head"><i class="far fa-clock"></i> Horario de atención</div>
            <div class="sidebar-card-body">
                @php
                    $diasConfig = ['lunes'=>'Lun','martes'=>'Mar','miercoles'=>'Mié','jueves'=>'Jue','viernes'=>'Vie','sabado'=>'Sáb','domingo'=>'Dom'];
                @endphp
                <div class="dias-grid">
                    @foreach($diasConfig as $valor => $etiqueta)
                        <span class="dia-pill {{ in_array($valor, $diasActivos) ? 'on' : 'off' }}">{{ $etiqueta }}</span>
                    @endforeach
                </div>
                <div class="hor-row">
                    <span class="hor-label"><i class="fas fa-door-open" style="color:#22c55e;font-size:11px;"></i> Apertura</span>
                    <span class="hor-value">
                        @if($restaurante->hora_apertura)
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $restaurante->hora_apertura)->format('h:i A') }}
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </span>
                </div>
                <div class="hor-row">
                    <span class="hor-label"><i class="fas fa-door-closed" style="color:#ef4444;font-size:11px;"></i> Cierre</span>
                    <span class="hor-value">
                        @if($restaurante->hora_cierre)
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $restaurante->hora_cierre)->format('h:i A') }}
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- ══ BOTONES DE CONTACTO ══ --}}
        <div style="display:flex;flex-direction:column;gap:10px;">

            {{-- Teléfono --}}
            @if(!empty($restaurante->telefono))
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $restaurante->telefono) }}"
                   class="btn-tel-cta">
                    <i class="fas fa-phone" style="font-size:16px;"></i>
                    {{ $restaurante->telefono }}
                </a>
            @endif

        </div>

    </aside>
</div>

@include('partials.footer')

{{-- ══ LIGHTBOX ══ --}}
<div id="lightbox" onclick="handleLbClick(event)">
    <button id="lb-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
    <button id="lb-prev" class="lb-btn" onclick="navLightbox(-1)"><i class="fas fa-chevron-left"></i></button>
    <button id="lb-next" class="lb-btn" onclick="navLightbox(1)"><i class="fas fa-chevron-right"></i></button>
    <img id="lightbox-img" src="" alt="">
    <div id="lb-counter"><span id="lb-text"></span><div id="lb-dots"></div></div>
</div>

<script>
    const galeriaImages = @json($todasLasImagenes);
    let curIdx = 0, isPortada = false;
    const lb = document.getElementById('lightbox');
    const lbImg = document.getElementById('lightbox-img');
    const lbText = document.getElementById('lb-text');
    const lbDots = document.getElementById('lb-dots');

    function openLightbox(src, index) {
        isPortada = (index === -1); curIdx = isPortada ? 0 : index;
        lbImg.src = src; lb.classList.add('active'); document.body.style.overflow = 'hidden'; updateLb();
    }
    function closeLightbox() {
        lb.classList.remove('active'); document.body.style.overflow = '';
        setTimeout(() => { lbImg.src = ''; }, 300);
    }
    function navLightbox(dir) {
        if (isPortada || galeriaImages.length === 0) return;
        curIdx = (curIdx + dir + galeriaImages.length) % galeriaImages.length;
        lbImg.style.opacity = '0'; lbImg.style.transform = 'scale(0.95)';
        setTimeout(() => { lbImg.src = galeriaImages[curIdx]; lbImg.style.opacity = '1'; lbImg.style.transform = 'scale(1)'; updateLb(); }, 160);
    }
    function updateLb() {
        const show = !isPortada && galeriaImages.length > 1;
        document.getElementById('lb-prev').style.display = show ? 'flex' : 'none';
        document.getElementById('lb-next').style.display = show ? 'flex' : 'none';
        if (!isPortada && galeriaImages.length > 0) {
            lbText.textContent = `${curIdx + 1} / ${galeriaImages.length}`;
            lbDots.innerHTML = galeriaImages.map((_,i) => `<span class="lb-dot ${i===curIdx?'on':''}" onclick="jumpLb(${i})"></span>`).join('');
        } else { lbText.textContent = 'Foto de portada'; lbDots.innerHTML = ''; }
    }
    function jumpLb(i) { curIdx = i; lbImg.src = galeriaImages[i]; updateLb(); }
    function handleLbClick(e) { if (e.target === lb) closeLightbox(); }
    document.addEventListener('keydown', e => {
        if (!lb.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') navLightbox(1);
        if (e.key === 'ArrowLeft') navLightbox(-1);
    });
    lbImg.style.transition = 'opacity 0.16s ease, transform 0.16s ease';
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    @if($restaurante->latitud && $restaurante->longitud)
    document.addEventListener('DOMContentLoaded', function() {
        const mapa = L.map('mapa-publico', { scrollWheelZoom: false })
            .setView([{{ $restaurante->latitud }}, {{ $restaurante->longitud }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>', maxZoom: 19
        }).addTo(mapa);
        const icono = L.divIcon({
            html: `<div style="width:20px;height:20px;background:#2563eb;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 10px rgba(0,0,0,0.3);"></div>`,
            iconSize: [20,20], iconAnchor: [10,20], className: ''
        });
        L.marker([{{ $restaurante->latitud }}, {{ $restaurante->longitud }}], { icon: icono })
            .addTo(mapa)
            .bindPopup(`<<strong style="font-size:13px;">{{ $restaurante->nombre }}</strong><br><span style="font-size:12px;color:#6b7280;">{{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}</span>`, { maxWidth: 200 })
            .openPopup();
    });
    @endif
</script>

</body>
</html>
