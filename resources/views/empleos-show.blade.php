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
            --blue: #2563eb;
            --blue-light: #3b82f6;
            --dark: #0f172a;
            --cream: #f8fafc;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--cream);
            color: #0f172a;
            overflow-x: hidden;
        }

        .premium-title { font-family: 'Playfair Display', serif; }

        /* ── ANIMACIONES ── */
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
            0%, 100% { box-shadow: 0 0 20px rgba(37,99,235,0.3); }
            50%       { box-shadow: 0 0 40px rgba(37,99,235,0.6); }
        }

        .anim-fade-up   { animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-fade-in   { animation: fadeIn 0.6s ease both; }
        .anim-slide-r   { animation: slideRight 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-slide-l   { animation: slideLeft 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-scale-in  { animation: scaleIn 0.6s cubic-bezier(0.16,1,0.3,1) both; }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
        .delay-7 { animation-delay: 0.7s; }



        /* ── RESTAURANT INFO BAR (estilo PedidosYa) ── */
        .resto-bar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 2px 12px rgba(15,23,42,0.06);
        }
        .resto-bar-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .resto-bar-top {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        .resto-logo {
            width: 64px; height: 64px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            overflow: hidden;
            flex-shrink: 0;
            background: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
        }
        .resto-logo img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .resto-logo-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--blue), var(--blue-light));
            display: flex; align-items: center; justify-content: center;
        }
        .resto-info { flex: 1; min-width: 0; }
        .resto-name {
            font-size: 18px; font-weight: 800; color: #0f172a;
            line-height: 1.2; margin-bottom: 5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .resto-meta {
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        }
        .resto-badge-tipo {
            display: inline-flex; align-items: center; gap: 5px;
            background: #eff6ff; color: var(--blue);
            border: 1.5px solid #bfdbfe;
            font-size: 11px; font-weight: 700;
            padding: 3px 10px; border-radius: 999px;
        }
        .resto-reviews {
            display: flex; align-items: center; gap: 4px;
            font-size: 12px; color: #64748b; font-weight: 600;
        }
        .resto-reviews i { color: #f59e0b; font-size: 11px; }
        .resto-location-pill {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 12px; color: #64748b; font-weight: 500;
        }
        .resto-location-pill i { color: var(--blue); font-size: 10px; }
        .resto-back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            border: 1.5px solid #e2e8f0; color: #64748b;
            font-size: 12px; font-weight: 600;
            padding: 8px 16px; border-radius: 999px;
            text-decoration: none; transition: all 0.2s;
            white-space: nowrap; flex-shrink: 0;
        }
        .resto-back-btn:hover {
            border-color: #0f172a; color: #0f172a;
            background: #f8fafc;
        }

        /* Tabs estilo PedidosYa */
        .resto-tabs {
            display: flex;
            gap: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .resto-tabs::-webkit-scrollbar { display: none; }
        .resto-tab {
            padding: 14px 20px;
            font-size: 13px; font-weight: 700;
            color: #94a3b8;
            border: none; background: transparent;
            border-bottom: 3px solid transparent;
            cursor: pointer; white-space: nowrap;
            transition: all 0.18s; font-family: 'Instrument Sans', sans-serif;
            letter-spacing: 0.01em;
        }
        .resto-tab:hover { color: #0f172a; }
        .resto-tab.active {
            color: #0f172a;
            border-bottom-color: #0f172a;
        }

        /* ── MAIN LAYOUT ── */
        .main-wrap {
            max-width: 1100px; margin: 0 auto; padding: 48px 2rem 400px;
            display: grid; grid-template-columns: 1fr 360px;
            gap: 32px; align-items: start;
        }

        /* ── CARDS ── */
        .content-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 24px;
            padding: 32px; position: relative; overflow: hidden;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .content-card:hover { box-shadow: 0 12px 40px rgba(15,23,42,0.08); transform: translateY(-2px); }
        .content-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, var(--blue), var(--blue-light), transparent);
            opacity: 0; transition: opacity 0.3s ease;
        }
        .content-card:hover::before { opacity: 1; }
        .card-header {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;
        }
        .card-icon-wrap {
            width: 38px; height: 38px; background: #eff6ff; border: 1px solid #bfdbfe;
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: all 0.3s ease;
        }
        .content-card:hover .card-icon-wrap { background: var(--blue); border-color: var(--blue); }
        .content-card:hover .card-icon-wrap i { color: white !important; }
        .card-title-text { font-size: 15px; font-weight: 800; color: #0f172a; }
        .card-body-text { color: #475569; font-size: 14px; line-height: 1.85; white-space: pre-line; }
        .empty-text { color: #94a3b8; font-size: 13px; font-style: italic; display: flex; align-items: center; gap: 8px; }

        /* ── SIDEBAR ── */
        .sidebar-sticky { position: sticky; top: 100px; display: flex; flex-direction: column; gap: 20px; }
        .summary-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 24px;
            overflow: hidden; box-shadow: 0 4px 24px rgba(15,23,42,0.06); transition: box-shadow 0.3s ease;
        }
        .summary-card:hover { box-shadow: 0 8px 40px rgba(15,23,42,0.1); }
        .summary-card-header {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            padding: 20px 24px; position: relative; overflow: hidden;
        }
        .summary-card-header::after {
            content: ''; position: absolute; top: -40px; right: -40px;
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(37,99,235,0.3) 0%, transparent 70%);
            pointer-events: none;
        }
        .summary-card-header-label {
            font-size: 9px; font-weight: 900; letter-spacing: 0.22em; text-transform: uppercase;
            color: rgba(255,255,255,0.4); margin-bottom: 4px;
        }
        .summary-card-header-title { font-size: 16px; font-weight: 800; color: white; }
        .summary-body { padding: 20px 24px; }
        .stat-row {
            display: flex; flex-direction: column; gap: 4px;
            padding: 16px 0; border-bottom: 1px solid #f1f5f9; position: relative;
        }
        .stat-row:last-child { border-bottom: none; padding-bottom: 0; }
        .stat-label { font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: #94a3b8; }
        .stat-value-salary {
            font-size: 22px; font-weight: 900; color: #16a34a;
            letter-spacing: -0.02em; display: flex; align-items: center; gap: 6px;
        }
        .stat-value-salary.negociar {
            font-size: 18px;
            background: linear-gradient(90deg, var(--blue), var(--blue-light));
            background-size: 200% auto;
            -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }
        .stat-value { font-size: 14px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px; }
        .stat-value-sm { font-size: 12px; font-weight: 600; color: #64748b; }
        .social-section { padding: 20px 24px; border-top: 1px solid #f1f5f9; }
        .social-label {
            font-size: 9px; font-weight: 900; letter-spacing: 0.18em; text-transform: uppercase;
            color: #94a3b8; margin-bottom: 12px; display: block;
        }
        .social-icons { display: flex; flex-wrap: wrap; gap: 10px; }
        .social-btn {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: all 0.25s cubic-bezier(0.16,1,0.3,1);
            position: relative; overflow: hidden;
        }
        .social-btn:hover { transform: translateY(-3px) scale(1.08); }
        .social-wa  { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .social-wa:hover  { background: #16a34a; color: white; border-color: #16a34a; box-shadow: 0 6px 20px rgba(22,163,74,0.35); }
        .social-ig  { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }
        .social-ig:hover  { background: linear-gradient(135deg, #f59e0b, #ec4899, #8b5cf6); color: white; border-color: transparent; box-shadow: 0 6px 20px rgba(219,39,119,0.35); }
        .social-tt  { background: #fafaf9; color: #0f172a; border: 1px solid #e2e8f0; }
        .social-tt:hover  { background: #0f172a; color: white; border-color: #0f172a; box-shadow: 0 6px 20px rgba(15,23,42,0.35); }
        .social-fb  { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .social-fb:hover  { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 6px 20px rgba(37,99,235,0.35); }
        .apply-section { padding: 20px 24px; border-top: 1px solid #f1f5f9; }
        .btn-apply {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white;
            font-weight: 800; font-size: 14px; padding: 14px 24px; border-radius: 14px;
            border: none; cursor: pointer; transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            position: relative; overflow: hidden; letter-spacing: 0.02em;
            animation: pulse-glow 3s ease-in-out infinite;
        }
        .btn-apply::before {
            content: ''; position: absolute; top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }
        .btn-apply:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 12px 32px rgba(37,99,235,0.45); animation: none; }
        .btn-apply:hover::before { left: 100%; }
        .btn-apply:active { transform: scale(0.98); }

        .alert-success {
            display: flex; align-items: center; gap: 10px;
            background: #f0fdf4; border: 1px solid #bbf7d0;
            color: #15803d; font-size: 13px; font-weight: 600;
            padding: 12px 16px; border-radius: 14px; animation: fadeUp 0.5s ease both;
        }
        .alert-error {
            display: flex; align-items: center; gap: 10px;
            background: #fef2f2; border: 1px solid #fecaca;
            color: #dc2626; font-size: 13px; font-weight: 600;
            padding: 12px 16px; border-radius: 14px; animation: fadeUp 0.5s ease both;
        }

        /* ── MODAL ── */
        @keyframes gastroSlideUp {
            from { opacity:0; transform:translateY(24px) scale(0.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }
        #applyModal {
            display: none; position: fixed; inset: 0; z-index: 9999;
            align-items: center; justify-content: center; padding: 1rem;
            background: rgba(0,0,0,0.85); backdrop-filter: blur(8px);
        }
        .modal-inner {
            position: relative; width: 100%; max-width: 680px;
            max-height: 90vh; overflow-y: auto; border-radius: 20px;
            background: #1a1a1a; border: 1px solid #2e2e2e;
            animation: gastroSlideUp 0.35s cubic-bezier(0.16,1,0.3,1);
        }
        .modal-header {
            position: sticky; top: 0; z-index: 10;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.5rem 1.75rem; border-bottom: 1px solid #2e2e2e;
            background: linear-gradient(135deg, #1a1a1a, #222);
        }
        .modal-close {
            background: rgba(255,255,255,0.05); border: 1px solid #333;
            color: #9ca3af; cursor: pointer; padding: 8px;
            border-radius: 10px; transition: all 0.2s; display: flex;
        }
        .modal-close:hover { background: #333; color: white; transform: rotate(90deg); }
        .modal-input {
            width: 100%; padding: 12px 14px; border-radius: 10px;
            background: #252525; border: 1.5px solid #333; color: #fff;
            font-size: 14px; outline: none; box-sizing: border-box;
            font-family: 'Instrument Sans', sans-serif; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .modal-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
        .modal-input::placeholder { color: #4b5563; }
        .modal-label {
            display: block; font-size: 11px; font-weight: 700;
            color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
        }

        @media (max-width: 900px) {
            .main-wrap { grid-template-columns: 1fr; }
            .sidebar-sticky { position: static; }
        }
        @media (max-width: 640px) {
            .resto-bar-top { gap: 12px; padding: 12px 0 10px; }
            .resto-logo { width: 52px; height: 52px; border-radius: 12px; }
            .resto-name { font-size: 15px; }
            .resto-back-btn span { display: none; }
        }
       @media (max-width: 580px) {
            .modal-grid-2 { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    {{-- ══ RESTAURANT BAR (estilo PedidosYa) ══ --}}
    @php
        $establecimiento = $empleo->gastrobar_id ? $empleo->gastrobar : $empleo->restaurante;
        $esGastrobar     = (bool) $empleo->gastrobar_id;
    @endphp
    <div class="resto-bar anim-fade-in">
        <div class="resto-bar-inner">
            <div class="resto-bar-top">

                {{-- Logo del establecimiento --}}
                <div class="resto-logo">
                    @if(!empty($establecimiento?->logo) || !empty($establecimiento?->imagen))
                        <img src="{{ asset('storage/' . ($establecimiento->logo ?? $establecimiento->imagen)) }}"
                             alt="{{ $establecimiento->nombre }}" loading="lazy">
                    @else
                        <div class="resto-logo-placeholder">
                            <i class="{{ $esGastrobar ? 'fas fa-glass-martini-alt' : 'fas fa-utensils' }}"
                               style="color:white;font-size:20px;"></i>
                        </div>
                    @endif
                </div>

                {{-- Info del establecimiento --}}
                <div class="resto-info">
                    <div class="resto-name">{{ $establecimiento?->nombre }}</div>
                    <div class="resto-meta">
                        @if($empleo->tipo_contrato)
                            <span class="resto-badge-tipo">
                                <i class="fas fa-clock" style="font-size:9px;"></i>
                                {{ $empleo->tipo_contrato }}
                            </span>
                        @endif
                        <span class="resto-reviews">
                            <i class="fas fa-star"></i>
                            {{ $esGastrobar ? 'Gastrobar' : 'Restaurante' }}
                        </span>
                        <span class="resto-location-pill">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $empleo->departamento->nombre }}@if($empleo->municipio), {{ $empleo->municipio->nombre }}@endif
                        </span>
                    </div>
                </div>

                {{-- Botón volver --}}
                <a href="{{ route('empleos.index') }}" class="resto-back-btn">
                    <i class="fas fa-chevron-left" style="font-size:10px;"></i>
                    <span>Volver a empleos</span>
                </a>

            </div>

            {{-- Tabs --}}
            <nav class="resto-tabs">
                <button class="resto-tab active" onclick="scrollToSection('descripcion', this)">Descripción</button>
                <button class="resto-tab"        onclick="scrollToSection('requisitos', this)">Requisitos</button>
                <button class="resto-tab"        onclick="scrollToSection('detalle', this)">Detalles</button>
            </nav>
        </div>
    </div>

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

        <div style="display:flex;flex-direction:column;gap:20px;">

            <div id="descripcion" class="content-card anim-slide-r delay-2">
                <div class="card-header">
                    <div class="card-icon-wrap">
                        <i class="fas fa-align-left" style="color:#2563eb;font-size:14px;"></i>
                    </div>
                    <span class="card-title-text">Descripción de la vacante</span>
                </div>
                <p class="card-body-text">{{ $empleo->descripcion }}</p>
            </div>

            <div id="requisitos" class="content-card anim-slide-r delay-3">
                <div class="card-header">
                    <div class="card-icon-wrap">
                        <i class="fas fa-clipboard-list" style="color:#2563eb;font-size:14px;"></i>
                    </div>
                    <span class="card-title-text">Requisitos del puesto</span>
                </div>
                @if($empleo->requisitos)
                    <p class="card-body-text">{{ $empleo->requisitos }}</p>
                @else
                    <p class="empty-text">
                        <i class="fas fa-info-circle" style="color:#cbd5e1;font-size:14px;"></i>
                        El establecimiento no especificó requisitos adicionales para esta posición.
                    </p>
                @endif
            </div>

            {{-- Detalles extra como card adicional --}}
            <div id="detalle" class="content-card anim-slide-r delay-4">
                <div class="card-header">
                    <div class="card-icon-wrap">
                        <i class="fas fa-receipt" style="color:#2563eb;font-size:14px;"></i>
                    </div>
                    <span class="card-title-text">Detalles de la oferta</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:0;">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:13px;color:#64748b;font-weight:500;">Publicado</span>
                        <span style="font-size:13px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:6px;">
                            <i class="far fa-clock" style="color:#2563eb;font-size:11px;"></i>
                            {{ $empleo->created_at->diffForHumans() }}
                        </span>
                    </div>
                    @if($empleo->fecha_limite)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:13px;color:#64748b;font-weight:500;">Fecha límite</span>
                        <span style="font-size:13px;font-weight:700;color:#dc2626;display:flex;align-items:center;gap:6px;">
                            <i class="far fa-calendar-times" style="font-size:11px;"></i>
                            {{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d \d\e M, Y') }}
                        </span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;">
                        <span style="font-size:13px;color:#64748b;font-weight:500;">Establecimiento</span>
                        <span style="font-size:13px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:6px;">
                            <i class="{{ $esGastrobar ? 'fas fa-glass-martini-alt' : 'fas fa-store' }}" style="color:#2563eb;font-size:11px;"></i>
                            {{ $establecimiento?->nombre }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="sidebar-sticky">
            <div class="summary-card anim-slide-l delay-2">

                <div class="summary-card-header">
                    <div class="summary-card-header-label">Resumen de oferta</div>
                    <div class="summary-card-header-title">{{ $empleo->titulo }}</div>
                </div>

                <div class="summary-body">
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

                    @if($empleo->fecha_limite)
                        <div class="stat-row">
                            <span class="stat-label">Fecha límite para aplicar</span>
                            <span class="stat-value">
                                <i class="far fa-calendar-times" style="color:#dc2626;font-size:13px;"></i>
                                {{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d \d\e M, Y') }}
                            </span>
                        </div>
                    @endif

                    <div class="stat-row">
                        <span class="stat-label">Publicado</span>
                        <span class="stat-value-sm">
                            <i class="far fa-clock" style="margin-right:4px;"></i>
                            {{ $empleo->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <div class="social-section">
                    <span class="social-label">Conoce el establecimiento</span>
                    <div class="social-icons">
                        @if(!empty($establecimiento?->whatsapp))
                            @php $phoneClean = preg_replace('/[^0-9]/', '', $establecimiento->whatsapp); @endphp
                            <a href="https://wa.me/{{ $phoneClean }}" target="_blank" class="social-btn social-wa" title="WhatsApp">
                                <i class="fab fa-whatsapp" style="font-size:17px;"></i>
                            </a>
                        @endif
                        @if(!empty($establecimiento?->instagram))
                            <a href="{{ $establecimiento->instagram }}" target="_blank" class="social-btn social-ig" title="Instagram">
                                <i class="fab fa-instagram" style="font-size:17px;"></i>
                            </a>
                        @endif
                        @if(!empty($establecimiento?->tiktok))
                            <a href="{{ $establecimiento->tiktok }}" target="_blank" class="social-btn social-tt" title="TikTok">
                                <i class="fab fa-tiktok" style="font-size:15px;"></i>
                            </a>
                        @endif
                        @if(!empty($establecimiento?->facebook))
                            <a href="{{ $establecimiento->facebook }}" target="_blank" class="social-btn social-fb" title="Facebook">
                                <i class="fab fa-facebook-f" style="font-size:15px;"></i>
                            </a>
                        @endif
                        @if(empty($establecimiento?->whatsapp) && empty($establecimiento?->instagram) && empty($establecimiento?->tiktok) && empty($establecimiento?->facebook))
                            <span style="font-size:12px;color:#94a3b8;font-style:italic;">Sin redes configuradas.</span>
                        @endif
                    </div>
                </div>

                <div class="apply-section">
                    <button type="button" onclick="abrirModalAplicar()" class="btn-apply">
                        <i class="fas fa-paper-plane" style="font-size:13px;"></i>
                        Aplicar a esta vacante
                    </button>
                </div>

            </div>
        </div>
    </main>

         {{-- ══ FOOTER ══ --}}
        <footer class="bg-slate-900 text-slate-300 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 pt-12 pb-8 sm:pt-16 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 mb-10">
                    <div class="sm:col-span-2 lg:col-span-4 space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-blue-500">Nicaragua</span></span>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed font-light">
                            La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
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

    {{-- ── MODAL ── --}}
    <div id="applyModal">
        <div class="modal-inner">
            <div class="modal-header">
                <div>
                    <p style="font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#3b82f6;margin:0 0 4px;">
                        @if($empleo->gastrobar_id)
                            {{ $empleo->gastrobar?->nombre ?? 'Gastrobar' }}
                        @else
                            {{ $empleo->restaurante?->nombre ?? 'Restaurante' }}
                        @endif
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
                <input type="hidden" name="restaurante_email" value="{{ $establecimiento?->email ?? '' }}">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="modal-grid-2">
                    <div>
                        <label class="modal-label">Nombre <span style="color:#3b82f6">*</span></label>
                        <input type="text" name="nombre" required placeholder="Tu nombre" class="modal-input">
                    </div>
                    <div>
                        <label class="modal-label">Apellido <span style="color:#3b82f6">*</span></label>
                        <input type="text" name="apellido" required placeholder="Tu apellido" class="modal-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="modal-grid-2">
                    <div>
                        <label class="modal-label">
                            @auth
                                {{ auth()->user()->telefono ? 'Número de teléfono' : 'Correo electrónico' }}
                            @else
                                Correo electrónico
                            @endauth
                            <span style="color:#3b82f6">*</span>
                        </label>
                        @auth
                            @if(auth()->user()->telefono)
                                {{-- Usuario registrado con teléfono: mostrar su número, pero seguir enviando el email real (autogenerado) por debajo --}}
                                <input type="text" value="{{ auth()->user()->telefono }}"
                                       class="modal-input" readonly
                                       style="opacity:0.6;cursor:not-allowed;">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <p style="font-size:11px;color:#6b7280;margin:4px 0 0 2px;">
                                    <i class="fas fa-lock" style="font-size:9px;margin-right:4px;"></i>
                                    Número vinculado a tu cuenta
                                </p>
                            @else
                                {{-- Usuario registrado con Google: mostrar y enviar su correo real --}}
                                <input type="email" name="email" value="{{ auth()->user()->email }}"
                                       class="modal-input" readonly
                                       style="opacity:0.6;cursor:not-allowed;">
                                <p style="font-size:11px;color:#6b7280;margin:4px 0 0 2px;">
                                    <i class="fas fa-lock" style="font-size:9px;margin-right:4px;"></i>
                                    Correo vinculado a tu cuenta
                                </p>
                            @endif
                        @else
                            <input type="email" name="email" required placeholder="correo@ejemplo.com" class="modal-input">
                        @endauth
                    </div>
                    <div>
                        <label class="modal-label">Teléfono / WhatsApp <span style="color:#3b82f6">*</span></label>
                        <input type="tel" name="telefono" required placeholder="+505 8888-0000" class="modal-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="modal-grid-2">
                    <div>
                        <label class="modal-label">Edad <span style="color:#3b82f6">*</span></label>
                        <input type="number" name="edad" required min="18" max="70" placeholder="Ej: 23" class="modal-input">
                    </div>
                    <div>
                        <label class="modal-label">Municipio donde vives <span style="color:#3b82f6">*</span></label>
                        <input type="text" name="municipio" required placeholder="Ej: Masatepe" class="modal-input">
                    </div>
                </div>

                <div>
                    <label class="modal-label">Experiencia relevante</label>
                    <textarea name="experiencia" rows="3" placeholder="Describe brevemente tu experiencia..." class="modal-input" style="resize:none;"></textarea>
                </div>



                <div>
                    <label class="modal-label">
                        Currículum Vitae <span style="color:#4b5563;font-weight:400;text-transform:none;">(PDF, DOC — máx. 5MB)</span>
                    </label>
                    <label id="cvDropzone"
                           style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;height:110px;border-radius:12px;border:2px dashed #333;background:#252525;cursor:pointer;transition:all 0.2s;box-sizing:border-box;"
                           onmouseover="this.style.borderColor='#3b82f6';this.style.background='#2a2a2a'"
                           onmouseout="if(!cvTieneArchivo){this.style.borderColor='#333';this.style.background='#252525'}">
                        <div id="cvPlaceholder" style="text-align:center;">
                            <svg style="margin:0 auto 8px;color:#4b5563;width:28px;height:28px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p style="font-size:13px;color:#6b7280;margin:0;">Arrastra tu CV aquí o <span style="color:#3b82f6;font-weight:700;">selecciona archivo</span></p>
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
                        style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;font-weight:800;font-size:15px;padding:15px 24px;border-radius:14px;border:none;cursor:pointer;transition:all 0.3s;font-family:'Instrument Sans',sans-serif;letter-spacing:0.02em;"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(37,99,235,0.45)'"
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

        /* ── Modal ── */
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

        /* ── Tabs scroll ── */
        function scrollToSection(id, tabEl) {
            document.querySelectorAll('.resto-tab').forEach(t => t.classList.remove('active'));
            tabEl.classList.add('active');
            const el = document.getElementById(id);
            if (!el) return;
            const barOffset = document.querySelector('.resto-bar').offsetHeight + 16;
            // Asegurar que haya espacio para scroll antes de mover
            ensureScrollSpace(el, barOffset);
            setTimeout(() => {
                const top = el.getBoundingClientRect().top + window.scrollY - barOffset;
                window.scrollTo({ top, behavior: 'smooth' });
            }, 10);
        }

        function ensureScrollSpace(targetEl, offset) {
            const wrap = document.querySelector('.main-wrap');
            const targetBottom = targetEl.getBoundingClientRect().bottom + window.scrollY;
            const needed = targetBottom - offset + window.innerHeight * 0.5;
            const current = document.documentElement.scrollHeight;
            if (needed > current) {
                const extra = needed - current + 80;
                wrap.style.paddingBottom = (parseInt(wrap.style.paddingBottom || 48) + extra) + 'px';
            }
        }

        /* ── Animaciones on scroll ── */
        const animObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.style.animationPlayState = 'running';
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.anim-fade-up, .anim-fade-in, .anim-slide-r, .anim-slide-l, .anim-scale-in').forEach(el => {
            el.style.animationPlayState = 'paused';
            animObserver.observe(el);
        });
    </script>
</body>
</html>
