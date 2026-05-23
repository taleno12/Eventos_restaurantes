<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gastro Nicaragua | Ofertas de Empleo</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            :root {
                --orange: #ea580c;
                --dark: #0c0a09;
                --cream: #faf9f6;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'Instrument Sans', sans-serif;
                overflow-x: hidden;
                background: var(--cream);
                color: #1c1917;
            }

            .premium-title { font-family: 'Playfair Display', serif; }

            /* ── NAV ── */
            .nav-bar {
                position: fixed;
                top: 0; left: 0; right: 0;
                z-index: 50;
                background: rgba(255,255,255,0.85);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(231,229,228,0.6);
                box-shadow: 0 1px 16px rgba(0,0,0,0.04);
            }

            .nav-inner {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                height: 76px;
                gap: 20px;
            }

            .nav-logo {
                display: flex;
                align-items: center;
                gap: 10px;
                text-decoration: none;
                flex-shrink: 0;
            }

            .nav-logo-icon {
                width: 40px; height: 40px;
                background: #ea580c;
                border-radius: 12px;
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 4px 14px rgba(234,88,12,0.3);
            }

            .search-field {
                flex: 1;
                position: relative;
                max-width: 520px;
            }

            .search-field input,
            .search-field select {
                width: 100%;
                background: #f5f5f4;
                border: 1.5px solid transparent;
                border-radius: 999px;
                padding: 10px 16px 10px 40px;
                font-size: 13px;
                color: #1c1917;
                font-family: 'Instrument Sans', sans-serif;
                outline: none;
                transition: all 0.2s ease;
            }

            .search-field input:focus,
            .search-field select:focus {
                border-color: #ea580c;
                background: white;
                box-shadow: 0 0 0 4px rgba(234,88,12,0.08);
            }

            .search-field i {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #a8a29e;
                font-size: 12px;
                pointer-events: none;
            }

            .btn-filter {
                background: #0c0a09;
                color: white;
                border: none;
                border-radius: 999px;
                padding: 10px 22px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.25s ease;
                flex-shrink: 0;
                font-family: 'Instrument Sans', sans-serif;
            }

            .btn-filter:hover { background: #ea580c; box-shadow: 0 6px 20px rgba(234,88,12,0.3); }

            /* ── HERO ── */
            .hero-section {
                background: linear-gradient(160deg, #1a0800 0%, #0c0a09 60%, #1c1410 100%);
                padding: 140px 2rem 80px;
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: -80px; right: -80px;
                width: 500px; height: 500px;
                background: radial-gradient(circle, rgba(234,88,12,0.18) 0%, transparent 70%);
                pointer-events: none;
            }

            .hero-section::after {
                content: '';
                position: absolute;
                bottom: -60px; left: 20%;
                width: 400px; height: 400px;
                background: radial-gradient(circle, rgba(251,146,60,0.07) 0%, transparent 70%);
                pointer-events: none;
            }

            .hero-dots {
                position: absolute;
                inset: 0;
                background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
                background-size: 22px 22px;
                pointer-events: none;
            }

            .hero-inner {
                max-width: 1280px;
                margin: 0 auto;
                position: relative;
                z-index: 10;
                text-align: center;
            }

            .hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(234,88,12,0.15);
                border: 1px solid rgba(234,88,12,0.3);
                color: #fb923c;
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                padding: 8px 20px;
                border-radius: 999px;
                margin-bottom: 28px;
            }

            .hero-title {
                font-size: clamp(2.4rem, 5.5vw, 5rem);
                font-weight: 900;
                color: white;
                line-height: 1.08;
                margin-bottom: 20px;
            }

            .hero-title span {
                color: transparent;
                background: linear-gradient(90deg, #fb923c, #f59e0b);
                -webkit-background-clip: text;
                background-clip: text;
            }

            .hero-sub {
                color: #a8a29e;
                font-size: 15px;
                max-width: 540px;
                margin: 0 auto 48px;
                line-height: 1.75;
                font-weight: 400;
            }

            .stats-row {
                display: inline-flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0;
                background: rgba(255,255,255,0.04);
                border: 1px solid rgba(255,255,255,0.07);
                border-radius: 20px;
                padding: 24px 40px;
                backdrop-filter: blur(8px);
            }

            .stat-item {
                padding: 0 40px;
                text-align: center;
                position: relative;
            }

            .stat-item + .stat-item::before {
                content: '';
                position: absolute;
                left: 0; top: 10%; bottom: 10%;
                width: 1px;
                background: rgba(255,255,255,0.08);
            }

            .stat-num {
                font-size: 2.5rem;
                font-weight: 900;
                color: #fb923c;
                line-height: 1;
                display: block;
            }

            .stat-label {
                font-size: 9px;
                font-weight: 800;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                color: #78716c;
                margin-top: 8px;
                display: block;
            }

            /* ── MAIN ── */
            .main-wrap {
                max-width: 1280px;
                margin: 0 auto;
                padding: 64px 2rem 80px;
            }

            /* ── SEARCH ACTIVE FILTERS ── */
            .filter-bar {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
                margin-bottom: 36px;
                padding: 14px 20px;
                background: white;
                border: 1px solid #e7e5e4;
                border-radius: 16px;
            }

            .filter-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #fafaf9;
                border: 1px solid #e7e5e4;
                color: #292524;
                font-size: 12px;
                font-weight: 600;
                padding: 6px 14px;
                border-radius: 999px;
                text-decoration: none;
            }

            .filter-chip a { color: #a8a29e; text-decoration: none; font-size: 14px; }
            .filter-chip a:hover { color: #e11d48; }

            /* ── SECTION HEADER ── */
            .section-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 32px;
            }

            .section-eyebrow {
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                color: #ea580c;
                margin-bottom: 4px;
            }

            .section-title {
                font-size: 24px;
                font-weight: 800;
                color: #1c1917;
            }

            .count-badge {
                background: #fff7ed;
                border: 1px solid #fed7aa;
                color: #c2410c;
                font-size: 11px;
                font-weight: 700;
                padding: 6px 14px;
                border-radius: 999px;
            }

            /* ── JOB CARDS ── */
            .jobs-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
                gap: 20px;
            }

            .job-card {
                background: white;
                border: 1px solid #f1f0ee;
                border-radius: 24px;
                padding: 28px;
                display: flex;
                flex-direction: column;
                gap: 20px;
                transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                position: relative;
                overflow: hidden;
            }

            .job-card::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0;
                height: 3px;
                background: linear-gradient(90deg, #ea580c, #f59e0b);
                transform: scaleX(0);
                transform-origin: left;
                transition: transform 0.35s ease;
            }

            .job-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 48px rgba(28,25,23,0.09);
                border-color: #fed7aa;
            }

            .job-card:hover::before { transform: scaleX(1); }

            .card-top {
                display: flex;
                align-items: flex-start;
                gap: 14px;
            }

            .card-icon {
                width: 48px; height: 48px;
                background: #f5f5f4;
                border: 1px solid #e7e5e4;
                border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
                transition: all 0.3s ease;
            }

            .job-card:hover .card-icon {
                background: #fff7ed;
                border-color: #fed7aa;
            }

            .job-card:hover .card-icon i { color: #ea580c; }

            .card-restaurant {
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: #ea580c;
                display: block;
                margin-bottom: 5px;
            }

            .card-title {
                font-size: 18px;
                font-weight: 800;
                color: #1c1917;
                line-height: 1.25;
                transition: color 0.2s;
            }

            .job-card:hover .card-title { color: #ea580c; }

            .card-desc {
                color: #78716c;
                font-size: 13px;
                line-height: 1.7;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
                margin: 0;
            }

            /* ── BADGES ── */
            .badges-row {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 11px;
                font-weight: 700;
                padding: 5px 12px;
                border-radius: 999px;
            }

            .badge-contract { background: #f5f5f4; color: #57534e; }
            .badge-salary   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
            .badge-negociar { background: #f5f5f4; color: #78716c; }
            .badge-location { background: #eff6ff; color: #1d4ed8; }

            /* ── CARD FOOTER ── */
            .card-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-top: 18px;
                border-top: 1px solid #f5f5f4;
                margin-top: auto;
            }

            .card-date {
                display: flex;
                align-items: center;
                gap: 6px;
                color: #a8a29e;
                font-size: 11px;
                font-weight: 500;
            }

            .btn-ver {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #0c0a09;
                color: white;
                text-decoration: none;
                font-size: 12px;
                font-weight: 700;
                padding: 9px 18px;
                border-radius: 999px;
                transition: all 0.25s ease;
            }

            .btn-ver:hover {
                background: #ea580c;
                color: white;
                transform: translateX(2px);
                box-shadow: 0 6px 20px rgba(234,88,12,0.3);
            }

            /* ── EMPTY STATE ── */
            .empty-state {
                text-align: center;
                background: white;
                border: 1px solid #e7e5e4;
                border-radius: 28px;
                padding: 64px 40px;
                max-width: 480px;
                margin: 0 auto;
            }

            .empty-icon {
                width: 72px; height: 72px;
                background: #fafaf9;
                border: 1px solid #e7e5e4;
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 20px;
            }

            /* ── PAGINATION ── */
            .pagination-wrap { margin-top: 56px; display: flex; justify-content: center; }

            /* ── MOBILE SEARCH PANEL ── */
            #mobileSearchPanel {
                display: none;
                position: absolute;
                top: 100%; left: 0; right: 0;
                background: rgba(255,255,255,0.98);
                backdrop-filter: blur(16px);
                border-top: 1px solid #e7e5e4;
                padding: 20px 24px;
                z-index: 40;
                box-shadow: 0 12px 32px rgba(0,0,0,0.08);
            }
            #mobileSearchPanel.open { display: block; }

            /* ── FOOTER ── */
            .site-footer {
                background: #0c0a09;
                border-top: 1px solid rgba(255,255,255,0.05);
                padding: 64px 2rem 32px;
            }

            .footer-grid {
                max-width: 1280px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1.5fr;
                gap: 48px;
                padding-bottom: 48px;
                border-bottom: 1px solid rgba(255,255,255,0.07);
            }

            .footer-bottom {
                max-width: 1280px;
                margin: 0 auto;
                padding-top: 32px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
            }

            .footer-social-link {
                width: 34px; height: 34px;
                background: rgba(255,255,255,0.06);
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                color: #78716c;
                text-decoration: none;
                transition: all 0.2s;
            }

            .footer-social-link:hover {
                background: rgba(234,88,12,0.2);
                color: #fb923c;
            }

            .footer-link {
                color: #78716c;
                font-size: 13px;
                text-decoration: none;
                transition: color 0.2s;
            }

            .footer-link:hover { color: #fb923c; }

            .footer-newsletter-input {
                background: rgba(255,255,255,0.06);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 8px;
                padding: 10px 14px;
                color: white;
                font-size: 13px;
                font-family: 'Instrument Sans', sans-serif;
                outline: none;
                width: 100%;
                transition: all 0.2s;
            }

            .footer-newsletter-input:focus {
                border-color: rgba(234,88,12,0.5);
                background: rgba(255,255,255,0.09);
            }

            .footer-newsletter-input::placeholder { color: #57534e; }

            .footer-newsletter-btn {
                background: #ea580c;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 10px 14px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                width: 100%;
                font-family: 'Instrument Sans', sans-serif;
                transition: all 0.2s;
            }

            .footer-newsletter-btn:hover {
                background: #c2410c;
                box-shadow: 0 4px 14px rgba(234,88,12,0.35);
            }

            /* ── ANIMATIONS ── */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(24px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .job-card { animation: fadeUp 0.5s ease both; }
            .job-card:nth-child(1)  { animation-delay: 0.00s; }
            .job-card:nth-child(2)  { animation-delay: 0.07s; }
            .job-card:nth-child(3)  { animation-delay: 0.14s; }
            .job-card:nth-child(4)  { animation-delay: 0.21s; }
            .job-card:nth-child(5)  { animation-delay: 0.28s; }
            .job-card:nth-child(6)  { animation-delay: 0.35s; }
            .job-card:nth-child(7)  { animation-delay: 0.42s; }
            .job-card:nth-child(8)  { animation-delay: 0.49s; }
            .job-card:nth-child(9)  { animation-delay: 0.56s; }

            @media (max-width: 768px) {
                .jobs-grid { grid-template-columns: 1fr; }
                .stats-row { padding: 20px 24px; }
                .stat-item { padding: 0 20px; }
                .stat-num { font-size: 2rem; }
                .hero-section { padding: 120px 1.25rem 64px; }
                .main-wrap { padding: 40px 1.25rem 64px; }
                .nav-search-desktop { display: none !important; }
                .footer-grid { grid-template-columns: 1fr; gap: 32px; }
                .footer-bottom { flex-direction: column; align-items: flex-start; }
            }
        </style>
    </head>
    <body>

        {{-- ── NAV ── --}}
        <nav class="nav-bar" style="position:fixed;">
            <div class="nav-inner">
                <a href="{{ route('home') }}" class="nav-logo">
                    <div class="nav-logo-icon">
                        <i class="fas fa-utensils" style="color:white;font-size:13px;"></i>
                    </div>
                    <span class="premium-title" style="font-size:22px;font-weight:700;color:#1c1917;font-style:italic;">Gastro<span style="color:#ea580c;">Nicaragua</span></span>
                </a>

                {{-- Desktop search --}}
                <form action="{{ route('empleos.index') }}" method="GET"
                      class="nav-search-desktop"
                      style="display:flex;align-items:center;gap:10px;flex:1;max-width:560px;">
                    <div class="search-field" style="flex:1;">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Puesto o restaurante…">
                    </div>
                    <div class="search-field" style="width:200px;">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="departamento" style="appearance:none;padding-right:32px;">
                            <option value="">Todos los destinos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                    {{ $depto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:10px;pointer-events:none;color:#a8a29e;"></i>
                    </div>
                    <button type="submit" class="btn-filter">
                        Filtrar <i class="fas fa-sliders-h" style="font-size:11px;opacity:0.8;"></i>
                    </button>
                </form>

                <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
                    <button id="mobileSearchToggle"
                            style="display:none;width:40px;height:40px;border-radius:50%;background:#f5f5f4;border:none;cursor:pointer;align-items:center;justify-content:center;color:#57534e;"
                            class="mobile-search-btn">
                        <i class="fas fa-search" style="font-size:13px;"></i>
                    </button>
                    <a href="{{ route('home') }}"
                       style="display:flex;align-items:center;gap:6px;border:1px solid #e7e5e4;color:#57534e;padding:8px 16px;border-radius:999px;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.2s;">
                        <i class="far fa-calendar-alt" style="font-size:11px;"></i> Eventos
                    </a>
                    {{-- BOTÓN PANEL ELIMINADO --}}
                </div>
            </div>

            {{-- Mobile search panel --}}
            <div id="mobileSearchPanel">
                <form action="{{ route('empleos.index') }}" method="GET" style="display:flex;flex-direction:column;gap:12px;">
                    <div class="search-field">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Puesto o restaurante…" style="width:100%;">
                    </div>
                    <div class="search-field" style="position:relative;">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="departamento" style="width:100%;appearance:none;padding-right:32px;">
                            <option value="">Todos los destinos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>{{ $depto->nombre }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:10px;pointer-events:none;color:#a8a29e;"></i>
                    </div>
                    <button type="submit" class="btn-filter" style="width:100%;justify-content:center;background:#ea580c;">
                        Buscar empleos <i class="fas fa-arrow-right" style="font-size:11px;"></i>
                    </button>
                </form>
            </div>
        </nav>

        {{-- ── HERO ── --}}
        <section class="hero-section">
            <div class="hero-dots"></div>
            <div class="hero-inner">
                <div class="hero-badge">
                    <i class="fas fa-briefcase" style="font-size:9px;"></i>
                    Oportunidades Laborales
                </div>
                <h1 class="premium-title hero-title">
                    Trabaja en los mejores<br>
                    <span>restaurantes</span> de Nicaragua
                </h1>
                <p class="hero-sub">
                    Encuentra tu lugar en la gastronomía local. Desde el corazón de la cocina hasta la excelencia en el servicio, hay un puesto premium esperándote.
                </p>
                <div class="stats-row">
                    <div class="stat-item">
                        <span class="stat-num">{{ $empleos->total() }}</span>
                        <span class="stat-label">Vacantes activas</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">{{ $totalRestaurantes }}</span>
                        <span class="stat-label">Restaurantes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">{{ $totalDepartamentos }}</span>
                        <span class="stat-label">Destinos</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── MAIN ── --}}
        <main class="main-wrap">

            {{-- Filtros activos --}}
            @if(request('search') || request('departamento'))
                <div class="filter-bar">
                    <span style="font-size:12px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.1em;">Filtros:</span>
                    @if(request('search'))
                        <span class="filter-chip">
                            <i class="fas fa-search" style="font-size:10px;color:#a8a29e;"></i>
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery(['search']) }}">×</a>
                        </span>
                    @endif
                    @if(request('departamento'))
                        <span class="filter-chip">
                            <i class="fas fa-map-marker-alt" style="font-size:10px;color:#a8a29e;"></i>
                            {{ $departamentos->find(request('departamento'))?->nombre }}
                            <a href="{{ request()->fullUrlWithoutQuery(['departamento']) }}">×</a>
                        </span>
                    @endif
                    <a href="{{ route('empleos.index') }}" style="margin-left:auto;font-size:12px;font-weight:600;color:#ea580c;text-decoration:none;">Limpiar todo</a>
                </div>
            @endif

            {{-- Header de sección --}}
            <div class="section-header">
                <div>
                    <p class="section-eyebrow">
                        <i class="fas fa-fire-alt" style="margin-right:4px;"></i> Vacantes disponibles
                    </p>
                    <h2 class="premium-title section-title">Ofertas de empleo</h2>
                </div>
                @if($empleos->count() > 0)
                    <span class="count-badge">{{ $empleos->total() }} {{ $empleos->total() === 1 ? 'oferta' : 'ofertas' }}</span>
                @endif
            </div>

            @if($empleos->count() > 0)
                <div class="jobs-grid">
                    @foreach($empleos as $empleo)
                        <article class="job-card">

                            <div class="card-top">
                                <div class="card-icon">
                                    <i class="fas fa-store" style="color:#a8a29e;font-size:18px;"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <span class="card-restaurant">{{ $empleo->restaurante->nombre }}</span>
                                    <h3 class="premium-title card-title">{{ $empleo->titulo }}</h3>
                                </div>
                            </div>

                            <p class="card-desc">{{ $empleo->descripcion }}</p>

                            <div class="badges-row">
                                @if($empleo->tipo_contrato)
                                    <span class="badge badge-contract">
                                        <i class="far fa-clock" style="font-size:10px;"></i> {{ $empleo->tipo_contrato }}
                                    </span>
                                @endif
                                @if($empleo->salario)
                                    <span class="badge badge-salary">
                                        <i class="fas fa-wallet" style="font-size:10px;"></i> C$ {{ number_format($empleo->salario, 0) }}
                                    </span>
                                @else
                                    <span class="badge badge-negociar">
                                        <i class="fas fa-handshake" style="font-size:10px;"></i> A convenir
                                    </span>
                                @endif
                                @if($empleo->departamento)
                                    <span class="badge badge-location">
                                        <i class="fas fa-map-marker-alt" style="font-size:10px;"></i>
                                        {{ $empleo->departamento->nombre }}@if($empleo->municipio), {{ $empleo->municipio->nombre }}@endif
                                    </span>
                                @endif
                            </div>

                            <div class="card-footer">
                                <div class="card-date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($empleo->created_at)->diffForHumans() }}
                                </div>
                                <a href="{{ route('empleos.show', $empleo->id) }}" class="btn-ver">
                                    Ver oferta <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pagination-wrap">
                    {{ $empleos->withQueryString()->links() }}
                </div>

            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-briefcase" style="font-size:26px;color:#d6d3d1;"></i>
                    </div>
                    <h3 class="premium-title" style="font-size:22px;font-weight:800;color:#1c1917;margin-bottom:8px;">No hay ofertas disponibles</h3>
                    <p style="color:#78716c;font-size:14px;line-height:1.7;margin:0 0 24px;">
                        @if(request('search') || request('departamento'))
                            No encontramos vacantes que coincidan con tus filtros. Intenta con otros términos.
                        @else
                            Los establecimientos aún no han publicado vacantes activas. ¡Vuelve pronto!
                        @endif
                    </p>
                    @if(request('search') || request('departamento'))
                        <a href="{{ route('empleos.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;background:#ea580c;color:white;padding:11px 24px;border-radius:999px;font-size:13px;font-weight:700;text-decoration:none;transition:all 0.2s;">
                            <i class="fas fa-times" style="font-size:11px;"></i> Limpiar filtros
                        </a>
                    @endif
                </div>
            @endif
        </main>

        {{-- ── FOOTER ── --}}
        <footer class="site-footer">

            {{-- Grid principal --}}
            <div class="footer-grid">

                {{-- Columna marca --}}
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                        <div style="width:36px;height:36px;background:#ea580c;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-utensils" style="color:white;font-size:12px;"></i>
                        </div>
                        <span class="premium-title" style="color:white;font-size:20px;font-style:italic;">
                            Gastro<span style="color:#fb923c;">Nicaragua</span>
                        </span>
                    </div>
                    <p style="color:#78716c;font-size:13px;line-height:1.75;max-width:280px;margin:0 0 24px;">
                        La plataforma líder en promoción turística y eventos culinarios de Nicaragua. Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                    </p>
                    <div style="display:flex;gap:12px;">
                        <a href="#" class="footer-social-link">
                            <i class="fab fa-facebook-f" style="font-size:12px;"></i>
                        </a>
                        <a href="#" class="footer-social-link">
                            <i class="fab fa-instagram" style="font-size:12px;"></i>
                        </a>
                        <a href="#" class="footer-social-link">
                            <i class="fab fa-tiktok" style="font-size:12px;"></i>
                        </a>
                    </div>
                </div>

                {{-- Columna Portal --}}
                <div>
                    <h4 style="color:white;font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;margin:0 0 20px;">Portal</h4>
                    <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:12px;">
                        <li><a href="{{ route('home') }}" class="footer-link">Inicio</a></li>
                        <li><a href="{{ route('restaurantes.index') }}" class="footer-link">Restaurantes</a></li>
                        <li><a href="{{ route('empleos.index') }}" style="color:#ea580c;font-size:13px;font-weight:600;text-decoration:none;">Bolsa de Empleos</a></li>
                        <li><a href="{{ route('contacto') }}" class="footer-link">Contacto</a></li>
                    </ul>
                </div>

                {{-- Columna Destinos --}}
                <div>
                    <h4 style="color:white;font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;margin:0 0 20px;">Destinos Destacados</h4>
                    <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:10px;">
                        <li><a href="#" class="footer-link">› Masaya</a></li>
                        <li><a href="#" class="footer-link">› Granada</a></li>
                        <li><a href="#" class="footer-link">› León</a></li>
                        <li><a href="#" class="footer-link">› San Juan del Sur</a></li>
                        <li><a href="#" class="footer-link">› Estelí</a></li>
                        <li><a href="#" class="footer-link">› Matagalpa</a></li>
                    </ul>
                </div>

                {{-- Columna Newsletter --}}
                <div>
                    <h4 style="color:white;font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;margin:0 0 8px;">Newsletter</h4>
                    <p style="color:#78716c;font-size:12px;line-height:1.6;margin:0 0 16px;">
                        Recibe directamente en tu bandeja las mejores agendas culinarias de la semana.
                    </p>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <input type="email"
                               placeholder="Tu correo electrónico"
                               class="footer-newsletter-input">
                        <button type="button" class="footer-newsletter-btn">
                            Suscribirse
                        </button>
                    </div>
                </div>

            </div>

            {{-- Footer bottom --}}
            <div class="footer-bottom">
                <p style="color:#57534e;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;margin:0;">
                    © 2026 Gastro Nicaragua. Todos los derechos reservados.
                </p>
                <div style="display:flex;gap:24px;">
                    <a href="#" class="footer-link" style="font-size:11px;">Política de Privacidad</a>
                    <a href="#" class="footer-link" style="font-size:11px;">Términos de Servicio</a>
                </div>
            </div>

        </footer>

        <script>
            const mobileToggle = document.getElementById('mobileSearchToggle');
            const mobilePanel  = document.getElementById('mobileSearchPanel');
            if (mobileToggle && mobilePanel) {
                mobileToggle.addEventListener('click', () => mobilePanel.classList.toggle('open'));
            }

            const cards = document.querySelectorAll('.job-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = (i * 0.06) + 's';
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            cards.forEach(card => observer.observe(card));
        </script>
    </body>
</html>