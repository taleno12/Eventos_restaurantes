@extends('restaurante.layout')
@section('title', 'Mi Perfil')

@section('content')

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="page-header">
    <div>
        <div class="page-title">Mi Perfil</div>
        <div class="page-sub">Información pública de {{ $restaurante->nombre }}</div>
    </div>
</div>

@if($errors->any())
    <div class="panel-alert panel-alert-error">
        <i class="bi bi-exclamation-circle-fill fs-5"></i>
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('restaurante.perfil.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Información básica --}}
            <div class="panel-card">
                <div class="card-header">Información básica</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div class="grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Nombre del restaurante *</label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre', $restaurante->nombre) }}" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Especialidad *</label>
                            <input type="text" name="especialidad" class="form-control"
                                   placeholder="Ej: Mariscos, Carnes, Comida típica..."
                                   value="{{ old('especialidad', $restaurante->especialidad) }}" required
                                   list="especialidades-list">
                            <datalist id="especialidades-list">
                                <option value="Mariscos">
                                <option value="Carnes y asados">
                                <option value="Comida típica nicaragüense">
                                <option value="Pizzas y pastas">
                                <option value="Comida rápida">
                                <option value="Sushi y comida japonesa">
                                <option value="Vegetariana">
                                <option value="Desayunos">
                                <option value="Pollos y aves">
                            </datalist>
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Descripción <span style="color:var(--muted);font-weight:400;">(opcional)</span></label>
                        <textarea name="descripcion" class="form-control" style="min-height:100px;"
                                  placeholder="Cuéntanos sobre tu restaurante, tu historia, qué te hace especial...">{{ old('descripcion', $restaurante->descripcion) }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Ubicación --}}
            <div class="panel-card">
                <div class="card-header">
                    <i class="bi bi-geo-alt-fill me-1" style="color:var(--primary);"></i> Ubicación
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Departamento / Municipio --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Departamento *</label>
                            <select name="departamento_id" id="select-dep" class="form-select" required>
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->id }}"
                                        {{ old('departamento_id', $restaurante->departamento_id) == $dep->id ? 'selected' : '' }}>
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
                                        {{ old('municipio_id', $restaurante->municipio_id) == $mun->id ? 'selected' : '' }}>
                                        {{ $mun->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Buscador de dirección --}}
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Buscar Dirección en el mapa</label>
                        <div class="d-flex gap-2">
                            <div class="input-group flex-1">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" id="direccion-buscar"
                                       placeholder="Ej: Restaurante La Terraza, Masaya, Nicaragua"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                            <button type="button" id="btn-buscar-mapa"
                                    class="btn btn-warning fw-semibold px-3 text-dark" style="white-space:nowrap;">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                        </div>
                        <p class="small text-muted mt-1 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Si no encuentra la dirección exacta, haz clic directamente en el mapa para colocar el pin.
                        </p>
                    </div>

                    {{-- Dirección exacta --}}
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Dirección exacta
                            <span style="color:var(--muted);font-weight:400;">(se actualiza al hacer clic en el mapa)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#f97316;"><i class="bi bi-pin-map-fill"></i></span>
                            <input type="text" name="direccion" id="direccion"
                                   value="{{ old('direccion', $restaurante->direccion) }}"
                                   placeholder="La dirección aparecerá aquí..."
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    {{-- Coordenadas --}}
                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Coordenadas
                            <span style="color:var(--muted);font-weight:400;">(latitud, longitud — o haz clic en el mapa)</span>
                        </label>
                        <div class="d-flex gap-2 align-items-start">
                            <div class="input-group flex-1">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-compass"></i></span>
                                <input type="text" id="coordenadas-input"
                                       placeholder="Ej: 12.865400, -85.207200"
                                       value="{{ old('latitud', $restaurante->latitud) && old('longitud', $restaurante->longitud) ? old('latitud', $restaurante->latitud) . ', ' . old('longitud', $restaurante->longitud) : (($restaurante->latitud && $restaurante->longitud) ? $restaurante->latitud . ', ' . $restaurante->longitud : '') }}"
                                       class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                            </div>
                            <button type="button" id="btn-ir-coordenadas"
                                    class="btn btn-outline-warning fw-semibold text-dark" style="white-space:nowrap;">
                                <i class="bi bi-crosshair me-1"></i> Ir
                            </button>
                        </div>
                    </div>

                    {{-- Campos ocultos --}}
                    <input type="hidden" id="latitud"  name="latitud"  value="{{ old('latitud', $restaurante->latitud) }}">
                    <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud', $restaurante->longitud) }}">

                    {{-- Mapa --}}
                    <div id="mapa-restaurante" class="w-100 rounded-3 border shadow-sm" style="height:320px;"></div>

                    <p id="mapa-coords-info" class="small text-muted mb-0 d-none">
                        <i class="bi bi-crosshair text-warning me-1"></i>
                        Coordenadas guardadas: <span id="coords-display" class="font-monospace text-dark ms-1"></span>
                    </p>

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
                                   value="{{ old('telefono', $restaurante->telefono) }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                <i class="bi bi-whatsapp" style="color:#22c55e;"></i> WhatsApp
                            </label>
                            <input type="text" name="whatsapp" class="form-control"
                                   placeholder="+505 8888-8888"
                                   value="{{ old('whatsapp', $restaurante->whatsapp) }}">
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            <i class="bi bi-instagram" style="color:#db2777;"></i> Instagram <span style="color:var(--muted);font-weight:400;">(URL completa)</span>
                        </label>
                        <input type="url" name="instagram" class="form-control"
                               placeholder="https://instagram.com/turestaurante"
                               value="{{ old('instagram', $restaurante->instagram) }}">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                <i class="bi bi-facebook" style="color:#2563eb;"></i> Facebook
                            </label>
                            <input type="url" name="facebook" class="form-control"
                                   placeholder="https://facebook.com/turestaurante"
                                   value="{{ old('facebook', $restaurante->facebook) }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">
                                <i class="bi bi-tiktok" style="color:#0f172a;"></i> TikTok
                            </label>
                            <input type="url" name="tiktok" class="form-control"
                                   placeholder="https://tiktok.com/@turestaurante"
                                   value="{{ old('tiktok', $restaurante->tiktok) }}">
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Columna derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Foto de portada --}}
            <div class="panel-card">
                <div class="card-header">Foto de portada</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">

                    @if($restaurante->foto_portada)
                    <div>
                        <p style="font-size:11px;color:var(--muted);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Portada actual</p>
                        <img src="{{ asset('storage/'.$restaurante->foto_portada) }}"
                             style="width:100%;border-radius:10px;aspect-ratio:1;object-fit:cover;border:1px solid var(--card-border);">
                    </div>
                    @endif

                    <div style="border:2px dashed var(--card-border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;"
                         onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--card-border)'">
                        <img id="portada-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                        <div id="portada-placeholder" style="text-align:center;padding:16px;">
                            <i class="bi bi-cloud-upload" style="font-size:28px;color:var(--muted);display:block;margin-bottom:8px;"></i>
                            <p style="font-size:11px;color:var(--muted);">{{ $restaurante->foto_portada ? 'Cambiar portada' : 'Subir foto de portada' }}</p>
                        </div>
                        <input type="file" name="foto_portada" id="portada-input" accept="image/*"
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
                    <a href="{{ route('restaurante.dashboard') }}" class="btn-secondary-panel"
                       style="width:100%;justify-content:center;margin-top:10px;">
                        Cancelar
                    </a>
                </div>
            </div>

            {{-- Vista previa info --}}
            <div class="panel-card">
                <div class="card-header">Vista previa</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:40px;height:40px;border-radius:10px;overflow:hidden;background:#f5f6fa;flex-shrink:0;">
                            @if($restaurante->foto_portada)
                                <img src="{{ asset('storage/'.$restaurante->foto_portada) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:var(--primary);">
                                    {{ strtoupper(substr($restaurante->nombre, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $restaurante->nombre }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $restaurante->especialidad ?? 'Sin especialidad' }}</div>
                        </div>
                    </div>
                    @if($restaurante->municipio)
                    <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-geo-alt-fill" style="color:var(--primary);font-size:11px;"></i>
                        {{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}
                    </div>
                    @endif
                    @if($restaurante->whatsapp)
                    <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-whatsapp" style="color:#22c55e;font-size:11px;"></i>
                        {{ $restaurante->whatsapp }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@section('scripts')
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

// ── Municipios dinámicos ──
const selectDep = document.getElementById('select-dep');
const selectMun = document.getElementById('select-mun');
const currentMun = {{ $restaurante->municipio_id ?? 'null' }};

selectDep.addEventListener('change', function () {
    const depId = this.value;
    if (!depId) return;
    fetch(`/mi-restaurante/api/municipios/${depId}`)
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

// ── Mapa interactivo ──
(function () {
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

    // Buscar dirección
    document.getElementById('btn-buscar-mapa').addEventListener('click', buscarDireccion);
    document.getElementById('direccion-buscar').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); buscarDireccion(); }
    });

    // Ir a coordenadas
    document.getElementById('btn-ir-coordenadas').addEventListener('click', irACoordenadas);
    document.getElementById('coordenadas-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); irACoordenadas(); }
    });

    function irACoordenadas() {
        const valor = document.getElementById('coordenadas-input').value.trim();
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
    }

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
        document.getElementById('coordenadas-input').value = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
        actualizarInfo(lat, lng);
    }

    function actualizarInfo(lat, lng) {
        document.getElementById('mapa-coords-info').classList.remove('d-none');
        document.getElementById('coords-display').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }
})();
</script>
@endsection
