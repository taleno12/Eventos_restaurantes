<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detalle del Gastrobar') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.gastrobares.edit', $gastrobar->id) }}"
                   class="inline-flex items-center gap-2 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors no-underline">
                    <i class="fas fa-pen-to-square"></i> Editar
                </a>
                <a href="{{ route('admin.gastrobares.index') }}"
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold px-4 py-2 rounded-xl transition-colors no-underline">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/60 min-h-screen" style="font-family: 'DM Sans', sans-serif;">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ══════════════════════════════════════════
                HERO — Portada + Datos Clave
            ══════════════════════════════════════════ --}}
            <div class="relative bg-zinc-900 sm:rounded-[1.5rem] overflow-hidden shadow-xl" style="min-height: 320px;">

                {{-- Imagen de fondo --}}
                @if($gastrobar->imagen_principal)
                    <img src="{{ asset('storage/' . $gastrobar->imagen_principal) }}"
                         alt="{{ $gastrobar->nombre }}"
                         class="absolute inset-0 w-full h-full object-cover opacity-40">
                @endif

                {{-- Degradado inferior --}}
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-900/70 to-transparent"></div>

                {{-- Contenido --}}
                <div class="relative z-10 p-8 md:p-10 flex flex-col justify-end h-full" style="min-height: 320px;">

                    {{-- Badges --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($gastrobar->tipo_bar)
                        <span class="bg-purple-600/30 border border-purple-500/40 text-purple-300 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                            <i class="fas fa-cocktail mr-1"></i>{{ $gastrobar->tipo_bar }}
                        </span>
                        @endif
                        @if($gastrobar->ambiente)
                        <span class="bg-white/10 border border-white/20 text-gray-300 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                            <i class="fas fa-chair mr-1"></i>{{ $gastrobar->ambiente }}
                        </span>
                        @endif
                        @if($gastrobar->tipo_musica)
                        <span class="bg-white/10 border border-white/20 text-gray-300 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                            <i class="fas fa-music mr-1"></i>{{ $gastrobar->tipo_musica }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-white font-black text-4xl md:text-5xl leading-none tracking-tight mb-2"
                        style="font-family: 'DM Serif Display', serif;">
                        {{ $gastrobar->nombre }}
                    </h1>

                    @if($gastrobar->descripcion)
                    <p class="text-gray-400 text-sm max-w-2xl mt-2 leading-relaxed">
                        {{ $gastrobar->descripcion }}
                    </p>
                    @endif

                    {{-- Horario --}}
                    @if($gastrobar->hora_apertura || $gastrobar->hora_cierre)
                    <div class="flex items-center gap-2 mt-4 text-gray-400 text-sm">
                        <i class="fas fa-clock text-purple-400"></i>
                        <span>
                            @if($gastrobar->hora_apertura && $gastrobar->hora_cierre)
                                {{ \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') }}
                                — {{ \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') }}
                            @elseif($gastrobar->hora_apertura)
                                Abre a las {{ \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') }}
                            @else
                                Cierra a las {{ \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') }}
                            @endif
                        </span>
                    </div>
                    @endif

                </div>
            </div>

            {{-- ══════════════════════════════════════════
                GRID: Info + Mapa
            ══════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Columna Izquierda: Detalles --}}
                <div class="md:col-span-1 space-y-4">

                    {{-- Datos de contacto --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2">
                            <i class="fas fa-address-card mr-2"></i>Contacto
                        </h3>

                        @if($gastrobar->email)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-purple-500 text-xs"></i>
                            </span>
                            <a href="mailto:{{ $gastrobar->email }}" class="text-sm text-gray-700 hover:text-purple-600 transition-colors break-all no-underline">
                                {{ $gastrobar->email }}
                            </a>
                        </div>
                        @endif

                        @if($gastrobar->whatsapp)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-whatsapp text-emerald-500 text-xs"></i>
                            </span>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $gastrobar->whatsapp) }}" target="_blank"
                               class="text-sm text-gray-700 hover:text-emerald-600 transition-colors no-underline">
                                {{ $gastrobar->whatsapp }}
                            </a>
                        </div>
                        @endif

                        @if(!$gastrobar->email && !$gastrobar->whatsapp)
                        <p class="text-xs text-gray-400 italic">Sin datos de contacto registrados.</p>
                        @endif
                    </div>

                    {{-- Info adicional --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2">
                            <i class="fas fa-info-circle mr-2"></i>Características
                        </h3>

                        @if($gastrobar->tipo_cocina)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-utensils text-amber-500 text-xs"></i>
                            </span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tipo de Cocina</p>
                                <p class="text-sm text-gray-700">{{ $gastrobar->tipo_cocina }}</p>
                            </div>
                        </div>
                        @endif

                        @if($gastrobar->capacidad)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-blue-500 text-xs"></i>
                            </span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Capacidad</p>
                                <p class="text-sm text-gray-700">{{ $gastrobar->capacidad }} personas</p>
                            </div>
                        </div>
                        @endif

                        @if($gastrobar->municipio)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-rose-500 text-xs"></i>
                            </span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Ubicación</p>
                                <p class="text-sm text-gray-700">
                                    {{ $gastrobar->municipio->nombre }}@if($gastrobar->municipio->departamento), {{ $gastrobar->municipio->departamento->nombre }}@endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Días de atención --}}
                    @php
                        $diasSemana = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
                        $diasNombres = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
                        $diasActivos = $gastrobar->dias_atencion ?? [];
                        if(is_string($diasActivos)) $diasActivos = json_decode($diasActivos, true) ?? [];
                    @endphp
                    @if(count($diasActivos))
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2 mb-4">
                            <i class="fas fa-calendar-week mr-2"></i>Días de Atención
                        </h3>
                        <div class="grid grid-cols-7 gap-1">
                            @foreach($diasSemana as $i => $dia)
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[9px] font-black uppercase text-gray-400">{{ $diasNombres[$i] }}</span>
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold
                                    {{ in_array($dia, $diasActivos) ? 'bg-purple-600 text-white shadow-sm shadow-purple-200' : 'bg-gray-100 text-gray-300' }}">
                                    {{ in_array($dia, $diasActivos) ? '✓' : '–' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Redes sociales --}}
                    @if($gastrobar->instagram || $gastrobar->facebook || $gastrobar->tiktok)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2 mb-4">
                            <i class="fas fa-share-alt mr-2"></i>Redes Sociales
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @if($gastrobar->instagram)
                            <a href="{{ $gastrobar->instagram }}" target="_blank"
                               class="flex items-center gap-2 bg-gradient-to-br from-pink-500 to-purple-600 text-white text-xs font-bold px-4 py-2 rounded-xl no-underline hover:opacity-90 transition-opacity">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                            @endif
                            @if($gastrobar->facebook)
                            <a href="{{ $gastrobar->facebook }}" target="_blank"
                               class="flex items-center gap-2 bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl no-underline hover:opacity-90 transition-opacity">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                            @endif
                            @if($gastrobar->tiktok)
                            <a href="{{ $gastrobar->tiktok }}" target="_blank"
                               class="flex items-center gap-2 bg-zinc-900 text-white text-xs font-bold px-4 py-2 rounded-xl no-underline hover:opacity-90 transition-opacity">
                                <i class="fab fa-tiktok"></i> TikTok
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Columna Derecha: Mapa --}}
                <div class="md:col-span-2 space-y-4">

                    {{-- Mapa --}}
                    @if($gastrobar->latitud && $gastrobar->longitud)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 pt-5 pb-3 flex items-center justify-between">
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">
                                <i class="fas fa-map-marker-alt text-purple-500 mr-2"></i>Ubicación en Mapa
                            </h3>
                            <a href="https://www.google.com/maps?q={{ $gastrobar->latitud }},{{ $gastrobar->longitud }}"
                               target="_blank"
                               class="text-[10px] font-bold text-purple-600 hover:underline no-underline flex items-center gap-1">
                                <i class="fas fa-external-link-alt"></i> Google Maps
                            </a>
                        </div>

                        @if($gastrobar->direccion)
                        <p class="px-6 pb-3 text-xs text-gray-500 flex items-start gap-2">
                            <i class="fas fa-map-pin text-purple-400 mt-0.5 flex-shrink-0"></i>
                            {{ $gastrobar->direccion }}
                        </p>
                        @endif

                        <div id="mapa-show" class="w-full" style="height: 320px;"></div>
                    </div>
                    @endif

                    {{-- Galería --}}
                    @php
                        $galeria = $gastrobar->galeria ?? [];
                        if(is_string($galeria)) $galeria = json_decode($galeria, true) ?? [];
                    @endphp
                    @if(count($galeria))
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2 mb-4">
                            <i class="fas fa-images mr-2"></i>Galería de Fotos
                        </h3>
                        <div class="grid grid-cols-2 {{ count($galeria) > 2 ? 'sm:grid-cols-4' : 'sm:grid-cols-2' }} gap-3">
                            @foreach($galeria as $foto)
                            <a href="{{ asset('storage/' . $foto) }}" target="_blank" class="block aspect-square rounded-xl overflow-hidden group relative">
                                <img src="{{ asset('storage/' . $foto) }}" alt="Foto galería"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                    <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition-opacity text-sm"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- ══════════════════════════════════════════
                Footer Actions
            ══════════════════════════════════════════ --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Registrado: {{ $gastrobar->created_at->format('d/m/Y') }}
                    @if($gastrobar->updated_at && $gastrobar->updated_at != $gastrobar->created_at)
                        · Actualizado: {{ $gastrobar->updated_at->diffForHumans() }}
                    @endif
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.gastrobares.edit', $gastrobar->id) }}"
                       class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors no-underline shadow-md shadow-purple-100">
                        <i class="fas fa-pen-to-square"></i> Editar Gastrobar
                    </a>
                    <form action="{{ route('admin.gastrobares.destroy', $gastrobar->id) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar este gastrobar? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-bold px-5 py-2.5 rounded-xl transition-colors border border-red-100 cursor-pointer">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @if($gastrobar->latitud && $gastrobar->longitud)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lat = {{ $gastrobar->latitud }};
            const lng = {{ $gastrobar->longitud }};

            const mapa = L.map('mapa-show', { zoomControl: true, scrollWheelZoom: false })
                          .setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapa);

            const iconoMorado = L.divIcon({
                html: '<div style="background:#9333ea;width:22px;height:22px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,0.35)"></div>',
                iconSize: [22, 22], iconAnchor: [11, 22], className: ''
            });

            L.marker([lat, lng], { icon: iconoMorado })
             .addTo(mapa)
             .bindPopup(`<strong style="font-family:sans-serif;">{{ addslashes($gastrobar->nombre) }}</strong>
                         @if($gastrobar->direccion)<br><span style="font-size:11px;color:#666;">{{ addslashes(Str::limit($gastrobar->direccion, 60)) }}</span>@endif`)
             .openPopup();
        });
    </script>
    @endif

</x-app-layout>