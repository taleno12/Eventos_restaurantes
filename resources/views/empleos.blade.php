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
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <style>
            :root {
                --blue: #2563eb;
                --dark: #0f172a;
                --cream: #f8fafc;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'Instrument Sans', sans-serif;
                overflow-x: hidden;
                background: var(--cream);
                color: #0f172a;
            }

            .premium-title { font-family: 'Playfair Display', serif; }

            /* ══ SEARCH BOX ══ */
            .search-box {
                display: flex; align-items: stretch; background: #f4f7fb;
                border: 1.5px solid #e2e8f0; border-radius: 18px; overflow: hidden;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .search-box:focus-within {
                border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.10); background: #fff;
            }
            .search-segment {
                display: flex; flex-direction: column; justify-content: center;
                padding: 8px 16px; min-width: 0; flex: 1; position: relative;
            }
            .search-segment + .search-segment::before {
                content: ''; position: absolute; left: 0; top: 20%;
                height: 60%; width: 1px; background: #e2e8f0;
            }
            .search-segment label {
                font-size: 9px; font-weight: 900; letter-spacing: 0.16em;
                text-transform: uppercase; color: #94a3b8; margin-bottom: 2px;
                display: flex; align-items: center; gap: 4px; cursor: pointer;
            }
            .search-segment select,
            .search-segment input {
                background: transparent; border: none; outline: none;
                font-size: 13px; font-weight: 600; color: #0f172a;
                font-family: 'Instrument Sans', sans-serif; width: 100%; padding: 0; cursor: pointer;
            }
            .search-segment select option { font-weight: 500; }
            .search-segment input::placeholder { color: #cbd5e1; font-weight: 500; }
            .search-segment select:disabled { opacity: 0.45; cursor: not-allowed; }

            .search-btn {
                display: flex; align-items: center; gap: 7px;
                background: #2563eb; color: white; border: none;
                padding: 0 20px; font-size: 13px; font-weight: 700;
                cursor: pointer; transition: background 0.2s;
                white-space: nowrap; border-radius: 0 16px 16px 0; flex-shrink: 0;
            }
            .search-btn:hover { background: #1d4ed8; }

            /* ── PANEL BÚSQUEDA MÓVIL ── */
            #mobileSearchPanel {
                display: none;
                position: absolute; top: 100%; left: 0; right: 0;
                background: rgba(255,255,255,0.98); backdrop-filter: blur(12px);
                border-top: 1px solid #e2e8f0; padding: 1rem 1.25rem;
                z-index: 40; box-shadow: 0 8px 24px rgba(15,23,42,0.08);
            }
            #mobileSearchPanel.open { display: block; }

            .nav-select-mobile {
                background: #f4f7fb; border: 1.5px solid #e2e8f0; border-radius: 12px;
                padding: 10px 14px; font-size: 13px; color: #0f172a; appearance: none;
                cursor: pointer; transition: all 0.2s; outline: none; width: 100%;
                font-family: 'Instrument Sans', sans-serif; font-weight: 600;
            }
            .nav-select-mobile:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.12); background: #fff; }
            .nav-select-mobile:disabled { opacity: 0.45; cursor: not-allowed; }

            .nav-input-mobile {
                background: #f4f7fb; border: 1.5px solid #e2e8f0; border-radius: 12px;
                padding: 10px 14px; font-size: 13px; color: #0f172a;
                transition: all 0.2s; outline: none; width: 100%;
                font-family: 'Instrument Sans', sans-serif; font-weight: 600;
            }
            .nav-input-mobile:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.12); background: #fff; }
            .nav-input-mobile::placeholder { color: #cbd5e1; }

            /* ══ HERO ══ */
            .hero-section {
                position: relative;
                overflow: hidden;
                min-height: 580px;
                display: flex;
                align-items: center;
            }
            .hero-bg-img {
                position: absolute; inset: 0;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
            .hero-bg-overlay {
                position: absolute; inset: 0;
                background: linear-gradient(120deg,
                    rgba(15,23,42,0.92) 0%,
                    rgba(15,23,42,0.82) 45%,
                    rgba(37,99,235,0.60) 100%);
            }
            .hero-inner {
                position: relative; z-index: 10;
                max-width: 1280px; margin: 0 auto;
                width: 100%;
                padding: 0 2rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 40px;
            }
            .hero-left { max-width: 620px; }

            .hero-badge {
                display: inline-flex; align-items: center; gap: 8px;
                background: rgba(37,99,235,0.18); border: 1px solid rgba(37,99,235,0.4);
                color: #60a5fa; font-size: 10px; font-weight: 800; letter-spacing: 0.2em;
                text-transform: uppercase; padding: 7px 18px; border-radius: 999px; margin-bottom: 24px;
            }
            .hero-badge-dot {
                width: 6px; height: 6px; background: #60a5fa; border-radius: 50%;
                animation: heroPulse 2s infinite;
            }
            @keyframes heroPulse {
                0%,100% { opacity:1; transform:scale(1); }
                50%      { opacity:.4; transform:scale(0.75); }
            }

            .hero-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2.2rem, 4.5vw, 3.8rem);
                font-weight: 900; color: white; line-height: 1.08; margin: 0 0 18px;
            }
            .hero-title span {
                color: transparent;
                background: linear-gradient(90deg, #60a5fa, #3b82f6);
                -webkit-background-clip: text; background-clip: text;
            }
            .hero-sub {
                color: #d1d5db; font-size: 14px; line-height: 1.8;
                margin: 0 0 32px; max-width: 500px;
            }

            .hero-pills { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 0; }
            .hero-pill {
                display: inline-flex; align-items: center; gap: 7px;
                background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.14);
                color: #e2e8f0; font-size: 12px; font-weight: 600;
                padding: 7px 14px; border-radius: 999px;
            }
            .hero-pill-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

            .hero-stats-card {
                background: rgba(255,255,255,0.07);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255,255,255,0.12);
                border-radius: 24px;
                padding: 32px 36px;
                text-align: center;
                min-width: 200px;
                flex-shrink: 0;
            }
            .hero-stats-icon {
                width: 56px; height: 56px;
                background: #2563eb; border-radius: 18px;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 18px;
            }
            .hero-stats-icon i { color: white; font-size: 22px; }
            .hero-stat-big-num {
                font-family: 'Playfair Display', serif;
                font-size: 3rem; font-weight: 900;
                color: #60a5fa; display: block; line-height: 1;
            }
            .hero-stat-big-lbl {
                font-size: 9px; font-weight: 800; letter-spacing: 0.2em;
                text-transform: uppercase; color: #94a3b8;
                margin-top: 6px; display: block;
            }
            .hero-stat-divider {
                border: none; border-top: 1px solid rgba(255,255,255,0.08);
                margin: 20px 0;
            }
            .hero-stat-mini-row { display: flex; gap: 32px; justify-content: center; }
            .hero-stat-mini { text-align: center; }
            .hero-stat-mini-num {
                font-family: 'Playfair Display', serif;
                font-size: 1.5rem; font-weight: 900;
                color: #e2e8f0; display: block; line-height: 1;
            }
            .hero-stat-mini-lbl {
                font-size: 9px; font-weight: 800; letter-spacing: 0.15em;
                text-transform: uppercase; color: #64748b;
                margin-top: 4px; display: block;
            }

            .hero-brand-tag {
                position: absolute; bottom: 28px; right: 2rem;
                font-size: 10px; font-weight: 800; letter-spacing: 0.22em;
                text-transform: uppercase; color: rgba(255,255,255,0.18);
                z-index: 10;
            }

            /* ── MAIN ── */
            .main-wrap { max-width: 1280px; margin: 0 auto; padding: 64px 2rem 80px; }

            /* ── FILTER PILL ── */
            .filter-pill {
                display: inline-flex; align-items: center; gap: 6px;
                background: #eff6ff; border: 1.5px solid #bfdbfe;
                color: #1d4ed8; font-size: 11px; font-weight: 700;
                padding: 5px 10px 5px 12px; border-radius: 999px;
            }
            .filter-pill a {
                background: rgba(29,78,216,0.12); width: 16px; height: 16px;
                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                color: #1d4ed8; font-size: 12px; text-decoration: none; line-height: 1; flex-shrink: 0;
            }

            /* ── JOB CARD ── */
            .job-card {
                background: white; border: 1px solid #f1f5f9; border-radius: 24px;
                padding: 28px; display: flex; flex-direction: column; gap: 20px;
                transition: all 0.35s cubic-bezier(0.16,1,0.3,1);
                position: relative; overflow: hidden;
                cursor: pointer;
            }
            .job-card::before {
                content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
                background: linear-gradient(90deg,#2563eb,#3b82f6);
                transform: scaleX(0); transform-origin: left;
                transition: transform 0.35s ease;
            }
            .job-card:hover { border-color: #bfdbfe; transform: translateY(-4px); box-shadow: 0 20px 48px rgba(15,23,42,0.09); }
            .job-card:hover::before { transform: scaleX(1); }
            .job-card:hover .card-icon { background: #eff6ff; border-color: #bfdbfe; }
            .job-card:hover .card-icon i { color: #2563eb; }
            .job-card:hover .card-title { color: #2563eb; }

            .card-icon {
                width: 48px; height: 48px; background: #f1f5f9;
                border: 1px solid #e2e8f0; border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0; transition: all 0.3s;
            }
            .card-title {
                font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 800;
                color: #0f172a; line-height: 1.25; transition: color 0.2s; margin: 0;
            }

            /* ── BADGE TIPO ESTABLECIMIENTO ── */
            .badge-restaurante {
                display: inline-flex; align-items: center; gap: 5px;
                font-size: 10px; font-weight: 800; letter-spacing: 0.08em;
                text-transform: uppercase; padding: 3px 10px; border-radius: 999px;
                background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;
            }
            .badge-gastrobar {
                display: inline-flex; align-items: center; gap: 5px;
                font-size: 10px; font-weight: 800; letter-spacing: 0.08em;
                text-transform: uppercase; padding: 3px 10px; border-radius: 999px;
                background: #fff8e1; color: #b45309; border: 1px solid #fde68a;
            }

            /* ── PAGINACIÓN ── */
            .pag-btn {
                width: 36px; height: 36px; border-radius: 50%;
                border: 1.5px solid #e2e8f0; background: #fff;
                display: inline-flex; align-items: center; justify-content: center;
                font-size: 13px; font-weight: 600; color: #475569;
                text-decoration: none; transition: all 0.2s;
            }
            .pag-btn:hover { border-color: #2563eb; color: #2563eb; }
            .pag-btn.active { background: #2563eb; border-color: #2563eb; color: #fff; }
            .pag-btn.disabled { color: #cbd5e1; pointer-events: none; }

            /* ── FOOTER ── */
            .site-footer { background: #0f172a; border-top: 1px solid rgba(255,255,255,0.05); padding: 64px 2rem 32px; }
            .footer-grid {
                max-width: 1280px; margin: 0 auto;
                display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 48px;
                padding-bottom: 48px; border-bottom: 1px solid rgba(255,255,255,0.07);
            }
            .footer-bottom {
                max-width: 1280px; margin: 0 auto; padding-top: 32px;
                display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
            }
            .footer-social-link {
                width: 34px; height: 34px; background: rgba(255,255,255,0.06);
                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                color: #94a3b8; text-decoration: none; transition: all 0.2s;
            }
            .footer-social-link:hover { background: rgba(37,99,235,0.2); color: #60a5fa; }
            .footer-link { color: #94a3b8; font-size: 13px; text-decoration: none; transition: color 0.2s; }
            .footer-link:hover { color: #60a5fa; }

            @media (max-width: 900px) {
                .hero-inner { flex-direction: column; align-items: flex-start; }
                .hero-stats-card { width: 100%; }
                .hero-brand-tag { display: none; }
            }
            @media (max-width: 768px) {
                .hero-section { min-height: auto; }
                .hero-left { max-width: 100%; }
                .main-wrap { padding: 40px 1.25rem 64px; }
                .footer-grid { grid-template-columns: 1fr; gap: 32px; }
                .footer-bottom { flex-direction: column; align-items: flex-start; }
                .jobs-grid { grid-template-columns: 1fr !important; }
            }
        </style>
    </head>
    <body>

        {{-- ══ NAVBAR ══ --}}
        <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 sm:h-20 items-center gap-2 sm:gap-4">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 no-underline">
                        <span class="text-base sm:text-xl font-bold tracking-tight premium-title italic text-slate-900">
                            Gastro<span class="text-blue-600">Nicaragua</span>
                        </span>
                    </a>

                    {{-- Search bar desktop --}}
                    <form action="{{ route('empleos.index') }}" method="GET"
                          class="hidden md:flex flex-1 max-w-2xl search-box">

                        {{-- 1. DESTINO --}}
                        <div class="search-segment" style="min-width:120px;">
                            <label for="search-departamento">
                                <i class="fas fa-map-marker-alt" style="color:#2563eb;"></i> Destino
                            </label>
                            <select id="search-departamento" name="departamento">
                                <option value="">Todos</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}"
                                        {{ (request('departamento') ?? $departamentoPredefinido) == $depto->id ? 'selected' : '' }}>
                                        {{ $depto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. MUNICIPIO --}}
                        <div class="search-segment" style="min-width:120px;">
                            <label for="search-municipio">
                                <i class="fas fa-city" style="color:#2563eb;"></i> Municipio
                            </label>
                            <select id="search-municipio" name="municipio"
                                    {{ (request('departamento') ?? $departamentoPredefinido) ? '' : 'disabled' }}>
                                <option value="">
                                    {{ (request('departamento') ?? $departamentoPredefinido) ? 'Todos' : 'Elige destino...' }}
                                </option>
                                @foreach($municipios as $mun)
                                    <option value="{{ $mun->id }}"
                                            data-departamento="{{ $mun->departamento_id }}"
                                            {{ (request('municipio') ?? $municipioPredefinido) == $mun->id ? 'selected' : '' }}
                                            style="{{ (request('departamento') ?? $departamentoPredefinido) && $mun->departamento_id == (request('departamento') ?? $departamentoPredefinido) ? '' : 'display:none' }}">
                                        {{ $mun->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. PUESTO --}}
                        <div class="search-segment" style="min-width:120px;">
                            <label for="search-puesto">
                                <i class="fas fa-search" style="color:#2563eb;"></i> Puesto
                            </label>
                            <input type="text" id="search-puesto" name="search"
                                   value="{{ request('search') }}" placeholder="Mesero, cocinero...">
                        </div>

                        <button type="submit" class="search-btn">
                            <i class="fas fa-search" style="font-size:12px;"></i>
                            <span>Buscar</span>
                        </button>
                    </form>

                    {{-- Acciones derecha --}}
                    <div class="flex items-center gap-1 sm:gap-2 shrink-0">

                        <button id="mobileSearchToggle"
                                class="md:hidden w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-blue-100 hover:text-blue-600 transition-colors border-0 cursor-pointer">
                            <i class="fas fa-search text-sm"></i>
                        </button>

                        <a href="{{ route('home') }}"
                           class="flex items-center gap-1.5 border border-slate-200 text-slate-600 bg-white px-3 py-2 rounded-full text-sm font-semibold hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm no-underline">
                            <i class="fas fa-home text-xs"></i>
                            <span class="hidden lg:inline">Inicio</span>
                        </a>

                        <a href="{{ route('empleos.index') }}"
                           class="flex items-center gap-1.5 border border-blue-600 text-white bg-blue-600 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-semibold shadow-sm no-underline justify-center">
                            <i class="fas fa-briefcase text-xs"></i>
                            <span class="hidden lg:inline">Empleos</span>
                        </a>

                        <a href="{{ route('contacto') }}"
                           class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-slate-100 sm:bg-transparent text-slate-600 hover:text-blue-600 transition-colors no-underline"
                           title="Contacto">
                            <i class="fas fa-envelope text-sm sm:hidden"></i>
                            <span class="hidden sm:inline text-sm font-semibold">Contacto</span>
                        </a>

                        @if (Route::has('login'))
                            @auth
                                @if(auth()->user()->email === 'admin@turismo.ni')
                                    <a href="{{ url('/dashboard') }}"
                                       class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-blue-50 sm:bg-transparent border border-blue-200 sm:border-0 text-blue-600 hover:text-blue-700 transition-colors no-underline"
                                       title="Panel Admin">
                                        <i class="fas fa-chart-line text-sm sm:hidden"></i>
                                        <span class="hidden sm:inline text-sm font-semibold">Panel</span>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-sm font-semibold text-slate-500 hover:text-red-500 transition-colors bg-transparent border-0 cursor-pointer px-1 sm:px-2">Salir</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors no-underline px-1 sm:px-2">Ingresar</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel búsqueda móvil --}}
            <div id="mobileSearchPanel">
                <form action="{{ route('empleos.index') }}" method="GET" class="flex flex-col gap-3">

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-blue-500"></i> Destino
                        </label>
                        <select id="mob-departamento" name="departamento" class="nav-select-mobile">
                            <option value="">Todos los destinos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}"
                                    {{ (request('departamento') ?? $departamentoPredefinido) == $depto->id ? 'selected' : '' }}>
                                    {{ $depto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-city text-blue-500"></i> Municipio
                        </label>
                        <select id="mob-municipio" name="municipio" class="nav-select-mobile"
                                {{ (request('departamento') ?? $departamentoPredefinido) ? '' : 'disabled' }}>
                            <option value="">
                                {{ (request('departamento') ?? $departamentoPredefinido) ? 'Todos' : 'Elige destino...' }}
                            </option>
                            @foreach($municipios as $mun)
                                <option value="{{ $mun->id }}"
                                        data-departamento="{{ $mun->departamento_id }}"
                                        {{ (request('municipio') ?? $municipioPredefinido) == $mun->id ? 'selected' : '' }}
                                        style="{{ (request('departamento') ?? $departamentoPredefinido) && $mun->departamento_id == (request('departamento') ?? $departamentoPredefinido) ? '' : 'display:none' }}">
                                    {{ $mun->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-search text-blue-500"></i> Puesto
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="nav-input-mobile" placeholder="Mesero, cocinero...">
                    </div>

                    <button type="submit"
                            class="bg-blue-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all flex items-center justify-center gap-2 border-0 cursor-pointer">
                        <i class="fas fa-search text-xs"></i> Buscar empleos
                    </button>
                </form>
            </div>
        </nav>

        {{-- ══ HERO ══ --}}
        <section class="hero-section" style="padding-top: 80px; padding-bottom: 80px;">
            <div class="hero-bg-img" style="background-image: url('{{ asset('img/hero-empleos.jpg') }}');"></div>
            <div class="hero-bg-overlay"></div>

            <div class="hero-inner">
                <div class="hero-left" data-aos="fade-right">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        Oportunidades Laborales
                    </div>
                    <h1 class="premium-title hero-title">
                        Nuestros<br>
                        <span>Empleos</span>
                    </h1>
                    <p class="hero-sub">
                        Desde el corazón de la cocina hasta la excelencia en el servicio —
                        encuentra tu lugar en la gastronomía nicaragüense.
                    </p>
                    <div class="hero-pills">
                        <span class="hero-pill">
                            <span class="hero-pill-dot" style="background:#22c55e;"></span>
                            Información verificada
                        </span>
                        <span class="hero-pill">
                            <span class="hero-pill-dot" style="background:#f59e0b;"></span>
                            Salarios actualizados
                        </span>
                        <span class="hero-pill">
                            <span class="hero-pill-dot" style="background:#60a5fa;"></span>
                            Postulación directa
                        </span>
                    </div>
                </div>

                <div class="hero-stats-card" data-aos="fade-left">
                    <div class="hero-stats-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <span class="hero-stat-big-num">{{ $empleos->total() }}</span>
                    <span class="hero-stat-big-lbl">Vacantes activas</span>
                    <hr class="hero-stat-divider">
                    <div class="hero-stat-mini-row">
                        <div class="hero-stat-mini">
                            <span class="hero-stat-mini-num">{{ $totalRestaurantes }}</span>
                            <span class="hero-stat-mini-lbl">Restaurantes</span>
                        </div>
                        <div class="hero-stat-mini">
                            <span class="hero-stat-mini-num">{{ $totalDepartamentos }}</span>
                            <span class="hero-stat-mini-lbl">Destinos</span>
                        </div>
                    </div>
                </div>
            </div>

            <span class="hero-brand-tag">Gastro Nicaragua</span>
            <div style="position:absolute;bottom:0;left:0;right:0;height:80px;background:linear-gradient(to bottom,transparent,#f8fafc);pointer-events:none;z-index:20;"></div>
        </section>

        {{-- ── MAIN ── --}}
        <main class="main-wrap">

            {{-- Filtros activos --}}
            @if(request('search') || request('departamento') || request('municipio'))
                <div style="margin-bottom:32px;display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:14px 20px;background:white;border:1px solid #e2e8f0;border-radius:16px;">
                    <span style="font-size:11px;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:#94a3b8;">Filtros:</span>

                    @if(request('departamento'))
                        <span class="filter-pill">
                            <i class="fas fa-map-marker-alt" style="font-size:9px;opacity:0.7;"></i>
                            {{ $departamentos->find(request('departamento'))?->nombre }}
                            <a href="{{ request()->fullUrlWithoutQuery(['departamento','municipio']) }}">×</a>
                        </span>
                    @endif
                    @if(request('municipio'))
                        <span class="filter-pill">
                            <i class="fas fa-city" style="font-size:9px;opacity:0.7;"></i>
                            {{ $municipios->find(request('municipio'))?->nombre }}
                            <a href="{{ request()->fullUrlWithoutQuery(['municipio']) }}">×</a>
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="filter-pill">
                            <i class="fas fa-search" style="font-size:9px;opacity:0.7;"></i>
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery(['search']) }}">×</a>
                        </span>
                    @endif

                    <a href="{{ route('empleos.index') }}"
                       style="margin-left:auto;font-size:12px;color:#2563eb;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-times-circle" style="font-size:10px;"></i> Limpiar todo
                    </a>
                </div>
            @endif

            {{-- Header sección --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
                <div>
                    <p style="font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;color:#2563eb;margin:0 0 4px;">
                        <i class="fas fa-fire-alt" style="margin-right:4px;"></i> Vacantes disponibles
                    </p>
                    <h2 class="premium-title" style="font-size:24px;font-weight:800;color:#0f172a;margin:0;">
                        Ofertas de empleo
                    </h2>
                </div>
                @if($empleos->total() > 0)
                    <span style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;font-size:11px;font-weight:700;padding:6px 14px;border-radius:999px;">
                        {{ $empleos->total() }} {{ $empleos->total() == 1 ? 'oferta' : 'ofertas' }}
                    </span>
                @endif
            </div>

            {{-- Grid de empleos --}}
            @forelse($empleos as $empleo)
                @if($loop->first)
                    <div class="jobs-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:20px;">
                @endif

                <article class="job-card"
                         data-aos="fade-up"
                         data-aos-delay="{{ ($loop->index % 3) * 80 }}"
                         onclick="window.location='{{ route('empleos.show', $empleo->id) }}'">

                    {{-- Encabezado --}}
                    <div style="display:flex;align-items:flex-start;gap:14px;">

                        <div class="card-icon" style="{{ $empleo->gastrobar_id ? 'background:#eff6ff;border-color:#bfdbfe;' : '' }}">
                            @if($empleo->gastrobar_id)
                                <i class="fas fa-glass-martini-alt" style="color:#1d4ed8;font-size:18px;"></i>
                            @else
                                <i class="fas fa-store" style="color:#94a3b8;font-size:18px;"></i>
                            @endif
                        </div>

                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:5px;">
                                <span style="font-size:10px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#2563eb;">
                                    @if($empleo->gastrobar_id)
                                        {{ $empleo->gastrobar?->nombre }}
                                    @else
                                        {{ $empleo->restaurante?->nombre }}
                                    @endif
                                </span>
                                @if($empleo->gastrobar_id)
                                    <span class="badge-gastrobar">
                                        <i class="fas fa-glass-martini-alt" style="font-size:9px;"></i> Gastrobar
                                    </span>
                                @else
                                    <span class="badge-restaurante">
                                        <i class="fas fa-store" style="font-size:9px;"></i> Restaurante
                                    </span>
                                @endif
                            </div>
                            <h3 class="card-title">{{ $empleo->titulo }}</h3>
                        </div>
                    </div>

                    <p style="color:#64748b;font-size:13px;line-height:1.7;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;margin:0;">
                        {{ $empleo->descripcion }}
                    </p>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @if($empleo->tipo_contrato)
                            <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;background:#f1f5f9;color:#475569;">
                                <i class="fas fa-clock" style="font-size:10px;"></i>
                                {{ $empleo->tipo_contrato }}
                            </span>
                        @endif

                        @if($empleo->salario)
                            <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">
                                <i class="fas fa-wallet" style="font-size:10px;"></i>
                                C$ {{ number_format($empleo->salario, 0) }}
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;">
                                <i class="fas fa-handshake" style="font-size:10px;"></i>
                                En entrevista
                            </span>
                        @endif

                        @if($empleo->departamento)
                            <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;background:#eff6ff;color:#1d4ed8;">
                                <i class="fas fa-map-marker-alt" style="font-size:10px;"></i>
                                {{ $empleo->departamento->nombre }}{{ $empleo->municipio ? ' · ' . $empleo->municipio->nombre : '' }}
                            </span>
                        @endif
                    </div>

                    <div style="padding-top:18px;border-top:1px solid #f1f5f9;margin-top:auto;">
                        <div style="display:flex;align-items:center;gap:6px;color:#94a3b8;font-size:11px;font-weight:500;">
                            <i class="far fa-calendar-alt"></i>
                            {{ $empleo->created_at->diffForHumans() }}
                        </div>
                    </div>

                </article>

                @if($loop->last)
                    </div>
                @endif

            @empty
                <div style="text-align:center;background:white;border:1px solid #e2e8f0;border-radius:28px;padding:64px 40px;max-width:480px;margin:0 auto;">
                    <div style="width:72px;height:72px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <i class="fas fa-briefcase" style="color:#cbd5e1;font-size:28px;"></i>
                    </div>
                    <h3 class="premium-title" style="font-size:22px;font-weight:800;color:#0f172a;margin-bottom:8px;">
                        No hay ofertas disponibles
                    </h3>
                    <p style="color:#64748b;font-size:14px;line-height:1.7;margin:0 0 24px;">
                        @if(request('search') || request('departamento') || request('municipio'))
                            No encontramos vacantes que coincidan con tus filtros. Intenta con otros términos.
                        @else
                            Los establecimientos aún no han publicado vacantes activas. ¡Vuelve pronto!
                        @endif
                    </p>
                    @if(request('search') || request('departamento') || request('municipio'))
                        <a href="{{ route('empleos.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:white;padding:11px 24px;border-radius:999px;font-size:13px;font-weight:700;text-decoration:none;">
                            <i class="fas fa-times" style="font-size:11px;"></i> Limpiar filtros
                        </a>
                    @endif
                </div>
            @endforelse

            {{-- Paginación --}}
            @if($empleos->hasPages())
                <div style="margin-top:56px;display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;">

                    @if($empleos->onFirstPage())
                        <span class="pag-btn disabled"><i class="fas fa-chevron-left" style="font-size:10px;"></i></span>
                    @else
                        <a href="{{ $empleos->previousPageUrl() }}" class="pag-btn">
                            <i class="fas fa-chevron-left" style="font-size:10px;"></i>
                        </a>
                    @endif

                    @php
                        $current = $empleos->currentPage();
                        $last    = $empleos->lastPage();
                        $pages   = [];
                        for ($i = 1; $i <= $last; $i++) {
                            if ($i === 1 || $i === $last || abs($i - $current) <= 1) {
                                $pages[] = $i;
                            } elseif (end($pages) !== '…') {
                                $pages[] = '…';
                            }
                        }
                    @endphp

                    @foreach($pages as $page)
                        @if($page === '…')
                            <span style="color:#94a3b8;font-size:13px;padding:0 2px;">…</span>
                        @else
                            <a href="{{ $empleos->url($page) }}"
                               class="pag-btn {{ $page == $current ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if($empleos->hasMorePages())
                        <a href="{{ $empleos->nextPageUrl() }}" class="pag-btn">
                            <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                        </a>
                    @else
                        <span class="pag-btn disabled"><i class="fas fa-chevron-right" style="font-size:10px;"></i></span>
                    @endif

                </div>
            @endif
        </main>

        {{-- ══ FOOTER ══ --}}
        <footer class="bg-slate-900 text-slate-300 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 pt-12 pb-8 sm:pt-16 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 mb-10">
                    <div class="sm:col-span-2 lg:col-span-4 space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-blue-500">Nicaragua</span></span>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed font-light">
                            La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                            Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                        </p>
                        <div class="flex items-center gap-3 pt-1">
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Portal</h4>
                        <ul class="space-y-2.5 text-sm p-0 list-none m-0">
                            <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Inicio</a></li>
                            <li><a href="{{ route('restaurantes.index') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Restaurantes</a></li>
                            <li><a href="{{ route('gastrobares.index') }}" class="text-slate-400 hover:text-indigo-400 transition-all inline-block no-underline">Gastrobares</a></li>
                            <li><a href="{{ route('empleos.index') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Bolsa de Empleos</a></li>
                            <li><a href="{{ route('contacto') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="lg:col-span-3 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Destinos Destacados</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm text-slate-400 font-light">
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Masaya</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Granada</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>León</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>San Juan del Sur</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Estelí</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Matagalpa</span>
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-800 pt-6 text-center text-xs text-slate-500 font-light flex flex-col sm:flex-row justify-between items-center gap-3">
                    <p>&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-slate-500 hover:text-slate-400 no-underline">Política de Privacidad</a>
                        <a href="#" class="text-slate-500 hover:text-slate-400 no-underline">Términos de Servicio</a>
                    </div>
                </div>
            </div>
        </footer>

        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 800, once: true });

            function configurarMunicipios(deptoId, munId) {
                const deptoSel = document.getElementById(deptoId);
                const munSel   = document.getElementById(munId);
                if (!deptoSel || !munSel) return;

                const munOpts = Array.from(munSel.querySelectorAll('option[data-departamento]'));

                function actualizar() {
                    const depto = deptoSel.value;
                    munOpts.forEach(opt => {
                        opt.style.display = (!depto || opt.dataset.departamento === depto) ? '' : 'none';
                        if (opt.style.display === 'none') opt.selected = false;
                    });
                    const ph = munSel.querySelector('option:not([data-departamento])');
                    if (depto) {
                        munSel.disabled = false;
                        if (ph) ph.textContent = 'Todos';
                    } else {
                        munSel.disabled = true;
                        munSel.value = '';
                        if (ph) ph.textContent = 'Elige destino...';
                    }
                }

                deptoSel.addEventListener('change', actualizar);
                actualizar();
            }

            configurarMunicipios('search-departamento', 'search-municipio');
            configurarMunicipios('mob-departamento', 'mob-municipio');

            const mobileSearchToggle = document.getElementById('mobileSearchToggle');
            const mobileSearchPanel  = document.getElementById('mobileSearchPanel');
            if (mobileSearchToggle && mobileSearchPanel) {
                mobileSearchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileSearchPanel.classList.toggle('open');
                });
                document.addEventListener('click', function(e) {
                    if (!mobileSearchPanel.contains(e.target) && e.target !== mobileSearchToggle)
                        mobileSearchPanel.classList.remove('open');
                });
            }
        </script>
    </body>
</html>
