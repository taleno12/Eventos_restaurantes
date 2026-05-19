<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $empleo->titulo }} | Gastro Nicaragua</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
            .premium-title { font-family: 'Playfair Display', serif; }
            .hero-empleo {
                background: linear-gradient(135deg, #1c1917 0%, #292524 60%, #431407 100%);
            }
        </style>
    </head>
    <body class="bg-stone-50 text-stone-900 antialiased">

        <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-stone-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline">
                        <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                            <i class="fas fa-utensils text-white"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight premium-title italic text-stone-900">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    </a>
                    <a href="{{ route('empleos.index') }}" class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline">
                        <i class="fas fa-chevron-left text-xs mr-1"></i> Volver a empleos
                    </a>
                </div>
            </div>
        </nav>

        <header class="hero-empleo pt-36 pb-16 text-white">
            <div class="max-w-4xl mx-auto px-4">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-orange-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $empleo->restaurante->nombre }}
                        </span>
                        @if($empleo->tipo_contrato)
                            <span class="bg-white/10 border border-white/10 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                <i class="fas fa-clock mr-1"></i> {{ $empleo->tipo_contrato }}
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="premium-title text-4xl md:text-5xl font-bold leading-tight">
                        {{ $empleo->titulo }}
                    </h1>

                    <p class="text-stone-300 text-sm flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-orange-500"></i>
                        <span class="font-semibold text-white">{{ $empleo->departamento->nombre }}</span> 
                        @if($empleo->municipio)
                            — {{ $empleo->municipio->nombre }}
                        @endif
                    </p>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Columna Izquierda: Detalles del Puesto --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Descripción --}}
                    <section class="bg-white rounded-2xl p-6 border border-stone-200/60 shadow-sm">
                        <h2 class="text-lg font-bold text-stone-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-align-left text-orange-600 text-sm"></i> Descripción de la vacante
                        </h2>
                        <p class="text-stone-600 text-sm leading-relaxed whitespace-pre-line">
                            {{ $empleo->descripcion }}
                        </p>
                    </section>

                    {{-- Requisitos --}}
                    <section class="bg-white rounded-2xl p-6 border border-stone-200/60 shadow-sm">
                        <h2 class="text-lg font-bold text-stone-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-orange-600 text-sm"></i> Requisitos del puesto
                        </h2>
                        @if($empleo->requisitos)
                            <p class="text-stone-600 text-sm leading-relaxed whitespace-pre-line">
                                {{ $empleo->requisitos }}
                            </p>
                        @else
                            <p class="text-stone-400 text-sm italic">
                                El restaurante no especificó requisitos adicionales para esta posición.
                            </p>
                        @endif
                    </section>
                </div>

                {{-- Columna Derecha: Panel de Acción / Datos Financieros --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 border border-stone-200/60 shadow-sm sticky top-24 space-y-5">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-3">Resumen de oferta</h3>
                            
                            <div class="space-y-4 text-sm">
                                {{-- Salario --}}
                                <div class="border-b border-stone-100 pb-3">
                                    <span class="text-xs text-stone-400 block mb-0.5">Remuneración mensual</span>
                                    <span class="text-lg font-bold text-green-600">
                                        @if($empleo->salario)
                                            C$ {{ number_format($empleo->salario, 2) }}
                                        @else
                                            A convenir
                                        @endif
                                    </span>
                                </div>

                                {{-- Fecha Límite --}}
                                @if($empleo->fecha_limite)
                                    <div class="border-b border-stone-100 pb-3">
                                        <span class="text-xs text-stone-400 block mb-0.5">Fecha límite para aplicar</span>
                                        <span class="text-sm font-semibold text-stone-700 flex items-center gap-1.5">
                                            <i class="far fa-calendar-times text-red-500"></i>
                                            {{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d \d\e M, Y') }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Publicación --}}
                                <div class="border-b border-stone-100 pb-3">
                                    <span class="text-xs text-stone-400 block mb-0.5">Publicado</span>
                                    <span class="text-xs font-medium text-stone-500">
                                        {{ $empleo->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN INTERACTIVA: Redes del Establecimiento para el postulante --}}
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-stone-400 block mb-3">Conoce el Establecimiento</span>
                            
                            <div class="flex flex-wrap items-center gap-2.5">
                                {{-- WhatsApp --}}
                                @if(!empty($empleo->restaurante->whatsapp))
                                    @php
                                        $phoneClean = preg_replace('/[^0-9]/', '', $empleo->restaurante->whatsapp);
                                    @endphp
                                    <a href="https://wa.me/{{ $phoneClean }}" 
                                       target="_blank" 
                                       title="Consultas rápidas por WhatsApp"
                                       class="w-9 h-9 rounded-full bg-green-50 hover:bg-green-500 text-green-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                    </a>
                                @endif

                                {{-- Instagram --}}
                                @if(!empty($empleo->restaurante->instagram))
                                    <a href="{{ $empleo->restaurante->instagram }}" 
                                       target="_blank" 
                                       title="Ver Instagram del restaurante"
                                       class="w-9 h-9 rounded-full bg-pink-50 hover:bg-gradient-to-tr hover:from-yellow-500 hover:to-purple-600 text-pink-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-instagram text-lg"></i>
                                    </a>
                                @endif

                                {{-- TikTok --}}
                                @if(!empty($empleo->restaurante->tiktok))
                                    <a href="{{ $empleo->restaurante->tiktok }}" 
                                       target="_blank" 
                                       title="Ver TikTok del restaurante"
                                       class="w-9 h-9 rounded-full bg-stone-50 hover:bg-black text-stone-800 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-tiktok text-sm"></i>
                                    </a>
                                @endif

                                {{-- Facebook --}}
                                @if(!empty($empleo->restaurante->facebook))
                                    <a href="{{ $empleo->restaurante->facebook }}" 
                                       target="_blank" 
                                       title="Ver Facebook del restaurante"
                                       class="w-9 h-9 rounded-full bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-facebook-f text-base"></i>
                                    </a>
                                @endif

                                {{-- Mensaje en caso de no poseer redes en la Base de Datos --}}
                                @if(empty($empleo->restaurante->whatsapp) && empty($empleo->restaurante->instagram) && empty($empleo->restaurante->tiktok) && empty($empleo->restaurante->facebook))
                                    <span class="text-xs text-stone-400 italic">Sin redes configuradas.</span>
                                @endif
                            </div>
                        </div>

                        {{-- Botón de Postulación Premium --}}
                        <div class="pt-1">
                            <a href="mailto:{{ $empleo->restaurante->email ?? 'contacto@gastronicaragua.com' }}?subject=Postulación: {{ $empleo->titulo }}"
                               class="w-full bg-orange-600 hover:bg-orange-700 text-white text-center font-bold py-3 px-4 rounded-xl transition-all shadow-sm shadow-orange-200 block no-underline text-sm">
                                <i class="fas fa-paper-plane mr-2 text-xs"></i> Aplicar a esta vacante
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <footer class="bg-stone-900 text-white py-12 mt-20">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <p class="text-stone-500 text-xs tracking-widest uppercase font-bold m-0">© 2026 Gastro Nicaragua — Experiencias Culinarias</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>