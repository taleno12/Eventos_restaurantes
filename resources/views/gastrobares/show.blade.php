@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.gastrobares.index') }}"
               class="btn btn-light border shadow-sm rounded-3 p-2 d-flex align-items-center justify-content-center"
               style="width:38px; height:38px; text-decoration:none;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h4 fw-bold mb-0" style="color: #2d3748;">
                    <i class="bi bi-cup-straw text-warning me-2"></i>{{ $gastrobar->nombre }}
                </h1>
                <p class="text-muted mb-0 small">EXPEDIENTE DE CONTROL — GASTROBAR</p>
            </div>
        </div>
        @if($gastrobar->activo ?? true)
            <span class="badge rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2"
                  style="background-color:#e6fffa; color:#047481; border:1px solid #b2f5ea; font-size:0.75rem;">
                <span class="bg-success rounded-circle" style="width:6px;height:6px;display:inline-block;"></span> Establecimiento Activo
            </span>
        @else
            <span class="badge rounded-pill bg-light text-muted border px-3 py-2 fw-semibold" style="font-size:0.75rem;">
                Inactivo / Pausado
            </span>
        @endif
    </div>

    {{-- ── Banner de Portada ── --}}
    <div class="rounded-4 overflow-hidden mb-4 position-relative shadow-sm"
         style="height:220px; background: linear-gradient(135deg, #1a202c, #2d3748);">
        @if($gastrobar->imagen_principal)
            <img src="{{ asset('storage/' . $gastrobar->imagen_principal) }}"
                 alt="Portada"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit:cover; opacity:0.4;">
        @endif
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.3) 60%, transparent 100%);"></div>
        <div class="position-absolute bottom-0 start-0 p-4 d-flex align-items-end gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center fw-black text-white shadow"
                 style="width:60px;height:60px;font-size:1.6rem;background:linear-gradient(135deg,#f59e0b,#fbbf24);border:2px solid rgba(255,255,255,0.2);flex-shrink:0;">
                {{ strtoupper(substr($gastrobar->nombre, 0, 1)) }}
            </div>
            <div class="mb-1">
                <span class="badge text-warning border mb-1"
                      style="background:rgba(234,88,12,0.2);border-color:rgba(249,115,22,0.4)!important;font-size:0.7rem;letter-spacing:1px;">
                    {{ $gastrobar->tipo_bar ?? 'Gastrobar' }}
                </span>
                <h2 class="fw-black text-white mb-0" style="font-size:1.5rem;">{{ $gastrobar->nombre }}</h2>
                <small class="text-secondary">ID: <span class="text-warning font-monospace">#{{ $gastrobar->id }}</span></small>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ══════════════ COLUMNA IZQUIERDA ══════════════ --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-4">

            {{-- ── Datos Generales ── --}}
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2"
                        style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:#2d3748;">
                        <i class="bi bi-info-circle text-warning"></i> Datos Generales
                    </h5>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Correo Electrónico</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-envelope text-secondary"></i>
                                    {{ $gastrobar->email ?? 'Sin correo registrado' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Tipo de Cocina</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-egg-fried text-warning"></i>
                                    {{ $gastrobar->tipo_cocina ?? 'No especificada' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Tipo de Música</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-music-note text-warning"></i>
                                    {{ $gastrobar->tipo_musica ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Ambiente</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-lamp text-warning"></i>
                                    {{ $gastrobar->ambiente ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Capacidad</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-people text-warning"></i>
                                    {{ $gastrobar->capacidad ? $gastrobar->capacidad . ' personas' : 'No especificada' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Ubicación</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    {{ $gastrobar->municipio->nombre ?? 'N/A' }}
                                    @if($gastrobar->departamento)
                                        , {{ $gastrobar->departamento->nombre }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-2" style="font-size:0.7rem;letter-spacing:0.5px;">Descripción Comercial</p>
                                <p class="mb-0 text-secondary fst-italic" style="font-size:0.9rem;line-height:1.6;">
                                    "{{ $gastrobar->descripcion ?? 'Este establecimiento aún no cuenta con una descripción comercial.' }}"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Cuenta del Propietario ── --}}
            @php $propietario = \App\Models\User::where('gastrobar_id', $gastrobar->id)->where('role', 'gastrobar')->first(); @endphp
            @if($propietario)
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2"
                        style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:#2d3748;">
                        <i class="bi bi-person-badge text-warning"></i> Cuenta del Propietario
                    </h5>
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center fw-black text-white shadow-sm flex-shrink-0"
                             style="width:52px;height:52px;font-size:1.3rem;background:linear-gradient(135deg,#1e293b,#334155);">
                            {{ strtoupper(substr($propietario->name, 0, 1)) }}
                        </div>
                        <div class="row g-3 flex-grow-1 w-100">
                            <div class="col-12 col-sm-6">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Nombre</p>
                                    <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                        <i class="bi bi-person text-secondary"></i>
                                        {{ $propietario->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Correo del Propietario</p>
                                    <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                        <i class="bi bi-envelope text-secondary flex-shrink-0"></i>
                                        <span class="text-truncate">{{ $propietario->email }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-3 rounded-3"
                         style="background:#fffbeb;border:1px solid #fde68a;">
                        <i class="bi bi-shield-check text-warning"></i>
                        <small class="fw-semibold" style="color:#92400e;">
                            Este usuario tiene acceso al panel de administración del gastrobar.
                        </small>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Horarios y Días de Atención ── --}}
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2"
                        style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:#2d3748;">
                        <i class="bi bi-clock text-warning"></i> Horarios y Días de Atención
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Hora de Apertura</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-clock text-success"></i>
                                    {{ $gastrobar->hora_apertura ? \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') : 'No especificada' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">Hora de Cierre</p>
                                <p class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                                    <i class="bi bi-clock-history text-danger"></i>
                                    {{ $gastrobar->hora_cierre ? \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') : 'No especificada' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Días de Atención --}}
                    @php
                        $diasSemana  = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
                        $diasNombres = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
                        $diasActivos = $gastrobar->dias_atencion ?? [];
                        if(is_string($diasActivos)) $diasActivos = json_decode($diasActivos, true) ?? [];
                    @endphp
                    <p class="text-uppercase text-muted fw-bold mb-2" style="font-size:0.7rem;letter-spacing:0.5px;">Días de Atención</p>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($diasSemana as $i => $dia)
                            <div class="d-flex flex-column align-items-center gap-1">
                                <span style="font-size:0.6rem;font-weight:700;text-transform:uppercase;color:#94a3b8;">
                                    {{ $diasNombres[$i] }}
                                </span>
                                <span class="d-flex align-items-center justify-content-center rounded-2 fw-bold"
                                      style="width:34px;height:34px;font-size:0.8rem;
                                      {{ in_array($dia, $diasActivos)
                                          ? 'background:#fbbf24;color:#1e293b;border:1px solid #f59e0b;'
                                          : 'background:#f1f5f9;color:#cbd5e1;border:1px solid #e2e8f0;' }}">
                                    {{ in_array($dia, $diasActivos) ? '✓' : '–' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Ubicación y Mapa ── --}}
            @if($gastrobar->latitud && $gastrobar->longitud)
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2"
                        style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:#2d3748;">
                        <i class="bi bi-map text-warning"></i> Ubicación del Establecimiento
                    </h5>
                    @if($gastrobar->direccion)
                        <p class="text-muted small mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-pin-map-fill text-warning"></i>
                            <span class="fw-semibold text-dark">{{ $gastrobar->direccion }}</span>
                        </p>
                    @endif
                    <div id="mapa-show-gastrobar" class="w-100 rounded-3 overflow-hidden border" style="height:320px;"></div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <p class="text-muted font-monospace d-flex align-items-center gap-2 mb-0" style="font-size:0.75rem;">
                            <i class="bi bi-crosshair text-warning"></i>
                            {{ number_format($gastrobar->latitud, 6) }}, {{ number_format($gastrobar->longitud, 6) }}
                        </p>
                        <a href="https://www.google.com/maps?q={{ $gastrobar->latitud }},{{ $gastrobar->longitud }}"
                           target="_blank"
                           class="text-decoration-none d-flex align-items-center gap-1"
                           style="font-size:0.72rem;font-weight:700;color:#7c3aed;">
                            <i class="bi bi-box-arrow-up-right"></i> Google Maps
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Galería Fotográfica ── --}}
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2"
                        style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:#2d3748;">
                        <i class="bi bi-images text-warning"></i> Galería de Álbum Fotográfico
                    </h5>

                    {{-- Foto de Portada --}}
                    <p class="text-uppercase text-muted fw-bold mb-2" style="font-size:0.7rem;letter-spacing:0.5px;">Foto de Portada</p>
                    <div class="rounded-3 overflow-hidden border mb-4 position-relative"
                         style="height:200px;background:#f1f5f9;">
                        @if($gastrobar->imagen_principal)
                            <img src="{{ asset('storage/' . $gastrobar->imagen_principal) }}"
                                 alt="Portada" class="w-100 h-100" style="object-fit:cover;">
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted gap-2">
                                <i class="bi bi-image fs-1 text-secondary opacity-25"></i>
                                <small class="fw-bold text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;">Sin foto de portada</small>
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white"
                              style="font-size:0.65rem;letter-spacing:0.5px;">
                            Imagen de Portada
                        </span>
                    </div>

                    {{-- Álbum --}}
                    @php
                        $galeria = $gastrobar->galeria ?? [];
                        if (is_string($galeria)) $galeria = json_decode($galeria, true) ?? [];
                        $galeria = array_values(array_filter($galeria));
                        $totalFotos = count($galeria);
                    @endphp
                    <p class="text-uppercase text-muted fw-bold mb-2 d-flex align-items-center gap-2"
                       style="font-size:0.7rem;letter-spacing:0.5px;">
                        Álbum
                        @if($totalFotos > 0)
                            <span class="badge fw-semibold"
                                  style="background:rgba(251,191,36,0.15);color:#92400e;border:1px solid rgba(251,191,36,0.3);font-size:0.65rem;">
                                {{ $totalFotos }} / 4 fotos
                            </span>
                        @endif
                    </p>
                    <div class="row g-3">
                        @if($totalFotos > 0)
                            @foreach($galeria as $idx => $foto)
                            <div class="col-6 col-sm-3">
                                <a href="{{ asset('storage/' . $foto) }}" target="_blank"
                                   class="d-block rounded-3 overflow-hidden border position-relative"
                                   style="aspect-ratio:1/1;background:#f1f5f9;">
                                    <img src="{{ asset('storage/' . $foto) }}"
                                         alt="Foto {{ $idx + 1 }}"
                                         class="w-100 h-100"
                                         style="object-fit:cover;transition:transform 0.3s;">
                                    <span class="position-absolute bottom-0 end-0 m-1 badge bg-dark bg-opacity-60 text-white font-monospace"
                                          style="font-size:0.6rem;">#{{ $idx + 1 }}</span>
                                </a>
                            </div>
                            @endforeach
                            @for($j = $totalFotos; $j < 4; $j++)
                            <div class="col-6 col-sm-3">
                                <div class="rounded-3 d-flex flex-column align-items-center justify-content-center text-muted"
                                     style="aspect-ratio:1/1;background:#f8fafc;border:2px dashed #cbd5e1;">
                                    <i class="bi bi-plus-lg mb-1" style="font-size:1rem;opacity:0.35;"></i>
                                    <small class="fw-bold text-uppercase" style="font-size:0.6rem;letter-spacing:0.5px;opacity:0.35;">Foto {{ $j + 1 }}</small>
                                </div>
                            </div>
                            @endfor
                        @else
                            @for($j = 0; $j < 4; $j++)
                            <div class="col-6 col-sm-3">
                                <div class="rounded-3 d-flex flex-column align-items-center justify-content-center text-muted"
                                     style="aspect-ratio:1/1;background:#f8fafc;border:2px dashed #cbd5e1;">
                                    <i class="bi bi-plus-lg mb-1" style="font-size:1rem;opacity:0.35;"></i>
                                    <small class="fw-bold text-uppercase" style="font-size:0.6rem;letter-spacing:0.5px;opacity:0.35;">Foto {{ $j + 1 }}</small>
                                </div>
                            </div>
                            @endfor
                            <div class="col-12">
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Este gastrobar aún no tiene fotos en el álbum.
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>

        {{-- ══════════════ COLUMNA DERECHA (Sidebar) ══════════════ --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            {{-- ── Ecosistema Digital / Redes ── --}}
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2"
                        style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:#2d3748;">
                        <i class="bi bi-share text-warning"></i> Ecosistema Digital
                    </h5>
                    <div class="d-flex flex-column gap-2">

                        {{-- WhatsApp --}}
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-whatsapp text-success fs-5"></i> WhatsApp
                            </span>
                            @if($gastrobar->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $gastrobar->whatsapp) }}"
                                   target="_blank"
                                   class="badge fw-semibold text-decoration-none"
                                   style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;font-size:0.72rem;">
                                    {{ $gastrobar->whatsapp }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.6rem;"></i>
                                </a>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Ausente</span>
                            @endif
                        </div>

                        {{-- Teléfono --}}
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-telephone text-secondary fs-5"></i> Teléfono
                            </span>
                            @if($gastrobar->telefono)
                                <span class="badge fw-semibold"
                                      style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;font-size:0.72rem;">
                                    {{ $gastrobar->telefono }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Ausente</span>
                            @endif
                        </div>

                        {{-- Instagram --}}
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-instagram text-danger fs-5"></i> Instagram
                            </span>
                            @if($gastrobar->instagram)
                                <a href="{{ $gastrobar->instagram }}" target="_blank"
                                   class="badge fw-semibold text-decoration-none"
                                   style="background:#fce7f3;color:#9d174d;border:1px solid #fbcfe8;font-size:0.72rem;">
                                    Ver Perfil <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.6rem;"></i>
                                </a>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Ausente</span>
                            @endif
                        </div>

                        {{-- TikTok --}}
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-tiktok fs-5" style="color:#111;"></i> TikTok
                            </span>
                            @if($gastrobar->tiktok)
                                <a href="{{ $gastrobar->tiktok }}" target="_blank"
                                   class="badge fw-semibold text-decoration-none"
                                   style="background:#f1f5f9;color:#1e293b;border:1px solid #cbd5e1;font-size:0.72rem;">
                                    Ver Perfil <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.6rem;"></i>
                                </a>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Ausente</span>
                            @endif
                        </div>

                        {{-- Facebook --}}
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-facebook text-primary fs-5"></i> Facebook
                            </span>
                            @if($gastrobar->facebook)
                                <a href="{{ $gastrobar->facebook }}" target="_blank"
                                   class="badge fw-semibold text-decoration-none"
                                   style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.72rem;">
                                    Ver Fanpage <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.6rem;"></i>
                                </a>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Ausente</span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Resumen Multimedia ── --}}
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2"
                        style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:#2d3748;">
                        <i class="bi bi-camera-video text-warning"></i> Resumen Multimedia
                    </h5>
                    <div class="d-flex flex-column gap-2">

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-image text-warning"></i> Foto de Portada
                            </span>
                            @if($gastrobar->imagen_principal)
                                <span class="badge fw-semibold"
                                      style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;font-size:0.7rem;">
                                    <i class="bi bi-check-lg me-1"></i>Cargada
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Sin portada</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-images text-warning"></i> Álbum
                            </span>
                            @if($totalFotos > 0)
                                <span class="badge fw-semibold"
                                      style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.7rem;">
                                    {{ $totalFotos }} / 4 fotos
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Sin fotos</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-telephone text-warning"></i> Teléfono
                            </span>
                            @if($gastrobar->telefono)
                                <span class="badge fw-semibold"
                                      style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;font-size:0.7rem;">
                                    <i class="bi bi-check-lg me-1"></i>Registrado
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Sin teléfono</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-person-badge text-warning"></i> Propietario
                            </span>
                            @if(isset($propietario) && $propietario)
                                <span class="badge fw-semibold"
                                      style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;font-size:0.7rem;">
                                    <i class="bi bi-check-lg me-1"></i>Asignado
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Sin asignar</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                             style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase" style="font-size:0.75rem;">
                                <i class="bi bi-geo-alt text-warning"></i> Ubicación en Mapa
                            </span>
                            @if($gastrobar->latitud && $gastrobar->longitud)
                                <span class="badge fw-semibold"
                                      style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;font-size:0.7rem;">
                                    <i class="bi bi-check-lg me-1"></i>Registrada
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Sin coordenadas</span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Metadatos de Registro ── --}}
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="background:#1e293b;">
                <div class="card-body p-4 position-relative">
                    <i class="bi bi-cup-straw position-absolute"
                       style="font-size:5rem;color:rgba(255,255,255,0.04);right:-10px;bottom:-10px;pointer-events:none;"></i>
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">
                        <i class="bi bi-clock-history text-warning"></i> Metadatos de Registro
                    </h5>
                    <p class="mb-3" style="font-size:0.8rem;color:#64748b;line-height:1.6;">
                        Este expediente forma parte del registro central de la plataforma.
                    </p>
                    <div class="pt-3 border-top d-flex flex-column gap-1" style="border-color:#334155!important;">
                        <span class="font-monospace" style="font-size:0.72rem;color:#64748b;">
                            Creado: {{ $gastrobar->created_at ? $gastrobar->created_at->format('d/m/Y - h:i A') : 'N/A' }}
                        </span>
                        <span class="font-monospace" style="font-size:0.72rem;color:#64748b;">
                            Última mod: {{ $gastrobar->updated_at ? $gastrobar->updated_at->format('d/m/Y - h:i A') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Barra de Acciones ── --}}
    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between border-top pt-4 mt-4 gap-3">
        <a href="{{ route('admin.gastrobares.index') }}"
           class="text-muted text-decoration-none d-flex align-items-center gap-2 fw-bold small text-uppercase"
           style="letter-spacing:0.5px;">
            <i class="bi bi-chevron-left" style="font-size:0.7rem;"></i> Volver al panel de control
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.gastrobares.edit', $gastrobar->id) }}"
               class="btn btn-dark px-4 py-2 rounded-3 fw-bold d-flex align-items-center gap-2 shadow-sm text-decoration-none"
               style="font-size:0.8rem;letter-spacing:0.5px;">
                <i class="bi bi-pencil-square"></i> Editar Parámetros
            </a>
            <form action="{{ route('admin.gastrobares.destroy', $gastrobar->id) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar este gastrobar? Esta acción no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-outline-danger fw-bold px-4 py-2 rounded-3"
                        style="font-size:0.8rem;">
                    <i class="bi bi-trash me-1"></i> Eliminar
                </button>
            </form>
        </div>
    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if($gastrobar->latitud && $gastrobar->longitud)
<script>
(function () {
    const lat = {{ $gastrobar->latitud }};
    const lng = {{ $gastrobar->longitud }};

    const mapa = L.map('mapa-show-gastrobar', {
        zoomControl: true,
        scrollWheelZoom: false
    }).setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);

    const iconoNaranja = L.divIcon({
        html: '<div style="background:#fbbf24;width:20px;height:20px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 20],
        className: ''
    });

    const popupNombre    = @json($gastrobar->nombre);
    const popupDireccion = @json($gastrobar->direccion);
    const popupContent   = `<strong>${popupNombre}</strong>${popupDireccion ? `<br><span style="font-size:11px;color:#6b7280">${popupDireccion}</span>` : ''}`;

    L.marker([lat, lng], { icon: iconoNaranja })
        .addTo(mapa)
        .bindPopup(popupContent)
        .openPopup();
})();
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
