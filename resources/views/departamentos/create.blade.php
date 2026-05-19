<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('departamentos.index') }}"
               class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors no-underline">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                    {{ __('Nuevo Departamento') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Registra una nueva región geográfica en la plataforma.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('departamentos.store') }}" method="POST">
            @csrf

            {{-- Errores globales de validación --}}
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
                    <p class="font-semibold mb-1">
                        <i class="fas fa-exclamation-circle mr-1"></i> Corrige los siguientes errores:
                    </p>
                    <ul class="list-disc list-inside space-y-0.5 opacity-90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card Principal Unificada --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6 flex items-center gap-2">
                    <i class="fas fa-map-marked-alt text-blue-500"></i> Información Geográfica
                </h3>

                <div class="space-y-6">
                    
                    {{-- Nombre del Departamento --}}
                    <div>
                        <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre del Departamento <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                                   placeholder="Ej: León, Masaya, Madriz..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-gray-50/50 transition-all @error('nombre') border-red-400 @enderror"
                                   required maxlength="100">
                        </div>
                        @error('nombre')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Carga Masiva de Municipios --}}
                    <div class="pt-4 border-t border-gray-100">
                        <label for="municipios_lista" class="block text-sm font-semibold text-gray-700 mb-1">
                            Municipios Iniciales <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">Escribe los municipios divididos por comas para guardarlos juntos en un solo paso.</p>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-4 top-3.5 text-gray-400 pointer-events-none">
                                <i class="fas fa-tags text-sm"></i>
                            </div>
                            <textarea name="municipios_lista" id="municipios_lista" rows="3"
                                      placeholder="Ej: Somoto, Palacagüina, Telpaneca"
                                      class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-gray-50/50 transition-all resize-none">{{ old('municipios_lista') }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Botones de Control Inferior --}}
            <div class="flex items-center justify-between px-2">
                <a href="{{ route('departamentos.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors no-underline">
                    <i class="fas fa-chevron-left mr-1 text-xs"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-7 py-3 rounded-xl transition-all shadow-md shadow-blue-100 text-sm border-0 cursor-pointer">
                    <i class="fas fa-save"></i> Guardar Departamento
                </button>
            </div>
        </form>
    </div>
</x-app-layout>