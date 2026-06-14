@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.gastrobares.index') }}"
           class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
           style="width: 38px; height: 38px;">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Gastrobar
            </h1>
            <p class="text-muted small mb-0">Modifica la información de <span class="fw-semibold text-dark">{{ $gastrobar->nombre }}</span>.</p>
        </div>
    </div>

    {{-- Errores globales --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
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

    <form id="form-gastrobar" action="{{ route('admin.gastrobares.update', $gastrobar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 1: Datos Generales                   --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-info-circle text-warning"></i> Datos Generales: {{ $gastrobar->nombre }}
                </h6>

                <div class="row g-4">

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark small">Nombre del Gastrobar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-cup-straw"></i></span>
                            <input type="text" name="nombre" value="{{ old('nombre', $gastrobar->nombre) }}" required maxlength="100"
                                   placeholder="Nombre del gastrobar"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Correo Electrónico <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email', $gastrobar->email) }}"
                                   placeholder="correo@gastrobar.com"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Tipo de Bar</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-tropical-storm"></i></span>
                            <select name="tipo_bar" class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                <option value="">Seleccionar tipo...</option>
                                @foreach(['Cocktail Bar','Sports Bar','Rooftop Bar','Lounge Bar','Bar de Tapas','Bar de Vinos','Bar de Cervezas','Otro'] as $tipo)
                                    <option value="{{ $tipo }}" @selected(old('tipo_bar', $gastrobar->tipo_bar) == $tipo)>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Tipo de Cocina</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-egg-fried"></i></span>
                            <input type="text" name="tipo_cocina" value="{{ old('tipo_cocina', $gastrobar->tipo_cocina) }}"
                                   placeholder="Ej: Tapas, Bocas, Fusión..."
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Capacidad de Personas</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-people"></i></span>
                            <input type="number" name="capacidad" value="{{ old('capacidad', $gastrobar->capacidad) }}" min="1"
                                   placeholder="Ej: 80"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 2: Ambiente y Horarios               --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-music-note-beamed text-warning"></i> Ambiente y Horarios
                </h6>

                <div class="row g-4">

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Tipo de Música</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-music-note"></i></span>
                            <select name="tipo_musica" class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                <option value="">Seleccionar música...</option>
                                @foreach(['Jazz','Electrónica','Reggaeton','Salsa','Rock','En Vivo','Variada'] as $musica)
                                    <option value="{{ $musica }}" @selected(old('tipo_musica', $gastrobar->tipo_musica) == $musica)>{{ $musica }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Ambiente</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lamp"></i></span>
                            <select name="ambiente" class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                <option value="">Seleccionar ambiente...</option>
                                @foreach(['Interior','Exterior','Rooftop','Mixto'] as $amb)
                                    <option value="{{ $amb }}" @selected(old('ambiente', $gastrobar->ambiente) == $amb)>{{ $amb }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Hora de Apertura</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-clock"></i></span>
                            <input type="time" name="hora_apertura"
                                   value="{{ old('hora_apertura', $gastrobar->hora_apertura ? \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('H:i') : '') }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Hora de Cierre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-clock-history"></i></span>
                            <input type="time" name="hora_cierre"
                                   value="{{ old('hora_cierre', $gastrobar->hora_cierre ? \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('H:i') : '') }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark small">Días de Atención</label>
                        @php
                            $diasGuardados = old('dias_atencion', $gastrobar->dias_atencion ?? []);
                            if (is_string($diasGuardados)) $diasGuardados = json_decode($diasGuardados, true) ?? [];
                        @endphp
                        <div class="row g-2">
                            @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $dia)
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="form-check">
                                    <input type="checkbox" name="dias_atencion[]" value="{{ $dia }}"
                                           id="dia_{{ $dia }}"
                                           {{ in_array($dia, $diasGuardados) ? 'checked' : '' }}
                                           class="form-check-input" style="accent-color:#ffc107;">
                                    <label class="form-check-label small text-capitalize" for="dia_{{ $dia }}">
                                        {{ ucfirst($dia) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 3: Ubicación Geográfica              --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-map text-warning"></i> Ubicación Geográfica
                </h6>

                <div class="row g-4">

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Departamento <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                            <select id="departamento_id" name="departamento_id" required
                                    class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                <option value="" disabled>Seleccionar...</option>
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->id }}" @selected(old('departamento_id', $gastrobar->departamento_id) == $dep->id)>{{ $dep->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Municipio <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                            <select id="municipio_id" name="municipio_id" required
                                    data-old-muni="{{ old('municipio_id', $gastrobar->municipio_id) }}"
                                    class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;">
                                <option value="" disabled selected>Cargando municipios...</option>
                                @foreach($municipios as $mun)
                                    <option value="{{ $mun->id }}" @selected(old('municipio_id', $gastrobar->municipio_id) == $mun->id)>{{ $mun->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 4: Redes Sociales                    --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-share text-warning"></i> Contacto y Redes Sociales
                </h6>

                <div class="row g-4">

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#25d366;"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $gastrobar->whatsapp) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="+505 8888-8888">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Instagram <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#e1306c;"><i class="bi bi-instagram"></i></span>
                            <input type="url" name="instagram" value="{{ old('instagram', $gastrobar->instagram) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://instagram.com/usuario">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Facebook <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color:#1877f2;"><i class="bi bi-facebook"></i></span>
                            <input type="url" name="facebook" value="{{ old('facebook', $gastrobar->facebook) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://facebook.com/pagina">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">TikTok <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-dark"><i class="bi bi-tiktok"></i></span>
                            <input type="url" name="tiktok" value="{{ old('tiktok', $gastrobar->tiktok) }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;" placeholder="https://tiktok.com/@usuario">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 5: Cuenta del Propietario            --}}
        {{-- ══════════════════════════════════════════ --}}
        @php $propietario = $gastrobar->propietario; @endphp
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

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Nombre del Propietario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="propietario_nombre"
                                   value="{{ old('propietario_nombre', $propietario->name ?? '') }}"
                                   required placeholder="Nombre completo"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Correo del Propietario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="propietario_email"
                                   value="{{ old('propietario_email', $propietario->email ?? '') }}"
                                   required placeholder="correo@propietario.com"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Nueva Contraseña <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="propietario_password" id="gb_password" minlength="8"
                                   placeholder="Dejar en blanco para no cambiar"
                                   class="form-control bg-light border-start-0 border-end-0 ps-0" style="box-shadow:none;">
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted"
                                    onclick="togglePassword('gb_password', this)" title="Mostrar/ocultar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark small">Confirmar Contraseña <span class="text-muted fw-normal">(opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="propietario_password_confirmation" id="gb_password_confirm" minlength="8"
                                   placeholder="Repetir nueva contraseña"
                                   class="form-control bg-light border-start-0 border-end-0 ps-0" style="box-shadow:none;">
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted"
                                    onclick="togglePassword('gb_password_confirm', this)" title="Mostrar/ocultar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 6: Foto de Portada                   --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-image text-warning"></i> Foto de Portada
                </h6>

                <div class="row g-4 align-items-start">

                    <div class="col-12 col-md-6">
                        <p class="small fw-semibold text-muted mb-2">Imagen actual:</p>
                        @if($gastrobar->imagen_principal)
                            <img id="img-portada-actual"
                                 src="{{ asset('storage/' . $gastrobar->imagen_principal) }}"
                                 alt="Portada actual"
                                 class="w-100 rounded-3 border shadow-sm" style="height: 180px; object-fit: cover;">
                        @else
                            <div id="img-portada-actual"
                                 class="w-100 rounded-3 d-flex flex-column align-items-center justify-content-center text-muted gap-2"
                                 style="height: 180px; border: 2px dashed #dee2e6;">
                                <i class="bi bi-image fs-2 text-secondary"></i>
                                <span class="small">Sin imagen de portada</span>
                            </div>
                        @endif
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="small fw-semibold text-muted mb-2">Reemplazar imagen <span class="fw-normal">(opcional)</span></p>
                        <label for="input-portada" class="w-100 cursor-pointer">
                            <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
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
        {{-- CARD 7: Galería de Fotos                  --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-images text-warning"></i> Fotos del Álbum
                    <span class="fw-normal text-secondary text-capitalize" style="font-size: 0.7rem;">(Máx. 4 — las nuevas reemplazan las existentes)</span>
                </h6>

                @php
                    $galeriaActual = $gastrobar->galeria ?? [];
                    if (is_string($galeriaActual)) $galeriaActual = json_decode($galeriaActual, true) ?? [];
                @endphp
                @if(count($galeriaActual))
                    <p class="small fw-semibold text-muted mt-3 mb-2">Imágenes actuales del álbum:</p>
                    <div class="row g-3 mb-4">
                        @foreach($galeriaActual as $foto)
                            <div class="col-6 col-sm-3">
                                <div class="rounded-3 border overflow-hidden shadow-sm" style="height: 90px;">
                                    <img src="{{ asset('storage/' . $foto) }}" alt="Foto álbum"
                                         class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="small text-muted mt-3 mb-3">
                        <i class="bi bi-info-circle text-secondary me-1"></i>
                        Este gastrobar aún no tiene fotos en el álbum.
                    </p>
                @endif

                <div class="row g-3">
                    @for($i = 0; $i < 4; $i++)
                    <div class="col-6 col-sm-3">
                        <label for="galeria_{{ $i }}" class="w-100 cursor-pointer">
                            <div class="rounded-3 bg-light d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                 style="aspect-ratio: 1/1; border: 2px dashed #dee2e6; cursor: pointer; transition: border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#ffc107'" onmouseout="this.style.borderColor='#dee2e6'">
                                <input type="file" name="galeria[]" id="galeria_{{ $i }}" accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer; z-index:10;">
                                <div id="placeholder-{{ $i }}" class="text-center">
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
        {{-- CARD 8: Mapa                              --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-geo-alt text-warning"></i> Ubicación en el Mapa
                </h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">Buscar Dirección</label>
                    <div class="d-flex gap-2">
                        <div class="input-group flex-1">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="direccion-buscar"
                                   placeholder="Ej: Gastrobar La Noche, Managua, Nicaragua"
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

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">
                        Dirección Exacta <span class="text-muted fw-normal">(se actualiza al buscar o hacer clic en el mapa)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="color:#f97316;"><i class="bi bi-pin-map-fill"></i></span>
                        <input type="text" name="direccion" id="direccion"
                               value="{{ old('direccion', $gastrobar->direccion) }}"
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
                            <input type="text" id="coordenadas-input"
                                   placeholder="Ej: 12.865400, -85.207200"
                                   value="{{ $gastrobar->latitud && $gastrobar->longitud ? $gastrobar->latitud . ', ' . $gastrobar->longitud : '' }}"
                                   class="form-control bg-light border-start-0 ps-0" style="box-shadow:none;">
                        </div>
                        <button type="button" id="btn-ir-coordenadas"
                                class="btn btn-outline-warning fw-semibold text-dark" style="white-space:nowrap;">
                            <i class="bi bi-crosshair me-1"></i> Ir al mapa
                        </button>
                    </div>
                    <p class="small text-muted mt-1 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Escribe las coordenadas separadas por coma y presiona "Ir al mapa", o simplemente haz clic en el mapa.
                    </p>
                </div>

                <input type="hidden" name="latitud"  id="latitud"  value="{{ old('latitud', $gastrobar->latitud) }}">
                <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $gastrobar->longitud) }}">

                <div id="mapa-gastrobar" class="w-100 rounded-3 border shadow-sm" style="height: 340px;"></div>

                <p id="mapa-coords-info" class="small text-muted mt-2 d-none">
                    <i class="bi bi-crosshair text-warning me-1"></i>
                    Coordenadas guardadas: <span id="coords-display" class="font-monospace text-dark ms-1"></span>
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- CARD 9: Descripción                       --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-text-paragraph text-warning"></i> Descripción Comercial
                </h6>
                <textarea name="descripcion" rows="4"
                          class="form-control bg-light" style="box-shadow:none; resize:none;"
                          placeholder="Breve reseña del ambiente, propuesta de coctelería...">{{ old('descripcion', $gastrobar->descripcion) }}</textarea>
            </div>
        </div>

        {{-- ── Botones de Control ── --}}
        <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
            <a href="{{ route('admin.gastrobares.index') }}"
               class="text-muted text-decoration-none small fw-medium">
                <i class="bi bi-chevron-left me-1"></i> Cancelar cambios
            </a>
            <button type="submit" id="btn-submit"
                    class="btn btn-warning fw-semibold px-5 py-2 rounded-pill shadow-sm text-dark">
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

    // ── Preview Portada ──
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

    // ── Preview Galería individual ──
    for (let i = 0; i < 4; i++) {
        (function (idx) {
            document.getElementById('galeria_' + idx).addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const preview     = document.getElementById('preview-galeria-' + idx);
                    const placeholder = document.getElementById('placeholder-' + idx);
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (placeholder) placeholder.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            });
        })(i);
    }

    // ── Departamentos → Municipios + Submit ──
    document.addEventListener('DOMContentLoaded', function () {
        const depSelect  = document.getElementById('departamento_id');
        const muniSelect = document.getElementById('municipio_id');

        function cargarMunicipios(depId, muniSeleccionado = null) {
            if (!depId) return;
            muniSelect.innerHTML = '<option value="">Cargando...</option>';
            fetch(`/api/departamentos/${depId}/municipios`)
                .then(r => r.json())
                .then(data => {
                    muniSelect.innerHTML = '<option value="" disabled>Seleccionar municipio...</option>';
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

        const deptoInicial = depSelect.value;
        const muniInicial  = "{{ old('municipio_id', $gastrobar->municipio_id) }}";
        if (deptoInicial) cargarMunicipios(deptoInicial, muniInicial);

        document.getElementById('form-gastrobar').addEventListener('submit', function () {
            muniSelect.disabled = false;
            const btn = document.getElementById('btn-submit');
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

        const mapa = L.map('mapa-gastrobar').setView([initLat, initLng], initZoom);

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

        document.getElementById('btn-ir-coordenadas').addEventListener('click', function () {
            const valor  = document.getElementById('coordenadas-input').value.trim();
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

        document.getElementById('coordenadas-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('btn-ir-coordenadas').click(); }
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
            document.getElementById('coordenadas-input').value = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
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

@endsection
