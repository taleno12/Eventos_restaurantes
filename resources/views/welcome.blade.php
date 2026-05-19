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
                to { transform: scale(1.1); }
            }
            .animate-slow-zoom {
                animation: slowZoom 10s infinite alternate;
            }

            @keyframes slideInLeftCustom {
                from { transform: translateX(-100px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            .animate-slide-left {
                animation: slideInLeftCustom 1s ease-out forwards;
            }

            .glass-card {
                background: #ffffff;
                border: 1px solid #f1f0ee;
                border-radius: 2.5rem;
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .glass-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 30px 60px rgba(28, 25, 23, 0.08);
                border-color: #ffedd5;
            }

            .carousel-item { transition: transform 0.8s ease-in-out, opacity 0.8s ease-in-out; }
            .carousel-control-prev, .carousel-control-next { z-index: 20; width: 5%; }

            /* Navbar search styles */
            .nav-search-input {
                background: rgba(245, 245, 244, 0.8);
                border: 1px solid rgba(231, 229, 228, 0.8);
                border-radius: 9999px;
                padding: 0.45rem 1rem 0.45rem 2.5rem;
                font-size: 0.85rem;
                color: #1c1917;
                transition: all 0.2s ease;
                outline: none;
                width: 140px;
            }
            .nav-search-input:focus {
                border-color: #ea580c;
                box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
                background: #fff;
                width: 170px;
            }
            .nav-search-input::placeholder { color: #a8a29e; }

            .nav-select {
                background: rgba(245, 245, 244, 0.8);
                border: 1px solid rgba(231, 229, 228, 0.8);
                border-radius: 9999px;
                padding: 0.45rem 2rem 0.45rem 2.5rem;
                font-size: 0.85rem;
                color: #1c1917;
                appearance: none;
                cursor: pointer;
                transition: all 0.2s ease;
                outline: none;
                width: 100%;
            }
            .nav-select:focus {
                border-color: #ea580c;
                box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
                background: #fff;
            }

            /* Mobile search panel */
            #mobileSearchPanel {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(255,255,255,0.98);
                backdrop-filter: blur(12px);
                border-top: 1px solid #e7e5e4;
                padding: 1rem 1.5rem;
                z-index: 40;
                box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            }
            #mobileSearchPanel.open { display: block; }
        </style>
    </head>
    <body class="bg-stone-50 text-stone-900">

        <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-stone-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center gap-4">

                    <div class="flex items-center gap-2 shrink-0">
                        <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                            <i class="fas fa-utensils text-white"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight premium-title italic">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    </div>

                    {{-- Barra de búsqueda Desktop optimizada con filtrado en cascada Departamento -> Restaurante --}}
                    <form action="{{ route('home') }}" method="GET" class="hidden md:flex items-center gap-2 flex-1 max-w-3xl">

                        {{-- 1. Selección de Destino (Departamento) --}}
                        <div class="relative flex-1">
                            <i class="fas fa-map absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                            <select id="search-departamento" name="departamento" class="nav-select">
                                <option value="">Todos los destinos</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                        {{ $depto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Selección de Local (Restaurante filtrado de forma reactiva) --}}
                        <div class="relative flex-1">
                            <i class="fas fa-store absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                            <select id="search-restaurante" name="restaurante_id" class="nav-select" {{ request('departamento') ? '' : 'disabled' }}>
                                <option value="">Todos los locales</option>
                                @if(request('departamento'))
                                    @foreach($restaurantes->where('departamento_id', request('departamento')) as $rest)
                                        <option value="{{ $rest->id }}" {{ request('restaurante_id') == $rest->id ? 'selected' : '' }}>
                                            {{ $rest->nombre }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Filtro por Especialidad --}}
                        <div class="relative flex-1">
                            <i class="fas fa-utensils absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                            <input type="text" name="especialidad" value="{{ request('especialidad') }}"
                                   class="nav-search-input w-full"
                                   placeholder="Especialidad (Asados, Mariscos...)">
                        </div>

                        <button type="submit"
                                class="bg-orange-600 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-orange-700 transition-all shadow-md shadow-orange-200 flex items-center gap-2 shrink-0 border-0 cursor-pointer">
                            <span>Buscador</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </form>

                    <div class="flex items-center gap-3 shrink-0">
                        <button id="mobileSearchToggle"
                                class="md:hidden w-9 h-9 rounded-full bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-orange-100 hover:text-orange-600 transition-colors border-0 cursor-pointer">
                            <i class="fas fa-search text-sm"></i>
                        </button>

                        <a href="{{ route('empleos.index') }}"
                           class="hidden sm:flex items-center gap-2 border border-orange-200 text-orange-600 bg-orange-50 px-4 py-2 rounded-full text-sm font-semibold hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all shadow-sm group no-underline">
                            <i class="fas fa-briefcase text-xs group-hover:animate-bounce"></i>
                            <span class="hidden lg:inline">Empleos</span>
                            @if(isset($totalEmpleos) && $totalEmpleos > 0)
                                <span class="bg-orange-600 group-hover:bg-white group-hover:text-orange-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    {{ $totalEmpleos }}
                                </span>
                            @endif

                        </a>
                        


                        

                        <a href="{{ route('contacto') }}" class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors px-2 no-underline">
                            Contacto
                        </a>

                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold hover:text-orange-600 transition-colors no-underline">Panel Control</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-orange-600 transition-colors sm:inline no-underline">Ingresar</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel móvil optimizado en cascada --}}
            <div id="mobileSearchPanel">
                <form action="{{ route('home') }}" method="GET" class="flex flex-col gap-3">
                    <div class="relative">
                        <i class="fas fa-map absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                        <select id="search-departamento-mobile" name="departamento" class="nav-select w-full">
                            <option value="">Todos los destinos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                    {{ $depto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fas fa-store absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                        <select id="search-restaurante-mobile" name="restaurante_id" class="nav-select w-full" {{ request('departamento') ? '' : 'disabled' }}>
                            <option value="">Todos los locales</option>
                            @if(request('departamento'))
                                @foreach($restaurantes->where('departamento_id', request('departamento')) as $rest)
                                    <option value="{{ $rest->id }}" {{ request('restaurante_id') == $rest->id ? 'selected' : '' }}>
                                        {{ $rest->nombre }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fas fa-utensils absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                        <input type="text" name="especialidad" value="{{ request('especialidad') }}"
                               class="nav-search-input w-full"
                               placeholder="Especialidad (Asados, Mariscos...)">
                    </div>
                    <button type="submit"
                            class="bg-orange-600 text-white py-2.5 rounded-full text-sm font-semibold hover:bg-orange-700 transition-all flex items-center justify-center gap-2 border-0 cursor-pointer">
                        <span>Filtrar Experiencias</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>
        </nav>

        <section class="relative">
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
                                <div class="relative w-full h-[650px] overflow-hidden flex items-center text-white">
                                    <div class="absolute inset-0 z-0">
                                        <a href="{{ route('eventos.show', $evento->id) }}" class="block w-full h-full">
                                            <img src="{{ asset('storage/' . $evento->imagen) }}"
                                                 class="w-full h-full object-cover animate-slow-zoom"
                                                 alt="{{ $evento->titulo }}">
                                        </a>
                                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent pointer-events-none"></div>
                                    </div>

                                    <div class="relative z-10 container mx-auto px-6 md:px-20">
                                        <div class="{{ $key == 0 ? 'animate-slide-left' : '' }} carousel-content">
                                            <span class="bg-orange-600/90 backdrop-blur-sm text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-[0.2em] mb-6 inline-block">
                                                Evento Destacado
                                            </span>
                                            <h1 class="premium-title text-5xl md:text-8xl font-bold mb-6 leading-tight drop-shadow-2xl">
                                                <a href="{{ route('eventos.show', $evento->id) }}" class="text-white hover:text-orange-400 transition-colors no-underline">
                                                    {{ $evento->titulo }}
                                                </a>
                                            </h1>
                                            <p class="text-lg md:text-2xl mb-10 max-w-2xl text-stone-200 drop-shadow-md leading-relaxed">
                                                {{ Str::limit($evento->descripcion, 150) }}
                                            </p>

                                            <div class="flex flex-wrap gap-6">
                                                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-xl">
                                                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-map-marker-alt text-white"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] uppercase text-stone-300 font-bold tracking-widest">Ubicación</p>
                                                        <p class="font-bold">{{ $evento->restaurante->nombre }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-xl">
                                                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-calendar-alt text-white"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] uppercase text-stone-300 font-bold tracking-widest">Fecha</p>
                                                        <p class="font-bold">{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d M, Y') }}</p>
                                                    </div>
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
                <div class="bg-stone-900 w-full h-[400px] flex items-center justify-center text-white">
                    <h1 class="premium-title text-4xl">Gastro Nicaragua</h1>
                </div>
            @endif
        </section>

        <main class="max-w-7xl mx-auto px-4 py-24">
            
            {{-- Badge informativo cuando se filtra activamente por Especialidad, Nombre o Destino --}}
            @if(request('search') || request('departamento') || request('especialidad') || request('restaurante_id'))
                <div class="mb-10 flex items-center gap-2 flex-wrap bg-stone-100 p-3 rounded-xl inline-flex text-sm">
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

            <div class="flex justify-between items-end mb-16">
                <div>
                    <h2 class="premium-title text-4xl md:text-5xl font-bold mb-4">Próximos Eventos</h2>
                    <div class="h-1.5 w-24 bg-orange-600 rounded-full"></div>
                </div>
            </div>

            {{-- MODIFICADO: Estructura de tarjetas horizontales premium segmentadas en Grid tipo Listado Ejecutivo --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                @forelse($eventos as $item)
                    <article class="glass-card overflow-hidden grid grid-cols-1 md:grid-cols-12 shadow-sm border border-stone-200/60" data-aos="fade-up">
                        
                        {{-- Contenedor de Textos (Lado Izquierdo - 7 Columnas) --}}
                        <div class="p-8 md:col-span-7 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="bg-stone-100 text-stone-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                        <i class="fas fa-map-marker-alt text-orange-600 mr-1"></i> {{ $item->departamento->nombre }}
                                    </span>
                                    @if($item->restaurante->especialidad)
                                        <span class="bg-orange-50 text-orange-700 text-[9px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider">
                                            {{ $item->restaurante->especialidad }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="premium-title text-2xl font-bold text-stone-900 leading-tight mb-2 hover:text-orange-600 transition-colors">
                                    <a href="{{ route('eventos.show', $item->id) }}" class="no-underline text-stone-900 hover:text-orange-600">
                                        {{ $item->titulo }}
                                    </a>
                                </h3>

                                <p class="text-stone-500 text-sm leading-relaxed line-clamp-3 font-normal">
                                    {{ $item->descripcion }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-stone-100 flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-2 text-stone-600">
                                    <i class="far fa-calendar-alt text-stone-400 text-sm"></i>
                                    <span class="text-xs font-bold">
                                        {{ \Carbon\Carbon::parse($item->fecha_evento)->translatedFormat('d M, Y') }}
                                    </span>
                                </div>
                                <div class="bg-red-50 px-3 py-1.5 rounded-xl border border-red-100/50">
                                    <span class="countdown text-[10px] font-black text-red-600 uppercase tracking-wider" data-expire="{{ $item->fecha_evento }}">
                                        Cargando...
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Contenedor de Imagen de la Tarjeta (Lado Derecho - 5 Columnas) --}}
                        <div class="relative h-60 md:h-full min-h-[240px] md:col-span-5 overflow-hidden order-first md:order-last bg-stone-100">
                            <a href="{{ route('eventos.show', $item->id) }}" class="block w-full h-full">
                                <img src="{{ asset('storage/' . $item->imagen) }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" alt="{{ $item->titulo }}">
                            </a>
                            <div class="absolute bottom-4 left-4">
                                <div class="bg-stone-900/90 backdrop-blur-md text-white px-4 py-1.5 rounded-xl shadow-lg border border-white/10 font-bold text-sm">
                                    C$ {{ number_format($item->precio, 0) }}
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
                        <h3 class="premium-title text-3xl font-extrabold text-stone-900 mb-3 tracking-tight">Sin experiences culinarias</h3>
                        <p class="text-stone-500 text-base leading-relaxed mb-8 font-light">
                            No logramos encontrar eventos activos que coincidan con los filtros seleccionados en este momento. ¡Prueba ajustando tu búsqueda gastronómica!
                        </p>
                        @if(request('especialidad') || request('departamento') || request('restaurante_id') || request('search'))
                            <a href="{{ route('home') }}" 
                               class="bg-stone-900 text-stone-50 text-xs font-bold tracking-wider uppercase px-6 py-3.5 rounded-xl no-underline hover:bg-orange-600 transition-all shadow-md shadow-stone-900/10 active:scale-95 border-0 cursor-pointer flex items-center gap-2">
                                <i class="fas fa-undo text-[10px]"></i> Limpiar Filtros Aplicados
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </main>

        <footer class="bg-stone-900 text-stone-300 border-t border-stone-800">
            <div class="max-w-7xl mx-auto px-4 pt-16 pb-8 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 mb-12">
                    
                    {{-- Columna 1: Info Corporativa --}}
                    <div class="lg:col-span-4 space-y-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 bg-orange-600 rounded-xl flex items-center justify-center shadow-md shadow-orange-600/20">
                                <i class="fas fa-utensils text-white text-xs"></i>
                            </div>
                            <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-orange-600">Nicaragua</span></span>
                        </div>
                        <p class="text-stone-400 text-sm leading-relaxed font-light">
                            La plataforma líder en promoción turística y eventos culinarios de Nicaragua. Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                        </p>
                        <div class="flex items-center gap-3 pt-2">
                            <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>

                    {{-- Columna 2: Enlaces Rápidos --}}
                    <div class="lg:col-span-2 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Portal</h4>
                        <ul class="space-y-2.5 text-sm p-0 list-none m-0">
                            <li><a href="{{ route('home') }}" class="text-stone-400 hover:text-orange-500 hover:translate-x-1 transition-all inline-block no-underline">Inicio</a></li>
                            <li><a href="{{ route('empleos.index') }}" class="text-stone-400 hover:text-orange-500 hover:translate-x-1 transition-all inline-block no-underline">Bolsa de Empleos</a></li>
                            <li><a href="{{ route('contacto') }}" class="text-stone-400 hover:text-orange-500 hover:translate-x-1 transition-all inline-block no-underline">Contacto</a></li>
                            <li><a href="{{ route('login') }}" class="text-stone-400 hover:text-orange-500 hover:translate-x-1 transition-all inline-block no-underline">Acceso Administrativo</a></li>
                        </ul>
                    </div>

                    {{-- Columna 3: Destinos Frecuentes --}}
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

                    {{-- Columna 4: Boletín --}}
                    <div class="lg:col-span-3 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Newsletter</h4>
                        <p class="text-stone-400 text-xs leading-relaxed font-light">Recibe directamente en tu bandeja las mejores agendas culinarias de la semana.</p>
                        <form class="flex flex-col gap-2">
                            <input type="email" placeholder="Tu correo electrónico" class="bg-stone-800 text-white border border-stone-700 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-orange-500">
                            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold py-2 rounded-xl border-0 cursor-pointer transition-colors">Suscribirse</button>
                        </form>
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
            // Inicializar animaciones AOS
            AOS.init({ duration: 800, once: true });

            // Pasar la colección de restaurantes limpia desde Blade a JavaScript para el filtrado reactivo
            const todosLosRestaurantes = @json($restaurantes->values());

            // Lógica común de filtrado en cascada
            function configurarFiltroCascada(selectDeptoId, selectRestId) {
                const deptoSelect = document.getElementById(selectDeptoId);
                const restSelect = document.getElementById(selectRestId);

                if (!deptoSelect || !restSelect) return;

                deptoSelect.addEventListener('change', function() {
                    const deptoId = this.value;
                    
                    // Limpiar opciones anteriores del selector de restaurantes
                    restSelect.innerHTML = '<option value="">Todos los locales</option>';

                    if (!deptoId) {
                        restSelect.disabled = true;
                        return;
                    }

                    // Filtrar los restaurantes pertenecientes al departamento seleccionado
                    const filtrados = todosLosRestaurantes.filter(r => r.departamento_id == deptoId);

                    filtrados.forEach(restaurante => {
                        const opt = document.createElement('option');
                        opt.value = restaurante.id;
                        opt.textContent = restaurante.nombre;
                        restSelect.appendChild(opt);
                    });

                    restSelect.disabled = false;
                });
            }

            // Inicializar cascada en Escritorio y Móviles
            configurarFiltroCascada('search-departamento', 'search-restaurante');
            configurarFiltroCascada('search-departamento-mobile', 'search-restaurante-mobile');

            // Control de apertura/cierre del panel de búsqueda móvil
            const mobileSearchToggle = document.getElementById('mobileSearchToggle');
            const mobileSearchPanel = document.getElementById('mobileSearchPanel');

            if (mobileSearchToggle && mobileSearchPanel) {
                mobileSearchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileSearchPanel.classList.toggle('open');
                });

                // Cerrar panel al hacer clic afuera
                document.addEventListener('click', function(e) {
                    if (!mobileSearchPanel.contains(e.target) && e.target !== mobileSearchToggle) {
                        mobileSearchPanel.classList.remove('open');
                    }
                });
            }

            // Temporizador en reversa (Countdown) para las tarjetas de eventos
            document.querySelectorAll('.countdown').forEach(el => {
                const targetDateStr = el.getAttribute('data-expire');
                if (!targetDateStr) return;

                const targetDate = new Date(targetDateStr.replace(/-/g, "/")).getTime();

                const interval = setInterval(() => {
                    const now = new Date().getTime();
                    const diff = targetDate - now;

                    if (diff <= 0) {
                        el.textContent = "Finalizado / En Curso";
                        el.className = "text-[10px] font-bold text-stone-400 uppercase tracking-wider";
                        clearInterval(interval);
                        return;
                    }

                    const dias = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const horas = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                    if (dias > 0) {
                        el.textContent = `Faltan ${dias} d y ${horas} h`;
                    } else if (horas > 0) {
                        el.textContent = `Faltan ${horas} h y ${minutos} m`;
                    } else {
                        el.textContent = `Inicia en menos de 1 h`;
                    }
                }, 1000);
            });
        </script>
    </body>
</html>