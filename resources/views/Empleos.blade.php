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

            /* ══ SEARCH BOX ══ */
            .search-box {
                display: flex; align-items: stretch; background: #f8f7f6;
                border: 1.5px solid #e7e5e4; border-radius: 18px; overflow: hidden;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .search-box:focus-within {
                border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,0.10); background: #fff;
            }
            .search-segment {
                display: flex; flex-direction: column; justify-content: center;
                padding: 8px 16px; min-width: 0; flex: 1; position: relative;
            }
            .search-segment + .search-segment::before {
                content: ''; position: absolute; left: 0; top: 20%;
                height: 60%; width: 1px; background: #e7e5e4;
            }
            .search-segment label {
                font-size: 9px; font-weight: 900; letter-spacing: 0.16em;
                text-transform: uppercase; color: #a8a29e; margin-bottom: 2px;
                display: flex; align-items: center; gap: 4px; cursor: pointer;
            }
            .search-segment select,
            .search-segment input {
                background: transparent; border: none; outline: none;
                font-size: 13px; font-weight: 600; color: #1c1917;
                font-family: 'Instrument Sans', sans-serif; width: 100%; padding: 0; cursor: pointer;
            }
            .search-segment select option { font-weight: 500; }
            .search-segment input::placeholder { color: #c4bfbb; font-weight: 500; }

            .search-btn {
                display: flex; align-items: center; gap: 7px;
                background: #ea580c; color: white; border: none;
                padding: 0 20px; font-size: 13px; font-weight: 700;
                cursor: pointer; transition: background 0.2s;
                white-space: nowrap; border-radius: 0 16px 16px 0; flex-shrink: 0;
            }
            .search-btn:hover { background: #c2410c; }

            /* ── PANEL BÚSQUEDA MÓVIL ── */
            #mobileSearchPanel {
                display: none;
                position: absolute; top: 100%; left: 0; right: 0;
                background: rgba(255,255,255,0.98); backdrop-filter: blur(12px);
                border-top: 1px solid #e7e5e4; padding: 1rem 1.25rem;
                z-index: 40; box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            }
            #mobileSearchPanel.open { display: block; }

            .nav-select-mobile {
                background: #f8f7f6; border: 1.5px solid #e7e5e4; border-radius: 12px;
                padding: 10px 14px; font-size: 13px; color: #1c1917; appearance: none;
                cursor: pointer; transition: all 0.2s; outline: none; width: 100%;
                font-family: 'Instrument Sans', sans-serif; font-weight: 600;
            }
            .nav-select-mobile:focus { border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,0.12); background: #fff; }

            .nav-input-mobile {
                background: #f8f7f6; border: 1.5px solid #e7e5e4; border-radius: 12px;
                padding: 10px 14px; font-size: 13px; color: #1c1917;
                transition: all 0.2s; outline: none; width: 100%;
                font-family: 'Instrument Sans', sans-serif; font-weight: 600;
            }
            .nav-input-mobile:focus { border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,0.12); background: #fff; }
            .nav-input-mobile::placeholder { color: #c4bfbb; }

            /* ── HERO ── */
            .hero-section {
                background: linear-gradient(160deg, #1a0800 0%, #0c0a09 60%, #1c1410 100%);
                padding: 140px 2rem 80px; position: relative; overflow: hidden;
            }
            .hero-section::before {
                content: ''; position: absolute; top: -80px; right: -80px;
                width: 500px; height: 500px;
                background: radial-gradient(circle, rgba(234,88,12,0.18) 0%, transparent 70%);
                pointer-events: none;
            }
            .hero-section::after {
                content: ''; position: absolute; bottom: -60px; left: 20%;
                width: 400px; height: 400px;
                background: radial-gradient(circle, rgba(251,146,60,0.07) 0%, transparent 70%);
                pointer-events: none;
            }
            .hero-dots {
                position: absolute; inset: 0;
                background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
                background-size: 22px 22px; pointer-events: none;
            }
            .hero-inner { max-width: 1280px; margin: 0 auto; position: relative; z-index: 10; text-align: center; }
            .hero-badge {
                display: inline-flex; align-items: center; gap: 8px;
                background: rgba(234,88,12,0.15); border: 1px solid rgba(234,88,12,0.3);
                color: #fb923c; font-size: 10px; font-weight: 800; letter-spacing: 0.2em;
                text-transform: uppercase; padding: 8px 20px; border-radius: 999px; margin-bottom: 28px;
            }
            .hero-title { font-size: clamp(2.4rem, 5.5vw, 5rem); font-weight: 900; color: white; line-height: 1.08; margin-bottom: 20px; }
            .hero-title span {
                color: transparent;
                background: linear-gradient(90deg, #fb923c, #f59e0b);
                -webkit-background-clip: text; background-clip: text;
            }
            .hero-sub { color: #a8a29e; font-size: 15px; max-width: 540px; margin: 0 auto 48px; line-height: 1.75; font-weight: 400; }
            .stats-row {
                display: inline-flex; flex-wrap: wrap; justify-content: center;
                background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
                border-radius: 20px; padding: 24px 40px; backdrop-filter: blur(8px);
            }
            .stat-item { padding: 0 40px; text-align: center; position: relative; }
            .stat-item + .stat-item::before {
                content: ''; position: absolute; left: 0; top: 10%; bottom: 10%;
                width: 1px; background: rgba(255,255,255,0.08);
            }
            .stat-num { font-size: 2.5rem; font-weight: 900; color: #fb923c; line-height: 1; display: block; }
            .stat-label { font-size: 9px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase; color: #78716c; margin-top: 8px; display: block; }

            /* ── MAIN ── */
            .main-wrap { max-width: 1280px; margin: 0 auto; padding: 64px 2rem 80px; }

            /* ── FILTER PILL ── */
            .filter-pill {
                display: inline-flex; align-items: center; gap: 6px;
                background: #fff7ed; border: 1.5px solid #fed7aa;
                color: #c2410c; font-size: 11px; font-weight: 700;
                padding: 5px 10px 5px 12px; border-radius: 999px;
            }
            .filter-pill a {
                background: rgba(194,65,12,0.12); width: 16px; height: 16px;
                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                color: #c2410c; font-size: 12px; text-decoration: none; line-height: 1; flex-shrink: 0;
            }

            /* ── JOB CARD ── */
            .job-card {
                background: white; border: 1px solid #f1f0ee; border-radius: 24px;
                padding: 28px; display: flex; flex-direction: column; gap: 20px;
                transition: all 0.35s cubic-bezier(0.16,1,0.3,1);
                position: relative; overflow: hidden;
            }
            .job-card::before {
                content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
                background: linear-gradient(90deg,#ea580c,#f59e0b);
                transform: scaleX(0); transform-origin: left;
                transition: transform 0.35s ease;
            }
            .job-card:hover { border-color: #fed7aa; transform: translateY(-4px); box-shadow: 0 20px 48px rgba(28,25,23,0.09); }
            .job-card:hover::before { transform: scaleX(1); }
            .job-card:hover .card-icon { background: #fff7ed; border-color: #fed7aa; }
            .job-card:hover .card-icon i { color: #ea580c; }
            .job-card:hover .card-title { color: #ea580c; }
            .job-card:hover .btn-oferta { background: #ea580c; box-shadow: 0 6px 20px rgba(234,88,12,0.3); }

            .card-icon {
                width: 48px; height: 48px; background: #f5f5f4;
                border: 1px solid #e7e5e4; border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0; transition: all 0.3s;
            }
            .card-title {
                font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 800;
                color: #1c1917; line-height: 1.25; transition: color 0.2s; margin: 0;
            }
            .btn-oferta {
                display: inline-flex; align-items: center; gap: 6px;
                background: #0c0a09; color: white; text-decoration: none;
                font-size: 12px; font-weight: 700; padding: 9px 18px; border-radius: 999px;
                transition: all 0.25s ease;
            }

            /* ── PAGINACIÓN ── */
            .pag-btn {
                width: 36px; height: 36px; border-radius: 50%;
                border: 1.5px solid #e7e5e4; background: #fff;
                display: inline-flex; align-items: center; justify-content: center;
                font-size: 13px; font-weight: 600; color: #57534e;
                text-decoration: none; transition: all 0.2s;
            }
            .pag-btn:hover { border-color: #ea580c; color: #ea580c; }
            .pag-btn.active { background: #ea580c; border-color: #ea580c; color: #fff; }
            .pag-btn.disabled { color: #d4cfc9; pointer-events: none; }

            /* ── FOOTER ── */
            .site-footer { background: #0c0a09; border-top: 1px solid rgba(255,255,255,0.05); padding: 64px 2rem 32px; }
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
                color: #78716c; text-decoration: none; transition: all 0.2s;
            }
            .footer-social-link:hover { background: rgba(234,88,12,0.2); color: #fb923c; }
            .footer-link { color: #78716c; font-size: 13px; text-decoration: none; transition: color 0.2s; }
            .footer-link:hover { color: #fb923c; }

            @media (max-width: 768px) {
                .stats-row { padding: 20px 24px; }
                .stat-item { padding: 0 20px; }
                .stat-num { font-size: 2rem; }
                .hero-section { padding: 120px 1.25rem 64px; }
                .main-wrap { padding: 40px 1.25rem 64px; }
                .footer-grid { grid-template-columns: 1fr; gap: 32px; }
                .footer-bottom { flex-direction: column; align-items: flex-start; }
                .jobs-grid { grid-template-columns: 1fr !important; }
            }
        </style>
    </head>
    <body>

        {{-- ══ NAVBAR (idéntico al de la home) ══ --}}
        <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 sm:h-20 items-center gap-2 sm:gap-4">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 no-underline">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                            <i class="fas fa-utensils text-white text-xs sm:text-sm"></i>
                        </div>
                        <span class="text-base sm:text-xl font-bold tracking-tight premium-title italic text-stone-900">
                            Gastro<span class="text-orange-600">Nicaragua</span>
                        </span>
                    </a>

                    {{-- Search bar desktop --}}
                    <form action="{{ route('empleos.index') }}" method="GET"
                          class="hidden md:flex flex-1 max-w-2xl search-box">
                        <div class="search-segment" style="min-width:130px;">
                            <label for="search-puesto">
                                <i class="fas fa-search" style="color:#ea580c;"></i> Puesto
                            </label>
                            <input type="text" id="search-puesto" name="search"
                                   value="{{ request('search') }}" placeholder="Mesero, cocinero...">
                        </div>
                        <div class="search-segment" style="min-width:130px;">
                            <label for="search-departamento">
                                <i class="fas fa-map-marker-alt" style="color:#ea580c;"></i> Destino
                            </label>
                            <select id="search-departamento" name="departamento">
                                <option value="">Todos los destinos</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                        {{ $depto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search" style="font-size:12px;"></i>
                            <span>Buscar</span>
                        </button>
                    </form>

                    {{-- Acciones derecha --}}
                    <div class="flex items-center gap-1 sm:gap-2 shrink-0">

                        {{-- Lupa móvil --}}
                        <button id="mobileSearchToggle"
                                class="md:hidden w-9 h-9 rounded-full bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-orange-100 hover:text-orange-600 transition-colors border-0 cursor-pointer">
                            <i class="fas fa-search text-sm"></i>
                        </button>

                        {{-- Restaurantes --}}
                        <a href="{{ route('restaurantes.index') }}"
                           class="flex items-center gap-1.5 border border-orange-200 text-orange-600 bg-orange-50 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-semibold hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all shadow-sm no-underline justify-center">
                            <i class="fas fa-store text-xs"></i>
                            <span class="hidden lg:inline">Restaurantes</span>
                        </a>

                        {{-- Empleos (activo) --}}
                        <a href="{{ route('empleos.index') }}"
                           class="flex items-center gap-1.5 border border-orange-600 text-white bg-orange-600 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-semibold shadow-sm no-underline justify-center">
                            <i class="fas fa-briefcase text-xs"></i>
                            <span class="hidden lg:inline">Empleos</span>
                        </a>

                        {{-- Contacto --}}
                        <a href="{{ route('contacto') }}"
                           class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-stone-100 sm:bg-transparent text-stone-600 hover:text-orange-600 transition-colors no-underline"
                           title="Contacto">
                            <i class="fas fa-envelope text-sm sm:hidden"></i>
                            <span class="hidden sm:inline text-sm font-semibold">Contacto</span>
                        </a>

                        @if (Route::has('login'))
                            @auth
                                @if(auth()->user()->email === 'admin@turismo.ni')
                                    <a href="{{ url('/dashboard') }}"
                                       class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-orange-50 sm:bg-transparent border border-orange-200 sm:border-0 text-orange-600 hover:text-orange-700 transition-colors no-underline"
                                       title="Panel Admin">
                                        <i class="fas fa-chart-line text-sm sm:hidden"></i>
                                        <span class="hidden sm:inline text-sm font-semibold">Panel</span>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-sm font-semibold text-stone-500 hover:text-red-500 transition-colors bg-transparent border-0 cursor-pointer px-1 sm:px-2">Salir</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline px-1 sm:px-2">Ingresar</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel búsqueda móvil --}}
            <div id="mobileSearchPanel">
                <form action="{{ route('empleos.index') }}" method="GET" class="flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                            <i class="fas fa-search text-orange-500"></i> Puesto
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="nav-input-mobile" placeholder="Mesero, cocinero...">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-orange-500"></i> Destino
                        </label>
                        <select name="departamento" class="nav-select-mobile">
                            <option value="">Todos los destinos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                    {{ $depto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="bg-orange-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-orange-700 transition-all flex items-center justify-center gap-2 border-0 cursor-pointer">
                        <i class="fas fa-search text-xs"></i> Buscar empleos
                    </button>
                </form>
            </div>
        </nav>

        {{-- ── HERO ── --}}
        <section class="hero-section" style="padding-top: calc(140px + 64px);">
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
                <div style="margin-bottom:32px;display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:14px 20px;background:white;border:1px solid #e7e5e4;border-radius:16px;">
                    <span style="font-size:11px;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:#a8a29e;">Filtros:</span>

                    @if(request('search'))
                        <span class="filter-pill">
                            <i class="fas fa-search" style="font-size:9px;opacity:0.7;"></i>
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery(['search']) }}">×</a>
                        </span>
                    @endif
                    @if(request('departamento'))
                        <span class="filter-pill">
                            <i class="fas fa-map-marker-alt" style="font-size:9px;opacity:0.7;"></i>
                            {{ $departamentos->find(request('departamento'))?->nombre }}
                            <a href="{{ request()->fullUrlWithoutQuery(['departamento']) }}">×</a>
                        </span>
                    @endif

                    <a href="{{ route('empleos.index') }}"
                       style="margin-left:auto;font-size:12px;color:#ea580c;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-times-circle" style="font-size:10px;"></i> Limpiar todo
                    </a>
                </div>
            @endif

            {{-- Header sección --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
                <div>
                    <p style="font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;color:#ea580c;margin:0 0 4px;">
                        <i class="fas fa-fire-alt" style="margin-right:4px;"></i> Vacantes disponibles
                    </p>
                    <h2 class="premium-title" style="font-size:24px;font-weight:800;color:#1c1917;margin:0;">
                        Ofertas de empleo
                    </h2>
                </div>
                @if($empleos->total() > 0)
                    <span style="background:#fff7ed;border:1px solid #fed7aa;color:#c2410c;font-size:11px;font-weight:700;padding:6px 14px;border-radius:999px;">
                        {{ $empleos->total() }} {{ $empleos->total() == 1 ? 'oferta' : 'ofertas' }}
                    </span>
                @endif
            </div>

            {{-- Grid de empleos --}}
            @forelse($empleos as $empleo)
                @if($loop->first)
                    <div class="jobs-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:20px;">
                @endif

                <article class="job-card">

                    {{-- Encabezado --}}
                    <div style="display:flex;align-items:flex-start;gap:14px;">
                        <div class="card-icon">
                            <i class="fas fa-store" style="color:#a8a29e;font-size:18px;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <span style="font-size:10px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#ea580c;display:block;margin-bottom:5px;">
                                {{ $empleo->restaurante?->nombre }}
                            </span>
                            <h3 class="card-title">{{ $empleo->titulo }}</h3>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <p style="color:#78716c;font-size:13px;line-height:1.7;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;margin:0;">
                        {{ $empleo->descripcion }}
                    </p>

                    {{-- Badges --}}
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @if($empleo->tipo_contrato)
                            <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;background:#f5f5f4;color:#57534e;">
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
                            <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;background:#f5f5f4;color:#78716c;border:1px solid #e7e5e4;">
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

                    {{-- Footer card --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;padding-top:18px;border-top:1px solid #f5f5f4;margin-top:auto;">
                        <div style="display:flex;align-items:center;gap:6px;color:#a8a29e;font-size:11px;font-weight:500;">
                            <i class="far fa-calendar-alt"></i>
                            {{ $empleo->created_at->diffForHumans() }}
                        </div>
                        <a href="{{ route('empleos.show', $empleo->id) }}" class="btn-oferta">
                            Ver oferta
                            <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                    </div>
                </article>

                @if($loop->last)
                    </div>
                @endif

            @empty
                <div style="text-align:center;background:white;border:1px solid #e7e5e4;border-radius:28px;padding:64px 40px;max-width:480px;margin:0 auto;">
                    <div style="width:72px;height:72px;background:#fafaf9;border:1px solid #e7e5e4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <i class="fas fa-briefcase" style="color:#d6d3d1;font-size:28px;"></i>
                    </div>
                    <h3 class="premium-title" style="font-size:22px;font-weight:800;color:#1c1917;margin-bottom:8px;">
                        No hay ofertas disponibles
                    </h3>
                    <p style="color:#78716c;font-size:14px;line-height:1.7;margin:0 0 24px;">
                        @if(request('search') || request('departamento'))
                            No encontramos vacantes que coincidan con tus filtros. Intenta con otros términos.
                        @else
                            Los establecimientos aún no han publicado vacantes activas. ¡Vuelve pronto!
                        @endif
                    </p>
                    @if(request('search') || request('departamento'))
                        <a href="{{ route('empleos.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;background:#ea580c;color:white;padding:11px 24px;border-radius:999px;font-size:13px;font-weight:700;text-decoration:none;">
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
                            <span style="color:#a8a29e;font-size:13px;padding:0 2px;">…</span>
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

        {{-- ── FOOTER ── --}}
        <footer class="site-footer">
            <div class="footer-grid">
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
                        La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                    </p>
                    <div style="display:flex;gap:12px;">
                        <a href="#" class="footer-social-link"><i class="fab fa-facebook-f" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social-link"><i class="fab fa-instagram" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social-link"><i class="fab fa-tiktok" style="font-size:12px;"></i></a>
                    </div>
                </div>
                <div>
                    <h4 style="color:white;font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;margin:0 0 20px;">Portal</h4>
                    <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:12px;">
                        <li><a href="{{ route('home') }}" class="footer-link">Inicio</a></li>
                        <li><a href="{{ route('restaurantes.index') }}" class="footer-link">Restaurantes</a></li>
                        <li><a href="{{ route('empleos.index') }}" style="color:#ea580c;font-size:13px;font-weight:600;text-decoration:none;">Bolsa de Empleos</a></li>
                        <li><a href="{{ route('contacto') }}" class="footer-link">Contacto</a></li>
                    </ul>
                </div>
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
            </div>
            <div class="footer-bottom">
                <p style="color:#57534e;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;margin:0;">
                    © {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.
                </p>
                <div style="display:flex;gap:24px;">
                    <a href="#" class="footer-link" style="font-size:11px;">Política de Privacidad</a>
                    <a href="#" class="footer-link" style="font-size:11px;">Términos de Servicio</a>
                </div>
            </div>
        </footer>

        <script>
            // ── Toggle búsqueda móvil ──
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