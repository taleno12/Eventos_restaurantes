<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $restaurante->nombre }} | Gastro Nicaragua</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            :root {
                --orange: #ea580c;
                --orange-light: #fed7aa;
                --dark: #0c0a09;
                --stone-soft: #fafaf9;
            }

            body {
                font-family: 'Instrument Sans', sans-serif;
                overflow-x: hidden;
                background: var(--stone-soft);
            }

            .premium-title { font-family: 'Playfair Display', serif; }

            /* ── HERO ── */
            .hero-section {
                position: relative;
                height: 65vh;          /* ← reducido de 92vh */
                min-height: 420px;     /* ← reducido de 580px */
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                overflow: hidden;
            }

            .hero-bg {
                position: absolute;
                inset: 0;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                transform: scale(1.04);
                transition: transform 8s ease;
            }

            .hero-section:hover .hero-bg { transform: scale(1); }

            .hero-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    to top,
                    rgba(12, 10, 9, 0.92) 0%,
                    rgba(12, 10, 9, 0.55) 45%,
                    rgba(12, 10, 9, 0.15) 100%
                );
            }

            .hero-content {
                position: relative;
                z-index: 10;
                padding: 0 2rem 4rem;
                max-width: 72rem;
                margin: 0 auto;
                width: 100%;
            }

            /* ── NAV ── */
            .nav-glass {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                z-index: 50;
                background: linear-gradient(to bottom, rgba(12,10,9,0.7) 0%, transparent 100%);
                backdrop-filter: blur(0px);
            }

            /* ── BADGE ── */
            .badge-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 16px;
                border-radius: 999px;
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            /* ── CARDS ── */
            .info-card {
                background: white;
                border-radius: 24px;
                border: 1px solid rgba(231,229,228,0.6);
                box-shadow: 0 4px 24px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .info-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 32px rgba(0,0,0,0.07);
            }

            /* ── STAT CHIPS ── */
            .stat-chip {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 18px 20px;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            /* ── SOCIAL ICONS ── */
            .social-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 52px;
                height: 52px;
                border-radius: 16px;
                transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
                text-decoration: none;
                font-size: 20px;
            }

            .social-btn:hover { transform: translateY(-4px) scale(1.08); }

            .social-btn.whatsapp  { background: #dcfce7; color: #16a34a; }
            .social-btn.whatsapp:hover  { background: #22c55e; color: white; box-shadow: 0 8px 24px rgba(34,197,94,0.4); }
            .social-btn.instagram { background: #fce7f3; color: #db2777; }
            .social-btn.instagram:hover { background: linear-gradient(135deg, #f59e0b, #ec4899, #8b5cf6); color: white; box-shadow: 0 8px 24px rgba(236,72,153,0.4); }
            .social-btn.tiktok    { background: #f1f5f9; color: #0f172a; }
            .social-btn.tiktok:hover    { background: #0f172a; color: white; box-shadow: 0 8px 24px rgba(15,23,42,0.3); }
            .social-btn.facebook  { background: #dbeafe; color: #2563eb; }
            .social-btn.facebook:hover  { background: #2563eb; color: white; box-shadow: 0 8px 24px rgba(37,99,235,0.4); }

            /* ── CTA BUTTON ── */
            .cta-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                background: var(--dark);
                color: white;
                font-weight: 700;
                font-size: 14px;
                padding: 16px 24px;
                border-radius: 16px;
                text-decoration: none;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                letter-spacing: 0.02em;
            }

            .cta-btn:hover {
                background: var(--orange);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 12px 32px rgba(234,88,12,0.35);
            }

            /* ── DIVIDER ── */
            .section-label {
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: #a8a29e;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .section-label::after {
                content: '';
                flex: 1;
                height: 1px;
                background: #e7e5e4;
            }

            /* ── GALERÍA ── */
            .gallery-item {
                aspect-ratio: 1;
                border-radius: 16px;
                overflow: hidden;
                border: 1px solid #e7e5e4;
                cursor: zoom-in;
                position: relative;
            }

            .gallery-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .gallery-item:hover img { transform: scale(1.08); }

            /* Overlay oscuro en hover */
            .gallery-item::after {
                content: '\f00e';
                font-family: 'Font Awesome 6 Free';
                font-weight: 900;
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                color: white;
                background: rgba(12,10,9,0);
                transition: background 0.3s ease, opacity 0.3s ease;
                opacity: 0;
            }

            .gallery-item:hover::after {
                background: rgba(12,10,9,0.38);
                opacity: 1;
            }

            /* ══════════════════════════════════════════════════ */
            /*  LIGHTBOX                                          */
            /* ══════════════════════════════════════════════════ */
            #lightbox {
                position: fixed;
                inset: 0;
                z-index: 9999;
                background: rgba(12, 10, 9, 0.96);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                padding: 20px;
            }

            #lightbox.active {
                opacity: 1;
                pointer-events: all;
            }

            #lightbox-img {
                max-width: min(90vw, 960px);
                max-height: 85vh;
                border-radius: 20px;
                object-fit: contain;
                box-shadow: 0 32px 80px rgba(0,0,0,0.6);
                transform: scale(0.92);
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
                user-select: none;
            }

            #lightbox.active #lightbox-img {
                transform: scale(1);
            }

            /* Botones prev / next */
            .lb-nav-btn {
                position: fixed;
                top: 50%;
                transform: translateY(-50%);
                width: 52px;
                height: 52px;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.15);
                backdrop-filter: blur(8px);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 18px;
                cursor: pointer;
                transition: background 0.2s ease, transform 0.2s ease;
                z-index: 10000;
            }

            .lb-nav-btn:hover {
                background: rgba(234,88,12,0.7);
                transform: translateY(-50%) scale(1.08);
            }

            #lb-prev { left: 20px; }
            #lb-next { right: 20px; }

            /* Botón cerrar */
            #lb-close {
                position: fixed;
                top: 20px;
                right: 20px;
                width: 44px;
                height: 44px;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(8px);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 16px;
                cursor: pointer;
                transition: background 0.2s ease;
                z-index: 10000;
            }

            #lb-close:hover { background: rgba(239,68,68,0.7); }

            /* Contador de fotos */
            #lb-counter {
                position: fixed;
                bottom: 24px;
                left: 50%;
                transform: translateX(-50%);
                color: rgba(255,255,255,0.5);
                font-size: 13px;
                font-weight: 600;
                letter-spacing: 0.08em;
                z-index: 10000;
                display: flex;
                gap: 8px;
                align-items: center;
            }

            #lb-dots {
                display: flex;
                gap: 6px;
                align-items: center;
            }

            .lb-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: rgba(255,255,255,0.25);
                transition: background 0.2s, transform 0.2s;
                cursor: pointer;
            }

            .lb-dot.active {
                background: #ea580c;
                transform: scale(1.3);
            }

            /* ── STICKY SIDEBAR ── */
            @media (min-width: 1024px) {
                .sticky-panel { position: sticky; top: 24px; }
            }

            /* ── ANIMATIONS ── */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(28px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .fade-up { animation: fadeUp 0.7s ease both; }
            .delay-1 { animation-delay: 0.1s; }
            .delay-2 { animation-delay: 0.2s; }
            .delay-3 { animation-delay: 0.3s; }
        </style>
    </head>
    <body>

        {{-- ── HERO CON FOTO DE FONDO ── --}}
        <section class="hero-section">
            @php
                $bgUrl = '';
                if($restaurante->foto_portada)
                    $bgUrl = asset('storage/' . $restaurante->foto_portada);
                elseif($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                    $bgUrl = asset('storage/' . $restaurante->imagenes->first()->ruta_foto);
                else
                    $bgUrl = 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1600&q=80';
            @endphp
            <div class="hero-bg" style="background-image: url('{{ $bgUrl }}')"></div>
            <div class="hero-overlay"></div>

            {{-- Botón zoom en la portada --}}
            @if($restaurante->foto_portada)
                <button
                    onclick="openLightbox('{{ asset('storage/' . $restaurante->foto_portada) }}', -1)"
                    style="position:absolute;bottom:80px;right:2rem;z-index:20;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);backdrop-filter:blur(12px);color:white;font-size:13px;font-weight:700;padding:10px 18px;border-radius:999px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(234,88,12,0.7)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                    <i class="fas fa-expand-alt" style="font-size:11px;"></i> Ver portada
                </button>
            @endif

            {{-- NAV --}}
            <nav class="nav-glass">
                <div style="max-width:72rem;margin:0 auto;padding:0 2rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;height:80px;">
                        <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                            <div style="width:40px;height:40px;background:#ea580c;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(234,88,12,0.35);">
                                <i class="fas fa-utensils" style="color:white;font-size:13px;"></i>
                            </div>
                            <span class="premium-title" style="font-size:22px;font-weight:700;color:white;font-style:italic;">Gastro<span style="color:#fb923c;">Nicaragua</span></span>
                        </a>
                        <a href="{{ route('restaurantes.index') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);backdrop-filter:blur(12px);color:white;font-size:13px;font-weight:700;padding:10px 20px;border-radius:999px;transition:all 0.2s;">
                            <i class="fas fa-arrow-left" style="font-size:11px;"></i> Volver a inicio
                        </a>
                    </div>
                </div>
            </nav>

            {{-- Hero content --}}
            <div class="hero-content">
                <div style="max-width:680px;">
                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px;" class="fade-up">
                        <span class="badge-pill" style="background:#ea580c;color:white;">
                            <i class="fas fa-utensils" style="font-size:9px;"></i> Establecimiento
                        </span>
                        @if($restaurante->especialidad)
                            <span class="badge-pill" style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.15);color:#fb923c;backdrop-filter:blur(8px);">
                                <i class="fas fa-tags" style="font-size:9px;"></i> {{ $restaurante->especialidad }}
                            </span>
                        @endif
                    </div>

                    <h1 class="premium-title fade-up delay-1" style="font-size:clamp(2.5rem,6vw,4.5rem);font-weight:900;color:white;line-height:1.1;margin:0 0 20px;text-shadow:0 2px 24px rgba(0,0,0,0.4);">
                        {{ $restaurante->nombre }}
                    </h1>

                    <div class="fade-up delay-2" style="display:inline-flex;align-items:center;gap:10px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);backdrop-filter:blur(12px);padding:10px 20px;border-radius:999px;">
                        <i class="fas fa-map-marker-alt" style="color:#fb923c;"></i>
                        <span style="color:white;font-weight:700;font-size:14px;">{{ $restaurante->departamento->nombre }}</span>
                        @if($restaurante->municipio)
                            <span style="color:rgba(255,255,255,0.4);">|</span>
                            <span style="color:rgba(255,255,255,0.75);font-size:14px;">{{ $restaurante->municipio->nombre }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ── MAIN CONTENT ── --}}
        @php
            $totalReviews = $restaurante->reviews()->count();
            $avgRating    = $totalReviews > 0 ? round($restaurante->reviews()->avg('rating'), 1) : null;

            // Construir el array de todas las imágenes para el lightbox
            $todasLasImagenes = [];
            if($restaurante->imagenes) {
                foreach($restaurante->imagenes as $foto) {
                    $todasLasImagenes[] = asset('storage/' . $foto->ruta_foto);
                }
            }
        @endphp

        <main style="max-width:72rem;margin:0 auto;padding:48px 2rem 80px;">
            <div style="display:grid;grid-template-columns:1fr;gap:28px;" class="lg-grid">

                {{-- ── COLUMNA IZQUIERDA ── --}}
                <div style="display:flex;flex-direction:column;gap:24px;">

                    {{-- FICHAS STATS --}}
                    <div class="info-card" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));">
                        <div class="stat-chip">
                            <div class="stat-icon" style="background:#fff7ed;">
                                <i class="fas fa-utensils" style="color:#ea580c;font-size:18px;"></i>
                            </div>
                            <div>
                                <span style="font-size:9px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;display:block;margin-bottom:4px;">Especialidad</span>
                                <span style="font-size:14px;font-weight:800;color:#1c1917;">{{ $restaurante->especialidad ?? 'General' }}</span>
                            </div>
                        </div>
                        <div class="stat-chip" style="border-left:1px solid #f5f5f4;">
                            <div class="stat-icon" style="background:#fffbeb;">
                                <i class="fas fa-map-signs" style="color:#d97706;font-size:18px;"></i>
                            </div>
                            <div>
                                <span style="font-size:9px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;display:block;margin-bottom:4px;">Municipio</span>
                                <span style="font-size:14px;font-weight:700;color:#292524;">{{ $restaurante->municipio->nombre }}</span>
                            </div>
                        </div>
                        <div class="stat-chip" style="border-left:1px solid #f5f5f4;">
                            <div class="stat-icon" style="background:#fefce8;">
                                <i class="fas fa-star" style="color:#eab308;font-size:18px;"></i>
                            </div>
                            <div>
                                <span style="font-size:9px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;display:block;margin-bottom:4px;">Calificación</span>
                                <span style="font-size:14px;font-weight:700;color:#292524;">
                                    @if($avgRating)
                                        {{ $avgRating }} <span style="color:#a8a29e;font-weight:500;">/ 5.0</span>
                                    @else
                                        <span style="color:#a8a29e;font-weight:500;">Sin reseñas</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div class="info-card" style="padding:36px 40px;">
                        <div class="section-label" style="margin-bottom:20px;">
                            <i class="fas fa-align-left" style="color:#ea580c;"></i>
                            Sobre el restaurante
                        </div>
                        <p style="color:#57534e;font-size:16px;line-height:1.8;margin:0;">
                            Bienvenidos a <strong style="color:#1c1917;">{{ $restaurante->nombre }}</strong>. Somos especialistas en ofrecer la mejor experiencia culinaria en la hermosa localidad de {{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}. ¡Visítanos y disfruta de una sazón inigualable!
                        </p>
                    </div>

                    {{-- ══ MAPA DE UBICACIÓN ══ --}}
@if($restaurante->latitud && $restaurante->longitud)
    <div class="info-card" style="padding:36px 40px;">
        <div class="section-label" style="margin-bottom:20px;">
            <i class="fas fa-map-marker-alt" style="color:#ea580c;"></i> Cómo llegar
        </div>

        @if($restaurante->direccion)
            <div style="display:flex;align-items:flex-start;gap:10px;background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;padding:14px 18px;margin-bottom:16px;">
                <i class="fas fa-map-pin" style="color:#ea580c;margin-top:2px;flex-shrink:0;"></i>
                <p style="margin:0;font-size:14px;color:#57534e;line-height:1.6;">{{ $restaurante->direccion }}</p>
            </div>
        @endif

        <div id="mapa-publico" style="height:320px;border-radius:20px;overflow:hidden;border:1px solid #e7e5e4;"></div>

        <a href="https://www.google.com/maps?q={{ $restaurante->latitud }},{{ $restaurante->longitud }}"
           target="_blank"
           style="display:inline-flex;align-items:center;gap:8px;margin-top:14px;background:#1c1917;color:white;text-decoration:none;font-size:13px;font-weight:700;padding:10px 20px;border-radius:12px;transition:all .2s;"
           onmouseover="this.style.background='#ea580c'"
           onmouseout="this.style.background='#1c1917'">
            <i class="fas fa-directions"></i> Abrir en Google Maps
        </a>
    </div>
@endif

                    {{-- GALERÍA CON LIGHTBOX --}}
                    @if($restaurante->imagenes && $restaurante->imagenes->count() > 0)
                        <div class="info-card" style="padding:36px 40px;">
                            <div class="section-label" style="margin-bottom:20px;">
                                <i class="fas fa-images" style="color:#ea580c;"></i>
                                Galería de fotos
                                <span style="font-size:10px;color:#d6d3d1;font-weight:500;normal-case;letter-spacing:0;">
                                    — clic para ampliar
                                </span>
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;">
                                @foreach($restaurante->imagenes as $index => $foto)
                                    <div class="gallery-item"
                                         onclick="openLightbox('{{ asset('storage/' . $foto->ruta_foto) }}', {{ $index }})">
                                        <img src="{{ asset('storage/' . $foto->ruta_foto) }}"
                                             alt="Foto {{ $index + 1 }} de {{ $restaurante->nombre }}"
                                             loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ── SIDEBAR ── --}}
                <div class="sticky-panel" style="display:flex;flex-direction:column;gap:20px;">

                    {{-- DISPONIBILIDAD + CONTACTO --}}
                    <div class="info-card" style="padding:28px 32px;">
                        <div class="section-label" style="margin-bottom:20px;">
                            <i class="far fa-clock"></i> Información
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f5f5f4;">
                            <span style="font-size:13px;font-weight:600;color:#78716c;">Estado actual</span>
                            <span style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-size:11px;font-weight:800;padding:6px 14px;border-radius:999px;letter-spacing:0.06em;">● ABIERTO</span>
                        </div>

                        <div>
                            <span style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:8px;">Contacto</span>
                            <div style="background:#fafaf9;border:1px solid #e7e5e4;border-radius:12px;padding:12px 16px;font-size:13px;font-weight:600;color:#292524;word-break:break-all;">
                                <i class="fas fa-envelope" style="color:#ea580c;margin-right:8px;"></i>
                                {{ $restaurante->email ?? 'No disponible' }}
                            </div>
                        </div>
                    </div>

                    {{-- REDES SOCIALES --}}
                    @if(!empty($restaurante->whatsapp) || !empty($restaurante->instagram) || !empty($restaurante->tiktok) || !empty($restaurante->facebook))
                        <div class="info-card" style="padding:28px 32px;">
                            <div class="section-label" style="margin-bottom:20px;">
                                <i class="fas fa-share-alt"></i> Redes sociales
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:12px;">
                                @if(!empty($restaurante->whatsapp))
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurante->whatsapp) }}" target="_blank" class="social-btn whatsapp" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                @if(!empty($restaurante->instagram))
                                    <a href="{{ $restaurante->instagram }}" target="_blank" class="social-btn instagram" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if(!empty($restaurante->tiktok))
                                    <a href="{{ $restaurante->tiktok }}" target="_blank" class="social-btn tiktok" title="TikTok">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                @endif
                                @if(!empty($restaurante->facebook))
                                    <a href="{{ $restaurante->facebook }}" target="_blank" class="social-btn facebook" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- CTA --}}
                    <a href="mailto:{{ $restaurante->email ?? 'contacto@gastronicaragua.com' }}" class="cta-btn">
                        <i class="fas fa-paper-plane" style="font-size:13px;"></i>
                        Enviar consulta al local
                    </a>

                </div>

            </div>
        </main>

        <footer style="background:#0c0a09;color:white;padding:48px 2rem;text-align:center;border-top:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px;">
                <div style="width:32px;height:32px;background:#ea580c;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-utensils" style="color:white;font-size:12px;"></i>
                </div>
                <span class="premium-title" style="color:white;font-size:18px;font-style:italic;">Gastro<span style="color:#fb923c;">Nicaragua</span></span>
            </div>
            <p style="color:#57534e;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;margin:0;">© 2026 — Experiencias Culinarias de Nicaragua</p>
        </footer>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- LIGHTBOX HTML                                                  --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div id="lightbox" onclick="handleLightboxClick(event)">
            <button id="lb-close"  onclick="closeLightbox()"><i class="fas fa-times"></i></button>
            <button id="lb-prev"   class="lb-nav-btn" onclick="navigateLightbox(-1)"><i class="fas fa-chevron-left"></i></button>
            <button id="lb-next"   class="lb-nav-btn" onclick="navigateLightbox(1)"><i class="fas fa-chevron-right"></i></button>
            <img id="lightbox-img" src="" alt="Foto ampliada">
            <div id="lb-counter">
                <span id="lb-text"></span>
                <div id="lb-dots"></div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- LIGHTBOX JS                                                    --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <script>
            // Array de imágenes de la galería (inyectado desde PHP)
            const galeriaImages = @json($todasLasImagenes);

            let currentIndex  = 0;   // índice actual dentro de galeriaImages
            let isPortada     = false; // si estamos mostrando la portada suelta

            const lb          = document.getElementById('lightbox');
            const lbImg       = document.getElementById('lightbox-img');
            const lbText      = document.getElementById('lb-text');
            const lbDots      = document.getElementById('lb-dots');
            const lbPrev      = document.getElementById('lb-prev');
            const lbNext      = document.getElementById('lb-next');

            function openLightbox(src, index) {
                isPortada    = (index === -1);   // -1 significa portada
                currentIndex = isPortada ? 0 : index;

                lbImg.src = src;
                lb.classList.add('active');
                document.body.style.overflow = 'hidden';

                updateControls();
            }

            function closeLightbox() {
                lb.classList.remove('active');
                document.body.style.overflow = '';
                // Limpia src tras la transición para evitar parpadeo
                setTimeout(() => { lbImg.src = ''; }, 300);
            }

            function navigateLightbox(dir) {
                if (isPortada || galeriaImages.length === 0) return;
                currentIndex = (currentIndex + dir + galeriaImages.length) % galeriaImages.length;

                // Transición suave
                lbImg.style.opacity = '0';
                lbImg.style.transform = 'scale(0.94)';
                setTimeout(() => {
                    lbImg.src = galeriaImages[currentIndex];
                    lbImg.style.opacity  = '1';
                    lbImg.style.transform = 'scale(1)';
                    updateControls();
                }, 180);
            }

            function updateControls() {
                // Ocultar nav si es portada o solo hay 1 foto
                const showNav = !isPortada && galeriaImages.length > 1;
                lbPrev.style.display = showNav ? 'flex' : 'none';
                lbNext.style.display = showNav ? 'flex' : 'none';

                // Contador y dots
                if (!isPortada && galeriaImages.length > 0) {
                    lbText.textContent = `${currentIndex + 1} / ${galeriaImages.length}`;
                    lbDots.innerHTML = galeriaImages.map((_, i) =>
                        `<span class="lb-dot ${i === currentIndex ? 'active' : ''}"
                               onclick="jumpTo(${i})"></span>`
                    ).join('');
                } else {
                    lbText.textContent = 'Foto de portada';
                    lbDots.innerHTML   = '';
                }
            }

            function jumpTo(index) {
                currentIndex = index;
                lbImg.src = galeriaImages[index];
                updateControls();
            }

            // Cerrar al hacer clic en el fondo oscuro (no en la imagen)
            function handleLightboxClick(e) {
                if (e.target === lb) closeLightbox();
            }

            // Navegación con teclado
            document.addEventListener('keydown', e => {
                if (!lb.classList.contains('active')) return;
                if (e.key === 'Escape')      closeLightbox();
                if (e.key === 'ArrowRight')  navigateLightbox(1);
                if (e.key === 'ArrowLeft')   navigateLightbox(-1);
            });

            // Transición suave de la imagen
            lbImg.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
        </script>

        <style>
            @media (min-width: 1024px) {
                .lg-grid { grid-template-columns: 1fr 380px !important; align-items: start; }
            }
        </style>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    @if($restaurante->latitud && $restaurante->longitud)
    document.addEventListener('DOMContentLoaded', function() {
        const mapa = L.map('mapa-publico', { scrollWheelZoom: false })
                      .setView([{{ $restaurante->latitud }}, {{ $restaurante->longitud }}], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(mapa);

        const icono = L.divIcon({
            html: `<div style="width:24px;height:24px;background:#ea580c;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 12px rgba(0,0,0,0.4);"></div>`,
            iconSize: [24, 24], iconAnchor: [12, 24], className: ''
        });

        L.marker([{{ $restaurante->latitud }}, {{ $restaurante->longitud }}], { icon: icono })
            .addTo(mapa)
            .bindPopup(`
                <strong style="font-size:14px;color:#1c1917;">{{ $restaurante->nombre }}</strong><br>
                <span style="font-size:12px;color:#78716c;">{{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}</span>
            `, { maxWidth: 220 })
            .openPopup();
    });
    @endif
</script>

    </body>
</html>