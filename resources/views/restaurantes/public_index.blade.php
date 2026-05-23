{{-- resources/views/restaurantes/index.blade.php --}}
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

    <style>
        body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; scroll-behavior: smooth; }
        .premium-title { font-family: 'Playfair Display', serif; }

        /* ── Hero ── */
        @keyframes heroFloat {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }
        .hero-icon { animation: heroFloat 4s ease-in-out infinite; }

        /* ── Cards Premium ── */
        .rest-card {
            background: #ffffff;
            border: 1px solid rgba(28,25,23,0.05);
            border-radius: 2rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .rest-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(28,25,23,0.08);
            border-color: rgba(234,88,12,0.15);
        }
        .rest-card .card-img-wrap {
            position: relative; overflow: hidden; height: 240px;
            background: #f5f5f4;
        }
        .rest-card .card-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .rest-card:hover .card-img-wrap img { transform: scale(1.05); }

        /* ── Badge Especialidad ── */
        .esp-badge {
            display: inline-block; font-size: 10px; font-weight: 700;
            letter-spacing: .05em; text-transform: uppercase;
            padding: 4px 12px; border-radius: 9999px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-blur: 4px;
            color: #c2410c;
            border: 1px solid rgba(234,88,12,0.1);
        }

        /* ── Paginación ── */
        .page-link { color: #ea580c; border-radius: 50%; margin: 0 3px; }
        .page-item.active .page-link { background-color: #ea580c; border-color: #ea580c; }

        /* ── Select municipio deshabilitado ── */
        select:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-stone-50/50 text-stone-900">

    {{-- ══════════════ NAVBAR ══════════════ --}}
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-stone-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center gap-4">

                <div class="flex items-center gap-2 shrink-0">
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-600/20">
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
                       class="hidden sm:flex items-center gap-2 bg-orange-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-md shadow-orange-600/10 no-underline hover:bg-orange-700 transition-all">
                        <i class="fas fa-store text-xs"></i>
                        <span>Restaurantes</span>
                    </a>

                    {{-- Empleos --}}
                    <a href="{{ route('empleos.index') }}"
                       class="hidden sm:flex items-center gap-2 border border-stone-200 text-stone-600 bg-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-stone-50 transition-all shadow-sm group no-underline">
                        <i class="fas fa-briefcase text-xs group-hover:animate-bounce text-stone-400 group-hover:text-orange-600"></i>
                        <span>Empleos</span>
                    </a>

                    <a href="{{ route('contacto') }}" class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors px-2 no-underline">Contacto</a>

                    {{-- BOTÓN PANEL ELIMINADO --}}
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

            <div class="relative max-w-7xl mx-auto px-4 py-20 sm:py-24 flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="flex-1" data-aos="fade-right">
                    <span class="bg-orange-600/10 text-orange-400 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 inline-block border border-orange-500/20">
                        <i class="fas fa-map-marker-alt mr-1.5"></i> Toda Nicaragua
                    </span>
                    <h1 class="premium-title text-5xl md:text-6xl font-bold text-white mb-5 leading-tight">
                        Nuestros<br><span class="text-orange-500 italic font-normal">Restaurantes</span>
                    </h1>
                    <p class="text-stone-400 text-base leading-relaxed max-w-xl mb-8 font-light">
                        Desde fritangas familiares hasta cocina gourmet — descubre cada sabor auténtico de Nicaragua en un solo lugar.
                    </p>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-stone-400">
                        <span class="flex items-center gap-2"><i class="fas fa-check text-xs text-orange-500"></i> Información verificada</span>
                        <span class="flex items-center gap-2"><i class="fas fa-check text-xs text-orange-500"></i> Horarios actualizados</span>
                        <span class="flex items-center gap-2"><i class="fas fa-check text-xs text-orange-500"></i> Ubicación en mapa</span>
                    </div>
                </div>
                <div class="hero-icon text-[100px] md:text-[130px] select-none pr-10" data-aos="fade-left">🍽️</div>
            </div>
        </div>
    </section>

    {{-- ══════════════ FILTROS ══════════════ --}}
    <section class="bg-white border-b border-stone-200/60 sticky top-20 z-40 shadow-sm backdrop-blur-md bg-white/95">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <form action="{{ route('restaurantes.index') }}" method="GET"
                  class="flex flex-wrap items-center gap-3">

                {{-- Búsqueda por nombre --}}
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm pointer-events-none"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full bg-stone-50 border border-stone-200 rounded-full py-2.5 pl-11 pr-4 text-sm outline-none transition-all focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/5 text-stone-800 placeholder-stone-400"
                           placeholder="Buscar por nombre...">
                </div>

                {{-- Departamento --}}
                <div class="relative min-w-[170px] flex-1 sm:flex-initial">
                    <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm pointer-events-none z-10"></i>
                    <select id="selectDepartamento" name="departamento"
                            class="w-full bg-stone-50 border border-stone-200 rounded-full py-2.5 pl-11 pr-10 text-sm outline-none transition-all focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/5 text-stone-800 appearance-none cursor-pointer">
                        <option value="">Todos los destinos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" {{ request('departamento') == $depto->id ? 'selected' : '' }}>
                                {{ $depto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                </div>

                {{-- Municipio (dinámico) --}}
                <div class="relative min-w-[170px] flex-1 sm:flex-initial">
                    <i class="fas fa-city absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm pointer-events-none z-10"></i>
                    <select id="selectMunicipio" name="municipio"
                            class="w-full bg-stone-50 border border-stone-200 rounded-full py-2.5 pl-11 pr-10 text-sm outline-none transition-all focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/5 text-stone-800 appearance-none cursor-pointer"
                            {{ !request('departamento') ? 'disabled' : '' }}>
                        <option value="">
                            {{ request('departamento') ? 'Todos los municipios' : 'Elige un destino primero' }}
                        </option>
                        @foreach($municipios as $mun)
                            <option value="{{ $mun->id }}"
                                    data-departamento="{{ $mun->departamento_id }}"
                                    {{ request('municipio') == $mun->id ? 'selected' : '' }}
                                    style="{{ request('departamento') && $mun->departamento_id == request('departamento') ? '' : 'display:none' }}">
                                {{ $mun->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 text-xs pointer-events-none"></i>
                </div>

                {{-- Especialidad --}}
                <div class="relative min-w-[160px] flex-1 sm:flex-initial">
                    <i class="fas fa-concierge-bell absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm pointer-events-none"></i>
                    <input type="text" name="especialidad" value="{{ request('especialidad') }}"
                           class="w-full bg-stone-50 border border-stone-200 rounded-full py-2.5 pl-11 pr-4 text-sm outline-none transition-all focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/5 text-stone-800 placeholder-stone-400"
                           placeholder="Especialidad...">
                </div>

                {{-- Botón Filtrar --}}
                <button type="submit"
                        class="bg-stone-900 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-orange-600 transition-all shadow-sm flex items-center gap-2 border-0 cursor-pointer shrink-0">
                    <i class="fas fa-sliders-h text-xs text-stone-400 group-hover:text-white"></i> Filtrar
                </button>

                @if(request('search') || request('departamento') || request('municipio') || request('especialidad'))
                    <a href="{{ route('restaurantes.index') }}"
                       class="text-stone-400 hover:text-red-500 text-sm font-medium transition-colors no-underline flex items-center gap-1.5">
                        <i class="fas fa-times-circle text-xs"></i> Limpiar
                    </a>
                @endif

                <span class="ml-auto text-xs text-stone-400 font-semibold tracking-wide uppercase shrink-0">
                    {{ $restaurantes->total() }} disponible{{ $restaurantes->total() != 1 ? 's' : '' }}
                </span>
            </form>
        </div>
    </section>

    {{-- ══════════════ GRID DE RESTAURANTES ══════════════ --}}
    <main class="max-w-7xl mx-auto px-4 py-16">

        {{-- Filtros activos --}}
        @if(request('search') || request('departamento') || request('municipio') || request('especialidad'))
            <div class="mb-8 flex flex-wrap items-center gap-2 text-sm bg-stone-100/60 p-3 rounded-2xl border border-stone-200/40">
                <span class="text-stone-500 font-medium pl-1">Filtros aplicados:</span>
                @if(request('search'))
                    <span class="bg-white border border-stone-200 text-stone-800 px-3 py-1 rounded-full font-medium flex items-center gap-2 text-xs shadow-sm">
                        "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="text-stone-400 hover:text-red-600 no-underline font-bold text-sm">×</a>
                    </span>
                @endif
                @if(request('departamento'))
                    <span class="bg-white border border-stone-200 text-stone-800 px-3 py-1 rounded-full font-medium flex items-center gap-2 text-xs shadow-sm">
                        <i class="fas fa-map-marker-alt text-orange-500 text-[10px]"></i>
                        {{ $departamentos->find(request('departamento'))?->nombre }}
                        <a href="{{ request()->fullUrlWithoutQuery(['departamento', 'municipio']) }}" class="text-stone-400 hover:text-red-600 no-underline font-bold text-sm">×</a>
                    </span>
                @endif
                @if(request('municipio'))
                    <span class="bg-white border border-stone-200 text-stone-800 px-3 py-1 rounded-full font-medium flex items-center gap-2 text-xs shadow-sm">
                        <i class="fas fa-city text-orange-500 text-[10px]"></i>
                        {{ $municipios->find(request('municipio'))?->nombre }}
                        <a href="{{ request()->fullUrlWithoutQuery(['municipio']) }}" class="text-stone-400 hover:text-red-600 no-underline font-bold text-sm">×</a>
                    </span>
                @endif
                @if(request('especialidad'))
                    <span class="bg-white border border-stone-200 text-stone-800 px-3 py-1 rounded-full font-medium flex items-center gap-2 text-xs shadow-sm">
                        {{ request('especialidad') }}
                        <a href="{{ request()->fullUrlWithoutQuery(['especialidad']) }}" class="text-stone-400 hover:text-red-600 no-underline font-bold text-sm">×</a>
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
                            @if($restaurante->foto_portada)
                                <img src="{{ asset('storage/' . $restaurante->foto_portada) }}" alt="{{ $restaurante->nombre }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-stone-50 to-stone-100/80 text-5xl">
                                    🍴
                                </div>
                            @endif

                            {{-- Badges sobre la imagen --}}
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($restaurante->departamento)
                                    <span class="bg-stone-900/75 backdrop-blur-md text-white text-[11px] font-medium px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-sm">
                                        <i class="fas fa-map-marker-alt text-orange-400 text-[10px]"></i>
                                        {{ $restaurante->departamento->nombre }}
                                        @if($restaurante->municipio)
                                            <span class="opacity-60 mx-0.5">·</span>{{ $restaurante->municipio->nombre }}
                                        @endif
                                    </span>
                                @endif
                            </div>

                            @if($restaurante->especialidad)
                                <div class="absolute top-4 right-4">
                                    <span class="esp-badge shadow-sm">{{ $restaurante->especialidad }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Cuerpo de la tarjeta --}}
                        <div class="p-6 flex flex-col gap-4">
                            <div>
                                <h3 class="premium-title text-xl font-bold text-stone-900 mb-2 leading-snug">
                                    {{ $restaurante->nombre }}
                                </h3>

                                @if($restaurante->descripcion)
                                    <p class="text-stone-500 text-sm leading-relaxed line-clamp-2 font-light">
                                        {{ $restaurante->descripcion }}
                                    </p>
                                @endif
                            </div>

                            {{-- Meta info --}}
                            <div class="flex flex-col gap-2.5 text-xs text-stone-500 border-t border-stone-100 pt-3">
                                @if($restaurante->direccion)
                                    <span class="flex items-start gap-2.5">
                                        <i class="fas fa-map-marker-alt text-stone-400 w-3 text-center mt-0.5 shrink-0"></i>
                                        <span class="line-clamp-1">{{ $restaurante->direccion }}</span>
                                    </span>
                                @endif
                                @if($restaurante->telefono)
                                    <span class="flex items-center gap-2.5">
                                        <i class="fas fa-phone text-stone-400 w-3 text-center shrink-0"></i>
                                        {{ $restaurante->telefono }}
                                    </span>
                                @endif
                                @if($restaurante->horario)
                                    <span class="flex items-center gap-2.5">
                                        <i class="fas fa-clock text-stone-400 w-3 text-center shrink-0"></i>
                                        {{ $restaurante->horario }}
                                    </span>
                                @endif
                            </div>

                            {{-- Eventos activos del restaurante --}}
                            @if(isset($restaurante->eventos_count) && $restaurante->eventos_count > 0)
                                <div class="bg-orange-50/60 border border-orange-100 rounded-xl px-3.5 py-2 flex items-center gap-2 text-xs text-orange-800 font-medium">
                                    <i class="fas fa-calendar-alt text-orange-500"></i>
                                    {{ $restaurante->eventos_count }} evento{{ $restaurante->eventos_count != 1 ? 's' : '' }} próximo{{ $restaurante->eventos_count != 1 ? 's' : '' }}
                                </div>
                            @endif

                            {{-- Footer de tarjeta --}}
                            <div class="pt-3 border-t border-stone-100 flex items-center justify-between mt-1">
                                @if($restaurante->precio_rango)
                                    <span class="text-stone-400 text-xs font-semibold tracking-wide">
                                        <i class="fas fa-tags text-stone-300 mr-1"></i>{{ $restaurante->precio_rango }}
                                    </span>
                                @else
                                    <span></span>
                                @endif

                                <a href="{{ route('restaurantes.show', $restaurante->id) }}"
                                   class="bg-stone-900 text-white text-xs font-semibold px-5 py-2.5 rounded-full hover:bg-orange-600 transition-all no-underline flex items-center gap-2 group shadow-sm">
                                    <span>Ver perfil</span>
                                    <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform text-stone-400 group-hover:text-white"></i>
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
                <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center text-stone-400 mb-6 border border-stone-200/60 shadow-sm relative">
                    <i class="fas fa-store-slash text-2xl"></i>
                </div>
                <h3 class="premium-title text-2xl font-bold text-stone-900 mb-2">Sin resultados</h3>
                <p class="text-stone-500 text-sm leading-relaxed mb-6 font-light">
                    No encontramos restaurantes que coincidan con tus filtros. ¡Intenta ajustar los criterios de búsqueda!
                </p>
                <a href="{{ route('restaurantes.index') }}"
                   class="bg-stone-900 text-white text-xs font-bold tracking-wider uppercase px-5 py-3 rounded-full no-underline hover:bg-orange-600 transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-undo text-[10px]"></i> Mostrar todos
                </a>
            </div>
        @endif
    </main>

    {{-- ══════════════ FOOTER ══════════════ --}}
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

        // ── Municipio dinámico ──
        const selectDepto = document.getElementById('selectDepartamento');
        const selectMun   = document.getElementById('selectMunicipio');
        const allOpts     = Array.from(selectMun.querySelectorAll('option[data-departamento]'));

        function actualizarMunicipios(deptoId, municipioSeleccionado) {
            // Ocultar/mostrar opciones según el departamento
            allOpts.forEach(opt => {
                if (!deptoId || opt.dataset.departamento === deptoId) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                    opt.selected = false;
                }
            });

            // Texto del placeholder y estado del select
            const placeholder = selectMun.querySelector('option:not([data-departamento])');
            if (deptoId) {
                selectMun.disabled = false;
                placeholder.textContent = 'Todos los municipios';
                // Restaurar selección previa si corresponde al mismo departamento
                if (municipioSeleccionado) {
                    const targetOpt = selectMun.querySelector(`option[value="${municipioSeleccionado}"]`);
                    if (targetOpt && targetOpt.style.display !== 'none') {
                        targetOpt.selected = true;
                    }
                }
            } else {
                selectMun.disabled = true;
                selectMun.value = '';
                placeholder.textContent = 'Elige un destino primero';
            }
        }

        // Al cambiar departamento
        selectDepto.addEventListener('change', function () {
            actualizarMunicipios(this.value, null);
        });

        // Al cargar la página, si ya hay un departamento y municipio seleccionado
        (function init() {
            const deptoVal = selectDepto.value;
            const munVal   = '{{ request('municipio') }}';
            if (deptoVal) actualizarMunicipios(deptoVal, munVal);
        })();
    </script>
</body>
</html>