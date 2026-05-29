<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $empleo->titulo }} | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400i,700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --orange: #ea580c;
            --orange-light: #fb923c;
            --dark: #0c0a09;
            --cream: #faf9f6;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--cream);
            color: #1c1917;
            overflow-x: hidden;
        }

        .premium-title { font-family: 'Playfair Display', serif; }

        /* ── ANIMACIONES DE ENTRADA ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideRight {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideLeft {
            from { opacity: 0; transform: translateX(24px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.92); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(234,88,12,0.3); }
            50%       { box-shadow: 0 0 40px rgba(234,88,12,0.6); }
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes lineGrow {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }

        .anim-fade-up   { animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-fade-in   { animation: fadeIn 0.6s ease both; }
        .anim-slide-r   { animation: slideRight 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-slide-l   { animation: slideLeft 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-scale-in  { animation: scaleIn 0.6s cubic-bezier(0.16,1,0.3,1) both; }

        .delay-1  { animation-delay: 0.1s; }
        .delay-2  { animation-delay: 0.2s; }
        .delay-3  { animation-delay: 0.3s; }
        .delay-4  { animation-delay: 0.4s; }
        .delay-5  { animation-delay: 0.5s; }
        .delay-6  { animation-delay: 0.6s; }
        .delay-7  { animation-delay: 0.7s; }

        /* ── NAV ── */
        .nav-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 50;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(231,229,228,0.5);
            animation: fadeIn 0.5s ease both;
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }

        .nav-logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }

        .nav-logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #ea580c, #c2410c);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(234,88,12,0.35);
            transition: transform 0.3s ease;
        }
        .nav-logo-icon:hover { transform: rotate(-8deg) scale(1.05); }

        .nav-back {
            display: flex; align-items: center; gap: 8px;
            text-decoration: none;
            font-size: 13px; font-weight: 700;
            color: #57534e;
            padding: 8px 18px;
            border: 1.5px solid #e7e5e4;
            border-radius: 999px;
            transition: all 0.25s ease;
        }
        .nav-back:hover {
            background: #1c1917;
            color: white;
            border-color: #1c1917;
            transform: translateX(-3px);
        }

        /* ── HERO ── */
        .hero-section {
            background: linear-gradient(135deg, #0c0a09 0%, #1c1917 40%, #2d1a0e 70%, #431407 100%);
            background-size: 300% 300%;
            animation: gradientShift 8s ease infinite;
            padding: 140px 2rem 80px;
            position: relative;
            overflow: hidden;
            min-height: 380px;
        }

        .hero-noise {
            position: absolute; inset: 0;
            opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        .hero-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .hero-glow-1 {
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(234,88,12,0.25) 0%, transparent 65%);
            pointer-events: none;
            animation: float 6s ease-in-out infinite;
        }

        .hero-glow-2 {
            position: absolute;
            bottom: -80px; left: 10%;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(251,146,60,0.12) 0%, transparent 65%);
            pointer-events: none;
            animation: float 8s ease-in-out infinite reverse;
        }

        .hero-inner {
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .hero-badge-row {
            display: flex; flex-wrap: wrap; gap: 10px;
            margin-bottom: 24px;
        }

        .hero-badge-resto {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--orange);
            color: white;
            font-size: 10px; font-weight: 800;
            letter-spacing: 0.18em; text-transform: uppercase;
            padding: 8px 18px; border-radius: 999px;
            box-shadow: 0 4px 20px rgba(234,88,12,0.4);
        }

        .hero-badge-contrato {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.9);
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.15em; text-transform: uppercase;
            padding: 8px 18px; border-radius: 999px;
            backdrop-filter: blur(8px);
        }

        .hero-title {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 900;
            color: white;
            line-height: 1.05;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .hero-location {
            display: inline-flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.6);
            font-size: 14px; font-weight: 500;
        }

        .hero-location strong { color: white; }

        /* Línea decorativa animada */
        .hero-line {
            width: 60px; height: 3px;
            background: linear-gradient(90deg, var(--orange), var(--orange-light));
            border-radius: 2px;
            margin: 24px 0;
            transform-origin: left;
            animation: lineGrow 0.8s cubic-bezier(0.16,1,0.3,1) 0.4s both;
        }

        /* ── MAIN LAYOUT ── */
        .main-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 56px 2rem 80px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 32px;
            align-items: start;
        }

        /* ── CARDS IZQUIERDA ── */
        .content-card {
            background: white;
            border: 1px solid #f0eeec;
            border-radius: 24px;
            padding: 32px;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .content-card:hover {
            box-shadow: 0 12px 40px rgba(28,25,23,0.08);
            transform: translateY(-2px);
        }

        .content-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--orange), var(--orange-light), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .content-card:hover::before { opacity: 1; }

        .card-header {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f5f4f2;
        }

        .card-icon-wrap {
            width: 38px; height: 38px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .content-card:hover .card-icon-wrap {
            background: var(--orange);
            border-color: var(--orange);
        }

        .content-card:hover .card-icon-wrap i { color: white !important; }

        .card-title-text {
            font-size: 15px; font-weight: 800;
            color: #1c1917;
        }

        .card-body-text {
            color: #6b6560;
            font-size: 14px;
            line-height: 1.85;
            white-space: pre-line;
        }

        .empty-text {
            color: #b5b0ab;
            font-size: 13px;
            font-style: italic;
            display: flex; align-items: center; gap: 8px;
        }

        /* ── SIDEBAR DERECHA ── */
        .sidebar-sticky {
            position: sticky;
            top: 96px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Tarjeta resumen */
        .summary-card {
            background: white;
            border: 1px solid #f0eeec;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(28,25,23,0.06);
            transition: box-shadow 0.3s ease;
        }
        .summary-card:hover { box-shadow: 0 8px 40px rgba(28,25,23,0.1); }

        .summary-card-header {
            background: linear-gradient(135deg, #1c1917, #292524);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
        }

        .summary-card-header::after {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(234,88,12,0.3) 0%, transparent 70%);
            pointer-events: none;
        }

        .summary-card-header-label {
            font-size: 9px; font-weight: 900;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 4px;
        }

        .summary-card-header-title {
            font-size: 16px; font-weight: 800;
            color: white;
        }

        .summary-body { padding: 20px 24px; }

        /* Stat row */
        .stat-row {
            display: flex; flex-direction: column; gap: 4px;
            padding: 16px 0;
            border-bottom: 1px solid #f5f4f2;
            position: relative;
        }

        .stat-row:last-child { border-bottom: none; padding-bottom: 0; }

        .stat-label {
            font-size: 9px; font-weight: 800;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: #a8a29e;
        }

        .stat-value-salary {
            font-size: 22px; font-weight: 900;
            color: #16a34a;
            letter-spacing: -0.02em;
            display: flex; align-items: center; gap: 6px;
        }

        .stat-value-salary.negociar {
            font-size: 18px;
            background: linear-gradient(90deg, var(--orange), var(--orange-light));
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }

        .stat-value {
            font-size: 14px; font-weight: 700;
            color: #1c1917;
            display: flex; align-items: center; gap: 6px;
        }

        .stat-value-sm {
            font-size: 12px; font-weight: 600;
            color: #78716c;
        }

        /* Redes */
        .social-section { padding: 20px 24px; border-top: 1px solid #f5f4f2; }

        .social-label {
            font-size: 9px; font-weight: 900;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: #a8a29e;
            margin-bottom: 12px;
            display: block;
        }

        .social-icons { display: flex; flex-wrap: wrap; gap: 10px; }

        .social-btn {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16,1,0.3,1);
            position: relative;
            overflow: hidden;
        }

        .social-btn::before {
            content: '';
            position: absolute; inset: 0;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .social-btn:hover { transform: translateY(-3px) scale(1.08); }
        .social-btn:hover::before { opacity: 1; }

        .social-wa  { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .social-wa:hover  { background: #16a34a; color: white; border-color: #16a34a; box-shadow: 0 6px 20px rgba(22,163,74,0.35); }
        .social-ig  { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }
        .social-ig:hover  { background: linear-gradient(135deg, #f59e0b, #ec4899, #8b5cf6); color: white; border-color: transparent; box-shadow: 0 6px 20px rgba(219,39,119,0.35); }
        .social-tt  { background: #fafaf9; color: #1c1917; border: 1px solid #e7e5e4; }
        .social-tt:hover  { background: #1c1917; color: white; border-color: #1c1917; box-shadow: 0 6px 20px rgba(28,25,23,0.35); }
        .social-fb  { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .social-fb:hover  { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 6px 20px rgba(37,99,235,0.35); }

        /* Botón aplicar */
        .apply-section { padding: 20px 24px; border-top: 1px solid #f5f4f2; }

        .btn-apply {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            background: linear-gradient(135deg, #ea580c, #c2410c);
            color: white;
            font-weight: 800; font-size: 14px;
            padding: 14px 24px;
            border-radius: 14px;
            border: none; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            position: relative; overflow: hidden;
            letter-spacing: 0.02em;
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .btn-apply::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }

        .btn-apply:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 32px rgba(234,88,12,0.45);
            animation: none;
        }

        .btn-apply:hover::before { left: 100%; }

        .btn-apply:active { transform: scale(0.98); }

        /* Alertas */
        .alert-success {
            display: flex; align-items: center; gap: 10px;
            background: #f0fdf4; border: 1px solid #bbf7d0;
            color: #15803d; font-size: 13px; font-weight: 600;
            padding: 12px 16px; border-radius: 14px;
            animation: fadeUp 0.5s ease both;
        }

        .alert-error {
            display: flex; align-items: center; gap: 10px;
            background: #fef2f2; border: 1px solid #fecaca;
            color: #dc2626; font-size: 13px; font-weight: 600;
            padding: 12px 16px; border-radius: 14px;
            animation: fadeUp 0.5s ease both;
        }

        /* ── FOOTER ── */
        .site-footer {
            background: #0c0a09;
            border-top: 1px solid rgba(255,255,255,0.05);
            padding: 64px 2rem 32px;
            margin-top: 80px;
        }

        .footer-grid {
            max-width: 1280px; margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .footer-bottom {
            max-width: 1280px; margin: 0 auto;
            padding-top: 32px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .footer-social-link {
            width: 34px; height: 34px;
            background: rgba(255,255,255,0.06); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #78716c; text-decoration: none; transition: all 0.2s;
        }
        .footer-social-link:hover { background: rgba(234,88,12,0.2); color: #fb923c; }

        .footer-link { color: #78716c; font-size: 13px; text-decoration: none; transition: color 0.2s; }
        .footer-link:hover { color: #fb923c; }

        @media (max-width: 900px) {
            .main-wrap { grid-template-columns: 1fr; }
            .sidebar-sticky { position: static; }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
            .footer-bottom { flex-direction: column; align-items: flex-start; }
        }

        /* ── MODAL ── */
        @keyframes gastroSlideUp {
            from { opacity:0; transform:translateY(24px) scale(0.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        #applyModal {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            align-items: center; justify-content: center;
            padding: 1rem;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(8px);
        }

        .modal-inner {
            position: relative; width: 100%; max-width: 680px;
            max-height: 90vh; overflow-y: auto;
            border-radius: 20px;
            background: #1a1a1a;
            border: 1px solid #2e2e2e;
            animation: gastroSlideUp 0.35s cubic-bezier(0.16,1,0.3,1);
        }

        .modal-header {
            position: sticky; top: 0; z-index: 10;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid #2e2e2e;
            background: linear-gradient(135deg, #1a1a1a, #222);
        }

        .modal-close {
            background: rgba(255,255,255,0.05); border: 1px solid #333;
            color: #9ca3af; cursor: pointer; padding: 8px;
            border-radius: 10px; transition: all 0.2s; display: flex;
        }
        .modal-close:hover { background: #333; color: white; transform: rotate(90deg); }

        .modal-input {
            width: 100%; padding: 12px 14px;
            border-radius: 10px;
            background: #252525; border: 1.5px solid #333; color: #fff;
            font-size: 14px; outline: none; box-sizing: border-box;
            font-family: 'Instrument Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .modal-input:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.12); }
        .modal-input::placeholder { color: #4b5563; }

        .modal-label {
            display: block; font-size: 11px; font-weight: 700;
            color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
        }

        @media (max-width: 580px) {
            .modal-grid-2 { grid-template-columns: 1fr !important; }
            .modal-grid-4 { grid-template-columns: 1fr 1fr !important; }
        }
    </style>
</head>
<body>

    {{-- ── NAV ── --}}
    <nav class="nav-bar">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-logo">
                <div class="nav-logo-icon">
                    <i class="fas fa-utensils" style="color:white;font-size:13px;"></i>
                </div>
                <span class="premium-title" style="font-size:22px;font-weight:700;color:#1c1917;font-style:italic;">
                    Gastro<span style="color:#ea580c;">Nicaragua</span>
                </span>
            </a>
            <a href="{{ route('empleos.index') }}" class="nav-back">
                <i class="fas fa-chevron-left" style="font-size:11px;"></i>
                Volver a empleos
            </a>
        </div>
    </nav>

    {{-- ── HERO ── --}}
    <header class="hero-section">
        <div class="hero-noise"></div>
        <div class="hero-grid"></div>
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>

        <div class="hero-inner">
            <div class="hero-badge-row anim-fade-up">
                <span class="hero-badge-resto">
                    <i class="fas fa-store" style="font-size:9px;"></i>
                    {{ $empleo->restaurante->nombre }}
                </span>
                @if($empleo->tipo_contrato)
                    <span class="hero-badge-contrato">
                        <i class="fas fa-clock" style="font-size:9px;"></i>
                        {{ $empleo->tipo_contrato }}
                    </span>
                @endif
            </div>

            <h1 class="premium-title hero-title anim-fade-up delay-1">
                {{ $empleo->titulo }}
            </h1>

            <div class="hero-line"></div>

            <div class="hero-location anim-fade-up delay-2">
                <i class="fas fa-map-marker-alt" style="color:#ea580c;font-size:12px;"></i>
                <strong>{{ $empleo->departamento->nombre }}</strong>
                @if($empleo->municipio)
                    <span style="color:rgba(255,255,255,0.3);">—</span>
                    <span>{{ $empleo->municipio->nombre }}</span>
                @endif
            </div>
        </div>
    </header>

    {{-- Alertas --}}
    @if(session('success'))
        <div style="max-width:1100px;margin:24px auto 0;padding:0 2rem;">
            <div class="alert-success">
                <i class="fas fa-check-circle" style="font-size:16px;flex-shrink:0;"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div style="max-width:1100px;margin:24px auto 0;padding:0 2rem;">
            <div class="alert-error">
                <i class="fas fa-exclamation-circle" style="font-size:16px;flex-shrink:0;"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- ── MAIN ── --}}
    <main class="main-wrap">

        {{-- Columna Izquierda --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Descripción --}}
            <div class="content-card anim-slide-r delay-2">
                <div class="card-header">
                    <div class="card-icon-wrap">
                        <i class="fas fa-align-left" style="color:#ea580c;font-size:14px;"></i>
                    </div>
                    <span class="card-title-text">Descripción de la vacante</span>
                </div>
                <p class="card-body-text">{{ $empleo->descripcion }}</p>
            </div>

            {{-- Requisitos --}}
            <div class="content-card anim-slide-r delay-3">
                <div class="card-header">
                    <div class="card-icon-wrap">
                        <i class="fas fa-clipboard-list" style="color:#ea580c;font-size:14px;"></i>
                    </div>
                    <span class="card-title-text">Requisitos del puesto</span>
                </div>
                @if($empleo->requisitos)
                    <p class="card-body-text">{{ $empleo->requisitos }}</p>
                @else
                    <p class="empty-text">
                        <i class="fas fa-info-circle" style="color:#d6d3d1;font-size:14px;"></i>
                        El restaurante no especificó requisitos adicionales para esta posición.
                    </p>
                @endif
            </div>

        </div>

        {{-- Columna Derecha (sidebar) --}}
        <div class="sidebar-sticky">

            {{-- Tarjeta resumen --}}
            <div class="summary-card anim-slide-l delay-2">

                <div class="summary-card-header">
                    <div class="summary-card-header-label">Resumen de oferta</div>
                    <div class="summary-card-header-title">{{ $empleo->titulo }}</div>
                </div>

                <div class="summary-body">

                    {{-- Salario --}}
                    <div class="stat-row">
                        <span class="stat-label">Remuneración mensual</span>
                        @if($empleo->salario)
                            <span class="stat-value-salary">
                                <i class="fas fa-wallet" style="font-size:14px;color:#16a34a;"></i>
                                C$ {{ number_format($empleo->salario, 2) }}
                            </span>
                        @else
                            <span class="stat-value-salary negociar">
                                <i class="fas fa-comments" style="font-size:14px;"></i>
                                En entrevista
                            </span>
                        @endif
                    </div>

                    {{-- Fecha límite --}}
                    @if($empleo->fecha_limite)
                        <div class="stat-row">
                            <span class="stat-label">Fecha límite para aplicar</span>
                            <span class="stat-value">
                                <i class="far fa-calendar-times" style="color:#dc2626;font-size:13px;"></i>
                                {{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d \d\e M, Y') }}
                            </span>
                        </div>
                    @endif

                    {{-- Publicación --}}
                    <div class="stat-row">
                        <span class="stat-label">Publicado</span>
                        <span class="stat-value-sm">
                            <i class="far fa-clock" style="margin-right:4px;"></i>
                            {{ $empleo->created_at->diffForHumans() }}
                        </span>
                    </div>

                </div>

                {{-- Redes --}}
                <div class="social-section">
                    <span class="social-label">Conoce el establecimiento</span>
                    <div class="social-icons">
                        @if(!empty($empleo->restaurante->whatsapp))
                            @php $phoneClean = preg_replace('/[^0-9]/', '', $empleo->restaurante->whatsapp); @endphp
                            <a href="https://wa.me/{{ $phoneClean }}" target="_blank" class="social-btn social-wa" title="WhatsApp">
                                <i class="fab fa-whatsapp" style="font-size:17px;"></i>
                            </a>
                        @endif
                        @if(!empty($empleo->restaurante->instagram))
                            <a href="{{ $empleo->restaurante->instagram }}" target="_blank" class="social-btn social-ig" title="Instagram">
                                <i class="fab fa-instagram" style="font-size:17px;"></i>
                            </a>
                        @endif
                        @if(!empty($empleo->restaurante->tiktok))
                            <a href="{{ $empleo->restaurante->tiktok }}" target="_blank" class="social-btn social-tt" title="TikTok">
                                <i class="fab fa-tiktok" style="font-size:15px;"></i>
                            </a>
                        @endif
                        @if(!empty($empleo->restaurante->facebook))
                            <a href="{{ $empleo->restaurante->facebook }}" target="_blank" class="social-btn social-fb" title="Facebook">
                                <i class="fab fa-facebook-f" style="font-size:15px;"></i>
                            </a>
                        @endif
                        @if(empty($empleo->restaurante->whatsapp) && empty($empleo->restaurante->instagram) && empty($empleo->restaurante->tiktok) && empty($empleo->restaurante->facebook))
                            <span style="font-size:12px;color:#b5b0ab;font-style:italic;">Sin redes configuradas.</span>
                        @endif
                    </div>
                </div>

                {{-- Botón aplicar --}}
                <div class="apply-section">
                    <button type="button" onclick="abrirModalAplicar()" class="btn-apply">
                        <i class="fas fa-paper-plane" style="font-size:13px;"></i>
                        Aplicar a esta vacante
                    </button>
                </div>

            </div>

        </div>
    </main>

    {{-- ── FOOTER ── --}}
    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                    <div style="width:36px;height:36px;background:#ea580c;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-utensils" style="color:white;font-size:12px;"></i>
                    </div>
                    <span class="premium-title" style="color:white;font-size:20px;font-style:italic;">
                        Gastro<span style="color:#fb923c;">Nicaragua</span>
                    </span>
                </div>
                <p style="color:#78716c;font-size:13px;line-height:1.75;max-width:280px;margin:0 0 24px;">
                    La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                </p>
                <div style="display:flex;gap:12px;">
                    <a href="#" class="footer-social-link"><i class="fab fa-facebook-f" style="font-size:12px;"></i></a>
                    <a href="#" class="footer-social-link"><i class="fab fa-instagram" style="font-size:12px;"></i></a>
                    <a href="#" class="footer-social-link"><i class="fab fa-tiktok" style="font-size:12px;"></i></a>
                </div>
            </div>
            <div>
                <h4 style="color:white;font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;margin:0 0 20px;">Portal</h4>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:12px;">
                    <li><a href="{{ route('home') }}" class="footer-link">Inicio</a></li>
                    <li><a href="{{ route('restaurantes.index') }}" class="footer-link">Restaurantes</a></li>
                    <li><a href="{{ route('empleos.index') }}" style="color:#ea580c;font-size:13px;font-weight:600;text-decoration:none;">Bolsa de Empleos</a></li>
                    <li><a href="{{ route('contacto') }}" class="footer-link">Contacto</a></li>
                </ul>
            </div>
            <div>
                <h4 style="color:white;font-size:10px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;margin:0 0 20px;">Destinos Destacados</h4>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:10px;">
                    <li><a href="#" class="footer-link">› Masaya</a></li>
                    <li><a href="#" class="footer-link">› Granada</a></li>
                    <li><a href="#" class="footer-link">› León</a></li>
                    <li><a href="#" class="footer-link">› San Juan del Sur</a></li>
                    <li><a href="#" class="footer-link">› Estelí</a></li>
                    <li><a href="#" class="footer-link">› Matagalpa</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p style="color:#57534e;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;margin:0;">
                © 2026 Gastro Nicaragua. Todos los derechos reservados.
            </p>
            <div style="display:flex;gap:24px;">
                <a href="#" class="footer-link" style="font-size:11px;">Política de Privacidad</a>
                <a href="#" class="footer-link" style="font-size:11px;">Términos de Servicio</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ── MODAL ── --}}
    <div id="applyModal">
        <div class="modal-inner">

            <div class="modal-header">
                <div>
                    <p style="font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#fb923c;margin:0 0 4px;">
                        {{ $empleo->restaurante->nombre ?? 'Restaurante' }}
                    </p>
                    <h2 style="font-size:20px;font-weight:800;color:#fff;margin:0;">
                        Aplicar: {{ $empleo->titulo }}
                    </h2>
                </div>
                <button type="button" onclick="cerrarModalAplicar()" class="modal-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="applyForm"
                  action="{{ route('empleos.aplicar', $empleo->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  style="padding:1.75rem;display:flex;flex-direction:column;gap:1.25rem;">
                @csrf
                <input type="hidden" name="empleo_id"         value="{{ $empleo->id }}">
                <input type="hidden" name="empleo_titulo"     value="{{ $empleo->titulo }}">
                <input type="hidden" name="restaurante_email" value="{{ $empleo->restaurante->email ?? '' }}">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="modal-grid-2">
                    <div>
                        <label class="modal-label">Nombre <span style="color:#fb923c">*</span></label>
                        <input type="text" name="nombre" required placeholder="Tu nombre" class="modal-input">
                    </div>
                    <div>
                        <label class="modal-label">Apellido <span style="color:#fb923c">*</span></label>
                        <input type="text" name="apellido" required placeholder="Tu apellido" class="modal-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="modal-grid-2">
                    <div>
                        <label class="modal-label">Correo electrónico <span style="color:#fb923c">*</span></label>
                        <input type="email" name="email" required placeholder="correo@ejemplo.com" class="modal-input">
                    </div>
                    <div>
                        <label class="modal-label">Teléfono / WhatsApp <span style="color:#fb923c">*</span></label>
                        <input type="tel" name="telefono" required placeholder="+505 8888-0000" class="modal-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="modal-grid-2">
                    <div>
                        <label class="modal-label">Edad <span style="color:#fb923c">*</span></label>
                        <input type="number" name="edad" required min="18" max="70" placeholder="Ej: 23" class="modal-input">
                    </div>
                    <div>
                        <label class="modal-label">Municipio donde vives <span style="color:#fb923c">*</span></label>
                        <input type="text" name="municipio" required placeholder="Ej: Masatepe" class="modal-input">
                    </div>
                </div>

                <div>
                    <label class="modal-label">Experiencia relevante</label>
                    <textarea name="experiencia" rows="3" placeholder="Describe brevemente tu experiencia..." class="modal-input" style="resize:none;"></textarea>
                </div>

                <div>
                    <label class="modal-label">Disponibilidad horaria <span style="color:#fb923c">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;" class="modal-grid-4">
                        @foreach(['Mañana', 'Tarde', 'Noche', 'Fines de semana'] as $turno)
                        <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:10px;border:1.5px solid #333;cursor:pointer;transition:border-color 0.2s,background 0.2s;"
                               onmouseover="this.style.borderColor='#f97316'"
                               onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='#333'">
                            <input type="checkbox" name="disponibilidad[]" value="{{ $turno }}" style="width:15px;height:15px;accent-color:#f97316;">
                            <span style="font-size:12px;color:#d1d5db;font-weight:600;">{{ $turno }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="modal-label">
                        Currículum Vitae <span style="color:#4b5563;font-weight:400;text-transform:none;">(PDF, DOC — máx. 5MB)</span>
                    </label>
                    <label id="cvDropzone"
                           style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;height:110px;border-radius:12px;border:2px dashed #333;background:#252525;cursor:pointer;transition:all 0.2s;box-sizing:border-box;"
                           onmouseover="this.style.borderColor='#f97316';this.style.background='#2a2a2a'"
                           onmouseout="if(!cvTieneArchivo){this.style.borderColor='#333';this.style.background='#252525'}">
                        <div id="cvPlaceholder" style="text-align:center;">
                            <svg style="margin:0 auto 8px;color:#4b5563;width:28px;height:28px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p style="font-size:13px;color:#6b7280;margin:0;">Arrastra tu CV aquí o <span style="color:#fb923c;font-weight:700;">selecciona archivo</span></p>
                        </div>
                        <div id="cvSelected" style="display:none;text-align:center;">
                            <svg style="margin:0 auto 4px;color:#4ade80;width:26px;height:26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p id="cvFileName" style="font-size:13px;color:#4ade80;font-weight:700;margin:0;"></p>
                        </div>
                        <input id="cvInput" type="file" name="curriculum" accept=".pdf,.doc,.docx" style="display:none;" onchange="handleCvChange(this)">
                    </label>
                </div>

                <div>
                    <label class="modal-label">Mensaje adicional (opcional)</label>
                    <textarea name="mensaje" rows="2" placeholder="¿Algo más que quieras contarle al empleador?" class="modal-input" style="resize:none;"></textarea>
                </div>

                <button type="submit" id="submitBtn"
                        style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:800;font-size:15px;padding:15px 24px;border-radius:14px;border:none;cursor:pointer;transition:all 0.3s;font-family:'Instrument Sans',sans-serif;letter-spacing:0.02em;"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(234,88,12,0.45)'"
                        onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span id="submitText">Enviar mi aplicación</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        var cvTieneArchivo = false;

        function abrirModalAplicar() {
            document.getElementById('applyModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalAplicar() {
            document.getElementById('applyModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.getElementById('applyModal').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalAplicar();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') cerrarModalAplicar();
        });

        function handleCvChange(input) {
            if (input.files && input.files[0]) {
                cvTieneArchivo = true;
                document.getElementById('cvPlaceholder').style.display = 'none';
                document.getElementById('cvSelected').style.display    = 'block';
                document.getElementById('cvFileName').textContent       = input.files[0].name;
                document.getElementById('cvDropzone').style.borderColor = '#22c55e';
                document.getElementById('cvDropzone').style.background  = '#1a2e1a';
            }
        }

        document.getElementById('applyForm').addEventListener('submit', function() {
            var btn  = document.getElementById('submitBtn');
            var text = document.getElementById('submitText');
            text.textContent  = 'Enviando...';
            btn.disabled      = true;
            btn.style.opacity = '0.7';
            btn.style.cursor  = 'not-allowed';
        });

        // Intersection Observer para animar elementos al hacer scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.anim-fade-up, .anim-slide-r, .anim-slide-l, .anim-scale-in').forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    </script>
</body>
</html>