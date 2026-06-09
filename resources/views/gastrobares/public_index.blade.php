{{-- resources/views/gastrobares/public_index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gastrobares | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- ▼ TODOS LOS GASTROBARES PARA EL FILTRO JS ▼ --}}
    <script>
        window.__GASTROBARES__ = @json($gastrobares->values());
    </script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; scroll-behavior: smooth; }
        .premium-title { font-family: 'Playfair Display', serif; }

        @keyframes heroFloat {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }
        .hero-icon { animation: heroFloat 4s ease-in-out infinite; }

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

        /* ══ CARDS ══ */
        .rest-card {
            background: #fff;
            border: 1px solid rgba(28,25,23,0.05);
            border-radius: 2rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }
        .rest-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(28,25,23,0.08);
            border-color: rgba(234,88,12,0.15);
        }
        .rest-card:hover .card-img img { transform: scale(1.05); }
        .card-img img { transition: transform 0.8s cubic-bezier(0.16,1,0.3,1); }

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
                <form action="{{ route('gastrobares.index') }}" method="GET"
                      class="hidden md:flex flex-1 search-box" style="min-width:0;">

                    {{-- 1. DESTINO --}}
                    <div class="search-segment">
                        <label for="nav-departamento">
                            <i class="fas fa-map-marker-alt" style="color:#ea580c;font-size:8px;"></i> Destino
                        </label>
                        <select id="nav-departamento" name="departamento">
                            <option value="">Todos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ (request('departamento') ?? $departamentoPredefinido) == $depto->id ? 'selected' : '' }}>
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
                                        {{ (request('municipio') ?? $municipioPredefinido) == $mun->id ? 'selected' : '' }}
                                        style="{{ (request('departamento') ?? $departamentoPredefinido) && $mun->departamento_id == (request('departamento') ?? $departamentoPredefinido) ? '' : 'display:none' }}">
                                    {{ $mun->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. TIPO DE BAR --}}
                    <div class="search-segment">
                        <label for="nav-tipo_bar">
                            <i class="fas fa-cocktail" style="color:#ea580c;font-size:8px;"></i> Tipo de Bar
                        </label>
                        <select id="nav-tipo_bar" name="tipo_bar">
                            <option value="">Todos</option>
                            @foreach(['Cocktail Bar','Sports Bar','Rooftop Bar','Lounge Bar','Bar de Tapas','Bar de Vinos','Bar de Cervezas','Otro'] as $tipo)
                                <option value="{{ $tipo }}" {{ request('tipo_bar') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. AMBIENTE --}}
                    <div class="search-segment">
                        <label for="nav-ambiente">
                            <i class="fas fa-chair" style="color:#ea580c;font-size:8px;"></i> Ambiente
                        </label>
                        <select id="nav-ambiente" name="ambiente">
                            <option value="">Todos</option>
                            @foreach(['Interior','Exterior','Rooftop','Mixto'] as $amb)
                                <option value="{{ $amb }}" {{ request('ambiente') == $amb ? 'selected' : '' }}>{{ $amb }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 5. LOCAL --}}
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

                    <button id="mobileSearchToggle"
                            class="md:hidden w-9 h-9 rounded-full bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-orange-100 hover:text-orange-600 transition-colors border-0 cursor-pointer">
                        <i class="fas fa-search text-sm"></i>
                    </button>

                    <a href="{{ route('home') }}"
                       class="flex items-center gap-1.5 border border-stone-200 text-stone-600 bg-white px-3 py-2 rounded-full text-sm font-semibold hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all shadow-sm no-underline">
                        <i class="fas fa-home text-xs"></i>
                        <span class="hidden lg:inline">Inicio</span>
                    </a>

                    <a href="{{ route('gastrobares.index') }}"
                       class="flex items-center gap-1.5 border border-orange-600 text-white bg-orange-600 px-3 py-2 rounded-full text-sm font-semibold shadow-sm no-underline">
                        <i class="fas fa-cocktail text-xs"></i>
                        <span class="hidden lg:inline">Gastrobares</span>
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
            <form action="{{ route('gastrobares.index') }}" method="GET" class="flex flex-col gap-3">

                {{-- 1. DESTINO --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt text-orange-500"></i> Destino
                    </label>
                    <select id="mob-departamento" name="departamento" class="nav-select-mobile">
                        <option value="">Todos los destinos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" {{ (request('departamento') ?? $departamentoPredefinido) == $depto->id ? 'selected' : '' }}>{{ $depto->nombre }}</option>
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
                                    {{ (request('municipio') ?? $municipioPredefinido) == $mun->id ? 'selected' : '' }}
                                    style="{{ (request('departamento') ?? $departamentoPredefinido) && $mun->departamento_id == (request('departamento') ?? $departamentoPredefinido) ? '' : 'display:none' }}">
                                {{ $mun->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. TIPO DE BAR --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                        <i class="fas fa-cocktail text-orange-500"></i> Tipo de Bar
                    </label>
                    <select id="mob-tipo_bar" name="tipo_bar" class="nav-select-mobile">
                        <option value="">Todos</option>
                        @foreach(['Cocktail Bar','Sports Bar','Rooftop Bar','Lounge Bar','Bar de Tapas','Bar de Vinos','Bar de Cervezas','Otro'] as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo_bar') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. AMBIENTE --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-stone-400 flex items-center gap-1.5">
                        <i class="fas fa-chair text-orange-500"></i> Ambiente
                    </label>
                    <select id="mob-ambiente" name="ambiente" class="nav-select-mobile">
                        <option value="">Todos</option>
                        @foreach(['Interior','Exterior','Rooftop','Mixto'] as $amb)
                            <option value="{{ $amb }}" {{ request('ambiente') == $amb ? 'selected' : '' }}>{{ $amb }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. LOCAL --}}
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
                    <i class="fas fa-search text-xs"></i> Buscar Gastrobares
                </button>
            </form>
        </div>
    </nav>

    {{-- ══════════════ HERO PREMIUM ══════════════ --}}
    <section class="pt-20">
        <div class="relative overflow-hidden" style="min-height:520px;">

            <div class="absolute inset-0"
                 style="background-image:url('{{ asset('img/coctel.jpeg') }}');
                        background-size:cover;background-position:center;
                        transform:scale(1.04);transition:transform 8s ease;">
            </div>

            <div class="absolute inset-0" style="background:linear-gradient(135deg, rgba(12,10,9,0.88) 0%, rgba(12,10,9,0.72) 50%, rgba(180,60,0,0.45) 100%);"></div>
            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image:radial-gradient(circle, #fff 1px, transparent 1px);background-size:28px 28px;"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] opacity-20 pointer-events-none"
                 style="background:radial-gradient(circle at 70% 30%, #ea580c 0%, transparent 65%);"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] opacity-10 pointer-events-none"
                 style="background:radial-gradient(circle at 20% 80%, #f97316 0%, transparent 60%);"></div>

            <div class="relative max-w-7xl mx-auto px-4 py-24 sm:py-28 flex flex-col md:flex-row items-center justify-between gap-12">

                <div class="flex-1 max-w-2xl" data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 mb-6"
                         style="background:rgba(234,88,12,0.15);border:1px solid rgba(234,88,12,0.4);padding:6px 16px;border-radius:999px;">
                        <span style="width:7px;height:7px;background:#ea580c;border-radius:50%;display:inline-block;box-shadow:0 0 8px rgba(234,88,12,0.8);animation:pulse 2s infinite;"></span>
                        <span style="color:#fb923c;font-size:11px;font-weight:800;letter-spacing:0.18em;text-transform:uppercase;">
                            <i class="fas fa-map-marker-alt mr-1" style="font-size:9px;"></i> Toda Nicaragua
                        </span>
                    </div>

                    <h1 class="premium-title mb-5 leading-tight"
                        style="font-size:clamp(2.8rem,5vw,4.2rem);font-weight:900;color:white;line-height:1.05;">
                        Nuestros<br>
                        <span style="color:#f97316;font-style:italic;font-weight:400;">Gastrobares</span>
                    </h1>

                    <p style="color:rgba(255,255,255,0.6);font-size:15px;line-height:1.8;max-width:520px;margin-bottom:32px;font-weight:300;">
                        Desde cocktail bars íntimos hasta rooftops con vistas — descubre los mejores espacios para tomar y disfrutar en Nicaragua.
                    </p>

                    <div class="flex flex-wrap gap-3 mb-8">
                        <span style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.75);font-size:12px;font-weight:600;padding:8px 16px;border-radius:999px;display:inline-flex;align-items:center;gap:7px;backdrop-filter:blur(8px);">
                            <i class="fas fa-check-circle" style="color:#22c55e;font-size:11px;"></i> Información verificada
                        </span>
                        <span style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.75);font-size:12px;font-weight:600;padding:8px 16px;border-radius:999px;display:inline-flex;align-items:center;gap:7px;backdrop-filter:blur(8px);">
                            <i class="fas fa-clock" style="color:#f59e0b;font-size:11px;"></i> Horarios actualizados
                        </span>
                        <span style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.75);font-size:12px;font-weight:600;padding:8px 16px;border-radius:999px;display:inline-flex;align-items:center;gap:7px;backdrop-filter:blur(8px);">
                            <i class="fas fa-map-marker-alt" style="color:#ea580c;font-size:11px;"></i> Ubicación en mapa
                        </span>
                    </div>

                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="height:1px;width:48px;background:linear-gradient(to right,#ea580c,transparent);"></div>
                        <span style="font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:rgba(255,255,255,0.3);">Gastro Nicaragua</span>
                    </div>
                </div>

                <div class="shrink-0 hidden md:flex flex-col items-center gap-5" data-aos="fade-left">
                    <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.14);backdrop-filter:blur(20px);border-radius:28px;padding:36px 40px;display:flex;flex-direction:column;align-items:center;gap:16px;box-shadow:0 24px 64px rgba(0,0,0,0.3),inset 0 1px 0 rgba(255,255,255,0.1);min-width:220px;">
                        <div style="width:80px;height:80px;background:linear-gradient(135deg,#ea580c,#f97316);border-radius:24px;display:flex;align-items:center;justify-content:center;box-shadow:0 12px 32px rgba(234,88,12,0.5);animation:heroFloat 4s ease-in-out infinite;">
                            <i class="fas fa-cocktail" style="color:white;font-size:32px;"></i>
                        </div>
                        <div style="text-align:center;">
                            <p style="color:white;font-size:28px;font-weight:900;line-height:1;margin-bottom:4px;">{{ $gastrobares->total() }}</p>
                            <p style="color:rgba(255,255,255,0.5);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;">Gastrobares</p>
                        </div>
                        <div style="width:100%;height:1px;background:rgba(255,255,255,0.08);"></div>
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
                    <p style="color:rgba(255,255,255,0.3);font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;">Vida nocturna auténtica 🇳🇮</p>
                </div>
            </div>

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

        {{-- Filtros activos + contador --}}
        <div class="mb-8 flex flex-wrap items-center gap-2">
            @if(request('departamento') || request('municipio') || request('tipo_bar') || request('ambiente') || request('search'))
                <span class="text-stone-500 font-medium text-sm pl-1">Filtros:</span>

                @if(request('departamento'))
                    <span class="filter-pill">
                        <i class="fas fa-map-marker-alt" style="font-size:9px;opacity:0.7;"></i>
                        {{ $departamentos->find(request('departamento'))?->nombre }}
                        <a href="{{ request()->fullUrlWithoutQuery(['departamento', 'municipio']) }}">×</a>
                    </span>
                @endif
                @if(request('municipio'))
                    <span class="filter-pill">
                        <i class="fas fa-city" style="font-size:9px;opacity:0.7;"></i>
                        {{ $municipios->find(request('municipio'))?->nombre }}
                        <a href="{{ request()->fullUrlWithoutQuery(['municipio']) }}">×</a>
                    </span>
                @endif
                @if(request('tipo_bar'))
                    <span class="filter-pill">
                        <i class="fas fa-cocktail" style="font-size:9px;opacity:0.7;"></i>
                        {{ request('tipo_bar') }}
                        <a href="{{ request()->fullUrlWithoutQuery(['tipo_bar']) }}">×</a>
                    </span>
                @endif
                @if(request('ambiente'))
                    <span class="filter-pill">
                        <i class="fas fa-chair" style="font-size:9px;opacity:0.7;"></i>
                        {{ request('ambiente') }}
                        <a href="{{ request()->fullUrlWithoutQuery(['ambiente']) }}">×</a>
                    </span>
                @endif
                @if(request('search'))
                    <span class="filter-pill">
                        <i class="fas fa-search" style="font-size:9px;opacity:0.7;"></i>
                        "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithoutQuery(['search']) }}">×</a>
                    </span>
                @endif

                <a href="{{ route('gastrobares.index') }}"
                   class="text-stone-400 hover:text-red-500 text-xs font-semibold no-underline flex items-center gap-1 ml-1">
                    <i class="fas fa-times-circle text-xs"></i> Limpiar todo
                </a>
            @endif

            <div class="ml-auto flex items-center gap-2">
                <div style="height:1px;width:32px;background:linear-gradient(to right,#ea580c,transparent);"></div>
                <span style="font-size:11px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;">
                    {{ $gastrobares->total() }} gastrobar{{ $gastrobares->total() != 1 ? 'es' : '' }}
                </span>
            </div>
        </div>

        {{-- Grid --}}
        @forelse($gastrobares as $gastrobar)
            @if($loop->first)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @endif

            <article class="rest-card" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}"
                     onclick="window.location='{{ route('gastrobares.show', $gastrobar->id) }}'">

                {{-- Imagen --}}
                <div class="card-img" style="position:relative;height:240px;overflow:hidden;background:#f5f5f4;">
                    @if($gastrobar->imagen_principal)
                        <img src="{{ asset('storage/' . $gastrobar->imagen_principal) }}"
                             alt="{{ $gastrobar->nombre }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:56px;">🍹</div>
                    @endif

                    @if($gastrobar->departamento)
                        <div style="position:absolute;top:14px;left:14px;">
                            <span style="background:rgba(28,25,23,0.75);backdrop-filter:blur(8px);color:#fff;font-size:11px;font-weight:500;padding:5px 10px;border-radius:999px;display:inline-flex;align-items:center;gap:6px;">
                                <i class="fas fa-map-marker-alt" style="color:#fb923c;font-size:9px;"></i>
                                {{ $gastrobar->departamento->nombre }}
                                @if($gastrobar->municipio)
                                    <span style="opacity:0.5;">·</span>{{ $gastrobar->municipio->nombre }}
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($gastrobar->tipo_bar)
                        <div style="position:absolute;top:14px;right:14px;">
                            <span style="background:rgba(255,255,255,0.9);color:#c2410c;font-size:10px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;padding:4px 12px;border-radius:999px;border:1px solid rgba(234,88,12,0.1);">
                                {{ $gastrobar->tipo_bar }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Contenido --}}
                <div style="padding:24px;display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <h3 class="premium-title" style="font-size:20px;font-weight:700;color:#1c1917;margin-bottom:6px;line-height:1.3;">
                            {{ $gastrobar->nombre }}
                        </h3>
                        @if($gastrobar->descripcion)
                            <p style="color:#78716c;font-size:13px;line-height:1.6;font-weight:400;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $gastrobar->descripcion }}
                            </p>
                        @endif
                    </div>

                    <div style="display:flex;flex-direction:column;gap:8px;font-size:12px;color:#78716c;border-top:1px solid #f5f5f4;padding-top:12px;">
                        @if($gastrobar->direccion)
                            <span style="display:flex;align-items:flex-start;gap:8px;">
                                <i class="fas fa-map-marker-alt" style="color:#a8a29e;font-size:11px;margin-top:1px;flex-shrink:0;"></i>
                                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $gastrobar->direccion }}</span>
                            </span>
                        @endif
                        @if($gastrobar->hora_apertura && $gastrobar->hora_cierre)
                            <span style="display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-clock" style="color:#a8a29e;font-size:11px;flex-shrink:0;"></i>
                                {{ \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') }} –
                                {{ \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') }}
                            </span>
                        @endif
                        @if($gastrobar->tipo_musica)
                            <span style="display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-music" style="color:#a8a29e;font-size:11px;flex-shrink:0;"></i>
                                {{ $gastrobar->tipo_musica }}
                            </span>
                        @endif
                        @if($gastrobar->capacidad)
                            <span style="display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-users" style="color:#a8a29e;font-size:11px;flex-shrink:0;"></i>
                                Capacidad: {{ $gastrobar->capacidad }} personas
                            </span>
                        @endif
                    </div>

                    @if($gastrobar->whatsapp || $gastrobar->instagram || $gastrobar->facebook || $gastrobar->tiktok)
                        <div style="display:flex;align-items:center;gap:8px;" onclick="event.stopPropagation();">
                            @if($gastrobar->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $gastrobar->whatsapp) }}" target="_blank"
                                   style="width:28px;height:28px;border-radius:50%;background:#f0fdf4;border:1px solid #bbf7d0;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <i class="fab fa-whatsapp" style="color:#16a34a;font-size:12px;"></i>
                                </a>
                            @endif
                            @if($gastrobar->instagram)
                                <a href="{{ $gastrobar->instagram }}" target="_blank"
                                   style="width:28px;height:28px;border-radius:50%;background:#fdf4ff;border:1px solid #f5d0fe;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <i class="fab fa-instagram" style="color:#a21caf;font-size:12px;"></i>
                                </a>
                            @endif
                            @if($gastrobar->facebook)
                                <a href="{{ $gastrobar->facebook }}" target="_blank"
                                   style="width:28px;height:28px;border-radius:50%;background:#eff6ff;border:1px solid #bfdbfe;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <i class="fab fa-facebook-f" style="color:#1d4ed8;font-size:12px;"></i>
                                </a>
                            @endif
                            @if($gastrobar->tiktok)
                                <a href="{{ $gastrobar->tiktok }}" target="_blank"
                                   style="width:28px;height:28px;border-radius:50%;background:#f9fafb;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <i class="fab fa-tiktok" style="color:#1c1917;font-size:12px;"></i>
                                </a>
                            @endif
                        </div>
                    @endif

                    <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f5f5f4;padding-top:12px;">
                        @if($gastrobar->ambiente)
                            <span style="font-size:11px;font-weight:600;color:#a8a29e;">
                                <i class="fas fa-chair" style="margin-right:4px;opacity:0.6;"></i>
                                {{ $gastrobar->ambiente }}
                            </span>
                        @else
                            <span></span>
                        @endif
                    </div>
                </div>
            </article>

            @if($loop->last)
                </div>
            @endif

        @empty
            <div style="padding:80px 0;display:flex;flex-direction:column;align-items:center;text-align:center;max-width:380px;margin:0 auto;" data-aos="fade-up">
                <div style="width:72px;height:72px;background:#fafaf9;border:1px solid #e7e5e4;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:20px;position:relative;">
                    <i class="fas fa-cocktail" style="color:#a8a29e;"></i>
                    <div style="position:absolute;bottom:-2px;right:-2px;width:24px;height:24px;background:#ea580c;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-times" style="color:#fff;font-size:9px;"></i>
                    </div>
                </div>
                <h3 class="premium-title" style="font-size:22px;font-weight:700;color:#1c1917;margin-bottom:8px;">Sin resultados</h3>
                <p style="font-size:14px;color:#78716c;line-height:1.6;margin-bottom:24px;">
                    No encontramos gastrobares con esos filtros.
                </p>
                @if(request('departamento') || request('municipio') || request('tipo_bar') || request('ambiente') || request('search'))
                    <a href="{{ route('gastrobares.index') }}"
                       style="background:#1c1917;color:#fff;border:none;cursor:pointer;font-size:12px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:10px 22px;border-radius:999px;text-decoration:none;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-undo" style="font-size:10px;"></i> Limpiar filtros
                    </a>
                @endif
            </div>
        @endforelse

        {{-- Paginación --}}
        @if($gastrobares->hasPages())
            <div style="margin-top:56px;display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;">

                @if($gastrobares->onFirstPage())
                    <span class="pag-btn disabled"><i class="fas fa-chevron-left" style="font-size:10px;"></i></span>
                @else
                    <a href="{{ $gastrobares->previousPageUrl() }}" class="pag-btn">
                        <i class="fas fa-chevron-left" style="font-size:10px;"></i>
                    </a>
                @endif

                @php
                    $current = $gastrobares->currentPage();
                    $last    = $gastrobares->lastPage();
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
                        <a href="{{ $gastrobares->url($page) }}"
                           class="pag-btn {{ $page == $current ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if($gastrobares->hasMorePages())
                    <a href="{{ $gastrobares->nextPageUrl() }}" class="pag-btn">
                        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                    </a>
                @else
                    <span class="pag-btn disabled"><i class="fas fa-chevron-right" style="font-size:10px;"></i></span>
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
                        <li><a href="{{ route('restaurantes.index') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Restaurantes</a></li>
                        <li><a href="{{ route('gastrobares.index') }}" class="text-orange-500 font-semibold inline-block no-underline">Gastrobares</a></li>
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

        const todosLosGastrobares = window.__GASTROBARES__ || [];

        function configurarFiltros(deptoId, munId, tipoId, ambienteId, searchId) {
            const deptoSel  = document.getElementById(deptoId);
            const munSel    = document.getElementById(munId);
            const tipoSel   = document.getElementById(tipoId);
            const ambSel    = document.getElementById(ambienteId);
            const searchSel = document.getElementById(searchId);
            if (!deptoSel || !munSel || !searchSel) return;

            const munOpts = Array.from(munSel.querySelectorAll('option[data-departamento]'));
            const currentSearch = '{{ request("search") }}';

            function actualizarMunicipios() {
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

            function actualizarLocales() {
                const depto = deptoSel.value;
                const mun   = munSel.value;
                const tipo  = tipoSel  ? tipoSel.value  : '';
                const amb   = ambSel   ? ambSel.value   : '';

                searchSel.innerHTML = '';
                const ph = document.createElement('option');
                ph.value = '';
                ph.textContent = 'Todos los locales';
                searchSel.appendChild(ph);

                let filtrados = todosLosGastrobares;
                if (depto) filtrados = filtrados.filter(g => String(g.departamento_id) === String(depto));
                if (mun)   filtrados = filtrados.filter(g => String(g.municipio_id) === String(mun));
                if (tipo)  filtrados = filtrados.filter(g => g.tipo_bar === tipo);
                if (amb)   filtrados = filtrados.filter(g => g.ambiente === amb);

                filtrados.forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g.nombre;
                    opt.textContent = g.nombre;
                    if (g.nombre === currentSearch) opt.selected = true;
                    searchSel.appendChild(opt);
                });
            }

            deptoSel.addEventListener('change', () => { actualizarMunicipios(); actualizarLocales(); });
            munSel.addEventListener('change', actualizarLocales);
            if (tipoSel) tipoSel.addEventListener('change', actualizarLocales);
            if (ambSel)  ambSel.addEventListener('change',  actualizarLocales);

            actualizarMunicipios();
            actualizarLocales();
        }

        // Desktop
        configurarFiltros('nav-departamento', 'nav-municipio', 'nav-tipo_bar', 'nav-ambiente', 'nav-search');
        // Móvil
        configurarFiltros('mob-departamento', 'mob-municipio', 'mob-tipo_bar', 'mob-ambiente', 'mob-search');

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
