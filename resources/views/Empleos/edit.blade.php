<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.empleos.index') }}"
               class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors text-gray-500">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-pen text-blue-500"></i>
                    Editar Oferta
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Modificando: <span class="font-semibold text-gray-700">{{ $empleo->titulo }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">

        {{-- ✅ Form eliminar FUERA del form principal para evitar forms anidados --}}
        <form id="form-eliminar"
              action="{{ route('admin.empleos.destroy', $empleo) }}"
              method="POST"
              onsubmit="return confirm('¿Eliminar esta oferta permanentemente?')"
              style="display: none;">
            @csrf @method('DELETE')
        </form>

        <form action="{{ route('admin.empleos.update', $empleo) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <input type="hidden" name="departamento_id" value="{{ $empleo->departamento_id }}">
            <input type="hidden" name="municipio_id" value="{{ $empleo->municipio_id }}">

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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 pb-3">
                    Información de la Vacante
                </h3>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Restaurante <span class="text-red-500">*</span>
                    </label>
                    <select name="restaurante_id"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('restaurante_id') border-red-400 @enderror">
                        <option value="">— Selecciona un restaurante —</option>
                        @foreach($restaurantes as $r)
                            <option value="{{ $r->id }}"
                                {{ old('restaurante_id', $empleo->restaurante_id) == $r->id ? 'selected' : '' }}>
                                {{ $r->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('restaurante_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Título del puesto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="titulo"
                           value="{{ old('titulo', $empleo->titulo) }}"
                           placeholder="Ej: Mesero, Chef de cocina, Cajero..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('titulo') border-red-400 @enderror">
                    @error('titulo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Descripción del puesto <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descripcion" rows="4"
                              placeholder="Describe las responsabilidades..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none @error('descripcion') border-red-400 @enderror">{{ old('descripcion', $empleo->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Requisitos <span class="text-gray-400 font-normal">(opcional)</span>
                    </label>
                    <textarea name="requisitos" rows="3"
                              placeholder="Experiencia requerida, estudios, habilidades..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none">{{ old('requisitos', $empleo->requisitos) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipo de contrato</label>
                        <select name="tipo_contrato"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                            <option value="">— Seleccionar —</option>
                            @foreach(['Tiempo completo', 'Medio tiempo', 'Por horas', 'Temporal', 'Fines de semana'] as $tipo)
                                <option value="{{ $tipo }}"
                                    {{ old('tipo_contrato', $empleo->tipo_contrato) == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Salario mensual (C$) <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="number" name="salario"
                               value="{{ old('salario', $empleo->salario) }}"
                               placeholder="Dejar vacío = A convenir"
                               min="0" step="0.01"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('salario') border-red-400 @enderror">
                        @error('salario')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Fecha límite de aplicación <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="date" name="fecha_limite"
                               value="{{ old('fecha_limite', $empleo->fecha_limite?->format('Y-m-d')) }}"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('fecha_limite') border-red-400 @enderror">
                        @error('fecha_limite')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- TOGGLE MEJORADO -->
                    <div style="display: flex; align-items: flex-end; padding-bottom: 4px;">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1" id="activo"
                               @if(old('activo', $empleo->activo)) checked @endif
                               style="display: none;"
                               onchange="actualizarEstadoToggle(this)">

                        <label for="activo" id="toggle-label"
                               style="display: inline-flex; align-items: center; gap: 12px; cursor: pointer; user-select: none; margin: 0; padding: 10px 16px; border-radius: 12px; border: 2px solid; transition: all 0.2s ease;">
                            <div id="toggle-track"
                                 style="position: relative; width: 48px; height: 26px; border-radius: 999px; transition: background 0.2s ease; flex-shrink: 0;">
                                <div id="toggle-thumb"
                                     style="position: absolute; top: 3px; width: 20px; height: 20px; background: white; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.3); transition: left 0.2s ease;"></div>
                            </div>
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

            <div class="bg-gray-50 rounded-xl px-5 py-3 text-xs text-gray-400 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                Creada el {{ $empleo->created_at->format('d M Y \a \l\a\s H:i') }}
                · Última actualización: {{ $empleo->updated_at->diffForHumans() }}
            </div>

            {{-- ✅ Botones — sin form anidado --}}
            <div class="flex items-center justify-between">
                <button type="button"
                        onclick="document.getElementById('form-eliminar').submit()"
                        class="inline-flex items-center gap-2 text-red-500 hover:text-red-700 text-sm font-semibold transition-colors">
                    <i class="fas fa-trash"></i> Eliminar oferta
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.empleos.index') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function actualizarEstadoToggle(checkbox) {
            const label    = document.getElementById('toggle-label');
            const track    = document.getElementById('toggle-track');
            const thumb    = document.getElementById('toggle-thumb');
            const icon     = document.getElementById('toggle-icon');
            const texto    = document.getElementById('toggle-texto');
            const subtexto = document.getElementById('toggle-subtexto');

            if (checkbox.checked) {
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
            actualizarEstadoToggle(document.getElementById('activo'));
        });
    </script>
</x-app-layout>