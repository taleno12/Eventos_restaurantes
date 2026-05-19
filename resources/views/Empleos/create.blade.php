<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.empleos.index') }}"
               class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors text-gray-500">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-plus-circle text-blue-500"></i>
                    Nueva Oferta de Empleo
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Completa los campos para publicar una vacante.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.empleos.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Errores globales --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
                    <p class="font-semibold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card principal --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 pb-3">
                    Información de la Vacante
                </h3>

                {{-- PASO 1: Departamento (Ancho completo) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Departamento de la Vacante <span class="text-red-500">*</span>
                    </label>
                    <select id="select-departamento" name="departamento_id"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('departamento_id') border-red-400 @enderror" required>
                        <option value="">— Seleccionar departamento —</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" {{ old('departamento_id') == $depto->id ? 'selected' : '' }}>
                                {{ $depto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- PASO 2: Municipio (Depende del Departamento) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Municipio de la Vacante <span class="text-red-500">*</span>
                        </label>
                        <select id="select-municipio" name="municipio_id" disabled
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent disabled:opacity-60 @error('municipio_id') border-red-400 @enderror" required>
                            <option value="">— Primero elige departamento —</option>
                        </select>
                        @error('municipio_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASO 3: Restaurante (Depende del Municipio) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Restaurante <span class="text-red-500">*</span>
                        </label>
                        <select id="select-restaurante" name="restaurante_id" disabled
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent disabled:opacity-60 @error('restaurante_id') border-red-400 @enderror" required>
                            <option value="">— Primero elige municipio —</option>
                        </select>
                        @error('restaurante_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Título del puesto --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Título del puesto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}"
                           placeholder="Ej: Mesero, Chef de cocina, Cajero..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('titulo') border-red-400 @enderror" required>
                    @error('titulo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Descripción del puesto <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descripcion" rows="4"
                              placeholder="Describe las responsabilidades y funciones del puesto..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none @error('descripcion') border-red-400 @enderror" required>{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Requisitos --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Requisitos <span class="text-gray-400 font-normal">(opcional)</span>
                    </label>
                    <textarea name="requisitos" rows="3"
                              placeholder="Experiencia requerida, estudios, habilidades..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none">{{ old('requisitos') }}</textarea>
                </div>

                {{-- Tipo contrato + Salario --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipo de contrato</label>
                        <select name="tipo_contrato"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                            <option value="">— Seleccionar —</option>
                            @foreach(['Tiempo completo', 'Medio tiempo', 'Por horas', 'Temporal', 'Fines de semana'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_contrato') == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Salario mensual (C$) <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="number" name="salario" value="{{ old('salario') }}"
                               placeholder="Ej: 8000  (dejar vacío = A convenir)"
                               min="0" step="0.01"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('salario') border-red-400 @enderror">
                        @error('salario')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Fecha límite + Estado --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Fecha límite de aplicación <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="date" name="fecha_limite" value="{{ old('fecha_limite') }}"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('fecha_limite') border-red-400 @enderror">
                        @error('fecha_limite')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <div class="relative">
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" value="1" id="activo"
                                       {{ old('activo', '1') ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-checked:bg-blue-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-blue-300"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Publicar oferta activa</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.empleos.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow border-0 cursor-pointer">
                    <i class="fas fa-paper-plane"></i> Publicar Oferta
                </button>
            </div>
        </form>
    </div>

    {{-- SCRIPT JAVASCRIPT: Lógica de Encadenamiento Triple Dinámico --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deptoSelect = document.getElementById('select-departamento');
            const muniSelect  = document.getElementById('select-municipio');
            const restSelect  = document.getElementById('select-restaurante');

            // --- EVENTO 1: Cambia el Departamento ---
            deptoSelect.addEventListener('change', function() {
                const deptoId = this.value;

                // Reiniciamos y bloqueamos los dos selectores hijos
                muniSelect.disabled = true;
                muniSelect.innerHTML = '<option value="">Cargando municipios...</option>';
                restSelect.disabled = true;
                restSelect.innerHTML = '<option value="">— Primero elige municipio —</option>';

                if (deptoId) {
                    fetch(`/api/departamentos/${deptoId}/municipios`)
                        .then(response => response.json())
                        .then(data => {
                            muniSelect.innerHTML = '<option value="">— Seleccionar municipio —</option>';
                            if (data.length > 0) {
                                data.forEach(muni => {
                                    const option = document.createElement('option');
                                    option.value = muni.id;
                                    option.textContent = muni.nombre;
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
                } else {
                    muniSelect.innerHTML = '<option value="">— Primero elige departamento —</option>';
                }
            });

            // --- EVENTO 2: Cambia el Municipio ---
            muniSelect.addEventListener('change', function() {
                const muniId = this.value;

                // Reiniciamos y bloqueamos el selector final de restaurantes
                restSelect.disabled = true;
                restSelect.innerHTML = '<option value="">Cargando establecimientos...</option>';

                if (muniId) {
                    fetch(`/api/municipios/${muniId}/restaurantes`)
                        .then(response => response.json())
                        .then(data => {
                            restSelect.innerHTML = '<option value="">— Selecciona un restaurante —</option>';
                            if (data.length > 0) {
                                data.forEach(rest => {
                                    const option = document.createElement('option');
                                    option.value = rest.id;
                                    option.textContent = rest.nombre;
                                    restSelect.appendChild(option);
                                });
                                restSelect.disabled = false;
                            } else {
                                restSelect.innerHTML = '<option value="">No hay restaurantes en este municipio</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            restSelect.innerHTML = '<option value="">Error de servidor</option>';
                        });
                } else {
                    restSelect.innerHTML = '<option value="">— Primero elige municipio —</option>';
                }
            });
        });
    </script>
</x-app-layout>