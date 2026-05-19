<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('restaurantes.index') }}"
               class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors no-underline">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                    Ficha Técnica: {{ $restaurante->nombre }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Expediente de control del establecimiento registrado.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        {{-- Card de Información Principal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
            
            {{-- Encabezado de Ficha --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-6 mb-6 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-orange-500 flex items-center justify-center text-white font-black text-xl shadow-md shadow-orange-100">
                        {{ strtoupper(substr($restaurante->nombre, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $restaurante->nombre }}</h3>
                        <p class="text-xs text-gray-400 font-medium">ID Establecimiento: #{{ $restaurante->id }}</p>
                    </div>
                </div>
                <div>
                    @if($restaurante->activo)
                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Establecimiento Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs font-semibold px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo / Pausado
                        </span>
                    @endif
                </div>
            </div>

            {{-- Rejilla de Datos Tácticos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Correo Electrónico --}}
                <div class="bg-gray-50/50 border border-gray-100 p-4 rounded-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Correo de Contacto</p>
                    <p class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-envelope text-gray-400 text-xs"></i>
                        {{ $restaurante->email ?? 'N/A' }}
                    </p>
                </div>

                {{-- Especialidad Culinaria --}}
                <div class="bg-gray-50/50 border border-gray-100 p-4 rounded-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Especialidad Culinaria</p>
                    <p class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-utensils text-orange-500 text-xs"></i>
                        {{ $restaurante->especialidad ?? 'No especificada' }}
                    </p>
                </div>

                {{-- Departamento --}}
                <div class="bg-gray-50/50 border border-gray-100 p-4 rounded-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Departamento</p>
                    <p class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-orange-500 text-xs"></i>
                        {{ $restaurante->departamento->nombre ?? 'N/A' }}
                    </p>
                </div>

                {{-- Municipio --}}
                <div class="bg-gray-50/50 border border-gray-100 p-4 rounded-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Municipio / Localidad</p>
                    <p class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-map text-blue-500 text-xs"></i>
                        {{ $restaurante->municipio->nombre ?? 'Sin municipio asignado' }}
                    </p>
                </div>

                {{-- NUEVA SECCIÓN: Canales Digitales y Redes --}}
                <div class="md:col-span-2 mt-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-2">
                        <i class="fas fa-share-alt text-orange-400"></i> Presencia Digital y Contacto Directo
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- WhatsApp --}}
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl text-sm">
                            <span class="flex items-center gap-2 font-medium text-gray-600">
                                <i class="fab fa-whatsapp text-green-500 text-base"></i> WhatsApp
                            </span>
                            @if($restaurante->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurante->whatsapp) }}" target="_blank" class="font-bold text-green-600 hover:underline">
                                    {{ $restaurante->whatsapp }} <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">No registrado</span>
                            @endif
                        </div>

                        {{-- Instagram --}}
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl text-sm">
                            <span class="flex items-center gap-2 font-medium text-gray-600">
                                <i class="fab fa-instagram text-pink-500 text-base"></i> Instagram
                            </span>
                            @if($restaurante->instagram)
                                <a href="{{ $restaurante->instagram }}" target="_blank" class="font-bold text-pink-600 hover:underline truncate max-w-[150px]">
                                    Ver perfil <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">No registrado</span>
                            @endif
                        </div>

                        {{-- TikTok --}}
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl text-sm">
                            <span class="flex items-center gap-2 font-medium text-gray-600">
                                <i class="fab fa-tiktok text-black text-base"></i> TikTok
                            </span>
                            @if($restaurante->tiktok)
                                <a href="{{ $restaurante->tiktok }}" target="_blank" class="font-bold text-gray-900 hover:underline truncate max-w-[150px]">
                                    Ver perfil <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">No registrado</span>
                            @endif
                        </div>

                        {{-- Facebook --}}
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl text-sm">
                            <span class="flex items-center gap-2 font-medium text-gray-600">
                                <i class="fab fa-facebook text-blue-600 text-base"></i> Facebook
                            </span>
                            @if($restaurante->facebook)
                                <a href="{{ $restaurante->facebook }}" target="_blank" class="font-bold text-blue-600 hover:underline truncate max-w-[150px]">
                                    Ver página <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">No registrado</span>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- Descripción extendida --}}
                <div class="md:col-span-2 bg-gray-50/50 border border-gray-100 p-4 rounded-xl mt-2">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Descripción o Reseña</p>
                    <p class="text-sm text-gray-600 leading-relaxed italic">
                        "{!! nl2br(e($restaurante->descripcion ?? 'Este establecimiento aún no cuenta con una descripción comercial.')) !!}"
                    </p>
                </div>

                {{-- Fecha de registro --}}
                <div class="md:col-span-2 text-right text-[11px] text-gray-400 font-medium px-2">
                    Registrado en el sistema el {{ $restaurante->created_at->format('d \d\e M \d\e\l Y \a \l\a\s h:i A') }}
                </div>

            </div>
        </div>

        {{-- Barra Inferior de Herramientas --}}
        <div class="flex items-center justify-between px-2">
            <a href="{{ route('restaurantes.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors no-underline">
                <i class="fas fa-chevron-left mr-1 text-xs"></i> Volver al panel
            </a>
            <a href="{{ route('restaurantes.edit', $restaurante) }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-md shadow-blue-100 text-sm no-underline">
                <i class="fas fa-pen text-xs"></i> Editar Parámetros
            </a>
        </div>
    </div>
</x-app-layout>