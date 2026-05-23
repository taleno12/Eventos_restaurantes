<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $evento->titulo }} | Gastro Nicaragua</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            :root {
                --orange: #ea580c;
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
                height: 92vh;
                min-height: 580px;
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
                    rgba(12,10,9,0.94) 0%,
                    rgba(12,10,9,0.55) 45%,
                    rgba(12,10,9,0.18) 100%
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
            .nav-glass {
                position: absolute;
                top: 0; left: 0; right: 0;
                z-index: 50;
                background: linear-gradient(to bottom, rgba(12,10,9,0.7) 0%, transparent 100%);
            }
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
            .stat-chip {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 20px 22px;
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

            /* ── SOCIAL ── */
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
            .social-btn.instagram:hover { background: linear-gradient(135deg,#f59e0b,#ec4899,#8b5cf6); color: white; box-shadow: 0 8px 24px rgba(236,72,153,0.4); }
            .social-btn.tiktok    { background: #f1f5f9; color: #0f172a; }
            .social-btn.tiktok:hover    { background: #0f172a; color: white; box-shadow: 0 8px 24px rgba(15,23,42,0.3); }
            .social-btn.facebook  { background: #dbeafe; color: #2563eb; }
            .social-btn.facebook:hover  { background: #2563eb; color: white; box-shadow: 0 8px 24px rgba(37,99,235,0.4); }

            /* ── CTA ── */
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

            /* ── SECTION LABEL ── */
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

            .countdown-badge {
                background: #fff1f2;
                color: #be123c;
                border: 1px solid #fecdd3;
                font-size: 12px;
                font-weight: 800;
                padding: 7px 14px;
                border-radius: 999px;
                letter-spacing: 0.04em;
            }

            /* ── GALERÍA ── */
            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 12px;
            }
            .gallery-item {
                aspect-ratio: 1;
                border-radius: 16px;
                overflow: hidden;
                cursor: zoom-in;
                position: relative;
                background: #f5f5f4;
                border: 2px solid #f0f0ef;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }
            .gallery-item:hover {
                transform: scale(1.04);
                box-shadow: 0 10px 36px rgba(0,0,0,0.13);
                border-color: #fed7aa;
            }
            .gallery-item img {
                width: 100%; height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.5s ease;
            }
            .gallery-item:hover img { transform: scale(1.07); }
            .gallery-item .overlay {
                position: absolute;
                inset: 0;
                background: rgba(12,10,9,0);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.25s ease;
            }
            .gallery-item:hover .overlay { background: rgba(12,10,9,0.35); }
            .gallery-item .overlay i {
                color: white;
                font-size: 24px;
                opacity: 0;
                transform: scale(0.7);
                transition: opacity 0.25s ease, transform 0.25s ease;
            }
            .gallery-item:hover .overlay i {
                opacity: 1;
                transform: scale(1);
            }

            /* ══════════════════════════════════════════ */
            /*  LIGHTBOX                                  */
            /* ══════════════════════════════════════════ */
            #lightbox {
                position: fixed;
                inset: 0;
                z-index: 9999;
                background: rgba(12, 10, 9, 0.96);
                backdrop-filter: blur(10px);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            #lightbox.active {
                opacity: 1;
                pointer-events: all;
            }

            /* Imagen principal */
            #lightbox-img {
                max-width: min(88vw, 1000px);
                max-height: 84vh;
                border-radius: 20px;
                object-fit: contain;
                box-shadow: 0 32px 80px rgba(0,0,0,0.7);
                transform: scale(0.92);
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
                            opacity 0.18s ease;
                user-select: none;
                display: block;
            }
            #lightbox.active #lightbox-img { transform: scale(1); }

            /* Botón cerrar */
            #lb-close {
                position: fixed;
                top: 20px; right: 20px;
                width: 46px; height: 46px;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(8px);
                border-radius: 14px;
                color: white;
                font-size: 16px;
                cursor: pointer;
                display: flex; align-items: center; justify-content: center;
                transition: background 0.2s ease, transform 0.2s ease;
                z-index: 10000;
            }
            #lb-close:hover {
                background: rgba(239,68,68,0.7);
                transform: scale(1.08);
            }

            /* Botón "Ver imagen hero" */
            .hero-zoom-btn {
                position: absolute;
                bottom: 120px; right: 2rem;
                z-index: 20;
                background: rgba(255,255,255,0.12);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(12px);
                color: white;
                font-size: 13px;
                font-weight: 700;
                padding: 10px 18px;
                border-radius: 999px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: background 0.2s ease;
            }
            .hero-zoom-btn:hover { background: rgba(234,88,12,0.7); }

            /* Flechas nav */
            .lb-nav {
                position: fixed;
                top: 50%; transform: translateY(-50%);
                width: 52px; height: 52px;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.15);
                backdrop-filter: blur(8px);
                border-radius: 50%;
                color: white;
                font-size: 18px;
                cursor: pointer;
                display: flex; align-items: center; justify-content: center;
                transition: background 0.2s ease, transform 0.2s ease;
                z-index: 10000;
            }
            .lb-nav:hover {
                background: rgba(234,88,12,0.7);
                transform: translateY(-50%) scale(1.08);
            }
            #lb-prev { left: 20px; }
            #lb-next { right: 20px; }

            /* Counter + dots */
            #lb-footer {
                position: fixed;
                bottom: 24px;
                left: 50%; transform: translateX(-50%);
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                z-index: 10000;
            }
            #lb-counter {
                color: rgba(255,255,255,0.5);
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.1em;
            }
            #lb-dots {
                display: flex;
                gap: 7px;
                align-items: center;
            }
            .lb-dot {
                width: 7px; height: 7px;
                border-radius: 50%;
                background: rgba(255,255,255,0.25);
                cursor: pointer;
                transition: background 0.2s ease, transform 0.2s ease;
            }
            .lb-dot.active {
                background: #ea580c;
                transform: scale(1.4);
            }

            /* ── ANIMATIONS ── */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(28px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .fade-up  { animation: fadeUp 0.7s ease both; }
            .delay-1  { animation-delay: 0.1s; }
            .delay-2  { animation-delay: 0.2s; }

            @media (min-width: 1024px) {
                .main-grid    { grid-template-columns: 1fr 380px !important; align-items: start; }
                .sticky-panel { position: sticky; top: 24px; }
            }
        </style>
    </head>
    <body>

        @php $restaurante = $evento->restaurante; @endphp

        {{-- ── HERO ── --}}
        <section class="hero-section">
            <div class="hero-bg" style="background-image: url('{{ asset('storage/' . $evento->imagen) }}')"></div>
            <div class="hero-overlay"></div>

            {{-- Botón zoom portada --}}
            <button class="hero-zoom-btn"
                    onclick="openHeroLightbox('{{ asset('storage/' . $evento->imagen) }}')">
                <i class="fas fa-expand-alt" style="font-size:11px;"></i> Ver imagen completa
            </button>

            <nav class="nav-glass">
                <div style="max-width:72rem;margin:0 auto;padding:0 2rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;height:80px;">
                        <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                            <div style="width:40px;height:40px;background:#ea580c;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(234,88,12,0.35);">
                                <i class="fas fa-utensils" style="color:white;font-size:13px;"></i>
                            </div>
                            <span class="premium-title" style="font-size:22px;font-weight:700;color:white;font-style:italic;">Gastro<span style="color:#fb923c;">Nicaragua</span></span>
                        </a>
                        <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);backdrop-filter:blur(12px);color:white;font-size:13px;font-weight:700;padding:10px 20px;border-radius:999px;">
                            <i class="fas fa-arrow-left" style="font-size:11px;"></i> Volver a inicio
                        </a>
                    </div>
                </div>
            </nav>

            <div class="hero-content">
                <div style="max-width:680px;">
                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px;" class="fade-up">
                        <span class="badge-pill" style="background:#ea580c;color:white;">
                            <i class="fas fa-store" style="font-size:9px;"></i> {{ $restaurante->nombre }}
                        </span>
                        @if($restaurante->especialidad)
                            <span class="badge-pill" style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.15);color:#fb923c;backdrop-filter:blur(8px);">
                                <i class="fas fa-tags" style="font-size:9px;"></i> {{ $restaurante->especialidad }}
                            </span>
                        @endif
                    </div>
                    <h1 class="premium-title fade-up delay-1" style="font-size:clamp(2.4rem,5.5vw,4.2rem);font-weight:900;color:white;line-height:1.1;margin:0 0 20px;text-shadow:0 2px 24px rgba(0,0,0,0.5);">
                        {{ $evento->titulo }}
                    </h1>
                    <div class="fade-up delay-2" style="display:inline-flex;align-items:center;gap:10px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);backdrop-filter:blur(12px);padding:10px 20px;border-radius:999px;">
                        <i class="fas fa-map-marker-alt" style="color:#fb923c;"></i>
                        <span style="color:white;font-weight:700;font-size:14px;">{{ $evento->departamento->nombre }}</span>
                        @if($evento->municipio)
                            <span style="color:rgba(255,255,255,0.4);">|</span>
                            <span style="color:rgba(255,255,255,0.75);font-size:14px;">{{ $evento->municipio->nombre }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ── MAIN ── --}}
        <main style="max-width:72rem;margin:0 auto;padding:48px 2rem 80px;">
            <div style="display:grid;grid-template-columns:1fr;gap:28px;" class="main-grid">

                {{-- COLUMNA IZQUIERDA --}}
                <div style="display:flex;flex-direction:column;gap:24px;">

                    {{-- STATS --}}
                    <div class="info-card" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));">
                        <div class="stat-chip">
                            <div class="stat-icon" style="background:#fff7ed;">
                                <i class="fas fa-ticket-alt" style="color:#ea580c;font-size:18px;"></i>
                            </div>
                            <div>
                                <span style="font-size:9px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;display:block;margin-bottom:4px;">Precio Entrada</span>
                                <span style="font-size:15px;font-weight:800;color:#1c1917;">
                                    @if($evento->precio > 0)
                                        C$ {{ number_format($evento->precio, 0) }}
                                    @else
                                        <span style="color:#16a34a;">Entrada Libre</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="stat-chip" style="border-left:1px solid #f5f5f4;">
                            <div class="stat-icon" style="background:#fffbeb;">
                                <i class="far fa-calendar-alt" style="color:#d97706;font-size:18px;"></i>
                            </div>
                            <div>
                                <span style="font-size:9px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;display:block;margin-bottom:4px;">Fecha Evento</span>
                                <span style="font-size:14px;font-weight:700;color:#292524;">
                                    {{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('d \d\e F, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="stat-chip" style="border-left:1px solid #f5f5f4;">
                            <div class="stat-icon" style="background:#f0fdf4;">
                                <i class="fas fa-building" style="color:#15803d;font-size:17px;"></i>
                            </div>
                            <div>
                                <span style="font-size:9px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#a8a29e;display:block;margin-bottom:4px;">Establecimiento</span>
                                <span style="font-size:14px;font-weight:700;color:#292524;display:block;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $restaurante->nombre }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div class="info-card" style="padding:36px 40px;">
                        <div class="section-label" style="margin-bottom:20px;">
                            <i class="fas fa-align-left" style="color:#ea580c;"></i>
                            Detalles del evento
                        </div>
                        <p style="color:#57534e;font-size:16px;line-height:1.8;margin:0;white-space:pre-line;">
                            {{ $evento->descripcion ?? 'Sin descripción detallada disponible por el momento.' }}
                        </p>
                    </div>

                    {{-- GALERÍA --}}
                    @if($evento->imagenes && $evento->imagenes->count() > 0)
                        <div class="info-card" style="padding:36px 40px;">
                            <div class="section-label" style="margin-bottom:20px;">
                                <i class="fas fa-images" style="color:#ea580c;"></i>
                                Galería de fotos
                                <span style="background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;letter-spacing:0.08em;">
                                    {{ $evento->imagenes->count() }} {{ $evento->imagenes->count() == 1 ? 'foto' : 'fotos' }}
                                </span>
                                <span style="font-size:10px;color:#d6d3d1;font-weight:500;letter-spacing:0;text-transform:none;">— clic para ampliar</span>
                            </div>
                            <div class="gallery-grid">
                                @foreach($evento->imagenes as $index => $foto)
                                    <div class="gallery-item" onclick="openLightbox({{ $index }})">
                                        <img src="{{ asset('storage/' . $foto->ruta) }}"
                                             alt="Foto {{ $index + 1 }} del evento"
                                             loading="lazy">
                                        <div class="overlay">
                                            <i class="fas fa-expand-alt"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- SIDEBAR --}}
                <div class="sticky-panel" style="display:flex;flex-direction:column;gap:20px;">

                    <div class="info-card" style="padding:28px 32px;">
                        <div class="section-label" style="margin-bottom:20px;">
                            <i class="far fa-clock"></i> Disponibilidad
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f5f5f4;">
                            <span style="font-size:13px;font-weight:600;color:#78716c;">Vigencia</span>
                            <span class="countdown countdown-badge" data-expire="{{ $evento->fecha_evento }}">Calculando...</span>
                        </div>
                        <div>
                            <span style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:8px;">Contacto sede</span>
                            <div style="background:#fafaf9;border:1px solid #e7e5e4;border-radius:12px;padding:12px 16px;font-size:13px;font-weight:600;color:#292524;word-break:break-all;">
                                <i class="fas fa-envelope" style="color:#ea580c;margin-right:8px;"></i>
                                {{ $restaurante->email ?? 'No disponible' }}
                            </div>
                        </div>
                    </div>

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

                    <a href="mailto:{{ $restaurante->email ?? 'contacto@gastronicaragua.com' }}?subject=Consulta sobre Evento: {{ $evento->titulo }}" class="cta-btn">
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

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- LIGHTBOX HTML                                          --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div id="lightbox" onclick="handleBgClick(event)">
            <button id="lb-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
            <button id="lb-prev"  class="lb-nav" onclick="navigate(-1)"><i class="fas fa-chevron-left"></i></button>
            <img    id="lightbox-img" src="" alt="Foto del evento">
            <button id="lb-next"  class="lb-nav" onclick="navigate(1)"><i class="fas fa-chevron-right"></i></button>
            <div id="lb-footer">
                <div id="lb-counter"></div>
                <div id="lb-dots"></div>
            </div>
        </div>

        <script>
            // ── Countdown ────────────────────────────────────────────────
            function updateCountdowns() {
                document.querySelectorAll('.countdown').forEach(el => {
                    const exp      = new Date(el.getAttribute('data-expire')).getTime();
                    const distance = exp - Date.now();
                    if (distance < 0) { el.textContent = 'FINALIZADO'; return; }
                    const d = Math.floor(distance / 86400000);
                    const h = Math.floor((distance % 86400000) / 3600000);
                    const m = Math.floor((distance % 3600000) / 60000);
                    el.textContent = `Faltan: ${d}d ${h}h ${m}m`;
                });
            }
            setInterval(updateCountdowns, 60000);
            updateCountdowns();

            // ── Lightbox ─────────────────────────────────────────────────
            const images = [
                @foreach($evento->imagenes ?? [] as $foto)
                    '{{ asset('storage/' . $foto->ruta) }}',
                @endforeach
            ];

            let currentIndex = 0;
            let isHero       = false;   // true cuando mostramos la imagen del hero

            const lb      = document.getElementById('lightbox');
            const lbImg   = document.getElementById('lightbox-img');
            const lbPrev  = document.getElementById('lb-prev');
            const lbNext  = document.getElementById('lb-next');
            const counter = document.getElementById('lb-counter');
            const dots    = document.getElementById('lb-dots');

            // Abrir desde la galería
            function openLightbox(index) {
                isHero       = false;
                currentIndex = index;
                lbImg.src    = images[index];
                lb.classList.add('active');
                document.body.style.overflow = 'hidden';
                updateUI();
            }

            // Abrir imagen del hero (sin nav)
            function openHeroLightbox(src) {
                isHero    = true;
                lbImg.src = src;
                lb.classList.add('active');
                document.body.style.overflow = 'hidden';
                updateUI();
            }

            function closeLightbox() {
                lb.classList.remove('active');
                document.body.style.overflow = '';
                setTimeout(() => { lbImg.src = ''; }, 300);
            }

            function navigate(dir) {
                if (isHero || images.length === 0) return;
                currentIndex = (currentIndex + dir + images.length) % images.length;

                lbImg.style.opacity   = '0';
                lbImg.style.transform = 'scale(0.94)';
                setTimeout(() => {
                    lbImg.src             = images[currentIndex];
                    lbImg.style.opacity   = '1';
                    lbImg.style.transform = 'scale(1)';
                    updateUI();
                }, 160);
            }

            function jumpTo(index) {
                currentIndex = index;
                lbImg.src = images[index];
                updateUI();
            }

            function updateUI() {
                const showNav = !isHero && images.length > 1;
                lbPrev.style.display = showNav ? 'flex' : 'none';
                lbNext.style.display = showNav ? 'flex' : 'none';

                if (!isHero && images.length > 0) {
                    counter.textContent = `${currentIndex + 1} / ${images.length}`;
                    dots.innerHTML = images.map((_, i) =>
                        `<span class="lb-dot ${i === currentIndex ? 'active' : ''}"
                               onclick="jumpTo(${i})"></span>`
                    ).join('');
                } else {
                    counter.textContent = 'Imagen del evento';
                    dots.innerHTML      = '';
                }
            }

            function handleBgClick(e) {
                if (e.target === lb) closeLightbox();
            }

            // Transición suave en img
            lbImg.style.transition = 'opacity 0.16s ease, transform 0.16s ease';

            // Teclado
            document.addEventListener('keydown', e => {
                if (!lb.classList.contains('active')) return;
                if (e.key === 'Escape')     closeLightbox();
                if (e.key === 'ArrowRight') navigate(1);
                if (e.key === 'ArrowLeft')  navigate(-1);
            });
        </script>
    </body>
</html>