{{-- resources/views/restaurantes/public_index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurantes | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- ▼ TODOS LOS RESTAURANTES PARA EL FILTRO JS ▼ --}}
    <script>
        window.__RESTAURANTES__ = @json($restaurantes->values());
    </script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; scroll-behavior: smooth; }
        .premium-title { font-family: 'Playfair Display', serif; }

        @keyframes heroFloat {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }
        .hero-icon { animation: heroFloat 4s ease-in-out infinite; }

        @keyframes pulse-dot {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.5; transform:scale(1.7); }
        }

        .page-link { color: #2563eb; border-radius: 50%; margin: 0 3px; }
        .page-item.active .page-link { background-color: #2563eb; border-color: #2563eb; }

        /* ══ SEARCH BOX (desktop) ══ */
        .search-box {
            display: flex; align-items: stretch;
            background: #f4f7fb; border: 1.5px solid #e2e8f0;
            border-radius: 18px; overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-box:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
            background: #fff;
        }
        .search-segment {
            display: flex; flex-direction: column; justify-content: center;
            padding: 7px 12px; min-width: 0; flex: 1; position: relative;
        }
        .search-segment + .search-segment::before {
            content: ''; position: absolute; left: 0; top: 20%;
            height: 60%; width: 1px; background: #e2e8f0;
        }
        .search-segment label {
            font-size: 9px; font-weight: 900; letter-spacing: 0.14em;
            text-transform: uppercase; color: #94a3b8; margin-bottom: 2px;
            display: flex; align-items: center; gap: 4px; cursor: pointer;
            white-space: nowrap;
        }
        .search-segment select,
        .search-segment input {
            background: transparent; border: none; outline: none;
            font-size: 12px; font-weight: 600; color: #0f172a;
            font-family: 'Instrument Sans', sans-serif; width: 100%; padding: 0; cursor: pointer;
        }
        .search-segment select option { font-weight: 500; }
        .search-segment input::placeholder { color: #cbd5e1; font-weight: 500; }
        .search-segment select:disabled { opacity: 0.45; cursor: not-allowed; }

        .search-btn {
            display: flex; align-items: center; gap: 6px;
            background: #2563eb; color: white; border: none;
            padding: 0 18px; font-size: 13px; font-weight: 700;
            cursor: pointer; transition: background 0.2s;
            white-space: nowrap; border-radius: 0 16px 16px 0; flex-shrink: 0;
        }
        .search-btn:hover { background: #1d4ed8; }

        /* ══ PANEL BÚSQUEDA MÓVIL ══ */
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

        /* ══ GLASS CARD ══ */
        .glass-card {
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 2rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08);
            border-color: #bfdbfe;
        }

        .rest-card-img {
            height: 200px; overflow: hidden; border-radius: 1.8rem 1.8rem 0 0;
            position: relative; background: #e2e8f0;
        }
        @media (min-width: 768px) {
            .rest-card-img { height: 100%; min-height: 220px; border-radius: 0 2rem 2rem 0; }
            .rest-card-body { border-radius: 2rem 0 0 2rem; }
        }

        .filter-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: #eff6ff; border: 1.5px solid #bfdbfe;
            color: #1d4ed8; font-size: 11px; font-weight: 700;
            padding: 5px 10px 5px 12px; border-radius: 999px;
        }
        .filter-pill a {
            background: rgba(29,78,216,0.12); width: 16px; height: 16px;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; color: #1d4ed8; font-size: 12px;
            text-decoration: none; line-height: 1; flex-shrink: 0;
        }

        .pag-btn {
            width: 36px; height: 36px; border-radius: 50%;
            border: 1.5px solid #e2e8f0; background: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600; color: #475569;
            text-decoration: none; transition: all 0.2s; cursor: pointer;
        }
        .pag-btn:hover { border-color: #2563eb; color: #2563eb; }
        .pag-btn.active { background: #2563eb; border-color: #2563eb; color: #fff; }
        .pag-btn.disabled { color: #cbd5e1; pointer-events: none; }

        .social-btn {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: all 0.2s; flex-shrink: 0;
            position: relative; z-index: 2;
        }
        .social-btn:hover { transform: scale(1.15); }

        .badge-abierto {
            display: inline-flex; align-items: center; gap: 5px;
            background: #f0fdf4; border: 1.5px solid #bbf7d0;
            color: #15803d; font-size: 10px; font-weight: 800;
            padding: 3px 8px; border-radius: 999px;
            letter-spacing: 0.04em; white-space: nowrap;
        }
        .badge-cerrado {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fef2f2; border: 1.5px solid #fecaca;
            color: #b91c1c; font-size: 10px; font-weight: 800;
            padding: 3px 8px; border-radius: 999px;
            letter-spacing: 0.04em; white-space: nowrap;
        }
        .badge-sin-horario {
            display: inline-flex; align-items: center; gap: 5px;
            background: #f8fafc; border: 1.5px solid #e2e8f0;
            color: #94a3b8; font-size: 10px; font-weight: 700;
            padding: 3px 8px; border-radius: 999px;
            letter-spacing: 0.04em; white-space: nowrap;
        }
        .dot-abierto {
            width: 6px; height: 6px; border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 2px rgba(34,197,94,0.25);
            animation: pulseDot 2s infinite;
            display: inline-block; flex-shrink: 0;
        }
        .dot-cerrado {
            width: 6px; height: 6px; border-radius: 50%;
            background: #ef4444;
            display: inline-block; flex-shrink: 0;
        }
        @keyframes pulseDot {
            0%,100% { box-shadow: 0 0 0 2px rgba(34,197,94,0.25); }
            50%      { box-shadow: 0 0 0 4px rgba(34,197,94,0.12); }
        }

        /* ══ SECTION RESTAURANTES — HEADER ══ */
        .rest-ghost-text {
            font-family: 'Playfair Display', serif; font-weight: 900;
            font-size: clamp(4rem, 15vw, 13rem); line-height: 1; color: transparent;
            -webkit-text-stroke: 1.5px rgba(37,99,235,0.07); letter-spacing: -0.04em;
            position: absolute; top: -1rem; left: -0.5rem;
            pointer-events: none; user-select: none; white-space: nowrap; z-index: 0;
        }
        .rest-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: #eff6ff; border: 1.5px solid #bfdbfe;
            color: #1d4ed8; font-size: 10px; font-weight: 800;
            letter-spacing: 0.2em; text-transform: uppercase;
            padding: 7px 20px; border-radius: 999px;
        }
        .rest-pill .dot {
            width: 7px; height: 7px; background: #2563eb; border-radius: 50%;
            animation: pulse-dot 1.6s ease-in-out infinite; flex-shrink: 0;
        }
        .rest-divider-icon {
            width: 38px; height: 38px; background: #2563eb; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 0 8px rgba(37,99,235,0.08); flex-shrink: 0;
        }

        @keyframes draw-underline {
            from { stroke-dashoffset: 400; }
            to   { stroke-dashoffset: 0; }
        }
        .rest-heading {
            font-family: 'Playfair Display', serif; font-weight: 900;
            font-size: clamp(1.8rem, 6vw, 4.5rem); line-height: 1.02;
            letter-spacing: -0.035em; color: #0f172a; margin: 0;
        }
        .rest-heading em { font-style: italic; color: #2563eb; position: relative; display: inline-block; }
        .underline-svg {
            position: absolute; bottom: -10px; left: 0; width: 100%; overflow: visible;
            stroke-dasharray: 400; stroke-dashoffset: 400;
            animation: draw-underline 1.3s cubic-bezier(0.4,0,0.2,1) 0.4s forwards;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    {{-- ══════════════ NAVBAR ══════════════ --}}
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center gap-3">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 no-underline">
                    <span class="text-xl font-bold tracking-tight premium-title italic text-slate-900 hidden lg:block">
                        Gastro<span class="text-blue-600">Nicaragua</span>
                    </span>
                </a>

                {{-- Search Bar desktop --}}
                <form action="{{ route('restaurantes.index') }}" method="GET"
                      class="hidden md:flex flex-1 search-box" style="min-width:0;">

                    {{-- 1. DESTINO --}}
                    <div class="search-segment">
                        <label for="nav-departamento">
                            <i class="fas fa-map-marker-alt" style="color:#2563eb;font-size:8px;"></i> Destino
                        </label>
                        <select id="nav-departamento" name="departamento">
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
                    <div class="search-segment">
                        <label for="nav-municipio">
                            <i class="fas fa-city" style="color:#2563eb;font-size:8px;"></i> Municipio
                        </label>
                        <select id="nav-municipio" name="municipio"
                                {{ (request('departamento') ?? $departamentoPredefinido) ? '' : 'disabled' }}>
                            <option value="">{{ (request('departamento') ?? $departamentoPredefinido) ? 'Todos' : 'Elige destino...' }}</option>
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

                    {{-- 3. ESPECIALIDAD --}}
                    <div class="search-segment">
                        <label for="nav-especialidad">
                            <i class="fas fa-utensils" style="color:#2563eb;font-size:8px;"></i> Especialidad
                        </label>
                        <input type="text" id="nav-especialidad" name="especialidad"
                               value="{{ request('especialidad') }}" placeholder="Asados...">
                    </div>

                    {{-- 4. LOCAL (select dinámico) --}}
                    <div class="search-segment">
                        <label for="nav-search">
                            <i class="fas fa-store" style="color:#2563eb;font-size:8px;"></i> Local
                        </label>
                        <select id="nav-search" name="search">
                            <option value="">Todos los locales</option>
                        </select>
                    </div>

                    <button type="submit" class="search-btn">
                        <i class="fas fa-search" style="font-size:11px;"></i>
                        <span>Buscar</span>
                    </button>
                </form>

                {{-- Botones de acción --}}
                <div class="flex items-center gap-2 shrink-0">

                    <button id="mobileSearchToggle"
                            class="md:hidden w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-blue-100 hover:text-blue-600 transition-colors border-0 cursor-pointer">
                        <i class="fas fa-search text-sm"></i>
                    </button>

                    <a href="{{ route('home') }}"
                       class="flex items-center gap-1.5 border border-slate-200 text-slate-600 bg-white px-3 py-2 rounded-full text-sm font-semibold hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm no-underline">
                        <i class="fas fa-home text-xs"></i>
                        <span class="hidden lg:inline">Inicio</span>
                    </a>

                    <a href="{{ route('restaurantes.index') }}"
                       class="flex items-center gap-1.5 border border-blue-600 text-white bg-blue-600 px-3 py-2 rounded-full text-sm font-semibold shadow-sm no-underline">
                        <i class="fas fa-store text-xs"></i>
                        <span class="hidden lg:inline">Restaurantes</span>
                    </a>


                    <a href="{{ route('contacto') }}"
                       class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 text-slate-600 hover:bg-blue-100 hover:text-blue-600 transition-colors no-underline lg:w-auto lg:h-auto lg:bg-transparent lg:rounded-none lg:px-2"
                       title="Contacto">
                        <i class="fas fa-envelope text-sm lg:hidden"></i>
                        <span class="hidden lg:inline text-sm font-semibold">Contacto</span>
                    </a>

                    @if (Route::has('login'))
                        @auth
                            @if(auth()->user()->email === 'admin@turismo.ni')
                                <a href="{{ url('/dashboard') }}"
                                   class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors no-underline px-2 hidden lg:inline">Panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                                @csrf
                                <button type="submit"
                                        class="text-sm font-semibold text-slate-500 hover:text-red-500 transition-colors bg-transparent border-0 cursor-pointer px-2">Salir</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors no-underline px-2">Ingresar</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel búsqueda móvil --}}
        <div id="mobileSearchPanel">
            <form action="{{ route('restaurantes.index') }}" method="GET" class="flex flex-col gap-3">

                {{-- 1. DESTINO --}}
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

                {{-- 2. MUNICIPIO --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                        <i class="fas fa-city text-blue-500"></i> Municipio
                    </label>
                    <select id="mob-municipio" name="municipio" class="nav-select-mobile"
                            {{ (request('departamento') ?? $departamentoPredefinido) ? '' : 'disabled' }}>
                        <option value="">{{ (request('departamento') ?? $departamentoPredefinido) ? 'Todos' : 'Elige destino...' }}</option>
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

                {{-- 3. ESPECIALIDAD --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                        <i class="fas fa-utensils text-blue-500"></i> Especialidad
                    </label>
                    <input type="text" id="mob-especialidad" name="especialidad" value="{{ request('especialidad') }}"
                           class="nav-input-mobile" placeholder="Asados, mariscos...">
                </div>

                {{-- 4. LOCAL (select dinámico) --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                        <i class="fas fa-store text-blue-500"></i> Local
                    </label>
                    <select id="mob-search" name="search" class="nav-select-mobile">
                        <option value="">Todos los locales</option>
                    </select>
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all flex items-center justify-center gap-2 border-0 cursor-pointer">
                    <i class="fas fa-search text-xs"></i> Buscar Restaurantes
                </button>
            </form>
        </div>
    </nav>

    {{-- ══════════════ HERO PREMIUM ══════════════ --}}
    <section class="pt-20">
        <div class="relative overflow-hidden" style="min-height:520px;">

            <div class="absolute inset-0"
                 style="background-image:url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1800&q=80');
                        background-size:cover;background-position:center;
                        transform:scale(1.04);transition:transform 8s ease;">
            </div>

            <div class="absolute inset-0" style="background:linear-gradient(135deg, rgba(9,9,50,0.92) 0%, rgba(9,9,50,0.75) 50%, rgba(37,99,235,0.40) 100%);"></div>

            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image:radial-gradient(circle, #fff 1px, transparent 1px);background-size:28px 28px;"></div>

            <div class="absolute top-0 right-0 w-[600px] h-[600px] opacity-20 pointer-events-none"
                 style="background:radial-gradient(circle at 70% 30%, #2563eb 0%, transparent 65%);"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] opacity-10 pointer-events-none"
                 style="background:radial-gradient(circle at 20% 80%, #3b82f6 0%, transparent 60%);"></div>

            <div class="relative max-w-7xl mx-auto px-4 py-24 sm:py-28 flex flex-col md:flex-row items-center justify-between gap-12">

                <div class="flex-1 max-w-2xl" data-aos="fade-right">

                    <div class="inline-flex items-center gap-2 mb-6"
                         style="background:rgba(37,99,235,0.15);border:1px solid rgba(37,99,235,0.4);
                                padding:6px 16px;border-radius:999px;">
                        <span style="width:7px;height:7px;background:#2563eb;border-radius:50%;display:inline-block;
                                     box-shadow:0 0 8px rgba(37,99,235,0.8);animation:pulse 2s infinite;"></span>
                        <span style="color:#93c5fd;font-size:11px;font-weight:800;letter-spacing:0.18em;text-transform:uppercase;">
                            <i class="fas fa-map-marker-alt mr-1" style="font-size:9px;"></i> Toda Nicaragua
                        </span>
                    </div>

                    <h1 class="premium-title mb-5 leading-tight"
                        style="font-size:clamp(2.8rem,5vw,4.2rem);font-weight:900;color:white;line-height:1.05;">
                        Nuestros<br>
                        <span style="color:#60a5fa;font-style:italic;font-weight:400;">Restaurantes</span>
                    </h1>

                    <p style="color:rgba(255,255,255,0.6);font-size:15px;line-height:1.8;max-width:520px;margin-bottom:32px;font-weight:300;">
                        Desde fritangas familiares hasta cocina gourmet — descubre cada sabor auténtico de Nicaragua en un solo lugar.
                    </p>

                    <div class="flex flex-wrap gap-3 mb-8">
                        <span style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);
                                     color:rgba(255,255,255,0.75);font-size:12px;font-weight:600;
                                     padding:8px 16px;border-radius:999px;display:inline-flex;align-items:center;gap:7px;
                                     backdrop-filter:blur(8px);">
                            <i class="fas fa-check-circle" style="color:#22c55e;font-size:11px;"></i> Información verificada
                        </span>
                        <span style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);
                                     color:rgba(255,255,255,0.75);font-size:12px;font-weight:600;
                                     padding:8px 16px;border-radius:999px;display:inline-flex;align-items:center;gap:7px;
                                     backdrop-filter:blur(8px);">
                            <i class="fas fa-clock" style="color:#f59e0b;font-size:11px;"></i> Horarios actualizados
                        </span>
                        <span style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);
                                     color:rgba(255,255,255,0.75);font-size:12px;font-weight:600;
                                     padding:8px 16px;border-radius:999px;display:inline-flex;align-items:center;gap:7px;
                                     backdrop-filter:blur(8px);">
                            <i class="fas fa-map-marker-alt" style="color:#60a5fa;font-size:11px;"></i> Ubicación en mapa
                        </span>
                    </div>

                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="height:1px;width:48px;background:linear-gradient(to right,#2563eb,transparent);"></div>
                        <span style="font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:rgba(255,255,255,0.3);">
                            Gastro Nicaragua
                        </span>
                    </div>
                </div>

                <div class="shrink-0 hidden md:flex flex-col items-center gap-5" data-aos="fade-left">

                    <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.14);
                                backdrop-filter:blur(20px);border-radius:28px;padding:36px 40px;
                                display:flex;flex-direction:column;align-items:center;gap:16px;
                                box-shadow:0 24px 64px rgba(0,0,0,0.3),inset 0 1px 0 rgba(255,255,255,0.1);
                                min-width:220px;">

                        <div style="width:80px;height:80px;background:linear-gradient(135deg,#2563eb,#3b82f6);
                                    border-radius:24px;display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 12px 32px rgba(37,99,235,0.5);
                                    animation:heroFloat 4s ease-in-out infinite;">
                            <i class="fas fa-utensils" style="color:white;font-size:32px;"></i>
                        </div>

                        <div style="text-align:center;">
                            <p style="color:white;font-size:28px;font-weight:900;line-height:1;margin-bottom:4px;">
                                {{ $restaurantes->total() }}
                            </p>
                            <p style="color:rgba(255,255,255,0.5);font-size:11px;font-weight:700;
                                      text-transform:uppercase;letter-spacing:0.15em;">
                                Restaurantes
                            </p>
                        </div>

                        <div style="width:100%;height:1px;background:rgba(255,255,255,0.08);"></div>

                        <div style="display:flex;gap:20px;">
                            <div style="text-align:center;">
                                <p style="color:#93c5fd;font-size:18px;font-weight:900;line-height:1;">17</p>
                                <p style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Deptos.</p>
                            </div>
                            <div style="width:1px;background:rgba(255,255,255,0.08);"></div>
                            <div style="text-align:center;">
                                <p style="color:#93c5fd;font-size:18px;font-weight:900;line-height:1;">153</p>
                                <p style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Munic.</p>
                            </div>
                        </div>
                    </div>

                    <p style="color:rgba(255,255,255,0.3);font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;">
                        Gastronomía auténtica 🇳🇮
                    </p>
                </div>

            </div>

            <div class="absolute bottom-0 left-0 right-0" style="height:80px;background:linear-gradient(to bottom,transparent,#f8fafc);"></div>
        </div>
    </section>

    <style>
        @keyframes pulse {
            0%,100% { opacity:1; box-shadow:0 0 8px rgba(37,99,235,0.8); }
            50%      { opacity:0.6; box-shadow:0 0 16px rgba(37,99,235,1); }
        }
    </style>

    {{-- ══════════════ MAIN ══════════════ --}}
    <main class="max-w-7xl mx-auto px-4 py-16">

        {{-- ── SECTION HEADER PREMIUM ── --}}
        <div class="relative mb-14 sm:mb-20" style="overflow:visible;">
            <div class="rest-ghost-text" aria-hidden="true">Restaurantes</div>

            <div class="relative z-10 mb-4">
                <span class="rest-pill">
                    <span class="dot"></span>
                    Descubre · Explora · Disfruta
                </span>
            </div>

            <div class="relative z-10">
                <h2 class="rest-heading">
                    Todos los&nbsp;<em>Restaurantes
                        <svg class="underline-svg" style="position:absolute;bottom:-10px;left:0;width:100%;"
                             height="14" viewBox="0 0 400 14" preserveAspectRatio="none">
                            <path d="M2 11 Q100 3 200 9 Q300 15 398 6"
                                  stroke="#2563eb" stroke-width="4" fill="none"
                                  stroke-linecap="round"
                                  stroke-dasharray="400" stroke-dashoffset="400"
                                  style="animation:draw-underline 1.3s cubic-bezier(0.4,0,0.2,1) 0.5s forwards;"/>
                        </svg>
                    </em>&nbsp;de Nicaragua
                </h2>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-end justify-between gap-3 mt-8">
                <p style="color:#64748b;font-size:0.92rem;max-width:480px;line-height:1.75;margin:0;">
                    Desde fritangas familiares hasta cocina gourmet — cada sabor auténtico de Nicaragua en un solo lugar.
                </p>
                <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;padding-bottom:2px;">
                    <div style="text-align:right;">
                        <div style="font-family:'Playfair Display',serif;font-weight:900;font-size:2rem;color:#2563eb;line-height:1;">
                            {{ $restaurantes->total() }}
                        </div>
                        <div style="font-size:9px;font-weight:800;letter-spacing:0.18em;text-transform:uppercase;color:#94a3b8;line-height:1.4;">
                            Locales<br>disponibles
                        </div>
                    </div>
                    <div style="width:2px;height:40px;background:linear-gradient(to bottom,#2563eb,transparent);border-radius:1px;"></div>
                </div>
            </div>

            <div class="relative z-10 flex items-center gap-4 mt-8">
                <div style="flex:1;height:1px;background:linear-gradient(to right,#e2e8f0,transparent);"></div>
                <div class="rest-divider-icon">
                    <i class="fas fa-utensils" style="color:#fff;font-size:13px;"></i>
                </div>
                <div style="flex:1;height:1px;background:linear-gradient(to left,#e2e8f0,transparent);"></div>
            </div>
        </div>

        {{-- Barra filtros activos + contador --}}
        <div class="mb-8 flex flex-wrap items-center gap-2">
            @if(request('departamento') || request('municipio') || request('especialidad') || request('search'))
                <span class="text-slate-500 font-medium text-sm pl-1">Filtros:</span>

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
                @if(request('especialidad'))
                    <span class="filter-pill">
                        <i class="fas fa-utensils" style="font-size:9px;opacity:0.7;"></i>
                        {{ request('especialidad') }}
                        <a href="{{ request()->fullUrlWithoutQuery(['especialidad']) }}">×</a>
                    </span>
                @endif
                @if(request('search'))
                    <span class="filter-pill">
                        <i class="fas fa-store" style="font-size:9px;opacity:0.7;"></i>
                        "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithoutQuery(['search']) }}">×</a>
                    </span>
                @endif

                <a href="{{ route('restaurantes.index') }}"
                   class="text-slate-400 hover:text-red-500 text-xs font-semibold no-underline flex items-center gap-1 ml-1">
                    <i class="fas fa-times-circle text-xs"></i> Limpiar todo
                </a>
            @endif

            <div class="ml-auto flex items-center gap-2">
                <div style="height:1px;width:32px;background:linear-gradient(to right,#2563eb,transparent);"></div>
                <span style="font-size:11px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#94a3b8;">
                    {{ $restaurantes->total() }} local{{ $restaurantes->total() != 1 ? 'es' : '' }}
                </span>
            </div>
        </div>

        {{-- ══ GRID DE RESTAURANTES ══ --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8">
            @forelse($restaurantes as $restaurante)

                @php
                    $diasMap = [
                        'lunes'     => 1,
                        'martes'    => 2,
                        'miercoles' => 3,
                        'jueves'    => 4,
                        'viernes'   => 5,
                        'sabado'    => 6,
                        'domingo'   => 0,
                    ];
                    $diasLabels = [
                        'lunes'     => 'Lun',
                        'martes'    => 'Mar',
                        'miercoles' => 'Mié',
                        'jueves'    => 'Jue',
                        'viernes'   => 'Vie',
                        'sabado'    => 'Sáb',
                        'domingo'   => 'Dom',
                    ];

                    $tieneHorario = $restaurante->hora_apertura && $restaurante->hora_cierre;
                    $estaAbierto  = false;
                    $diaHoyNum    = (int) now()->setTimezone('America/Managua')->format('w');
                    $horaActual   = now()->setTimezone('America/Managua')->format('H:i');

                    if ($tieneHorario) {
                        $diasLaborales = $restaurante->dias_laborales ?? [];
                        $hoyEsLaboral  = empty($diasLaborales)
                            ? true
                            : collect($diasLaborales)->contains(fn($d) => ($diasMap[$d] ?? -1) === $diaHoyNum);

                        if ($hoyEsLaboral) {
                            $apertura = $restaurante->hora_apertura;
                            $cierre   = $restaurante->hora_cierre;

                            if ($cierre > $apertura) {
                                $estaAbierto = $horaActual >= $apertura && $horaActual < $cierre;
                            } else {
                                $estaAbierto = $horaActual >= $apertura || $horaActual < $cierre;
                            }
                        }
                    }

                    $aperturaFmt = $tieneHorario
                        ? \Carbon\Carbon::createFromFormat('H:i', substr($restaurante->hora_apertura, 0, 5))->format('g:i a')
                        : null;
                    $cierreFmt = $tieneHorario
                        ? \Carbon\Carbon::createFromFormat('H:i', substr($restaurante->hora_cierre, 0, 5))->format('g:i a')
                        : null;

                    $diasLaboralesStr = '';
                    if (!empty($restaurante->dias_laborales)) {
                        $diasLaboralesStr = collect($restaurante->dias_laborales)
                            ->map(fn($d) => $diasLabels[$d] ?? ucfirst($d))
                            ->implode(' · ');
                    }
                @endphp

                <article class="glass-card overflow-hidden" data-aos="fade-up"
                         data-aos-delay="{{ ($loop->index % 2) * 80 }}">
                    <a href="{{ route('restaurantes.show', $restaurante->id) }}"
                       class="no-underline text-inherit block">
                        <div class="flex flex-col md:flex-row md:h-56">

                            <div class="rest-card-body flex flex-col justify-between p-5 sm:p-7 md:flex-1 order-2 md:order-1">
                                <div>
                                    <div class="flex items-center justify-between mb-2.5 gap-2">
                                        @if($restaurante->departamento)
                                            <span class="bg-slate-100 text-slate-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider whitespace-nowrap">
                                                <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i>
                                                {{ $restaurante->departamento->nombre }}
                                                @if($restaurante->municipio)
                                                    · {{ $restaurante->municipio->nombre }}
                                                @endif
                                            </span>
                                        @endif
                                        @if($restaurante->especialidad)
                                            <span class="bg-blue-50 text-blue-700 text-[9px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider whitespace-nowrap">
                                                {{ $restaurante->especialidad }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="premium-title text-xl sm:text-2xl font-bold text-slate-900 leading-tight mb-2">
                                        {{ $restaurante->nombre }}
                                    </h3>

                                    @if($restaurante->descripcion)
                                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-2 font-normal">
                                            {{ $restaurante->descripcion }}
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 mt-3">

                                    <div class="flex flex-col gap-1.5">

                                        @if($tieneHorario)
                                            @if($estaAbierto)
                                                <span class="badge-abierto">
                                                    <span class="dot-abierto"></span>
                                                    Abierto ahora
                                                </span>
                                            @else
                                                <span class="badge-cerrado">
                                                    <span class="dot-cerrado"></span>
                                                    Cerrado ahora
                                                </span>
                                            @endif

                                            <span class="text-xs text-slate-500 flex items-center gap-1.5">
                                                <i class="fas fa-clock text-slate-400" style="font-size:10px;"></i>
                                                {{ $aperturaFmt }} – {{ $cierreFmt }}
                                            </span>

                                            @if($diasLaboralesStr)
                                                <span class="text-xs text-slate-400 flex items-center gap-1.5">
                                                    <i class="fas fa-calendar-week text-slate-300" style="font-size:10px;"></i>
                                                    {{ $diasLaboralesStr }}
                                                </span>
                                            @endif

                                        @else
                                            <span class="badge-sin-horario">
                                                <i class="fas fa-clock" style="font-size:9px;"></i>
                                                Horario no disponible
                                            </span>
                                        @endif

                                    </div>

                                    @if($restaurante->whatsapp || $restaurante->instagram || $restaurante->tiktok || $restaurante->facebook)
                                        <div class="flex items-center gap-2"
                                             onclick="event.preventDefault(); event.stopPropagation();">
                                            @if($restaurante->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurante->whatsapp) }}"
                                                   target="_blank" class="social-btn"
                                                   style="background:#f0fdf4;border:1px solid #bbf7d0;"
                                                   onmouseover="this.style.background='#22c55e';this.style.borderColor='#22c55e';this.querySelector('i').style.color='#fff'"
                                                   onmouseout="this.style.background='#f0fdf4';this.style.borderColor='#bbf7d0';this.querySelector('i').style.color='#16a34a'">
                                                    <i class="fab fa-whatsapp" style="color:#16a34a;font-size:14px;"></i>
                                                </a>
                                            @endif
                                            @if($restaurante->instagram)
                                                <a href="{{ $restaurante->instagram }}"
                                                   target="_blank" class="social-btn"
                                                   style="background:#fdf2f8;border:1px solid #f9a8d4;"
                                                   onmouseover="this.style.background='#ec4899';this.style.borderColor='#ec4899';this.querySelector('i').style.color='#fff'"
                                                   onmouseout="this.style.background='#fdf2f8';this.style.borderColor='#f9a8d4';this.querySelector('i').style.color='#db2777'">
                                                    <i class="fab fa-instagram" style="color:#db2777;font-size:14px;"></i>
                                                </a>
                                            @endif
                                            @if($restaurante->tiktok)
                                                <a href="{{ $restaurante->tiktok }}"
                                                   target="_blank" class="social-btn"
                                                   style="background:#f8fafc;border:1px solid #e2e8f0;"
                                                   onmouseover="this.style.background='#1c1917';this.style.borderColor='#1c1917';this.querySelector('i').style.color='#fff'"
                                                   onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0';this.querySelector('i').style.color='#1c1917'">
                                                    <i class="fab fa-tiktok" style="color:#1c1917;font-size:13px;"></i>
                                                </a>
                                            @endif
                                            @if($restaurante->facebook)
                                                <a href="{{ $restaurante->facebook }}"
                                                   target="_blank" class="social-btn"
                                                   style="background:#eff6ff;border:1px solid #bfdbfe;"
                                                   onmouseover="this.style.background='#3b82f6';this.style.borderColor='#3b82f6';this.querySelector('i').style.color='#fff'"
                                                   onmouseout="this.style.background='#eff6ff';this.style.borderColor='#bfdbfe';this.querySelector('i').style.color='#2563eb'">
                                                    <i class="fab fa-facebook-f" style="color:#2563eb;font-size:13px;"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="rest-card-img order-1 md:order-2 md:w-44 md:rounded-none md:rounded-r-[2rem]">
                                @if($restaurante->foto_portada)
                                    <img src="{{ asset('storage/' . $restaurante->foto_portada) }}"
                                         alt="{{ $restaurante->nombre }}"
                                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-utensils" style="font-size:2.5rem;color:#2563eb;opacity:0.3;"></i>
                                    </div>
                                @endif

                                @if(isset($restaurante->eventos_count) && $restaurante->eventos_count > 0)
                                    <div class="absolute bottom-3 left-3">
                                        <div class="bg-slate-900/90 backdrop-blur-md text-white px-3 py-1 rounded-lg shadow-lg border border-white/10 font-bold text-xs">
                                            <i class="fas fa-calendar-alt text-blue-400 mr-1"></i>
                                            {{ $restaurante->eventos_count }} evento{{ $restaurante->eventos_count != 1 ? 's' : '' }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </a>
                </article>

            @empty
                <div class="col-span-full py-16 flex flex-col items-center text-center max-w-lg mx-auto" data-aos="fade-up">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-6 border border-slate-200/40 shadow-sm relative">
                        <i class="fas fa-store-slash text-2xl"></i>
                        <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-[10px] shadow-md">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                    <h3 class="premium-title text-2xl sm:text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">Sin resultados</h3>
                    <p class="text-slate-500 text-base leading-relaxed mb-8 font-light">
                        No encontramos restaurantes con esos filtros.
                    </p>
                    @if(request('departamento') || request('municipio') || request('especialidad') || request('search'))
                        <a href="{{ route('restaurantes.index') }}"
                           class="bg-slate-900 text-slate-50 text-xs font-bold tracking-wider uppercase px-6 py-3.5 rounded-xl no-underline hover:bg-blue-600 transition-all shadow-md active:scale-95 border-0 cursor-pointer flex items-center gap-2">
                            <i class="fas fa-undo text-[10px]"></i> Limpiar Filtros
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($restaurantes->hasPages())
            <div style="margin-top:56px;display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;">

                @if($restaurantes->onFirstPage())
                    <span class="pag-btn disabled">
                        <i class="fas fa-chevron-left" style="font-size:10px;"></i>
                    </span>
                @else
                    <a href="{{ $restaurantes->previousPageUrl() }}" class="pag-btn">
                        <i class="fas fa-chevron-left" style="font-size:10px;"></i>
                    </a>
                @endif

                @php
                    $current  = $restaurantes->currentPage();
                    $last     = $restaurantes->lastPage();
                    $pages    = [];
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
                        <a href="{{ $restaurantes->url($page) }}"
                           class="pag-btn {{ $page == $current ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if($restaurantes->hasMorePages())
                    <a href="{{ $restaurantes->nextPageUrl() }}" class="pag-btn">
                        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                    </a>
                @else
                    <span class="pag-btn disabled">
                        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                    </span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        const todosLosRestaurantes = window.__RESTAURANTES__ || [];

        function configurarFiltros(deptoId, munId, especId, searchId) {
            const deptoSel   = document.getElementById(deptoId);
            const munSel     = document.getElementById(munId);
            const especInput = especId ? document.getElementById(especId) : null;
            const searchSel  = document.getElementById(searchId);
            if (!deptoSel || !munSel || !searchSel) return;

            const munOpts = Array.from(munSel.querySelectorAll('option[data-departamento]'));

            function actualizarMunicipios() {
                const depto = deptoSel.value;
                munOpts.forEach(opt => {
                    opt.style.display = (!depto || opt.dataset.departamento === depto) ? '' : 'none';
                    if (opt.style.display === 'none') opt.selected = false;
                });
                const ph = munSel.querySelector('option:not([data-departamento])');
                if (depto) {
                    munSel.disabled = false;
                    if (ph) ph.textContent = 'Todos los municipios';
                } else {
                    munSel.disabled = true;
                    munSel.value = '';
                    if (ph) ph.textContent = 'Elige destino...';
                }
            }

            function actualizarLocales() {
                const depto = deptoSel.value;
                const mun   = munSel.value;
                const espec = especInput ? especInput.value.toLowerCase().trim() : '';
                const currentSearch = '{{ request("search") }}';

                searchSel.innerHTML = '';

                const ph = document.createElement('option');
                ph.value = '';
                ph.textContent = 'Todos los locales';
                searchSel.appendChild(ph);

                let filtrados = todosLosRestaurantes;

                if (depto) filtrados = filtrados.filter(r => String(r.departamento_id) === String(depto));
                if (mun)   filtrados = filtrados.filter(r => String(r.municipio_id) === String(mun));
                if (espec) filtrados = filtrados.filter(r =>
                    r.especialidad && r.especialidad.toLowerCase().includes(espec)
                );

                filtrados.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.nombre;
                    opt.textContent = r.nombre;
                    if (r.nombre === currentSearch) opt.selected = true;
                    searchSel.appendChild(opt);
                });
            }

            deptoSel.addEventListener('change', function () {
                actualizarMunicipios();
                actualizarLocales();
            });
            munSel.addEventListener('change', actualizarLocales);
            if (especInput) especInput.addEventListener('input', actualizarLocales);

            actualizarMunicipios();
            actualizarLocales();
        }

        configurarFiltros('nav-departamento', 'nav-municipio', 'nav-especialidad', 'nav-search');
        configurarFiltros('mob-departamento', 'mob-municipio', 'mob-especialidad', 'mob-search');

        const mobileSearchToggle = document.getElementById('mobileSearchToggle');
        const mobileSearchPanel  = document.getElementById('mobileSearchPanel');
        if (mobileSearchToggle && mobileSearchPanel) {
            mobileSearchToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                mobileSearchPanel.classList.toggle('open');
            });
            document.addEventListener('click', function (e) {
                if (!mobileSearchPanel.contains(e.target) && e.target !== mobileSearchToggle)
                    mobileSearchPanel.classList.remove('open');
            });
        }
    </script>
</body>
</html>
