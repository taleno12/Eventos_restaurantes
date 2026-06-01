@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.gastrobares.index') }}"
               class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
               style="width: 38px; height: 38px;">
                <i class="bi bi-arrow-left text-secondary"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold text-dark">
                    <i class="bi bi-cup-straw text-warning me-2"></i> {{ $gastrobar->nombre }}
                </h1>
                <p class="text-muted small mb-0">Detalle del gastrobar registrado en el sistema.</p>
            </div>
        </div>
        <a href="{{ route('admin.gastrobares.edit', $gastrobar->id) }}"
           class="btn btn-warning fw-semibold px-4 rounded-pill text-dark shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Editar Gastrobar
        </a>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- HERO — Portada                            --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden position-relative"
         style="min-height: 300px; background-color: #18181b;">

        {{-- Imagen de fondo --}}
        @if($gastrobar->imagen_principal)
            <img src="{{ asset('storage/' . $gastrobar->imagen_principal) }}"
                 alt="{{ $gastrobar->nombre }}"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit: cover; opacity: 0.35;">
        @endif

        {{-- Degradado inferior --}}
        <div class="position-absolute bottom-0 start-0 w-100 h-100"
             style="background: linear-gradient(to top, rgba(10,10,10,0.92) 0%, rgba(24,24,27,0.55) 60%, transparent 100%);"></div>

        {{-- Contenido sobre la imagen --}}
        <div class="position-relative p-4 p-md-5 d-flex flex-column justify-content-end" style="min-height: 300px; z-index: 2;">

            {{-- Badges --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @if($gastrobar->tipo_bar)
                    <span class="badge rounded-pill fw-bold px-3 py-2"
                          style="font-size: 0.65rem; letter-spacing: 0.08em; background: rgba(147,51,234,0.25); border: 1px solid rgba(168,85,247,0.4); color: #c4b5fd;">
                        <i class="bi bi-cup me-1"></i>{{ $gastrobar->tipo_bar }}
                    </span>
                @endif
                @if($gastrobar->ambiente)
                    <span class="badge rounded-pill fw-bold px-3 py-2"
                          style="font-size: 0.65rem; letter-spacing: 0.08em; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #d1d5db;">
                        <i class="bi bi-lamp me-1"></i>{{ $gastrobar->ambiente }}
                    </span>
                @endif
                @if($gastrobar->tipo_musica)
                    <span class="badge rounded-pill fw-bold px-3 py-2"
                          style="font-size: 0.65rem; letter-spacing: 0.08em; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #d1d5db;">
                        <i class="bi bi-music-note me-1"></i>{{ $gastrobar->tipo_musica }}
                    </span>
                @endif
            </div>

            <h2 class="fw-bold text-white mb-2" style="font-size: 2.4rem; line-height: 1.1;">
                {{ $gastrobar->nombre }}
            </h2>

            @if($gastrobar->descripcion)
                <p class="mb-0" style="color: #9ca3af; max-width: 640px; font-size: 0.9rem; line-height: 1.6;">
                    {{ $gastrobar->descripcion }}
                </p>
            @endif

            {{-- Horario --}}
            @if($gastrobar->hora_apertura || $gastrobar->hora_cierre)
                <div class="d-flex align-items-center gap-2 mt-3" style="color: #9ca3af; font-size: 0.85rem;">
                    <i class="bi bi-clock" style="color: #a78bfa;"></i>
                    <span>
                        @if($gastrobar->hora_apertura && $gastrobar->hora_cierre)
                            {{ \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') }}
                            — {{ \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') }}
                        @elseif($gastrobar->hora_apertura)
                            Abre a las {{ \Carbon\Carbon::parse($gastrobar->hora_apertura)->format('g:i A') }}
                        @else
                            Cierra a las {{ \Carbon\Carbon::parse($gastrobar->hora_cierre)->format('g:i A') }}
                        @endif
                    </span>
                </div>
            @endif

        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- GRID: Columna info + Columna mapa/galería --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="row g-4">

        {{-- ── Columna Izquierda ── --}}
        <div class="col-12 col-md-4 d-flex flex-column gap-4">

            {{-- Contacto --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-person-lines-fill text-warning"></i> Contacto
                    </h6>

                    @if($gastrobar->email)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="d-flex align-items-center justify-content-center rounded-3 bg-light flex-shrink-0"
                                  style="width:34px;height:34px;">
                                <i class="bi bi-envelope" style="color: #9333ea; font-size: 0.8rem;"></i>
                            </span>
                            <a href="mailto:{{ $gastrobar->email }}"
                               class="text-dark text-decoration-none small" style="word-break:break-all;">
                                {{ $gastrobar->email }}
                            </a>
                        </div>
                    @endif

                    @if($gastrobar->whatsapp)
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                  style="width:34px;height:34px;background:#f0fdf4;">
                                <i class="bi bi-whatsapp" style="color:#25d366;font-size:0.8rem;"></i>
                            </span>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $gastrobar->whatsapp) }}"
                               target="_blank" class="text-dark text-decoration-none small">
                                {{ $gastrobar->whatsapp }}
                            </a>
                        </div>
                    @endif

                    @if(!$gastrobar->email && !$gastrobar->whatsapp)
                        <p class="text-muted small fst-italic mb-0">Sin datos de contacto registrados.</p>
                    @endif
                </div>
            </div>

            {{-- Características --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-info-circle text-warning"></i> Características
                    </h6>

                    @if($gastrobar->tipo_cocina)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                  style="width:34px;height:34px;background:#fffbeb;">
                                <i class="bi bi-egg-fried" style="color:#f59e0b;font-size:0.8rem;"></i>
                            </span>
                            <div>
                                <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Tipo de Cocina</p>
                                <p class="mb-0 small text-dark">{{ $gastrobar->tipo_cocina }}</p>
                            </div>
                        </div>
                    @endif

                    @if($gastrobar->capacidad)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                  style="width:34px;height:34px;background:#eff6ff;">
                                <i class="bi bi-people" style="color:#3b82f6;font-size:0.8rem;"></i>
                            </span>
                            <div>
                                <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Capacidad</p>
                                <p class="mb-0 small text-dark">{{ $gastrobar->capacidad }} personas</p>
                            </div>
                        </div>
                    @endif

                    @if($gastrobar->municipio)
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                  style="width:34px;height:34px;background:#fff1f2;">
                                <i class="bi bi-geo-alt" style="color:#f43f5e;font-size:0.8rem;"></i>
                            </span>
                            <div>
                                <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Ubicación</p>
                                <p class="mb-0 small text-dark">
                                    {{ $gastrobar->municipio->nombre }}
                                    @if($gastrobar->municipio->departamento)
                                        , {{ $gastrobar->municipio->departamento->nombre }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Días de Atención --}}
            @php
                $diasSemana  = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
                $diasNombres = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
                $diasActivos = $gastrobar->dias_atencion ?? [];
                if(is_string($diasActivos)) $diasActivos = json_decode($diasActivos, true) ?? [];
            @endphp
            @if(count($diasActivos))
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-calendar-week text-warning"></i> Días de Atención
                        </h6>
                        <div class="d-flex justify-content-between gap-1">
                            @foreach($diasSemana as $i => $dia)
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="text-muted" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;">
                                        {{ $diasNombres[$i] }}
                                    </span>
                                    <span class="d-flex align-items-center justify-content-center rounded-2 fw-bold"
                                          style="width:30px;height:30px;font-size:0.75rem;
                                          {{ in_array($dia, $diasActivos)
                                              ? 'background:#7c3aed;color:#fff;'
                                              : 'background:#f3f4f6;color:#d1d5db;' }}">
                                        {{ in_array($dia, $diasActivos) ? '✓' : '–' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Redes Sociales --}}
            @if($gastrobar->instagram || $gastrobar->facebook || $gastrobar->tiktok)
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-share text-warning"></i> Redes Sociales
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @if($gastrobar->instagram)
                                <a href="{{ $gastrobar->instagram }}" target="_blank"
                                   class="btn btn-sm fw-bold text-white text-decoration-none px-3"
                                   style="background: linear-gradient(135deg, #ec4899, #9333ea); border: none; border-radius: 10px;">
                                    <i class="bi bi-instagram me-1"></i> Instagram
                                </a>
                            @endif
                            @if($gastrobar->facebook)
                                <a href="{{ $gastrobar->facebook }}" target="_blank"
                                   class="btn btn-sm fw-bold text-white text-decoration-none px-3"
                                   style="background:#1877f2; border: none; border-radius: 10px;">
                                    <i class="bi bi-facebook me-1"></i> Facebook
                                </a>
                            @endif
                            @if($gastrobar->tiktok)
                                <a href="{{ $gastrobar->tiktok }}" target="_blank"
                                   class="btn btn-sm fw-bold text-white text-decoration-none px-3"
                                   style="background:#18181b; border: none; border-radius: 10px;">
                                    <i class="bi bi-tiktok me-1"></i> TikTok
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── Columna Derecha ── --}}
        <div class="col-12 col-md-8 d-flex flex-column gap-4">

            {{-- Mapa --}}
            @if($gastrobar->latitud && $gastrobar->longitud)
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4 pb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="text-uppercase text-muted fw-bold mb-0 d-flex align-items-center gap-2"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="bi bi-geo-alt text-warning"></i> Ubicación en el Mapa
                            </h6>
                            <a href="https://www.google.com/maps?q={{ $gastrobar->latitud }},{{ $gastrobar->longitud }}"
                               target="_blank"
                               class="text-decoration-none d-flex align-items-center gap-1"
                               style="font-size:0.72rem;font-weight:700;color:#7c3aed;">
                                <i class="bi bi-box-arrow-up-right"></i> Google Maps
                            </a>
                        </div>
                        @if($gastrobar->direccion)
                            <p class="small text-muted mb-3 d-flex align-items-start gap-2">
                                <i class="bi bi-pin-map-fill text-warning mt-1 flex-shrink-0"></i>
                                {{ $gastrobar->direccion }}
                            </p>
                        @endif
                    </div>
                    <div id="mapa-show" style="height: 320px;"></div>
                </div>
            @endif

            {{-- Galería --}}
            @php
                $galeria = $gastrobar->galeria ?? [];
                if(is_string($galeria)) $galeria = json_decode($galeria, true) ?? [];
            @endphp
            @if(count($galeria))
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-images text-warning"></i> Galería de Fotos
                        </h6>
                        <div class="row g-3">
                            @foreach($galeria as $foto)
                                <div class="{{ count($galeria) > 2 ? 'col-6 col-sm-3' : 'col-6' }}">
                                    <a href="{{ asset('storage/' . $foto) }}" target="_blank"
                                       class="d-block rounded-3 overflow-hidden position-relative"
                                       style="aspect-ratio: 1/1;">
                                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto galería"
                                             class="w-100 h-100 foto-galeria-hover"
                                             style="object-fit: cover; transition: transform 0.3s;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- Footer de acciones                        --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-body p-4 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
            <p class="text-muted small mb-0">
                <i class="bi bi-clock me-1"></i>
                Registrado: {{ $gastrobar->created_at->format('d/m/Y') }}
                @if($gastrobar->updated_at && $gastrobar->updated_at != $gastrobar->created_at)
                    · Actualizado: {{ $gastrobar->updated_at->diffForHumans() }}
                @endif
            </p>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.gastrobares.edit', $gastrobar->id) }}"
                   class="btn btn-warning fw-semibold px-4 rounded-pill text-dark shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
                <form action="{{ route('admin.gastrobares.destroy', $gastrobar->id) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este gastrobar? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-outline-danger fw-semibold px-4 rounded-pill">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<style>
    .foto-galeria-hover:hover { transform: scale(1.05); }
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@if($gastrobar->latitud && $gastrobar->longitud)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $gastrobar->latitud }};
        const lng = {{ $gastrobar->longitud }};

        const mapa = L.map('mapa-show', { zoomControl: true, scrollWheelZoom: false })
                      .setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);

        const iconoNaranja = L.divIcon({
            html: '<div style="background:#ffc107;width:22px;height:22px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,0.35)"></div>',
            iconSize: [22, 22],
            iconAnchor: [11, 22],
            className: ''
        });

        L.marker([lat, lng], { icon: iconoNaranja })
         .addTo(mapa)
         .bindPopup(`<strong style="font-family:sans-serif;">{{ addslashes($gastrobar->nombre) }}</strong>
                     @if($gastrobar->direccion)<br><span style="font-size:11px;color:#666;">{{ addslashes(Str::limit($gastrobar->direccion, 60)) }}</span>@endif`)
         .openPopup();
    });
</script>
@endif

@endsection
