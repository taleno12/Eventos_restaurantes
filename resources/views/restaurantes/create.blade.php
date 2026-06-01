<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Restaurante') }}
        </h2>
    </x-slot>

    <div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

        {{-- ── Encabezado ── --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.restaurantes.index') }}"
               class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
               style="width: 38px; height: 38px;">
                <i class="bi bi-arrow-left text-secondary"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold text-dark">
                    <i class="bi bi-plus-circle text-warning me-2"></i> Registrar Nuevo Restaurante
                </h1>
                <p class="text-muted small mb-0">Registra un nuevo establecimiento gastronómico en la plataforma administrativa.</p>
            </div>
        </div>

        {{-- Errores globales --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-circle-fill fs-5 mt-0.5"></i>
                    <div>
                        <p class="fw-semibold mb-1">Por favor corrige los siguientes campos:</p>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form id="form-restaurante" action="{{ route('admin.restaurantes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ══════════════════════════════════════════ --}}
            {{-- CARD 1: Datos Generales                   --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-info-circle text-warning"></i> Datos Generales
                    </h6>

                    <div class="row g-4">

                        {{-- Nombre --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Nombre del Restaurante <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="100"
                                       placeholder="Nombre del restaurante"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Correo Electrónico <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="correo@restaurante.com"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                        {{-- Especialidad --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Especialidad Culinaria <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-egg-fried"></i></span>
                                <input type="text" name="especialidad" value="{{ old('especialidad') }}" required
                                       placeholder="Ej: Mariscos, Asados, Comida Típica..."
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                        {{-- Separador --}}
                        <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                        {{-- Ubicación Geográfica --}}
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                <i class="bi bi-map text-warning"></i> Ubicación Geográfica
                            </h6>
                        </div>

                        {{-- Departamento --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Departamento <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                                <select id="departamento_id" name="departamento_id" required
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled selected>Seleccionar...</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id }}" @selected(old('departamento_id') == $dep->id)>{{ $dep->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Municipio --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Municipio <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                                <select id="municipio_id" name="municipio_id" required disabled
                                        data-old-muni="{{ old('municipio_id') }}"
                                        class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                    <option value="" disabled selected>Elige departamento...</option>
                                </select>
                            </div>
                        </div>

                        {{-- Separador --}}
                        <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                        {{-- Redes Sociales --}}
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                <i class="bi bi-share text-warning"></i> Contacto y Redes Sociales
                            </h6>
                        </div>

                        {{-- WhatsApp --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="color:#25d366;"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="+505 8888-8888">
                            </div>
                        </div>

                        {{-- Instagram --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Instagram <span class="text-muted fw-normal">(opcional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="color:#e1306c;"><i class="bi bi-instagram"></i></span>
                                <input type="url" name="instagram" value="{{ old('instagram') }}"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://instagram.com/usuario">
                            </div>
                        </div>

                        {{-- Facebook --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Facebook <span class="text-muted fw-normal">(opcional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="color:#1877f2;"><i class="bi bi-facebook"></i></span>
                                <input type="url" name="facebook" value="{{ old('facebook') }}"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://facebook.com/pagina">
                            </div>
                        </div>

                        {{-- TikTok --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">TikTok <span class="text-muted fw-normal">(opcional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-dark"><i class="bi bi-tiktok"></i></span>
                                <input type="url" name="tiktok" value="{{ old('tiktok') }}"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://tiktok.com/@usuario">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- CARD 2: Cuenta del Propietario            --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-person-badge text-warning"></i> Cuenta del Propietario
                    </h6>
                    <p class="text-muted small mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        Se creará un usuario con acceso al panel del restaurante.
                    </p>

                    <div class="row g-4">

                        {{-- Nombre del Propietario --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Nombre del Propietario <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="propietario_nombre" value="{{ old('propietario_nombre') }}" required
                                       placeholder="Nombre completo"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                        {{-- Correo del Propietario --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Correo del Propietario <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="propietario_email" value="{{ old('propietario_email') }}" required
                                       placeholder="correo@propietario.com"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                        {{-- Contraseña --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="propietario_password" required minlength="8"
                                       placeholder="Mínimo 8 caracteres"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="propietario_password_confirmation" required minlength="8"
                                       placeholder="Repite la contraseña"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- CARD 3: Foto de Portada                   --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-image text-warning"></i> Foto de Portada
                    </h6>

                    <label for="imagen" class="w-100 cursor-pointer">
                        <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                             style="min-height: 180px; border: 2px dashed #dee2e6; cursor: pointer; transition: border-color 0.2s;"
                             onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                            <input type="file" name="imagen_principal" id="imagen" accept="image/*" required
                                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer; z-index:10;">
                            <div id="preview-container" class="text-center py-3">
                                <i class="bi bi-cloud-upload fs-2 text-secondary"></i>
                                <p class="small text-muted mt-1 mb-0">Haz clic para seleccionar</p>
                                <p class="small text-secondary" style="font-size:0.75rem;">JPG, PNG o WEBP</p>
                            </div>
                            <img id="image-preview" src="" alt=""
                                 class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                        </div>
                    </label>
                </div>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- CARD 4: Galería de Fotos                  --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-images text-warning"></i> Fotos del Álbum
                        <span class="fw-normal text-capitalize text-secondary" style="font-size: 0.7rem;">(Opcional — Máx. 4 imágenes)</span>
                    </h6>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Haz clic en cada casilla para agregar una foto individualmente.
                    </p>

                    <div class="row g-3">
                        @for($i = 0; $i < 4; $i++)
                        <div class="col-6 col-sm-3">
                            <label for="galeria_{{ $i }}" class="w-100 cursor-pointer">
                                <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                     style="aspect-ratio: 1/1; border: 2px dashed #dee2e6; cursor: pointer; transition: border-color 0.2s;"
                                     onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                                    <input type="file" name="galeria[]" id="galeria_{{ $i }}" accept="image/*"
                                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer; z-index:10;">
                                    <div id="placeholder-{{ $i }}" class="text-center pointer-events-none">
                                        <i class="bi bi-plus-lg fs-4 text-secondary"></i>
                                        <p class="small text-muted mb-0 mt-1" style="font-size:0.7rem;">Foto {{ $i + 1 }}</p>
                                    </div>
                                    <img id="preview-galeria-{{ $i }}" src="" alt=""
                                         class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                                </div>
                            </label>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- CARD 5: Ubicación y Mapa                  --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-geo-alt text-warning"></i> Ubicación del Restaurante
                    </h6>

                    {{-- Buscador --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Buscar Dirección</label>
                        <div class="d-flex gap-2">
                            <div class="input-group flex-1">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" id="direccion-buscar"
                                       placeholder="Ej: Restaurante La Terraza, Masaya, Nicaragua"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                            <button type="button" id="btn-buscar-mapa"
                                    class="btn btn-warning fw-semibold px-4 text-dark" style="white-space:nowrap;">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                        </div>
                        <p class="small text-muted mt-1 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Si no encuentra la dirección exacta, haz clic directamente en el mapa para colocar el pin.
                        </p>
                    </div>

                    {{-- Dirección exacta --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">
                            Dirección Exacta <span class="text-muted fw-normal">(se guarda automáticamente al buscar o hacer clic en el mapa)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#f97316;"><i class="bi bi-pin-map-fill"></i></span>
                            <input type="text" name="direccion" id="direccion"
                                   value="{{ old('direccion') }}"
                                   placeholder="La dirección aparecerá aquí..."
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    {{-- Coordenadas ocultas --}}
                    <input type="hidden" name="latitud"  id="latitud"  value="{{ old('latitud') }}">
                    <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud') }}">

                    {{-- Mapa --}}
                    <div id="mapa-restaurante" class="w-100 rounded-3 border shadow-sm" style="height: 340px;"></div>

                    <p id="mapa-coords-info" class="small text-muted mt-2 d-none">
                        <i class="bi bi-crosshair text-warning me-1"></i>
                        Coordenadas guardadas: <span id="coords-display" class="font-monospace text-dark ms-1"></span>
                    </p>
                </div>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- CARD 6: Descripción                       --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-text-paragraph text-warning"></i> Descripción Comercial
                    </h6>
                    <textarea name="descripcion" rows="4"
                              class="form-control bg-light" style="box-shadow:none; resize:none;"
                              placeholder="Breve reseña del ambiente, tipo de cocina...">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            {{-- ── Botones de Control ── --}}
            <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
                <a href="{{ route('admin.restaurantes.index') }}"
                   class="text-muted text-decoration-none small fw-medium">
                    <i class="bi bi-chevron-left me-1"></i> Volver al panel admin
                </a>
                <button type="submit" id="btn-submit-restaurante"
                        class="btn btn-warning fw-semibold px-5 py-2 rounded-pill shadow-sm text-dark">
                    <i class="bi bi-check2-circle me-1"></i> Registrar Restaurante
                </button>
            </div>

        </form>
    </div>

    <style>
        .cursor-pointer { cursor: pointer; }
    </style>

    <script>
        // ── Preview Portada ──
        document.getElementById('imagen').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('preview-container');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });

        // ── Preview Galería individual ──
        for (let i = 0; i < 4; i++) {
            (function (idx) {
                document.getElementById('galeria_' + idx).addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('preview-galeria-' + idx);
                        const placeholder = document.getElementById('placeholder-' + idx);
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        if (placeholder) placeholder.classList.add('d-none');
                    };
                    reader.readAsDataURL(file);
                });
            })(i);
        }

        // ── Departamentos → Municipios ──
        document.addEventListener('DOMContentLoaded', function () {
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

            depSelect.addEventListener('change', function () { cargarMunicipios(this.value); });
            if (depSelect.value) cargarMunicipios(depSelect.value, muniSelect.dataset.oldMuni);

            // ── Submit: deshabilitar botón ──
            document.getElementById('form-restaurante').addEventListener('submit', function () {
                muniSelect.disabled = false;
                const btn = document.getElementById('btn-submit-restaurante');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
                }
            });
        });
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function () {
        function initMapa() {
            const defaultLat = 12.8654;
            const defaultLng = -85.2072;

            const savedLat = document.getElementById('latitud').value;
            const savedLng = document.getElementById('longitud').value;

            const initLat  = savedLat ? parseFloat(savedLat) : defaultLat;
            const initLng  = savedLng ? parseFloat(savedLng) : defaultLng;
            const initZoom = savedLat ? 16 : 7;

            const mapa = L.map('mapa-restaurante').setView([initLat, initLng], initZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapa);

            const iconoNaranja = L.divIcon({
                html: '<div style="background:#ffc107;width:20px;height:20px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 20],
                className: ''
            });

            let marker = null;

            if (savedLat && savedLng) {
                marker = L.marker([initLat, initLng], { icon: iconoNaranja, draggable: true }).addTo(mapa);
                actualizarInfo(initLat, initLng);
                marker.on('dragend', function () {
                    const pos = marker.getLatLng();
                    actualizarCoordenadas(pos.lat, pos.lng);
                    geocodeInverso(pos.lat, pos.lng);
                });
            }

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

            document.getElementById('btn-buscar-mapa').addEventListener('click', buscarDireccion);
            document.getElementById('direccion-buscar').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); buscarDireccion(); }
            });

            function buscarDireccion() {
                const query = document.getElementById('direccion-buscar').value.trim();
                if (!query) return;

                const btn = document.getElementById('btn-buscar-mapa');
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Buscando...';
                btn.disabled = true;

                const queryFinal = query.toLowerCase().includes('nicaragua') ? query : query + ', Nicaragua';

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(queryFinal)}&limit=1&countrycodes=ni`, {
                    headers: { 'Accept-Language': 'es' }
                })
                .then(r => r.json())
                .then(data => {
                    btn.innerHTML = '<i class="bi bi-search me-1"></i> Buscar';
                    btn.disabled = false;

                    if (data.length === 0) {
                        alert('No se encontró esa dirección. Intenta ser más específico o haz clic directamente en el mapa.');
                        return;
                    }

                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
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
                    document.getElementById('direccion').value = data[0].display_name;
                })
                .catch(() => {
                    btn.innerHTML = '<i class="bi bi-search me-1"></i> Buscar';
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
                document.getElementById('mapa-coords-info').classList.remove('d-none');
                document.getElementById('coords-display').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMapa);
        } else {
            initMapa();
        }
    })();
    </script>

</x-app-layout>
