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
            --sidebar-w: 255px;
            --topbar-h: 60px;
            --primary: #8b5cf6;
            --primary-light: #f5f3ff;
            --primary-border: #ddd6fe;
            --bg: #f5f6fa;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e8eaed;
            --text: #1a1d23;
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
            --overlay-bg: rgba(15,18,26,0.4);
        }

        [data-theme="dark"] {
            --primary: #a78bfa;
            --primary-light: #2e1f5e;
            --primary-border: #4c1d95;
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
            --shadow: rgba(0,0,0,0.3);
            --overlay-bg: rgba(0,0,0,0.6);
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; transition: background 0.3s, color 0.3s; }

        /* ── Theme toggle ── */
        .theme-toggle-nav {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px;
            color: var(--muted); font-size: 13.5px; font-weight: 600; border-radius: 10px;
            cursor: pointer; transition: all 0.18s ease; margin-bottom: 2px;
            border: none; background: none; width: 100%; text-align: left;
        }
        .theme-toggle-nav:hover { color: var(--text); background: var(--hover-bg); }
        .theme-toggle-nav i {
            width: 32px; height: 32px; border-radius: 8px; background: var(--badge-gray-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: var(--muted); flex-shrink: 0; transition: all 0.18s;
        }
        .theme-toggle-nav:hover i { background: var(--hover-bg); color: var(--text); }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg); border-right: 1px solid var(--sidebar-border);
            display: flex; flex-direction: column; z-index: 1040;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1), background 0.3s;
            overflow-y: auto; box-shadow: 2px 0 12px var(--shadow);
        }
        @media (max-width: 991px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } }

        .sidebar-brand { padding: 18px 20px; border-bottom: 1px solid var(--sidebar-border); display: flex; align-items: center; gap: 12px; }
        .brand-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border-radius: 11px; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; box-shadow: 0 4px 10px rgba(139,92,246,0.3);
        }
        .brand-logo i { color: white; font-size: 15px; }
        .brand-name { font-size: 13.5px; font-weight: 800; color: var(--text); line-height: 1.2; }
        .brand-sub { font-size: 10.5px; color: var(--muted); font-weight: 500; }

        .sidebar-nav { flex: 1; padding: 10px 12px; }
        .nav-section { font-size: 10px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); padding: 16px 8px 6px; }

        .nav-link {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px;
            color: var(--muted); font-size: 13.5px; font-weight: 600; border-radius: 10px;
            text-decoration: none; transition: all 0.18s ease; margin-bottom: 2px;
            border: none; background: none; width: 100%; text-align: left; cursor: pointer; position: relative;
        }
        .nav-link i {
            width: 32px; height: 32px; border-radius: 8px; background: var(--badge-gray-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: var(--muted); flex-shrink: 0; transition: all 0.18s;
        }
        .nav-link:hover { color: var(--text); background: var(--hover-bg); }
        .nav-link:hover i { background: var(--hover-bg); color: var(--text); }
        .nav-link.active { color: var(--primary); background: var(--primary-light); }
        .nav-link.active i { background: var(--primary-border); color: var(--primary); }

        /* ── Notification badge ── */
        .nav-notif {
            margin-left: auto; background: #ef4444; color: white;
            font-size: 10px; font-weight: 800; min-width: 18px; height: 18px;
            border-radius: 999px; display: inline-flex; align-items: center;
            justify-content: center; padding: 0 5px; line-height: 1; flex-shrink: 0;
        }

        .sidebar-footer { padding: 12px; border-top: 1px solid var(--sidebar-border); }
        .sidebar-footer .nav-link { font-size: 13px; color: var(--muted); }
        .sidebar-footer .nav-link:hover { color: var(--text); }
        .nav-link-danger { color: #ef4444 !important; }
        .nav-link-danger i { color: #ef4444 !important; background: rgba(239,68,68,0.1) !important; }
        .nav-link-danger:hover { background: rgba(239,68,68,0.1) !important; }

        /* ── Topbar móvil ── */
        .topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0; height: var(--topbar-h);
            background: var(--sidebar-bg); border-bottom: 1px solid var(--sidebar-border); z-index: 1039;
            align-items: center; padding: 0 16px; gap: 12px; box-shadow: 0 1px 8px var(--shadow);
        }
        @media (max-width: 991px) { .topbar { display: flex; } }

        .hamburger {
            background: var(--hover-bg); border: 1px solid var(--sidebar-border); color: var(--text);
            width: 36px; height: 36px; border-radius: 9px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: background 0.15s;
        }
        .hamburger:hover { background: var(--card-border); }

        .sidebar-overlay { display: none; position: fixed; inset: 0; background: var(--overlay-bg); z-index: 1038; backdrop-filter: blur(3px); }
        .sidebar-overlay.open { display: block; }

        .main-content { margin-left: var(--sidebar-w); padding: 28px 32px; min-height: 100vh; }
        @media (max-width: 991px) { .main-content { margin-left: 0; padding: calc(var(--topbar-h) + 16px) 16px 24px; } }

        .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .page-title { font-size: 22px; font-weight: 800; color: var(--text); }
        .page-sub { font-size: 12.5px; color: var(--muted); margin-top: 2px; font-weight: 500; }

        .panel-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 14px; box-shadow: 0 1px 4px var(--shadow); transition: background 0.3s, border-color 0.3s; }
        .panel-card .card-body { padding: 22px; }
        .panel-card .card-header { background: transparent; border-bottom: 1px solid var(--card-border); padding: 14px 22px; font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); border-radius: 14px 14px 0 0; }

        .metric-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 14px; padding: 18px 20px; box-shadow: 0 1px 4px var(--shadow); transition: box-shadow 0.2s, transform 0.2s, background 0.3s; }
        .metric-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.15); transform: translateY(-1px); }
        .metric-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; }
        .metric-icon.purple { background: var(--primary-light); color: var(--primary); }
        .metric-icon.blue   { background: #eff6ff; color: #2563eb; }
        .metric-icon.green  { background: rgba(22,163,74,0.1); color: #22c55e; }
        .metric-icon.orange { background: #fff7ed; color: #f97316; }
        .metric-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); }
        .metric-value { font-size: 26px; font-weight: 800; color: var(--text); line-height: 1.1; }

        .btn-primary-panel {
            background: var(--primary); color: white; border: none; border-radius: 10px;
            padding: 9px 18px; font-size: 13px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 7px; text-decoration: none;
            transition: all 0.2s; box-shadow: 0 2px 8px rgba(139,92,246,0.25);
        }
        .btn-primary-panel:hover { background: #7c3aed; color: white; box-shadow: 0 4px 14px rgba(139,92,246,0.35); }

        .btn-secondary-panel { background: var(--card-bg); color: var(--muted); border: 1px solid var(--card-border); border-radius: 10px; padding: 8px 16px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 7px; text-decoration: none; transition: all 0.2s; }
        .btn-secondary-panel:hover { background: var(--hover-bg); color: var(--text); }

        .btn-danger-panel { background: transparent; color: #ef4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 10px; padding: 8px 16px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 7px; text-decoration: none; transition: all 0.2s; cursor: pointer; }
        .btn-danger-panel:hover { background: rgba(239,68,68,0.1); color: #dc2626; }

        .form-control, .form-select { background: var(--input-bg) !important; border: 1px solid var(--input-border) !important; color: var(--text) !important; border-radius: 10px !important; font-size: 13.5px !important; font-family: inherit !important; padding: 9px 14px !important; transition: background 0.3s, border-color 0.3s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 3px rgba(139,92,246,0.12) !important; }
        .form-control::placeholder { color: var(--muted) !important; opacity: 0.6; }
        .input-group-text { background: var(--input-bg) !important; border-color: var(--input-border) !important; color: var(--muted) !important; }

        .panel-alert { padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .panel-alert-success { background: rgba(22,163,74,0.1); border: 1px solid rgba(22,163,74,0.2); color: #22c55e; }
        .panel-alert-error   { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; }

        .panel-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .panel-table th { font-size: 10.5px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); padding: 10px 16px; border-bottom: 1px solid var(--card-border); text-align: left; background: var(--table-header); }
        .panel-table td { padding: 13px 16px; border-bottom: 1px solid var(--card-border); color: var(--text); vertical-align: middle; }
        .panel-table tr:last-child td { border-bottom: none; }
        .panel-table tbody tr:hover td { background: var(--table-hover); }

        .panel-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; }
        .badge-green  { background: rgba(22,163,74,0.1); color: #22c55e; border: 1px solid rgba(22,163,74,0.2); }
        .badge-red    { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
        .badge-orange { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }
        .badge-gray   { background: var(--badge-gray-bg); color: var(--badge-gray-text); border: 1px solid var(--card-border); }
        .badge-blue   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-purple { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }

        .action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid var(--card-border); cursor: pointer; text-decoration: none; transition: all 0.15s; background: var(--card-bg); color: var(--muted); }
        .action-btn:hover { color: var(--text); background: var(--hover-bg); }
        .action-btn-edit:hover   { color: var(--primary); background: var(--primary-light); border-color: var(--primary-border); }
        .action-btn-delete:hover { color: #ef4444; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); }

        .empty-state { text-align: center; padding: 52px 24px; color: var(--muted); }
        .empty-state i { font-size: 40px; margin-bottom: 14px; display: block; opacity: 0.3; }
        .empty-state p { font-size: 13px; margin-bottom: 20px; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--muted); border-radius: 4px; opacity: 0.5; }
    </style>

    @yield('styles')
</head>

<body>

    <div class="sidebar-overlay" id="overlay"></div>

    <div class="topbar">
        <button class="hamburger" id="hamburger">
            <i class="bi bi-list" style="font-size:17px;"></i>
        </button>
        <div class="d-flex align-items-center gap-2">
            <div style="width:28px;height:28px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(139,92,246,.3)">
                <i class="bi bi-cup-straw" style="color:white;font-size:12px;"></i>
            </div>
            <span style="font-weight:800;font-size:13px;color:var(--text);">{{ Str::limit($gastrobar->nombre, 22) }}</span>
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
                    <span style="margin-left:auto;background:var(--primary);color:white;font-size:10px;font-weight:800;padding:2px 7px;border-radius:999px;">{{ $noLeidas }}</span>
                @endif
            </a>

            <a href="{{ route('gastrobar.perfil.edit') }}"
               class="nav-link {{ request()->routeIs('gastrobar.perfil.*') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                Mi Perfil
            </a>

            <div class="nav-section">Preferencias</div>
            <button class="theme-toggle-nav" onclick="toggleTheme()" id="themeToggleBtn">
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
    </script>

    @yield('scripts')
</body>
</html>
