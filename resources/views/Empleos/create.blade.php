<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.empleos.index') }}"
               class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors text-gray-500"
               style="text-decoration: none;">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3 m-0">
                    <i class="fas fa-plus-circle text-blue-500"></i>
                    Nueva Oferta de Empleo
                </h2>
                <p class="text-sm text-gray-500 mt-0.5 mb-0">Completa los campos para publicar una vacante.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto" style="padding: 20px 0;">
        <form action="{{ route('admin.empleos.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 24px;">
            @csrf

            {{-- Errores globales --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
                    <p class="font-semibold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside space-y-0.5" style="margin: 0; padding-left: 5px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card principal --}}
            <div class="bg-white rounded-2xl p-6" style="border: 1px solid #f0f0f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 20px;">

                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 pb-3" style="margin: 0; font-size: 0.75rem; letter-spacing: 1px;">
                    Información de la Vacante
                </h3>

                {{-- PASO 1: Departamento --}}
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="block text-sm font-semibold text-gray-700">
                        Departamento de la Vacante <span class="text-red-500">*</span>
                    </label>
                    <select id="select-departamento" name="departamento_id"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('departamento_id') border-red-400 @enderror" required>
                        <option value="">— Seleccionar departamento —</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" {{ old('departamento_id') == $depto->id ? 'selected' : '' }}>
                                {{ $depto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento_id')
                        <p class="text-red-500 text-xs mt-1" style="margin: 0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Grid de Municipio y Restaurante --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                    {{-- PASO 2: Municipio --}}
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="block text-sm font-semibold text-gray-700">
                            Municipio de la Vacante <span class="text-red-500">*</span>
                        </label>
                        <select id="select-municipio" name="municipio_id" disabled
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60 @error('municipio_id') border-red-400 @enderror" required>
                            <option value="">— Primero elige departamento —</option>
                        </select>
                        @error('municipio_id')
                            <p class="text-red-500 text-xs mt-1" style="margin: 0;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASO 3: Restaurante --}}
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="block text-sm font-semibold text-gray-700">
                            Restaurante <span class="text-red-500">*</span>
                        </label>
                        <select id="select-restaurante" name="restaurante_id" disabled
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60 @error('restaurante_id') border-red-400 @enderror" required>
                            <option value="">— Primero elige municipio —</option>
                        </select>
                        @error('restaurante_id')
                            <p class="text-red-500 text-xs mt-1" style="margin: 0;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Título del puesto --}}
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="block text-sm font-semibold text-gray-700">
                        Título del puesto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}"
                           placeholder="Ej: Mesero, Chef de cocina, Cajero..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('titulo') border-red-400 @enderror" required>
                    @error('titulo')
                        <p class="text-red-500 text-xs mt-1" style="margin: 0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="block text-sm font-semibold text-gray-700">
                        Descripción del puesto <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descripcion" rows="4"
                              placeholder="Describe las responsabilidades y funciones del puesto..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none @error('descripcion') border-red-400 @enderror" required>{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1" style="margin: 0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Requisitos --}}
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="block text-sm font-semibold text-gray-700">
                        Requisitos <span class="text-gray-400 font-normal">(opcional)</span>
                    </label>
                    <textarea name="requisitos" rows="3"
                              placeholder="Experiencia requerida, estudios, habilidades..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none">{{ old('requisitos') }}</textarea>
                </div>

                {{-- Tipo contrato + Salario --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="block text-sm font-semibold text-gray-700">Tipo de contrato</label>
                        <select name="tipo_contrato"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">— Seleccionar —</option>
                            @foreach(['Tiempo completo', 'Medio tiempo', 'Por horas', 'Temporal', 'Fines de semana'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_contrato') == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="block text-sm font-semibold text-gray-700">
                            Salario mensual (C$) <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="number" name="salario" value="{{ old('salario') }}"
                               placeholder="Ej: 8000  (dejar vacío = En entrevista)"
                               min="0" step="0.01"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('salario') border-red-400 @enderror">
                        @error('salario')
                            <p class="text-red-500 text-xs mt-1" style="margin: 0;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Fecha límite + Estado --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="block text-sm font-semibold text-gray-700">
                            Fecha límite de aplicación <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="date" name="fecha_limite" value="{{ old('fecha_limite') }}"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('fecha_limite') border-red-400 @enderror">
                        @error('fecha_limite')
                            <p class="text-red-500 text-xs mt-1" style="margin: 0;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ✅ TOGGLE MEJORADO --}}
                    <div style="display: flex; align-items: center; padding-top: 24px;">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1" id="activo"
                               {{ old('activo', '1') ? 'checked' : '' }}
                               style="display: none;"
                               onchange="actualizarEstadoToggle(this)">

                        <label for="activo" id="toggle-label"
                               style="display: inline-flex; align-items: center; gap: 12px; cursor: pointer; user-select: none; margin: 0; padding: 10px 16px; border-radius: 12px; border: 2px solid; transition: all 0.2s ease;">
                            {{-- Track del toggle --}}
                            <div id="toggle-track"
                                 style="position: relative; width: 48px; height: 26px; border-radius: 999px; transition: background 0.2s ease; flex-shrink: 0;">
                                <div id="toggle-thumb"
                                     style="position: absolute; top: 3px; width: 20px; height: 20px; background: white; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.3); transition: left 0.2s ease;"></div>
                            </div>
                            {{-- Ícono + Texto --}}
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i id="toggle-icon" class="fas" style="font-size: 15px;"></i>
                                <div>
                                    <div id="toggle-texto" style="font-size: 0.875rem; font-weight: 700; line-height: 1.2;"></div>
                                    <div id="toggle-subtexto" style="font-size: 0.72rem; font-weight: 400; opacity: 0.75; line-height: 1.2;"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            {{-- Botones --}}
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 8px;">
                <a href="{{ route('admin.empleos.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors"
                   style="text-decoration: none;">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow border-0 cursor-pointer">
                    <i class="fas fa-paper-plane"></i> Publicar Oferta
                </button>
            </div>
        </form>
    </div>

    <script>
        // ─── Función del Toggle Visual ────────────────────────────────────────
        function actualizarEstadoToggle(checkbox) {
            const label    = document.getElementById('toggle-label');
            const track    = document.getElementById('toggle-track');
            const thumb    = document.getElementById('toggle-thumb');
            const icon     = document.getElementById('toggle-icon');
            const texto    = document.getElementById('toggle-texto');
            const subtexto = document.getElementById('toggle-subtexto');

            if (checkbox.checked) {
                // ── ACTIVO ──
                label.style.borderColor     = '#2563eb';
                label.style.backgroundColor = '#eff6ff';
                track.style.backgroundColor = '#2563eb';
                thumb.style.left            = '25px';
                icon.className              = 'fas fa-circle-check';
                icon.style.color            = '#2563eb';
                texto.textContent           = 'Oferta Activa';
                texto.style.color           = '#1d4ed8';
                subtexto.textContent        = 'Visible para los postulantes';
                subtexto.style.color        = '#3b82f6';
            } else {
                // ── INACTIVO ──
                label.style.borderColor     = '#d1d5db';
                label.style.backgroundColor = '#f9fafb';
                track.style.backgroundColor = '#9ca3af';
                thumb.style.left            = '3px';
                icon.className              = 'fas fa-circle-xmark';
                icon.style.color            = '#6b7280';
                texto.textContent           = 'Oferta Inactiva';
                texto.style.color           = '#374151';
                subtexto.textContent        = 'No visible para postulantes';
                subtexto.style.color        = '#9ca3af';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {

            // Inicializar el toggle con su estado correcto al cargar la página
            const checkbox = document.getElementById('activo');
            actualizarEstadoToggle(checkbox);

            // ─── Encadenamiento Departamento → Municipio → Restaurante ────────
            const deptoSelect = document.getElementById('select-departamento');
            const muniSelect  = document.getElementById('select-municipio');
            const restSelect  = document.getElementById('select-restaurante');

            // --- EVENTO 1: Cambia el Departamento ---
            deptoSelect.addEventListener('change', function () {
                const deptoId = this.value;

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
            muniSelect.addEventListener('change', function () {
                const muniId = this.value;

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