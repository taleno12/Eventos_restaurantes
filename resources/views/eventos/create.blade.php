<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Publicar Nuevo Anuncio Gastronómico') }}
        </h2>
    </x-slot>

    <div class="py-12 animate-fade-in bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[1.5rem] border border-gray-100">
                
                {{-- Encabezado Visual --}}
                <div class="bg-gradient-to-r from-zinc-900 to-zinc-800 p-8">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-500/20 p-3 rounded-xl border border-blue-500/30">
                            <i class="fas fa-bullhorn text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-lg">Detalles del Anuncio</h3>
                            <p class="text-gray-400 text-xs">Atrae a más clientes con una descripción llamativa.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Columna Izquierda: Información Básica --}}
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Título del Evento</label>
                                    <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="100"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none"
                                           placeholder="Ej: Festival del Vigorón">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Fecha</label>
                                        <input type="date" name="fecha_evento" value="{{ old('fecha_evento') }}" required
                                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Precio (C$)</label>
                                        <input type="number" name="precio" step="0.01" value="{{ old('precio') }}" required
                                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none"
                                               placeholder="0.00">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    {{-- Selector 1: Departamento --}}
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Departamento</label>
                                        <select id="departamento_id" name="departamento_id" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-gray-50 outline-none">
                                            <option value="" disabled selected>Seleccionar...</option>
                                            @foreach($departamentos as $dep)
                                                <option value="{{ $dep->id }}" @selected(old('departamento_id') == $dep->id)>{{ $dep->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Selector 2: Municipio --}}
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Municipio</label>
                                        <select id="municipio_id" name="municipio_id" required disabled
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-gray-50 outline-none disabled:opacity-50">
                                            <option value="" disabled selected>Primero elige departamento...</option>
                                        </select>
                                    </div>
                                    
                                    {{-- Selector 3: Restaurante --}}
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Restaurante / Local</label>
                                        <select id="restaurante_id" name="restaurante_id" required disabled
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-gray-50 outline-none disabled:opacity-50">
                                            <option value="" disabled selected>Primero elige municipio...</option>
                                        </select>
                                    </div>

                                    {{-- Especialidad dinámica --}}
                                    <div id="wrapper-especialidad" class="hidden transition-all duration-300">
                                        <label class="block text-xs font-black uppercase tracking-widest text-orange-500 mb-2">
                                            <i class="fas fa-utensils mr-1"></i> Especialidad Gastronómica
                                        </label>
                                        <input type="text" id="info-especialidad" readonly
                                               class="w-full px-4 py-3 rounded-xl border border-orange-100 bg-orange-50/40 text-sm font-semibold text-orange-800 select-none cursor-not-allowed outline-none"
                                               placeholder="Especialidad del local seleccionado">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">¿Es un evento destacado?</label>
                                        <select name="is_destacado" id="is_destacado" 
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-gray-50 outline-none">
                                            <option value="0" @selected(old('is_destacado') == '0')>Evento Normal</option>
                                            <option value="1" @selected(old('is_destacado') == '1')>Evento Destacado (Banner Principal)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Columna Derecha: Multimedia --}}
                            <div class="space-y-6">
                                {{-- Imagen Principal --}}
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Imagen Principal</label>
                                    <div class="relative group">
                                        <div class="border-2 border-dashed border-gray-200 rounded-[1.5rem] p-8 text-center hover:border-blue-400 transition-colors bg-gray-50 relative min-h-[180px] flex flex-col justify-center items-center">
                                            <input type="file" name="imagen" id="imagen" accept="image/*" required
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div id="preview-container" class="space-y-2 pointer-events-none">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 group-hover:text-blue-400 transition-colors"></i>
                                                <p class="text-xs text-gray-500">JPG, PNG o WEBP (Máx. 2MB)</p>
                                            </div>
                                            <img id="image-preview" class="hidden mx-auto h-40 w-full object-cover rounded-xl shadow-sm pointer-events-none">
                                        </div>
                                    </div>
                                </div>

                                {{-- Descripción --}}
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Descripción del Evento</label>
                                    <textarea name="descripcion" rows="4" required maxlength="500"
                                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none resize-none"
                                              placeholder="Cuéntanos más sobre el evento...">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════════════════ --}}
                        {{-- SECCIÓN: GALERÍA DE IMÁGENES ADICIONALES               --}}
                        {{-- ═══════════════════════════════════════════════════════ --}}
                        <div class="border-t border-gray-100 pt-8">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="bg-purple-500/10 p-2.5 rounded-xl border border-purple-500/20">
                                    <i class="fas fa-images text-purple-500 text-base"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-700 text-sm">Galería de Imágenes Destacadas</h4>
                                    <p class="text-xs text-gray-400">Agrega fotos adicionales del evento (opcional, máx. 6 imágenes).</p>
                                </div>
                            </div>

                            {{-- Grid de slots de imágenes --}}
                            <div id="galeria-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @for($i = 0; $i < 6; $i++)
                                <div class="relative group galeria-slot">
                                    <label class="block cursor-pointer">
                                        <div class="border-2 border-dashed border-gray-200 rounded-2xl aspect-square flex flex-col items-center justify-center bg-gray-50 hover:border-purple-400 hover:bg-purple-50/30 transition-all overflow-hidden relative">
                                            {{-- Input oculto --}}
                                            <input type="file" name="galeria[]" accept="image/*"
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 galeria-input">
                                            {{-- Placeholder --}}
                                            <div class="galeria-placeholder flex flex-col items-center gap-1 pointer-events-none">
                                                <i class="fas fa-plus text-gray-300 text-xl group-hover:text-purple-400 transition-colors"></i>
                                                <span class="text-xs text-gray-400">Foto {{ $i + 1 }}</span>
                                            </div>
                                            {{-- Preview --}}
                                            <img class="galeria-preview hidden absolute inset-0 w-full h-full object-cover rounded-2xl">
                                        </div>
                                    </label>
                                    {{-- Botón eliminar preview --}}
                                    <button type="button"
                                            class="galeria-clear hidden absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center shadow-md hover:bg-red-600 transition-colors z-20">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-400 mt-3">
                                <i class="fas fa-info-circle mr-1 text-blue-400"></i>
                                Las imágenes de galería se guardarán al publicar el evento.
                            </p>
                        </div>

                        {{-- Barra de Acciones Inferior --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('eventos.index') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors no-underline">
                                <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-xl font-bold shadow-md shadow-blue-200 transition-all hover:scale-[1.01] active:scale-95 text-sm border-0 cursor-pointer">
                                Publicar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Preview imagen principal ──────────────────────────────────────
            const input     = document.getElementById('imagen');
            const preview   = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-container');

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // ── Galería: preview por slot ─────────────────────────────────────
            document.querySelectorAll('.galeria-slot').forEach(slot => {
                const fileInput  = slot.querySelector('.galeria-input');
                const imgPreview = slot.querySelector('.galeria-preview');
                const imgPlaceholder = slot.querySelector('.galeria-placeholder');
                const clearBtn   = slot.querySelector('.galeria-clear');

                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            imgPreview.src = e.target.result;
                            imgPreview.classList.remove('hidden');
                            imgPlaceholder.classList.add('hidden');
                            clearBtn.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });

                clearBtn.addEventListener('click', function() {
                    fileInput.value = '';
                    imgPreview.src  = '';
                    imgPreview.classList.add('hidden');
                    imgPlaceholder.classList.remove('hidden');
                    clearBtn.classList.add('hidden');
                });
            });

            // ── Encadenamiento geográfico ─────────────────────────────────────
            const depSelect  = document.getElementById('departamento_id');
            const muniSelect = document.getElementById('municipio_id');
            const restSelect = document.getElementById('restaurante_id');
            const wrapEspec  = document.getElementById('wrapper-especialidad');
            const inputEspec = document.getElementById('info-especialidad');

            depSelect.addEventListener('change', function() {
                const depId = this.value;
                muniSelect.disabled = true;
                muniSelect.innerHTML = '<option value="">Cargando municipios...</option>';
                restSelect.disabled = true;
                restSelect.innerHTML = '<option value="">Primero elige municipio...</option>';
                wrapEspec.classList.add('hidden');

                if (depId) {
                    fetch(`/api/departamentos/${depId}/municipios`)
                        .then(r => r.json())
                        .then(data => {
                            muniSelect.innerHTML = '<option value="" disabled selected>Seleccionar municipio...</option>';
                            if (data.length > 0) {
                                data.forEach(muni => {
                                    const opt = document.createElement('option');
                                    opt.value = muni.id;
                                    opt.textContent = muni.nombre;
                                    muniSelect.appendChild(opt);
                                });
                                muniSelect.disabled = false;
                            } else {
                                muniSelect.innerHTML = '<option value="">Sin municipios registrados</option>';
                            }
                        });
                }
            });

            muniSelect.addEventListener('change', function() {
                const muniId = this.value;
                restSelect.disabled = true;
                restSelect.innerHTML = '<option value="">Cargando locales...</option>';
                wrapEspec.classList.add('hidden');

                if (muniId) {
                    fetch(`/api/municipios/${muniId}/restaurantes`)
                        .then(r => r.json())
                        .then(data => {
                            restSelect.innerHTML = '<option value="" disabled selected>Seleccionar local...</option>';
                            if (data.length > 0) {
                                data.forEach(rest => {
                                    const opt = document.createElement('option');
                                    opt.value = rest.id;
                                    opt.textContent = rest.nombre;
                                    opt.setAttribute('data-especialidad', rest.especialidad || 'Variada / Comida General');
                                    restSelect.appendChild(opt);
                                });
                                restSelect.disabled = false;
                            } else {
                                restSelect.innerHTML = '<option value="">Sin restaurantes en este municipio</option>';
                            }
                        });
                }
            });

            restSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const especialidad   = selectedOption.getAttribute('data-especialidad');
                if (this.value && especialidad) {
                    inputEspec.value = especialidad;
                    wrapEspec.classList.remove('hidden');
                } else {
                    wrapEspec.classList.add('hidden');
                }
            });
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>