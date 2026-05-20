{{-- resources/views/restaurantes/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurantes | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; scroll-behavior: smooth; }
        .premium-title { font-family: 'Playfair Display', serif; }

        /* ── Navbar (igual que home) ── */
        .nav-search-input {
            background: rgba(245,245,244,0.8);
            border: 1px solid rgba(231,229,228,0.8);
            border-radius: 9999px;
            padding: 0.45rem 1rem 0.45rem 2.5rem;
            font-size: 0.85rem; color: #1c1917;
            transition: all .2s; outline: none; width: 140px;
        }
        .nav-search-input:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234,88,12,.12);
            background: #fff; width: 170px;
        }
        .nav-search-input::placeholder { color: #a8a29e; }

        .nav-select {
            background: rgba(245,245,244,0.8);
            border: 1px solid rgba(231,229,228,0.8);
            border-radius: 9999px;
            padding: 0.45rem 2rem 0.45rem 2.5rem;
            font-size: 0.85rem; color: #1c1917;
            appearance: none; cursor: pointer;
            transition: all .2s; outline: none; width: 100%;
        }
        .nav-select:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234,88,12,.12);
            background: #fff;
        }

        /* ── Hero ── */
        @keyframes heroFloat {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }
        .hero-icon { animation: heroFloat 4s ease-in-out infinite; }

        /* ── Cards ── */
        .rest-card {
            background: #ffffff;
            border: 1px solid #f1f0ee;
            border-radius: 2rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
        }
        .rest-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 56px rgba(28,25,23,.1);
            border-color: #ffedd5;
        }
        .rest-card .card-img-wrap {
            position: relative; overflow: hidden; height: 220px;
            background: #f5f5f4;
        }
        .rest-card .card-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .7s ease;
        }
        .rest-card:hover .card-img-wrap img { transform: scale(1.06); }

        /* ── Filtro chips ── */
        .chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 16px; border-radius: 9999px; font-size: 0.78rem;
            font-weight: 600; cursor: pointer; transition: all .2s;
            border: 1.5px solid #e7e5e4; background: #fff; color: #57534e;
            white-space: nowrap;
        }
        .chip:hover, .chip.active {
            background: #ea580c; border-color: #ea580c; color: #fff;
        }

        /* ── Searchbar ── */
        .rest-search {
            background: #fff; border: 1.5px solid #e7e5e4;
            border-radius: 9999px; padding: 10px 20px 10px 44px;
            font-size: 0.9rem; outline: none; width: 100%;
            transition: border-color .2s, box-shadow .2s; color: #1c1917;
        }
        .rest-search:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234,88,12,.12);
        }
        .rest-search::placeholder { color: #a8a29e; }

        /* ── Badge ── */
        .esp-badge {
            display: inline-block; font-size: 10px; font-weight: 800;
            letter-spacing: .06em; text-transform: uppercase;
            padding: 3px 10px; border-radius: 6px;
            background: #fff7ed; color: #c2410c;
        }

        /* ── Star rating ── */
        .stars { color: #f59e0b; font-size: 13px; letter-spacing: 1px; }

        /* ── Paginación coherente ── */
        .page-link { color: #ea580c; }
        .page-item.active .page-link { background-color: #ea580c; border-color: #ea580c; }
    </style>
</head>
<body class="bg-stone-50 text-stone-900">

    {{-- ══════════════ NAVBAR (idéntico al home) ══════════════ --}}
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center gap-4">

                <div class="flex items-center gap-2 shrink-0">
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                        <i class="fas fa-utensils text-white"></i>
                    </div>
                    <a href="{{ route('home') }}" class="no-underline">
                        <span class="text-2xl font-bold tracking-tight premium-title italic text-stone-900">
                            Gastro<span class="text-orange-600">Nicaragua</span>
                        </span>
                    </a>
                </div>

                <div class="flex items-center gap-3 shrink-0 ml-auto">

                    {{-- Botón Restaurantes (activo) --}}
                    <a href="{{ route('restaurantes.index') }}"
                       class="hidden sm:flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-md shadow-orange-200 no-underline">
                        <i class="fas fa-store text-xs"></i>
                        <span class="hidden lg:inline">Restaurantes</span>
                    </a>

                    {{-- Empleos --}}
                    <a href="{{ route('empleos.index') }}"
                       class="hidden sm:flex items-center gap-2 border border-orange-200 text-orange-600 bg-orange-50 px-4 py-2 rounded-full text-sm font-semibold hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all shadow-sm group no-underline">
                        <i class="fas fa-briefcase text-xs group-hover:animate-bounce"></i>
                        <span class="hidden lg:inline">Empleos</span>
                    </a>

                    <a href="{{ route('contacto') }}" class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors px-2 no-underline">Contacto</a>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold hover:text-orange-600 transition-colors no-underline">Panel</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-orange-600 transition-colors no-underline">Ingresar</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- ══════════════ HERO ══════════════ --}}
    <section class="pt-20">
        <div class="relative bg-stone-900 overflow-hidden">
            {{-- Fondo decorativo --}}
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, #ea580c 0%, transparent 50%), radial-gradient(circle at 80% 20%, #f97316 0%, transparent 40%);"></div>
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.02\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <div class="relative max-w-7xl mx-auto px-4 py-20 sm:py-28 flex flex-col md:flex-row items-center gap-10">
                <div class="flex-1" data-aos="fade-right">
                    <span class="bg-orange-600/20 text-orange-400 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 inline-block border border-orange-600/30">
                        <i class="fas fa-map-marker-alt mr-1"></i> Toda Nicaragua
                    </span>
                    <h1 class="premium-title text-5xl md:text-7xl font-bold text-white mb-5 leading-tight">
                        Nuestros<br><span class="text-orange-500">Restaurantes</span>
                    </h1>
                    <p class="text-stone-400 text-lg leading-relaxed max-w-xl mb-8">
                        Desde fritangas familiares hasta cocina gourmet — descubre cada sabor auténtico de Nicaragua en un solo lugar.
                    </p>
                    <div class="flex flex-wrap gap-4 text-sm text-stone-400">
                        <span class="flex items-center gap-2"><i class="fas fa-check-circle text-orange-500"></i> Información verificada</span>
                        <span class="flex items-center gap-2"><i class="fas fa-check-circle text-orange-500"></i> Horarios actualizados</span>
                        <span class="flex items-center gap-2"><i class="fas fa-check-circle text-orange-500"></i> Ubicación en mapa</span>
                    </div>
                </div>
                <div class="hero-icon text-[120px] md:text-[160px] select-none" data-aos="fade-left">🍽️</div>
            </div>
        </div>
    </section>

    {{-- ══════════════ FILTROS ══════════════ --}}
    <section class="bg-white border-b border-stone-100 sticky top-20 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <form action="{{ route('restaurantes.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                {{-- Búsqueda por nombre --}}
                <div class="relative flex-1 min-w-[200px] max-w-sm">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm pointer-events-none"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="rest-search" placeholder="Buscar restaurante...">
                </div>

                {{-- Departamento --}}
                <div class="relative min-w-[160px]">
                    <i class="fas fa-map absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none z-10"></i>
                    <select name="departamento" class="nav-select">
                        <option value="">Todos los destinos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                {{ $depto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Especialidad --}}
                <div class="relative min-w-[160px]">
                    <i class="fas fa-utensils absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none z-10"></i>
                    <input type="text" name="especialidad" value="{{ request('especialidad') }}"
                           class="nav-search-input w-full" placeholder="Especialidad...">
                </div>

                <button type="submit"
                        class="bg-orange-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-orange-700 transition-all shadow-md shadow-orange-200 flex items-center gap-2 border-0 cursor-pointer shrink-0">
                    <i class="fas fa-filter text-xs"></i> Filtrar
                </button>

                @if(request('search') || request('departamento') || request('especialidad'))
                    <a href="{{ route('restaurantes.index') }}"
                       class="text-stone-400 hover:text-red-500 text-sm font-medium transition-colors no-underline flex items-center gap-1">
                        <i class="fas fa-times text-xs"></i> Limpiar
                    </a>
                @endif

                <span class="ml-auto text-xs text-stone-400 font-medium shrink-0">
                    {{ $restaurantes->total() }} restaurante{{ $restaurantes->total() != 1 ? 's' : '' }}
                </span>
            </form>
        </div>
    </section>

    {{-- ══════════════ GRID DE RESTAURANTES ══════════════ --}}
    <main class="max-w-7xl mx-auto px-4 py-16">

        {{-- Breadcrumb activo --}}
        @if(request('search') || request('departamento') || request('especialidad'))
            <div class="mb-8 flex flex-wrap items-center gap-2 text-sm">
                <span class="text-stone-400">Filtros activos:</span>
                @if(request('search'))
                    <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-lg font-semibold flex items-center gap-2">
                        "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="text-orange-400 hover:text-red-600 no-underline font-bold">×</a>
                    </span>
                @endif
                @if(request('departamento'))
                    <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-lg font-semibold flex items-center gap-2">
                        {{ $departamentos->find(request('departamento'))?->nombre }}
                        <a href="{{ request()->fullUrlWithoutQuery(['departamento']) }}" class="text-orange-400 hover:text-red-600 no-underline font-bold">×</a>
                    </span>
                @endif
                @if(request('especialidad'))
                    <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-lg font-semibold flex items-center gap-2">
                        {{ request('especialidad') }}
                        <a href="{{ request()->fullUrlWithoutQuery(['especialidad']) }}" class="text-orange-400 hover:text-red-600 no-underline font-bold">×</a>
                    </span>
                @endif
            </div>
        @endif

        @if($restaurantes->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($restaurantes as $restaurante)
                    <article class="rest-card" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}">

                        {{-- Imagen --}}
                        <div class="card-img-wrap">
                            @if($restaurante->imagen)
                                <img src="{{ asset('storage/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre }}">
                            @else
                                <div class="w-full h-full flex items-center justify-content-center bg-gradient-to-br from-orange-50 to-stone-100 text-6xl" style="display:flex;align-items:center;justify-content:center;">
                                    🍴
                                </div>
                            @endif

                            {{-- Badges sobre la imagen --}}
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($restaurante->departamento)
                                    <span class="bg-stone-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-orange-400 text-[9px]"></i>
                                        {{ $restaurante->departamento->nombre }}
                                    </span>
                                @endif
                            </div>

                            @if($restaurante->especialidad)
                                <div class="absolute top-4 right-4">
                                    <span class="esp-badge shadow-sm">{{ $restaurante->especialidad }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Cuerpo --}}
                        <div class="p-6 flex flex-col gap-4">
                            <div>
                                <h3 class="premium-title text-xl font-bold text-stone-900 mb-1 leading-snug">
                                    {{ $restaurante->nombre }}
                                </h3>

                                @if($restaurante->descripcion)
                                    <p class="text-stone-500 text-sm leading-relaxed line-clamp-2">
                                        {{ $restaurante->descripcion }}
                                    </p>
                                @endif
                            </div>

                            {{-- Meta info --}}
                            <div class="flex flex-col gap-2 text-xs text-stone-400">
                                @if($restaurante->direccion)
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-orange-500 w-3 text-center shrink-0"></i>
                                        {{ $restaurante->direccion }}
                                    </span>
                                @endif
                                @if($restaurante->telefono)
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-phone text-orange-500 w-3 text-center shrink-0"></i>
                                        {{ $restaurante->telefono }}
                                    </span>
                                @endif
                                @if($restaurante->horario)
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-clock text-orange-500 w-3 text-center shrink-0"></i>
                                        {{ $restaurante->horario }}
                                    </span>
                                @endif
                                @if($restaurante->correo)
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-envelope text-orange-500 w-3 text-center shrink-0"></i>
                                        {{ $restaurante->correo }}
                                    </span>
                                @endif
                            </div>

                            {{-- Eventos activos del restaurante --}}
                            @if(isset($restaurante->eventos_count) && $restaurante->eventos_count > 0)
                                <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-2.5 flex items-center gap-2 text-xs text-orange-700 font-semibold">
                                    <i class="fas fa-calendar-star text-orange-500"></i>
                                    {{ $restaurante->eventos_count }} evento{{ $restaurante->eventos_count != 1 ? 's' : '' }} próximo{{ $restaurante->eventos_count != 1 ? 's' : '' }}
                                </div>
                            @endif

                            {{-- Footer de tarjeta --}}
                            <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                                @if($restaurante->precio_rango)
                                    <span class="text-stone-400 text-xs font-medium">
                                        <i class="fas fa-tag text-orange-400 mr-1"></i>{{ $restaurante->precio_rango }}
                                    </span>
                                @else
                                    <span></span>
                                @endif

                                <a href="{{ route('restaurantes.show', $restaurante->id) }}"
                                   class="bg-stone-900 text-white text-xs font-bold px-5 py-2 rounded-full hover:bg-orange-600 transition-all no-underline flex items-center gap-2 group">
                                    Ver perfil
                                    <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if($restaurantes->hasPages())
                <div class="mt-16 flex justify-center">
                    {{ $restaurantes->withQueryString()->links() }}
                </div>
            @endif

        @else
            {{-- Estado vacío --}}
            <div class="py-24 flex flex-col items-center text-center max-w-md mx-auto" data-aos="fade-up">
                <div class="w-24 h-24 bg-stone-100 rounded-full flex items-center justify-center text-stone-400 mb-6 border border-stone-200 shadow-sm relative">
                    <i class="fas fa-store text-3xl"></i>
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center text-white text-xs shadow-md">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <h3 class="premium-title text-3xl font-bold text-stone-900 mb-3">Sin restaurantes</h3>
                <p class="text-stone-500 text-base leading-relaxed mb-8">
                    No encontramos restaurantes con los filtros seleccionados. ¡Intenta ajustar tu búsqueda!
                </p>
                <a href="{{ route('restaurantes.index') }}"
                   class="bg-stone-900 text-white text-xs font-bold tracking-wider uppercase px-6 py-3.5 rounded-xl no-underline hover:bg-orange-600 transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-undo text-[10px]"></i> Ver todos los restaurantes
                </a>
            </div>
        @endif
    </main>

    {{-- ══════════════ FOOTER (igual al home) ══════════════ --}}
    <footer class="bg-stone-900 text-stone-300 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 pt-16 pb-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 mb-12">
                <div class="lg:col-span-4 space-y-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-orange-600 rounded-xl flex items-center justify-center shadow-md shadow-orange-600/20">
                            <i class="fas fa-utensils text-white text-xs"></i>
                        </div>
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
                <div class="lg:col-span-3 space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Newsletter</h4>
                    <p class="text-stone-400 text-xs leading-relaxed font-light">Recibe las mejores agendas culinarias de la semana.</p>
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
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>