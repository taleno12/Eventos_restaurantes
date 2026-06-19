<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gastro.ni') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sidebar-width: 255px;
            --sidebar-bg: #1a2035;
            --sidebar-bg-darker: #141929;
            --sidebar-text: #a0aec0;
            --sidebar-active-bg: rgba(255,255,255,0.07);
            --sidebar-active-border: #3b82f6;
            --sidebar-hover-bg: rgba(255,255,255,0.04);
            --topbar-height: 60px;
            --content-bg: #f0f2f8;
        }
        * { box-sizing: border-box; }
        body {
            background-color: var(--content-bg);
            font-family: 'Figtree', 'Segoe UI', sans-serif;
            margin: 0;
        }
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0; top: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 999;
            backdrop-filter: blur(3px);
        }
        .sidebar-overlay.open { display: block; }
        .topbar-mobile {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-height);
            background: var(--sidebar-bg);
            z-index: 998;
            align-items: center;
            padding: 0 16px;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 2px 12px rgba(0,0,0,0.2);
        }
        @media (max-width: 1023px) {
            .topbar-mobile { display: flex; }
        }
        .sidebar-brand {
            padding: 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--sidebar-bg-darker);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(59,130,246,0.35);
        }
        .nav-menu {
            padding: 12px 0 32px;
            flex: 1;
        }
        .nav-section-title {
            padding: 20px 20px 6px;
            font-size: 0.62rem;
            text-transform: uppercase;
            color: #4a5568;
            letter-spacing: 1.8px;
            font-weight: 800;
        }
        .nav-item {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
            border-top: none; border-right: none; border-bottom: none;
            width: 100%; text-align: left;
            white-space: nowrap;
        }
        .nav-item i, .nav-item .bi {
            width: 20px;
            text-align: center;
            font-size: 0.95rem;
            opacity: 0.75;
            flex-shrink: 0;
        }
        .nav-item:hover {
            background: var(--sidebar-hover-bg);
            color: #e2e8f0;
            border-left-color: rgba(59,130,246,0.4);
        }
        .nav-item:hover i, .nav-item:hover .bi { opacity: 1; }
        .nav-item.active {
            background: var(--sidebar-active-bg);
            color: #fff;
            border-left-color: var(--sidebar-active-border);
            font-weight: 600;
        }
        .nav-item.active i, .nav-item.active .bi {
            opacity: 1;
            color: #60a5fa;
        }
        .nav-badge {
            margin-left: auto;
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(59,130,246,0.18);
            color: #60a5fa;
            border: 1px solid rgba(59,130,246,0.25);
            border-radius: 4px;
            padding: 2px 6px;
            flex-shrink: 0;
        }
        .nav-divider {
            border-top: 1px solid rgba(255,255,255,0.06);
            margin: 8px 0;
        }
        .nav-item.nav-danger { color: #fc8181; }
        .nav-item.nav-danger:hover { color: #feb2b2; background: rgba(252,129,129,0.07); border-left-color: #fc8181; }
        .nav-item.nav-info { color: #63b3ed; }
        .nav-item.nav-info:hover { color: #90cdf4; background: rgba(99,179,237,0.07); border-left-color: #63b3ed; }
        .content-wrapper {
            margin-left: var(--sidebar-width);
            padding: 36px 36px;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (max-width: 1023px) {
            .content-wrapper {
                margin-left: 0;
                padding: calc(var(--topbar-height) + 24px) 16px 24px;
            }
        }
        @media (max-width: 640px) {
            .content-wrapper {
                padding: calc(var(--topbar-height) + 14px) 12px 14px;
            }
        }
        .hamburger-btn {
            background: rgba(255,255,255,0.08);
            border: none;
            color: white;
            width: 38px; height: 38px;
            border-radius: 10px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .hamburger-btn:hover { background: rgba(255,255,255,0.15); }
        .alert-success-custom {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #166534;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="topbar-mobile">
        <button class="hamburger-btn" id="hamburgerBtn">
            <i class="bi bi-list" style="font-size:1.1rem;"></i>
        </button>
        <div class="d-flex align-items-center gap-2">
            <div class="brand-icon" style="width:28px;height:28px;border-radius:7px;">
                <i class="bi bi-grid-3x3-gap-fill text-white" style="font-size:0.75rem;"></i>
            </div>
            <span class="fw-bold text-white" style="font-size:0.85rem;letter-spacing:0.04em;">GASTRO STUDIO</span>
        </div>
    </div>
    <div style="min-height:100vh;">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="bi bi-grid-3x3-gap-fill text-white" style="font-size:1rem;"></i>
                </div>
                <div>
                    <span class="d-block text-white fw-bold" style="font-size:0.95rem;letter-spacing:0.04em;line-height:1.2;">GASTRO STUDIO</span>
                    <span class="text-uppercase fw-black" style="font-size:0.58rem;letter-spacing:0.15em;color:#3b82f6;opacity:0.8;">Admin Panel</span>
                </div>
            </div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Resumen General</span>
                </a>
                <div class="nav-section-title">Logística Gastronómica</div>
                <a href="{{ route('departamentos.index') }}"
                   class="nav-item {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Departamentos</span>
                </a>
                <a href="{{ route('admin.restaurantes.index') }}"
                   class="nav-item {{ request()->routeIs('admin.restaurantes.*') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Restaurantes</span>
                </a>
                <a href="{{ route('admin.gastrobares.index') }}"
                   class="nav-item {{ request()->routeIs('admin.gastrobares.*') ? 'active' : '' }}">
                    <i class="bi bi-cup-straw"></i>
                    <span>Gastrobares</span>
                </a>
                <a href="{{ route('eventos.index') }}"
                   class="nav-item {{ request()->routeIs('eventos.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Gestión de Eventos</span>
                </a>
                <a href="{{ route('admin.empleos.index') }}"
                   class="nav-item {{ request()->routeIs('admin.empleos.*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase"></i>
                    <span>Ofertas de Empleo</span>
                </a>
                <div class="nav-section-title">Administración Interna</div>
                <a href="{{ route('trabajadores.index') }}"
                   class="nav-item {{ request()->routeIs('trabajadores.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    <span>Personal</span>
                </a>
                <a href="{{ route('contratos.index') }}"
                   class="nav-item {{ request()->routeIs('contratos.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Contratos</span>
                </a>
                <a href="{{ route('membresias.index') }}"
                   class="nav-item {{ request()->routeIs('membresias.*') ? 'active' : '' }}">
                    <i class="bi bi-patch-check"></i>
                    <span>Membresías Activas</span>
                </a>
                <a href="{{ route('pagos.index') }}"
                   class="nav-item {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span>Pagos</span>
                </a>
                <a href="{{ route('usuarios.index') }}"
                   class="nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Usuarios del Sistema</span>
                </a>
            <a href="{{ route('notificaciones.index') }}"
                   class="nav-item {{ request()->routeIs('notificaciones.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span>Notificaciones</span>
                    @php $totalNoLeidas = \App\Models\Notificacion::noLeidas()->where('user_id', auth()->id())->count(); @endphp
                    @if($totalNoLeidas > 0)
                        <span class="nav-badge" style="background:rgba(220,38,38,0.18);color:#fc8181;border-color:rgba(220,38,38,0.25);">
                            {{ $totalNoLeidas }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('reportes.index') }}"
                   class="nav-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Reportes y Estadísticas</span>
                </a>
                <a href="{{ route('soporte.index') }}"
                   class="nav-item {{ request()->routeIs('soporte.*') ? 'active' : '' }}">
                    <i class="bi bi-headset"></i>
                    <span>Soporte Técnico</span>
                </a>
                <a href="{{ route('configuracion.index') }}"
                   class="nav-item {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Configuración</span>
                </a>
                <div class="nav-divider mx-3"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item nav-danger">
                        <i class="bi bi-power"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
                <a href="{{ route('home') }}" class="nav-item nav-info">
                    <i class="bi bi-globe2"></i>
                    <span>Ver Sitio Web</span>
                </a>
            </nav>
            @auth
            <div class="px-3 pb-4 mt-auto flex-shrink-0">
                <div class="d-flex align-items-center gap-2 px-3 py-3 rounded-3"
                     style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.07);">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                         style="width:32px;height:32px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);font-size:0.75rem;font-weight:700;color:white;">
                        {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 1)) }}
                    </div>
                    <div style="overflow:hidden;">
                        <p class="text-white fw-semibold mb-0" style="font-size:0.78rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ Auth::user()->name ?? 'Administrador' }}
                        </p>
                        <p class="mb-0" style="font-size:0.65rem;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
            </div>
            @endauth
        </aside>
        <main class="content-wrapper">
            @isset($header)
                <header class="mb-4">{{ $header }}</header>
            @endisset

            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar      = document.getElementById('sidebar');
        const overlay      = document.getElementById('sidebarOverlay');
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }
        hamburgerBtn?.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        overlay?.addEventListener('click', closeSidebar);
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth < 1024) closeSidebar();
            });
        });
    </script>
</body>
</html>
