<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gastro Nicaragua | Panel de Eventos</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; scroll-behavior: smooth; }
            .premium-title { font-family: 'Playfair Display', serif; }

            @keyframes slowZoom {
                from { transform: scale(1); }
                to   { transform: scale(1.1); }
            }
            .animate-slow-zoom { animation: slowZoom 10s infinite alternate; }

            @keyframes slideInLeftCustom {
                from { transform: translateX(-100px); opacity: 0; }
                to   { transform: translateX(0); opacity: 1; }
            }
            .animate-slide-left { animation: slideInLeftCustom 1s ease-out forwards; }

            .glass-card {
                background: #ffffff;
                border: 1px solid #f1f0ee;
                border-radius: 2rem;
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .glass-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 30px 60px rgba(28, 25, 23, 0.08);
                border-color: #ffedd5;
            }

            .carousel-item { transition: transform 0.8s ease-in-out, opacity 0.8s ease-in-out; }
            .carousel-control-prev,
            .carousel-control-next { z-index: 20; width: 5%; }

            /* ── NAVBAR SEARCH ── */
            .search-box {
                display: flex; align-items: stretch;
                background: #f8f7f6; border: 1.5px solid #e7e5e4;
                border-radius: 18px; overflow: hidden;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .search-box:focus-within {
                border-color: #ea580c;
                box-shadow: 0 0 0 3px rgba(234,88,12,0.10);
                background: #fff;
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
            .search-segment select:disabled { opacity: 0.45; cursor: not-allowed; }

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

            /* ── HERO RESPONSIVO ── */
            .hero-slide {
                position: relative; width: 100%;
                height: 75vw;           /* proporcional en móvil */
                min-height: 360px;
                max-height: 650px;
                overflow: hidden;
                display: flex;
                align-items: flex-end;  /* contenido pegado abajo en móvil */
            }
            @media (min-width: 640px) {
                .hero-slide { align-items: center; }
            }

            /* Badges info (Ubicación / Fecha) en el carrusel */
            .hero-badges {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
            }
            .hero-badge {
                display: flex;
                align-items: center;
                gap: 10px;
                background: rgba(255,255,255,0.12);
                backdrop-filter: blur(12px);
                padding: 8px 14px;
                border-radius: 14px;
                border: 1px solid rgba(255,255,255,0.2);
                flex: 1 1 auto;
                min-width: 0;
            }
            .hero-badge-icon {
                width: 34px; height: 34px;
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }
            .hero-badge-icon.orange { background: #ea580c; }
            .hero-badge-icon.white  { background: rgba(255,255,255,0.2); }
            .hero-badge p { margin: 0; line-height: 1.3; }
            .hero-badge .label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.16em; color: rgba(214,211,208,0.9); font-weight: 800; }
            .hero-badge .value { font-size: 13px; font-weight: 700; color: #fff; }

            /* Título hero responsive */
            .hero-title {
                font-family: 'Playfair Display', serif;
                font-weight: 900;
                line-height: 1.05;
                font-size: clamp(1.8rem, 7vw, 5rem);
                color: #fff;
                margin-bottom: 6px;
                text-shadow: 0 2px 16px rgba(0,0,0,0.4);
            }
            .hero-desc {
                font-size: clamp(0.85rem, 2.5vw, 1.25rem);
                color: rgba(214,211,208,0.9);
                margin-bottom: 16px;
                max-width: 520px;
                line-height: 1.6;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            @media (min-width: 640px) {
                .hero-desc { -webkit-line-clamp: 4; }
            }

            /* ── SECTION EVENTOS ── */
            @keyframes pulse-dot {
                0%,100% { opacity:1; transform:scale(1); }
                50%      { opacity:0.5; transform:scale(1.7); }
            }
            @keyframes draw-underline {
                from { stroke-dashoffset: 300; }
                to   { stroke-dashoffset: 0; }
            }
            .eventos-ghost-text {
                font-family: 'Playfair Display', serif; font-weight: 900;
                font-size: clamp(4rem, 15vw, 13rem); line-height: 1;
                color: transparent;
                -webkit-text-stroke: 1.5px rgba(234,88,12,0.10);
                letter-spacing: -0.04em;
                position: absolute; top: -1rem; left: -0.5rem;
                pointer-events: none; user-select: none;
                white-space: nowrap; z-index: 0;
            }
            .eventos-pill {
                display: inline-flex; align-items: center; gap: 8px;
                background: #fff7ed; border: 1.5px solid #fed7aa;
                color: #c2410c; font-size: 10px; font-weight: 800;
                letter-spacing: 0.2em; text-transform: uppercase;
                padding: 6px 18px; border-radius: 999px;
            }
            .eventos-pill .dot {
                width: 7px; height: 7px;
                background: #ea580c; border-radius: 50%;
                animation: pulse-dot 1.6s ease-in-out infinite; flex-shrink: 0;
            }
            .eventos-heading {
                font-family: 'Playfair Display', serif; font-weight: 900;
                font-size: clamp(1.8rem, 6vw, 4.5rem);
                line-height: 1.05; letter-spacing: -0.03em; color: #1c1917; margin: 0;
            }
            .eventos-heading em { font-style: italic; color: #ea580c; position: relative; }
            .underline-svg {
                position: absolute; bottom: -10px; left: 0; width: 100%; overflow: visible;
                stroke-dasharray: 300; stroke-dashoffset: 300;
                animation: draw-underline 1.2s cubic-bezier(0.4,0,0.2,1) 0.4s forwards;
            }
            .eventos-divider-icon {
                width: 34px; height: 34px;
                background: #ea580c; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 0 0 7px rgba(234,88,12,0.1); flex-shrink: 0;
            }

            /* ── CARD EVENTO ── */
            /* En móvil: imagen arriba (pequeña), texto abajo */
            .event-card-img {
                height: 200px;
                overflow: hidden;
                border-radius: 1.8rem 1.8rem 0 0;
                position: relative;
                background: #e7e5e4;
            }
            @media (min-width: 768px) {
                /* En tablet+: layout horizontal */
                .event-card-img {
                    height: 100%;
                    min-height: 220px;
                    border-radius: 0 2rem 2rem 0;
                }
                .event-card-body { border-radius: 2rem 0 0 2rem; }
            }


        </style>
    </head>
    <body class="bg-stone-50 text-stone-900">

        {{-- ══ NAVBAR ══ --}}
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
                    <form action="{{ route('home') }}" method="GET"
                          class="hidden md:flex flex-1 max-w-2xl search-box">
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
                        <div class="search-segment" style="min-width:130px;">
                            <label for="search-restaurante">
                                <i class="fas fa-store" style="color:#ea580c;"></i> Local
                            </label>
                            <select id="search-restaurante" name="restaurante_id"
                                    {{ request('departamento') ? '' : 'disabled' }}>
                                <option value="">{{ request('departamento') ? 'Todos los locales' : 'Elige destino...' }}</option>
                                @if(request('departamento'))
                                    @foreach($restaurantes->where('departamento_id', request('departamento')) as $rest)
                                        <option value="{{ $rest->id }}" {{ request('restaurante_id') == $rest->id ? 'selected' : '' }}>
                                            {{ $rest->nombre }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="search-segment" style="min-width:120px;">
                            <label for="search-especialidad">
                                <i class="fas fa-utensils" style="color:#ea580c;"></i> Especialidad
                            </label>
                            <input type="text" id="search-especialidad" name="especialidad"
                                   value="{{ request('especialidad') }}"
                                   placeholder="Asados, mariscos...">
                        </div>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search" style="font-size:12px;"></i>
                            <span>Buscar</span>
                        </button>
                    </form>

                    {{-- Acciones derecha --}}
                    <div class="flex items-center gap-1 sm:gap-2 shrink-0">

                        {{-- Búsqueda: ícono lupa (solo md abajo) --}}
                        <button id="mobileSearchToggle"
                                class="md:hidden w-9 h-9 rounded-full bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-orange-100 hover:text-orange-600 transition-colors border-0 cursor-pointer">
                            <i class="fas fa-search text-sm"></i>
                        </button>

                        {{-- Restaurantes icono (xs) / con texto (lg+) --}}
                        <a href="{{ route('restaurantes.index') }}"
                           class="flex items-center gap-1.5 border border-orange-200 text-orange-600 bg-orange-50 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-semibold hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all shadow-sm group no-underline justify-center">
                            <i class="fas fa-store text-xs"></i>
                            <span class="hidden lg:inline">Restaurantes</span>
                            @if(isset($totalRestaurantes) && $totalRestaurantes > 0)
                                <span class="hidden lg:flex bg-orange-600 group-hover:bg-white group-hover:text-orange-600 text-white text-[10px] font-bold w-5 h-5 rounded-full items-center justify-center transition-colors">{{ $totalRestaurantes }}</span>
                            @endif
                        </a>

                        {{-- Empleos icono (xs) / con texto (lg+) --}}
                        <a href="{{ route('empleos.index') }}"
                           class="flex items-center gap-1.5 border border-orange-200 text-orange-600 bg-orange-50 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-semibold hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all shadow-sm group no-underline justify-center">
                            <i class="fas fa-briefcase text-xs"></i>
                            <span class="hidden lg:inline">Empleos</span>
                            @if(isset($totalEmpleos) && $totalEmpleos > 0)
                                <span class="hidden lg:flex bg-orange-600 group-hover:bg-white group-hover:text-orange-600 text-white text-[10px] font-bold w-5 h-5 rounded-full items-center justify-center transition-colors">{{ $totalEmpleos }}</span>
                            @endif
                        </a>

                        {{-- Contacto: ícono en móvil, texto en lg+ --}}
                        <a href="{{ route('contacto') }}"
                           class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-stone-100 sm:bg-transparent text-stone-600 hover:text-orange-600 transition-colors no-underline"
                           title="Contacto">
                            <i class="fas fa-envelope text-sm sm:hidden"></i>
                            <span class="hidden sm:inline text-sm font-semibold">Contacto</span>
                        </a>

                        @if (Route::has('login'))
                            @auth
                                @if(auth()->user()->email === 'admin@turismo.ni')
                                    {{-- Panel: ícono en móvil, texto en lg+ --}}
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

            {{-- Panel búsqueda móvil desplegable --}}
            <div id="mobileSearchPanel">
                <form action="{{ route('home') }}" method="GET" class="flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-orange-500"></i> Destino
                        </label>
                        <select id="search-departamento-mobile" name="departamento" class="nav-select-mobile">
                            <option value="">Todos los destinos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>{{ $depto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                            <i class="fas fa-store text-orange-500"></i> Local
                        </label>
                        <select id="search-restaurante-mobile" name="restaurante_id"
                                class="nav-select-mobile" {{ request('departamento') ? '' : 'disabled' }}>
                            <option value="">{{ request('departamento') ? 'Todos los locales' : 'Primero elige destino...' }}</option>
                            @if(request('departamento'))
                                @foreach($restaurantes->where('departamento_id', request('departamento')) as $rest)
                                    <option value="{{ $rest->id }}" {{ request('restaurante_id') == $rest->id ? 'selected' : '' }}>{{ $rest->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                            <i class="fas fa-utensils text-orange-500"></i> Especialidad
                        </label>
                        <input type="text" name="especialidad" value="{{ request('especialidad') }}"
                               class="nav-input-mobile" placeholder="Asados, mariscos...">
                    </div>
                    <button type="submit"
                            class="bg-orange-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-orange-700 transition-all flex items-center justify-center gap-2 border-0 cursor-pointer">
                        <i class="fas fa-search text-xs"></i> Filtrar Experiencias
                    </button>
                </form>

            </div>
        </nav>

        {{-- ══ HERO / CARRUSEL ══ --}}
        <section class="relative" style="padding-top: 64px;">
            @if(isset($eventosDestacados) && $eventosDestacados->count() > 0)
                <div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($eventosDestacados as $key => $evento)
                            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $key }}"
                                    class="{{ $key == 0 ? 'active' : '' }}" aria-current="true"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($eventosDestacados as $key => $evento)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-bs-interval="6000">
                                <div class="hero-slide text-white">

                                    {{-- Imagen de fondo --}}
                                    <div class="absolute inset-0 z-0">
                                        <a href="{{ route('eventos.show', $evento->id) }}" class="block w-full h-full">
                                            <img src="{{ asset('storage/' . $evento->imagen) }}"
                                                 class="w-full h-full object-cover animate-slow-zoom"
                                                 alt="{{ $evento->titulo }}">
                                        </a>
                                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-black/20 pointer-events-none"></div>
                                        {{-- Degradado extra en la parte inferior en móvil --}}
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none sm:hidden"></div>
                                    </div>

                                    {{-- Contenido --}}
                                    <div class="relative z-10 w-full px-4 sm:px-10 md:px-20 py-8 sm:py-0
                                                {{ $key == 0 ? 'animate-slide-left' : '' }}">

                                        {{-- Badge --}}
                                        <span class="bg-orange-600/90 backdrop-blur-sm text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-[0.2em] mb-4 inline-block">
                                            Evento Destacado
                                        </span>

                                        {{-- Título --}}
                                        <h1 class="hero-title mb-2">
                                            <a href="{{ route('eventos.show', $evento->id) }}"
                                               class="text-white hover:text-orange-400 transition-colors no-underline">
                                                {{ $evento->titulo }}
                                            </a>
                                        </h1>

                                        {{-- Descripción --}}
                                        <p class="hero-desc">
                                            {{ Str::limit($evento->descripcion, 150) }}
                                        </p>

                                        {{-- Badges Ubicación / Fecha --}}
                                        <div class="hero-badges" style="max-width: 520px;">
                                            <div class="hero-badge">
                                                <div class="hero-badge-icon orange">
                                                    <i class="fas fa-map-marker-alt text-white text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="label">Ubicación</p>
                                                    <p class="value" style="font-size:13px;">{{ $evento->restaurante->nombre }}</p>
                                                </div>
                                            </div>
                                            <div class="hero-badge">
                                                <div class="hero-badge-icon white">
                                                    <i class="fas fa-calendar-alt text-white text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="label">Fecha</p>
                                                    <p class="value" style="font-size:13px;">{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M, Y') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev border-0 bg-transparent" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next border-0 bg-transparent" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            @else
                <div class="bg-stone-900 w-full h-[380px] flex items-center justify-center text-white">
                    <h1 class="premium-title text-4xl">Gastro Nicaragua</h1>
                </div>
            @endif
        </section>

        {{-- ══ MAIN ══ --}}
        <main class="max-w-7xl mx-auto px-4 py-12 sm:py-20 md:py-24">

            {{-- Filtros activos --}}
            @if(request('search') || request('departamento') || request('especialidad') || request('restaurante_id'))
                <div class="mb-8 flex items-center gap-2 flex-wrap bg-stone-100 p-3 rounded-xl text-sm">
                    <span class="text-stone-500 font-medium">Búsqueda activa:</span>
                    @if(request('search'))
                        <span class="bg-white text-stone-800 px-3 py-1 rounded-md text-xs font-semibold shadow-sm flex items-center gap-2">
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="text-stone-400 hover:text-red-600 no-underline">×</a>
                        </span>
                    @endif
                    @if(request('departamento'))
                        <span class="bg-white text-stone-800 px-3 py-1 rounded-md text-xs font-semibold shadow-sm flex items-center gap-2">
                            Región: {{ $departamentos->find(request('departamento'))?->nombre }}
                            <a href="{{ request()->fullUrlWithoutQuery(['departamento', 'restaurante_id']) }}" class="text-stone-400 hover:text-red-600 no-underline">×</a>
                        </span>
                    @endif
                    @if(request('restaurante_id'))
                        <span class="bg-white text-stone-800 px-3 py-1 rounded-md text-xs font-semibold shadow-sm flex items-center gap-2">
                            Local: {{ $restaurantes->find(request('restaurante_id'))?->nombre }}
                            <a href="{{ request()->fullUrlWithoutQuery(['restaurante_id']) }}" class="text-stone-400 hover:text-red-600 no-underline">×</a>
                        </span>
                    @endif
                    @if(request('especialidad'))
                        <span class="bg-white text-stone-800 px-3 py-1 rounded-md text-xs font-semibold shadow-sm flex items-center gap-2">
                            Comida: {{ request('especialidad') }}
                            <a href="{{ request()->fullUrlWithoutQuery(['especialidad']) }}" class="text-stone-400 hover:text-red-600 no-underline">×</a>
                        </span>
                    @endif
                </div>
            @endif

            {{-- ── SECTION HEADER ── --}}
            <div class="relative mb-14 sm:mb-20" style="overflow:visible;">
                <div class="eventos-ghost-text" aria-hidden="true">Eventos</div>

                <div class="relative z-10 mb-4">
                    <span class="eventos-pill">
                        <span class="dot"></span>
                        Descubre · Reserva · Disfruta
                    </span>
                </div>

                <div class="relative z-10">
                    <h2 class="eventos-heading">
                        Próximos&nbsp;<em style="position:relative;">
                            Eventos
                            <svg class="underline-svg" style="position:absolute;bottom:-10px;left:0;width:100%;" height="12" viewBox="0 0 300 12" preserveAspectRatio="none">
                                <path d="M2 9 Q75 2 150 8 Q225 14 298 5" stroke="#ea580c" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-dasharray="300" stroke-dashoffset="300" style="animation:draw-underline 1.2s cubic-bezier(0.4,0,0.2,1) 0.5s forwards;"/>
                            </svg>
                        </em>&nbsp;Gastronómicos
                    </h2>
                </div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-7">
                    <p style="color:#78716c; font-size:0.95rem; max-width:500px; line-height:1.7; margin:0;">
                        Experiencias culinarias únicas en los mejores restaurantes de Nicaragua.
                        Desde ceviches costeros hasta asados de montaña.
                    </p>
                    <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                        <div style="height:1px; width:40px; background:linear-gradient(to right,#ea580c,transparent);"></div>
                        <span style="font-size:11px; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#a8a29e;">
                            {{ method_exists($eventos,'total') ? $eventos->total() : $eventos->count() }} experiencias
                        </span>
                    </div>
                </div>

                <div class="relative z-10 flex items-center gap-4 mt-8">
                    <div style="flex:1; height:1px; background:#e7e5e4;"></div>
                    <div class="eventos-divider-icon">
                        <i class="fas fa-utensils" style="color:#fff; font-size:12px;"></i>
                    </div>
                    <div style="flex:1; height:1px; background:#e7e5e4;"></div>
                </div>
            </div>

            {{-- ── GRID DE EVENTOS ── --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8">
                @forelse($eventos as $item)
                    <article class="glass-card overflow-hidden" data-aos="fade-up">
                        <div class="flex flex-col md:flex-row md:h-56">

                            {{-- Texto --}}
                            <div class="event-card-body flex flex-col justify-between p-5 sm:p-7 md:flex-1 order-2 md:order-1">
                                <div>
                                    <div class="flex items-center justify-between mb-2.5 gap-2">
                                        <span class="bg-stone-100 text-stone-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider whitespace-nowrap">
                                            <i class="fas fa-map-marker-alt text-orange-600 mr-1"></i> {{ $item->departamento->nombre }}
                                        </span>
                                        @if($item->restaurante->especialidad)
                                            <span class="bg-orange-50 text-orange-700 text-[9px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider whitespace-nowrap">
                                                {{ $item->restaurante->especialidad }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="premium-title text-xl sm:text-2xl font-bold text-stone-900 leading-tight mb-2">
                                        <a href="{{ route('eventos.show', $item->id) }}"
                                           class="no-underline text-stone-900 hover:text-orange-600 transition-colors">
                                            {{ $item->titulo }}
                                        </a>
                                    </h3>
                                    <p class="text-stone-500 text-sm leading-relaxed line-clamp-2 font-normal">
                                        {{ $item->descripcion }}
                                    </p>
                                </div>
                                <div class="pt-3 border-t border-stone-100 flex flex-wrap items-center justify-between gap-2 mt-3">
                                    <div class="flex items-center gap-2 text-stone-600">
                                        <i class="far fa-calendar-alt text-stone-400 text-sm"></i>
                                        <span class="text-xs font-bold">
                                            {{ \Carbon\Carbon::parse($item->fecha_evento)->translatedFormat('d M, Y') }}
                                        </span>
                                    </div>
                                    <div class="bg-red-50 px-3 py-1.5 rounded-xl border border-red-100/50">
                                        <span class="countdown text-[10px] font-black text-red-600 uppercase tracking-wider"
                                              data-expire="{{ $item->fecha_evento }}">Cargando...</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Imagen --}}
                            <div class="event-card-img order-1 md:order-2 md:w-44 md:rounded-none md:rounded-r-[2rem]">
                                <a href="{{ route('eventos.show', $item->id) }}" class="block w-full h-full">
                                    <img src="{{ asset('storage/' . $item->imagen) }}"
                                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                                         alt="{{ $item->titulo }}">
                                </a>
                                <div class="absolute bottom-3 left-3">
                                    <div class="bg-stone-900/90 backdrop-blur-md text-white px-3 py-1 rounded-lg shadow-lg border border-white/10 font-bold text-xs">
                                        C$ {{ number_format($item->precio, 0) }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center text-center max-w-lg mx-auto" data-aos="fade-up">
                        <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center text-stone-400 mb-6 border border-stone-200/40 shadow-sm relative">
                            <i class="fas fa-search text-2xl"></i>
                            <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-orange-600 rounded-full flex items-center justify-center text-white text-[10px] shadow-md">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                        <h3 class="premium-title text-2xl sm:text-3xl font-extrabold text-stone-900 mb-3 tracking-tight">Sin experiencias culinarias</h3>
                        <p class="text-stone-500 text-base leading-relaxed mb-8 font-light">
                            No encontramos eventos activos con los filtros seleccionados.
                        </p>
                        @if(request('especialidad') || request('departamento') || request('restaurante_id') || request('search'))
                            <a href="{{ route('home') }}"
                               class="bg-stone-900 text-stone-50 text-xs font-bold tracking-wider uppercase px-6 py-3.5 rounded-xl no-underline hover:bg-orange-600 transition-all shadow-md active:scale-95 border-0 cursor-pointer flex items-center gap-2">
                                <i class="fas fa-undo text-[10px]"></i> Limpiar Filtros
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            @if(method_exists($eventos,'links'))
                <div class="mt-10">{{ $eventos->links() }}</div>
            @endif
        </main>

        {{-- ══ FOOTER ══ --}}
        <footer class="bg-stone-900 text-stone-300 border-t border-stone-800">
            <div class="max-w-7xl mx-auto px-4 pt-12 pb-8 sm:pt-16 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 mb-10">

                    <div class="sm:col-span-2 lg:col-span-4 space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 bg-orange-600 rounded-xl flex items-center justify-center shadow-md shadow-orange-600/20">
                                <i class="fas fa-utensils text-white text-xs"></i>
                            </div>
                            <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-orange-600">Nicaragua</span></span>
                        </div>
                        <p class="text-stone-400 text-sm leading-relaxed font-light">
                            La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                            Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                        </p>
                        <div class="flex items-center gap-3 pt-1">
                            <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>

                    <div class="lg:col-span-2 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Portal</h4>
                        <ul class="space-y-2.5 text-sm p-0 list-none m-0">
                            <li><a href="{{ route('home') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Inicio</a></li>
                            <li><a href="{{ route('restaurantes.index') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Restaurantes</a></li>
                            <li><a href="{{ route('empleos.index') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Bolsa de Empleos</a></li>
                            <li><a href="{{ route('contacto') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Contacto</a></li>
                        </ul>
                    </div>

                    <div class="lg:col-span-3 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Destinos Destacados</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm text-stone-400 font-light">
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Masaya</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Granada</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>León</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>San Juan del Sur</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Estelí</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Matagalpa</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-stone-800 pt-6 text-center text-xs text-stone-500 font-light flex flex-col sm:flex-row justify-between items-center gap-3">
                    <p>&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-stone-500 hover:text-stone-400 no-underline">Política de Privacidad</a>
                        <a href="#" class="text-stone-500 hover:text-stone-400 no-underline">Términos de Servicio</a>
                    </div>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 800, once: true });

            const todosLosRestaurantes = @json($restaurantes->values());

            function configurarFiltroCascada(selectDeptoId, selectRestId) {
                const deptoSelect = document.getElementById(selectDeptoId);
                const restSelect  = document.getElementById(selectRestId);
                if (!deptoSelect || !restSelect) return;
                deptoSelect.addEventListener('change', function () {
                    const deptoId = this.value;
                    restSelect.innerHTML = '<option value="">Todos los locales</option>';
                    if (!deptoId) {
                        restSelect.disabled = true;
                        restSelect.options[0].text = 'Elige destino primero...';
                        return;
                    }
                    const filtrados = todosLosRestaurantes.filter(r => r.departamento_id == deptoId);
                    filtrados.forEach(r => {
                        const opt = document.createElement('option');
                        opt.value = r.id;
                        opt.textContent = r.nombre;
                        restSelect.appendChild(opt);
                    });
                    restSelect.disabled = false;
                    restSelect.options[0].text = 'Todos los locales';
                });
            }
            configurarFiltroCascada('search-departamento',        'search-restaurante');
            configurarFiltroCascada('search-departamento-mobile', 'search-restaurante-mobile');

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

            document.querySelectorAll('.countdown').forEach(el => {
                const targetDateStr = el.getAttribute('data-expire');
                if (!targetDateStr) return;
                const targetDate = new Date(targetDateStr.replace(/-/g, "/")).getTime();
                const interval = setInterval(() => {
                    const now  = new Date().getTime();
                    const diff = targetDate - now;
                    if (diff <= 0) {
                        el.textContent = "Finalizado / En Curso";
                        el.className   = "text-[10px] font-bold text-stone-400 uppercase tracking-wider";
                        clearInterval(interval);
                        return;
                    }
                    const dias    = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const horas   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    if (dias > 0)       el.textContent = `Faltan ${dias} d y ${horas} h`;
                    else if (horas > 0) el.textContent = `Faltan ${horas} h y ${minutos} m`;
                    else                el.textContent = `Inicia en menos de 1 h`;
                }, 1000);
            });
        </script>
    </body>
</html>