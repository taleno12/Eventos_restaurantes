<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('restaurantes.index') }}"
               class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Nuevo Restaurante</h2>
                <p class="text-sm text-gray-500 mt-0.5">Registra un nuevo restaurante en la plataforma.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('restaurantes.store') }}" method="POST">
            @csrf

            {{-- Errores globales --}}
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
                    <p class="font-semibold mb-1">
                        <i class="fas fa-exclamation-circle mr-1"></i> Corrige los siguientes errores:
                    </p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card Principal --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6 flex items-center gap-2">
                    <i class="fas fa-store text-orange-400"></i> Información del Restaurante
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nombre --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-store text-sm"></i>
                            </div>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                   placeholder="Nombre del restaurante"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('nombre') border-red-400 @enderror"
                                   required>
                        </div>
                        @error('nombre')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   placeholder="correo@restaurante.com"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('email') border-red-400 @enderror"
                                   required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Especialidad Gastronómica --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Especialidad culinaria <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-utensils text-sm"></i>
                            </div>
                            <input type="text" name="especialidad" value="{{ old('especialidad') }}"
                                   placeholder="Ej: Mariscos, Asados, Comida Típica..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('especialidad') border-red-400 @enderror"
                                   required>
                        </div>
                        @error('especialidad')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Departamento --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Departamento <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <select id="select-departamento" name="departamento_id"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('departamento_id') border-red-400 @enderror"
                                    required>
                                <option value="">— Seleccionar departamento —</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}"
                                        @selected(old('departamento_id') == $departamento->id)>
                                        {{ $departamento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('departamento_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Municipio --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Municipio <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-map text-sm"></i>
                            </div>
                            <select id="select-municipio" name="municipio_id"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 disabled:opacity-60 disabled:cursor-not-allowed @error('municipio_id') border-red-400 @enderror"
                                    required disabled>
                                <option value="">— Primero elige departamento —</option>
                            </select>
                        </div>
                        @error('municipio_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NUEVA SECCIÓN: Redes Sociales y Contacto --}}
                    <div class="md:col-span-2 mt-2">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6 flex items-center gap-2">
                            <i class="fas fa-share-alt text-orange-400"></i> Redes Sociales y Contacto
                        </h3>
                    </div>

                    {{-- Instagram --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Enlace de Instagram <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-instagram text-sm"></i>
                            </div>
                            <input type="url" name="instagram" value="{{ old('instagram') }}"
                                   placeholder="https://instagram.com/nombre"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('instagram') border-red-400 @enderror">
                        </div>
                        @error('instagram')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TikTok --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Enlace de TikTok <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-tiktok text-sm"></i>
                            </div>
                            <input type="url" name="tiktok" value="{{ old('tiktok') }}"
                                   placeholder="https://tiktok.com/@nombre"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('tiktok') border-red-400 @enderror">
                        </div>
                        @error('tiktok')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Facebook --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Enlace de Facebook <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-facebook text-sm"></i>
                            </div>
                            <input type="url" name="facebook" value="{{ old('facebook') }}"
                                   placeholder="https://facebook.com/nombre"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('facebook') border-red-400 @enderror">
                        </div>
                        @error('facebook')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Número de WhatsApp <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </div>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                                   placeholder="Ej: +50588888888"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 @error('whatsapp') border-red-400 @enderror">
                        </div>
                        @error('whatsapp')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Descripción <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <textarea name="descripcion" rows="4"
                                  placeholder="Describe brevemente el restaurante, tipo de cocina, ambiente..."
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-gray-50/50 resize-none">{{ old('descripcion') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('restaurantes.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
                    <i class="fas fa-chevron-left mr-1 text-xs"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-7 py-3 rounded-xl transition-all shadow-md shadow-orange-200 text-sm border-0 cursor-pointer">
                    <i class="fas fa-paper-plane"></i> Guardar Restaurante
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deptoSelect = document.getElementById('select-departamento');
            const muniSelect  = document.getElementById('select-municipio');

            function cargarMunicipios(deptoId, municipioSeleccionado = null) {
                muniSelect.disabled = true;
                muniSelect.innerHTML = '<option value="">Cargando municipios...</option>';

                fetch(`/api/departamentos/${deptoId}/municipios`)
                    .then(response => response.json())
                    .then(data => {
                        muniSelect.innerHTML = '<option value="">— Seleccionar municipio —</option>';
                        
                        if (data.length > 0) {
                            data.forEach(muni => {
                                const option = document.createElement('option');
                                option.value = muni.id;
                                option.textContent = muni.nombre;
                                if (municipioSeleccionado && muni.id == municipioSeleccionado) {
                                    option.selected = true;
                                }
                                muniSelect.appendChild(option);
                            });
                            muniSelect.disabled = false;
                        } else {
                            muniSelect.innerHTML = '<option value="">Sin municipios registrados</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        muniSelect.innerHTML = '<option value="">Error de servidor</option>';
                    });
            }

            // Escuchar cambios en el selector de departamentos
            deptoSelect.addEventListener('change', function() {
                const deptoId = this.value;
                if (deptoId) {
                    cargarMunicipios(deptoId);
                } else {
                    muniSelect.innerHTML = '<option value="">— Primero elige departamento —</option>';
                    muniSelect.disabled = true;
                }
            });

            // Recuperar la selección si el formulario regresa con errores de validación
            const deptoInicial = deptoSelect.value;
            const muniInicial  = "{{ old('municipio_id') }}";
            
            if (deptoInicial) {
                cargarMunicipios(deptoInicial, muniInicial);
            }
        });
    </script>
</x-app-layout>