<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gastro Nicaragua | Contacto & Planes</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; scroll-behavior: smooth; }
        .premium-title { font-family: 'Playfair Display', serif; }

        /* ── NAVBAR ── */

        /* ── HERO CONTACTO ── */
        .contact-hero {
            background: linear-gradient(135deg, #1c1917 0%, #292524 60%, #431407 100%);
            position: relative; overflow: hidden;
        }
        .contact-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 70% 50%, rgba(234,88,12,0.18) 0%, transparent 60%);
            pointer-events: none;
        }
        .ghost-text {
            font-family: 'Playfair Display', serif; font-weight: 900;
            font-size: clamp(5rem, 18vw, 16rem); line-height: 1; color: transparent;
            -webkit-text-stroke: 1px rgba(234,88,12,0.08); letter-spacing: -0.04em;
            position: absolute; bottom: -2rem; right: -1rem;
            pointer-events: none; user-select: none; white-space: nowrap;
        }

        /* ── STATS BAR ── */
        .stat-bar {
            background: #fff;
            border-top: 1px solid #f1f0ee;
            border-bottom: 1px solid #f1f0ee;
        }
        .stat-item {
            display: flex; flex-direction: column; align-items: center;
            padding: 24px 20px;
            border-right: 1px solid #f1f0ee;
            transition: background 0.2s;
        }
        .stat-item:last-child { border-right: none; }
        .stat-item:hover { background: #fff7ed; }
        .stat-number {
            font-family: 'Playfair Display', serif; font-weight: 900;
            font-size: 2.2rem; color: #ea580c; line-height: 1;
        }
        .stat-label { font-size: 11px; font-weight: 700; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.15em; margin-top: 4px; }

        /* ── PLANES ── */
        @keyframes pulse-dot {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.5; transform:scale(1.7); }
        }
        .plan-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff7ed; border: 1.5px solid #fed7aa;
            color: #c2410c; font-size: 10px; font-weight: 800;
            letter-spacing: 0.2em; text-transform: uppercase;
            padding: 6px 18px; border-radius: 999px;
        }
        .plan-pill .dot {
            width: 7px; height: 7px; background: #ea580c; border-radius: 50%;
            animation: pulse-dot 1.6s ease-in-out infinite;
        }

        .plan-card {
            background: #fff;
            border: 2px solid #f1f0ee;
            border-radius: 2rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative; overflow: hidden;
        }
        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 40px 80px rgba(28,25,23,0.10);
            border-color: #fed7aa;
        }
        .plan-card.featured {
            border-color: #ea580c;
            box-shadow: 0 20px 60px rgba(234,88,12,0.15);
        }
        .plan-card.featured:hover {
            box-shadow: 0 40px 80px rgba(234,88,12,0.22);
            border-color: #c2410c;
        }
        .plan-badge {
            position: absolute; top: 20px; right: 20px;
            background: #ea580c; color: white;
            font-size: 9px; font-weight: 900; letter-spacing: 0.2em;
            text-transform: uppercase; padding: 5px 12px; border-radius: 999px;
        }
        .plan-icon-wrap {
            width: 56px; height: 56px; border-radius: 1.2rem;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .plan-price {
            font-family: 'Playfair Display', serif; font-weight: 900;
            font-size: 3rem; color: #1c1917; line-height: 1;
        }
        .plan-price sup { font-size: 1.1rem; vertical-align: top; margin-top: 12px; font-weight: 700; }
        .plan-price small { font-size: 0.9rem; font-weight: 600; color: #a8a29e; margin-left: 2px; }
        .plan-feature {
            display: flex; align-items: center; gap: 10px;
            font-size: 13px; color: #57534e; padding: 7px 0;
            border-bottom: 1px dashed #f1f0ee;
        }
        .plan-feature:last-child { border-bottom: none; }
        .plan-feature .check { color: #ea580c; font-size: 12px; width: 18px; flex-shrink: 0; }
        .plan-feature .x { color: #d1cdc8; font-size: 12px; width: 18px; flex-shrink: 0; }
        .plan-btn {
            display: block; width: 100%; text-align: center;
            padding: 13px 20px; border-radius: 14px;
            font-size: 13px; font-weight: 800; letter-spacing: 0.05em;
            text-transform: uppercase; transition: all 0.25s; text-decoration: none;
            cursor: pointer; border: 2px solid transparent;
        }
        .plan-btn-outline {
            border-color: #e7e5e4; color: #1c1917; background: #fff;
        }
        .plan-btn-outline:hover { background: #1c1917; color: #fff; border-color: #1c1917; }
        .plan-btn-primary {
            background: #ea580c; color: #fff; border-color: #ea580c;
        }
        .plan-btn-primary:hover { background: #c2410c; border-color: #c2410c; transform: scale(1.02); }

        /* ── FAQ ── */
        .faq-item {
            border-bottom: 1px solid #f1f0ee;
            transition: all 0.2s;
        }
        .faq-question {
            width: 100%; background: none; border: none; text-align: left;
            padding: 20px 0; cursor: pointer; display: flex;
            align-items: center; justify-content: space-between; gap: 16px;
            font-family: 'Instrument Sans', sans-serif; font-size: 15px;
            font-weight: 700; color: #1c1917; transition: color 0.2s;
        }
        .faq-question:hover { color: #ea580c; }
        .faq-icon {
            width: 28px; height: 28px; border-radius: 50%;
            border: 1.5px solid #e7e5e4; display: flex;
            align-items: center; justify-content: center;
            font-size: 11px; color: #a8a29e; flex-shrink: 0;
            transition: all 0.3s;
        }
        .faq-item.open .faq-icon { background: #ea580c; border-color: #ea580c; color: white; transform: rotate(45deg); }
        .faq-answer {
            max-height: 0; overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.16,1,0.3,1), padding 0.3s;
            font-size: 14px; color: #78716c; line-height: 1.75; padding: 0 0 0;
        }
        .faq-item.open .faq-answer { max-height: 300px; padding: 0 0 20px; }

        /* ── TESTIMONIOS ── */
        .testimonial-card {
            background: #fff;
            border: 1px solid #f1f0ee;
            border-radius: 1.5rem;
            padding: 28px;
            transition: all 0.3s;
        }
        .testimonial-card:hover {
            box-shadow: 0 20px 50px rgba(28,25,23,0.07);
            transform: translateY(-4px);
        }
        .stars { color: #ea580c; font-size: 12px; letter-spacing: 2px; }

        /* ── FORM ── */
        .contact-input {
            width: 100%;
            background: #f8f7f6;
            border: 1.5px solid #e7e5e4;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: 'Instrument Sans', sans-serif;
            color: #1c1917;
            transition: all 0.2s;
            outline: none;
        }
        .contact-input:focus {
            border-color: #ea580c;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(234,88,12,0.10);
        }
        .contact-input::placeholder { color: #c4bfbb; }
        .contact-label {
            display: block;
            font-size: 10px; font-weight: 900; letter-spacing: 0.18em;
            text-transform: uppercase; color: #a8a29e; margin-bottom: 8px;
        }

        /* ── INFO CARDS ── */
        .info-card {
            display: flex; align-items: flex-start; gap: 14px;
            background: #fff; border: 1px solid #f1f0ee; border-radius: 1.2rem;
            padding: 20px; transition: all 0.3s;
        }
        .info-card:hover { border-color: #fed7aa; box-shadow: 0 8px 24px rgba(234,88,12,0.08); }
        .info-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; background: #fff7ed;
        }

        /* ── BENTO GRID ── */
        .bento-grid { display: grid; gap: 16px; }
        .bento-card {
            background: #fff; border: 1px solid #f1f0ee;
            border-radius: 1.5rem; overflow: hidden; transition: all 0.3s;
        }
        .bento-card:hover { box-shadow: 0 16px 40px rgba(28,25,23,0.07); transform: translateY(-3px); }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.16,1,0.3,1) forwards; }
    </style>
</head>
<body class="bg-stone-50 text-stone-900">

    {{-- ══ NAVBAR (igual al home) ══ --}}
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 sm:h-20 items-center gap-2 sm:gap-4">

                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 no-underline">
                    <span class="text-base sm:text-xl font-bold tracking-tight premium-title italic text-stone-900">
                        Gastro<span class="text-orange-600">Nicaragua</span>
                    </span>
                </a>

                {{-- Acciones derecha --}}
                <div class="flex items-center gap-1 sm:gap-2 shrink-0">

                    {{-- Inicio --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-1.5 border border-stone-200 text-stone-600 bg-stone-50 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-semibold hover:bg-stone-700 hover:text-white hover:border-stone-700 transition-all shadow-sm no-underline justify-center">
                        <i class="fas fa-home text-xs"></i>
                        <span class="hidden lg:inline">Inicio</span>
                    </a>

                    


                    <a href="{{ route('contacto') }}"
                       class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-orange-100 sm:bg-transparent text-orange-600 sm:text-stone-600 hover:text-orange-600 transition-colors no-underline font-semibold"
                       title="Contacto">
                        <i class="fas fa-envelope text-sm sm:hidden"></i>
                        <span class="hidden sm:inline text-sm font-bold text-orange-600">Contacto</span>
                    </a>

                    @if (Route::has('login'))
                        @auth
                            @if(auth()->user()->email === 'admin@turismo.ni')
                                <a href="{{ url('/dashboard') }}"
                                   class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-orange-50 sm:bg-transparent border border-orange-200 sm:border-0 text-orange-600 hover:text-orange-700 transition-colors no-underline">
                                    <i class="fas fa-chart-line text-sm sm:hidden"></i>
                                    <span class="hidden sm:inline text-sm font-semibold">Panel</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-stone-500 hover:text-red-500 transition-colors bg-transparent border-0 cursor-pointer px-1 sm:px-2">Salir</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline px-1 sm:px-2">Ingresar</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>

    </nav>

    {{-- ══ HERO PREMIUM ══ --}}
    <section style="position:relative; min-height:92vh; display:flex; align-items:center; overflow:hidden; padding-top:80px;">

        {{-- Foto de fondo --}}
        <div style="position:absolute;inset:0;z-index:0;">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=2070&q=80"
                 alt="Gastronomía premium"
                 style="width:100%;height:100%;object-fit:cover;object-position:center;transform:scale(1.04);animation:heroZoom 14s ease-in-out infinite alternate;">
            {{-- Capas de overlay --}}
            <div style="position:absolute;inset:0;background:linear-gradient(110deg,rgba(12,9,8,0.93) 0%,rgba(20,12,6,0.82) 45%,rgba(60,20,5,0.45) 100%);"></div>
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 75% 40%,rgba(234,88,12,0.22) 0%,transparent 55%);"></div>
            {{-- Grain overlay para textura premium --}}
            <div style="position:absolute;inset:0;opacity:0.04;background-image:url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22/%3E%3C/svg%3E');"></div>
        </div>

        {{-- Línea decorativa izquierda --}}
        <div style="position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(to bottom,transparent,#ea580c 30%,#fb923c 60%,transparent);z-index:5;"></div>

        {{-- Contenido --}}
        <div class="max-w-7xl mx-auto px-6 w-full" style="position:relative;z-index:10;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" class="hero-grid">

                {{-- Columna izquierda: texto --}}
                <div>
                    {{-- Label superior --}}
                    <div class="fade-in-up" style="animation-delay:0.05s;opacity:0;margin-bottom:24px;">
                        <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(234,88,12,0.15);border:1px solid rgba(234,88,12,0.35);backdrop-filter:blur(8px);padding:7px 16px;border-radius:999px;">
                            <span style="width:7px;height:7px;background:#fb923c;border-radius:50%;display:inline-block;animation:pulse-dot 1.6s ease-in-out infinite;"></span>
                            <span style="font-size:10px;font-weight:800;letter-spacing:0.22em;text-transform:uppercase;color:#fb923c;">Únete a la plataforma</span>
                        </div>
                    </div>

                    <h1 class="premium-title fade-in-up"
                        style="font-size:clamp(2.8rem,5.5vw,5rem);line-height:1.0;color:#fff;margin:0 0 24px;animation-delay:0.15s;opacity:0;letter-spacing:-0.02em;">
                        Crece con<br>
                        <em style="color:#fb923c;font-style:normal;position:relative;display:inline-block;">
                            Gastro Nicaragua
                            <svg style="position:absolute;bottom:-8px;left:0;width:100%;height:10px;overflow:visible;" viewBox="0 0 300 10" preserveAspectRatio="none">
                                <path d="M2 7 Q75 1 150 6 Q225 11 298 4" stroke="#ea580c" stroke-width="2.5" fill="none" stroke-linecap="round"
                                      stroke-dasharray="300" stroke-dashoffset="300"
                                      style="animation:drawLine 1.2s cubic-bezier(0.4,0,0.2,1) 0.8s forwards;"/>
                            </svg>
                        </em>
                    </h1>

                    <p class="fade-in-up" style="font-size:1.05rem;line-height:1.75;color:rgba(214,211,208,0.85);max-width:480px;margin:0 0 36px;animation-delay:0.25s;opacity:0;">
                        Registra tu restaurante, gastrobar o negocio gastronómico y llega a miles de visitantes que buscan experiencias culinarias auténticas en Nicaragua.
                    </p>

                    {{-- CTA buttons --}}
                    <div class="fade-in-up" style="display:flex;flex-wrap:wrap;gap:12px;animation-delay:0.35s;opacity:0;">
                        <a href="#planes"
                           style="display:inline-flex;align-items:center;gap:8px;background:#ea580c;color:#fff;padding:14px 28px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.04em;text-transform:uppercase;transition:all 0.25s;box-shadow:0 8px 32px rgba(234,88,12,0.40);"
                           onmouseover="this.style.background='#c2410c';this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.background='#ea580c';this.style.transform='translateY(0)'">
                            <i class="fas fa-rocket" style="font-size:11px;"></i> Ver Planes
                        </a>
                        <a href="#contacto-form"
                           style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);color:#fff;padding:14px 28px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.04em;text-transform:uppercase;border:1.5px solid rgba(255,255,255,0.2);backdrop-filter:blur(8px);transition:all 0.25s;"
                           onmouseover="this.style.background='rgba(255,255,255,0.15)'"
                           onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                            <i class="fas fa-envelope" style="font-size:11px;"></i> Escribirnos
                        </a>
                    </div>

                    

                {{-- Columna derecha: tarjetas flotantes --}}
                <div style="position:relative;display:flex;flex-direction:column;gap:16px;" class="hero-cards">

                    {{-- Card 1 --}}
                    <div class="fade-in-up" style="animation-delay:0.3s;opacity:0;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);backdrop-filter:blur(16px);border-radius:20px;padding:20px 24px;display:flex;align-items:center;gap:16px;transform:translateX(20px);">
                        <div style="width:48px;height:48px;background:#ea580c;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 8px 20px rgba(234,88,12,0.4);">
                            <i class="fas fa-chart-line" style="color:#fff;font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:rgba(214,211,208,0.6);font-weight:700;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:2px;">Visibilidad</div>
                            <div style="font-size:22px;font-weight:900;color:#fff;font-family:'Playfair Display',serif;">+8,000</div>
                            <div style="font-size:12px;color:rgba(214,211,208,0.65);">visitantes únicos al mes</div>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="fade-in-up" style="animation-delay:0.4s;opacity:0;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);backdrop-filter:blur(16px);border-radius:20px;padding:20px 24px;display:flex;align-items:center;gap:16px;transform:translateX(20px);">
                        <div style="width:48px;height:48px;background:rgba(255,255,255,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-map-marker-alt" style="color:#fb923c;font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:rgba(214,211,208,0.6);font-weight:700;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:2px;">Cobertura</div>
                            <div style="font-size:22px;font-weight:900;color:#fff;font-family:'Playfair Display',serif;">17</div>
                            <div style="font-size:12px;color:rgba(214,211,208,0.65);">departamentos de Nicaragua</div>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                    <div class="fade-in-up" style="animation-delay:0.5s;opacity:0;background:rgba(234,88,12,0.12);border:1px solid rgba(234,88,12,0.25);backdrop-filter:blur(16px);border-radius:20px;padding:20px 24px;display:flex;align-items:center;gap:16px;transform:translateX(20px);">
                        <div style="width:48px;height:48px;background:rgba(255,255,255,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-calendar-check" style="color:#fb923c;font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:rgba(214,211,208,0.6);font-weight:700;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:2px;">Eventos activos</div>
                            <div style="font-size:22px;font-weight:900;color:#fff;font-family:'Playfair Display',serif;">+300</div>
                            <div style="font-size:12px;color:rgba(214,211,208,0.65);">experiencias publicadas</div>
                        </div>
                    </div>

                    {{-- Badge flotante "Gratis para empezar" --}}
                    <div class="fade-in-up" style="animation-delay:0.6s;opacity:0;align-self:flex-end;background:rgba(255,255,255,0.95);border-radius:16px;padding:12px 18px;display:flex;align-items:center;gap:10px;box-shadow:0 20px 50px rgba(0,0,0,0.3);">
                        <div style="width:36px;height:36px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-check" style="color:#16a34a;font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:900;color:#1c1917;">Gratis para empezar</div>
                            <div style="font-size:10px;color:#78716c;">Sin tarjeta de crédito</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div style="position:absolute;bottom:32px;left:50%;transform:translateX(-50%);z-index:10;display:flex;flex-direction:column;align-items:center;gap:6px;opacity:0.5;">
            <span style="font-size:9px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;color:#fff;">Descubre más</span>
            <div style="width:1px;height:32px;background:linear-gradient(to bottom,#fff,transparent);animation:scrollPulse 2s ease-in-out infinite;"></div>
        </div>
    </section>

    <style>
        @keyframes heroZoom { from{transform:scale(1.04)} to{transform:scale(1.10)} }
        @keyframes drawLine { from{stroke-dashoffset:300} to{stroke-dashoffset:0} }
        @keyframes scrollPulse { 0%,100%{opacity:0.5;transform:scaleY(1)} 50%{opacity:1;transform:scaleY(1.15)} }
        @media (max-width: 768px) {
            .hero-grid { grid-template-columns: 1fr !important; gap: 2.5rem !important; }
            .hero-cards { display: none !important; }
        }
    </style>

    {{-- ══ STATS BAR ══ --}}
    <section class="stat-bar" data-aos="fade-up">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4">
                <div class="stat-item">
                    <div class="stat-number">+120</div>
                    <div class="stat-label">Restaurantes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">17</div>
                    <div class="stat-label">Departamentos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">+8K</div>
                    <div class="stat-label">Visitantes / mes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">+300</div>
                    <div class="stat-label">Eventos activos</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ BENEFICIOS ══ --}}
    <section class="py-20 bg-white" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <span class="plan-pill mb-4 inline-flex">
                    <span class="dot"></span> ¿Por qué unirse?
                </span>
                <h2 class="premium-title text-4xl font-black text-stone-900 mt-4">Todo lo que obtienes al<br><em class="text-orange-600 not-italic">registrar tu negocio</em></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-eye text-orange-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Visibilidad Masiva</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Aparece en búsquedas de miles de turistas y locales que buscan experiencias gastronómicas en tu región.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-calendar-check text-orange-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Gestión de Eventos</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Publica y gestiona tus eventos gastronómicos, noches de degustación, cenas especiales y más desde un solo lugar.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-briefcase text-orange-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Bolsa de Empleo</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Publica vacantes y encuentra el talento gastronómico que necesitas directamente en la plataforma.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-chart-line text-orange-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Estadísticas en Tiempo Real</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Accede a métricas de visitas, interacciones y el alcance de tu perfil desde tu panel administrativo.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-star text-orange-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Perfil Destacado</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Tu restaurante aparece en el carrusel principal del home visto por todos los visitantes de la plataforma.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-headset text-orange-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Soporte Dedicado</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Nuestro equipo te ayuda con el proceso de registro, publicación de contenido y cualquier duda técnica.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ PLANES ══ --}}
    <section id="planes" class="py-20 bg-stone-50" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="plan-pill mb-4 inline-flex">
                    <span class="dot"></span> Planes & Precios
                </span>
                <h2 class="premium-title text-4xl font-black text-stone-900 mt-4">Elige el plan que mejor<br><em class="text-orange-600 not-italic">se adapta a tu negocio</em></h2>
                <p class="text-stone-500 text-sm mt-4 max-w-md mx-auto leading-relaxed">Sin contratos de largo plazo. Cancela cuando quieras. Empieza hoy mismo.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

                {{-- Plan Básico --}}
                <div class="plan-card p-8" data-aos="fade-up" data-aos-delay="0">
                    <div class="plan-icon-wrap" style="background:#f5f5f4;">
                        <i class="fas fa-seedling text-stone-600 text-xl"></i>
                    </div>
                    <h3 class="premium-title text-2xl font-black text-stone-900 mb-1">Básico</h3>
                    <p class="text-stone-400 text-xs mb-6">Ideal para empezar</p>
                    <div class="mb-6">
                        <div class="plan-price"><sup>C$</sup>0<small>/mes</small></div>
                        <p class="text-stone-400 text-xs mt-1">Gratis para siempre</p>
                    </div>
                    <div class="mb-8 space-y-0">
                        <div class="plan-feature"><i class="fas fa-check check"></i>Perfil básico del restaurante</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Aparece en búsquedas</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>1 foto de portada</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Información de contacto</div>
                        <div class="plan-feature"><i class="fas fa-times x"></i>Publicar eventos</div>
                        <div class="plan-feature"><i class="fas fa-times x"></i>Bolsa de empleo</div>
                        <div class="plan-feature"><i class="fas fa-times x"></i>Estadísticas</div>
                        <div class="plan-feature"><i class="fas fa-times x"></i>Soporte prioritario</div>
                    </div>
                    <a href="#contacto-form" class="plan-btn plan-btn-outline">Registrarme Gratis</a>
                </div>

                {{-- Plan Pro (DESTACADO) --}}
                <div class="plan-card featured p-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="plan-badge">Más Popular</div>
                    <div class="plan-icon-wrap" style="background:#fff7ed;">
                        <i class="fas fa-fire text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="premium-title text-2xl font-black text-stone-900 mb-1">Pro</h3>
                    <p class="text-stone-400 text-xs mb-6">Para negocios en crecimiento</p>
                    <div class="mb-6">
                        <div class="plan-price" style="color:#ea580c;"><sup style="color:#ea580c;">C$</sup>599<small>/mes</small></div>
                        <p class="text-stone-400 text-xs mt-1">~$16 USD por mes</p>
                    </div>
                    <div class="mb-8 space-y-0">
                        <div class="plan-feature"><i class="fas fa-check check"></i>Todo lo del plan Básico</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Galería hasta 20 fotos</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Publicar eventos ilimitados</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Bolsa de empleo (3 vacantes)</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Estadísticas básicas</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Destacado en carrusel</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Soporte por WhatsApp</div>
                        <div class="plan-feature"><i class="fas fa-times x"></i>Badge verificado</div>
                    </div>
                    <a href="#contacto-form" class="plan-btn plan-btn-primary">Empezar Ahora</a>
                </div>

                {{-- Plan Premium --}}
                <div class="plan-card p-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="plan-icon-wrap" style="background:#1c1917;">
                        <i class="fas fa-crown text-yellow-400 text-xl"></i>
                    </div>
                    <h3 class="premium-title text-2xl font-black text-stone-900 mb-1">Premium</h3>
                    <p class="text-stone-400 text-xs mb-6">Para cadenas y marcas establecidas</p>
                    <div class="mb-6">
                        <div class="plan-price"><sup>C$</sup>1,299<small>/mes</small></div>
                        <p class="text-stone-400 text-xs mt-1">~$35 USD por mes</p>
                    </div>
                    <div class="mb-8 space-y-0">
                        <div class="plan-feature"><i class="fas fa-check check"></i>Todo lo del plan Pro</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Galería ilimitada + videos</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Bolsa de empleo ilimitada</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Estadísticas avanzadas</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Badge verificado ✓</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Posición #1 en búsquedas</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Soporte 24/7 prioritario</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Gestión de múltiples locales</div>
                    </div>
                    <a href="#contacto-form" class="plan-btn plan-btn-outline">Contactar Ventas</a>
                </div>

            </div>

            {{-- Nota debajo de planes --}}
            <div class="mt-10 text-center">
                <p class="text-stone-400 text-sm">
                    <i class="fas fa-shield-alt text-orange-600 mr-1"></i>
                    Sin contratos. Sin tarjeta de crédito para el plan gratuito. Precios en Córdobas nicaragüenses.
                    <a href="#faq" class="text-orange-600 hover:text-orange-700 font-semibold no-underline ml-1">¿Tienes dudas? Ver FAQ →</a>
                </p>
            </div>
        </div>
    </section>

    
    {{-- ══ CONTACTO + INFO ══ --}}
    <section id="contacto-form" class="py-20 bg-stone-50" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                {{-- Info izquierda --}}
                <div>
                    <span class="plan-pill mb-5 inline-flex">
                        <span class="dot"></span> Hablemos
                    </span>
                    <h2 class="premium-title text-4xl font-black text-stone-900 mt-4 mb-4">
                        ¿Listo para registrar<br><em class="text-orange-600 not-italic">tu negocio?</em>
                    </h2>
                    <p class="text-stone-500 text-sm leading-relaxed max-w-md mb-10">
                        Escríbenos y nuestro equipo comercial te contactará en menos de 24 horas para guiarte en el proceso de registro y elegir el plan ideal para tu negocio.
                    </p>

                    <div class="space-y-4 mb-10">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt text-orange-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">Ubicación</div>
                                <div class="text-stone-500 text-sm">Masaya, Nicaragua</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fab fa-whatsapp text-orange-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">WhatsApp Comercial</div>
                                <div class="text-stone-500 text-sm">+505 8888-8888</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-envelope text-orange-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">Correo Electrónico</div>
                                <div class="text-stone-500 text-sm">soporte@gastronicaragua.ni</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock text-orange-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">Horario de Atención</div>
                                <div class="text-stone-500 text-sm">Lun – Vie, 8:00 AM – 6:00 PM</div>
                            </div>
                        </div>
                    </div>

                    {{-- Redes sociales --}}
<div>
    <div class="text-xs font-black uppercase tracking-widest text-stone-400 mb-3">Síguenos en redes</div>
    <div class="flex gap-3">
        {{-- Facebook (pendiente de enlace) --}}
        <a href="#" class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all no-underline">
            <i class="fab fa-facebook-f text-sm"></i>
        </a>

        {{-- Instagram (pendiente de enlace) --}}
        <a href="#" class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all no-underline">
            <i class="fab fa-instagram text-sm"></i>
        </a>

        {{-- TikTok --}}
        <a href="https://www.tiktok.com/@nrg434" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all no-underline">
            <i class="fab fa-tiktok text-sm"></i>
        </a>

        {{-- WhatsApp --}}
        <a href="https://wa.me/50575159753" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all no-underline">
            <i class="fab fa-whatsapp text-sm"></i>
        </a>
    </div>
</div>
                {{-- Formulario derecha --}}
                <div class="bg-white border border-stone-200 rounded-[2rem] p-8 md:p-10 shadow-sm">
                    <h3 class="premium-title text-2xl font-black text-stone-900 mb-1">Envíanos un mensaje</h3>
                    <p class="text-stone-400 text-xs mb-7">Te respondemos en menos de 24 horas hábiles</p>

                    <form id="whatsappForm" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="contact-label">Nombre Completo</label>
                                <input type="text" id="nombre" required class="contact-input" placeholder="Ej: Juan Pérez">
                            </div>
                            <div>
                                <label class="contact-label">Correo Electrónico</label>
                                <input type="email" id="email" required class="contact-input" placeholder="juan@ejemplo.com">
                            </div>
                        </div>

                        <div>
                            <label class="contact-label">Nombre del Negocio</label>
                            <input type="text" id="negocio" class="contact-input" placeholder="Ej: Restaurante El Fogón">
                        </div>

                        <div>
                            <label class="contact-label">Plan de Interés</label>
                            <select id="plan" class="contact-input" style="cursor:pointer;">
                                <option value="">Selecciona un plan...</option>
                                <option value="Básico (Gratis)">Básico — Gratis</option>
                                <option value="Pro (C$599/mes)">Pro — C$599 / mes</option>
                                <option value="Premium (C$1,299/mes)">Premium — C$1,299 / mes</option>
                                <option value="No estoy seguro">Aún no lo sé, necesito asesoría</option>
                            </select>
                        </div>

                        <div>
                            <label class="contact-label">Tipo de Negocio</label>
                            <select id="tipo" class="contact-input" style="cursor:pointer;">
                                <option value="">Selecciona...</option>
                                <option value="Restaurante">Restaurante</option>
                                <option value="Gastrobar">Gastrobar</option>
                                <option value="Cafetería">Cafetería</option>
                                <option value="Comida rápida">Comida rápida</option>
                                <option value="Catering">Catering / Eventos</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div>
                            <label class="contact-label">Tu Mensaje</label>
                            <textarea id="mensaje" rows="4" required class="contact-input" style="resize:none;"
                                      placeholder="Cuéntanos sobre tu negocio o pregúntanos lo que necesites..."></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-orange-600 text-white py-3.5 rounded-xl text-sm font-bold hover:bg-orange-700 transition-all shadow-md shadow-orange-200 flex items-center justify-center gap-2 border-0 cursor-pointer">
                            <i class="fab fa-whatsapp text-base"></i>
                            <span>Enviar por WhatsApp</span>
                        </button>

                        <p class="text-center text-stone-400 text-xs">
                            <i class="fas fa-lock text-xs mr-1"></i> Tu información es privada y nunca será compartida con terceros.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

   {{-- ══ FAQ ══ --}}
<section id="faq" class="py-20 bg-white" data-aos="fade-up">
    <div class="max-w-3xl mx-auto px-6">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@400;500&display=swap');

            .faq-wrap {
                max-width: 700px;
                margin: 0 auto;
                padding: 2rem 0 1rem;
                font-family: 'DM Sans', sans-serif;
            }

            .faq-header {
                text-align: center;
                margin-bottom: 2.5rem;
            }

            .faq-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 12px;
                font-weight: 500;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #854F0B;
                background: #FAEEDA;
                padding: 5px 14px;
                border-radius: 99px;
                border: 0.5px solid #EF9F27;
                margin-bottom: 1rem;
            }

            .faq-pill-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #EF9F27;
            }

            .faq-title {
                font-family: 'Playfair Display', serif;
                font-size: 32px;
                font-weight: 700;
                line-height: 1.2;
                color: #1c1917;
                margin: 0;
            }

            .faq-title em {
                font-style: italic;
                color: #D85A30;
            }

            .faq-divider {
                width: 40px;
                height: 2px;
                background: #EF9F27;
                margin: 1.25rem auto 0;
                border-radius: 2px;
            }

            .faq-list {
                border-top: 0.5px solid #e7e5e4;
            }

            .faq-item {
                border-bottom: 0.5px solid #e7e5e4;
                transition: background 0.2s;
            }

            .faq-item.open {
                background: #fafaf9;
                border-radius: 12px;
                border: 0.5px solid #d6d3d1;
                margin: 6px 0;
            }

            .faq-btn {
                width: 100%;
                background: none;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1.2rem 1rem;
                text-align: left;
                gap: 12px;
                color: #1c1917;
            }

            .faq-q {
                font-size: 15px;
                font-weight: 500;
                line-height: 1.4;
                font-family: 'DM Sans', sans-serif;
            }

            .faq-num {
                font-family: 'Playfair Display', serif;
                font-size: 13px;
                font-style: italic;
                color: #78716c;
                min-width: 22px;
                flex-shrink: 0;
            }

            .faq-icon-wrap {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: 0.5px solid #d6d3d1;
                background: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 14px;
                color: #78716c;
                transition: transform 0.3s, background 0.2s, border-color 0.2s;
            }

            .faq-item.open .faq-icon-wrap {
                transform: rotate(45deg);
                background: #FAEEDA;
                border-color: #EF9F27;
                color: #854F0B;
            }

            .faq-answer {
                font-size: 14px;
                color: #57534e;
                line-height: 1.75;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.35s ease, padding 0.3s ease;
                padding: 0 1rem;
            }

            .faq-item.open .faq-answer {
                max-height: 400px;
                padding: 0 1rem 1.25rem;
            }

            .plan-row {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 7px 10px;
                border-radius: 8px;
                margin-bottom: 4px;
                background: #fff;
                border: 0.5px solid #e7e5e4;
            }

            .badge {
                display: inline-flex;
                align-items: center;
                font-size: 11px;
                font-weight: 500;
                padding: 3px 10px;
                border-radius: 99px;
                white-space: nowrap;
                flex-shrink: 0;
                letter-spacing: 0.03em;
            }

            .badge-premium {
                background: #FAEEDA;
                color: #854F0B;
                border: 0.5px solid #EF9F27;
            }

            .badge-pro {
                background: #E6F1FB;
                color: #185FA5;
                border: 0.5px solid #85B7EB;
            }

            .badge-free {
                background: #F1EFE8;
                color: #5F5E5A;
                border: 0.5px solid #B4B2A9;
            }

            .faq-highlight-box {
                background: #FAEEDA;
                border: 0.5px solid #EF9F27;
                border-left: 3px solid #D85A30;
                border-radius: 8px;
                padding: 10px 14px;
                margin-top: 10px;
                font-size: 13px;
                color: #633806;
                line-height: 1.6;
                display: flex;
                gap: 8px;
                align-items: flex-start;
            }
        </style>

        <div class="faq-wrap">
            <div class="faq-header">
                <div class="faq-pill">
                    <span class="faq-pill-dot"></span> Preguntas frecuentes
                </div>
                <h2 class="faq-title">
                    Todo lo que necesitas<br><em>saber antes de unirte</em>
                </h2>
                <div class="faq-divider"></div>
            </div>

            <div class="faq-list">

                <div class="faq-item">
                    <button class="faq-btn" onclick="faqToggle(this)">
                        <span class="faq-num">01</span>
                        <span class="faq-q">¿Cómo registro mi restaurante en la plataforma?</span>
                        <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        Llena el formulario de contacto con el nombre de tu negocio, dirección, teléfono y correo. Nuestro equipo te contactará en menos de 24 horas para confirmar los datos y activar tu perfil. En un máximo de 48 horas tu restaurante ya estará visible para todos los usuarios — sin costo de activación.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="faqToggle(this)">
                        <span class="faq-num">02</span>
                        <span class="faq-q">¿Cómo funciona la sección de eventos destacados?</span>
                        <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <p style="margin: 0 0 12px;">Los eventos destacados aparecen en el <strong style="color: #1c1917;">hero principal</strong> de la plataforma — lo primero que ven todos los visitantes al entrar. La visibilidad según tu plan:</p>
                        <div class="plan-row">
                            <span class="badge badge-free">Básico</span>
                            <span>No puedes publicar eventos. Tu perfil aparece únicamente en el directorio general.</span>
                        </div>
                        <div class="plan-row">
                            <span class="badge badge-pro">Pro</span>
                            <span>Eventos ilimitados visibles en la sección de eventos, sin prioridad de posicionamiento.</span>
                        </div>
                        <div class="plan-row">
                            <span class="badge badge-premium">Premium</span>
                            <span>Tus eventos aparecen en los <strong style="color: #854F0B;">destacados del hero</strong> — la posición con mayor tráfico de toda la plataforma. Prioridad garantizada.</span>
                        </div>
                        <div class="faq-highlight-box">
                            <i class="fas fa-fire" style="font-size: 14px; flex-shrink: 0; margin-top: 2px;"></i>
                            El hero recibe el 100% de las visitas. Los restaurantes Premium en eventos destacados tienen hasta 4× más clics que los listados regulares.
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="faqToggle(this)">
                        <span class="faq-num">03</span>
                        <span class="faq-q">¿Cuántos eventos puedo publicar?</span>
                        <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <div class="plan-row">
                            <span class="badge badge-free">Básico</span>
                            <span>Sin publicación de eventos.</span>
                        </div>
                        <div class="plan-row">
                            <span class="badge badge-pro">Pro</span>
                            <span>Eventos ilimitados en la sección de eventos de la plataforma.</span>
                        </div>
                        <div class="plan-row">
                            <span class="badge badge-premium">Premium</span>
                            <span>Eventos ilimitados + posición destacada en el hero de la página principal.</span>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="faqToggle(this)">
                        <span class="faq-num">04</span>
                        <span class="faq-q">¿Puedo cambiar de plan después de registrarme?</span>
                        <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        Sí. Puedes subir o bajar de plan en cualquier momento desde tu panel de administración. Los cambios se aplican al inicio del siguiente período de facturación y no hay penalizaciones ni costos adicionales por cambiar de plan.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="faqToggle(this)">
                        <span class="faq-num">05</span>
                        <span class="faq-q">¿La plataforma acepta pagos con tarjeta?</span>
                        <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        Actualmente aceptamos transferencia bancaria, depósito en cuenta y pago móvil (SINPE/BAC). El pago con tarjeta de crédito y débito está en desarrollo y estará disponible próximamente. Si necesitas una forma de pago diferente, contáctanos y buscamos una solución.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="faqToggle(this)">
                        <span class="faq-num">06</span>
                        <span class="faq-q">¿Qué pasa si cancelo mi suscripción?</span>
                        <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        Puedes cancelar en cualquier momento sin penalizaciones. Tu perfil permanecerá activo con todas las ventajas de tu plan hasta el último día del período pagado. Al vencer, tu cuenta pasa automáticamente al plan <span class="badge badge-free" style="vertical-align: middle;">Básico</span> gratuito — tu perfil sigue visible en el directorio, pero sin acceso a eventos ni funciones avanzadas.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="faqToggle(this)">
                        <span class="faq-num">07</span>
                        <span class="faq-q">¿Puedo gestionar múltiples sucursales desde una sola cuenta?</span>
                        <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        La gestión centralizada de múltiples locales desde un solo panel está disponible exclusivamente en el plan <span class="badge badge-premium" style="vertical-align: middle;">Premium</span>. Con los planes Básico y Pro, cada sucursal requiere su propio perfil independiente. Si tienes más de 3 locales, contáctanos para conocer condiciones especiales.
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<script>
    function faqToggle(btn) {
        const item = btn.closest('.faq-item');
        const wasOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
        if (!wasOpen) item.classList.add('open');
    }
</script>

    {{-- ══ CTA FINAL ══ --}}
    <section style="position:relative;overflow:hidden;padding:100px 0;" data-aos="fade-up">
        <div style="position:absolute;inset:0;z-index:0;">
            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=2070&q=80"
                 alt="Restaurante premium"
                 style="width:100%;height:100%;object-fit:cover;object-position:center;">
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(12,9,8,0.92) 0%,rgba(30,15,5,0.85) 50%,rgba(67,20,7,0.80) 100%);"></div>
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 50% 60%,rgba(234,88,12,0.2) 0%,transparent 60%);"></div>
        </div>
        <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(to bottom,transparent,#ea580c 40%,transparent);"></div>
        <div style="position:absolute;right:0;top:0;bottom:0;width:3px;background:linear-gradient(to bottom,transparent,#ea580c 40%,transparent);"></div>
        <div class="max-w-3xl mx-auto px-6 text-center" style="position:relative;z-index:10;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(234,88,12,0.15);border:1px solid rgba(234,88,12,0.3);padding:6px 16px;border-radius:999px;margin-bottom:24px;">
                <span style="width:6px;height:6px;background:#fb923c;border-radius:50%;animation:pulse-dot 1.6s ease-in-out infinite;display:inline-block;"></span>
                <span style="font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;color:#fb923c;">Empieza hoy</span>
            </div>
            <h2 class="premium-title" style="color:#fff;font-size:clamp(2rem,5vw,3.5rem);font-weight:900;line-height:1.05;margin-bottom:16px;">
                ¿Tu restaurante aún no<br><em style="color:#fb923c;font-style:normal;">está en el mapa?</em>
            </h2>
            <p style="color:rgba(214,211,208,0.75);font-size:1rem;line-height:1.75;max-width:480px;margin:0 auto 40px;">
                Miles de personas buscan experiencias gastronómicas en Nicaragua cada mes. Asegúrate de que te encuentren a ti.
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;">
                <a href="#planes"
                   style="display:inline-flex;align-items:center;gap:8px;background:#ea580c;color:#fff;padding:15px 32px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.05em;text-transform:uppercase;box-shadow:0 10px 36px rgba(234,88,12,0.45);transition:all 0.25s;"
                   onmouseover="this.style.background='#c2410c';this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.background='#ea580c';this.style.transform='translateY(0)'">
                    <i class="fas fa-rocket" style="font-size:11px;"></i> Comenzar Ahora
                </a>
                <a href="https://api.whatsapp.com/send?phone=50585406068&text=Hola%20Gastro%20Nicaragua%2C%20quiero%20informaci%C3%B3n%20sobre%20los%20planes" target="_blank"
                   style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);color:#fff;padding:15px 32px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.05em;text-transform:uppercase;border:1.5px solid rgba(255,255,255,0.2);backdrop-filter:blur(8px);transition:all 0.25s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.15)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                    <i class="fab fa-whatsapp" style="font-size:14px;"></i> Hablar con un asesor
                </a>
            </div>
        </div>
    </section>

    {{-- ══ FOOTER ══ --}}
    <footer class="bg-stone-900 text-stone-300 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 pt-12 pb-8 sm:pt-16 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 mb-10">
                <div class="sm:col-span-2 lg:col-span-4 space-y-4">
                    <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    <p class="text-stone-400 text-sm leading-relaxed font-light">
                        La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                        Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                    </p>
                    <div class="flex items-center gap-3 pt-1">
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Portal</h4>
                    <ul class="space-y-2.5 text-sm p-0 list-none m-0">
                        <li><a href="{{ route('home') }}" class="text-stone-400 hover:text-orange-500 transition-all no-underline">Inicio</a></li>
                        <li><a href="{{ route('restaurantes.index') }}" class="text-stone-400 hover:text-orange-500 transition-all no-underline">Restaurantes</a></li>
                        <li><a href="{{ route('gastrobares.index') }}" class="text-stone-400 hover:text-purple-400 transition-all no-underline">Gastrobares</a></li>
                        <li><a href="{{ route('empleos.index') }}" class="text-stone-400 hover:text-orange-500 transition-all no-underline">Bolsa de Empleos</a></li>
                        <li><a href="{{ route('contacto') }}" class="text-orange-400 font-semibold no-underline">Contacto & Planes</a></li>
                    </ul>
                </div>
                <div class="lg:col-span-3 space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Destinos Destacados</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm text-stone-400 font-light">
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Masaya</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Granada</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>León</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>San Juan del Sur</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Estelí</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Matagalpa</span>
                    </div>
                </div>
            </div>
            <div class="border-t border-stone-800 pt-6 text-center text-xs text-stone-500 font-light flex flex-col sm:flex-row justify-between items-center gap-3">
                <p>&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-stone-500 hover:text-stone-400 no-underline">Política de Privacidad</a>
                    <a href="#" class="text-stone-500 hover:text-stone-400 no-underline">Términos de Servicio</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // FAQ accordion
        document.querySelectorAll('.faq-question').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const item = this.closest('.faq-item');
                const isOpen = item.classList.contains('open');
                document.querySelectorAll('.faq-item').forEach(function(i) { i.classList.remove('open'); });
                if (!isOpen) item.classList.add('open');
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // WhatsApp form
        document.getElementById('whatsappForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const telefono = "50585406068";
            const nombre   = document.getElementById('nombre').value;
            const email    = document.getElementById('email').value;
            const negocio  = document.getElementById('negocio').value;
            const plan     = document.getElementById('plan').value;
            const tipo     = document.getElementById('tipo').value;
            const mensaje  = document.getElementById('mensaje').value;

            const texto =
                `¡Hola Gastro Nicaragua! 👋%0A%0A` +
                `Me gustaría registrar mi negocio en la plataforma:%0A%0A` +
                `👤 *Nombre:* ${nombre}%0A` +
                `📧 *Correo:* ${email}%0A` +
                `🍽️ *Negocio:* ${negocio || 'No especificado'}%0A` +
                `🏷️ *Tipo:* ${tipo || 'No especificado'}%0A` +
                `📦 *Plan de interés:* ${plan || 'No especificado'}%0A%0A` +
                `💬 *Mensaje:* ${mensaje}`;

            window.open(`https://api.whatsapp.com/send?phone=${telefono}&text=${texto}`, '_blank');
        });

        // Animate hero elements
        document.querySelectorAll('.fade-in-up').forEach(function(el, i) {
            el.style.animationFillMode = 'forwards';
        });
    </script>
</body>
</html>