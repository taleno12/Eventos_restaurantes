{{-- resources/views/gastrobares/public_show.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $gastrobar->nombre }} | Gastro Nicaragua</title>

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

            /* ─────────────────────────────────────────────────────
               HERO SPLIT-SCREEN
            ───────────────────────────────────────────────────── */
            .hero-wrap {
                display: grid;
                grid-template-columns: 1fr 1fr;
                min-height: auto;
                position: relative;
            }

            @media (max-width: 900px) {
                .hero-wrap { grid-template-columns: 1fr; }
                .hero-img-col { height: 52vw; min-height: 220px; max-height: 380px; }
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
                color: white; font-size: 12px; font-weight: 700;
                padding: 8px 16px; border-radius: 999px;
                cursor: pointer;
                display: flex; align-items: center; gap: 7px;
                transition: background 0.2s, transform 0.2s;
                letter-spacing: 0.04em;
            }
            .btn-zoom-portada:hover { background: rgba(234,88,12,0.75); transform: translateY(-2px); }

            /* Columna info */
            .hero-info-col {
                background: var(--dark);
                display: flex; flex-direction: column;
                padding: 0; position: relative; overflow: hidden;
            }

            .hero-info-col::before {
                content: '';
                position: absolute; inset: 0;
                background-image:
                    radial-gradient(circle at 80% 20%, rgba(234,88,12,0.12) 0%, transparent 55%),
                    radial-gradient(circle at 10% 85%, rgba(234,88,12,0.07) 0%, transparent 45%);
                pointer-events: none;
            }

            /* NAV */
            .nav-inner {
                position: relative; z-index: 10;
                padding: 22px 40px;
                display: flex; align-items: center; justify-content: space-between;
                border-bottom: 1px solid rgba(255,255,255,0.06);
            }
            @media (max-width: 900px) { .nav-inner { padding: 18px 20px; } }

            .logo-link { display: flex; align-items: center; gap: 10px; text-decoration: none; }

            .logo-icon {
                width: 34px; height: 34px;
                background: var(--orange); border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 4px 16px rgba(234,88,12,0.4); flex-shrink: 0;
            }

            .btn-back {
                display: flex; align-items: center; gap: 7px;
                background: rgba(255,255,255,0.07);
                border: 1px solid rgba(255,255,255,0.12);
                color: rgba(255,255,255,0.75);
                font-size: 12px; font-weight: 700;
                padding: 7px 16px; border-radius: 999px;
                text-decoration: none; transition: all 0.2s;
                letter-spacing: 0.04em;
            }
            .btn-back:hover { background: rgba(234,88,12,0.2); color: white; border-color: rgba(234,88,12,0.4); }

            /* Bloque central del hero */
            .hero-center {
                position: relative; z-index: 10;
                padding: 32px 40px; flex: 1;
                display: flex; flex-direction: column; justify-content: center;
            }
            @media (max-width: 900px) { .hero-center { padding: 24px 20px; } }

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
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1px;
                background: rgba(255,255,255,0.07);
                border: 1px solid rgba(255,255,255,0.07);
                border-radius: 16px; overflow: hidden;
            }

            .hero-stat {
                background: rgba(255,255,255,0.04);
                padding: 16px 18px; transition: background 0.2s;
            }
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
            @media (max-width: 900px) { .hero-foot { padding: 14px 20px; } }

            /* ─────────────────────────────────────────────────────
               MAIN LAYOUT
            ───────────────────────────────────────────────────── */
            .main-wrap {
                max-width: 1240px;
                margin: 0 auto;
                padding: 48px 40px 80px;
                display: grid;
                grid-template-columns: 1fr 360px;
                gap: 28px; align-items: start;
            }
            @media (max-width: 1024px) { .main-wrap { grid-template-columns: 1fr; } }
            @media (max-width: 600px)  { .main-wrap { padding: 28px 16px 60px; } }

            /* ─────────────────────────────────────────────────────
               CARDS
            ───────────────────────────────────────────────────── */
            .card {
                background: white; border-radius: 20px;
                border: 1px solid var(--border);
                box-shadow: 0 2px 16px rgba(0,0,0,0.04);
                overflow: hidden; transition: box-shadow 0.25s ease;
            }
            .card:hover { box-shadow: 0 6px 32px rgba(0,0,0,0.07); }

            .card-body { padding: 28px 32px; }
            @media (max-width: 600px) { .card-body { padding: 20px 18px; } }

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

            /* ─────────────────────────────────────────────────────
               GALERÍA
            ───────────────────────────────────────────────────── */
            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                grid-auto-rows: 120px; gap: 8px;
            }
            @media (max-width: 700px) {
                .gallery-grid { grid-template-columns: repeat(3, 1fr); grid-auto-rows: 90px; }
            }
            @media (max-width: 420px) { .gallery-grid { grid-template-columns: repeat(2, 1fr); } }

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

            /* ─────────────────────────────────────────────────────
               DÍAS DE ATENCIÓN
            ───────────────────────────────────────────────────── */
            .dia-pill {
                display: inline-flex; align-items: center; justify-content: center;
                width: 42px; height: 42px; border-radius: 50%;
                font-size: 11px; font-weight: 700; text-transform: uppercase;
                border: 1.5px solid var(--border); color: #a8a29e; background: #fafaf9;
                transition: all 0.2s;
            }
            .dia-pill.activo {
                background: var(--orange); border-color: var(--orange);
                color: white; box-shadow: 0 4px 14px rgba(234,88,12,0.35);
            }

            /* ─────────────────────────────────────────────────────
               MAPA
            ───────────────────────────────────────────────────── */
            #mapa-publico {
                height: 260px; border-radius: 14px;
                overflow: hidden; border: 1px solid var(--border);
            }

            .dir-box {
                display: flex; align-items: flex-start; gap: 10px;
                background: #fff7ed; border: 1px solid var(--orange-light);
                border-radius: 12px; padding: 12px 16px; margin-bottom: 14px;
                font-size: 13.5px; color: #57534e; line-height: 1.6;
            }
            .dir-box i { color: var(--orange); margin-top: 2px; flex-shrink: 0; }

            .btn-gmaps {
                display: inline-flex; align-items: center; gap: 8px;
                background: var(--dark); color: white; text-decoration: none;
                font-size: 13px; font-weight: 700;
                padding: 10px 20px; border-radius: 10px; margin-top: 14px;
                transition: background 0.2s, transform 0.2s; letter-spacing: 0.02em;
            }
            .btn-gmaps:hover { background: var(--orange); transform: translateY(-1px); }

            /* ─────────────────────────────────────────────────────
               SIDEBAR
            ───────────────────────────────────────────────────── */
            .sidebar { display: flex; flex-direction: column; gap: 16px; }
            @media (min-width: 1024px) { .sidebar { position: sticky; top: 24px; } }

            .contact-row {
                background: #fafaf9; border: 1px solid var(--border);
                border-radius: 10px; padding: 12px 14px;
                font-size: 13px; font-weight: 600; color: #292524;
                word-break: break-all;
                display: flex; align-items: center; gap: 10px;
            }
            .contact-row i { color: var(--orange); }

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
                padding: 15px 20px; border-radius: 14px; text-decoration: none;
                transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
                letter-spacing: 0.03em;
            }
            .cta-btn:hover {
                background: var(--orange);
                transform: translateY(-2px);
                box-shadow: 0 12px 32px rgba(234,88,12,0.35);
            }

            /* ─────────────────────────────────────────────────────
               LIGHTBOX
            ───────────────────────────────────────────────────── */
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

            /* ─────────────────────────────────────────────────────
               ANIMACIONES
            ───────────────────────────────────────────────────── */
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

        {{-- ══════════════════════════════════════════════════════════
             HERO SPLIT-SCREEN
        ════════════════════════════════════════════════════════════ --}}
        <section class="hero-wrap">

            {{-- Columna imagen --}}
            <div class="hero-img-col">
                @php
                    $bgUrl = '';
                    if ($gastrobar->imagen_principal)
                        $bgUrl = asset('storage/' . $gastrobar->imagen_principal);
                    elseif ($gastrobar->galeria && count($gastrobar->galeria) > 0)
                        $bgUrl = asset('storage/' . $gastrobar->galeria[0]);
                    else
                        $bgUrl = 'https://images.unsplash.com/photo-1572116469696-31de0f17cc34?auto=format&fit=crop&w=1600&q=80';
                @endphp
                <div class="hero-bg" style="background-image:url('{{ $bgUrl }}');"></div>
                <div class="hero-img-overlay"></div>

                @if($gastrobar->imagen_principal)
                    <button class="btn-zoom-portada"
                            onclick="openLightbox('{{ asset('storage/' . $gastrobar->imagen_principal) }}', -1)">
                        <i class="fas fa-expand-alt" style="font-size:10px;"></i> Ver portada
                    </button>
                @endif
            </div>

            {{-- Columna info --}}
            <div class="hero-info-col">

                {{-- NAV --}}
                <nav class="nav-inner">
                    <a href="{{ route('home') }}" class="logo-link">
                        <div class="logo-icon">
                            <i class="fas fa-cocktail" style="color:white;font-size:12px;"></i>
                        </div>
                        <span class="premium-title" style="font-size:19px;font-weight:700;color:white;font-style:italic;">
                            Gastro<span style="color:#fb923c;">Nicaragua</span>
                        </span>
                    </a>
                    <a href="{{ route('gastrobares.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left" style="font-size:10px;"></i> Volver
                    </a>
                </nav>

                {{-- Contenido central --}}
                <div class="hero-center">

                    <div class="hero-badge fu">
                        <i class="fas fa-cocktail" style="font-size:9px;"></i>
                        Gastrobar
                        @if($gastrobar->tipo_bar)
                            &nbsp;·&nbsp; {{ $gastrobar->tipo_bar }}
                        @endif
                    </div>

                    <h1 class="premium-title hero-title fu d1">
                        {{ $gastrobar->nombre }}
                    </h1>

                    <div class="hero-location fu d2">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>{{ $gastrobar->departamento->nombre }}</strong>
                        @if($gastrobar->municipio)
                            <span style="opacity:.45;">·</span>
                            <span>{{ $gastrobar->municipio->nombre }}</span>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="hero-stats fu d3">
                        <div class="hero-stat">
                            <span class="hero-stat-label">Ambiente</span>
                            <span class="hero-stat-value" style="color:#fb923c;">
                                {{ $gastrobar->ambiente ?? ($gastrobar->tipo_bar ?? 'Bar') }}
                            </span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-label">Música</span>
                            <span class="hero-stat-value" style="font-size:12px;">
                                {{ $gastrobar->tipo_musica ?? '—' }}
                            </span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-label">Capacidad</span>
                            <span class="hero-stat-value">
                                @if($gastrobar->capacidad)
                                    {{ $gastrobar->capacidad }}<span style="font-size:11px;color:rgba(255,255,255,0.35);font-weight:500;"> pers.</span>
                                @else
                                    <span style="font-size:11px;color:rgba(255,255,255,0.35);font-weight:500;">—</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Pie: redes + estado --}}
                <div class="hero-foot fu d4">
                    @php
                        $abierto = true; // Ajusta con lógica real si tienes horarios
                        if ($gastrobar->hora_apertura && $gastrobar->hora_cierre) {
                            $ahora = \Carbon\Carbon::now()->format('H:i');
                            $abierto = ($ahora >= $gastrobar->hora_apertura && $ahora <= $gastrobar->hora_cierre);
                        }
                    @endphp
                    <span style="background:{{ $abierto ? '#dcfce7' : '#fee2e2' }};color:{{ $abierto ? '#15803d' : '#b91c1c' }};border:1px solid {{ $abierto ? '#bbf7d0' : '#fecaca' }};font-size:11px;font-weight:800;padding:6px 14px;border-radius:999px;display:inline-flex;align-items:center;gap:6px;">
                        <span style="width:6px;height:6px;background:{{ $abierto ? '#22c55e' : '#ef4444' }};border-radius:50%;display:inline-block;"></span>
                        {{ $abierto ? 'ABIERTO' : 'CERRADO' }}
                    </span>

                    @if(!empty($gastrobar->whatsapp))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$gastrobar->whatsapp) }}" target="_blank" class="social-btn wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    @endif
                    @if(!empty($gastrobar->instagram))
                        <a href="{{ $gastrobar->instagram }}" target="_blank" class="social-btn ig" title="Instagram"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(!empty($gastrobar->tiktok))
                        <a href="{{ $gastrobar->tiktok }}" target="_blank" class="social-btn tt" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    @endif
                    @if(!empty($gastrobar->facebook))
                        <a href="{{ $gastrobar->facebook }}" target="_blank" class="social-btn fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    @endif
                </div>

            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
             MAIN CONTENT
        ════════════════════════════════════════════════════════════ --}}
        @php
            $todasLasImagenes = [];
            if ($gastrobar->galeria && count($gastrobar->galeria) > 0) {
                foreach ($gastrobar->galeria as $foto) {
                    $todasLasImagenes[] = asset('storage/' . $foto);
                }
            }
        @endphp

        <main class="main-wrap">

            {{-- ── COLUMNA PRINCIPAL ── --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Descripción --}}
                <div class="card">
                    <div class="card-body">
                        <div class="section-label">
                            <i class="fas fa-align-left"></i> Sobre el gastrobar
                        </div>
                        <p class="desc-text">
                            @if($gastrobar->descripcion)
                                {{ $gastrobar->descripcion }}
                            @else
                                Bienvenidos a <strong style="color:var(--text-main);">{{ $gastrobar->nombre }}</strong>.
                                Somos un espacio único para disfrutar de la mejor experiencia en la hermosa localidad
                                de {{ $gastrobar->municipio->nombre ?? '' }}, {{ $gastrobar->departamento->nombre ?? '' }}.
                                ¡Visítanos y vive una noche inigualable!
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Horarios y días de atención --}}
                @if(($gastrobar->hora_apertura && $gastrobar->hora_cierre) || ($gastrobar->dias_atencion && count($gastrobar->dias_atencion) > 0))
                    <div class="card">
                        <div class="card-body">
                            <div class="section-label">
                                <i class="fas fa-clock"></i> Horario de atención
                            </div>

                            @if($gastrobar->hora_apertura && $gastrobar->hora_cierre)
                                <div style="display:flex;align-items:center;gap:10px;background:#fff7ed;border:1px solid var(--orange-light);border-radius:12px;padding:14px 18px;margin-bottom:20px;">
                                    <i class="fas fa-clock" style="color:var(--orange);font-size:18px;"></i>
                                    <div>
                                        <span style="font-size:10px;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:#a8a29e;display:block;margin-bottom:3px;">Horario</span>
                                        <span style="font-size:15px;font-weight:700;color:var(--text-main);">
                                            {{ \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') }}
                                            <span style="color:#a8a29e;font-weight:400;">–</span>
                                            {{ \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if($gastrobar->dias_atencion && count($gastrobar->dias_atencion) > 0)
                                @php
                                    $diasSemana = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
                                    $diasLabels = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
                                @endphp
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                    @foreach($diasSemana as $i => $dia)
                                        <div class="dia-pill {{ in_array($dia, $gastrobar->dias_atencion) ? 'activo' : '' }}">
                                            {{ $diasLabels[$i] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Mapa --}}
                @if($gastrobar->latitud && $gastrobar->longitud)
                    <div class="card">
                        <div class="card-body">
                            <div class="section-label">
                                <i class="fas fa-map-marker-alt"></i> Cómo llegar
                            </div>

                            @if($gastrobar->direccion)
                                <div class="dir-box">
                                    <i class="fas fa-map-pin"></i>
                                    <p>{{ $gastrobar->direccion }}</p>
                                </div>
                            @endif

                            <div id="mapa-publico"></div>

                            <a href="https://www.google.com/maps?q={{ $gastrobar->latitud }},{{ $gastrobar->longitud }}"
                               target="_blank" class="btn-gmaps">
                                <i class="fas fa-directions"></i> Abrir en Google Maps
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Galería --}}
                @if(count($todasLasImagenes) > 0)
                    <div class="card">
                        <div class="card-body">
                            <div class="section-label">
                                <i class="fas fa-images"></i>
                                Galería de fotos
                                <span style="font-size:10px;color:#d6d3d1;font-weight:500;text-transform:none;letter-spacing:0;">— clic para ampliar</span>
                            </div>
                            <div class="gallery-grid">
                                @foreach($gastrobar->galeria as $index => $foto)
                                    <div class="g-item" onclick="openLightbox('{{ asset('storage/' . $foto) }}', {{ $index }})">
                                        <img src="{{ asset('storage/' . $foto) }}"
                                             alt="Foto {{ $index + 1 }} de {{ $gastrobar->nombre }}"
                                             loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar">

                {{-- Información --}}
                <div class="card">
                    <div class="card-body">
                        <div class="section-label">
                            <i class="far fa-clock"></i> Información
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #f5f5f4;">
                            <span style="font-size:13px;font-weight:600;color:var(--text-muted);">Estado actual</span>
                            <span style="background:{{ $abierto ? '#dcfce7' : '#fee2e2' }};color:{{ $abierto ? '#15803d' : '#b91c1c' }};border:1px solid {{ $abierto ? '#bbf7d0' : '#fecaca' }};font-size:11px;font-weight:800;padding:5px 12px;border-radius:999px;letter-spacing:0.06em;display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:6px;height:6px;background:{{ $abierto ? '#22c55e' : '#ef4444' }};border-radius:50%;display:inline-block;"></span>
                                {{ $abierto ? 'ABIERTO' : 'CERRADO' }}
                            </span>
                        </div>

                        @if($gastrobar->tipo_cocina)
                            <div style="margin-bottom:12px;">
                                <span style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:6px;">Cocina</span>
                                <div class="contact-row">
                                    <i class="fas fa-utensils"></i> {{ $gastrobar->tipo_cocina }}
                                </div>
                            </div>
                        @endif

                        <span style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:10px;">Contacto</span>
                        <div class="contact-row">
                            <i class="fas fa-envelope"></i>
                            {{ $gastrobar->email ?? 'No disponible' }}
                        </div>
                    </div>
                </div>

                {{-- Redes sociales --}}
                @if(!empty($gastrobar->whatsapp) || !empty($gastrobar->instagram) || !empty($gastrobar->tiktok) || !empty($gastrobar->facebook))
                    <div class="card">
                        <div class="card-body">
                            <div class="section-label">
                                <i class="fas fa-share-alt"></i> Redes sociales
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                                @if(!empty($gastrobar->whatsapp))
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$gastrobar->whatsapp) }}" target="_blank" class="social-btn wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                @endif
                                @if(!empty($gastrobar->instagram))
                                    <a href="{{ $gastrobar->instagram }}" target="_blank" class="social-btn ig" title="Instagram"><i class="fab fa-instagram"></i></a>
                                @endif
                                @if(!empty($gastrobar->tiktok))
                                    <a href="{{ $gastrobar->tiktok }}" target="_blank" class="social-btn tt" title="TikTok"><i class="fab fa-tiktok"></i></a>
                                @endif
                                @if(!empty($gastrobar->facebook))
                                    <a href="{{ $gastrobar->facebook }}" target="_blank" class="social-btn fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- CTA --}}
                <a href="mailto:{{ $gastrobar->email ?? 'contacto@gastronicaragua.com' }}" class="cta-btn">
                    <i class="fas fa-paper-plane" style="font-size:13px;"></i>
                    Enviar consulta al local
                </a>

            </aside>

        </main>

        {{-- Footer --}}
        <footer style="background:#0c0a09;color:white;padding:40px 2rem;text-align:center;border-top:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px;">
                <div style="width:30px;height:30px;background:#ea580c;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-cocktail" style="color:white;font-size:11px;"></i>
                </div>
                <span class="premium-title" style="color:white;font-size:17px;font-style:italic;">Gastro<span style="color:#fb923c;">Nicaragua</span></span>
            </div>
            <p style="color:#57534e;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;margin:0;">© {{ date('Y') }} — Experiencias Culinarias de Nicaragua</p>
        </footer>

        {{-- ══ LIGHTBOX ══ --}}
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
                lbImg.style.opacity   = '0';
                lbImg.style.transform = 'scale(0.94)';
                setTimeout(() => {
                    lbImg.src             = galeriaImages[currentIndex];
                    lbImg.style.opacity   = '1';
                    lbImg.style.transform = 'scale(1)';
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
                    lbDots.innerHTML   = '';
                }
            }

            function jumpTo(i) { currentIndex = i; lbImg.src = galeriaImages[i]; updateControls(); }
            function handleLightboxClick(e) { if (e.target === lb) closeLightbox(); }

            document.addEventListener('keydown', e => {
                if (!lb.classList.contains('active')) return;
                if (e.key === 'Escape')     closeLightbox();
                if (e.key === 'ArrowRight') navigateLightbox(1);
                if (e.key === 'ArrowLeft')  navigateLightbox(-1);
            });

            lbImg.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
        </script>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            @if($gastrobar->latitud && $gastrobar->longitud)
            document.addEventListener('DOMContentLoaded', function() {
                const mapa = L.map('mapa-publico', { scrollWheelZoom: false })
                              .setView([{{ $gastrobar->latitud }}, {{ $gastrobar->longitud }}], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19
                }).addTo(mapa);

                const icono = L.divIcon({
                    html: `<div style="width:22px;height:22px;background:#ea580c;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 12px rgba(0,0,0,0.4);"></div>`,
                    iconSize: [22,22], iconAnchor: [11,22], className: ''
                });

                L.marker([{{ $gastrobar->latitud }}, {{ $gastrobar->longitud }}], { icon: icono })
                    .addTo(mapa)
                    .bindPopup(`
                        <strong style="font-size:13px;color:#1c1917;">{{ $gastrobar->nombre }}</strong><br>
                        <span style="font-size:12px;color:#78716c;">{{ optional($gastrobar->municipio)->nombre }}, {{ $gastrobar->departamento->nombre }}</span>
                    `, { maxWidth: 200 })
                    .openPopup();
            });
            @endif
        </script>

    </body>
</html>