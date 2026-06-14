<!DOCTYPE html>
<html lang="es">

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
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg); border-right: 1px solid var(--sidebar-border);
            display: flex; flex-direction: column; z-index: 1040;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            overflow-y: auto; box-shadow: 2px 0 12px rgba(0,0,0,0.04);
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
            color: #5a6175; font-size: 13.5px; font-weight: 600; border-radius: 10px;
            text-decoration: none; transition: all 0.18s ease; margin-bottom: 2px;
            border: none; background: none; width: 100%; text-align: left; cursor: pointer;
        }

        .nav-link i {
            width: 32px; height: 32px; border-radius: 8px; background: #f1f3f8;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: #8b92a5; flex-shrink: 0; transition: all 0.18s;
        }

        .nav-link:hover { color: var(--text); background: #f5f6fa; }
        .nav-link:hover i { background: #e8eaed; color: #5a6175; }

        .nav-link.active { color: #6d28d9; background: var(--primary-light); }
        .nav-link.active i { background: #ddd6fe; color: var(--primary); }

        .sidebar-footer { padding: 12px; border-top: 1px solid var(--sidebar-border); }
        .sidebar-footer .nav-link { font-size: 13px; color: var(--muted); }
        .sidebar-footer .nav-link:hover { color: var(--text); }

        .nav-link-danger { color: #dc2626 !important; }
        .nav-link-danger i { color: #dc2626 !important; background: #fef2f2 !important; }
        .nav-link-danger:hover { background: #fef2f2 !important; }

        .topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0; height: var(--topbar-h);
            background: white; border-bottom: 1px solid var(--sidebar-border); z-index: 1039;
            align-items: center; padding: 0 16px; gap: 12px; box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        @media (max-width: 991px) { .topbar { display: flex; } }

        .hamburger {
            background: #f5f6fa; border: 1px solid var(--sidebar-border); color: var(--text);
            width: 36px; height: 36px; border-radius: 9px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: background 0.15s;
        }
        .hamburger:hover { background: #e8eaed; }

        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(15,18,26,0.4); z-index: 1038; backdrop-filter: blur(3px); }
        .sidebar-overlay.open { display: block; }

        .main-content { margin-left: var(--sidebar-w); padding: 28px 32px; min-height: 100vh; }
        @media (max-width: 991px) { .main-content { margin-left: 0; padding: calc(var(--topbar-h) + 16px) 16px 24px; } }

        .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .page-title { font-size: 22px; font-weight: 800; color: var(--text); }
        .page-sub { font-size: 12.5px; color: var(--muted); margin-top: 2px; font-weight: 500; }

        .panel-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
        .panel-card .card-body { padding: 22px; }
        .panel-card .card-header { background: transparent; border-bottom: 1px solid var(--card-border); padding: 14px 22px; font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); border-radius: 14px 14px 0 0; }

        .metric-card { background: white; border: 1px solid var(--card-border); border-radius: 14px; padding: 18px 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); transition: box-shadow 0.2s, transform 0.2s; }
        .metric-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-1px); }

        .metric-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; }
        .metric-icon.purple { background: #f5f3ff; color: var(--primary); }
        .metric-icon.blue   { background: #eff6ff; color: #2563eb; }
        .metric-icon.green  { background: #f0fdf4; color: #16a34a; }
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

        .btn-secondary-panel { background: white; color: #5a6175; border: 1px solid var(--card-border); border-radius: 10px; padding: 8px 16px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 7px; text-decoration: none; transition: all 0.2s; }
        .btn-secondary-panel:hover { background: #f5f6fa; color: var(--text); }

        .btn-danger-panel { background: transparent; color: #dc2626; border: 1px solid #fecaca; border-radius: 10px; padding: 8px 16px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 7px; text-decoration: none; transition: all 0.2s; cursor: pointer; }
        .btn-danger-panel:hover { background: #fef2f2; color: #b91c1c; }

        .form-control, .form-select { background: #fafbfc !important; border: 1px solid #e2e5ec !important; color: var(--text) !important; border-radius: 10px !important; font-size: 13.5px !important; font-family: inherit !important; padding: 9px 14px !important; }
        .form-control:focus, .form-select:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 3px rgba(139,92,246,0.12) !important; }
        .form-control::placeholder { color: #b0b7c8 !important; }
        .input-group-text { background: #f5f6fa !important; border-color: #e2e5ec !important; color: var(--muted) !important; }

        .panel-alert { padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .panel-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .panel-alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        .panel-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .panel-table th { font-size: 10.5px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); padding: 10px 16px; border-bottom: 1px solid var(--card-border); text-align: left; background: #fafbfc; }
        .panel-table td { padding: 13px 16px; border-bottom: 1px solid #f0f1f4; color: var(--text); vertical-align: middle; }
        .panel-table tr:last-child td { border-bottom: none; }
        .panel-table tbody tr:hover td { background: #fafbfc; }

        .panel-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; }
        .badge-green  { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-red    { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-orange { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .badge-gray   { background: #f5f6fa; color: #5a6175; border: 1px solid #e2e5ec; }
        .badge-blue   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-purple { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }

        .action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid var(--card-border); cursor: pointer; text-decoration: none; transition: all 0.15s; background: white; color: var(--muted); }
        .action-btn:hover { color: var(--text); background: #f5f6fa; }
        .action-btn-edit:hover   { color: #6d28d9; background: #f5f3ff; border-color: #ddd6fe; }
        .action-btn-delete:hover { color: #dc2626; background: #fef2f2; border-color: #fecaca; }

        .empty-state { text-align: center; padding: 52px 24px; color: var(--muted); }
        .empty-state i { font-size: 40px; margin-bottom: 14px; display: block; opacity: 0.3; }
        .empty-state p { font-size: 13px; margin-bottom: 20px; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
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
            </a>
            <a href="{{ route('gastrobar.galeria.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.galeria.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i>
                Galería
            </a>
            <a href="{{ route('gastrobar.estadisticas.index') }}"
               class="nav-link {{ request()->routeIs('gastrobar.estadisticas.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                Estadísticas
            </a>
            <a href="{{ route('gastrobar.perfil.edit') }}"
               class="nav-link {{ request()->routeIs('gastrobar.perfil.*') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                Mi Perfil
            </a>

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
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('overlay');
        const hamburger = document.getElementById('hamburger');
        hamburger?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });
    </script>

    @yield('scripts')
</body>
</html>
