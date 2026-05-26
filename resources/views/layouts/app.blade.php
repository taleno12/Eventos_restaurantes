<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gastro.ni') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --sidebar-width: 260px;
                --sidebar-color: #11111d;
                --accent-color: #3b82f6;
                --topbar-height: 60px;
            }

            * { box-sizing: border-box; }
            body { background-color: #f4f6f9; font-family: 'Figtree', sans-serif; margin: 0; }

            /* ── SIDEBAR ── */
            .sidebar {
                width: var(--sidebar-width);
                height: 100vh;
                background: var(--sidebar-color);
                position: fixed;
                left: 0; top: 0;
                color: #c2c7d0;
                z-index: 1000;
                overflow-y: auto;
                overflow-x: hidden;
                -webkit-overflow-scrolling: touch; /* scroll suave en iOS/Android */
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* En móvil el sidebar se oculta fuera de pantalla */
            @media (max-width: 1023px) {
                .sidebar {
                    transform: translateX(-100%);
                }
                .sidebar.open {
                    transform: translateX(0);
                }
            }

            /* ── OVERLAY ── */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                backdrop-filter: blur(2px);
            }
            .sidebar-overlay.open { display: block; }

            /* ── TOPBAR MÓVIL ── */
            .topbar-mobile {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0;
                height: var(--topbar-height);
                background: var(--sidebar-color);
                z-index: 998;
                align-items: center;
                padding: 0 16px;
                gap: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.05);
            }
            @media (max-width: 1023px) {
                .topbar-mobile { display: flex; }
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

            /* ── BRAND ── */
            .brand-link {
                padding: 24px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.05);
                background: rgba(0,0,0,0.1);
            }

            /* ── NAV ── */
            .nav-menu { padding: 15px 0 40px; } /* padding-bottom para que el último ítem no quede cortado */

            .nav-item {
                padding: 12px 24px;
                display: flex;
                align-items: center;
                gap: 12px;
                color: #c2c7d0;
                text-decoration: none;
                transition: all 0.3s ease;
                font-size: 0.9rem;
                border-left: 4px solid transparent;
                cursor: pointer;
                background: transparent;
                border-top: none; border-right: none; border-bottom: none;
                width: 100%; text-align: left;
            }
            .nav-item i { width: 20px; text-align: center; }
            .nav-item:hover, .nav-item.active {
                background: rgba(255,255,255,0.05);
                color: #fff;
                border-left: 4px solid var(--accent-color);
            }

            .section-title {
                padding: 24px 24px 8px;
                font-size: 0.65rem;
                text-transform: uppercase;
                color: #6c757d;
                letter-spacing: 1.5px;
                font-weight: 800;
            }

            /* ── CONTENIDO ── */
            .content-wrapper {
                margin-left: var(--sidebar-width);
                padding: 40px;
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
                    padding: calc(var(--topbar-height) + 16px) 12px 16px;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">

        {{-- Overlay para cerrar sidebar en móvil --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- Topbar solo visible en móvil --}}
        <div class="topbar-mobile">
            <button class="hamburger-btn" id="hamburgerBtn">
                <i class="fas fa-bars text-sm"></i>
            </button>
            <div class="flex items-center gap-2">
                <i class="fas fa-utensils text-blue-400 text-sm"></i>
                <span class="font-bold text-white text-sm tracking-tight">GASTRO STUDIO</span>
            </div>
        </div>

        <div class="min-h-screen">

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar" id="sidebar">

                {{-- Brand --}}
                <div class="brand-link">
                    <i class="fas fa-utensils text-blue-500 text-xl"></i>
                    <div>
                        <span class="font-bold text-lg tracking-tight block text-white">GASTRO STUDIO</span>
                        <span class="text-[10px] uppercase opacity-40 font-black tracking-widest text-blue-400">Admin Panel</span>
                    </div>
                </div>

                {{-- Navegación principal --}}
                <nav class="nav-menu">

                    <a href="{{ route('dashboard') }}"
                       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Resumen General</span>
                    </a>

                    <div class="section-title">Logística Gastronómica</div>

                    <a href="{{ route('departamentos.index') }}"
                       class="nav-item {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Departamentos</span>
                    </a>

                    <a href="{{ route('admin.restaurantes.index') }}"
                       class="nav-item {{ request()->routeIs('admin.restaurantes.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Restaurantes</span>
                    </a>

                    <a href="{{ route('eventos.index') }}"
                       class="nav-item {{ request()->routeIs('eventos.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Gestión de Eventos</span>
                    </a>

                    <a href="{{ route('admin.empleos.index') }}"
                       class="nav-item {{ request()->routeIs('admin.empleos.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Ofertas de Empleo</span>
                    </a>

                    <div class="section-title">Personal y Seguridad</div>

                    <a href="{{ route('trabajadores.index') }}"
                       class="nav-item {{ request()->routeIs('trabajadores.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Trabajadores</span>
                    </a>

                    <a href="{{ route('contratos.index') }}"
                       class="nav-item {{ request()->routeIs('contratos.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Contratos</span>
                    </a>

                    <a href="{{ route('soporte.index') }}"
                       class="nav-item {{ request()->routeIs('soporte.*') ? 'active' : '' }}">
                        <i class="fas fa-headset"></i>
                        <span>Soporte Técnico</span>
                    </a>

                    <a href="{{ route('configuracion.index') }}"
                       class="nav-item {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Configuración</span>
                    </a>

                    {{-- ── SECCIÓN INFERIOR: Logout + Ver Sitio ── --}}
                    <div class="mt-4 border-t border-gray-800/50 pt-3">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-item text-red-400 hover:text-red-300">
                                <i class="fas fa-power-off"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>

                        <div class="mt-4 border-t border-gray-800/50 pt-2 mb-6">
                            <a href="{{ route('home') }}" class="nav-item text-blue-400 hover:text-blue-300">
                                <i class="fas fa-globe"></i>
                                <span>Ver Sitio Web</span>
                            </a>
                        </div>

                    </div>
                    {{-- ── FIN SECCIÓN INFERIOR ── --}}

                </nav>
            </aside>

            {{-- ── CONTENIDO PRINCIPAL ── --}}
            <main class="content-wrapper">

                @isset($header)
                    <header class="mb-8">
                        {{ $header }}
                    </header>
                @else
                    <div class="flex flex-wrap justify-between items-center mb-8 gap-3">
                        <h2 class="text-2xl font-bold text-gray-800">Panel de Control</h2>
                        @auth
                            <span class="text-xs bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full font-bold border border-blue-100">
                                <i class="fas fa-user-circle mr-1"></i> Admin: {{ Auth::user()->email }}
                            </span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-600 px-4 py-1.5 rounded-full font-bold border border-gray-200">
                                <i class="fas fa-eye mr-1"></i> Modo Visitante
                            </span>
                        @endauth
                    </div>
                @endisset

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-bold shadow-sm rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset

            </main>

        </div>

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

            // Cerrar sidebar al navegar en móvil
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', () => {
                    if (window.innerWidth < 1024) closeSidebar();
                });
            });
        </script>
    </body>
</html>