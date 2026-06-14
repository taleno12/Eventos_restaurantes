@extends('gastrobar.layout')
@section('title', 'Mi Perfil')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Mi Perfil</div>
        <div class="page-sub">Información pública de {{ $gastrobar->nombre }}</div>
    </div>
    <a href="{{ route('gastrobares.show', $gastrobar) }}" target="_blank" class="btn-secondary-panel">
        <i class="bi bi-box-arrow-up-right"></i> Ver página pública
    </a>
</div>

@if($errors->any())
    <div class="panel-alert panel-alert-error">
        <i class="bi bi-exclamation-circle-fill fs-5"></i>
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('gastrobar.perfil.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Información básica --}}
            <div class="panel-card">
                <div class="card-header">Información básica</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Nombre del gastrobar *</label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre', $gastrobar->nombre) }}" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Tipo de bar</label>
                            <select name="tipo_bar" class="form-select">
                                <option value="">Seleccionar tipo...</option>
                                @foreach(['Cocktail Bar','Sports Bar','Rooftop Bar','Lounge Bar','Bar de Tapas','Bar de Vinos','Bar de Cervezas','Otro'] as $tipo)
                                    <option value="{{ $tipo }}"
                                        {{ old('tipo_bar', $gastrobar->tipo_bar) === $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Tipo de cocina</label>
                            <input type="text" name="tipo_cocina" class="form-control"
                                   placeholder="Ej: Fusión, Internacional, Tapas..."
                                   value="{{ old('tipo_cocina', $gastrobar->tipo_cocina) }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Ambiente</label>
                            <select name="ambiente" class="form-select">
                                <option value="">Seleccionar ambiente...</option>
                                @foreach(['Interior','Exterior','Rooftop','Mixto'] as $amb)
                                    <option value="{{ $amb }}"
                                        {{ old('ambiente', $gastrobar->ambiente) === $amb ? 'selected' : '' }}>
                                        {{ $amb }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Capacidad</label>
                            <input type="number" name="capacidad" class="form-control" min="1"
                                   placeholder="Ej: 80"
                                   value="{{ old('capacidad', $gastrobar->capacidad) }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Correo electrónico</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="contacto@migastrobar.com"
                                   value="{{ old('email', $gastrobar->email) }}">
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Descripción <span style="color:var(--muted);font-weight:400;">(opcional)</span></label>
                        <textarea name="descripcion" class="form-control" style="min-height:100px;"
                                  placeholder="Cuéntanos sobre tu gastrobar, tu historia, qué te hace especial...">{{ old('descripcion', $gastrobar->descripcion) }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Horario --}}
            <div class="panel-card">
                <div class="card-header">Horario de atención</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    @php
                        $diasConfig = [
                            'lunes'     => 'Lun',
                            'martes'    => 'Mar',
                            'miercoles' => 'Mié',
                            'jueves'    => 'Jue',
                            'viernes'   => 'Vie',
                            'sabado'    => 'Sáb',
                            'domingo'   => 'Dom',
                        ];
                        $diasActivos = old('dias_atencion', $gastrobar->dias_atencion ?? []);
                    @endphp

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Días de atención</label>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:6px;">
                            @foreach($diasConfig as $valor => $etiqueta)
                                <input type="checkbox"
                                       name="dias_atencion[]"
                                       value="{{ $valor }}"
                                       id="dia-{{ $valor }}"
                                       {{ in_array($valor, $diasActivos) ? 'checked' : '' }}
                                       style="display:none;">
                                <span class="dia-pill-toggle {{ in_array($valor, $diasActivos) ? 'active' : '' }}"
                                      data-dia="{{ $valor }}">
                                    {{ $etiqueta }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Hora de apertura</label>
                            <input type="time" name="hora_apertura" class="form-control"
                                   value="{{ old('hora_apertura', $gastrobar->hora_apertura ? substr($gastrobar->hora_apertura, 0, 5) : '') }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Hora de cierre</label>
                            <input type="time" name="hora_cierre" class="form-control"
                                   value="{{ old('hora_cierre', $gastrobar->hora_cierre ? substr($gastrobar->hora_cierre, 0, 5) : '') }}">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Ubicación --}}
            <div class="panel-card">
                <div class="card-header">Ubicación</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Departamento *</label>
                            <select name="departamento_id" id="select-dep" class="form-select" required>
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->id }}"
                                        {{ old('departamento_id', $gastrobar->departamento_id) == $dep->id ? 'selected' : '' }}>
                                        {{ $dep->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Municipio *</label>
                            <select name="municipio_id" id="select-mun" class="form-select" required>
                                @foreach($municipios as $mun)
                                    <option value="{{ $mun->id }}"
                                        {{ old('municipio_id', $gastrobar->municipio_id) == $mun->id ? 'selected' : '' }}>
                                        {{ $mun->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Dirección exacta <span style="color:var(--muted);font-weight:400;">(opcional)</span></label>
                        <input type="text" name="direccion" id="direccion" class="form-control"
                               placeholder="Ej: Del parque central 1 cuadra al lago, edificio esquinero"
                               value="{{ old('direccion', $gastrobar->direccion) }}">
                    </div>

                    {{-- Un solo campo de coordenadas --}}
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Coordenadas <span style="color:var(--muted);font-weight:400;">(latitud, longitud — o haz clic en el mapa)</span>
                        </label>
                        <div style="display:flex;gap:8px;">
                            <input type="text" id="coordenadas-input" class="form-control"
                                   placeholder="Ej: 12.865400, -85.207200"
                                   value="{{ $gastrobar->latitud && $gastrobar->longitud ? $gastrobar->latitud . ', ' . $gastrobar->longitud : '' }}">
                            <button type="button" id="btn-ir-coordenadas"
                                    class="btn btn-outline-warning fw-semibold text-dark" style="white-space:nowrap;">
                                <i class="bi bi-crosshair me-1"></i> Ir al mapa
                            </button>
                        </div>
                        <p style="font-size:11px;color:var(--muted);margin-top:4px;margin-bottom:0;">
                            <i class="bi bi-info-circle"></i>
                            Escribe las coordenadas separadas por coma y presiona "Ir al mapa", o haz clic directamente en el mapa.
                        </p>
                    </div>

                    {{-- Inputs ocultos enviados al servidor --}}
                    <input type="hidden" name="latitud"  id="latitud"  value="{{ old('latitud', $gastrobar->latitud) }}">
                    <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $gastrobar->longitud) }}">

                    {{-- Mapa interactivo --}}
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Buscar Dirección</label>
                        <div style="display:flex;gap:8px;margin-bottom:6px;">
                            <input type="text" id="direccion-buscar" class="form-control"
                                   placeholder="Ej: Gastrobar La Noche, Managua, Nicaragua"
                                   style="font-size:13px;">
                            <button type="button" id="btn-buscar-mapa"
                                    class="btn btn-warning btn-sm fw-semibold text-dark px-3" style="white-space:nowrap;">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                        </div>
                        <p style="font-size:11px;color:var(--muted);margin-bottom:8px;">
                            <i class="bi bi-info-circle"></i>
                            Si no encuentra la dirección exacta, haz clic directamente en el mapa para colocar el pin.
                        </p>
                        <div id="mapa-perfil" style="height:300px;border-radius:12px;overflow:hidden;border:1px solid var(--card-border);"></div>
                        <p id="mapa-coords-info" style="display:none;font-size:11px;color:var(--muted);margin-top:6px;">
                            <i class="bi bi-crosshair" style="color:#f97316;"></i>
                            Coordenadas guardadas: <span id="coords-display" style="font-family:monospace;color:var(--text);"></span>
                        </p>
                    </div>

                </div>
            </div>

            {{-- Contacto y redes --}}
            <div class="panel-card">
                <div class="card-header">Contacto y redes sociales</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                <i class="bi bi-telephone-fill" style="color:var(--primary);"></i> Teléfono
                            </label>
                            <input type="text" name="telefono" class="form-control"
                                   placeholder="+505 8888-8888"
                                   value="{{ old('telefono', $gastrobar->telefono) }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                <i class="bi bi-whatsapp" style="color:#22c55e;"></i> WhatsApp
                            </label>
                            <input type="text" name="whatsapp" class="form-control"
                                   placeholder="+505 8888-8888"
                                   value="{{ old('whatsapp', $gastrobar->whatsapp) }}">
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            <i class="bi bi-instagram" style="color:#db2777;"></i> Instagram <span style="color:var(--muted);font-weight:400;">(URL completa)</span>
                        </label>
                        <input type="url" name="instagram" class="form-control"
                               placeholder="https://instagram.com/tugastrobar"
                               value="{{ old('instagram', $gastrobar->instagram) }}">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                <i class="bi bi-facebook" style="color:#2563eb;"></i> Facebook
                            </label>
                            <input type="url" name="facebook" class="form-control"
                                   placeholder="https://facebook.com/tugastrobar"
                                   value="{{ old('facebook', $gastrobar->facebook) }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                <i class="bi bi-tiktok" style="color:#0f172a;"></i> TikTok
                            </label>
                            <input type="url" name="tiktok" class="form-control"
                                   placeholder="https://tiktok.com/@tugastrobar"
                                   value="{{ old('tiktok', $gastrobar->tiktok) }}">
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Columna derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Imagen principal --}}
            <div class="panel-card">
                <div class="card-header">Imagen principal</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">

                    @if($gastrobar->imagen_principal)
                    <div>
                        <p style="font-size:11px;color:var(--muted);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Imagen actual</p>
                        <img src="{{ asset('storage/'.$gastrobar->imagen_principal) }}"
                             style="width:100%;border-radius:10px;aspect-ratio:1;object-fit:cover;border:1px solid var(--card-border);">
                    </div>
                    @endif

                    <div style="border:2px dashed var(--card-border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;"
                         onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--card-border)'">
                        <img id="portada-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                        <div id="portada-placeholder" style="text-align:center;padding:16px;">
                            <i class="bi bi-cloud-upload" style="font-size:28px;color:var(--muted);display:block;margin-bottom:8px;"></i>
                            <p style="font-size:11px;color:var(--muted);">{{ $gastrobar->imagen_principal ? 'Cambiar imagen' : 'Subir imagen principal' }}</p>
                        </div>
                        <input type="file" name="imagen_principal" id="portada-input" accept="image/*"
                               style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    </div>
                    <p style="font-size:10px;color:var(--muted);">JPG, PNG, WEBP — máx. 3 MB. Deja vacío para mantener la actual.</p>
                </div>
            </div>

            {{-- Guardar --}}
            <div class="panel-card">
                <div class="card-body">
                    <button type="submit" class="btn-primary-panel" style="width:100%;justify-content:center;padding:12px;">
                        <i class="bi bi-floppy-fill"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('gastrobar.dashboard') }}" class="btn-secondary-panel"
                       style="width:100%;justify-content:center;margin-top:10px;">
                        Cancelar
                    </a>
                </div>
            </div>

            {{-- Vista previa --}}
            <div class="panel-card">
                <div class="card-header">Vista previa</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:40px;height:40px;border-radius:10px;overflow:hidden;background:#f5f6fa;flex-shrink:0;">
                            @if($gastrobar->imagen_principal)
                                <img src="{{ asset('storage/'.$gastrobar->imagen_principal) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:var(--primary);">
                                    {{ strtoupper(substr($gastrobar->nombre, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $gastrobar->nombre }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $gastrobar->tipo_bar ?? 'Sin tipo de bar' }}</div>
                        </div>
                    </div>
                    @if($gastrobar->municipio)
                    <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-geo-alt-fill" style="color:var(--primary);font-size:11px;"></i>
                        {{ $gastrobar->municipio->nombre }}, {{ $gastrobar->departamento->nombre }}
                    </div>
                    @endif
                    @if($gastrobar->whatsapp)
                    <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-whatsapp" style="color:#22c55e;font-size:11px;"></i>
                        {{ $gastrobar->whatsapp }}
                    </div>
                    @endif
                    @if($gastrobar->hora_apertura && $gastrobar->hora_cierre)
                    <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-clock" style="color:var(--primary);font-size:11px;"></i>
                        {{ \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') }}
                        – {{ \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ── Preview portada ──
document.getElementById('portada-input').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('portada-preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('portada-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});

// ── Toggle días ──
document.querySelectorAll('.dia-pill-toggle').forEach(function(pill) {
    pill.addEventListener('click', function() {
        const dia = this.getAttribute('data-dia');
        const checkbox = document.getElementById('dia-' + dia);
        checkbox.checked = !checkbox.checked;
        this.classList.toggle('active', checkbox.checked);
    });
});

// ── Cascada departamento → municipio ──
const selectDep = document.getElementById('select-dep');
const selectMun = document.getElementById('select-mun');
const currentMun = {{ $gastrobar->municipio_id ?? 'null' }};

selectDep.addEventListener('change', function () {
    const depId = this.value;
    if (!depId) return;
    fetch(`/mi-gastrobar/api/municipios/${depId}`)
        .then(r => r.json())
        .then(data => {
            selectMun.innerHTML = '<option value="">Selecciona municipio</option>';
            data.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nombre;
                if (m.id == currentMun) opt.selected = true;
                selectMun.appendChild(opt);
            });
        });
});

// ── Mapa Leaflet ──
(function () {
    function initMapa() {
        const latInput    = document.getElementById('latitud');
        const lngInput    = document.getElementById('longitud');
        const coordInput  = document.getElementById('coordenadas-input');

        const savedLat = latInput.value;
        const savedLng = lngInput.value;

        const initLat  = savedLat ? parseFloat(savedLat) : 12.8654;
        const initLng  = savedLng ? parseFloat(savedLng) : -85.2072;
        const initZoom = savedLat ? 16 : 7;

        const mapa = L.map('mapa-perfil').setView([initLat, initLng], initZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);

        const iconoNaranja = L.divIcon({
            html: '<div style="background:#f97316;width:20px;height:20px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
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

        // ── Botón Buscar dirección ──
        document.getElementById('btn-buscar-mapa').addEventListener('click', buscarDireccion);
        document.getElementById('direccion-buscar').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); buscarDireccion(); }
        });

        // ── Botón Ir al mapa por coordenadas ──
        document.getElementById('btn-ir-coordenadas').addEventListener('click', function () {
            const valor  = coordInput.value.trim();
            const partes = valor.split(',');
            if (partes.length !== 2) {
                alert('Ingresa las coordenadas en formato: latitud, longitud\nEj: 12.865400, -85.207200');
                return;
            }
            const lat = parseFloat(partes[0].trim());
            const lng = parseFloat(partes[1].trim());
            if (isNaN(lat) || isNaN(lng)) {
                alert('Coordenadas inválidas. Ejemplo: 12.865400, -85.207200');
                return;
            }
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
            geocodeInverso(lat, lng);
        });

        coordInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('btn-ir-coordenadas').click(); }
        });

        function buscarDireccion() {
            const query = document.getElementById('direccion-buscar').value.trim();
            if (!query) return;
            const btn = document.getElementById('btn-buscar-mapa');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btn.disabled = true;
            const q = query.toLowerCase().includes('nicaragua') ? query : query + ', Nicaragua';
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1&countrycodes=ni`, {
                headers: { 'Accept-Language': 'es' }
            })
            .then(r => r.json())
            .then(data => {
                btn.innerHTML = '<i class="bi bi-search me-1"></i> Buscar';
                btn.disabled = false;
                if (!data.length) {
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
            }).catch(() => {});
        }

        function actualizarCoordenadas(lat, lng) {
            latInput.value   = lat.toFixed(7);
            lngInput.value   = lng.toFixed(7);
            coordInput.value = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
            actualizarInfo(lat, lng);
        }

        function actualizarInfo(lat, lng) {
            const info = document.getElementById('mapa-coords-info');
            info.style.display = 'block';
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

<style>
.dia-pill-toggle {
    display: inline-block;
    padding: 5px 13px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: 1.5px solid var(--card-border);
    background: #f9fafb;
    color: #9ca3af;
    transition: all 0.18s;
    user-select: none;
    cursor: pointer;
}
.dia-pill-toggle.active {
    background: #fff7ed;
    color: #ea580c;
    border-color: #fed7aa;
}
.dia-pill-toggle:hover {
    border-color: #ea580c;
}
</style>
@endsection
