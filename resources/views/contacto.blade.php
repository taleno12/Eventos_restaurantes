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

        .contact-hero {
            background: linear-gradient(135deg, #1c1917 0%, #292524 60%, #1e3a8a 100%);
            position: relative; overflow: hidden;
        }
        .contact-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 70% 50%, rgba(37,99,235,0.18) 0%, transparent 60%);
            pointer-events: none;
        }
        .ghost-text {
            font-family: 'Playfair Display', serif; font-weight: 900;
            font-size: clamp(5rem, 18vw, 16rem); line-height: 1; color: transparent;
            -webkit-text-stroke: 1px rgba(37,99,235,0.08); letter-spacing: -0.04em;
            position: absolute; bottom: -2rem; right: -1rem;
            pointer-events: none; user-select: none; white-space: nowrap;
        }

        @keyframes pulse-dot {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.5; transform:scale(1.7); }
        }
        .plan-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: #eff6ff; border: 1.5px solid #bfdbfe;
            color: #1d4ed8; font-size: 10px; font-weight: 800;
            letter-spacing: 0.2em; text-transform: uppercase;
            padding: 6px 18px; border-radius: 999px;
        }
        .plan-pill .dot {
            width: 7px; height: 7px; background: #2563eb; border-radius: 50%;
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
            border-color: #bfdbfe;
        }
        .plan-card.featured {
            border-color: #2563eb;
            box-shadow: 0 20px 60px rgba(37,99,235,0.15);
        }
        .plan-card.featured:hover {
            box-shadow: 0 40px 80px rgba(37,99,235,0.22);
            border-color: #1d4ed8;
        }
        .plan-badge {
            position: absolute; top: 20px; right: 20px;
            background: #2563eb; color: white;
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
        .plan-feature .check { color: #2563eb; font-size: 12px; width: 18px; flex-shrink: 0; }
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
            background: #2563eb; color: #fff; border-color: #2563eb;
        }
        .plan-btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; transform: scale(1.02); }

        .faq-item { border-bottom: 1px solid #f1f0ee; transition: all 0.2s; }
        .faq-question {
            width: 100%; background: none; border: none; text-align: left;
            padding: 20px 0; cursor: pointer; display: flex;
            align-items: center; justify-content: space-between; gap: 16px;
            font-family: 'Instrument Sans', sans-serif; font-size: 15px;
            font-weight: 700; color: #1c1917; transition: color 0.2s;
        }
        .faq-question:hover { color: #2563eb; }
        .faq-icon {
            width: 28px; height: 28px; border-radius: 50%;
            border: 1.5px solid #e7e5e4; display: flex;
            align-items: center; justify-content: center;
            font-size: 11px; color: #a8a29e; flex-shrink: 0;
            transition: all 0.3s;
        }
        .faq-item.open .faq-icon { background: #2563eb; border-color: #2563eb; color: white; transform: rotate(45deg); }
        .faq-answer {
            max-height: 0; overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.16,1,0.3,1), padding 0.3s;
            font-size: 14px; color: #78716c; line-height: 1.75; padding: 0 0 0;
        }
        .faq-item.open .faq-answer { max-height: 300px; padding: 0 0 20px; }

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
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
        }
        .contact-input::placeholder { color: #c4bfbb; }
        .contact-label {
            display: block;
            font-size: 10px; font-weight: 900; letter-spacing: 0.18em;
            text-transform: uppercase; color: #a8a29e; margin-bottom: 8px;
        }

        .info-card {
            display: flex; align-items: flex-start; gap: 14px;
            background: #fff; border: 1px solid #f1f0ee; border-radius: 1.2rem;
            padding: 20px; transition: all 0.3s;
        }
        .info-card:hover { border-color: #bfdbfe; box-shadow: 0 8px 24px rgba(37,99,235,0.08); }
        .info-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; background: #eff6ff;
        }

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

    {{-- ══ NAVBAR ══ --}}
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 sm:h-20 items-center gap-2 sm:gap-4">

                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 no-underline">
                    <span class="text-base sm:text-xl font-bold tracking-tight premium-title italic text-stone-900">
                        Gastro<span class="text-blue-600">Nicaragua</span>
                    </span>
                </a>

                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-1.5 border border-stone-200 text-stone-600 bg-stone-50 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-semibold hover:bg-stone-700 hover:text-white hover:border-stone-700 transition-all shadow-sm no-underline justify-center">
                        <i class="fas fa-home text-xs"></i>
                        <span class="hidden lg:inline">Inicio</span>
                    </a>

                    <a href="{{ route('contacto') }}"
                       class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-blue-100 sm:bg-transparent text-blue-600 sm:text-stone-600 hover:text-blue-600 transition-colors no-underline font-semibold"
                       title="Contacto">
                        <i class="fas fa-envelope text-sm sm:hidden"></i>
                        <span class="hidden sm:inline text-sm font-bold text-blue-600">Contacto</span>
                    </a>

                    @if (Route::has('login'))
                        @auth
                            @if(in_array(auth()->user()->email, ['kevintaleno17@gmail.com', '15ulisesramirez@gmail.com']))
                                <a href="{{ url('/dashboard') }}"
                                   class="flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-2 rounded-full sm:rounded-none bg-blue-50 sm:bg-transparent border border-blue-200 sm:border-0 text-blue-600 hover:text-blue-700 transition-colors no-underline">
                                    <i class="fas fa-chart-line text-sm sm:hidden"></i>
                                    <span class="hidden sm:inline text-sm font-semibold">Panel</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-stone-500 hover:text-red-500 transition-colors bg-transparent border-0 cursor-pointer px-1 sm:px-2">Salir</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-stone-600 hover:text-blue-600 transition-colors no-underline px-1 sm:px-2">Ingresar</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- ══ HERO PREMIUM ══ --}}
    <section style="position:relative; min-height:92vh; display:flex; align-items:center; overflow:hidden; padding-top:80px;">
        <div style="position:absolute;inset:0;z-index:0;">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=2070&q=80"
                 alt="Gastronomía premium"
                 style="width:100%;height:100%;object-fit:cover;object-position:center;transform:scale(1.04);animation:heroZoom 14s ease-in-out infinite alternate;">
            <div style="position:absolute;inset:0;background:linear-gradient(110deg,rgba(12,9,8,0.93) 0%,rgba(20,12,6,0.82) 45%,rgba(60,20,5,0.45) 100%);"></div>
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 75% 40%,rgba(37,99,235,0.22) 0%,transparent 55%);"></div>
            <div style="position:absolute;inset:0;opacity:0.04;background-image:url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22/%3E%3C/svg%3E');"></div>
        </div>

        <div style="position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(to bottom,transparent,#2563eb 30%,#3b82f6 60%,transparent);z-index:5;"></div>

        <div class="max-w-7xl mx-auto px-6 w-full" style="position:relative;z-index:10;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" class="hero-grid">

                <div>
                    <div class="fade-in-up" style="animation-delay:0.05s;opacity:0;margin-bottom:24px;">
                        <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(37,99,235,0.15);border:1px solid rgba(37,99,235,0.35);backdrop-filter:blur(8px);padding:7px 16px;border-radius:999px;">
                            <span style="width:7px;height:7px;background:#3b82f6;border-radius:50%;display:inline-block;animation:pulse-dot 1.6s ease-in-out infinite;"></span>
                            <span style="font-size:10px;font-weight:800;letter-spacing:0.22em;text-transform:uppercase;color:#3b82f6;">Únete a la plataforma</span>
                        </div>
                    </div>

                    <h1 class="premium-title fade-in-up"
                        style="font-size:clamp(2.8rem,5.5vw,5rem);line-height:1.0;color:#fff;margin:0 0 24px;animation-delay:0.15s;opacity:0;letter-spacing:-0.02em;">
                        Crece con<br>
                        <em style="color:#3b82f6;font-style:normal;position:relative;display:inline-block;">
                            Gastro Nicaragua
                            <svg style="position:absolute;bottom:-8px;left:0;width:100%;height:10px;overflow:visible;" viewBox="0 0 300 10" preserveAspectRatio="none">
                                <path d="M2 7 Q75 1 150 6 Q225 11 298 4" stroke="#2563eb" stroke-width="2.5" fill="none" stroke-linecap="round"
                                      stroke-dasharray="300" stroke-dashoffset="300"
                                      style="animation:drawLine 1.2s cubic-bezier(0.4,0,0.2,1) 0.8s forwards;"/>
                            </svg>
                        </em>
                    </h1>

                    <p class="fade-in-up" style="font-size:1.05rem;line-height:1.75;color:rgba(214,211,208,0.85);max-width:480px;margin:0 0 36px;animation-delay:0.25s;opacity:0;">
                        Registra tu restaurante, gastrobar o negocio gastronómico y llega a miles de visitantes que buscan experiencias culinarias auténticas en Nicaragua.
                    </p>

                    <div class="fade-in-up" style="display:flex;flex-wrap:wrap;gap:12px;animation-delay:0.35s;opacity:0;">
                        <a href="#planes"
                           style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:#fff;padding:14px 28px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.04em;text-transform:uppercase;transition:all 0.25s;box-shadow:0 8px 32px rgba(37,99,235,0.40);"
                           onmouseover="this.style.background='#1d4ed8';this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.background='#2563eb';this.style.transform='translateY(0)'">
                            <i class="fas fa-rocket" style="font-size:11px;"></i> Ver Plan
                        </a>
                        <a href="#contacto-form"
                           style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);color:#fff;padding:14px 28px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.04em;text-transform:uppercase;border:1.5px solid rgba(255,255,255,0.2);backdrop-filter:blur(8px);transition:all 0.25s;"
                           onmouseover="this.style.background='rgba(255,255,255,0.15)'"
                           onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                            <i class="fas fa-envelope" style="font-size:11px;"></i> Escribirnos
                        </a>
                    </div>
                </div>

               <div style="position:relative;display:flex;flex-direction:column;gap:16px;" class="hero-cards">
                    <div class="fade-in-up" style="animation-delay:0.3s;opacity:0;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);backdrop-filter:blur(16px);border-radius:20px;padding:20px 24px;display:flex;align-items:center;gap:16px;transform:translateX(20px);">
                        <div style="width:48px;height:48px;background:#2563eb;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 8px 20px rgba(37,99,235,0.4);">
                            <i class="fas fa-chart-line" style="color:#fff;font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:rgba(214,211,208,0.6);font-weight:700;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:2px;">Visibilidad</div>
                            <div style="font-size:22px;font-weight:900;color:#fff;font-family:'Playfair Display',serif;">Creciente</div>
                            <div style="font-size:12px;color:rgba(214,211,208,0.65);">visitantes que buscan experiencias gastronómicas</div>
                        </div>
                    </div>

                    <div class="fade-in-up" style="animation-delay:0.4s;opacity:0;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);backdrop-filter:blur(16px);border-radius:20px;padding:20px 24px;display:flex;align-items:center;gap:16px;transform:translateX(20px);">
                        <div style="width:48px;height:48px;background:rgba(255,255,255,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-map-marker-alt" style="color:#3b82f6;font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:rgba(214,211,208,0.6);font-weight:700;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:2px;">Cobertura</div>
                            <div style="font-size:22px;font-weight:900;color:#fff;font-family:'Playfair Display',serif;">17</div>
                            <div style="font-size:12px;color:rgba(214,211,208,0.65);">departamentos de Nicaragua</div>
                        </div>
                    </div>

                    <div class="fade-in-up" style="animation-delay:0.5s;opacity:0;background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);backdrop-filter:blur(16px);border-radius:20px;padding:20px 24px;display:flex;align-items:center;gap:16px;transform:translateX(20px);">
                        <div style="width:48px;height:48px;background:rgba(255,255,255,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-calendar-check" style="color:#3b82f6;font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:rgba(214,211,208,0.6);font-weight:700;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:2px;">Eventos activos</div>
                            <div style="font-size:22px;font-weight:900;color:#fff;font-family:'Playfair Display',serif;">Actividades</div>
                            <div style="font-size:12px;color:rgba(214,211,208,0.65);">experiencias gastronómicas sociales publicadas</div>
                        </div>
                    </div>

                    <div class="fade-in-up" style="animation-delay:0.6s;opacity:0;align-self:flex-end;background:rgba(255,255,255,0.95);border-radius:16px;padding:12px 18px;display:flex;align-items:center;gap:10px;box-shadow:0 20px 50px rgba(0,0,0,0.3);">
                        <div style="width:36px;height:36px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-check" style="color:#16a34a;font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:900;color:#1c1917;">Plan único: $30/mes</div>
                            <div style="font-size:10px;color:#78716c;">Sin contratos de largo plazo</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

    {{-- ══ BENEFICIOS ══ --}}
    <section class="py-20 bg-white" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <span class="plan-pill mb-4 inline-flex">
                    <span class="dot"></span> ¿Por qué unirse?
                </span>
                <h2 class="premium-title text-4xl font-black text-stone-900 mt-4">Todo lo que obtienes al<br><em class="text-blue-600 not-italic">registrar tu negocio</em></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-eye text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Visibilidad Masiva</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Aparece en búsquedas de miles de turistas y locales que buscan experiencias gastronómicas en tu región.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-calendar-check text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Gestión de Eventos</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Publica y gestiona tus eventos gastronómicos, noches de degustación, cenas especiales y más desde un solo lugar.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-briefcase text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Bolsa de Empleo</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Publica vacantes y encuentra el talento gastronómico que necesitas directamente en la plataforma.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Panel de Administración</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Accede a tu propio panel de restaurante con métricas de visitas, interacciones y el alcance de tu perfil.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-star text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">Eventos Destacados (add-on)</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Destaca un evento puntual en el banner principal del home por un pago extra, solo cuando lo necesites.</p>
                </div>
                <div class="bento-card p-7" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-mobile-alt text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="premium-title text-xl font-bold text-stone-900 mb-2">App Móvil Incluida</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Gestiona tu negocio también desde nuestra app móvil, con las mismas funciones que la plataforma web.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ PLAN ÚNICO ══ --}}
    <section id="planes" class="py-20 bg-stone-50" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="plan-pill mb-4 inline-flex">
                    <span class="dot"></span> Plan & Precio
                </span>
                <h2 class="premium-title text-4xl font-black text-stone-900 mt-4">Un solo plan,<br><em class="text-blue-600 not-italic">todo incluido</em></h2>
                <p class="text-stone-500 text-sm mt-4 max-w-md mx-auto leading-relaxed">Sin contratos de largo plazo. Cancela cuando quieras. Empieza hoy mismo.</p>
            </div>

            <div class="max-w-md mx-auto">

                {{-- Plan único --}}
                <div class="plan-card featured p-8" data-aos="fade-up" data-aos-delay="0">
                    <div class="plan-badge">Plan Único</div>
                    <div class="plan-icon-wrap" style="background:#1c1917;">
                        <i class="fas fa-crown text-yellow-400 text-xl"></i>
                    </div>
                    <h3 class="premium-title text-2xl font-black text-stone-900 mb-1">Gastro Nicaragua</h3>
                    <p class="text-stone-400 text-xs mb-6">Todo lo que tu negocio necesita, en un solo plan</p>
                    <div class="mb-6">
                        <div class="plan-price" style="color:#2563eb;"><sup style="color:#2563eb;">$</sup>30<small>/mes</small></div>
                        <p class="text-stone-400 text-xs mt-1">USD por mes</p>
                    </div>
                    <div class="mb-8 space-y-0">
                        <div class="plan-feature"><i class="fas fa-check check"></i>Perfil completo de tu restaurante</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Aparece en búsquedas de la plataforma</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Galería</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>¡Hasta 12 eventos al mes podrán ser publicados!</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Bolsa de empleo ilimitada</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Acceso al panel administrativo de tu restaurante</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Estadísticas y métricas en tiempo real</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Badge verificado ✓</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Soporte prioritario</div>
                        <div class="plan-feature"><i class="fas fa-check check"></i>Acceso incluido a la app móvil</div>
                    </div>
                    <div class="mb-4 p-3 rounded-xl bg-blue-50 border border-blue-100 text-xs text-blue-700 space-y-1">
                        <p class="font-semibold mb-1"><i class="fas fa-bolt mr-1"></i>Eventos destacados (extra)</p>
                        <p>Si quieres que un evento puntual aparezca en el banner principal del home — lo primero que ve todo visitante — puedes contratarlo como un pago adicional por evento, sin necesidad de cambiar de plan.</p>
                    </div>
                    <a href="#contacto-form" class="plan-btn plan-btn-primary">Registrar mi Negocio</a>
                </div>

            </div>

            <div class="mt-10 text-center">
                <p class="text-stone-400 text-sm">
                    <i class="fas fa-shield-alt text-blue-600 mr-1"></i>
                    <a href="#faq" class="text-blue-600 hover:text-blue-700 font-semibold no-underline ml-1">¿Tienes dudas? Ver FAQ →</a>
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
                        ¿Listo para registrar<br><em class="text-blue-600 not-italic">tu negocio?</em>
                    </h2>
                    <p class="text-stone-500 text-sm leading-relaxed max-w-md mb-10">
                        Escríbenos y nuestro equipo comercial te contactará en menos de 24 horas para guiarte en el proceso de registro y activar tu perfil, tu panel administrativo y tu acceso a la app móvil.
                    </p>

                    <div class="space-y-4 mb-10">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">Ubicación</div>
                                <div class="text-stone-500 text-sm">Masaya, Nicaragua</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fab fa-whatsapp text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">WhatsApp Comercial</div>
                                <div class="text-stone-500 text-sm">+505 8376-7512</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">Horario de Atención</div>
                                <div class="text-stone-500 text-sm">Lun – Vie: 8:00 AM – 5:00 PM &nbsp;|&nbsp; Sáb: 8:00 AM – 1:00 PM</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-mobile-alt text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900">App Móvil Gastro Nicaragua</div>
                                <div class="text-stone-500 text-sm">Disponible para tu negocio, con las mismas funciones que la plataforma web: panel, eventos, empleos y estadísticas.</div>
                            </div>
                        </div>
                    </div>

                    {{-- Redes sociales --}}
                    <div>
                        <div class="text-xs font-black uppercase tracking-widest text-stone-400 mb-3">Síguenos en redes</div>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/kevin.taleno.98" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all no-underline">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <a href="https://www.instagram.com/taleno5196" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all no-underline">
                                <i class="fab fa-instagram text-sm"></i>
                            </a>
                            <a href="https://www.tiktok.com/@kevintaleno2" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all no-underline">
                                <i class="fab fa-tiktok text-sm"></i>
                            </a>
                            <a href="https://wa.me/50583767512" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all no-underline">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </a>
                        </div>
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
                            <label class="contact-label">Interés</label>
                            <select id="plan" class="contact-input" style="cursor:pointer;">
                                <option value="">Selecciona una opción...</option>
                                <option value="Plan único ($30 USD/mes)">Plan único — $30 USD / mes</option>
                                <option value="Plan único + evento destacado (extra)">Plan único + evento destacado (pago extra)</option>
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
                                class="w-full bg-blue-600 text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-md shadow-blue-200 flex items-center justify-center gap-2 border-0 cursor-pointer">
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
                .faq-wrap { max-width:700px; margin:0 auto; padding:2rem 0 1rem; font-family:'DM Sans',sans-serif; }
                .faq-header { text-align:center; margin-bottom:2.5rem; }
                .faq-pill { display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:500; letter-spacing:0.08em; text-transform:uppercase; color:#1e3a8a; background:#eff6ff; padding:5px 14px; border-radius:99px; border:0.5px solid #2563eb; margin-bottom:1rem; }
                .faq-pill-dot { width:6px; height:6px; border-radius:50%; background:#2563eb; }
                .faq-title { font-family:'Playfair Display',serif; font-size:32px; font-weight:700; line-height:1.2; color:#1c1917; margin:0; }
                .faq-title em { font-style:italic; color:#2563eb; }
                .faq-divider { width:40px; height:2px; background:#2563eb; margin:1.25rem auto 0; border-radius:2px; }
                .faq-list { border-top:0.5px solid #e7e5e4; }
                .faq-item { border-bottom:0.5px solid #e7e5e4; transition:background 0.2s; }
                .faq-item.open { background:#fafaf9; border-radius:12px; border:0.5px solid #d6d3d1; margin:6px 0; }
                .faq-btn { width:100%; background:none; border:none; cursor:pointer; display:flex; align-items:center; justify-content:space-between; padding:1.2rem 1rem; text-align:left; gap:12px; color:#1c1917; }
                .faq-q { font-size:15px; font-weight:500; line-height:1.4; font-family:'DM Sans',sans-serif; }
                .faq-num { font-family:'Playfair Display',serif; font-size:13px; font-style:italic; color:#78716c; min-width:22px; flex-shrink:0; }
                .faq-icon-wrap { width:30px; height:30px; border-radius:50%; border:0.5px solid #d6d3d1; background:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; color:#78716c; transition:transform 0.3s, background 0.2s, border-color 0.2s; }
                .faq-item.open .faq-icon-wrap { transform:rotate(45deg); background:#eff6ff; border-color:#2563eb; color:#1e3a8a; }
                .faq-answer { font-size:14px; color:#57534e; line-height:1.75; max-height:0; overflow:hidden; transition:max-height 0.35s ease, padding 0.3s ease; padding:0 1rem; }
                .faq-item.open .faq-answer { max-height:400px; padding:0 1rem 1.25rem; }
                .plan-row { display:flex; align-items:flex-start; gap:10px; padding:7px 10px; border-radius:8px; margin-bottom:4px; background:#fff; border:0.5px solid #e7e5e4; }
                .badge { display:inline-flex; align-items:center; font-size:11px; font-weight:500; padding:3px 10px; border-radius:99px; white-space:nowrap; flex-shrink:0; letter-spacing:0.03em; }
                .badge-premium { background:#eff6ff; color:#1e3a8a; border:0.5px solid #2563eb; }
                .badge-basico { background:#F1EFE8; color:#5F5E5A; border:0.5px solid #B4B2A9; }
                .faq-highlight-box { background:#eff6ff; border:0.5px solid #2563eb; border-left:3px solid #2563eb; border-radius:8px; padding:10px 14px; margin-top:10px; font-size:13px; color:#1e3a8a; line-height:1.6; display:flex; gap:8px; align-items:flex-start; }
            </style>

            <div class="faq-wrap">
                <div class="faq-header">
                    <div class="faq-pill"><span class="faq-pill-dot"></span> Preguntas frecuentes</div>
                    <h2 class="faq-title">Todo lo que necesitas<br><em>saber antes de unirte</em></h2>
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
                            Llena el formulario de contacto con el nombre de tu negocio, dirección, teléfono y correo. Nuestro equipo te contactará en menos de 24 horas para confirmar los datos y activar tu perfil, tu panel administrativo y tu acceso a la app móvil. En un máximo de 48 horas tu restaurante ya estará visible para todos los usuarios.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn" onclick="faqToggle(this)">
                            <span class="faq-num">02</span>
                            <span class="faq-q">¿Qué incluye el plan de $30 USD/mes?</span>
                            <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            <p style="margin:0 0 12px;">El plan único de <strong style="color:#1c1917;">$30 USD/mes</strong> incluye absolutamente todo lo que necesita tu negocio:</p>
                            <div class="plan-row"><span class="badge badge-premium">Incluido</span><span>Perfil completo, galería, hasta 12 eventos al mes, bolsa de empleo ilimitada, badge verificado y estadísticas en tiempo real.</span></div>
                            <div class="plan-row"><span class="badge badge-premium">Incluido</span><span>Acceso completo al <strong style="color:#1e3a8a;">panel administrativo</strong> de tu restaurante, donde gestionas todo tu contenido.</span></div>
                            <div class="plan-row"><span class="badge badge-premium">Incluido</span><span>Acceso a la <strong style="color:#1e3a8a;">app móvil</strong> de Gastro Nicaragua, con las mismas funciones que la web.</span></div>
                            <div class="faq-highlight-box"><i class="fas fa-fire" style="font-size:14px;flex-shrink:0;margin-top:2px;"></i>Lo único que no está incluido es aparecer en el banner destacado del home, que se contrata aparte solo si lo necesitas.</div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn" onclick="faqToggle(this)">
                            <span class="faq-num">03</span>
                            <span class="faq-q">¿Cómo funcionan los eventos destacados en el banner principal?</span>
                            <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            Con el plan de $30 puedes publicar todos los eventos que quieras, y aparecerán en la sección de eventos de la plataforma. Si además quieres que un evento puntual se muestre en el <strong style="color:#1c1917;">banner principal del home</strong> — lo primero que ve cada visitante al entrar — puedes contratar ese destacado como un <strong style="color:#1c1917;">pago extra por evento</strong>, sin necesidad de cambiar de plan. Contáctanos por WhatsApp para conocer el costo por evento destacado.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn" onclick="faqToggle(this)">
                            <span class="faq-num">04</span>
                            <span class="faq-q">¿Tienen una app móvil?</span>
                            <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            Sí. Gastro Nicaragua también está disponible como <strong style="color:#1c1917;">app móvil</strong>, con las mismas funciones que la plataforma web: perfil de tu negocio, publicación de eventos, bolsa de empleo, panel administrativo y estadísticas. El acceso a la app está incluido dentro del plan de $30 USD/mes, sin costo adicional.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn" onclick="faqToggle(this)">
                            <span class="faq-num">05</span>
                            <span class="faq-q">¿Cómo se realiza el pago?</span>
                            <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            Actualmente aceptamos transferencia bancaria, depósito en cuenta y pago móvil. Si necesitas una forma de pago diferente, contáctanos directamente por WhatsApp y buscamos una solución.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn" onclick="faqToggle(this)">
                            <span class="faq-num">06</span>
                            <span class="faq-q">¿Qué pasa si cancelo mi suscripción?</span>
                            <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            Puedes cancelar en cualquier momento sin penalizaciones. Tu perfil permanecerá activo hasta el último día del período pagado. Al vencer, contáctanos para renovar tu plan.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn" onclick="faqToggle(this)">
                            <span class="faq-num">07</span>
                        
                        <span class="faq-q">¿Puedo gestionar varios locales desde el panel administrativo?</span>                            <span class="faq-icon-wrap"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            El panel administrativo te permite gestionar cómodamente todos tus negocios desde una sola cuenta, pero cada local se registra y factura de forma independiente, ya que cada uno cuenta con su propio perfil, visibilidad y suscripción de $30 USD/mes. Si tienes varios locales, contáctanos para coordinar el registro de cada uno.
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
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 50% 60%,rgba(37,99,235,0.2) 0%,transparent 60%);"></div>
        </div>
        <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(to bottom,transparent,#2563eb 40%,transparent);"></div>
        <div style="position:absolute;right:0;top:0;bottom:0;width:3px;background:linear-gradient(to bottom,transparent,#2563eb 40%,transparent);"></div>
        <div class="max-w-3xl mx-auto px-6 text-center" style="position:relative;z-index:10;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(37,99,235,0.15);border:1px solid rgba(37,99,235,0.3);padding:6px 16px;border-radius:999px;margin-bottom:24px;">
                <span style="width:6px;height:6px;background:#3b82f6;border-radius:50%;animation:pulse-dot 1.6s ease-in-out infinite;display:inline-block;"></span>
                <span style="font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;color:#3b82f6;">Empieza hoy</span>
            </div>
            <h2 class="premium-title" style="color:#fff;font-size:clamp(2rem,5vw,3.5rem);font-weight:900;line-height:1.05;margin-bottom:16px;">
                ¿Tu restaurante aún no<br><em style="color:#3b82f6;font-style:normal;">está en el mapa?</em>
            </h2>
            <p style="color:rgba(214,211,208,0.75);font-size:1rem;line-height:1.75;max-width:480px;margin:0 auto 40px;">
                Miles de personas buscan experiencias gastronómicas en Nicaragua cada mes, tanto en la web como en nuestra app móvil. Asegúrate de que te encuentren a ti.
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;">
                <a href="#planes"
                   style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:#fff;padding:15px 32px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.05em;text-transform:uppercase;box-shadow:0 10px 36px rgba(37,99,235,0.45);transition:all 0.25s;"
                   onmouseover="this.style.background='#1d4ed8';this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.background='#2563eb';this.style.transform='translateY(0)'">
                    <i class="fas fa-rocket" style="font-size:11px;"></i> Comenzar Ahora
                </a>
                <a href="https://api.whatsapp.com/send?phone=50583767512&text=Hola%20Gastro%20Nicaragua%2C%20quiero%20informaci%C3%B3n%20sobre%20el%20plan" target="_blank"
                   style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);color:#fff;padding:15px 32px;border-radius:14px;font-size:13px;font-weight:800;text-decoration:none;letter-spacing:0.05em;text-transform:uppercase;border:1.5px solid rgba(255,255,255,0.2);backdrop-filter:blur(8px);transition:all 0.25s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.15)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                    <i class="fab fa-whatsapp" style="font-size:14px;"></i> Hablar con un asesor
                </a>
            </div>
        </div>
    </section>
        {{-- ══ FOOTER ══ --}}
        <footer class="bg-slate-900 text-slate-300 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 pt-12 pb-8 sm:pt-16 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 mb-10">
                    <div class="sm:col-span-2 lg:col-span-4 space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-blue-500">Nicaragua</span></span>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed font-light">
                            La plataforma líder en promoción turística y eventos culinarios de Nicaragua, disponible en web y app móvil.
                            Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                        </p>
                        <div class="flex items-center gap-3 pt-1">
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Portal</h4>
                        <ul class="space-y-2.5 text-sm p-0 list-none m-0">
                            <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Inicio</a></li>
                            <li><a href="{{ route('restaurantes.index') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Restaurantes</a></li>
                            <li><a href="{{ route('gastrobares.index') }}" class="text-slate-400 hover:text-indigo-400 transition-all inline-block no-underline">Gastrobares</a></li>
                            <li><a href="{{ route('empleos.index') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Bolsa de Empleos</a></li>
                            <li><a href="{{ route('contacto') }}" class="text-slate-400 hover:text-blue-400 transition-all inline-block no-underline">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="lg:col-span-3 space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white">Destinos Destacados</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm text-slate-400 font-light">
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Masaya</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Granada</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>León</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>San Juan del Sur</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Estelí</span>
                            <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-blue-500 mr-1.5"></i>Matagalpa</span>
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-800 pt-6 text-center text-xs text-slate-500 font-light flex flex-col sm:flex-row justify-between items-center gap-3">
                    <p>&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-slate-500 hover:text-slate-400 no-underline">Política de Privacidad</a>
                        <a href="#" class="text-slate-500 hover:text-slate-400 no-underline">Términos de Servicio</a>
                    </div>
                </div>
            </div>
        </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        document.getElementById('whatsappForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const telefono = "50583767512";
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
                `📦 *Interés:* ${plan || 'No especificado'}%0A%0A` +
                `💬 *Mensaje:* ${mensaje}`;

            window.open(`https://api.whatsapp.com/send?phone=${telefono}&text=${texto}`, '_blank');
        });

        document.querySelectorAll('.fade-in-up').forEach(function(el) {
            el.style.animationFillMode = 'forwards';
        });
    </script>
</body>
</html>