<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.restaurantes.index') }}"
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
        {{-- ✅ enctype necesario para subir archivos --}}
        <form action="{{ route('admin.restaurantes.update', $restaurante->id) }}" method="POST" enctype="multipart/form-data">
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

                    {{-- Separador --}}
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

                    {{-- Separador --}}
                    <div class="md:col-span-2 my-2 border-t border-gray-100"></div>

                    {{-- Sección 3: Redes y Canales Digitales --}}
                    <div class="md:col-span-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2 mb-1">
                            <i class="fas fa-share-alt text-orange-400"></i> Contacto Directo y Redes Sociales
                        </h4>
                    </div>

                    {{-- WhatsApp --}}
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

                    {{-- Instagram --}}
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

                    {{-- TikTok --}}
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

                    {{-- Facebook --}}
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

                    {{-- Separador --}}
                    <div class="md:col-span-2 my-2 border-t border-gray-100"></div>

                    {{-- ══════════════════════════════════════════════════════════ --}}
                    {{-- Sección 4: Foto de Portada                               --}}
                    {{-- El controlador guarda en $restaurante->foto_portada       --}}
                    {{-- ══════════════════════════════════════════════════════════ --}}
                    <div class="md:col-span-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2 mb-4">
                            <i class="fas fa-image text-orange-400"></i> Foto de Portada
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">

                            {{-- Imagen actual guardada en foto_portada --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-2">Imagen actual:</p>
                                @if($restaurante->foto_portada)
                                    <img id="img-portada-actual"
                                         src="{{ asset('storage/' . $restaurante->foto_portada) }}"
                                         alt="Portada actual"
                                         class="w-full h-44 object-cover rounded-xl border border-gray-200 shadow-sm">
                                @else
                                    <div id="img-portada-actual"
                                         class="w-full h-44 flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 text-gray-400 gap-2">
                                        <i class="fas fa-image text-2xl"></i>
                                        <span class="text-xs">Sin imagen de portada</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Input para reemplazar imagen --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-2">
                                    Reemplazar imagen <span class="font-normal text-gray-400">(opcional)</span>
                                </p>
                                <div class="relative group border-2 border-dashed border-gray-200 rounded-xl hover:border-orange-400 transition-colors bg-gray-50 min-h-[11rem] flex flex-col justify-center items-center overflow-hidden">
                                    <input type="file" name="imagen_principal" id="input-portada" accept="image/*"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div id="placeholder-portada" class="pointer-events-none text-center space-y-2 px-4">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 group-hover:text-orange-400 transition-colors"></i>
                                        <p class="text-xs text-gray-400">Haz clic para seleccionar</p>
                                        <p class="text-xs text-gray-300">JPG, PNG o WEBP</p>
                                    </div>
                                    <img id="nueva-preview-portada"
                                         class="hidden absolute inset-0 w-full h-full object-cover rounded-xl pointer-events-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Separador --}}
                    <div class="md:col-span-2 my-2 border-t border-gray-100"></div>

                    {{-- ══════════════════════════════════════════════════════════ --}}
                    {{-- Sección 5: Galería de Fotos                              --}}
                    {{-- Las fotos viven en RestauranteFoto (relación imagenes)    --}}
                    {{-- Columna: ruta_foto                                        --}}
                    {{-- ══════════════════════════════════════════════════════════ --}}
                    <div class="md:col-span-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2 mb-1">
                            <i class="fas fa-images text-orange-400"></i> Fotos del Álbum
                            <span class="font-normal normal-case text-gray-300 text-[11px]">(Máx. 4 — las nuevas se agregan a las existentes)</span>
                        </h4>

                        {{-- Fotos ya guardadas en la relación "imagenes" --}}
                        @if($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                            <p class="text-xs font-semibold text-gray-500 mt-3 mb-2">Imágenes actuales del álbum:</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                                @foreach($restaurante->imagenes as $foto)
                                    <div class="relative h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                        {{-- ✅ Usa ruta_foto según el modelo RestauranteFoto --}}
                                        <img src="{{ asset('storage/' . $foto->ruta_foto) }}"
                                             alt="Foto álbum"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 mt-3 mb-3 flex items-center gap-1.5">
                                <i class="fas fa-info-circle text-gray-300"></i>
                                Este restaurante aún no tiene fotos en el álbum.
                            </p>
                        @endif

                        {{-- Input para agregar nuevas fotos --}}
                        <div class="relative group border-2 border-dashed border-gray-200 rounded-xl hover:border-orange-400 transition-colors bg-gray-50 min-h-[100px] flex flex-col justify-center items-center overflow-hidden">
                            <input type="file" name="galeria[]" id="input-galeria" accept="image/*" multiple
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div id="placeholder-galeria" class="pointer-events-none text-center space-y-2 px-4 py-5">
                                <i class="fas fa-images text-3xl text-gray-300 group-hover:text-orange-400 transition-colors"></i>
                                <p class="text-xs text-gray-400">Haz clic para agregar fotos al álbum</p>
                                <p class="text-xs text-gray-300">Hasta 4 imágenes — JPG, PNG o WEBP</p>
                            </div>
                        </div>

                        {{-- Preview de las nuevas fotos seleccionadas --}}
                        <div id="galeria-preview-container" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3 hidden"></div>
                    </div>

                    {{-- Separador --}}
                    <div class="md:col-span-2 my-2 border-t border-gray-100"></div>

                    {{-- Descripción --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción Comercial <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <textarea name="descripcion" rows="4"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-gray-50/50 resize-none"
                                  placeholder="Escribe detalles breves sobre el menú, ambiente o historia del restaurante...">{{ old('descripcion', $restaurante->descripcion) }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Botones de Control Inferior --}}
            <div class="flex items-center justify-between px-2">
                <a href="{{ route('admin.restaurantes.index') }}"
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
        document.addEventListener('DOMContentLoaded', function () {

            // ─── Departamento / Municipio ────────────────────────────────────
            const deptoSelect = document.getElementById('select-departamento');
            const muniSelect  = document.getElementById('select-municipio');

            function cargarMunicipios(deptoId, municipioSeleccionado = null) {
                muniSelect.disabled = true;
                muniSelect.innerHTML = '<option value="">Cargando municipios...</option>';

                fetch(`/api/departamentos/${deptoId}/municipios`)
                    .then(r => r.json())
                    .then(data => {
                        muniSelect.innerHTML = '<option value="">— Seleccionar municipio —</option>';
                        if (data.length > 0) {
                            data.forEach(muni => {
                                const opt = document.createElement('option');
                                opt.value = muni.id;
                                opt.textContent = muni.nombre;
                                if (municipioSeleccionado && muni.id == municipioSeleccionado) {
                                    opt.selected = true;
                                }
                                muniSelect.appendChild(opt);
                            });
                            muniSelect.disabled = false;
                        } else {
                            muniSelect.innerHTML = '<option value="">Sin municipios registrados</option>';
                        }
                    })
                    .catch(() => {
                        muniSelect.innerHTML = '<option value="">Error de servidor</option>';
                    });
            }

            deptoSelect.addEventListener('change', function () {
                if (this.value) {
                    cargarMunicipios(this.value);
                } else {
                    muniSelect.innerHTML = '<option value="">— Primero elige departamento —</option>';
                    muniSelect.disabled = true;
                }
            });

            const deptoInicial = deptoSelect.value;
            const muniInicial  = "{{ old('municipio_id', $restaurante->municipio_id) }}";
            if (deptoInicial) cargarMunicipios(deptoInicial, muniInicial);


            // ─── Preview Foto de Portada ─────────────────────────────────────
            const inputPortada        = document.getElementById('input-portada');
            const nuevaPreviewPortada = document.getElementById('nueva-preview-portada');
            const placeholderPortada  = document.getElementById('placeholder-portada');

            inputPortada.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    // Actualiza visualmente la imagen "actual" al instante
                    const actual = document.getElementById('img-portada-actual');
                    if (actual.tagName === 'IMG') {
                        actual.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.id = 'img-portada-actual';
                        img.src = e.target.result;
                        img.alt = 'Nueva portada';
                        img.className = 'w-full h-44 object-cover rounded-xl border border-gray-200 shadow-sm';
                        actual.replaceWith(img);
                    }
                    nuevaPreviewPortada.src = e.target.result;
                    nuevaPreviewPortada.classList.remove('hidden');
                    placeholderPortada.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });


            // ─── Preview Galería ─────────────────────────────────────────────
            const inputGaleria   = document.getElementById('input-galeria');
            const galeriaPreview = document.getElementById('galeria-preview-container');
            const placeholderGal = document.getElementById('placeholder-galeria');

            inputGaleria.addEventListener('change', function () {
                galeriaPreview.innerHTML = '';
                const files = Array.from(this.files).slice(0, 4);

                if (files.length > 0) {
                    galeriaPreview.classList.remove('hidden');
                    placeholderGal.classList.add('opacity-40');

                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = e => {
                            const div = document.createElement('div');
                            div.className = 'relative h-24 rounded-xl overflow-hidden border-2 border-orange-300 shadow-sm';

                            const badge = document.createElement('span');
                            badge.className = 'absolute top-1 right-1 bg-orange-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full z-10';
                            badge.textContent = 'NUEVA';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-full h-full object-cover';

                            div.appendChild(badge);
                            div.appendChild(img);
                            galeriaPreview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    galeriaPreview.classList.add('hidden');
                    placeholderGal.classList.remove('opacity-40');
                }
            });

        });
    </script>
</x-app-layout>