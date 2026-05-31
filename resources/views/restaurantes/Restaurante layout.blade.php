<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — {{ $restaurante->nombre }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700,800" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w: 240px;
            --topbar-h: 56px;
            --bg: #0d0d0d;
            --card: #161616;
            --border: #222;
            --orange: #e85d04;
            --orange-dim: rgba(232,93,4,0.12);
            --text: #e5e5e5;
            --muted: #666;
        }
        body { font-family: 'Instrument Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: #111; border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 100; transition: transform 0.3s ease; overflow-y: auto;
        }
        @media (max-width: 1023px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } }

        .sidebar-brand { padding: 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 36px; height: 36px; background: var(--orange); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .brand-logo i { color: white; font-size: 14px; }
        .brand-name { font-size: 13px; font-weight: 800; color: white; line-height: 1.2; }
        .brand-sub { font-size: 10px; color: var(--muted); }

        .sidebar-nav { flex: 1; padding: 8px 0; }
        .nav-section { font-size: 9px; font-weight: 900; letter-spacing: 0.2em; text-transform: uppercase; color: var(--muted); padding: 14px 16px 4px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: #777; text-decoration: none; font-size: 13px; font-weight: 600; border-left: 3px solid transparent; transition: all 0.2s; cursor: pointer; background: none; border-top: none; border-right: none; border-bottom: none; width: 100%; text-align: left; }
        .nav-link i { width: 16px; text-align: center; font-size: 12px; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.04); }
        .nav-link.active { color: var(--orange); border-left-color: var(--orange); background: var(--orange-dim); }

        .sidebar-footer { padding: 12px; border-top: 1px solid var(--border); }

        .topbar { display: none; position: fixed; top: 0; left: 0; right: 0; height: var(--topbar-h); background: #111; border-bottom: 1px solid var(--border); z-index: 99; align-items: center; padding: 0 14px; gap: 10px; }
        @media (max-width: 1023px) { .topbar { display: flex; } }
        .hamburger { background: rgba(255,255,255,0.06); border: none; color: white; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }

        .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 98; }
        .overlay.open { display: block; }

        .content { margin-left: var(--sidebar-w); padding: 32px; min-height: 100vh; }
        @media (max-width: 1023px) { .content { margin-left: 0; padding: calc(var(--topbar-h) + 16px) 14px 20px; } }

        /* Componentes reutilizables */
        .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .page-title { font-size: 22px; font-weight: 800; color: white; }
        .page-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

        .card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; }
        .card-body { padding: 20px; }

        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; letter-spacing: 0.05em; text-transform: uppercase; }
        .btn-orange { background: var(--orange); color: white; }
        .btn-orange:hover { background: #c44d00; }
        .btn-ghost { background: transparent; color: var(--muted); border: 1px solid var(--border); }
        .btn-ghost:hover { color: white; border-color: #444; }
        .btn-danger { background: transparent; color: #e74c3c; border: 1px solid #e74c3c33; }
        .btn-danger:hover { background: #e74c3c22; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 10px; font-weight: 900; letter-spacing: 0.15em; text-transform: uppercase; color: var(--muted); margin-bottom: 6px; }
        .form-control { width: 100%; background: #111; border: 1px solid var(--border); color: var(--text); border-radius: 10px; padding: 10px 14px; font-size: 13px; font-family: inherit; transition: border-color 0.2s; outline: none; }
        .form-control:focus { border-color: var(--orange); }
        .form-control::placeholder { color: #444; }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .alert-success { background: rgba(46,204,113,0.1); border: 1px solid rgba(46,204,113,0.2); color: #2ecc71; }
        .alert-error { background: rgba(231,76,60,0.1); border: 1px solid rgba(231,76,60,0.2); color: #e74c3c; }

        .table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table th { font-size: 10px; font-weight: 900; letter-spacing: 0.15em; text-transform: uppercase; color: var(--muted); padding: 10px 14px; border-bottom: 1px solid var(--border); text-align: left; }
        .table td { padding: 12px 14px; border-bottom: 1px solid #1a1a1a; color: var(--text); vertical-align: middle; }
        .table tr:last-child td { border-bottom: none; }
        .table tr:hover td { background: rgba(255,255,255,0.02); }

        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 10px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; }
        .badge-green { background: rgba(46,204,113,0.1); color: #2ecc71; border: 1px solid rgba(46,204,113,0.2); }
        .badge-red { background: rgba(231,76,60,0.1); color: #e74c3c; border: 1px solid rgba(231,76,60,0.2); }
        .badge-orange { background: var(--orange-dim); color: var(--orange); border: 1px solid rgba(232,93,4,0.2); }

        .empty-state { text-align: center; padding: 48px 24px; color: var(--muted); }
        .empty-state i { font-size: 36px; margin-bottom: 14px; display: block; opacity: 0.3; }
        .empty-state p { font-size: 13px; margin-bottom: 20px; }

        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 640px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }
    </style>
    @yield('styles')
</head>
<body>
    <div class="overlay" id="overlay"></div>

    <div class="topbar">
        <button class="hamburger" id="hamburger"><i class="fas fa-bars" style="font-size:13px;"></i></button>
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:26px;height:26px;background:var(--orange);border-radius:7px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-utensils" style="color:white;font-size:10px;"></i>
            </div>
            <span style="font-weight:800;font-size:12px;color:white;">{{ Str::limit($restaurante->nombre, 20) }}</span>
        </div>
    </div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="fas fa-utensils"></i></div>
            <div>
                <div class="brand-name">{{ Str::limit($restaurante->nombre, 16) }}</div>
                <div class="brand-sub">Panel del restaurante</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Principal</div>
            <a href="{{ route('restaurante.dashboard') }}" class="nav-link {{ request()->routeIs('restaurante.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-section">Gestión</div>
            <a href="{{ route('restaurante.eventos.index') }}" class="nav-link {{ request()->routeIs('restaurante.eventos.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Mis Eventos
            </a>
            <a href="{{ route('restaurante.empleos.index') }}" class="nav-link {{ request()->routeIs('restaurante.empleos.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Mis Empleos
            </a>
            <a href="{{ route('restaurante.galeria.index') }}" class="nav-link {{ request()->routeIs('restaurante.galeria.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Galería
            </a>
            <a href="{{ route('restaurante.perfil.edit') }}" class="nav-link {{ request()->routeIs('restaurante.perfil.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Mi Perfil
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('restaurantes.show', $restaurante) }}" class="nav-link" style="color:#888;padding:8px 0;">
                <i class="fas fa-eye"></i> Ver mi página
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="color:#e74c3c;padding:8px 0;">
                    <i class="fas fa-power-off"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <script>
        const sidebar   = document.getElementById('sidebar');
        const overlay   = document.getElementById('overlay');
        const hamburger = document.getElementById('hamburger');
        hamburger?.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
        overlay?.addEventListener('click',   () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });
    </script>
    @yield('scripts')
</body>
</html>