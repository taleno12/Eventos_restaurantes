<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('restaurantes.index') }}"
               class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors no-underline">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Editar Restaurante</h2>
                <p class="text-sm text-gray-500 mt-0.5">Modifica los parámetros y canales digitales del establecimiento seleccionado.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('restaurantes.update', $restaurante->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Errores globales de validación --}}
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
                
                {{-- Sección 1: Datos de Identidad --}}
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6 flex items-center gap-2">
                    <i class="fas fa-edit text-orange-400"></i> Datos Generales: {{ $restaurante->nombre }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nombre Comercial --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre Comercial <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-store text-sm"></i>
                            </div>
                            <input type="text" name="nombre" value="{{ old('nombre', $restaurante->nombre) }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" required>
                        </div>
                    </div>

                    {{-- Email de Contacto --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email de Contacto <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $restaurante->email) }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" required>
                        </div>
                    </div>

                    {{-- Especialidad Culinaria --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Especialidad Culinaria <span class="text-gray-400 font-normal">(ej. Mariscos, Asados)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-utensils text-sm"></i>
                            </div>
                            <input type="text" name="especialidad" value="{{ old('especialidad', $restaurante->especialidad) }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" placeholder="No especificada">
                        </div>
                    </div>

                    {{-- Separador Visual Interno --}}
                    <div class="md:col-span-2 my-2 border-t border-gray-100"></div>

                    {{-- Sección 2: Ubicación Regional --}}
                    <div class="md:col-span-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2 mb-1">
                            <i class="fas fa-map-marked-alt text-orange-400"></i> Ubicación Geográfica
                        </h4>
                    </div>

                    {{-- Departamento --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Departamento <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <select id="select-departamento" name="departamento_id"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" required>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}" @selected(old('departamento_id', $restaurante->departamento_id) == $depto->id)>
                                        {{ $depto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Municipio <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-map text-sm"></i>
                            </div>
                            <select id="select-municipio" name="municipio_id"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50 disabled:opacity-60 disabled:cursor-not-allowed" required disabled>
                                <option value="">Cargando municipios...</option>
                            </select>
                        </div>
                    </div>

                    {{-- Separador Visual Interno --}}
                    <div class="md:col-span-2 my-2 border-t border-gray-100"></div>

                    {{-- Sección 3: Redes y Canales Digitales --}}
                    <div class="md:col-span-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2 mb-1">
                            <i class="fas fa-share-alt text-orange-400"></i> Contacto Directo y Redes Sociales
                        </h4>
                    </div>

                    {{-- WhatsApp / Teléfono Comercial --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">WhatsApp Comercial <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </div>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $restaurante->whatsapp) }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" placeholder="Ej: +505 8888-8888" required>
                        </div>
                    </div>

                    {{-- Instagram URL --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Enlace de Instagram <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-instagram text-sm"></i>
                            </div>
                            <input type="url" name="instagram" value="{{ old('instagram', $restaurante->instagram) }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" placeholder="https://instagram.com/perfil">
                        </div>
                    </div>

                    {{-- TikTok URL --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Enlace de TikTok <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-tiktok text-sm"></i>
                            </div>
                            <input type="url" name="tiktok" value="{{ old('tiktok', $restaurante->tiktok) }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" placeholder="https://tiktok.com/@usuario">
                        </div>
                    </div>

                    {{-- Facebook URL --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Enlace de Facebook <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fab fa-facebook text-sm"></i>
                            </div>
                            <input type="url" name="facebook" value="{{ old('facebook', $restaurante->facebook) }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-gray-50/50" placeholder="https://facebook.com/pagina">
                        </div>
                    </div>

                    {{-- Separador Visual Interno --}}
                    <div class="md:col-span-2 my-2 border-t border-gray-100"></div>

                    {{-- Descripción --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción Comercial <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <textarea name="descripcion" rows="4"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-gray-50/50 resize-none" placeholder="Escribe detalles breves sobre el menú, ambiente o historia del restaurante...">{{ old('descripcion', $restaurante->descripcion) }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Botones de Control Inferior --}}
            <div class="flex items-center justify-between px-2">
                <a href="{{ route('restaurantes.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors no-underline">
                    <i class="fas fa-chevron-left mr-1 text-xs"></i> Cancelar cambios
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-7 py-3 rounded-xl transition-all shadow-md shadow-orange-200 text-sm">
                    <i class="fas fa-sync-alt text-xs"></i> Guardar Cambios
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

            deptoSelect.addEventListener('change', function() {
                const deptoId = this.value;
                if (deptoId) {
                    cargarMunicipios(deptoId);
                } else {
                    muniSelect.innerHTML = '<option value="">— Primero elige departamento —</option>';
                    muniSelect.disabled = true;
                }
            });

            const deptoInicial = deptoSelect.value;
            const muniInicial  = "{{ old('municipio_id', $restaurante->municipio_id) }}";
            
            if (deptoInicial) {
                cargarMunicipios(deptoInicial, muniInicial);
            }
        });
    </script>
</x-app-layout>