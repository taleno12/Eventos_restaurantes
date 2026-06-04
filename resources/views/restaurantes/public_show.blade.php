<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $restaurante->nombre }} | Gastro Nicaragua</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            :root {
                --orange:       #ea580c;
                --orange-light: #fed7aa;
                --orange-glow:  rgba(234,88,12,0.18);
                --dark:         #0c0a09;
                --stone:        #fafaf9;
                --border:       #e7e5e4;
                --text-muted:   #78716c;
                --text-main:    #1c1917;
            }

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Instrument Sans', sans-serif;
                background: var(--stone);
                color: var(--text-main);
                overflow-x: hidden;
            }

            .premium-title { font-family: 'Playfair Display', serif; }

            /* HERO DESKTOP: split-screen */
            .hero-wrap {
                display: grid;
                grid-template-columns: 1fr 1fr;
                min-height: auto;
                position: relative;
            }

            /* HERO MOVIL: columna unica */
            @media (max-width: 900px) {
                .hero-wrap {
                    display: flex;
                    flex-direction: column;
                }
                .hero-img-col  { order: 1; height: 52vw; min-height: 220px; max-height: 340px; }
                .hero-info-col {
                    order: 2;
                    min-height: 0 !important;
                    height: auto !important;
                }
                .nav-inner {
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    z-index: 20;
                    background: linear-gradient(to bottom, rgba(12,10,9,0.70) 0%, transparent 100%);
                    border-bottom: none;
                    padding: 14px 16px;
                }
                /* Nav desktop oculto sin ocupar espacio */
                .nav-desktop-only {
                    display: none !important;
                    height: 0 !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    border: none !important;
                    overflow: hidden !important;
                }
                /* hero-center no fuerza altura minima en movil */
                .hero-center {
                    flex: 0 0 auto !important;
                    justify-content: flex-start !important;
                    padding: 20px 16px !important;
                }
            }

            .hero-img-col {
                position: relative;
                overflow: hidden;
                height: 420px;
            }

            @media (min-width: 900px) {
                .hero-img-col { height: auto; min-height: 420px; }
            }

            .hero-bg {
                position: absolute;
                inset: 0;
                background-size: cover;
                background-position: center;
                transition: transform 9s ease;
            }
            .hero-img-col:hover .hero-bg { transform: scale(1.05); }

            .hero-img-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(12,10,9,0.35) 0%, transparent 60%);
            }

            .btn-zoom-portada {
                position: absolute;
                bottom: 20px; right: 20px; z-index: 10;
                background: rgba(255,255,255,0.14);
                border: 1px solid rgba(255,255,255,0.25);
                backdrop-filter: blur(14px);
                color: white;
                font-size: 12px; font-weight: 700;
                padding: 8px 16px; border-radius: 999px;
                cursor: pointer;
                display: flex; align-items: center; gap: 7px;
                transition: background 0.2s, transform 0.2s;
                letter-spacing: 0.04em;
            }
            .btn-zoom-portada:hover { background: rgba(234,88,12,0.75); transform: translateY(-2px); }

            .hero-info-col {
                background: var(--dark);
                display: flex;
                flex-direction: column;
                padding: 0;
                position: relative;
                overflow: hidden;
            }

            .hero-info-col::before {
                content: '';
                position: absolute; inset: 0;
                background-image:
                    radial-gradient(circle at 80% 20%, rgba(234,88,12,0.12) 0%, transparent 55%),
                    radial-gradient(circle at 10% 85%, rgba(234,88,12,0.07) 0%, transparent 45%);
                pointer-events: none;
            }

            .nav-inner {
                position: relative; z-index: 10;
                padding: 22px 40px;
                display: flex; align-items: center; justify-content: space-between;
                border-bottom: 1px solid rgba(255,255,255,0.06);
            }
            @media (max-width: 900px) { .nav-inner { padding: 14px 16px; } }

            .logo-link {
                display: flex; align-items: center; gap: 10px;
                text-decoration: none;
            }

            .logo-icon {
                width: 34px; height: 34px;
                background: var(--orange);
                border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 4px 16px rgba(234,88,12,0.4);
                flex-shrink: 0;
            }

            .nav-actions {
                display: flex; align-items: center; gap: 8px;
            }

            .btn-back {
                display: flex; align-items: center; gap: 7px;
                background: rgba(255,255,255,0.07);
                border: 1px solid rgba(255,255,255,0.12);
                color: rgba(255,255,255,0.75);
                font-size: 12px; font-weight: 700;
                padding: 7px 16px; border-radius: 999px;
                text-decoration: none;
                transition: all 0.2s;
                letter-spacing: 0.04em;
            }
            .btn-back:hover { background: rgba(234,88,12,0.2); color: white; border-color: rgba(234,88,12,0.4); }

            .btn-panel {
                display: inline-flex; align-items: center; gap: 7px;
                background: rgba(232,93,4,0.85);
                border: 1px solid rgba(232,93,4,0.6);
                color: white;
                font-size: 12px; font-weight: 700;
                padding: 7px 16px; border-radius: 999px;
                text-decoration: none;
                transition: all 0.2s;
                letter-spacing: 0.04em;
            }
            .btn-panel:hover { background: var(--orange); }

            .hero-center {
                position: relative; z-index: 10;
                padding: 32px 40px;
                flex: 1;
                display: flex; flex-direction: column; justify-content: center;
            }
            @media (max-width: 900px) { .hero-center { padding: 20px 16px; } }

            .hero-badge {
                display: inline-flex; align-items: center; gap: 6px;
                background: rgba(234,88,12,0.18);
                border: 1px solid rgba(234,88,12,0.35);
                color: #fb923c;
                font-size: 10px; font-weight: 800;
                letter-spacing: 0.14em; text-transform: uppercase;
                padding: 5px 12px; border-radius: 999px;
                margin-bottom: 16px; width: fit-content;
            }

            .hero-title {
                font-size: clamp(2rem, 3.5vw, 3.2rem);
                font-weight: 900; line-height: 1.08;
                color: white; margin-bottom: 14px;
            }

            .hero-location {
                display: flex; align-items: center; gap: 8px;
                color: rgba(255,255,255,0.55);
                font-size: 13px; font-weight: 600;
                margin-bottom: 28px;
            }
            .hero-location i { color: var(--orange); }
            .hero-location strong { color: rgba(255,255,255,0.85); }

            .hero-stats {
                display: grid; grid-template-columns: repeat(3, 1fr);
                gap: 1px;
                background: rgba(255,255,255,0.07);
                border: 1px solid rgba(255,255,255,0.07);
                border-radius: 16px; overflow: hidden;
            }

            .hero-stat {
                background: rgba(255,255,255,0.04);
                padding: 16px 18px; transition: background 0.2s;
            }
            @media (max-width: 900px) { .hero-stat { padding: 12px 14px; } }
            .hero-stat:hover { background: rgba(255,255,255,0.08); }

            .hero-stat-label {
                font-size: 9px; font-weight: 800;
                letter-spacing: 0.16em; text-transform: uppercase;
                color: rgba(255,255,255,0.3);
                display: block; margin-bottom: 5px;
            }
            .hero-stat-value { font-size: 14px; font-weight: 800; color: white; }

            .hero-foot {
                position: relative; z-index: 10;
                padding: 18px 40px;
                border-top: 1px solid rgba(255,255,255,0.06);
                display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
            }
            @media (max-width: 900px) { .hero-foot { padding: 14px 16px; } }

            /* MAIN LAYOUT */
            .main-wrap {
                max-width: 1240px;
                margin: 0 auto;
                padding: 48px 40px 80px;
                display: grid;
                grid-template-columns: 1fr 360px;
                gap: 28px;
                align-items: start;
            }
            @media (max-width: 1024px) {
                .main-wrap {
                    grid-template-columns: 1fr;
                    padding: 28px 16px 60px;
                }
            }

            .col-principal { display: flex; flex-direction: column; gap: 24px; }
            .col-sidebar   { display: flex; flex-direction: column; gap: 16px; }

            @media (max-width: 1024px) {
                .main-wrap {
                    display: flex;
                    flex-direction: column;
                    gap: 16px;
                }
                .sidebar-redes   { order: 1; }
                .sidebar-horario { order: 2; }
                .card-descripcion { order: 3; }
                .card-galeria { order: 4; }
                .card-mapa { order: 5; }
                .card-resenas { order: 6; }
                .sidebar-cta { order: 7; }
                .col-principal, .col-sidebar { display: contents; }
            }

            /* CARDS */
            .card {
                background: white;
                border-radius: 20px;
                border: 1px solid var(--border);
                box-shadow: 0 2px 16px rgba(0,0,0,0.04);
                overflow: hidden;
                transition: box-shadow 0.25s ease;
            }
            .card:hover { box-shadow: 0 6px 32px rgba(0,0,0,0.07); }

            .card-body { padding: 28px 32px; }
            @media (max-width: 600px) { .card-body { padding: 18px 16px; } }

            .section-label {
                font-size: 10px; font-weight: 800;
                letter-spacing: 0.18em; text-transform: uppercase;
                color: #a8a29e;
                display: flex; align-items: center; gap: 10px;
                margin-bottom: 20px;
            }
            .section-label i { color: var(--orange); font-size: 11px; }
            .section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

            .desc-text { color: #57534e; font-size: 15px; line-height: 1.8; }

            /* HORARIO */
            .dias-grid {
                display: flex; flex-wrap: wrap; gap: 6px;
                margin-bottom: 20px;
            }
            .dia-pill {
                font-size: 11px; font-weight: 800;
                padding: 5px 13px; border-radius: 999px;
                letter-spacing: 0.06em;
            }
            .dia-pill.activo {
                background: #fff7ed;
                color: #c2410c;
                border: 1px solid #fed7aa;
            }
            .dia-pill.inactivo {
                background: #f5f5f4;
                color: #c4bdb8;
                border: 1px solid #e7e5e4;
            }
            .horario-row {
                display: flex; align-items: center; justify-content: space-between;
                padding: 12px 16px;
                border-radius: 12px;
                background: #fafaf9;
                border: 1px solid var(--border);
            }
            .horario-label {
                display: flex; align-items: center; gap: 8px;
                font-size: 12px; font-weight: 700;
                color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em;
            }
            .horario-value {
                font-size: 15px; font-weight: 800; color: var(--text-main);
            }

            /* GALERIA */
            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                grid-auto-rows: 120px;
                gap: 8px;
            }
            @media (max-width: 700px) {
                .gallery-grid { grid-template-columns: repeat(3, 1fr); grid-auto-rows: 90px; }
            }
            @media (max-width: 420px) {
                .gallery-grid { grid-template-columns: repeat(2, 1fr); }
            }
            .gallery-grid .g-item:first-child { grid-column: span 2; grid-row: span 2; }

            .g-item {
                border-radius: 12px; overflow: hidden;
                cursor: zoom-in; position: relative;
                border: 1px solid var(--border);
            }
            .g-item img {
                width: 100%; height: 100%; object-fit: cover;
                transition: transform 0.5s ease; display: block;
            }
            .g-item:hover img { transform: scale(1.08); }
            .g-item::after {
                content: '\f00e';
                font-family: 'Font Awesome 6 Free'; font-weight: 900;
                position: absolute; inset: 0;
                display: flex; align-items: center; justify-content: center;
                font-size: 20px; color: white;
                background: rgba(12,10,9,0);
                transition: background 0.3s, opacity 0.3s; opacity: 0;
            }
            .g-item:hover::after { background: rgba(12,10,9,0.38); opacity: 1; }

            /* MAPA */
            #mapa-publico {
                height: 260px; border-radius: 14px;
                overflow: hidden; border: 1px solid var(--border);
            }

            .dir-box {
                display: flex; align-items: flex-start; gap: 10px;
                background: #fff7ed;
                border: 1px solid var(--orange-light);
                border-radius: 12px;
                padding: 12px 16px; margin-bottom: 14px;
                font-size: 13.5px; color: #57534e; line-height: 1.6;
            }
            .dir-box i { color: var(--orange); margin-top: 2px; flex-shrink: 0; }

            .btn-gmaps {
                display: inline-flex; align-items: center; gap: 8px;
                background: var(--dark); color: white;
                text-decoration: none;
                font-size: 13px; font-weight: 700;
                padding: 10px 20px; border-radius: 10px; margin-top: 14px;
                transition: background 0.2s, transform 0.2s; letter-spacing: 0.02em;
            }
            .btn-gmaps:hover { background: var(--orange); transform: translateY(-1px); }

            /* SIDEBAR REDES */
            .social-btn {
                display: flex; align-items: center; justify-content: center;
                width: 48px; height: 48px; border-radius: 14px;
                font-size: 18px; text-decoration: none;
                transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
            }
            .social-btn:hover { transform: translateY(-4px) scale(1.08); }
            .social-btn.wa { background: #dcfce7; color: #16a34a; }
            .social-btn.wa:hover { background: #22c55e; color: white; box-shadow: 0 8px 24px rgba(34,197,94,0.4); }
            .social-btn.ig { background: #fce7f3; color: #db2777; }
            .social-btn.ig:hover { background: linear-gradient(135deg,#f59e0b,#ec4899,#8b5cf6); color: white; box-shadow: 0 8px 24px rgba(236,72,153,0.4); }
            .social-btn.tt { background: #f1f5f9; color: #0f172a; }
            .social-btn.tt:hover { background: #0f172a; color: white; box-shadow: 0 8px 24px rgba(15,23,42,0.3); }
            .social-btn.fb { background: #dbeafe; color: #2563eb; }
            .social-btn.fb:hover { background: #2563eb; color: white; box-shadow: 0 8px 24px rgba(37,99,235,0.4); }

            .cta-btn {
                display: flex; align-items: center; justify-content: center; gap: 9px;
                width: 100%; background: var(--dark); color: white;
                font-weight: 700; font-size: 14px;
                padding: 15px 20px; border-radius: 14px;
                text-decoration: none;
                transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
                letter-spacing: 0.03em;
            }
            .cta-btn:hover {
                background: var(--orange); transform: translateY(-2px);
                box-shadow: 0 12px 32px rgba(234,88,12,0.35);
            }

            /* LIGHTBOX */
            #lightbox {
                position: fixed; inset: 0; z-index: 9999;
                background: rgba(12,10,9,0.96);
                display: flex; align-items: center; justify-content: center;
                opacity: 0; pointer-events: none;
                transition: opacity 0.3s ease; padding: 20px;
            }
            #lightbox.active { opacity: 1; pointer-events: all; }

            #lightbox-img {
                max-width: min(90vw, 960px); max-height: 85vh;
                border-radius: 18px; object-fit: contain;
                box-shadow: 0 32px 80px rgba(0,0,0,0.6);
                transform: scale(0.92);
                transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
                user-select: none;
            }
            #lightbox.active #lightbox-img { transform: scale(1); }

            .lb-nav-btn {
                position: fixed; top: 50%; transform: translateY(-50%);
                width: 48px; height: 48px; border-radius: 50%;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.15);
                backdrop-filter: blur(8px);
                display: flex; align-items: center; justify-content: center;
                color: white; font-size: 16px; cursor: pointer;
                transition: background 0.2s, transform 0.2s; z-index: 10000;
            }
            .lb-nav-btn:hover { background: rgba(234,88,12,0.7); transform: translateY(-50%) scale(1.08); }
            #lb-prev { left: 16px; }
            #lb-next { right: 16px; }

            #lb-close {
                position: fixed; top: 16px; right: 16px;
                width: 40px; height: 40px; border-radius: 50%;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(8px);
                display: flex; align-items: center; justify-content: center;
                color: white; font-size: 14px; cursor: pointer;
                transition: background 0.2s; z-index: 10000;
            }
            #lb-close:hover { background: rgba(239,68,68,0.7); }

            #lb-counter {
                position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
                color: rgba(255,255,255,0.5); font-size: 13px; font-weight: 600;
                letter-spacing: 0.08em; z-index: 10000;
                display: flex; gap: 8px; align-items: center;
            }

            #lb-dots { display: flex; gap: 6px; align-items: center; }
            .lb-dot {
                width: 6px; height: 6px; border-radius: 50%;
                background: rgba(255,255,255,0.25);
                transition: background 0.2s, transform 0.2s; cursor: pointer;
            }
            .lb-dot.active { background: #ea580c; transform: scale(1.35); }

            /* ANIMACIONES */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .fu  { animation: fadeUp 0.6s ease both; }
            .d1  { animation-delay: 0.08s; }
            .d2  { animation-delay: 0.16s; }
            .d3  { animation-delay: 0.24s; }
            .d4  { animation-delay: 0.32s; }
        </style>
    </head>
    <body>

        {{-- HERO SPLIT-SCREEN --}}
        <section class="hero-wrap">

            {{-- Columna imagen --}}
            <div class="hero-img-col">
                @php
                    $bgUrl = '';
                    if($restaurante->foto_portada)
                        $bgUrl = asset('storage/' . $restaurante->foto_portada);
                    elseif($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                        $bgUrl = asset('storage/' . $restaurante->imagenes->first()->ruta_foto);
                    else
                        $bgUrl = 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1600&q=80';
                @endphp
                <div class="hero-bg" style="background-image:url('{{ $bgUrl }}');"></div>
                <div class="hero-img-overlay"></div>

                {{-- Nav superpuesto solo visible en movil --}}
                <nav class="nav-inner nav-mobile-overlay">
                    <a href="{{ route('home') }}" class="logo-link">
                        <div class="logo-icon">
                            <i class="fas fa-utensils" style="color:white;font-size:12px;"></i>
                        </div>
                        <span class="premium-title" style="font-size:19px;font-weight:700;color:white;font-style:italic;">
                            Gastro<span style="color:#fb923c;">Nicaragua</span>
                        </span>
                    </a>
                    <div class="nav-actions">
                        <a href="{{ route('restaurantes.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left" style="font-size:10px;"></i> Volver
                        </a>
                        @auth
                            @if(auth()->user()->restaurante && auth()->user()->restaurante->id === $restaurante->id)
                                <a href="{{ route('restaurante.dashboard') }}" class="btn-panel">
                                    <i class="fas fa-chart-pie" style="font-size:10px;"></i> Mi Panel
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>

                @if($restaurante->foto_portada)
                    <button class="btn-zoom-portada"
                            onclick="openLightbox('{{ asset('storage/' . $restaurante->foto_portada) }}', -1)">
                        <i class="fas fa-expand-alt" style="font-size:10px;"></i> Ver portada
                    </button>
                @endif
            </div>

            {{-- Columna info --}}
            <div class="hero-info-col">

                {{-- Nav desktop (oculto en movil sin ocupar espacio) --}}
                <nav class="nav-inner nav-desktop-only">
                    <a href="{{ route('home') }}" class="logo-link">
                        <div class="logo-icon">
                            <i class="fas fa-utensils" style="color:white;font-size:12px;"></i>
                        </div>
                        <span class="premium-title" style="font-size:19px;font-weight:700;color:white;font-style:italic;">
                            Gastro<span style="color:#fb923c;">Nicaragua</span>
                        </span>
                    </a>

                    <div class="nav-actions">
                        <a href="{{ route('restaurantes.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left" style="font-size:10px;"></i> Volver
                        </a>
                        @auth
                            @if(auth()->user()->restaurante && auth()->user()->restaurante->id === $restaurante->id)
                                <a href="{{ route('restaurante.dashboard') }}" class="btn-panel">
                                    <i class="fas fa-chart-pie" style="font-size:10px;"></i> Mi Panel
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>

                {{-- Contenido central --}}
                <div class="hero-center">

                    <div class="hero-badge fu">
                        <i class="fas fa-utensils" style="font-size:9px;"></i>
                        Establecimiento
                        @if($restaurante->especialidad)
                            &nbsp;·&nbsp; {{ $restaurante->especialidad }}
                        @endif
                    </div>

                    <h1 class="premium-title hero-title fu d1">
                        {{ $restaurante->nombre }}
                    </h1>

                    <div class="hero-location fu d2">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>{{ $restaurante->departamento->nombre }}</strong>
                        @if($restaurante->municipio)
                            <span style="opacity:.45;">·</span>
                            <span>{{ $restaurante->municipio->nombre }}</span>
                        @endif
                    </div>

                    @php
                        $totalReviews = $restaurante->reviews()->count();
                        $avgRating    = $totalReviews > 0 ? round($restaurante->reviews()->avg('rating'),1) : null;
                    @endphp
                    <div class="hero-stats fu d3">
                        <div class="hero-stat">
                            <span class="hero-stat-label">Especialidad</span>
                            <span class="hero-stat-value" style="color:#fb923c;font-size:12px;">{{ $restaurante->especialidad ?? 'General' }}</span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-label">Calificacion</span>
                            <span class="hero-stat-value">
                                @if($avgRating)
                                    ★ {{ $avgRating }}<span style="font-size:11px;color:rgba(255,255,255,0.35);font-weight:500;"> /5</span>
                                @else
                                    <span style="font-size:11px;color:rgba(255,255,255,0.35);font-weight:500;">Sin resenas</span>
                                @endif
                            </span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-label">Municipio</span>
                            <span class="hero-stat-value" style="font-size:12px;">{{ $restaurante->municipio->nombre }}</span>
                        </div>
                    </div>
                </div>

                {{-- Pie del hero --}}
                <div class="hero-foot fu d4">
                    @php
                        $diasActivos = $restaurante->dias_laborales ?? [];
                        if (is_string($diasActivos)) $diasActivos = json_decode($diasActivos, true) ?? [];

                        $diasMap = [
                            'lunes'=>1,'martes'=>2,'miercoles'=>3,
                            'jueves'=>4,'viernes'=>5,'sabado'=>6,'domingo'=>0,
                        ];
                        $tieneHorario  = !empty($restaurante->hora_apertura) && !empty($restaurante->hora_cierre);
                        $estaAbiertoHero = false;
                        $diaHoyNum     = (int) now()->setTimezone('America/Managua')->format('w');
                        $horaActual    = now()->setTimezone('America/Managua')->format('H:i');

                        if ($tieneHorario) {
                            $hoyEsLaboral = empty($diasActivos)
                                ? true
                                : collect($diasActivos)->contains(fn($d) => ($diasMap[$d] ?? -1) === $diaHoyNum);
                            if ($hoyEsLaboral) {
                                $ap = substr($restaurante->hora_apertura, 0, 5);
                                $ci = substr($restaurante->hora_cierre,   0, 5);
                                $estaAbiertoHero = $ci > $ap
                                    ? ($horaActual >= $ap && $horaActual < $ci)
                                    : ($horaActual >= $ap || $horaActual < $ci);
                            }
                        }
                    @endphp

                    @if($tieneHorario)
                        @if($estaAbiertoHero)
                            <span style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-size:11px;font-weight:800;padding:6px 14px;border-radius:999px;display:inline-flex;align-items:center;gap:6px;">
                                <span style="width:6px;height:6px;background:#22c55e;border-radius:50%;display:inline-block;animation:pulseDot 2s infinite;"></span>
                                Abierto ahora
                            </span>
                        @else
                            <span style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;font-size:11px;font-weight:800;padding:6px 14px;border-radius:999px;display:inline-flex;align-items:center;gap:6px;">
                                <span style="width:6px;height:6px;background:#ef4444;border-radius:50%;display:inline-block;"></span>
                                Cerrado ahora
                            </span>
                        @endif
                    @else
                        <span style="background:#f8fafc;color:#94a3b8;border:1px solid #e2e8f0;font-size:11px;font-weight:700;padding:6px 14px;border-radius:999px;display:inline-flex;align-items:center;gap:6px;">
                            <i class="fas fa-clock" style="font-size:9px;"></i> Horario no configurado
                        </span>
                    @endif
                </div>

            </div>
        </section>

        <style>
            @keyframes pulseDot {
                0%,100% { box-shadow: 0 0 0 2px rgba(34,197,94,0.25); }
                50%      { box-shadow: 0 0 0 4px rgba(34,197,94,0.12); }
            }

            /* Nav mobile overlay: solo visible en movil */
            .nav-mobile-overlay {
                display: none;
            }
            @media (max-width: 900px) {
                .nav-mobile-overlay {
                    display: flex;
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    z-index: 20;
                    background: linear-gradient(to bottom, rgba(12,10,9,0.70) 0%, transparent 100%);
                    border-bottom: none;
                    padding: 14px 16px;
                }
                .nav-desktop-only {
                    display: none !important;
                    height: 0 !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    border: none !important;
                    overflow: hidden !important;
                }
            }
        </style>

        {{-- MAIN CONTENT --}}
        @php
            $todasLasImagenes = [];
            if($restaurante->imagenes) {
                foreach($restaurante->imagenes as $foto) {
                    $todasLasImagenes[] = asset('storage/' . $foto->ruta_foto);
                }
            }
        @endphp

        <main class="main-wrap">

            {{-- COLUMNA PRINCIPAL --}}
            <div class="col-principal">

                {{-- Descripcion --}}
                <div class="card card-descripcion">
                    <div class="card-body">
                        <div class="section-label">
                            <i class="fas fa-align-left"></i> Sobre el restaurante
                        </div>
                        <p class="desc-text">
                            Bienvenidos a <strong style="color:var(--text-main);">{{ $restaurante->nombre }}</strong>.
                            @if($restaurante->descripcion)
                                {{ $restaurante->descripcion }}
                            @else
                                Somos especialistas en ofrecer la mejor experiencia culinaria en la hermosa localidad
                                de {{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}.
                                ¡Visitanos y disfruta de una sazon inigualable!
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Galeria --}}
                @if($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                    <div class="card card-galeria">
                        <div class="card-body">
                            <div class="section-label">
                                <i class="fas fa-images"></i>
                                Galeria de fotos
                                <span style="font-size:10px;color:#d6d3d1;font-weight:500;text-transform:none;letter-spacing:0;">— clic para ampliar</span>
                            </div>
                            <div class="gallery-grid">
                                @foreach($restaurante->imagenes as $index => $foto)
                                    <div class="g-item" onclick="openLightbox('{{ asset('storage/' . $foto->ruta_foto) }}', {{ $index }})">
                                        <img src="{{ asset('storage/' . $foto->ruta_foto) }}"
                                             alt="Foto {{ $index + 1 }} de {{ $restaurante->nombre }}"
                                             loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Mapa --}}
                @if($restaurante->latitud && $restaurante->longitud)
                    <div class="card card-mapa">
                        <div class="card-body">
                            <div class="section-label">
                                <i class="fas fa-map-marker-alt"></i> Como llegar
                            </div>

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

                {{-- Resenas --}}
                <div class="card card-resenas" id="resenas">
                    <div class="card-body">
                        <div class="section-label">
                            <i class="fas fa-star"></i> Resenas
                            <span style="font-size:10px;color:#d6d3d1;font-weight:500;text-transform:none;letter-spacing:0;">
                                — {{ $totalReviews }} {{ $totalReviews === 1 ? 'resena' : 'resenas' }}
                            </span>
                        </div>

                        @if(session('success'))
                            <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div style="background:#fee2e2;border:1px solid #fecaca;color:#dc2626;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        @if($totalReviews > 0)
                            <div style="display:flex;align-items:center;gap:20px;background:#fff7ed;border:1px solid #fed7aa;border-radius:16px;padding:20px 24px;margin-bottom:24px;">
                                <div style="text-align:center;min-width:64px;">
                                    <div style="font-size:3rem;font-weight:900;color:#ea580c;line-height:1;">{{ $avgRating }}</div>
                                    <div style="font-size:11px;color:#a8a29e;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-top:4px;">de 5</div>
                                </div>
                                <div style="flex:1;">
                                    @for($s = 5; $s >= 1; $s--)
                                        @php $cnt = $restaurante->reviews()->where('rating',$s)->count(); @endphp
                                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                            <span style="font-size:11px;font-weight:700;color:#78716c;min-width:12px;">{{ $s }}</span>
                                            <i class="fas fa-star" style="font-size:10px;color:#f59e0b;"></i>
                                            <div style="flex:1;height:6px;background:#e7e5e4;border-radius:999px;overflow:hidden;">
                                                <div style="height:100%;background:#ea580c;border-radius:999px;width:{{ $totalReviews > 0 ? round($cnt/$totalReviews*100) : 0 }}%;transition:width 0.6s ease;"></div>
                                            </div>
                                            <span style="font-size:11px;color:#a8a29e;font-weight:600;min-width:20px;">{{ $cnt }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @endif

                        @auth
                            @php $miResena = $restaurante->reviews()->where('user_id', auth()->id())->first(); @endphp
                            @if(!$miResena)
                                <div style="background:#f9fafb;border:1px solid #e7e5e4;border-radius:16px;padding:24px;margin-bottom:28px;">
                                    <p style="font-size:13px;font-weight:800;color:#1c1917;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:16px;">
                                        <i class="fas fa-pencil-alt" style="color:#ea580c;margin-right:6px;"></i>
                                        Deja tu resena
                                    </p>
                                    <form action="{{ route('reviews.store', $restaurante) }}" method="POST">
                                        @csrf
                                        <div style="margin-bottom:16px;">
                                            <label style="font-size:11px;font-weight:700;color:#78716c;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:8px;">Calificacion</label>
                                            <div style="display:flex;gap:6px;" id="star-selector">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" onclick="setRating({{ $i }})" data-star="{{ $i }}"
                                                            style="font-size:28px;background:none;border:none;cursor:pointer;color:#d6d3d1;transition:color 0.15s,transform 0.15s;padding:0 2px;"
                                                            onmouseover="hoverRating({{ $i }})" onmouseout="resetHover()">★</button>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="rating-input" value="">
                                            @error('rating')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                                        </div>
                                        <div style="margin-bottom:12px;">
                                            <label style="font-size:11px;font-weight:700;color:#78716c;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:6px;">
                                                Titulo <span style="color:#d6d3d1;font-weight:400;">(opcional)</span>
                                            </label>
                                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Ej: Excelente experiencia" maxlength="100"
                                                   style="width:100%;padding:10px 14px;border:1px solid #e7e5e4;border-radius:10px;font-size:14px;outline:none;font-family:inherit;background:white;transition:border-color 0.2s;"
                                                   onfocus="this.style.borderColor='#ea580c'" onblur="this.style.borderColor='#e7e5e4'">
                                            @error('title')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                                        </div>
                                        <div style="margin-bottom:16px;">
                                            <label style="font-size:11px;font-weight:700;color:#78716c;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:6px;">
                                                Comentario <span style="color:#d6d3d1;font-weight:400;">(opcional)</span>
                                            </label>
                                            <textarea name="body" rows="3" placeholder="Cuentanos tu experiencia..." maxlength="1000"
                                                      style="width:100%;padding:10px 14px;border:1px solid #e7e5e4;border-radius:10px;font-size:14px;outline:none;font-family:inherit;background:white;resize:vertical;transition:border-color 0.2s;"
                                                      onfocus="this.style.borderColor='#ea580c'" onblur="this.style.borderColor='#e7e5e4'">{{ old('body') }}</textarea>
                                            @error('body')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                                        </div>
                                        <button type="submit"
                                                style="display:inline-flex;align-items:center;gap:8px;background:#ea580c;color:white;border:none;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:background 0.2s,transform 0.2s;"
                                                onmouseover="this.style.background='#c2410c';this.style.transform='translateY(-1px)'"
                                                onmouseout="this.style.background='#ea580c';this.style.transform='none'">
                                            <i class="fas fa-paper-plane" style="font-size:12px;"></i> Publicar resena
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:16px;padding:20px 24px;margin-bottom:28px;text-align:center;">
                                <i class="fas fa-lock" style="color:#ea580c;font-size:20px;margin-bottom:8px;display:block;"></i>
                                <p style="font-size:14px;color:#57534e;font-weight:600;margin-bottom:12px;">Inicia sesion para dejar tu resena</p>
                                <a href="{{ route('login') }}"
                                   style="display:inline-flex;align-items:center;gap:7px;background:#ea580c;color:white;text-decoration:none;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:700;">
                                    <i class="fas fa-sign-in-alt" style="font-size:11px;"></i> Iniciar sesion
                                </a>
                            </div>
                        @endauth

                        @php $reviews = $restaurante->reviews()->with('user')->latest()->get(); @endphp

                        @forelse($reviews as $review)
                            <div style="border-bottom:1px solid #f5f5f4;padding-bottom:20px;margin-bottom:20px;">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">
                                    <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                                        <div style="width:38px;height:38px;border-radius:50%;overflow:hidden;flex-shrink:0;background:#fed7aa;display:flex;align-items:center;justify-content:center;">
                                            @if($review->user->avatar)
                                                <img src="{{ $review->user->avatar }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <span style="font-size:15px;font-weight:800;color:#ea580c;">{{ strtoupper(substr($review->user->name,0,1)) }}</span>
                                            @endif
                                        </div>
                                        <div style="min-width:0;">
                                            <p style="font-size:14px;font-weight:700;color:#1c1917;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $review->user->name }}</p>
                                            <p style="font-size:11px;color:#a8a29e;margin:0;">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div style="display:flex;gap:2px;flex-shrink:0;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span style="color:{{ $i <= $review->rating ? '#f59e0b' : '#e7e5e4' }};font-size:14px;">★</span>
                                        @endfor
                                    </div>
                                </div>

                                @if($review->title)
                                    <p style="font-size:14px;font-weight:700;color:#1c1917;margin-bottom:4px;">{{ $review->title }}</p>
                                @endif
                                @if($review->body)
                                    <p style="font-size:14px;color:#57534e;line-height:1.7;margin:0;">{{ $review->body }}</p>
                                @endif

                                @auth
                                    @if(auth()->id() === $review->user_id || auth()->user()->email === 'admin@turismo.ni')
                                        <div style="display:flex;gap:8px;margin-top:10px;">
                                            <button onclick="toggleEdit({{ $review->id }})"
                                                    style="font-size:12px;font-weight:600;color:#78716c;background:none;border:1px solid #e7e5e4;padding:5px 12px;border-radius:8px;cursor:pointer;font-family:inherit;transition:all 0.2s;"
                                                    onmouseover="this.style.borderColor='#ea580c';this.style.color='#ea580c'"
                                                    onmouseout="this.style.borderColor='#e7e5e4';this.style.color='#78716c'">
                                                <i class="fas fa-pencil-alt" style="font-size:10px;"></i> Editar
                                            </button>
                                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Eliminar esta resena?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        style="font-size:12px;font-weight:600;color:#78716c;background:none;border:1px solid #e7e5e4;padding:5px 12px;border-radius:8px;cursor:pointer;font-family:inherit;transition:all 0.2s;"
                                                        onmouseover="this.style.borderColor='#dc2626';this.style.color='#dc2626'"
                                                        onmouseout="this.style.borderColor='#e7e5e4';this.style.color='#78716c'">
                                                    <i class="fas fa-trash" style="font-size:10px;"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>

                                        <div id="edit-form-{{ $review->id }}" style="display:none;margin-top:14px;background:#f9fafb;border:1px solid #e7e5e4;border-radius:12px;padding:16px;">
                                            <form action="{{ route('reviews.update', $review) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div style="margin-bottom:12px;">
                                                    <label style="font-size:11px;font-weight:700;color:#78716c;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:6px;">Calificacion</label>
                                                    <div style="display:flex;gap:4px;" id="edit-stars-{{ $review->id }}">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <button type="button" onclick="setEditRating({{ $review->id }},{{ $i }})" data-star="{{ $i }}"
                                                                    style="font-size:24px;background:none;border:none;cursor:pointer;padding:0 2px;color:{{ $i <= $review->rating ? '#f59e0b' : '#d6d3d1' }};transition:color 0.15s;">★</button>
                                                        @endfor
                                                    </div>
                                                    <input type="hidden" name="rating" id="edit-rating-{{ $review->id }}" value="{{ $review->rating }}">
                                                </div>
                                                <input type="text" name="title" value="{{ $review->title }}" placeholder="Titulo" maxlength="100"
                                                       style="width:100%;padding:9px 12px;border:1px solid #e7e5e4;border-radius:9px;font-size:13px;font-family:inherit;margin-bottom:10px;outline:none;"
                                                       onfocus="this.style.borderColor='#ea580c'" onblur="this.style.borderColor='#e7e5e4'">
                                                <textarea name="body" rows="3" maxlength="1000"
                                                          style="width:100%;padding:9px 12px;border:1px solid #e7e5e4;border-radius:9px;font-size:13px;font-family:inherit;resize:vertical;outline:none;margin-bottom:12px;"
                                                          onfocus="this.style.borderColor='#ea580c'" onblur="this.style.borderColor='#e7e5e4'">{{ $review->body }}</textarea>
                                                <button type="submit" style="background:#ea580c;color:white;border:none;padding:9px 20px;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;">
                                                    Guardar cambios
                                                </button>
                                                <button type="button" onclick="toggleEdit({{ $review->id }})"
                                                        style="background:none;border:1px solid #e7e5e4;color:#78716c;padding:9px 16px;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;margin-left:6px;">
                                                    Cancelar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <div style="text-align:center;padding:40px 20px;">
                                <i class="far fa-comment-dots" style="font-size:36px;color:#d6d3d1;display:block;margin-bottom:12px;"></i>
                                <p style="color:#a8a29e;font-size:14px;font-weight:600;">Aun no hay resenas. Se el primero!</p>
                            </div>
                        @endforelse

                    </div>
                </div>

                <script>
                    let selectedRating = 0;
                    function setRating(val) {
                        selectedRating = val;
                        document.getElementById('rating-input').value = val;
                        updateStars('star-selector', val, '#f59e0b');
                    }
                    function hoverRating(val) { updateStars('star-selector', val, '#fb923c'); }
                    function resetHover() { updateStars('star-selector', selectedRating, '#f59e0b'); }
                    function updateStars(containerId, val, activeColor) {
                        document.querySelectorAll(`#${containerId} button`).forEach(btn => {
                            btn.style.color = parseInt(btn.dataset.star) <= val ? activeColor : '#d6d3d1';
                        });
                    }
                    function toggleEdit(id) {
                        const el = document.getElementById(`edit-form-${id}`);
                        el.style.display = el.style.display === 'none' ? 'block' : 'none';
                    }
                    function setEditRating(reviewId, val) {
                        document.getElementById(`edit-rating-${reviewId}`).value = val;
                        document.querySelectorAll(`#edit-stars-${reviewId} button`).forEach(btn => {
                            btn.style.color = parseInt(btn.dataset.star) <= val ? '#f59e0b' : '#d6d3d1';
                        });
                    }
                </script>

            </div>{{-- /col-principal --}}


            {{-- SIDEBAR --}}
            <aside class="col-sidebar">

                {{-- Redes sociales --}}
                @if(!empty($restaurante->whatsapp) || !empty($restaurante->instagram) || !empty($restaurante->tiktok) || !empty($restaurante->facebook))
                    <div class="card sidebar-redes">
                        <div class="card-body">
                            <div class="section-label">
                                <i class="fas fa-share-alt"></i> Redes sociales
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                                @if(!empty($restaurante->whatsapp))
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$restaurante->whatsapp) }}" target="_blank" class="social-btn wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                @endif
                                @if(!empty($restaurante->instagram))
                                    <a href="{{ $restaurante->instagram }}" target="_blank" class="social-btn ig" title="Instagram"><i class="fab fa-instagram"></i></a>
                                @endif
                                @if(!empty($restaurante->tiktok))
                                    <a href="{{ $restaurante->tiktok }}" target="_blank" class="social-btn tt" title="TikTok"><i class="fab fa-tiktok"></i></a>
                                @endif
                                @if(!empty($restaurante->facebook))
                                    <a href="{{ $restaurante->facebook }}" target="_blank" class="social-btn fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Horario --}}
                <div class="card sidebar-horario">
                    <div class="card-body">
                        <div class="section-label">
                            <i class="far fa-clock"></i> Horario de atencion
                        </div>

                        @php
                            $diasConfig = [
                                'lunes'     => 'Lun',
                                'martes'    => 'Mar',
                                'miercoles' => 'Mie',
                                'jueves'    => 'Jue',
                                'viernes'   => 'Vie',
                                'sabado'    => 'Sab',
                                'domingo'   => 'Dom',
                            ];
                        @endphp
                        <div class="dias-grid">
                            @foreach($diasConfig as $valor => $etiqueta)
                                <span class="dia-pill {{ in_array($valor, $diasActivos) ? 'activo' : 'inactivo' }}">
                                    {{ $etiqueta }}
                                </span>
                            @endforeach
                        </div>

                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <div class="horario-row">
                                <span class="horario-label">
                                    <i class="fas fa-door-open" style="color:#22c55e;"></i> Apertura
                                </span>
                                <span class="horario-value">
                                    @if($restaurante->hora_apertura)
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $restaurante->hora_apertura)->format('h:i A') }}
                                    @else
                                        <span style="color:#c4bdb8;font-size:13px;">—</span>
                                    @endif
                                </span>
                            </div>
                            <div class="horario-row">
                                <span class="horario-label">
                                    <i class="fas fa-door-closed" style="color:#ef4444;"></i> Cierre
                                </span>
                                <span class="horario-value">
                                    @if($restaurante->hora_cierre)
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $restaurante->hora_cierre)->format('h:i A') }}
                                    @else
                                        <span style="color:#c4bdb8;font-size:13px;">—</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CTA WhatsApp --}}
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurante->whatsapp ?? '') }}"
                   target="_blank"
                   class="cta-btn sidebar-cta"
                   style="{{ empty($restaurante->whatsapp) ? 'pointer-events:none;opacity:0.5;' : '' }}">
                    <i class="fab fa-whatsapp" style="font-size:16px;"></i>
                    Contactar por WhatsApp
                </a>

            </aside>

        </main>

        {{-- Footer --}}
        <footer style="background:#0c0a09;color:white;padding:40px 2rem;text-align:center;border-top:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px;">
                <div style="width:30px;height:30px;background:#ea580c;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-utensils" style="color:white;font-size:11px;"></i>
                </div>
                <span class="premium-title" style="color:white;font-size:17px;font-style:italic;">Gastro<span style="color:#fb923c;">Nicaragua</span></span>
            </div>
            <p style="color:#57534e;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;margin:0;">© 2026 — Experiencias Culinarias de Nicaragua</p>
        </footer>

        {{-- LIGHTBOX --}}
        <div id="lightbox" onclick="handleLightboxClick(event)">
            <button id="lb-close"  onclick="closeLightbox()"><i class="fas fa-times"></i></button>
            <button id="lb-prev"   class="lb-nav-btn" onclick="navigateLightbox(-1)"><i class="fas fa-chevron-left"></i></button>
            <button id="lb-next"   class="lb-nav-btn" onclick="navigateLightbox(1)"><i class="fas fa-chevron-right"></i></button>
            <img id="lightbox-img" src="" alt="Foto ampliada">
            <div id="lb-counter">
                <span id="lb-text"></span>
                <div id="lb-dots"></div>
            </div>
        </div>

        <script>
            const galeriaImages = @json($todasLasImagenes);
            let currentIndex = 0, isPortada = false;

            const lb    = document.getElementById('lightbox');
            const lbImg = document.getElementById('lightbox-img');
            const lbText= document.getElementById('lb-text');
            const lbDots= document.getElementById('lb-dots');
            const lbPrev= document.getElementById('lb-prev');
            const lbNext= document.getElementById('lb-next');

            function openLightbox(src, index) {
                isPortada    = (index === -1);
                currentIndex = isPortada ? 0 : index;
                lbImg.src = src;
                lb.classList.add('active');
                document.body.style.overflow = 'hidden';
                updateControls();
            }
            function closeLightbox() {
                lb.classList.remove('active');
                document.body.style.overflow = '';
                setTimeout(() => { lbImg.src = ''; }, 300);
            }
            function navigateLightbox(dir) {
                if (isPortada || galeriaImages.length === 0) return;
                currentIndex = (currentIndex + dir + galeriaImages.length) % galeriaImages.length;
                lbImg.style.opacity = '0'; lbImg.style.transform = 'scale(0.94)';
                setTimeout(() => {
                    lbImg.src = galeriaImages[currentIndex];
                    lbImg.style.opacity = '1'; lbImg.style.transform = 'scale(1)';
                    updateControls();
                }, 180);
            }
            function updateControls() {
                const showNav = !isPortada && galeriaImages.length > 1;
                lbPrev.style.display = showNav ? 'flex' : 'none';
                lbNext.style.display = showNav ? 'flex' : 'none';
                if (!isPortada && galeriaImages.length > 0) {
                    lbText.textContent = `${currentIndex + 1} / ${galeriaImages.length}`;
                    lbDots.innerHTML = galeriaImages.map((_,i) =>
                        `<span class="lb-dot ${i === currentIndex ? 'active' : ''}" onclick="jumpTo(${i})"></span>`
                    ).join('');
                } else {
                    lbText.textContent = 'Foto de portada';
                    lbDots.innerHTML = '';
                }
            }
            function jumpTo(i) { currentIndex = i; lbImg.src = galeriaImages[i]; updateControls(); }
            function handleLightboxClick(e) { if (e.target === lb) closeLightbox(); }
            document.addEventListener('keydown', e => {
                if (!lb.classList.contains('active')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') navigateLightbox(1);
                if (e.key === 'ArrowLeft') navigateLightbox(-1);
            });
            lbImg.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
        </script>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            @if($restaurante->latitud && $restaurante->longitud)
            document.addEventListener('DOMContentLoaded', function() {
                const mapa = L.map('mapa-publico', { scrollWheelZoom: false })
                              .setView([{{ $restaurante->latitud }}, {{ $restaurante->longitud }}], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19
                }).addTo(mapa);
                const icono = L.divIcon({
                    html: `<div style="width:22px;height:22px;background:#ea580c;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 12px rgba(0,0,0,0.4);"></div>`,
                    iconSize: [22,22], iconAnchor: [11,22], className: ''
                });
                L.marker([{{ $restaurante->latitud }}, {{ $restaurante->longitud }}], { icon: icono })
                    .addTo(mapa)
                    .bindPopup(`
                        <strong style="font-size:13px;color:#1c1917;">{{ $restaurante->nombre }}</strong><br>
                        <span style="font-size:12px;color:#78716c;">{{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}</span>
                    `, { maxWidth: 200 })
                    .openPopup();
            });
            @endif
        </script>

    </body>
</html>
