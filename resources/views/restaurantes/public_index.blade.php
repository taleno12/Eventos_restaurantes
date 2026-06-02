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

        .page-link { color: #ea580c; border-radius: 50%; margin: 0 3px; }
        .page-item.active .page-link { background-color: #ea580c; border-color: #ea580c; }

        /* ══ SEARCH BOX (desktop) ══ */
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
            padding: 7px 12px; min-width: 0; flex: 1; position: relative;
        }
        .search-segment + .search-segment::before {
            content: ''; position: absolute; left: 0; top: 20%;
            height: 60%; width: 1px; background: #e7e5e4;
        }
        .search-segment label {
            font-size: 9px; font-weight: 900; letter-spacing: 0.14em;
            text-transform: uppercase; color: #a8a29e; margin-bottom: 2px;
            display: flex; align-items: center; gap: 4px; cursor: pointer;
            white-space: nowrap;
        }
        .search-segment select,
        .search-segment input {
            background: transparent; border: none; outline: none;
            font-size: 12px; font-weight: 600; color: #1c1917;
            font-family: 'Instrument Sans', sans-serif; width: 100%; padding: 0; cursor: pointer;
        }
        .search-segment select option { font-weight: 500; }
        .search-segment input::placeholder { color: #c4bfbb; font-weight: 500; }
        .search-segment select:disabled { opacity: 0.45; cursor: not-allowed; }

        .search-btn {
            display: flex; align-items: center; gap: 6px;
            background: #ea580c; color: white; border: none;
            padding: 0 18px; font-size: 13px; font-weight: 700;
            cursor: pointer; transition: background 0.2s;
            white-space: nowrap; border-radius: 0 16px 16px 0; flex-shrink: 0;
        }
        .search-btn:hover { background: #c2410c; }

        /* ══ PANEL BÚSQUEDA MÓVIL ══ */
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
        .nav-select-mobile:disabled { opacity: 0.45; cursor: not-allowed; }

        .nav-input-mobile {
            background: #f8f7f6; border: 1.5px solid #e7e5e4; border-radius: 12px;
            padding: 10px 14px; font-size: 13px; color: #1c1917;
            transition: all 0.2s; outline: none; width: 100%;
            font-family: 'Instrument Sans', sans-serif; font-weight: 600;
        }
        .nav-input-mobile:focus { border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,0.12); background: #fff; }
        .nav-input-mobile::placeholder { color: #c4bfbb; }

        /* ══ GLASS CARD (igual que eventos) ══ */
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

        /* ── CARD RESTAURANTE (igual que event-card-img) ── */
        .rest-card-img {
            height: 200px; overflow: hidden; border-radius: 1.8rem 1.8rem 0 0;
            position: relative; background: #e7e5e4;
        }
        @media (min-width: 768px) {
            .rest-card-img { height: 100%; min-height: 220px; border-radius: 0 2rem 2rem 0; }
            .rest-card-body { border-radius: 2rem 0 0 2rem; }
        }

        /* ══ FILTROS PILL ══ */
        .filter-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fff7ed; border: 1.5px solid #fed7aa;
            color: #c2410c; font-size: 11px; font-weight: 700;
            padding: 5px 10px 5px 12px; border-radius: 999px;
        }
        .filter-pill a {
            background: rgba(194,65,12,0.12); width: 16px; height: 16px;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; color: #c2410c; font-size: 12px;
            text-decoration: none; line-height: 1; flex-shrink: 0;
        }

        /* ══ PAGINACIÓN ══ */
        .pag-btn {
            width: 36px; height: 36px; border-radius: 50%;
            border: 1.5px solid #e7e5e4; background: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600; color: #57534e;
            text-decoration: none; transition: all 0.2s; cursor: pointer;
        }
        .pag-btn:hover { border-color: #ea580c; color: #ea580c; }
        .pag-btn.active { background: #ea580c; border-color: #ea580c; color: #fff; }
        .pag-btn.disabled { color: #d4cfc9; pointer-events: none; }

        /* ══ REDES SOCIALES ══ */
        .social-btn {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: all 0.2s; flex-shrink: 0;
            position: relative; z-index: 2;
        }
        .social-btn:hover { transform: scale(1.15); }
    </style>
</head>
<body class="bg-stone-50/50 text-stone-900">

    {{-- ══════════════ NAVBAR ══════════════ --}}
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center gap-3">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 no-underline">
                    <span class="text-xl font-bold tracking-tight premium-title italic text-stone-900 hidden lg:block">
                        Gastro<span class="text-orange-600">Nicaragua</span>
                    </span>
                </a>

                {{-- Search Bar desktop --}}
                <form action="{{ route('restaurantes.index') }}" method="GET"
                      class="hidden md:flex flex-1 search-box" style="min-width:0;">

                    {{-- 1. DESTINO --}}
                    <div class="search-segment">
                        <label for="nav-departamento">
                            <i class="fas fa-map-marker-alt" style="color:#ea580c;font-size:8px;"></i> Destino
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
                            <i class="fas fa-city" style="color:#ea580c;font-size:8px;"></i> Municipio
                        </label>
                        <select id="nav-municipio" name="municipio"
                                {{ (request('departamento') ?? $departamentoPredefinido) ? '' : 'disabled' }}>
                            <option value="">{{ (request('departamento') ?? $departamentoPredefinido) ? 'Todos' : 'Elige destino...' }}</option>
                            @foreach($municipios as $mun)
                                <option value="{{ $mun->id }}"
                                        data-departamento="{{ $mun->departamento_id }}"
                                        {{ request('municipio') == $mun->id ? 'selected' : '' }}
                                        style="{{ (request('departamento') ?? $departamentoPredefinido) && $mun->departamento_id == (request('departamento') ?? $departamentoPredefinido) ? '' : 'display:none' }}">
                                    {{ $mun->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. ESPECIALIDAD --}}
                    <div class="search-segment">
                        <label for="nav-especialidad">
                            <i class="fas fa-utensils" style="color:#ea580c;font-size:8px;"></i> Especialidad
                        </label>
                        <input type="text" id="nav-especialidad" name="especialidad"
                               value="{{ request('especialidad') }}" placeholder="Asados...">
                    </div>

                    {{-- 4. LOCAL (select dinámico) --}}
                    <div class="search-segment">
                        <label for="nav-search">
                            <i class="fas fa-store" style="color:#ea580c;font-size:8px;"></i> Local
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

                    {{-- Lupa móvil --}}
                    <button id="mobileSearchToggle"
                            class="md:hidden w-9 h-9 rounded-full bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-orange-100 hover:text-orange-600 transition-colors border-0 cursor-pointer">
                        <i class="fas fa-search text-sm"></i>
                    </button>

                    <a href="{{ route('home') }}"
                       class="flex items-center gap-1.5 border border-stone-200 text-stone-600 bg-white px-3 py-2 rounded-full text-sm font-semibold hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all shadow-sm no-underline">
                        <i class="fas fa-home text-xs"></i>
                        <span class="hidden lg:inline">Inicio</span>
                    </a>
                    <a href="{{ route('restaurantes.index') }}"
                       class="flex items-center gap-1.5 border border-orange-600 text-white bg-orange-600 px-3 py-2 rounded-full text-sm font-semibold shadow-sm no-underline">
                        <i class="fas fa-store text-xs"></i>
                        <span class="hidden lg:inline">Restaurantes</span>
                    </a>

                    <a href="{{ route('contacto') }}"
                       class="flex items-center justify-center w-9 h-9 rounded-full bg-stone-100 text-stone-600 hover:bg-orange-100 hover:text-orange-600 transition-colors no-underline lg:w-auto lg:h-auto lg:bg-transparent lg:rounded-none lg:px-2"
                       title="Contacto">
                        <i class="fas fa-envelope text-sm lg:hidden"></i>
                        <span class="hidden lg:inline text-sm font-semibold">Contacto</span>
                    </a>

                    @if (Route::has('login'))
                        @auth
                            @if(auth()->user()->email === 'admin@turismo.ni')
                                <a href="{{ url('/dashboard') }}"
                                   class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline px-2 hidden lg:inline">Panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                                @csrf
                                <button type="submit"
                                        class="text-sm font-semibold text-stone-500 hover:text-red-500 transition-colors bg-transparent border-0 cursor-pointer px-2">Salir</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline px-2">Ingresar</a>
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
                    <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt text-orange-500"></i> Destino
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
                    <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                        <i class="fas fa-city text-orange-500"></i> Municipio
                    </label>
                    <select id="mob-municipio" name="municipio" class="nav-select-mobile"
                            {{ (request('departamento') ?? $departamentoPredefinido) ? '' : 'disabled' }}>
                        <option value="">{{ (request('departamento') ?? $departamentoPredefinido) ? 'Todos' : 'Elige destino...' }}</option>
                        @foreach($municipios as $mun)
                            <option value="{{ $mun->id }}"
                                    data-departamento="{{ $mun->departamento_id }}"
                                    {{ request('municipio') == $mun->id ? 'selected' : '' }}
                                    style="{{ (request('departamento') ?? $departamentoPredefinido) && $mun->departamento_id == (request('departamento') ?? $departamentoPredefinido) ? '' : 'display:none' }}">
                                {{ $mun->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. ESPECIALIDAD --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                        <i class="fas fa-utensils text-orange-500"></i> Especialidad
                    </label>
                    <input type="text" id="mob-especialidad" name="especialidad" value="{{ request('especialidad') }}"
                           class="nav-input-mobile" placeholder="Asados, mariscos...">
                </div>

                {{-- 4. LOCAL (select dinámico) --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                        <i class="fas fa-store text-orange-500"></i> Local
                    </label>
                    <select id="mob-search" name="search" class="nav-select-mobile">
                        <option value="">Todos los locales</option>
                    </select>
                </div>

                <button type="submit"
                        class="bg-orange-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-orange-700 transition-all flex items-center justify-center gap-2 border-0 cursor-pointer">
                    <i class="fas fa-search text-xs"></i> Buscar Restaurantes
                </button>
            </form>
        </div>
    </nav>

    {{-- ══════════════ HERO PREMIUM ══════════════ --}}
    <section class="pt-20">
        <div class="relative overflow-hidden" style="min-height:520px;">

            {{-- Imagen de fondo --}}
            <div class="absolute inset-0"
                 style="background-image:url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1800&q=80');
                        background-size:cover;background-position:center;
                        transform:scale(1.04);transition:transform 8s ease;">
            </div>

            {{-- Overlay multicapa: oscuro + degradado naranja --}}
            <div class="absolute inset-0" style="background:linear-gradient(135deg, rgba(12,10,9,0.88) 0%, rgba(12,10,9,0.72) 50%, rgba(180,60,0,0.45) 100%);"></div>

            {{-- Patrón de puntos sutil --}}
            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image:radial-gradient(circle, #fff 1px, transparent 1px);background-size:28px 28px;"></div>

            {{-- Brillo naranja decorativo --}}
            <div class="absolute top-0 right-0 w-[600px] h-[600px] opacity-20 pointer-events-none"
                 style="background:radial-gradient(circle at 70% 30%, #ea580c 0%, transparent 65%);"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] opacity-10 pointer-events-none"
                 style="background:radial-gradient(circle at 20% 80%, #f97316 0%, transparent 60%);"></div>

            {{-- Contenido --}}
            <div class="relative max-w-7xl mx-auto px-4 py-24 sm:py-28 flex flex-col md:flex-row items-center justify-between gap-12">

                {{-- Texto principal --}}
                <div class="flex-1 max-w-2xl" data-aos="fade-right">

                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 mb-6"
                         style="background:rgba(234,88,12,0.15);border:1px solid rgba(234,88,12,0.4);
                                padding:6px 16px;border-radius:999px;">
                        <span style="width:7px;height:7px;background:#ea580c;border-radius:50%;display:inline-block;
                                     box-shadow:0 0 8px rgba(234,88,12,0.8);animation:pulse 2s infinite;"></span>
                        <span style="color:#fb923c;font-size:11px;font-weight:800;letter-spacing:0.18em;text-transform:uppercase;">
                            <i class="fas fa-map-marker-alt mr-1" style="font-size:9px;"></i> Toda Nicaragua
                        </span>
                    </div>

                    <h1 class="premium-title mb-5 leading-tight"
                        style="font-size:clamp(2.8rem,5vw,4.2rem);font-weight:900;color:white;line-height:1.05;">
                        Nuestros<br>
                        <span style="color:#f97316;font-style:italic;font-weight:400;">Restaurantes</span>
                    </h1>

                    <p style="color:rgba(255,255,255,0.6);font-size:15px;line-height:1.8;max-width:520px;margin-bottom:32px;font-weight:300;">
                        Desde fritangas familiares hasta cocina gourmet — descubre cada sabor auténtico de Nicaragua en un solo lugar.
                    </p>

                    {{-- Pills de stats --}}
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
                            <i class="fas fa-map-marker-alt" style="color:#ea580c;font-size:11px;"></i> Ubicación en mapa
                        </span>
                    </div>

                    {{-- Línea decorativa --}}
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="height:1px;width:48px;background:linear-gradient(to right,#ea580c,transparent);"></div>
                        <span style="font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:rgba(255,255,255,0.3);">
                            Gastro Nicaragua
                        </span>
                    </div>
                </div>

                {{-- Panel derecho: tarjeta premium flotante --}}
                <div class="shrink-0 hidden md:flex flex-col items-center gap-5" data-aos="fade-left">

                    {{-- Tarjeta glassmorphism --}}
                    <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.14);
                                backdrop-filter:blur(20px);border-radius:28px;padding:36px 40px;
                                display:flex;flex-direction:column;align-items:center;gap:16px;
                                box-shadow:0 24px 64px rgba(0,0,0,0.3),inset 0 1px 0 rgba(255,255,255,0.1);
                                min-width:220px;">

                        {{-- Ícono principal --}}
                        <div style="width:80px;height:80px;background:linear-gradient(135deg,#ea580c,#f97316);
                                    border-radius:24px;display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 12px 32px rgba(234,88,12,0.5);
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

                        {{-- Mini stats --}}
                        <div style="display:flex;gap:20px;">
                            <div style="text-align:center;">
                                <p style="color:#fb923c;font-size:18px;font-weight:900;line-height:1;">17</p>
                                <p style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Deptos.</p>
                            </div>
                            <div style="width:1px;background:rgba(255,255,255,0.08);"></div>
                            <div style="text-align:center;">
                                <p style="color:#fb923c;font-size:18px;font-weight:900;line-height:1;">153</p>
                                <p style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Munic.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Texto debajo de la tarjeta --}}
                    <p style="color:rgba(255,255,255,0.3);font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;">
                        Gastronomía auténtica 🇳🇮
                    </p>
                </div>

            </div>

            {{-- Borde inferior degradado --}}
            <div class="absolute bottom-0 left-0 right-0" style="height:80px;background:linear-gradient(to bottom,transparent,#fafaf9);"></div>
        </div>
    </section>

    <style>
        @keyframes pulse {
            0%,100% { opacity:1; box-shadow:0 0 8px rgba(234,88,12,0.8); }
            50%      { opacity:0.6; box-shadow:0 0 16px rgba(234,88,12,1); }
        }
    </style>

    {{-- ══════════════ MAIN ══════════════ --}}
    <main class="max-w-7xl mx-auto px-4 py-16">

        {{-- Barra filtros activos + contador --}}
        <div class="mb-8 flex flex-wrap items-center gap-2">
            @if(request('departamento') || request('municipio') || request('especialidad') || request('search'))
                <span class="text-stone-500 font-medium text-sm pl-1">Filtros:</span>

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
                   class="text-stone-400 hover:text-red-500 text-xs font-semibold no-underline flex items-center gap-1 ml-1">
                    <i class="fas fa-times-circle text-xs"></i> Limpiar todo
                </a>
            @endif

            {{-- Contador --}}
            <div class="ml-auto flex items-center gap-2">
                <div style="height:1px;width:32px;background:linear-gradient(to right,#ea580c,transparent);"></div>
                <span style="font-size:11px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;">
                    {{ $restaurantes->total() }} local{{ $restaurantes->total() != 1 ? 'es' : '' }}
                </span>
            </div>
        </div>

        {{-- ══ GRID DE RESTAURANTES (mismo estilo que eventos) ══ --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8">
            @forelse($restaurantes as $restaurante)
                <article class="glass-card overflow-hidden" data-aos="fade-up"
                         data-aos-delay="{{ ($loop->index % 2) * 80 }}">
                    <a href="{{ route('restaurantes.show', $restaurante->id) }}"
                       class="no-underline text-inherit block">
                        <div class="flex flex-col md:flex-row md:h-56">

                            {{-- Texto --}}
                            <div class="rest-card-body flex flex-col justify-between p-5 sm:p-7 md:flex-1 order-2 md:order-1">
                                <div>
                                    <div class="flex items-center justify-between mb-2.5 gap-2">
                                        @if($restaurante->departamento)
                                            <span class="bg-stone-100 text-stone-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider whitespace-nowrap">
                                                <i class="fas fa-map-marker-alt text-orange-600 mr-1"></i>
                                                {{ $restaurante->departamento->nombre }}
                                                @if($restaurante->municipio)
                                                    · {{ $restaurante->municipio->nombre }}
                                                @endif
                                            </span>
                                        @endif
                                        @if($restaurante->especialidad)
                                            <span class="bg-orange-50 text-orange-700 text-[9px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider whitespace-nowrap">
                                                {{ $restaurante->especialidad }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="premium-title text-xl sm:text-2xl font-bold text-stone-900 leading-tight mb-2">
                                        {{ $restaurante->nombre }}
                                    </h3>

                                    @if($restaurante->descripcion)
                                        <p class="text-stone-500 text-sm leading-relaxed line-clamp-2 font-normal">
                                            {{ $restaurante->descripcion }}
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-3 border-t border-stone-100 flex flex-wrap items-center justify-between gap-2 mt-3">
                                    <div class="flex flex-col gap-1">
                                        @if($restaurante->telefono)
                                            <span class="text-xs text-stone-500 flex items-center gap-1.5">
                                                <i class="fas fa-phone text-stone-400" style="font-size:10px;"></i>
                                                {{ $restaurante->telefono }}
                                            </span>
                                        @endif
                                        @if($restaurante->horario)
                                            <span class="text-xs text-stone-500 flex items-center gap-1.5">
                                                <i class="fas fa-clock text-stone-400" style="font-size:10px;"></i>
                                                {{ $restaurante->horario }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Redes sociales --}}
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

                            {{-- Imagen --}}
                            <div class="rest-card-img order-1 md:order-2 md:w-44 md:rounded-none md:rounded-r-[2rem]">
                                @if($restaurante->foto_portada)
                                    <img src="{{ asset('storage/' . $restaurante->foto_portada) }}"
                                         alt="{{ $restaurante->nombre }}"
                                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-utensils" style="font-size:2.5rem;color:#ea580c;opacity:0.3;"></i>
                                    </div>
                                @endif

                                @if(isset($restaurante->eventos_count) && $restaurante->eventos_count > 0)
                                    <div class="absolute bottom-3 left-3">
                                        <div class="bg-stone-900/90 backdrop-blur-md text-white px-3 py-1 rounded-lg shadow-lg border border-white/10 font-bold text-xs">
                                            <i class="fas fa-calendar-alt text-orange-400 mr-1"></i>
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
                    <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center text-stone-400 mb-6 border border-stone-200/40 shadow-sm relative">
                        <i class="fas fa-store-slash text-2xl"></i>
                        <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-orange-600 rounded-full flex items-center justify-center text-white text-[10px] shadow-md">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                    <h3 class="premium-title text-2xl sm:text-3xl font-extrabold text-stone-900 mb-3 tracking-tight">Sin resultados</h3>
                    <p class="text-stone-500 text-base leading-relaxed mb-8 font-light">
                        No encontramos restaurantes con esos filtros.
                    </p>
                    @if(request('departamento') || request('municipio') || request('especialidad') || request('search'))
                        <a href="{{ route('restaurantes.index') }}"
                           class="bg-stone-900 text-stone-50 text-xs font-bold tracking-wider uppercase px-6 py-3.5 rounded-xl no-underline hover:bg-orange-600 transition-all shadow-md active:scale-95 border-0 cursor-pointer flex items-center gap-2">
                            <i class="fas fa-undo text-[10px]"></i> Limpiar Filtros
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($restaurantes->hasPages())
            <div style="margin-top:56px;display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;">

                {{-- Anterior --}}
                @if($restaurantes->onFirstPage())
                    <span class="pag-btn disabled">
                        <i class="fas fa-chevron-left" style="font-size:10px;"></i>
                    </span>
                @else
                    <a href="{{ $restaurantes->previousPageUrl() }}" class="pag-btn">
                        <i class="fas fa-chevron-left" style="font-size:10px;"></i>
                    </a>
                @endif

                {{-- Números --}}
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
                        <span style="color:#a8a29e;font-size:13px;padding:0 2px;">…</span>
                    @else
                        <a href="{{ $restaurantes->url($page) }}"
                           class="pag-btn {{ $page == $current ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Siguiente --}}
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

    {{-- ══════════════ FOOTER ══════════════ --}}
    <footer class="bg-stone-900 text-stone-300 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 pt-16 pb-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-9 gap-10 mb-12">
                <div class="lg:col-span-4 space-y-5">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    </div>
                    <p class="text-stone-400 text-sm leading-relaxed font-light">
                        La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Portal</h4>
                    <ul class="space-y-2.5 text-sm p-0 list-none m-0">
                        <li><a href="{{ route('home') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Inicio</a></li>
                        <li><a href="{{ route('restaurantes.index') }}" class="text-orange-500 font-semibold inline-block no-underline">Restaurantes</a></li>
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
            <div class="border-t border-stone-800 pt-8 text-center text-xs text-stone-500 font-light flex flex-col sm:flex-row justify-between items-center gap-4">
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

        const todosLosRestaurantes = window.__RESTAURANTES__ || [];

        // ── Filtro completo: depto → municipio → especialidad → local ──
        function configurarFiltros(deptoId, munId, especId, searchId) {
            const deptoSel   = document.getElementById(deptoId);
            const munSel     = document.getElementById(munId);
            const especInput = especId ? document.getElementById(especId) : null;
            const searchSel  = document.getElementById(searchId);
            if (!deptoSel || !munSel || !searchSel) return;

            const munOpts = Array.from(munSel.querySelectorAll('option[data-departamento]'));

            // ── Actualiza municipios visibles según departamento ──
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

            // ── Actualiza el select de locales según depto + municipio + especialidad ──
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

                if (depto) {
                    filtrados = filtrados.filter(r => String(r.departamento_id) === String(depto));
                }
                if (mun) {
                    filtrados = filtrados.filter(r => String(r.municipio_id) === String(mun));
                }
                if (espec) {
                    filtrados = filtrados.filter(r =>
                        r.especialidad && r.especialidad.toLowerCase().includes(espec)
                    );
                }

                filtrados.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.nombre;
                    opt.textContent = r.nombre;
                    if (r.nombre === currentSearch) opt.selected = true;
                    searchSel.appendChild(opt);
                });
            }

            // ── Eventos ──
            deptoSel.addEventListener('change', function () {
                actualizarMunicipios();
                actualizarLocales();
            });
            munSel.addEventListener('change', actualizarLocales);
            if (especInput) especInput.addEventListener('input', actualizarLocales);

            // ── Disparar al cargar (depto puede venir predefinido por login) ──
            actualizarMunicipios();
            actualizarLocales();
        }

        // Desktop
        configurarFiltros('nav-departamento', 'nav-municipio', 'nav-especialidad', 'nav-search');
        // Móvil
        configurarFiltros('mob-departamento', 'mob-municipio', 'mob-especialidad', 'mob-search');

        // ── Toggle búsqueda móvil ──
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
