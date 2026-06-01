<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurante->nombre }} — Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w: 255px; --topbar-h: 60px;
            --bg: #f5f6fa; --card: #ffffff;
            --border: #e8eaed; --orange: #f97316;
            --orange-dim: #fff7ed;
            --text: #1a1d23; --muted: #8b92a5;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: #ffffff; border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 1040; transition: transform 0.3s ease;
            overflow-y: auto; box-shadow: 2px 0 12px rgba(0,0,0,0.04);
        }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
        .sidebar-brand {
            padding: 18px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }
        .brand-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-radius: 11px; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(249,115,22,0.3);
        }
        .brand-logo i { color: white; font-size: 15px; }
        .brand-name { font-size: 13.5px; font-weight: 800; color: var(--text); line-height: 1.2; }
        .brand-sub  { font-size: 10.5px; color: var(--muted); font-weight: 500; }

        .sidebar-nav { flex: 1; padding: 10px 12px; }
        .nav-section {
            font-size: 10px; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--muted); padding: 16px 8px 6px;
        }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; color: #5a6175;
            font-size: 13.5px; font-weight: 600;
            border-radius: 10px; text-decoration: none;
            transition: all 0.18s ease; margin-bottom: 2px;
        }
        .nav-link i {
            width: 32px; height: 32px; border-radius: 8px;
            background: #f1f3f8; display: flex; align-items: center;
            justify-content: center; font-size: 14px;
            color: #8b92a5; flex-shrink: 0; transition: all 0.18s;
        }
        .nav-link:hover { color: var(--text); background: #f5f6fa; }
        .nav-link:hover i { background: #e8eaed; color: #5a6175; }
        .nav-link.active { color: #c2410c; background: var(--orange-dim); }
        .nav-link.active i { background: #fed7aa; color: var(--orange); }

        .sidebar-footer {
            padding: 12px; border-top: 1px solid var(--border);
        }
        .sidebar-footer .nav-link { font-size: 13px; color: var(--muted); }
        .nav-link-danger { color: #dc2626 !important; }
        .nav-link-danger i { color: #dc2626 !important; background: #fef2f2 !important; }
        .nav-link-danger:hover { background: #fef2f2 !important; }

        /* ── Topbar móvil ── */
        .topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0;
            height: var(--topbar-h); background: white;
            border-bottom: 1px solid var(--border);
            z-index: 1039; align-items: center; padding: 0 16px; gap: 12px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }
        @media (max-width: 991px) { .topbar { display: flex; } }
        .hamburger {
            background: #f5f6fa; border: 1px solid var(--border);
            color: var(--text); width: 36px; height: 36px; border-radius: 9px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
        }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,18,26,0.4); z-index: 1038;
            backdrop-filter: blur(3px);
        }
        .sidebar-overlay.open { display: block; }

        /* ── Content ── */
        .main-content { margin-left: var(--sidebar-w); padding: 28px 32px; min-height: 100vh; }
        @media (max-width: 991px) {
            .main-content { margin-left: 0; padding: calc(var(--topbar-h) + 16px) 16px 24px; }
        }

        /* ── Perfil card ── */
        .perfil-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 16px; padding: 20px;
            display: flex; align-items: center; gap: 16px; margin-bottom: 28px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .perfil-avatar {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            display: flex; align-items: center;
            justify-content: center; font-size: 22px; font-weight: 800;
            color: white; flex-shrink: 0; overflow: hidden;
        }
        .perfil-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .perfil-nombre { font-size: 17px; font-weight: 800; color: var(--text); }
        .perfil-email  { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .perfil-badge  {
            margin-left: auto;
            background: #fff7ed; border: 1px solid #fed7aa;
            color: #c2410c; font-size: 10px; font-weight: 800;
            letter-spacing: 0.15em; text-transform: uppercase;
            padding: 6px 12px; border-radius: 999px;
        }

        /* ── Page header ── */
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 24px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
        .page-header p  { color: var(--muted); font-size: 13px; }

        /* ── Stats ── */
        .stat-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 14px; padding: 20px;
            display: flex; align-items: center; gap: 16px;
            transition: all 0.2s; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-1px); }
        .stat-icon {
            width: 48px; height: 48px; background: #fff7ed;
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .stat-icon i { color: var(--orange); font-size: 20px; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-label { font-size: 11px; color: var(--muted); margin-top: 3px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; }

        /* ── Action cards ── */
        .action-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 14px; padding: 18px; text-decoration: none;
            color: var(--text); display: flex; flex-direction: column;
            align-items: flex-start; gap: 10px;
            transition: all 0.2s ease; font-weight: 700; font-size: 13px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .action-card:hover {
            border-color: #fed7aa; background: #fff7ed;
            color: #c2410c; transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(249,115,22,0.12);
        }
        .action-card-icon {
            width: 38px; height: 38px; background: #fff7ed;
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; color: var(--orange); font-size: 16px;
        }

        /* ── Section title ── */
        .section-title {
            font-size: 13px; font-weight: 700; color: var(--text);
            margin-bottom: 14px; display: flex; align-items: center; gap: 10px;
            text-transform: uppercase; letter-spacing: 0.08em;
        }
        .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* ── Eventos ── */
        .evento-item {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 12px; padding: 14px 16px;
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 8px; transition: all 0.2s;
            text-decoration: none; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .evento-item:hover { border-color: #fed7aa; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .evento-date {
            background: #fff7ed; border: 1px solid #fed7aa;
            border-radius: 10px; padding: 8px 12px;
            text-align: center; flex-shrink: 0; min-width: 52px;
        }
        .evento-date .day   { font-size: 20px; font-weight: 800; color: var(--orange); line-height: 1; }
        .evento-date .month { font-size: 10px; color: var(--muted); text-transform: uppercase; font-weight: 600; }
        .evento-info { flex: 1; min-width: 0; }
        .evento-titulo { font-weight: 700; font-size: 14px; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .evento-precio { font-size: 12px; color: var(--muted); margin-top: 2px; }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 40px; color: var(--muted);
            background: var(--card); border: 1px dashed var(--border); border-radius: 16px;
        }
        .empty-state i { font-size: 36px; margin-bottom: 12px; display: block; opacity: 0.3; }
        .empty-state p { font-size: 13px; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b0b7c8; }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="overlay"></div>

    <div class="topbar">
        <button class="hamburger" id="hamburger">
            <i class="bi bi-list" style="font-size:17px;"></i>
        </button>
        <div class="d-flex align-items-center gap-2">
            <div style="width:28px;height:28px;background:linear-gradient(135deg,#f97316,#ea580c);border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(249,115,22,.3)">
                <i class="bi bi-shop" style="color:white;font-size:12px;"></i>
            </div>
            <span style="font-weight:800;font-size:13px;color:var(--text);">{{ Str::limit($restaurante->nombre, 22) }}</span>
        </div>
    </div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="bi bi-shop"></i></div>
            <div>
                <div class="brand-name">{{ Str::limit($restaurante->nombre, 17) }}</div>
                <div class="brand-sub">Panel del restaurante</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Principal</div>
            <a href="{{ route('restaurante.dashboard') }}" class="nav-link active">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section">Gestión</div>
            <a href="{{ route('restaurante.eventos.index') }}" class="nav-link">
                <i class="bi bi-calendar-event-fill"></i> Mis Eventos
            </a>
            <a href="{{ route('restaurante.empleos.index') }}" class="nav-link">
                <i class="bi bi-briefcase-fill"></i> Mis Empleos
            </a>
            <a href="{{ route('restaurante.galeria.index') }}" class="nav-link">
                <i class="bi bi-images"></i> Galería
            </a>
            <a href="{{ route('restaurante.perfil.edit') }}" class="nav-link">
                <i class="bi bi-shop-window"></i> Mi Perfil
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('restaurantes.show', $restaurante) }}" class="nav-link" style="color:var(--muted);">
                <i class="bi bi-box-arrow-up-right"></i> Ver mi página
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link nav-link-danger w-100 text-start" style="background:none;border:none;">
                    <i class="bi bi-power"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">

        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Perfil card --}}
        <div class="perfil-card">
            <div class="perfil-avatar">
                @if($restaurante->foto_portada)
                    <img src="{{ asset('storage/' . $restaurante->foto_portada) }}" alt="{{ $restaurante->nombre }}">
                @else
                    {{ strtoupper(substr($restaurante->nombre, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="perfil-nombre">{{ $restaurante->nombre }}</div>
                <div class="perfil-email">{{ auth()->user()->email }}</div>
            </div>
            <div class="perfil-badge">Restaurante</div>
        </div>

        {{-- Page header --}}
        <div class="page-header">
            <h1>Bienvenido 👋</h1>
            <p>Gestiona tu restaurante desde este panel. Todo lo que necesitas en un solo lugar.</p>
        </div>

        {{-- Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-calendar-check-fill"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalEventos }}</div>
                        <div class="stat-label">Eventos totales</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalEmpleos }}</div>
                        <div class="stat-label">Empleos activos</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-images"></i></div>
                    <div>
                        <div class="stat-value">{{ $restaurante->imagenes?->count() ?? 0 }}</div>
                        <div class="stat-label">Fotos en galería</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <p class="section-title">Acciones rápidas</p>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('restaurante.eventos.create') }}" class="action-card h-100">
                    <div class="action-card-icon"><i class="bi bi-plus-circle-fill"></i></div>
                    Nuevo evento
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('restaurante.empleos.create') }}" class="action-card h-100">
                    <div class="action-card-icon"><i class="bi bi-plus-circle-fill"></i></div>
                    Nueva oferta de empleo
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('restaurante.galeria.index') }}" class="action-card h-100">
                    <div class="action-card-icon"><i class="bi bi-camera-fill"></i></div>
                    Subir fotos
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('restaurante.perfil.edit') }}" class="action-card h-100">
                    <div class="action-card-icon"><i class="bi bi-pencil-fill"></i></div>
                    Editar perfil
                </a>
            </div>
        </div>

        {{-- Próximos eventos --}}
        <p class="section-title">Próximos eventos</p>

        @forelse($eventosProximos as $evento)
            <div class="evento-item">
                <div class="evento-date">
                    <div class="day">{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d') }}</div>
                    <div class="month">{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('M') }}</div>
                </div>
                <div class="evento-info">
                    <div class="evento-titulo">{{ $evento->titulo }}</div>
                    <div class="evento-precio">C$ {{ number_format($evento->precio, 0) }}</div>
                </div>
                <a href="{{ route('restaurante.eventos.edit', $evento) }}"
                   style="color:var(--muted);font-size:12px;text-decoration:none;white-space:nowrap;">
                    Ver <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                </a>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <p>No tienes eventos próximos.<br>¡Crea tu primer evento!</p>
            </div>
        @endforelse

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar   = document.getElementById('sidebar');
        const overlay   = document.getElementById('overlay');
        const hamburger = document.getElementById('hamburger');
        hamburger?.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
        overlay?.addEventListener('click',   () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });
    </script>
</body>
</html>
