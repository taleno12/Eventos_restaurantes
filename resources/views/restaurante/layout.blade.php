<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — {{ $restaurante->nombre }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ════════════════════════════════════════
           TEMA CLARO (default)
        ════════════════════════════════════════ */
        :root {
            --sidebar-w: 255px;
            --topbar-h: 60px;
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --primary-border: #bfdbfe;
            --bg: #f5f6fa;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e8eaed;
            --text: #1a1d23;
            --muted: #4a5568;
            --card-bg: #ffffff;
            --card-border: #e8eaed;
            --input-bg: #fafbfc;
            --input-border: #e2e5ec;
            --hover-bg: #f5f6fa;
            --table-header: #fafbfc;
            --table-hover: #f8f9fc;
            --badge-gray-bg: #f5f6fa;
            --badge-gray-text: #5a6175;
            --shadow: rgba(0, 0, 0, 0.04);
            --overlay-bg: rgba(15, 18, 26, 0.4);
        }

        /* ════════════════════════════════════════
           TEMA OSCURO
        ════════════════════════════════════════ */
        [data-theme="dark"] {
            --primary: #3b82f6;
            --primary-light: #1e3a5f;
            --primary-border: #1e40af;
            --bg: #0f172a;
            --sidebar-bg: #1e293b;
            --sidebar-border: #334155;
            --text: #f1f5f9;
            --muted: #94a3b8;
            --card-bg: #1e293b;
            --card-border: #334155;
            --input-bg: #0f172a;
            --input-border: #334155;
            --hover-bg: #334155;
            --table-header: #1e293b;
            --table-hover: #334155;
            --badge-gray-bg: #334155;
            --badge-gray-text: #cbd5e1;
            --shadow: rgba(0, 0, 0, 0.3);
            --overlay-bg: rgba(0, 0, 0, 0.6);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            font-size: 14px;
            transition: background 0.3s, color 0.3s;
        }

        /* ── Toggle tema en sidebar ── */
        .theme-toggle-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            color: var(--muted);
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.18s ease;
            margin-bottom: 2px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .theme-toggle-nav:hover {
            color: var(--text);
            background: var(--hover-bg);
        }

        .theme-toggle-nav i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--badge-gray-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--muted);
            flex-shrink: 0;
            transition: all 0.18s;
        }

        .theme-toggle-nav:hover i {
            background: var(--hover-bg);
            color: var(--text);
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s cubic-bezier(.4, 0, .2, 1), background 0.3s;
            overflow-y: auto;
            box-shadow: 2px 0 12px var(--shadow);
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        /* Brand */
        .sidebar-brand {
            padding: 18px 20px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        .brand-logo i {
            color: white;
            font-size: 15px;
        }

        .brand-name {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 10.5px;
            color: var(--muted);
            font-weight: 500;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 10px 12px;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 16px 8px 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            color: var(--muted);
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.18s ease;
            margin-bottom: 2px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            position: relative;
        }

        .nav-link i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--badge-gray-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--muted);
            flex-shrink: 0;
            transition: all 0.18s;
        }

        .nav-link:hover {
            color: var(--text);
            background: var(--hover-bg);
        }

        .nav-link:hover i {
            background: var(--hover-bg);
            color: var(--text);
        }

        .nav-link.active {
            color: var(--primary);
            background: var(--primary-light);
        }

        .nav-link.active i {
            background: var(--primary-border);
            color: var(--primary);
        }

        /* ── Notification badge ── */
        .nav-notif {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: 800;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            line-height: 1;
            flex-shrink: 0;
        }

        /* Footer */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--sidebar-border);
        }

        .sidebar-footer .nav-link {
            font-size: 13px;
            color: var(--muted);
        }

        .sidebar-footer .nav-link:hover {
            color: var(--text);
        }

        .nav-link-danger {
            color: #ef4444 !important;
        }

        .nav-link-danger i {
            color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.1) !important;
        }

        .nav-link-danger:hover {
            background: rgba(239, 68, 68, 0.1) !important;
        }

        /* ── Topbar (móvil) ── */
        .topbar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-h);
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--sidebar-border);
            z-index: 1039;
            align-items: center;
            padding: 0 16px;
            gap: 12px;
            box-shadow: 0 1px 8px var(--shadow);
        }

        @media (max-width: 991px) {
            .topbar {
                display: flex;
            }
        }

        .hamburger {
            background: var(--hover-bg);
            border: 1px solid var(--sidebar-border);
            color: var(--text);
            width: 36px;
            height: 36px;
            border-radius: 9px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .hamburger:hover {
            background: var(--card-border);
        }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: var(--overlay-bg);
            z-index: 1038;
            backdrop-filter: blur(3px);
        }

        .sidebar-overlay.open {
            display: block;
        }

        /* ── Main content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding: 28px 32px;
            min-height: 100vh;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                padding: calc(var(--topbar-h) + 16px) 16px 24px;
            }
        }

        /* ── Page header ── */
        .page-header {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
        }

        .page-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 2px;
            font-weight: 500;
        }

        /* ── Cards ── */
        .panel-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            box-shadow: 0 1px 4px var(--shadow);
            transition: background 0.3s, border-color 0.3s;
        }

        .panel-card .card-body {
            padding: 22px;
        }

        .panel-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--card-border);
            padding: 14px 22px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            border-radius: 14px 14px 0 0;
        }

        /* ── Metric cards ── */
        .metric-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 1px 4px var(--shadow);
            transition: box-shadow 0.2s, transform 0.2s, background 0.3s;
        }

        .metric-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            flex-shrink: 0;
        }

        .metric-icon.orange {
            background: var(--primary-light);
            color: var(--primary);
        }

        .metric-icon.green {
            background: rgba(22, 163, 74, 0.1);
            color: #22c55e;
        }

        .metric-icon.blue {
            background: var(--primary-light);
            color: var(--primary);
        }

        .metric-icon.purple {
            background: rgba(147, 51, 234, 0.1);
            color: #a855f7;
        }

        .metric-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
        }

        .metric-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
        }

        /* ── Buttons ── */
        .btn-primary-panel {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        .btn-primary-panel:hover {
            background: #1d4ed8;
            color: white;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .btn-secondary-panel {
            background: var(--card-bg);
            color: var(--muted);
            border: 1px solid var(--card-border);
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-secondary-panel:hover {
            background: var(--hover-bg);
            color: var(--text);
        }

        .btn-danger-panel {
            background: transparent;
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-danger-panel:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* ── Form overrides ── */
        .form-control,
        .form-select {
            background: var(--input-bg) !important;
            border: 1px solid var(--input-border) !important;
            color: var(--text) !important;
            border-radius: 10px !important;
            font-size: 13.5px !important;
            font-family: inherit !important;
            padding: 9px 14px !important;
            transition: background 0.3s, border-color 0.3s, color 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
        }

        .form-control::placeholder {
            color: var(--muted) !important;
            opacity: 0.6;
        }

        .input-group-text {
            background: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--muted) !important;
        }

        /* ── Alerts ── */
        .panel-alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panel-alert-success {
            background: rgba(22, 163, 74, 0.1);
            border: 1px solid rgba(22, 163, 74, 0.2);
            color: #22c55e;
        }

        .panel-alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        /* ── Table ── */
        .panel-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .panel-table th {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 10px 16px;
            border-bottom: 1px solid var(--card-border);
            text-align: left;
            background: var(--table-header);
            transition: background 0.3s;
        }

        .panel-table td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--card-border);
            color: var(--text);
            vertical-align: middle;
            transition: color 0.3s;
        }

        .panel-table tr:last-child td {
            border-bottom: none;
        }

        .panel-table tbody tr:hover td {
            background: var(--table-hover);
        }

        /* ── Badges ── */
        .panel-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .badge-green {
            background: rgba(22, 163, 74, 0.1);
            color: #22c55e;
            border: 1px solid rgba(22, 163, 74, 0.2);
        }

        .badge-red {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-orange {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid var(--primary-border);
        }

        .badge-gray {
            background: var(--badge-gray-bg);
            color: var(--badge-gray-text);
            border: 1px solid var(--card-border);
        }

        .badge-blue {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid var(--primary-border);
        }

        /* ── Action icon buttons ── */
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: 1px solid var(--card-border);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            background: var(--card-bg);
            color: var(--muted);
        }

        .action-btn:hover {
            color: var(--text);
            background: var(--hover-bg);
        }

        .action-btn-view:hover {
            color: var(--primary);
            background: var(--primary-light);
            border-color: var(--primary-border);
        }

        .action-btn-edit:hover {
            color: var(--primary);
            background: var(--primary-light);
            border-color: var(--primary-border);
        }

        .action-btn-delete:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 52px 24px;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 14px;
            display: block;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--muted);
            border-radius: 4px;
            opacity: 0.5;
        }

        /* ── Animaciones ── */
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        }
    </style>

    @yield('styles')
</head>

<body>

    {{-- Overlay móvil --}}
    <div class="sidebar-overlay" id="overlay"></div>

    {{-- Topbar móvil --}}
    <div class="topbar">
        <button class="hamburger" id="hamburger">
            <i class="bi bi-list" style="font-size:17px;"></i>
        </button>
        <div class="d-flex align-items-center gap-2">
            <div
                style="width:28px;height:28px;background:linear-gradient(135deg,#2563eb,#1d4ed8);border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(37,99,235,.3)">
                <i class="bi bi-shop" style="color:white;font-size:12px;"></i>
            </div>
            <span style="font-weight:800;font-size:13px;color:var(--text);">{{ Str::limit($restaurante->nombre, 22)
                }}</span>
        </div>
    </div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-logo"><i class="bi bi-shop"></i></div>
            <div>
                <div class="brand-name">{{ Str::limit($restaurante->nombre, 17) }}</div>
                <div class="brand-sub">Panel del restaurante</div>
            </div>
        </div>

        @php
        // ── Contadores de notificaciones ──────────────────────────────────
        $notifPedidos = \App\Models\Pedido::where('restaurante_id', $restaurante->id)
        ->where('estado', 'pendiente')
        ->count();

        $notifSolicitudes = \App\Models\SolicitudEmpleo::whereHas('empleo', fn($q) =>
        $q->where('restaurante_id', $restaurante->id))
        ->where('estado', 'nueva')
        ->count();

        $notifReviews = \App\Models\Review::where('restaurante_id', $restaurante->id)
        ->where('vista', false)
        ->count();
        @endphp

        <nav class="sidebar-nav">

            <div class="nav-section">Principal</div>
            <a href="{{ route('restaurante.dashboard') }}"
                class="nav-link {{ request()->routeIs('restaurante.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>

            <div class="nav-section">Gestión</div>

            <a href="{{ route('restaurante.eventos.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.eventos.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill"></i>
                Mis Eventos
            </a>

            <a href="{{ route('restaurante.empleos.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.empleos.*') ? 'active' : '' }}">
                <i class="bi bi-briefcase-fill"></i>
                Mis Empleos
                @if($notifSolicitudes > 0)
                <span class="nav-notif">{{ $notifSolicitudes }}</span>
                @endif
            </a>

            <a href="{{ route('restaurante.platos.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.platos.*') ? 'active' : '' }}">
                <i class="bi bi-egg-fried"></i>
                Menú
            </a>

            <a href="{{ route('restaurante.galeria.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.galeria.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i>
                Galería
            </a>

            <a href="{{ route('restaurante.pedidos.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.pedidos.*') ? 'active' : '' }}">
                <i class="bi bi-bag-fill"></i>
                Pedidos
                @if($notifPedidos > 0)
                <span class="nav-notif">{{ $notifPedidos }}</span>
                @endif
            </a>

            <a href="{{ route('restaurante.estadisticas.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.estadisticas.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                Estadísticas
            </a>
<a href="{{ route('restaurante.soporte') }}"
                class="nav-link {{ request()->routeIs('restaurante.soporte') ? 'active' : '' }}">
                <i class="bi bi-headset"></i>
                Soporte
            </a>

            <a href="{{ route('restaurante.reviews.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star-fill"></i>
                Reseñas
                @if($notifReviews > 0)
                <span class="nav-notif">{{ $notifReviews }}</span>
                @endif
            </a>

            <a href="{{ route('restaurante.info.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.info.*') ? 'active' : '' }}">
                <i class="bi bi-info-circle-fill"></i>
                Ayuda e Info
            </a>

            <a href="{{ route('restaurante.notificaciones.index') }}"
                class="nav-link {{ request()->routeIs('restaurante.notificaciones.*') ? 'active' : '' }}"
                style="position:relative;">
                <i class="bi bi-bell-fill"></i>
                Notificaciones
                @php $noLeidas = \App\Models\Notificacion::where('user_id', auth()->id())->where('leida',
                false)->count(); @endphp
                @if($noLeidas > 0)
                <span
                    style="margin-left:auto;background:var(--primary);color:white;font-size:10px;font-weight:800;padding:2px 7px;border-radius:999px;">{{
                    $noLeidas }}</span>
                @endif
            </a>

            <a href="{{ route('restaurante.perfil.edit') }}"
                class="nav-link {{ request()->routeIs('restaurante.perfil.*') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                Mi Perfil
            </a>

            {{-- Toggle modo oscuro integrado en el menú --}}
            <div class="nav-section">Preferencias</div>
            <button class="theme-toggle-nav" onclick="toggleTheme()" id="themeToggleBtn">
                <i class="bi bi-moon-fill" id="themeIconNav"></i>
                <span id="themeText">Modo oscuro</span>
            </button>

        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('restaurantes.show', $restaurante) }}" class="nav-link" style="color:var(--muted);">
                <i class="bi bi-box-arrow-up-right"></i>
                Ver mi página
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link nav-link-danger w-100 text-start">
                    <i class="bi bi-power"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenido principal --}}
    <main class="main-content">

        @if(session('success'))
        <div class="panel-alert panel-alert-success">
            <i class="bi bi-check-circle-fill fs-5"></i>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="panel-alert panel-alert-error">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Sidebar móvil ──
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const hamburger = document.getElementById('hamburger');
        hamburger?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });

        // ── Tema claro/oscuro ──
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIconNav');
            const text = document.getElementById('themeText');
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);

            if (next === 'dark') {
                icon.className = 'bi bi-sun-fill';
                text.textContent = 'Modo claro';
            } else {
                icon.className = 'bi bi-moon-fill';
                text.textContent = 'Modo oscuro';
            }
        }

        // Cargar tema guardado
        (function() {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            const icon = document.getElementById('themeIconNav');
            const text = document.getElementById('themeText');
            if (saved === 'dark') {
                icon.className = 'bi bi-sun-fill';
                text.textContent = 'Modo claro';
            }
        })();
    </script>

    @yield('scripts')
</body>

</html>
