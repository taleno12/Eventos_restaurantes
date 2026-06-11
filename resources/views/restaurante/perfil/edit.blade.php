@extends('restaurante.layout')
@section('title', 'Mi Perfil')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Mi Perfil</div>
        <div class="page-sub">Información pública de {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurantes.show', $restaurante) }}" target="_blank" class="btn-secondary-panel">
        <i class="bi bi-box-arrow-up-right"></i> Ver página pública
    </a>
</div>

@if(session('success'))
    <div class="panel-alert panel-alert-success">
        <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
    </div>
@endif
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
                <div class="card-header">Ubicación</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

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

                    <div>
                        <label class="form-label fw-semibold" style="font-size:13px;">Dirección exacta <span style="color:var(--muted);font-weight:400;">(opcional)</span></label>
                        <input type="text" name="direccion" class="form-control"
                               placeholder="Ej: De la Iglesia 2 cuadras al norte, casa esquinera"
                               value="{{ old('direccion', $restaurante->direccion) }}">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Latitud <span style="color:var(--muted);font-weight:400;">(GPS)</span></label>
                            <input type="number" name="latitud" class="form-control"
                                   step="any" placeholder="Ej: 11.9789"
                                   value="{{ old('latitud', $restaurante->latitud) }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Longitud <span style="color:var(--muted);font-weight:400;">(GPS)</span></label>
                            <input type="number" name="longitud" class="form-control"
                                   step="any" placeholder="Ej: -86.0937"
                                   value="{{ old('longitud', $restaurante->longitud) }}">
                        </div>
                    </div>
                    <p style="font-size:11px;color:var(--muted);">
                        <i class="bi bi-info-circle"></i>
                        Para obtener coordenadas: busca tu local en Google Maps → clic derecho → copia las coordenadas.
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
<script>
// Preview portada
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

// Municipios dinámicos
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
</script>
@endsection