<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Registrar Nuevo Restaurante') }}
        </h2>
    </x-slot>

    <div class="py-12 animate-fade-in bg-gray-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[1.5rem] border border-gray-100">

                <div class="bg-gradient-to-r from-zinc-900 to-zinc-800 p-8">
                    <div class="flex items-center gap-4">
                        <div class="bg-orange-500/20 p-3 rounded-xl border border-orange-500/30">
                            <i class="fas fa-utensils text-orange-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-lg">Información del Restaurante</h3>
                            <p class="text-gray-400 text-xs">Registra un nuevo establecimiento gastronomico en la plataforma administrativa.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-red-700 text-sm">
                            <p class="font-bold mb-1"><i class="fas fa-exclamation-circle mr-2"></i>Por favor corrige los siguientes campos:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="form-restaurante" action="{{ route('admin.restaurantes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            {{-- COLUMNA IZQUIERDA --}}
                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-zinc-700 border-b border-gray-100 pb-2">
                                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>Datos Generales
                                </h4>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nombre del Restaurante *</label>
                                    <div class="relative">
                                        <i class="fas fa-store absolute left-4 top-3.5 text-gray-300"></i>
                                        <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="100"
                                               placeholder="Nombre del restaurante"
                                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Correo Electrónico *</label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-300"></i>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                               placeholder="correo@restaurante.com"
                                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Especialidad Culinaria *</label>
                                    <div class="relative">
                                        <i class="fas fa-concierge-bell absolute left-4 top-3.5 text-gray-300"></i>
                                        <input type="text" name="especialidad" value="{{ old('especialidad') }}" required
                                               placeholder="Ej: Mariscos, Asados, Comida Típica..."
                                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Departamento *</label>
                                        <select id="departamento_id" name="departamento_id" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all bg-gray-50 outline-none">
                                            <option value="" disabled selected>Seleccionar...</option>
                                            @foreach($departamentos as $dep)
                                                <option value="{{ $dep->id }}" @selected(old('departamento_id') == $dep->id)>{{ $dep->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Municipio *</label>
                                        <select id="municipio_id" name="municipio_id" required data-old-muni="{{ old('municipio_id') }}" disabled
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all bg-gray-50 outline-none disabled:opacity-50">
                                            <option value="" disabled selected>Elige departamento...</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Descripción Corta (Opcional)</label>
                                    <textarea name="descripcion" rows="3" maxlength="500"
                                              placeholder="Breve reseña del ambiente, tipo de cocina..."
                                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none resize-none">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>

                            {{-- COLUMNA DERECHA --}}
                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-zinc-700 border-b border-gray-100 pb-2">
                                    <i class="fas fa-share-alt text-gray-400 mr-2"></i>Contacto y Multimedia
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">WhatsApp</label>
                                        <div class="relative">
                                            <i class="fab fa-whatsapp absolute left-3 top-3 text-emerald-500"></i>
                                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                                                   class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 focus:border-orange-500 outline-none" placeholder="+505 8888-8888">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Instagram</label>
                                        <div class="relative">
                                            <i class="fab fa-instagram absolute left-3 top-3 text-pink-500"></i>
                                            <input type="text" name="instagram" value="{{ old('instagram') }}"
                                                   class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 focus:border-orange-500 outline-none" placeholder="https://instagram.com/usuario">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Facebook</label>
                                        <div class="relative">
                                            <i class="fab fa-facebook absolute left-3 top-3 text-blue-600"></i>
                                            <input type="text" name="facebook" value="{{ old('facebook') }}"
                                                   class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 focus:border-orange-500 outline-none" placeholder="https://facebook.com/pagina">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">TikTok</label>
                                        <div class="relative">
                                            <i class="fab fa-tiktok absolute left-3 top-3 text-black"></i>
                                            <input type="text" name="tiktok" value="{{ old('tiktok') }}"
                                                   class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 focus:border-orange-500 outline-none" placeholder="https://tiktok.com/@usuario">
                                        </div>
                                    </div>
                                </div>

                                {{-- Foto de Portada --}}
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Foto de Portada *</label>
                                    <label for="imagen" class="block cursor-pointer">
                                        <div class="border-2 border-dashed border-gray-200 rounded-2xl hover:border-orange-400 transition-colors bg-gray-50 min-h-[160px] flex flex-col justify-center items-center overflow-hidden relative">
                                            <input type="file" name="imagen_principal" id="imagen" accept="image/*" required
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div id="preview-container" class="flex flex-col items-center gap-2 pointer-events-none">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-300"></i>
                                                <p class="text-xs text-gray-500">Haz clic para seleccionar</p>
                                                <p class="text-[10px] text-gray-400">JPG, PNG o WEBP</p>
                                            </div>
                                            <img id="image-preview" src="" alt=""
                                                 class="hidden absolute inset-0 w-full h-full object-cover pointer-events-none">
                                        </div>
                                    </label>
                                </div>
                            </div>

                        </div>

                        {{-- SECCIÓN UBICACIÓN Y MAPA --}}
<div class="pt-4 space-y-4">
    <h4 class="text-sm font-bold text-zinc-700 border-b border-gray-100 pb-2">
        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>Ubicación del Restaurante
    </h4>

    {{-- Buscador de dirección --}}
    <div>
        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">
            Buscar Dirección
        </label>
        <div class="flex gap-2">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-300"></i>
                <input type="text" id="direccion-buscar"
                       placeholder="Ej: Restaurante La Terraza, Masaya, Nicaragua"
                       class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none">
            </div>
            <button type="button" id="btn-buscar-mapa"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-3 rounded-xl font-bold text-sm transition-colors border-0 cursor-pointer whitespace-nowrap">
                <i class="fas fa-search mr-1"></i> Buscar
            </button>
        </div>
        <p class="text-xs text-gray-400 mt-1">
            <i class="fas fa-info-circle mr-1"></i>
            Si no encuentra la dirección exacta, haz clic directamente en el mapa para colocar el pin.
        </p>
    </div>

    {{-- Campo dirección guardada --}}
    <div>
        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">
            Dirección Exacta (se guarda automáticamente al buscar o hacer clic en el mapa)
        </label>
        <div class="relative">
            <i class="fas fa-map-pin absolute left-4 top-3.5 text-orange-400"></i>
            <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}"
                   placeholder="La dirección aparecerá aquí..."
                   class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none bg-gray-50">
        </div>
    </div>

    {{-- Coordenadas ocultas --}}
    <input type="hidden" name="latitud"  id="latitud"  value="{{ old('latitud') }}">
    <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud') }}">

    {{-- Mapa Leaflet --}}
    <div id="mapa-restaurante" class="w-full rounded-2xl overflow-hidden border border-gray-200 shadow-sm" style="height: 350px;"></div>

    <p id="mapa-coords-info" class="text-xs text-gray-400 hidden">
        <i class="fas fa-crosshairs text-orange-400 mr-1"></i>
        Coordenadas: <span id="coords-display"></span>
    </p>
</div>

                        {{-- SECCIÓN GALERÍA --}}
                        <div class="pt-4 space-y-4">
                            <h4 class="text-sm font-bold text-zinc-700 border-b border-gray-100 pb-2">
                                <i class="fas fa-images text-gray-400 mr-2"></i>Fotos del Álbum (Opcional, Máx. 4 imágenes)
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @for($i = 0; $i < 4; $i++)
                                <label for="galeria_{{ $i }}" class="block cursor-pointer">
                                    <div class="border-2 border-dashed border-gray-200 rounded-2xl hover:border-orange-400 transition-colors bg-gray-50 aspect-square flex flex-col justify-center items-center overflow-hidden relative">
                                        <input type="file" name="galeria[]" id="galeria_{{ $i }}" accept="image/*"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div id="placeholder-{{ $i }}" class="flex flex-col items-center gap-2 pointer-events-none">
                                            <i class="fas fa-plus text-gray-300 text-xl"></i>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase">Foto {{ $i + 1 }}</span>
                                        </div>
                                        <img id="preview-galeria-{{ $i }}" src="" alt=""
                                             class="hidden absolute inset-0 w-full h-full object-cover pointer-events-none">
                                    </div>
                                </label>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-400"><i class="fas fa-info-circle mr-1"></i>Haz clic en cada casilla para agregar una foto individualmente.</p>
                        </div>

                        {{-- Acciones --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('admin.restaurantes.index') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors no-underline">
                                <i class="fas fa-arrow-left mr-2"></i> Volver al panel admin
                            </a>
                            <button type="submit" id="btn-submit-restaurante"
                                    class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3.5 rounded-xl font-bold shadow-md shadow-orange-200 transition-all text-sm border-0 cursor-pointer">
                                Registrar Restaurante
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Preview Portada ──
        document.getElementById('imagen').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-container');
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });

        // ── Preview Galería individual ──
        for (let i = 0; i < 4; i++) {
            document.getElementById('galeria_' + i).addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                const preview = document.getElementById('preview-galeria-' + i);
                const placeholder = document.getElementById('placeholder-' + i);
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });
        }

        // ── Departamentos → Municipios ──
        document.addEventListener('DOMContentLoaded', function() {
            const depSelect  = document.getElementById('departamento_id');
            const muniSelect = document.getElementById('municipio_id');

            function cargarMunicipios(depId, muniSeleccionado = null) {
                if (!depId) return;
                muniSelect.disabled = true;
                muniSelect.innerHTML = '<option value="">Cargando...</option>';
                fetch(`/api/departamentos/${depId}/municipios`)
                    .then(r => r.json())
                    .then(data => {
                        muniSelect.innerHTML = '<option value="" disabled selected>Seleccionar municipio...</option>';
                        data.forEach(muni => {
                            const opt = document.createElement('option');
                            opt.value = muni.id;
                            opt.textContent = muni.nombre;
                            if (muniSeleccionado && muni.id == muniSeleccionado) opt.selected = true;
                            muniSelect.appendChild(opt);
                        });
                        muniSelect.disabled = data.length === 0;
                    })
                    .catch(e => console.error(e));
            }

            depSelect.addEventListener('change', function() { cargarMunicipios(this.value); });
            if (depSelect.value) cargarMunicipios(depSelect.value, muniSelect.dataset.oldMuni);

            // ── Submit ──
            document.getElementById('form-restaurante').addEventListener('submit', function() {
                muniSelect.disabled = false;
                const btn = document.getElementById('btn-submit-restaurante');
                if (btn) {
                    btn.disabled = true;
                    btn.className = "bg-zinc-400 text-white px-8 py-3.5 rounded-xl font-bold text-sm cursor-not-allowed border-0";
                    btn.innerText = 'Guardando...';
                }
            });
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    {{-- Leaflet CSS y JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Coordenadas por defecto: centro de Nicaragua
    const defaultLat = 12.8654;
    const defaultLng = -85.2072;

    const savedLat = document.getElementById('latitud').value;
    const savedLng = document.getElementById('longitud').value;

    const initLat = savedLat ? parseFloat(savedLat) : defaultLat;
    const initLng = savedLng ? parseFloat(savedLng) : defaultLng;

    // Inicializar mapa
    const mapa = L.map('mapa-restaurante').setView([initLat, initLng], savedLat ? 16 : 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);

    // Marker personalizado naranja
    const iconoNaranja = L.divIcon({
        html: '<div style="background:#ea580c;width:20px;height:20px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 20],
        className: ''
    });

    let marker = null;

    // Si hay coordenadas guardadas, colocar marker
    if (savedLat && savedLng) {
        marker = L.marker([initLat, initLng], { icon: iconoNaranja, draggable: true }).addTo(mapa);
        actualizarInfo(initLat, initLng);
        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            actualizarCoordenadas(pos.lat, pos.lng);
            geocodeInverso(pos.lat, pos.lng);
        });
    }

    // Clic en el mapa → colocar/mover pin
    mapa.on('click', function (e) {
        const { lat, lng } = e.latlng;
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { icon: iconoNaranja, draggable: true }).addTo(mapa);
            marker.on('dragend', function () {
                const pos = marker.getLatLng();
                actualizarCoordenadas(pos.lat, pos.lng);
                geocodeInverso(pos.lat, pos.lng);
            });
        }
        actualizarCoordenadas(lat, lng);
        geocodeInverso(lat, lng);
    });

    // Botón buscar
    document.getElementById('btn-buscar-mapa').addEventListener('click', buscarDireccion);
    document.getElementById('direccion-buscar').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); buscarDireccion(); }
    });

    function buscarDireccion() {
        const query = document.getElementById('direccion-buscar').value.trim();
        if (!query) return;

        const btn = document.getElementById('btn-buscar-mapa');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Buscando...';
        btn.disabled = true;

        // Si no menciona Nicaragua, agregarlo para mejores resultados
        const queryFinal = query.toLowerCase().includes('nicaragua') ? query : query + ', Nicaragua';

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(queryFinal)}&limit=1&countrycodes=ni`, {
            headers: { 'Accept-Language': 'es' }
        })
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = '<i class="fas fa-search mr-1"></i> Buscar';
            btn.disabled = false;

            if (data.length === 0) {
                alert('No se encontró esa dirección. Intenta ser más específico o haz clic en el mapa.');
                return;
            }

            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            const nombre = data[0].display_name;

            mapa.setView([lat, lng], 17);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { icon: iconoNaranja, draggable: true }).addTo(mapa);
                marker.on('dragend', function () {
                    const pos = marker.getLatLng();
                    actualizarCoordenadas(pos.lat, pos.lng);
                    geocodeInverso(pos.lat, pos.lng);
                });
            }

            actualizarCoordenadas(lat, lng);
            document.getElementById('direccion').value = nombre;
        })
        .catch(() => {
            btn.innerHTML = '<i class="fas fa-search mr-1"></i> Buscar';
            btn.disabled = false;
            alert('Error al buscar. Verifica tu conexión e intenta de nuevo.');
        });
    }

    function geocodeInverso(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18`, {
            headers: { 'Accept-Language': 'es' }
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.display_name) {
                document.getElementById('direccion').value = data.display_name;
            }
        })
        .catch(() => {});
    }

    function actualizarCoordenadas(lat, lng) {
        document.getElementById('latitud').value  = lat.toFixed(7);
        document.getElementById('longitud').value = lng.toFixed(7);
        actualizarInfo(lat, lng);
    }

    function actualizarInfo(lat, lng) {
        document.getElementById('mapa-coords-info').classList.remove('hidden');
        document.getElementById('coords-display').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }
});
</script>

</x-app-layout>