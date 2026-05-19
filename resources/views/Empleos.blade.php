<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gastro Nicaragua | Ofertas de Empleo</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; }
            .premium-title { font-family: 'Playfair Display', serif; }

            .job-card {
                background: #fff;
                border: 1px solid #f1f0ee;
                border-radius: 1.75rem;
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .job-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 30px 60px rgba(28, 25, 23, 0.07);
                border-color: #ffedd5;
            }

            .badge-tipo {
                font-size: 0.65rem;
                font-weight: 700;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                padding: 0.4rem 0.85rem;
                border-radius: 9999px;
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
            }

            .nav-search-input {
                background: #f5f5f4;
                border: 1px solid transparent;
                border-radius: 9999px;
                padding: 0.55rem 1rem 0.55rem 2.5rem;
                font-size: 0.85rem;
                color: #1c1917;
                transition: all 0.25s ease;
                outline: none;
            }
            .nav-search-input:focus {
                border-color: #ea580c;
                box-shadow: 0 0 0 4px rgba(234,88,12,0.08);
                background: #fff;
            }

            .nav-select {
                background: #f5f5f4;
                border: 1px solid transparent;
                border-radius: 9999px;
                padding: 0.55rem 2.5rem 0.55rem 2.5rem;
                font-size: 0.85rem;
                color: #1c1917;
                appearance: none;
                cursor: pointer;
                transition: all 0.25s ease;
                outline: none;
            }
            .nav-select:focus {
                border-color: #ea580c;
                box-shadow: 0 0 0 4px rgba(234,88,12,0.08);
                background: #fff;
            }

            #mobileSearchPanel {
                display: none;
                position: absolute;
                top: 100%;
                left: 0; right: 0;
                background: rgba(255,255,255,0.98);
                backdrop-filter: blur(12px);
                border-top: 1px solid #e7e5e4;
                padding: 1.25rem 1.5rem;
                z-index: 40;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            }
            #mobileSearchPanel.open { display: block; }

            .hero-empleo {
                background: radial-gradient(circle at top right, #3c1105 0%, #1c1917 100%);
            }
        </style>
    </head>
    <body class="bg-[#faf9f6] text-stone-900 antialiased">

        <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-stone-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center gap-4">

                    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 no-underline">
                        <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-md shadow-orange-500/20">
                            <i class="fas fa-utensils text-white text-sm"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight premium-title italic text-stone-900">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    </a>

                    <form action="{{ route('empleos.index') }}" method="GET"
                          class="hidden md:flex items-center gap-2 flex-1 max-w-2xl">
                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="nav-search-input w-full"
                                   placeholder="Puesto o restaurante...">
                        </div>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                            <select name="departamento" class="nav-select">
                                <option value="">Todos los destinos</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                        {{ $depto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <button type="submit"
                                class="bg-stone-950 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-orange-600 transition-all shadow-sm flex items-center gap-2 shrink-0 border-0 cursor-pointer">
                            <span>Filtrar</span>
                            <i class="fas fa-sliders-h text-xs opacity-80"></i>
                        </button>
                    </form>

                    <div class="flex items-center gap-3 shrink-0">
                        <button id="mobileSearchToggle"
                                class="md:hidden w-10 h-10 rounded-full bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-orange-50 hover:text-orange-600 transition-colors border-0 cursor-pointer">
                            <i class="fas fa-search text-sm"></i>
                        </button>

                        <a href="{{ route('home') }}"
                           class="hidden sm:flex items-center gap-2 border border-stone-200 text-stone-600 px-4 py-2 rounded-full text-sm font-semibold hover:bg-stone-100 transition-all no-underline">
                            <i class="far fa-calendar-alt text-xs"></i>
                            <span class="hidden lg:inline">Eventos</span>
                        </a>

                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-stone-700 hover:text-orange-600 transition-colors no-underline">Panel Control</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors hidden sm:inline no-underline">Ingresar</a>
                               
                            @endauth
                        @endif
                    </div>
                </div>
            </div>

            <div id="mobileSearchPanel">
                <form action="{{ route('empleos.index') }}" method="GET" class="flex flex-col gap-3">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="nav-search-input w-full" placeholder="Puesto o restaurante...">
                    </div>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                        <select name="departamento" class="nav-select w-full">
                            <option value="">Todos los destinos</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                    {{ $depto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 text-[10px] pointer-events-none"></i>
                    </div>
                    <button type="submit"
                            class="bg-orange-600 text-white py-2.5 rounded-full text-sm font-semibold hover:bg-orange-700 transition-all flex items-center justify-center gap-2 border-0 cursor-pointer">
                        <span>Buscar Empleos</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>
        </nav>

        <section class="hero-empleo pt-40 pb-24 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff08_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
                <span class="bg-gradient-to-r from-orange-600 to-amber-500 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-[0.2em] mb-6 inline-block shadow-sm">
                    Oportunidades Laborales
                </span>
                <h1 class="premium-title text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-[1.1] tracking-tight">
                    Trabaja en los mejores<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-400">restaurantes</span> de Nicaragua
                </h1>
                <p class="text-stone-300 text-base md:text-lg max-w-2xl mx-auto font-light leading-relaxed">
                    Encuentra tu lugar en la gastronomía local. Desde el corazón de la cocina hasta la excelencia en el servicio, hay un puesto de nivel premium esperándote.
                </p>

                <div class="inline-flex flex-wrap justify-center items-center gap-4 md:gap-12 mt-12 bg-white/[0.03] backdrop-blur-md p-6 rounded-2xl border border-white/5 shadow-inner">
                    <div class="text-center px-4">
                        <p class="text-3xl md:text-4xl font-bold text-orange-400 tracking-tight">{{ $empleos->total() }}</p>
                        <p class="text-stone-400 text-[10px] mt-1 uppercase tracking-widest font-bold">Vacantes activas</p>
                    </div>
                    <div class="w-px h-8 bg-white/10 hidden sm:block"></div>
                    <div class="text-center px-4">
                        <p class="text-3xl md:text-4xl font-bold text-orange-400 tracking-tight">{{ $totalRestaurantes }}</p>
                        <p class="text-stone-400 text-[10px] mt-1 uppercase tracking-widest font-bold">Restaurantes</p>
                    </div>
                    <div class="w-px h-8 bg-white/10 hidden sm:block"></div>
                    <div class="text-center px-4">
                        <p class="text-3xl md:text-4xl font-bold text-orange-400 tracking-tight">{{ $totalDepartamentos }}</p>
                        <p class="text-stone-400 text-[10px] mt-1 uppercase tracking-widest font-bold">Destinos</p>
                    </div>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 py-16">

            @if(request('search') || request('departamento'))
                <div class="mb-10 flex items-center gap-2 flex-wrap bg-stone-100 p-3 rounded-xl inline-flex text-sm">
                    <span class="text-stone-500 font-medium">Búsqueda:</span>
                    @if(request('search'))
                        <span class="bg-white text-stone-800 px-3 py-1 rounded-md text-xs font-semibold shadow-sm flex items-center gap-2">
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="text-stone-400 hover:text-red-600 no-underline">×</a>
                        </span>
                    @endif
                    @if(request('departamento'))
                        <span class="bg-white text-stone-800 px-3 py-1 rounded-md text-xs font-semibold shadow-sm flex items-center gap-2">
                            Región: {{ $departamentos->find(request('departamento'))?->nombre }}
                            <a href="{{ request()->fullUrlWithoutQuery(['departamento']) }}" class="text-stone-400 hover:text-red-600 no-underline">×</a>
                        </span>
                    @endif
                </div>
            @endif

            @if($empleos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach($empleos as $empleo)
                        <article class="job-card p-8 flex flex-col gap-6 group" data-aos="fade-up">

                            <div class="flex items-start justify-between gap-4">
                                <div class="w-12 h-12 bg-stone-50 border border-stone-100 rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:bg-orange-50 group-hover:border-orange-100 transition-colors">
                                    <i class="fas fa-store text-stone-400 text-lg group-hover:text-orange-600 transition-colors"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] uppercase font-black tracking-widest text-orange-600">
                                        {{ $empleo->restaurante->nombre }}
                                    </p>
                                    <h3 class="premium-title text-xl font-bold mt-1 leading-snug text-stone-900 group-hover:text-orange-600 transition-colors">
                                        {{ $empleo->titulo }}
                                    </h3>
                                </div>
                            </div>

                            <p class="text-stone-500 text-sm leading-relaxed line-clamp-3 font-normal">
                                {{ $empleo->descripcion }}
                            </p>

                            <div class="flex flex-wrap gap-1.5 pt-2">
                                @if($empleo->tipo_contrato)
                                    <span class="badge-tipo bg-stone-100 text-stone-600">
                                        <i class="far fa-clock"></i> {{ $empleo->tipo_contrato }}
                                    </span>
                                @endif
                                @if($empleo->salario)
                                    <span class="badge-tipo bg-green-50 text-green-700">
                                        <i class="fas fa-wallet"></i> C$ {{ number_format($empleo->salario, 0) }}
                                    </span>
                                @else
                                    <span class="badge-tipo bg-stone-100 text-stone-500">
                                        <i class="fas fa-handshake"></i> A convenir
                                    </span>
                                @endif
                                @if($empleo->departamento)
                                    <span class="badge-tipo bg-blue-50 text-blue-600">
                                        <i class="fas fa-map-marker-alt"></i> {{ $empleo->departamento->nombre }}@if($empleo->municipio), {{ $empleo->municipio->nombre }}@endif
                                    </span>
                                @endif
                            </div>

                            <div class="pt-5 border-t border-stone-100 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2 text-stone-400 text-xs font-medium">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>{{ \Carbon\Carbon::parse($empleo->created_at)->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('empleos.show', $empleo->id) }}"
                                   class="bg-stone-950 text-white px-5 py-2 rounded-full text-xs font-bold hover:bg-orange-600 transition-all shadow-sm flex items-center gap-2 no-underline cursor-pointer border-0">
                                    <span>Ver oferta</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-16 flex justify-center">
                    {{ $empleos->withQueryString()->links() }}
                </div>

            @else
                <div class="py-24 text-center bg-white border border-stone-200/60 rounded-3xl p-8 max-w-xl mx-auto shadow-sm">
                    <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-stone-100">
                        <i class="fas fa-briefcase text-2xl text-stone-300"></i>
                    </div>
                    <h3 class="premium-title text-2xl font-bold text-stone-800 mb-2">No hay ofertas disponibles</h3>
                    <p class="text-stone-500 text-sm max-w-sm mx-auto leading-relaxed">
                        @if(request('search') || request('departamento'))
                            No encontramos vacantes que coincidan con tus filtros actuales. Intenta limpiando los parámetros.
                        @else
                            Los establecimientos gastronómicos asociados aún no han publicado vacantes activas. ¡Vuelve pronto!
                        @endif
                    </p>
                    @if(request('search') || request('departamento'))
                        <a href="{{ route('empleos.index') }}"
                           class="mt-6 inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-orange-700 transition-all no-underline border-0 cursor-pointer">
                            <i class="fas fa-times text-xs"></i> Limpiar todos los filtros
                        </a>
                    @endif
                </div>
            @endif
        </main>

        <footer class="bg-stone-950 text-white py-16 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline">
                    <span class="text-xl font-bold tracking-tight premium-title italic text-white">Gastro<span class="text-orange-500">Nicaragua</span></span>
                </a>
                <p class="text-stone-500 text-xs tracking-widest uppercase font-bold m-0">© 2026 Gastro Nicaragua — Experiencias Culinarias</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 1000, once: true, easing: 'ease-in-out-cubic' });

            const mobileToggle = document.getElementById('mobileSearchToggle');
            const mobilePanel  = document.getElementById('mobileSearchPanel');
            if (mobileToggle && mobilePanel) {
                mobileToggle.addEventListener('click', () => mobilePanel.classList.toggle('open'));
            }
        </script>
    </body>
</html>