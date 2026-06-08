@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/css/intlTelInput.css"/>

@section('content')
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
                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Restaurante
            </h1>
            <p class="text-muted small mb-0">Modifica los parámetros y canales digitales del establecimiento seleccionado.</p>
        </div>
    </div>

    <form id="form-edit-restaurante" action="{{ route('admin.restaurantes.update', $restaurante->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Errores globales --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-circle-fill fs-5 mt-0.5"></i>
                    <div>
                        <p class="fw-semibold mb-1">Corrige los siguientes errores:</p>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 1: Datos Generales                   --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-info-circle text-warning"></i> Datos Generales: {{ $restaurante->nombre }}
                </h6>

                <div class="row g-4">

                    {{-- Nombre Comercial --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark small">Nombre Comercial <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                            <input type="text" name="nombre" value="{{ old('nombre', $restaurante->nombre) }}" required
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="Nombre del restaurante">
                        </div>
                    </div>

                    {{-- Email de Contacto --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Email de Contacto <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email', $restaurante->email) }}" required
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="correo@restaurante.com">
                        </div>
                    </div>

                    {{-- Especialidad --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Especialidad Culinaria <span class="text-muted fw-normal">(ej. Mariscos, Asados)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-egg-fried"></i></span>
                            <input type="text" name="especialidad" value="{{ old('especialidad', $restaurante->especialidad) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="No especificada">
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
                            <select id="select-departamento" name="departamento_id" required
                                    class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}" @selected(old('departamento_id', $restaurante->departamento_id) == $depto->id)>
                                        {{ $depto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Municipio <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                            <select id="select-municipio" name="municipio_id" required disabled
                                    class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                <option value="">Cargando municipios...</option>
                            </select>
                        </div>
                    </div>

                    {{-- Separador --}}
                    <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                    {{-- Redes Sociales --}}
                    <div class="col-12">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="bi bi-share text-warning"></i> Contacto Directo y Redes Sociales
                        </h6>
                    </div>

                    {{-- WhatsApp con selector de país --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">WhatsApp Comercial <span class="text-danger">*</span></label>
                        {{-- Campo visible con intl-tel-input --}}
                        <input type="tel" id="whatsapp_input"
                               class="form-control bg-light" style="box-shadow:none;"
                               placeholder="8888-8888">
                        {{-- Campo oculto que se envía al servidor con el número completo --}}
                        <input type="hidden" id="whatsapp_full" name="whatsapp" value="{{ old('whatsapp', $restaurante->whatsapp) }}">
                        <small class="text-muted">Selecciona tu país y escribe el número sin código.</small>
                    </div>

                    {{-- Instagram --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Instagram <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#e1306c;"><i class="bi bi-instagram"></i></span>
                            <input type="url" name="instagram" value="{{ old('instagram', $restaurante->instagram) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://instagram.com/perfil">
                        </div>
                    </div>

                    {{-- TikTok --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">TikTok <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-dark"><i class="bi bi-tiktok"></i></span>
                            <input type="url" name="tiktok" value="{{ old('tiktok', $restaurante->tiktok) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://tiktok.com/@usuario">
                        </div>
                    </div>

                    {{-- Facebook --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Facebook <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#1877f2;"><i class="bi bi-facebook"></i></span>
                            <input type="url" name="facebook" value="{{ old('facebook', $restaurante->facebook) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://facebook.com/pagina">
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
                    Deja la contraseña en blanco si no deseas cambiarla.
                </p>

                <div class="row g-4">

                    {{-- Nombre del Propietario --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Nombre del Propietario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="propietario_nombre" required
                                   value="{{ old('propietario_nombre', $restaurante->propietario->name ?? '') }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="Nombre completo">
                        </div>
                    </div>

                    {{-- Correo del Propietario --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Correo del Propietario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="propietario_email" required
                                   value="{{ old('propietario_email', $restaurante->propietario->email ?? '') }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="correo@propietario.com">
                        </div>
                    </div>

                    {{-- Nueva Contraseña con toggle --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Nueva Contraseña <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="propietario_password" id="edit_password" minlength="8"
                                   class="form-control bg-light border-start-0 border-end-0 ps-0" style="box-shadow:none;" placeholder="Mínimo 8 caracteres">
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted"
                                    onclick="togglePassword('edit_password', this)" title="Mostrar/ocultar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Confirmar Contraseña con toggle --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Confirmar Contraseña <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="propietario_password_confirmation" id="edit_password_confirm" minlength="8"
                                   class="form-control bg-light border-start-0 border-end-0 ps-0" style="box-shadow:none;" placeholder="Repite la contraseña">
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted"
                                    onclick="togglePassword('edit_password_confirm', this)" title="Mostrar/ocultar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
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

                <div class="row g-4 align-items-start">

                    {{-- Imagen actual --}}
                    <div class="col-12 col-md-6">
                        <p class="small fw-semibold text-muted mb-2">Imagen actual:</p>
                        @if($restaurante->foto_portada)
                            <img id="img-portada-actual"
                                 src="{{ asset('storage/' . $restaurante->foto_portada) }}"
                                 alt="Portada actual"
                                 class="w-100 rounded-3 border shadow-sm" style="height: 180px; object-fit: cover;">
                        @else
                            <div id="img-portada-actual"
                                 class="w-100 rounded-3 border-2 border-dashed bg-light d-flex flex-column align-items-center justify-content-center text-muted gap-2"
                                 style="height: 180px; border-style: dashed !important;">
                                <i class="bi bi-image fs-2 text-secondary"></i>
                                <span class="small">Sin imagen de portada</span>
                            </div>
                        @endif
                    </div>

                    {{-- Input nueva imagen --}}
                    <div class="col-12 col-md-6">
                        <p class="small fw-semibold text-muted mb-2">Reemplazar imagen <span class="fw-normal">(opcional)</span></p>
                        <label for="input-portada" class="w-100 cursor-pointer">
                            <div class="rounded-3 border-2 bg-light d-flex flex-column align-items-center justify-content-center text-muted position-relative overflow-hidden"
                                 style="height: 180px; border: 2px dashed #dee2e6; cursor: pointer; transition: border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                                <input type="file" name="imagen_principal" id="input-portada" accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer; z-index:10;">
                                <div id="placeholder-portada" class="text-center">
                                    <i class="bi bi-cloud-upload fs-2 text-secondary"></i>
                                    <p class="small text-muted mt-1 mb-0">Haz clic para seleccionar</p>
                                    <p class="small text-secondary" style="font-size:0.75rem;">JPG, PNG o WEBP</p>
                                </div>
                                <img id="nueva-preview-portada" src="" alt=""
                                     class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                            </div>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 4: Galería de Fotos                  --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-images text-warning"></i> Fotos del Álbum
                    <span class="fw-normal text-capitalize text-secondary" style="font-size: 0.7rem;">(Máx. 4 — las nuevas se agregan a las existentes)</span>
                </h6>

                {{-- Fotos existentes --}}
                @if($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                    <p class="small fw-semibold text-muted mt-3 mb-2">Imágenes actuales del álbum:</p>
                    <div class="row g-3 mb-4">
                        @foreach($restaurante->imagenes as $foto)
                            <div class="col-6 col-sm-3">
                                <div class="rounded-3 border overflow-hidden shadow-sm" style="height: 90px;">
                                    <img src="{{ asset('storage/' . $foto->ruta_foto) }}" alt="Foto álbum"
                                         class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="small text-muted mt-3 mb-3">
                        <i class="bi bi-info-circle text-secondary me-1"></i>
                        Este restaurante aún no tiene fotos en el álbum.
                    </p>
                @endif

                <p class="small fw-semibold text-muted mb-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Haz clic en cada casilla para agregar una foto individualmente.
                </p>
                <div class="row g-3">
                    @for($i = 0; $i < 4; $i++)
                    <div class="col-6 col-sm-3">
                        <label for="galeria_edit_{{ $i }}" class="w-100 cursor-pointer">
                            <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                 style="aspect-ratio: 1/1; border: 2px dashed #dee2e6; cursor: pointer; transition: border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                                <input type="file" name="galeria[]" id="galeria_edit_{{ $i }}" accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer; z-index:10;">
                                <div id="placeholder-edit-{{ $i }}" class="text-center pointer-events-none">
                                    <i class="bi bi-plus-lg fs-4 text-secondary"></i>
                                    <p class="small text-muted mb-0 mt-1" style="font-size:0.7rem;">Foto {{ $i + 1 }}</p>
                                </div>
                                <img id="preview-galeria-edit-{{ $i }}" src="" alt=""
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

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">Buscar Dirección</label>
                    <div class="d-flex gap-2">
                        <div class="input-group flex-1">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="edit-direccion-buscar"
                                   placeholder="Ej: Restaurante La Terraza, Masaya, Nicaragua"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                        <button type="button" id="edit-btn-buscar-mapa"
                                class="btn btn-warning fw-semibold px-4 text-dark" style="white-space:nowrap;">
                            <i class="bi bi-search me-1"></i> Buscar
                        </button>
                    </div>
                    <p class="small text-muted mt-1 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Si no encuentra la dirección exacta, haz clic directamente en el mapa para colocar el pin.
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Dirección Exacta <span class="text-muted fw-normal">(se actualiza al buscar o hacer clic en el mapa)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="color:#f97316;"><i class="bi bi-pin-map-fill"></i></span>
                        <input type="text" name="direccion" id="edit-direccion"
                               value="{{ old('direccion', $restaurante->direccion) }}"
                               placeholder="La dirección aparecerá aquí..."
                               class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Coordenadas <span class="text-muted fw-normal">(latitud, longitud — o haz clic en el mapa)</span>
                    </label>
                    <div class="d-flex gap-2 align-items-start">
                        <div class="input-group flex-1">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-compass"></i></span>
                            <input type="text" id="edit-coordenadas-input"
                                   placeholder="Ej: 12.865400, -85.207200"
                                   value="{{ (old('latitud', $restaurante->latitud) && old('longitud', $restaurante->longitud)) ? old('latitud', $restaurante->latitud) . ', ' . old('longitud', $restaurante->longitud) : '' }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                        <button type="button" id="edit-btn-ir-coordenadas"
                                class="btn btn-outline-warning fw-semibold text-dark" style="white-space:nowrap;">
                            <i class="bi bi-crosshair me-1"></i> Ir al mapa
                        </button>
                    </div>
                    <p class="small text-muted mt-1 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Escribe las coordenadas separadas por coma y presiona "Ir al mapa", o simplemente haz clic en el mapa.
                    </p>
                </div>

                <input type="hidden" name="latitud"  id="edit-latitud"  value="{{ old('latitud', $restaurante->latitud) }}">
                <input type="hidden" name="longitud" id="edit-longitud" value="{{ old('longitud', $restaurante->longitud) }}">

                <div id="edit-mapa-restaurante" class="w-100 rounded-3 border shadow-sm" style="height: 340px;"></div>

                <p id="edit-mapa-coords-info" class="small text-muted mt-2 d-none">
                    <i class="bi bi-crosshair text-warning me-1"></i>
                    Coordenadas guardadas: <span id="edit-coords-display" class="font-monospace text-dark ms-1"></span>
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 6: Horario de Atención               --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2"
                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-clock text-warning"></i> Horario de Atención
                </h6>

                {{-- Días laborales --}}
                <label class="form-label fw-semibold text-dark small mb-2">Días que atiende</label>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach(['lunes'=>'Lun','martes'=>'Mar','miercoles'=>'Mié','jueves'=>'Jue','viernes'=>'Vie','sabado'=>'Sáb','domingo'=>'Dom'] as $valor => $etiqueta)
                    <div>
                        <input type="checkbox" class="btn-check" name="dias_laborales[]"
                               id="dia_{{ $valor }}" value="{{ $valor }}"
                               @checked(in_array($valor, old('dias_laborales', $restaurante->dias_laborales ?? [])))>
                        <label class="btn btn-outline-warning btn-sm fw-semibold text-dark"
                               for="dia_{{ $valor }}">{{ $etiqueta }}</label>
                    </div>
                    @endforeach
                </div>

                {{-- Horas --}}
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Hora de Apertura</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-door-open"></i>
                            </span>
                            <input type="time" name="hora_apertura"
                                   value="{{ old('hora_apertura', $restaurante->hora_apertura ? \Carbon\Carbon::parse($restaurante->hora_apertura)->format('H:i') : '') }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Hora de Cierre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-door-closed"></i>
                            </span>
                            <input type="time" name="hora_cierre"
                                   value="{{ old('hora_cierre', $restaurante->hora_cierre ? \Carbon\Carbon::parse($restaurante->hora_cierre)->format('H:i') : '') }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 7: Descripción                       --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-text-paragraph text-warning"></i> Descripción Comercial
                </h6>
                <textarea name="descripcion" rows="4"
                          class="form-control bg-light" style="box-shadow:none; resize:none;"
                          placeholder="Escribe detalles breves sobre el menú, ambiente o historia del restaurante...">{{ old('descripcion', $restaurante->descripcion) }}</textarea>
            </div>
        </div>

        {{-- ── Botones de Control ── --}}
        <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
            <a href="{{ route('admin.restaurantes.index') }}"
               class="text-muted text-decoration-none small fw-medium">
                <i class="bi bi-chevron-left me-1"></i> Cancelar cambios
            </a>
            <button type="submit" class="btn btn-primary fw-semibold px-5 py-2 rounded-pill shadow-sm">
                <i class="bi bi-check2-circle me-1"></i> Guardar Cambios
            </button>
        </div>

    </form>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
</style>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

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

        // ─── intl-tel-input para WhatsApp ────────────────────────────────
        const inputTel = document.getElementById('whatsapp_input');
        const iti = window.intlTelInput(inputTel, {
            initialCountry: "ni",
            preferredCountries: ["ni", "cr", "hn", "gt", "sv", "mx", "us"],
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/utils.js"
        });

        // Precargar el número existente del restaurante
        const numeroActual = document.getElementById('whatsapp_full').value;
        if (numeroActual) {
            iti.setNumber('+' + numeroActual);
        }

        // Al enviar el form, guardar número completo en el campo oculto
        document.getElementById('form-edit-restaurante').addEventListener('submit', function () {
            const numeroCompleto = iti.getNumber(); // ej: +50588887777
            if (numeroCompleto) {
                document.getElementById('whatsapp_full').value = numeroCompleto.replace('+', '').replace(/\s/g, '');
            }
        });

        // ─── Preview Foto de Portada ─────────────────────────────────────
        document.getElementById('input-portada').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const actual = document.getElementById('img-portada-actual');
                if (actual.tagName === 'IMG') {
                    actual.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.id = 'img-portada-actual';
                    img.src = e.target.result;
                    img.alt = 'Nueva portada';
                    img.className = 'w-100 rounded-3 border shadow-sm';
                    img.style.cssText = 'height:180px;object-fit:cover;';
                    actual.replaceWith(img);
                }
                const preview = document.getElementById('nueva-preview-portada');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                document.getElementById('placeholder-portada').classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });

        // ─── Preview Galería individual (4 slots) ────────────────────────
        for (let i = 0; i < 4; i++) {
            (function (idx) {
                const input = document.getElementById('galeria_edit_' + idx);
                if (!input) return;
                input.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('preview-galeria-edit-' + idx);
                        const placeholder = document.getElementById('placeholder-edit-' + idx);
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        if (placeholder) placeholder.classList.add('d-none');
                    };
                    reader.readAsDataURL(file);
                });
            })(i);
        }

    });
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/intlTelInput.min.js"></script>

<script>
(function () {
    function initEditMapa() {
        const defaultLat = 12.8654;
        const defaultLng = -85.2072;

        const savedLat = document.getElementById('edit-latitud').value;
        const savedLng = document.getElementById('edit-longitud').value;

        const initLat  = savedLat ? parseFloat(savedLat) : defaultLat;
        const initLng  = savedLng ? parseFloat(savedLng) : defaultLng;
        const initZoom = savedLat ? 16 : 7;

        const mapa = L.map('edit-mapa-restaurante').setView([initLat, initLng], initZoom);

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

        document.getElementById('edit-btn-buscar-mapa').addEventListener('click', buscarDireccion);
        document.getElementById('edit-direccion-buscar').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); buscarDireccion(); }
        });

        document.getElementById('edit-btn-ir-coordenadas').addEventListener('click', function () {
            const valor = document.getElementById('edit-coordenadas-input').value.trim();
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

        document.getElementById('edit-coordenadas-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('edit-btn-ir-coordenadas').click(); }
        });

        function buscarDireccion() {
            const query = document.getElementById('edit-direccion-buscar').value.trim();
            if (!query) return;

            const btn = document.getElementById('edit-btn-buscar-mapa');
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
                document.getElementById('edit-direccion').value = data[0].display_name;
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
                    document.getElementById('edit-direccion').value = data.display_name;
                }
            })
            .catch(() => {});
        }

        function actualizarCoordenadas(lat, lng) {
            document.getElementById('edit-latitud').value  = lat.toFixed(7);
            document.getElementById('edit-longitud').value = lng.toFixed(7);
            document.getElementById('edit-coordenadas-input').value = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
            actualizarInfo(lat, lng);
        }

        function actualizarInfo(lat, lng) {
            document.getElementById('edit-mapa-coords-info').classList.remove('d-none');
            document.getElementById('edit-coords-display').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditMapa);
    } else {
        initEditMapa();
    }
})();
</script>

@endsection
