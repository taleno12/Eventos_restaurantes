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
        <form action="{{ route('admin.empleos.update', $empleo) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

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

                {{-- Restaurante --}}
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

                {{-- Título --}}
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

                {{-- Descripción --}}
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

                {{-- Requisitos --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Requisitos <span class="text-gray-400 font-normal">(opcional)</span>
                    </label>
                    <textarea name="requisitos" rows="3"
                              placeholder="Experiencia requerida, estudios, habilidades..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none">{{ old('requisitos', $empleo->requisitos) }}</textarea>
                </div>

                {{-- Tipo contrato + Salario --}}
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

                {{-- Fecha límite + Estado --}}
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
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <div class="relative">
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" value="1" id="activo"
                                       {{ old('activo', $empleo->activo) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-checked:bg-blue-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-blue-300"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Oferta activa (visible al público)</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Info de creación --}}
            <div class="bg-gray-50 rounded-xl px-5 py-3 text-xs text-gray-400 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                Creada el {{ $empleo->created_at->format('d M Y \a \l\a\s H:i') }}
                · Última actualización: {{ $empleo->updated_at->diffForHumans() }}
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-between">
                <form action="{{ route('admin.empleos.destroy', $empleo) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar esta oferta permanentemente?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 text-red-500 hover:text-red-700 text-sm font-semibold transition-colors">
                        <i class="fas fa-trash"></i> Eliminar oferta
                    </button>
                </form>

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
</x-app-layout>