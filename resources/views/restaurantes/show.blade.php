<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.restaurantes.index') }}"
                   class="w-10 h-10 rounded-xl bg-white hover:bg-gray-50 flex items-center justify-center text-gray-500 shadow-sm border border-gray-200 transition-all hover:scale-95 no-underline">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                        Ficha Técnica: {{ $restaurante->nombre }}
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium tracking-wide">EXPEDIENTE DE CONTROL DE LOGÍSTICA GASTRONÓMICA</p>
                </div>
            </div>

            <div>
                @if($restaurante->activo ?? true)
                    <span class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 text-xs font-bold px-4 py-2 rounded-xl border border-emerald-100 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Establecimiento Activo
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-500 text-xs font-bold px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-gray-400"></span> Inactivo / Pausado
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Banner de Portada Superior Dinámico --}}
        <div class="relative h-48 sm:h-64 rounded-3xl overflow-hidden mb-8 shadow-sm border border-gray-100 bg-gradient-to-r from-gray-900 to-slate-800">
            @if($restaurante->foto_portada)
                <img src="{{ asset('storage/' . $restaurante->foto_portada) }}" 
                     alt="Portada {{ $restaurante->nombre }}" 
                     class="w-full h-full object-cover opacity-50 scale-105 transition-transform duration-700 hover:scale-100">
            @elseif($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                <img src="{{ asset('storage/' . $restaurante->imagenes->first()->ruta_foto) }}" 
                     alt="Portada {{ $restaurante->nombre }}" 
                     class="w-full h-full object-cover opacity-50 scale-105">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full p-6 sm:p-8 flex items-end gap-4 sm:gap-6">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white font-black text-2xl sm:text-3xl shadow-lg border-2 border-white/20">
                    {{ strtoupper(substr($restaurante->nombre, 0, 1)) }}
                </div>
                <div class="mb-1">
                    <span class="text-xs font-black uppercase tracking-widest text-orange-400 bg-orange-950/50 px-2 py-0.5 rounded border border-orange-500/30">
                        {{ $restaurante->especialidad ?? 'Gastronomía' }}
                    </span>
                    <h1 class="text-xl sm:text-3xl font-extrabold text-white mt-1.5 drop-shadow-sm">{{ $restaurante->nombre }}</h1>
                    <p class="text-xs text-gray-300 font-medium mt-0.5">ID Identificador: <span class="text-amber-400 font-mono">#{{ $restaurante->id }}</span></p>
                </div>
            </div>
        </div>

        {{-- Grid Principal de Contenido --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- COLUMNA IZQUIERDA --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Bloque de Datos Generales --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 transition-shadow hover:shadow-md">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-orange-500"></i> Parámetros de Ubicación y Especialidad
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50/70 border border-slate-100 p-4 rounded-xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Correo Electrónico</p>
                            <p class="text-sm font-semibold text-gray-700 flex items-center gap-2.5">
                                <i class="fas fa-envelope text-slate-400 text-sm"></i>
                                <span class="truncate">{{ $restaurante->email ?? 'Sin correo registrado' }}</span>
                            </p>
                        </div>

                        <div class="bg-slate-50/70 border border-slate-100 p-4 rounded-xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Especialidad Culinaria</p>
                            <p class="text-sm font-semibold text-gray-700 flex items-center gap-2.5">
                                <i class="fas fa-utensils text-orange-500 text-sm"></i>
                                <span>{{ $restaurante->especialidad ?? 'No especificada' }}</span>
                            </p>
                        </div>

                        <div class="bg-slate-50/70 border border-slate-100 p-4 rounded-xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Departamento</p>
                            <p class="text-sm font-semibold text-gray-700 flex items-center gap-2.5">
                                <i class="fas fa-map-marker-alt text-rose-500 text-sm"></i>
                                <span>{{ $restaurante->departamento->nombre ?? 'N/A' }}</span>
                            </p>
                        </div>

                        <div class="bg-slate-50/70 border border-slate-100 p-4 rounded-xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Municipio / Localidad</p>
                            <p class="text-sm font-semibold text-gray-700 flex items-center gap-2.5">
                                <i class="fas fa-map text-blue-500 text-sm"></i>
                                <span>{{ $restaurante->municipio->nombre ?? 'Sin municipio asignado' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 bg-slate-50/40 border border-slate-100 p-5 rounded-xl">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Descripción Comercial</p>
                        <p class="text-sm text-gray-600 leading-relaxed italic font-medium">
                            "{!! nl2br(e($restaurante->descripcion ?? 'Este establecimiento aún no cuenta con una descripción comercial.')) !!}"
                        </p>
                    </div>
                </div>

                {{-- Bloque de Galería Fotográfica --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-5 flex items-center gap-2">
                        <i class="fas fa-images text-orange-500"></i> Galería de Álbum Fotográfico
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2 aspect-[16/10] rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 group relative shadow-inner">
                            @if($restaurante->foto_portada)
                                <img src="{{ asset('storage/' . $restaurante->foto_portada) }}" 
                                     alt="Imagen principal" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @elseif($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                                <img src="{{ asset('storage/' . $restaurante->imagenes->first()->ruta_foto) }}" 
                                     alt="Imagen principal" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                                    <span class="text-xs font-medium">Sin foto de portada asignada</span>
                                </div>
                            @endif
                            <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wider uppercase">
                                Imagen de Portada
                            </div>
                        </div>

                        <div class="grid grid-cols-3 sm:grid-cols-1 gap-3">
                            @if($restaurante->imagenes && $restaurante->imagenes->count() > 1)
                                @foreach($restaurante->imagenes->skip($restaurante->foto_portada ? 0 : 1)->take(3) as $imagen)
                                    <div class="aspect-video sm:aspect-[4/3] rounded-xl overflow-hidden bg-gray-50 border border-gray-100 group relative shadow-sm">
                                        <img src="{{ asset('storage/' . $imagen->ruta_foto) }}" 
                                             alt="Galería" class="w-full h-full object-cover hover:scale-110 transition-all duration-300">
                                    </div>
                                @endforeach
                            @else
                                <div class="hidden sm:flex aspect-[4/3] border border-dashed border-gray-200 rounded-xl items-center justify-center bg-gray-50/50 text-gray-300">
                                    <div class="text-center">
                                        <i class="fas fa-plus text-xs mb-1 block"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Álbum Vacío</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: Sidebar --}}
            <div class="space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-4 flex items-center gap-2">
                        <i class="fas fa-fingerprint text-orange-400"></i> Ecosistema Digital
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-sm">
                            <span class="flex items-center gap-2.5 font-bold text-gray-600 text-xs">
                                <i class="fab fa-whatsapp text-emerald-500 text-base w-5 text-center"></i> WHATSAPP
                            </span>
                            @if($restaurante->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurante->whatsapp) }}" target="_blank" class="font-bold text-xs text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100 transition-colors no-underline">
                                    {{ $restaurante->whatsapp }} <i class="fas fa-external-link-alt text-[9px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-[11px] text-gray-400 italic bg-white px-2 py-0.5 rounded border border-gray-100">Ausente</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-sm">
                            <span class="flex items-center gap-2.5 font-bold text-gray-600 text-xs">
                                <i class="fab fa-instagram text-pink-500 text-base w-5 text-center"></i> INSTAGRAM
                            </span>
                            @if($restaurante->instagram)
                                <a href="{{ $restaurante->instagram }}" target="_blank" class="font-bold text-xs text-pink-600 hover:text-pink-700 bg-pink-50 px-2.5 py-1 rounded-lg border border-pink-100 transition-colors no-underline">
                                    Ver Perfil <i class="fas fa-external-link-alt text-[9px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-[11px] text-gray-400 italic bg-white px-2 py-0.5 rounded border border-gray-100">Ausente</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-sm">
                            <span class="flex items-center gap-2.5 font-bold text-gray-600 text-xs">
                                <i class="fab fa-tiktok text-gray-900 text-base w-5 text-center"></i> TIKTOK
                            </span>
                            @if($restaurante->tiktok)
                                <a href="{{ $restaurante->tiktok }}" target="_blank" class="font-bold text-xs text-gray-800 hover:text-black bg-gray-200/60 px-2.5 py-1 rounded-lg border border-gray-300/40 transition-colors no-underline">
                                    Ver Perfil <i class="fas fa-external-link-alt text-[9px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-[11px] text-gray-400 italic bg-white px-2 py-0.5 rounded border border-gray-100">Ausente</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-sm">
                            <span class="flex items-center gap-2.5 font-bold text-gray-600 text-xs">
                                <i class="fab fa-facebook text-blue-600 text-base w-5 text-center"></i> FACEBOOK
                            </span>
                            @if($restaurante->facebook)
                                <a href="{{ $restaurante->facebook }}" target="_blank" class="font-bold text-xs text-blue-600 hover:text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 transition-colors no-underline">
                                    Ver Fanpage <i class="fas fa-external-link-alt text-[9px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-[11px] text-gray-400 italic bg-white px-2 py-0.5 rounded border border-gray-100">Ausente</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Módulo de Auditoría --}}
                <div class="bg-slate-900 text-slate-300 rounded-2xl shadow-sm border border-slate-800 p-6 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-slate-800 text-7xl font-black select-none pointer-events-none opacity-40">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
                        <i class="fas fa-history text-amber-500"></i> Metadatos de Registro
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">
                        Este expediente forma parte del registro central de Gastro Studio.
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-800 text-[11px] font-mono text-slate-400 flex flex-col gap-1">
                        <span>Creado: {{ $restaurante->created_at ? $restaurante->created_at->format('d/m/Y - h:i A') : 'N/A' }}</span>
                        <span>Última mod: {{ $restaurante->updated_at ? $restaurante->updated_at->format('d/m/Y - h:i A') : 'N/A' }}</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Barra de Acción Inferior --}}
        <div class="flex flex-col sm:flex-row items-center justify-between border-t border-gray-200/70 pt-6 mt-8 gap-4">
            <a href="{{ route('admin.restaurantes.index') }}"
               class="text-xs font-bold text-gray-400 hover:text-gray-600 uppercase tracking-wider transition-colors no-underline flex items-center gap-2">
                <i class="fas fa-chevron-left text-[10px]"></i> Volver al panel de control
            </a>
            <a href="{{ route('admin.restaurantes.edit', $restaurante) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-black text-white font-bold px-7 py-3 rounded-xl transition-all shadow-md shadow-slate-200 text-xs uppercase tracking-wider no-underline hover:scale-[0.98]">
                <i class="fas fa-pen text-[10px]"></i> Editar Parámetros del Local
            </a>
        </div>
    </div>
</x-app-layout>