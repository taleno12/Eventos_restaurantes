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
            }
            body { background-color: #f4f6f9; font-family: 'Figtree', sans-serif; margin: 0; }

            .sidebar {
                width: var(--sidebar-width);
                height: 100vh;
                background: var(--sidebar-color);
                position: fixed;
                left: 0; top: 0;
                color: #270b0b;
                z-index: 1000;
                overflow-y: auto;
            }

            .brand-link {
                padding: 24px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.05);
                background: rgba(0,0,0,0.1);
            }

            .nav-menu { padding: 15px 0; }

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

            .badge-lock {
                background: #e11d48;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 10px;
                margin-left: auto;
                color: white;
            }

            .content-wrapper {
                margin-left: var(--sidebar-width);
                padding: 40px;
                min-height: 100vh;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">

            <aside class="sidebar">
                <div class="brand-link">
                    <i class="fas fa-utensils text-blue-500 text-xl"></i>
                    <div>
                        <span class="font-bold text-lg tracking-tight block">GASTRO STUDIO</span>
                        <span class="text-[10px] uppercase opacity-40 font-black tracking-widest text-blue-400">Admin Panel</span>
                    </div>
                </div>

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

                    <a href="{{ route('restaurantes.index') }}"
                       class="nav-item {{ request()->routeIs('restaurantes.*') ? 'active' : '' }}">
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

                    {{-- Trabajadores (Habilitado) --}}
                    <a href="{{ route('trabajadores.index') }}" 
                       class="nav-item {{ request()->routeIs('trabajadores.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Trabajadores</span>
                    </a>

                    {{-- Área de Contratos (Nuevo) --}}
                    <a href="{{ route('contratos.index') }}" 
                       class="nav-item {{ request()->routeIs('contratos.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Contratos</span>
                    </a>

                    {{-- Área de Soporte (Nuevo) --}}
                    <a href="{{ route('soporte.index') }}" 
                       class="nav-item {{ request()->routeIs('soporte.*') ? 'active' : '' }}">
                        <i class="fas fa-headset"></i>
                        <span>Soporte Técnico</span>
                    </a>

                    {{-- Configuración (Habilitado) --}}
                    <a href="{{ route('configuracion.index') }}" 
                       class="nav-item {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Configuración</span>
                    </a>

                    <div class="mt-10 border-t border-gray-800/50 pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="nav-item w-full text-left bg-transparent border-none cursor-pointer text-red-400 hover:text-red-300">
                                <i class="fas fa-power-off"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <main class="content-wrapper">
                @isset($header)
                    <header class="mb-8">
                        {{ $header }}
                    </header>
                @else
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Panel de Control</h2>
                        <span class="text-xs bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full font-bold border border-blue-100">
                            <i class="fas fa-user-circle mr-1"></i> Admin: {{ Auth::user()->email }}
                        </span>
                    </div>
                @endisset

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-bold shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

        </div>
    </body>
</html>