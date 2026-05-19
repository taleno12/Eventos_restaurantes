<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Editar Anuncio Gastronómico') }}
        </h2>
    </x-slot>

    <div class="py-12 animate-fade-in bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[1.5rem] border border-gray-100">
                
                {{-- Encabezado Visual --}}
                <div class="bg-gradient-to-r from-zinc-900 to-zinc-800 p-8">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-500/20 p-3 rounded-xl border border-yellow-500/30">
                            <i class="fas fa-edit text-yellow-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-lg">Modificar Evento: {{ $evento->titulo }}</h3>
                            <p class="text-gray-400 text-xs">Actualiza la información necesaria para tus clientes.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('eventos.update', $evento->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT') {{-- Requerido para actualizar en Laravel --}}
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Columna Izquierda: Información Básica --}}
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Título del Evento</label>
                                    <input type="text" name="titulo" value="{{ old('titulo', $evento->titulo) }}" required maxlength="100"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none"
                                           placeholder="Ej: Festival del Vigorón">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Fecha</label>
                                        <input type="date" name="fecha_evento" value="{{ old('fecha_evento', \Carbon\Carbon::parse($evento->fecha_evento)->format('Y-m-d')) }}" required
                                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Precio (C$)</label>
                                        <input type="number" name="precio" step="0.01" value="{{ old('precio', $evento->precio) }}" required
                                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none"
                                               placeholder="0.00">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Departamento</label>
                                        <select id="departamento_id" name="departamento_id" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-gray-50 outline-none">
                                            <option value="" disabled>Seleccionar...</option>
                                            @foreach($departamentos as $dep)
                                                <option value="{{ $dep->id }}" {{ $evento->departamento_id == $dep->id ? 'selected' : '' }}>
                                                    {{ $dep->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Restaurante / Local</label>
                                        <select id="restaurante_id" name="restaurante_id" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-gray-50 outline-none">
                                            @foreach($restaurantes as $rest)
                                                <option value="{{ $rest->id }}" {{ $evento->restaurante_id == $rest->id ? 'selected' : '' }}>
                                                    {{ $rest->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="is_destacado" class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">¿Es un evento destacado?</label>
                                        <select name="is_destacado" id="is_destacado" 
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-gray-50 outline-none">
                                            <option value="0" {{ !$evento->is_destacado ? 'selected' : '' }}>Evento Normal</option>
                                            <option value="1" {{ $evento->is_destacado ? 'selected' : '' }}>Evento Destacado (Banner Principal)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Columna Derecha: Multimedia --}}
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Imagen del Evento</label>
                                    <div class="relative group">
                                        <div class="border-2 border-dashed border-gray-200 rounded-[1.5rem] p-6 text-center hover:border-blue-400 transition-colors bg-gray-50 relative min-h-[180px] flex flex-col justify-center items-center">
                                            <input type="file" name="imagen" id="imagen" accept="image/*"
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            
                                            {{-- Si ya tiene imagen cargada, la mostramos por defecto --}}
                                            <div id="preview-container" class="{{ $evento->imagen ? 'hidden' : '' }} space-y-2 pointer-events-none">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 group-hover:text-blue-400 transition-colors"></i>
                                                <p class="text-xs text-gray-500">Subir nueva imagen para reemplazar (Máx. 2MB)</p>
                                            </div>
                                            
                                            <img id="image-preview" 
                                                 src="{{ $evento->imagen ? asset('storage/' . $evento->imagen) : '#' }}" 
                                                 class="{{ $evento->imagen ? '' : 'hidden' }} mx-auto h-40 w-full object-cover rounded-xl shadow-sm pointer-events-none">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Descripción del Evento</label>
                                    <textarea name="descripcion" rows="4" required maxlength="500"
                                              class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none resize-none"
                                              placeholder="Cuéntanos más sobre el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Barra de Acciones Inferior --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('eventos.index') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors no-underline">
                                <i class="fas fa-arrow-left mr-2"></i> Cancelar y volver
                            </a>
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-md shadow-yellow-100 transition-all hover:scale-[1.01] active:scale-95 text-sm">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview de Imagen Dinámica
            const input = document.getElementById('imagen');
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-container');

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Carga Dinámica de Restaurantes al cambiar Departamento
            const depSelect = document.getElementById('departamento_id');
            const restSelect = document.getElementById('restaurante_id');

            depSelect.addEventListener('change', function() {
                const depId = this.value;
                restSelect.disabled = true;
                restSelect.innerHTML = '<option value="">Cargando locales...</option>';

                if (depId) {
                    fetch(`/api/departamentos/${depId}/restaurantes`)
                        .then(response => response.json())
                        .then(data => {
                            restSelect.innerHTML = '<option value="" disabled selected>Seleccionar local...</option>';
                            
                            if (data.length > 0) {
                                data.forEach(rest => {
                                    const option = document.createElement('option');
                                    option.value = rest.id;
                                    option.textContent = rest.nombre;
                                    restSelect.appendChild(option);
                                });
                                restSelect.disabled = false;
                            } else {
                                restSelect.innerHTML = '<option value="">Sin restaurantes en este departamento</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            restSelect.innerHTML = '<option value="">Error de carga</option>';
                        });
                }
            });
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>