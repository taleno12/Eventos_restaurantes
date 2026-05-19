<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $evento->titulo }} | Gastro Nicaragua</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; }
            .premium-title { font-family: 'Playfair Display', serif; }
            .hero-blur-bg {
                background: linear-gradient(135deg, #1c1917 0%, #0c0a09 100%);
            }
        </style>
    </head>
    <body class="bg-stone-50/60 text-stone-900 antialiased">

        <nav class="fixed w-full z-50 bg-white/70 backdrop-blur-xl border-b border-stone-200/40 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 no-underline">
                        <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-600/20">
                            <i class="fas fa-utensils text-white text-sm"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight premium-title italic text-stone-900">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    </a>
                    <a href="{{ route('home') }}" class="text-sm font-bold text-stone-600 hover:text-orange-600 transition-all no-underline flex items-center gap-2 bg-stone-100 hover:bg-orange-50 px-4 py-2 rounded-full">
                        <i class="fas fa-arrow-left text-xs"></i> Volver a inicio
                    </a>
                </div>
            </div>
        </nav>

        <header class="hero-blur-bg pt-44 pb-28 text-white relative overflow-hidden">
            {{-- Luces de fondo difuminadas para dar profundidad premium --}}
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-orange-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-12 right-1/3 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff02_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
            
            <div class="max-w-6xl mx-auto px-4 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    {{-- Información del Evento (Lado Izquierdo) --}}
                    <div class="lg:col-span-7 flex flex-col gap-4">
                        <div class="flex flex-wrap gap-2.5 items-center">
                            <span class="bg-orange-600 text-white text-[11px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md shadow-orange-900/30 flex items-center gap-1.5">
                                <i class="fas fa-store text-[10px]"></i> {{ $evento->restaurante->nombre }}
                            </span>
                            @if($evento->restaurante->especialidad)
                                <span class="bg-white/10 border border-white/10 text-orange-400 text-[11px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider backdrop-blur-md">
                                    <i class="fas fa-tags text-[10px] mr-1"></i> {{ $evento->restaurante->especialidad }}
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="premium-title text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.15] drop-shadow-md tracking-tight mt-2 text-stone-50">
                            {{ $evento->titulo }}
                        </h1>

                        <div class="flex items-center gap-2 text-stone-300 text-sm sm:text-base bg-white/5 border border-white/10 px-4 py-2.5 rounded-2xl w-fit backdrop-blur-sm mt-3">
                            <i class="fas fa-map-marker-alt text-orange-500 shadow-sm"></i>
                            <span class="text-white font-bold">{{ $evento->departamento->nombre }}</span> 
                            @if($evento->municipio)
                                <span class="text-stone-500 font-light">|</span> 
                                <span class="text-stone-300 font-medium">{{ $evento->municipio->nombre }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Imagen del Evento Estilizada (Lado Derecho) --}}
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="relative group w-full max-w-md">
                            {{-- Efecto de resplandor detrás de la imagen --}}
                            <div class="absolute inset-0 bg-orange-600/20 rounded-[2rem] blur-xl opacity-40 group-hover:opacity-60 transition-opacity"></div>
                            <div class="relative rounded-[2rem] overflow-hidden shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/10 aspect-[4/3] sm:aspect-video lg:aspect-square bg-stone-900">
                                <img src="{{ asset('storage/' . $evento->imagen) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $evento->titulo }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-4 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Columna Principal (Lado Izquierdo - 8 Columnas) --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- Grid Fichas de Características Estilizadas --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white p-5 rounded-3xl border border-stone-200/60 shadow-[0_4px_20px_rgba(0,0,0,0.01)]">
                        <div class="p-3 flex items-center gap-3">
                            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 shrink-0">
                                <i class="fas fa-ticket-alt text-lg"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-0.5">Precio Entrada</span>
                                <span class="text-base font-black text-stone-800">
                                    @if($evento->precio > 0)
                                        C$ {{ number_format($evento->precio, 0) }}
                                    @else
                                        Entrada Libre
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="p-3 flex items-center gap-3 sm:border-l border-stone-100">
                            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 shrink-0">
                                <i class="far fa-calendar-alt text-lg"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-0.5">Fecha Evento</span>
                                <span class="text-sm font-bold text-stone-700">
                                    {{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('d \d\e F, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-3 flex items-center gap-3 sm:border-l border-stone-100">
                            <div class="w-12 h-12 bg-stone-50 rounded-2xl flex items-center justify-center text-stone-600 shrink-0">
                                <i class="fas fa-building text-lg"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-0.5">Establecimiento</span>
                                <span class="text-sm font-bold text-stone-700 truncate max-w-[160px] block">{{ $evento->restaurante->nombre }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Caja de Texto: Descripción --}}
                    <section class="bg-white rounded-3xl p-8 sm:p-10 border border-stone-200/60 shadow-[0_4px_20px_rgba(0,0,0,0.01)] space-y-6">
                        <h2 class="text-xs font-black uppercase tracking-widest text-stone-400 flex items-center gap-2.5 border-b border-stone-100 pb-4">
                            <i class="fas fa-align-left text-orange-600 text-sm"></i> Detalles e Información del Evento
                        </h2>
                        <p class="text-stone-600 text-base leading-relaxed whitespace-pre-line font-normal">
                            {{ $evento->descripcion ?? 'Sin descripción detallada disponible por el momento.' }}
                        </p>
                    </section>
                </div>

                {{-- Columna Lateral (Lado Derecho - 4 Columnas) --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                    <div class="bg-white rounded-3xl p-8 border border-stone-200/60 shadow-[0_15px_40px_rgba(0,0,0,0.02)] space-y-5">
                        
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-stone-400 mb-2 flex items-center gap-2">
                                <i class="far fa-clock text-stone-400"></i> Disponibilidad
                            </h3>
                            <div class="border-b border-stone-100 pb-3 flex justify-between items-center">
                                <span class="text-stone-400 font-medium text-sm">Vigencia</span>
                                <span class="countdown font-bold text-xs text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-xl" data-expire="{{ $evento->fecha_evento }}">
                                    Calculando...
                                </span>
                            </div>
                        </div>

                        <div class="pt-1">
                            <span class="text-stone-400 font-medium text-sm block mb-2">Contacto Sede</span>
                            <span class="text-xs text-stone-700 font-bold truncate block w-full bg-stone-50 px-3 py-2 rounded-xl border border-stone-100">
                                {{ $evento->restaurante->email ?? 'No disponible' }}
                            </span>
                        </div>

                        {{-- SECCIÓN AGREGADA: Canales Digitales y Redes Sociales --}}
                        <div class="border-t border-stone-100 pt-4">
                            <span class="text-stone-400 font-medium text-sm block mb-3">Redes del Establecimiento</span>
                            
                            <div class="flex flex-wrap items-center gap-2.5">
                                {{-- WhatsApp --}}
                                @if(!empty($evento->restaurante->whatsapp))
                                    @php
                                        $phoneClean = preg_replace('/[^0-9]/', '', $evento->restaurante->whatsapp);
                                    @endphp
                                    <a href="https://wa.me/{{ $phoneClean }}" 
                                       target="_blank" 
                                       title="Contactar por WhatsApp"
                                       class="w-9 h-9 rounded-full bg-green-50 hover:bg-green-500 text-green-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                    </a>
                                @endif

                                {{-- Instagram --}}
                                @if(!empty($evento->restaurante->instagram))
                                    <a href="{{ $evento->restaurante->instagram }}" 
                                       target="_blank" 
                                       title="Visitar Instagram"
                                       class="w-9 h-9 rounded-full bg-pink-50 hover:bg-gradient-to-tr hover:from-yellow-500 hover:to-purple-600 text-pink-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-instagram text-lg"></i>
                                    </a>
                                @endif

                                {{-- TikTok --}}
                                @if(!empty($evento->restaurante->tiktok))
                                    <a href="{{ $evento->restaurante->tiktok }}" 
                                       target="_blank" 
                                       title="Ver en TikTok"
                                       class="w-9 h-9 rounded-full bg-stone-50 hover:bg-black text-stone-800 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-tiktok text-sm"></i>
                                    </a>
                                @endif

                                {{-- Facebook --}}
                                @if(!empty($evento->restaurante->facebook))
                                    <a href="{{ $evento->restaurante->facebook }}" 
                                       target="_blank" 
                                       title="Ir a Facebook"
                                       class="w-9 h-9 rounded-full bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-facebook-f text-base"></i>
                                    </a>
                                @endif

                                {{-- Fallback si no tiene canales configurados --}}
                                @if(empty($evento->restaurante->whatsapp) && empty($evento->restaurante->instagram) && empty($evento->restaurante->tiktok) && empty($evento->restaurante->facebook))
                                    <span class="text-xs text-stone-400 italic">Sin redes sociales registradas.</span>
                                @endif
                            </div>
                        </div>

                        {{-- Botón de Acción Principal --}}
                        <div class="pt-2">
                            <a href="mailto:{{ $evento->restaurante->email ?? 'contacto@gastronicaragua.com' }}?subject=Consulta sobre Evento: {{ $evento->titulo }}"
                               class="w-full bg-stone-950 hover:bg-orange-600 text-white text-center font-bold py-3.5 px-4 rounded-xl transition-all shadow-md hover:shadow-orange-600/10 block no-underline text-sm border-0 cursor-pointer">
                                <i class="fas fa-paper-plane mr-2 text-xs"></i> Enviar Consulta al Local
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <footer class="bg-stone-950 text-white py-16 border-t border-white/5">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <p class="text-stone-600 text-xs tracking-widest uppercase font-bold m-0">© 2026 Gastro Nicaragua — Experiencias Culinarias</p>
            </div>
        </footer

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function updateCountdowns() {
                document.querySelectorAll('.countdown').forEach(el => {
                    const expireStr = el.getAttribute('data-expire');
                    const expireDate = new Date(expireStr).getTime();
                    const distance   = expireDate - Date.now();

                    if (distance < 0) { el.innerHTML = 'FINALIZADO'; return; }

                    const days    = Math.floor(distance / 86400000);
                    const hours   = Math.floor((distance % 86400000) / 3600000);
                    const minutes = Math.floor((distance % 3600000)   / 60000);

                    el.innerHTML = `Faltan: ${days}d ${hours}h ${minutes}m`;
                });
            }
            setInterval(updateCountdowns, 60000);
            updateCountdowns();
        </script>
    </body>
</html>