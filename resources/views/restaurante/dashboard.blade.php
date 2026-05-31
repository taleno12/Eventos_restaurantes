<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurante->nombre }} — Panel</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700,800|playfair-display:700,700i" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w: 260px; --topbar-h: 60px; --bg: #0d0d0d; --card: #161616;
            --border: #222; --orange: #e85d04; --orange-dim: rgba(232,93,4,0.12);
            --text: #e5e5e5; --muted: #666;
        }
        body { font-family: 'Instrument Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        .sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh; background: #111; border-right: 1px solid var(--border); display: flex; flex-direction: column; z-index: 100; transition: transform 0.3s ease; }
        @media (max-width: 1023px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } }
        .sidebar-brand { padding: 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .brand-logo { width: 40px; height: 40px; background: var(--orange); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .brand-logo i { color: white; font-size: 16px; }
        .brand-name { font-size: 13px; font-weight: 800; color: white; line-height: 1.3; }
        .brand-sub { font-size: 10px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; }
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section { font-size: 9px; font-weight: 900; letter-spacing: 0.2em; text-transform: uppercase; color: var(--muted); padding: 16px 20px 6px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 20px; color: #888; text-decoration: none; font-size: 13px; font-weight: 600; border-left: 3px solid transparent; transition: all 0.2s ease; cursor: pointer; background: none; border-top: none; border-right: none; border-bottom: none; width: 100%; text-align: left; }
        .nav-link i { width: 18px; text-align: center; font-size: 13px; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.04); }
        .nav-link.active { color: var(--orange); border-left-color: var(--orange); background: var(--orange-dim); }
        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 4px; }
        .topbar { display: none; position: fixed; top: 0; left: 0; right: 0; height: var(--topbar-h); background: #111; border-bottom: 1px solid var(--border); z-index: 99; align-items: center; padding: 0 16px; gap: 12px; }
        @media (max-width: 1023px) { .topbar { display: flex; } }
        .hamburger { background: rgba(255,255,255,0.06); border: none; color: white; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 98; backdrop-filter: blur(2px); }
        .overlay.open { display: block; }
        .content { margin-left: var(--sidebar-w); padding: 40px; min-height: 100vh; }
        @media (max-width: 1023px) { .content { margin-left: 0; padding: calc(var(--topbar-h) + 20px) 16px 24px; } }
        .page-header { margin-bottom: 32px; }
        .page-header h1 { font-size: 28px; font-weight: 800; color: white; margin-bottom: 4px; }
        .page-header p { color: var(--muted); font-size: 13px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px; transition: border-color 0.2s; }
        .stat-card:hover { border-color: var(--orange); }
        .stat-icon { width: 44px; height: 44px; background: var(--orange-dim); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon i { color: var(--orange); font-size: 18px; }
        .stat-value { font-size: 28px; font-weight: 800; color: white; line-height: 1; }
        .stat-label { font-size: 11px; color: var(--muted); margin-top: 3px; text-transform: uppercase; letter-spacing: 0.1em; }
        .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 32px; }
        .action-btn { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 18px; text-decoration: none; color: var(--text); display: flex; flex-direction: column; align-items: flex-start; gap: 10px; transition: all 0.2s ease; font-weight: 700; font-size: 13px; }
        .action-btn:hover { border-color: var(--orange); background: var(--orange-dim); color: white; transform: translateY(-2px); }
        .action-btn i { width: 36px; height: 36px; background: var(--orange-dim); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--orange); font-size: 15px; }
        .section-title { font-size: 14px; font-weight: 800; color: white; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        .evento-item { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 14px 16px; display: flex; align-items: center; gap: 14px; margin-bottom: 10px; transition: border-color 0.2s; }
        .evento-item:hover { border-color: #333; }
        .evento-date { background: var(--orange-dim); border: 1px solid rgba(232,93,4,0.2); border-radius: 10px; padding: 8px 12px; text-align: center; flex-shrink: 0; min-width: 50px; }
        .evento-date .day { font-size: 20px; font-weight: 800; color: var(--orange); line-height: 1; }
        .evento-date .month { font-size: 10px; color: var(--muted); text-transform: uppercase; }
        .evento-info { flex: 1; min-width: 0; }
        .evento-titulo { font-weight: 700; font-size: 14px; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .evento-precio { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .empty-state { text-align: center; padding: 40px; color: var(--muted); background: var(--card); border: 1px dashed var(--border); border-radius: 16px; }
        .empty-state i { font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.4; }
        .empty-state p { font-size: 13px; }
        .perfil-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px; margin-bottom: 32px; }
        .perfil-avatar { width: 56px; height: 56px; border-radius: 16px; background: var(--orange); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: white; flex-shrink: 0; overflow: hidden; }
        .perfil-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .perfil-nombre { font-size: 18px; font-weight: 800; color: white; }
        .perfil-email { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .perfil-badge { margin-left: auto; background: var(--orange-dim); border: 1px solid rgba(232,93,4,0.3); color: var(--orange); font-size: 10px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; padding: 6px 12px; border-radius: 999px; }
    </style>
</head>
<body>

    <div class="overlay" id="overlay"></div>

    <div class="topbar">
        <button class="hamburger" id="hamburger">
            <i class="fas fa-bars" style="font-size:14px;"></i>
        </button>
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:28px;height:28px;background:var(--orange);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-utensils" style="color:white;font-size:11px;"></i>
            </div>
            <span style="font-weight:800;font-size:13px;color:white;">{{ Str::limit($restaurante->nombre, 20) }}</span>
        </div>
    </div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="fas fa-utensils"></i></div>
            <div>
                <div class="brand-name">{{ Str::limit($restaurante->nombre, 18) }}</div>
                <div class="brand-sub">Panel del restaurante</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Principal</div>
            <a href="{{ route('restaurante.dashboard') }}" class="nav-link active">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-section">Gestión</div>
            <a href="{{ route('restaurante.eventos.index') }}" class="nav-link">
                <i class="fas fa-calendar-alt"></i> Mis Eventos
            </a>
            <a href="{{ route('restaurante.empleos.index') }}" class="nav-link">
                <i class="fas fa-briefcase"></i> Mis Empleos
            </a>
            <a href="{{ route('restaurante.galeria.index') }}" class="nav-link">
                <i class="fas fa-images"></i> Galería
            </a>
            <a href="{{ route('restaurante.perfil.edit') }}" class="nav-link">
                <i class="fas fa-store"></i> Mi Perfil Público
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('restaurantes.show', $restaurante) }}" class="nav-link" style="padding:8px 0;">
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

        <div class="page-header">
            <h1>Bienvenido 👋</h1>
            <p>Gestiona tu restaurante desde este panel. Todo lo que necesitas en un solo lugar.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <div class="stat-value">{{ $totalEventos }}</div>
                    <div class="stat-label">Eventos totales</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                <div>
                    <div class="stat-value">{{ $totalEmpleos }}</div>
                    <div class="stat-label">Empleos activos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-images"></i></div>
                <div>
                    <div class="stat-value">{{ $restaurante->imagenes?->count() ?? 0 }}</div>
                    <div class="stat-label">Fotos en galería</div>
                </div>
            </div>
        </div>

        <p class="section-title">Acciones rápidas</p>
        <div class="actions-grid">
            <a href="{{ route('restaurante.eventos.create') }}" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                Nuevo evento
            </a>
            <a href="{{ route('restaurante.empleos.create') }}" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                Nueva oferta de empleo
            </a>
            <a href="{{ route('restaurante.galeria.index') }}" class="action-btn">
                <i class="fas fa-camera"></i>
                Subir fotos
            </a>
            <a href="{{ route('restaurante.perfil.edit') }}" class="action-btn">
                <i class="fas fa-pen"></i>
                Editar perfil
            </a>
        </div>

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
                    Ver <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                </a>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-calendar-xmark"></i>
                <p>No tienes eventos próximos.<br>¡Crea tu primer evento!</p>
            </div>
        @endforelse

    </main>

    <script>
        const sidebar   = document.getElementById('sidebar');
        const overlay   = document.getElementById('overlay');
        const hamburger = document.getElementById('hamburger');
        hamburger?.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
        overlay?.addEventListener('click',   () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });
    </script>
</body>
</html>