<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — {{ $gastrobar->nombre }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 260px;
            --topbar-h: 62px;
            --primary: #8b5cf6;
            --primary-hover: #7c3aed;
            --primary-light: #f5f3ff;
            --primary-border: #ddd6fe;
            --primary-glow: rgba(139, 92, 246, 0.3);
            --bg: #f5f6fa;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e8eaed;
            --text: #1a1d23;
            --text-secondary: #4a5168;
            --muted: #8b92a5;
            --card-bg: #ffffff;
            --card-border: #e8eaed;
            --input-bg: #fafbfc;
            --input-border: #e2e5ec;
            --hover-bg: #f5f6fa;
            --table-header: #fafbfc;
            --table-hover: #f8f9fc;
            --badge-gray-bg: #f5f6fa;
            --badge-gray-text: #5a6175;
            --shadow: rgba(0,0,0,0.04);
            --shadow-lg: rgba(0,0,0,0.08);
            --overlay-bg: rgba(15,18,26,0.5);
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        [data-theme="dark"] {
            --primary: #a78bfa;
            --primary-hover: #8b5cf6;
            --primary-light: #2e1f5e;
            --primary-border: #4c1d95;
            --primary-glow: rgba(167, 139, 250, 0.4);
            --bg: #0f172a;
            --sidebar-bg: #1e293b;
            --sidebar-border: #334155;
            --text: #f1f5f9;
            --text-secondary: #cbd5e1;
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
            --shadow: rgba(0,0,0,0.3);
            --shadow-lg: rgba(0,0,0,0.5);
            --overlay-bg: rgba(0,0,0,0.7);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* ═══════════════════════════════════════════════
           THEME TOGGLE - SWITCH MODERNO
        ═══════════════════════════════════════════════ */
        .theme-toggle-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: var(--muted);
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 2px;
            border: 1px solid transparent;
            background: none;
            width: 100%;
            text-align: left;
            position: relative;
        }
        .theme-toggle-nav:hover {
            color: var(--text);
            background: var(--hover-bg);
            border-color: var(--card-border);
        }
        .theme-toggle-nav:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
        .theme-toggle-nav i {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: var(--badge-gray-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: var(--muted);
            flex-shrink: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-toggle-nav:hover i {
            background: var(--primary-light);
            color: var(--primary);
            transform: rotate(20deg);
        }

        /* ═══════════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════════ */
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
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 2px 0 16px var(--shadow);
        }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }

        /* Brand / Logo */
        .sidebar-brand {
            padding: 20px 20px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        .sidebar-brand::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 20px;
            right: 20px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-border), transparent);
        }
        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px var(--primary-glow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .brand-logo::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border-radius: 14px;
            opacity: 0;
            z-index: -1;
            filter: blur(8px);
            transition: opacity 0.3s ease;
        }
        .sidebar-brand:hover .brand-logo {
            transform: scale(1.05) rotate(-3deg);
        }
        .sidebar-brand:hover .brand-logo::before {
            opacity: 0.6;
        }
        .brand-logo i {
            color: white;
            font-size: 16px;
        }
        .brand-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }
        .brand-sub {
            font-size: 11px;
            color: var(--muted);
            font-weight: 500;
            margin-top: 2px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 12px 14px;
        }
        .nav-section {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 18px 12px 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-section::before {
            content: '';
            width: 3px;
            height: 10px;
            background: var(--primary);
            border-radius: 2px;
            opacity: 0.6;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            color: var(--text-secondary);
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 11px;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 3px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
            transition: height 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link i {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: var(--badge-gray-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14.5px;
            color: var(--muted);
            flex-shrink: 0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link:hover {
            color: var(--text);
            background: var(--hover-bg);
            transform: translateX(2px);
        }
        .nav-link:hover i {
            background: var(--hover-bg);
            color: var(--text);
            transform: scale(1.05);
        }
        .nav-link.active {
            color: var(--primary);
            background: var(--primary-light);
            font-weight: 700;
        }
        .nav-link.active::before {
            height: 60%;
        }
        .nav-link.active i {
            background: var(--primary);
            color: white;
            box-shadow: 0 2px 8px var(--primary-glow);
        }
        .nav-link:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Notification badge con pulse */
        .nav-notif {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 10px;
            font-weight: 800;
            min-width: 19px;
            height: 19px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            line-height: 1;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35);
            animation: pulse-notif 2s infinite;
        }
        @keyframes pulse-notif {
            0%, 100% { box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35); }
            50% { box-shadow: 0 2px 12px rgba(239, 68, 68, 0.6); }
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid var(--sidebar-border);
            background: var(--sidebar-bg);
            position: relative;
        }
        .sidebar-footer::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(to bottom, transparent, var(--sidebar-bg));
            pointer-events: none;
        }
        .sidebar-footer .nav-link {
            font-size: 13px;
            color: var(--muted);
        }
        .sidebar-footer .nav-link:hover {
            color: var(--text);
        }
        .nav-link-danger {
            color: var(--danger) !important;
            margin-top: 4px;
        }
        .nav-link-danger i {
            color: var(--danger) !important;
            background: rgba(239, 68, 68, 0.1) !important;
        }
        .nav-link-danger:hover {
            background: rgba(239, 68, 68, 0.08) !important;
            color: #dc2626 !important;
        }
        .nav-link-danger:hover i {
            background: rgba(239, 68, 68, 0.15) !important;
            transform: scale(1.1);
        }

        /* ═══════════════════════════════════════════════
           TOPBAR MÓVIL
        ═══════════════════════════════════════════════ */
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
            box-shadow: 0 2px 12px var(--shadow-lg);
            backdrop-filter: blur(10px);
        }
        @media (max-width: 991px) {
            .topbar { display: flex; }
        }

        .hamburger {
            background: var(--hover-bg);
            border: 1px solid var(--sidebar-border);
            color: var(--text);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .hamburger:hover {
            background: var(--primary-light);
            border-color: var(--primary-border);
            color: var(--primary);
        }
        .hamburger:active {
            transform: scale(0.95);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: var(--overlay-bg);
            z-index: 1038;
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.open {
            display: block;
            opacity: 1;
        }

        /* ═══════════════════════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════════════════════ */
        .main-content {
            margin-left: var(--sidebar-w);
            padding: 32px 36px;
            min-height: 100vh;
        }
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                padding: calc(var(--topbar-h) + 20px) 16px 28px;
            }
        }

        .page-header {
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }
        .page-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .page-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════════
           CARDS & METRICS
        ═══════════════════════════════════════════════ */
        .panel-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 1px 4px var(--shadow);
            transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .panel-card:hover {
            box-shadow: 0 4px 16px var(--shadow-lg);
        }
        .panel-card .card-body { padding: 24px; }
        .panel-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--card-border);
            padding: 16px 24px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            border-radius: 16px 16px 0 0;
        }

        .metric-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: 0 1px 4px var(--shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-hover));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .metric-card:hover {
            box-shadow: 0 8px 24px var(--shadow-lg);
            transform: translateY(-3px);
        }
        .metric-card:hover::before {
            opacity: 1;
        }
        .metric-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }
        .metric-card:hover .metric-icon {
            transform: scale(1.08);
        }
        .metric-icon.purple { background: var(--primary-light); color: var(--primary); }
        .metric-icon.blue   { background: #eff6ff; color: #2563eb; }
        .metric-icon.green  { background: rgba(22,163,74,0.1); color: #22c55e; }
        .metric-icon.orange { background: #fff7ed; color: #f97316; }
        .metric-label {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
        }
        .metric-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        /* ═══════════════════════════════════════════════
           BUTTONS
        ═══════════════════════════════════════════════ */
        .btn-primary-panel {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 11px;
            padding: 10px 20px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px var(--primary-glow);
        }
        .btn-primary-panel:hover {
            background: var(--primary-hover);
            color: white;
            box-shadow: 0 4px 16px var(--primary-glow);
            transform: translateY(-1px);
        }
        .btn-primary-panel:active {
            transform: translateY(0);
        }

        .btn-secondary-panel {
            background: var(--card-bg);
            color: var(--text-secondary);
            border: 1px solid var(--card-border);
            border-radius: 11px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-secondary-panel:hover {
            background: var(--hover-bg);
            color: var(--text);
            border-color: var(--primary-border);
        }

        .btn-danger-panel {
            background: transparent;
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 11px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .btn-danger-panel:hover {
            background: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.4);
        }

        /* ═══════════════════════════════════════════════
           FORMS
        ═══════════════════════════════════════════════ */
        .form-control, .form-select {
            background: var(--input-bg) !important;
            border: 1.5px solid var(--input-border) !important;
            color: var(--text) !important;
            border-radius: 11px !important;
            font-size: 13.5px !important;
            font-family: inherit !important;
            padding: 10px 15px !important;
            transition: all 0.25s ease !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1) !important;
            background: var(--card-bg) !important;
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

        /* ═══════════════════════════════════════════════
           ALERTS - CON ANIMACIÓN
        ═══════════════════════════════════════════════ */
        .panel-alert {
            padding: 14px 18px;
            border-radius: 13px;
            font-size: 13.5px;
            font-weight: 600;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .panel-alert.fade-out {
            animation: fadeOut 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-10px);
                max-height: 0;
                margin: 0;
                padding: 0;
            }
        }
        .panel-alert-success {
            background: rgba(22, 163, 74, 0.08);
            border: 1px solid rgba(22, 163, 74, 0.2);
            color: var(--success);
        }
        .panel-alert-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }
        .panel-alert i {
            font-size: 18px;
        }

        /* ═══════════════════════════════════════════════
           TABLES
        ═══════════════════════════════════════════════ */
        .panel-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }
        .panel-table th {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 12px 18px;
            border-bottom: 1px solid var(--card-border);
            text-align: left;
            background: var(--table-header);
        }
        .panel-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--card-border);
            color: var(--text);
            vertical-align: middle;
        }
        .panel-table tr:last-child td { border-bottom: none; }
        .panel-table tbody tr {
            transition: background 0.2s ease;
        }
        .panel-table tbody tr:hover td {
            background: var(--table-hover);
        }

        /* ═══════════════════════════════════════════════
           BADGES
        ═══════════════════════════════════════════════ */
        .panel-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .badge-green  { background: rgba(22,163,74,0.1); color: var(--success); border: 1px solid rgba(22,163,74,0.2); }
        .badge-red    { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); }
        .badge-orange { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }
        .badge-gray   { background: var(--badge-gray-bg); color: var(--badge-gray-text); border: 1px solid var(--card-border); }
        .badge-blue   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-purple { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }

        /* ═══════════════════════════════════════════════
           ACTION BUTTONS
        ═══════════════════════════════════════════════ */
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14.5px;
            border: 1px solid var(--card-border);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--card-bg);
            color: var(--muted);
        }
        .action-btn:hover {
            color: var(--text);
            background: var(--hover-bg);
            transform: translateY(-1px);
        }
        .action-btn-edit:hover {
            color: var(--primary);
            background: var(--primary-light);
            border-color: var(--primary-border);
        }
        .action-btn-delete:hover {
            color: var(--danger);
            background: rgba(239, 68, 68, 0.08);
            border-color: rgba(239, 68, 68, 0.25);
        }

        /* ═══════════════════════════════════════════════
           EMPTY STATE
        ═══════════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 56px 28px;
            color: var(--muted);
        }
        .empty-state i {
            font-size: 44px;
            margin-bottom: 16px;
            display: block;
            opacity: 0.3;
        }
        .empty-state p {
            font-size: 13.5px;
            margin-bottom: 22px;
            line-height: 1.5;
        }

        /* ═══════════════════════════════════════════════
           SCROLLBAR ELEGANTE
        ═══════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: var(--muted);
            border-radius: 10px;
            opacity: 0.5;
            transition: background 0.2s ease;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--card-border);
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--muted);
        }
    </style>

    @yield('styles')
</head>

<body>

    <div class="sidebar-overlay" id="overlay"></div>

    <div class="topbar">
        <button class="hamburger" id="hamburger" aria-label="Abrir menú">
            <i class="bi bi-list" style="font-size:18px;"></i>
        </button>
        <div class="d-flex align-items-center gap-2">
            <div style="width:30px;height:30px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);border-radius:9px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(139,92,246,.3)">
                <i class="bi bi-cup-straw" style="color:white;font-size:13px;"></i>
            </div>
            <span style="font-weight:800;font-size:13.5px;color:var(--text);letter-spacing:-0.01em;">{{ Str::limit($gastrobar->nombre, 22) }}</span>
        </div>
    </div>

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-logo"><i class="bi bi-cup-straw"></i></div>
            <div>
                <div class="brand-name">{{ Str::limit($gastrobar->nombre, 17) }}</div>
                <div class="brand-sub">Panel del gastrobar</div>
            </div>
        </div>

        @php
            $notifPedidos = \App\Models\PedidoGastrobar::where('gastrobar_id', $gastrobar->id)
                ->where('estado', 'pendiente')
                ->count();

            $notifSolicitudes = \App\Models\SolicitudEmpleo::whereHas('empleo', fn($q) =>
                $q->where('gastrobar_id', $gastrobar->id))
                ->where('estado', 'nueva')
                ->count();

            $notifReviews = \App\Models\Review::where('gastrobar_id', $gastrobar->id)
                ->where('vista', false)
                ->count();
        @endphp

        <nav class="sidebar-nav">

            <div class="nav-section">Principal</div>
            <a href="{{ route('gastrobar.dashboard') }}"
               class="nav-link {{ request()->routeIs('gastrobar.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>

            <div class="nav-section">Gestión</div>

            <a href="{{ route('gastrobar.eventos.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.eventos.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill"></i>
                Mis Eventos
            </a>

            <a href="{{ route('gastrobar.empleos.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.empleos.*') ? 'active' : '' }}">
                <i class="bi bi-briefcase-fill"></i>
                Mis Empleos
                @if($notifSolicitudes > 0)
                    <span class="nav-notif">{{ $notifSolicitudes }}</span>
                @endif
            </a>

            <a href="{{ route('gastrobar.platos.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.platos.*') ? 'active' : '' }}">
                <i class="bi bi-egg-fried"></i>
                Menú
            </a>

            <a href="{{ route('gastrobar.galeria.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.galeria.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i>
                Galería
            </a>

            <a href="{{ route('gastrobar.pedidos.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.pedidos.*') ? 'active' : '' }}">
                <i class="bi bi-bag-fill"></i>
                Pedidos
                @if($notifPedidos > 0)
                    <span class="nav-notif">{{ $notifPedidos }}</span>
                @endif
            </a>

            <a href="{{ route('gastrobar.estadisticas.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.estadisticas.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                Estadísticas
            </a>

            <a href="{{ route('gastrobar.soporte') }}"
               class="nav-link {{ request()->routeIs('gastrobar.soporte') ? 'active' : '' }}">
                <i class="bi bi-headset"></i>
                Soporte
            </a>

            <a href="{{ route('gastrobar.reviews.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star-fill"></i>
                Reseñas
                @if($notifReviews > 0)
                    <span class="nav-notif">{{ $notifReviews }}</span>
                @endif
            </a>

            <a href="{{ route('gastrobar.info.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.info.*') ? 'active' : '' }}">
                <i class="bi bi-info-circle-fill"></i>
                Ayuda e Info
            </a>

            <a href="{{ route('gastrobar.notificaciones.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.notificaciones.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill"></i>
                Notificaciones
                @php $noLeidas = \App\Models\Notificacion::where('user_id', auth()->id())->where('leida', false)->count(); @endphp
                @if($noLeidas > 0)
                    <span style="margin-left:auto;background:var(--primary);color:white;font-size:10px;font-weight:800;padding:3px 8px;border-radius:999px;box-shadow:0 2px 6px var(--primary-glow);">{{ $noLeidas }}</span>
                @endif
            </a>

            <a href="{{ route('gastrobar.perfil.edit') }}"
               class="nav-link {{ request()->routeIs('gastrobar.perfil.*') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                Mi Perfil
            </a>

            <div class="nav-section">Preferencias</div>
            <button class="theme-toggle-nav" onclick="toggleTheme()" id="themeToggleBtn" aria-label="Cambiar tema">
                <i class="bi bi-moon-fill" id="themeIconNav"></i>
                <span id="themeText">Modo oscuro</span>
            </button>

        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('gastrobares.show', $gastrobar) }}" class="nav-link" style="color:var(--muted);">
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

    <main class="main-content">

        @if(session('success'))
        <div class="panel-alert panel-alert-success" id="alertSuccess">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="panel-alert panel-alert-error" id="alertError">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar   = document.getElementById('sidebar');
        const overlay   = document.getElementById('overlay');
        const hamburger = document.getElementById('hamburger');

        hamburger?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });

        // Cerrar sidebar con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            }
        });

        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIconNav');
            const text = document.getElementById('themeText');
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            icon.className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            text.textContent = next === 'dark' ? 'Modo claro' : 'Modo oscuro';
        }

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

        // Auto-dismiss de alertas después de 5 segundos
        setTimeout(() => {
            const successAlert = document.getElementById('alertSuccess');
            if (successAlert) {
                successAlert.classList.add('fade-out');
                setTimeout(() => successAlert.remove(), 400);
            }
        }, 5000);

        setTimeout(() => {
            const errorAlert = document.getElementById('alertError');
            if (errorAlert) {
                errorAlert.classList.add('fade-out');
                setTimeout(() => errorAlert.remove(), 400);
            }
        }, 7000);
    </script>

    @yield('scripts')
</body>
</html>
