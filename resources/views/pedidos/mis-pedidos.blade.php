<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Pedidos — Gastro Nicaragua</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue:   #2563eb;
            --border: #e5e7eb;
            --bg:     #f4f4f5;
            --text:   #111827;
            --muted:  #6b7280;
            --white:  #ffffff;
        }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        nav {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            position: sticky; top: 0; z-index: 10;
        }
        .logo {
            font-size: 17px; font-weight: 800; color: var(--text);
            text-decoration: none; letter-spacing: -0.3px;
        }
        .logo span { color: var(--blue); }
        .nav-actions {
            display: flex; align-items: center; gap: 8px;
        }
        .nav-btn {
            display: flex; align-items: center; gap: 6px;
            text-decoration: none; font-size: 13px; font-weight: 600;
            padding: 7px 14px; border-radius: 999px;
            transition: all 0.2s; white-space: nowrap;
        }
        .nav-btn i { font-size: 11px; }
        .nav-btn-home {
            border: 1px solid var(--border); color: var(--muted); background: var(--white);
        }
        .nav-btn-home:hover { background: var(--text); color: var(--white); border-color: var(--text); }
        .nav-btn-rest {
            border: 1.5px solid var(--blue); color: #fff; background: var(--blue);
        }
        .nav-btn-rest:hover { background: #1d4ed8; border-color: #1d4ed8; }
        .nav-btn-contact { color: var(--muted); padding: 7px 10px; }
        .nav-btn-contact:hover { color: var(--blue); }
        .nav-btn-login { color: var(--muted); padding: 7px 10px; }
        .nav-btn-login:hover { color: var(--blue); }
        .nav-btn-logout {
            color: var(--muted); background: transparent; border: none;
            font-family: inherit; font-size: 13px; font-weight: 600;
            cursor: pointer; padding: 7px 10px;
        }
        .nav-btn-logout:hover { color: #ef4444; }

        /* ── WRAP ── */
        .wrap {
            max-width: 640px;
            margin: 0 auto;
            padding: 36px 20px 64px;
        }

        /* ── FLASH ── */
        .flash-success {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px;
            padding: 10px 16px; margin-bottom: 16px;
            font-size: 13px; font-weight: 600; color: #15803d;
            display: flex; align-items: center; gap: 8px;
        }

        /* ── HEADER ── */
        .page-h { margin-bottom: 24px; }
        .page-h h1 { font-size: 22px; font-weight: 800; color: var(--text); }
        .page-h p  { font-size: 13px; color: var(--muted); margin-top: 3px; }

        /* ── PEDIDO ── */
        .pedido {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        /* cabecera */
        .pedido-top {
            padding: 16px 18px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .pedido-rest-wrap {
            display: flex; align-items: center; gap: 8px;
        }
        .tipo-tag {
            font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em;
            padding: 2px 7px; border-radius: 6px; flex-shrink: 0;
        }
        .tipo-tag-restaurante { background: #eff6ff; color: #2563eb; }
        .tipo-tag-gastrobar   { background: #f5f3ff; color: #7c3aed; }
        .pedido-rest {
            font-size: 15px; font-weight: 700; color: var(--text);
            text-decoration: none;
        }
        .pedido-rest:hover { color: var(--blue); }
        .pedido-id {
            font-size: 12px; color: var(--muted); font-weight: 500; margin-top: 2px;
        }

        /* acciones cabecera */
        .pedido-top-actions {
            display: flex; align-items: center; gap: 8px; flex-shrink: 0;
        }

        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: 999px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }

        /* botón eliminar */
        .btn-eliminar {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: 999px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
            background: #fef2f2; color: #b91c1c;
            border: 1.5px solid #fecaca;
            cursor: pointer; font-family: inherit;
            transition: all 0.2s;
        }
        .btn-eliminar:hover { background: #b91c1c; color: #fff; border-color: #b91c1c; }
        .btn-eliminar i { font-size: 10px; }

        /* ítems */
        .pedido-items {
            border-top: 1px solid var(--border);
            padding: 4px 18px;
        }
        .item {
            display: flex; align-items: baseline; justify-content: space-between;
            padding: 9px 0;
            border-bottom: 1px solid #f3f4f6;
            gap: 12px;
        }
        .item:last-child { border-bottom: none; }
        .item-info { display: flex; align-items: baseline; gap: 8px; }
        .item-qty  { font-size: 12px; font-weight: 800; color: var(--blue); min-width: 18px; }
        .item-name { font-size: 14px; font-weight: 500; color: var(--text); }
        .item-opts { font-size: 11px; color: var(--muted); margin-top: 2px; margin-left: 26px; }
        .item-price { font-size: 13px; font-weight: 700; color: var(--text); white-space: nowrap; flex-shrink: 0; }

        /* nota del cliente */
        .nota {
            margin: 0 18px 12px;
            padding: 9px 12px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            font-size: 12px; color: #92400e;
            display: flex; gap: 8px; align-items: flex-start;
        }

        /* pie */
        .pedido-foot {
            border-top: 1px solid var(--border);
            padding: 12px 18px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .foot-meta {
            font-size: 12px; color: var(--muted);
            display: flex; align-items: center; gap: 10px;
        }
        .foot-meta i { font-size: 10px; color: var(--blue); }
        .foot-total { font-size: 15px; font-weight: 800; color: var(--text); }
        .foot-total span { color: var(--blue); }

        /* strip cancelado */
        .cancelado-strip {
            background: #fef2f2;
            border-top: 1px solid #fecaca;
            padding: 8px 18px;
            font-size: 12px; font-weight: 600; color: #b91c1c;
            display: flex; align-items: center; gap: 6px;
        }

        /* empty */
        .empty {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 56px 24px;
            text-align: center;
        }
        .empty-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: #eff6ff; margin: 0 auto 16px;
            display: flex; align-items: center; justify-content: center;
        }
        .empty-icon i { font-size: 24px; color: var(--blue); }
        .empty h2 { font-size: 17px; font-weight: 800; margin-bottom: 6px; }
        .empty p  { font-size: 13px; color: var(--muted); margin-bottom: 20px; }
        .btn-blue {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--blue); color: white; text-decoration: none;
            padding: 10px 22px; border-radius: 10px; font-size: 13px; font-weight: 700;
            transition: opacity 0.15s;
        }
        .btn-blue:hover { opacity: 0.88; }

        .pagination-wrap { margin-top: 20px; }

        @media (max-width: 640px) {
            nav { padding: 0 14px; }
            .nav-btn span { display: none; }
            .nav-btn { padding: 7px 10px; }
            .wrap { padding: 24px 14px 48px; }
        }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="logo">Gastro<span>Nicaragua</span></a>

    <div class="nav-actions">
        <a href="{{ route('home') }}" class="nav-btn nav-btn-home">
            <i class="fas fa-home"></i> <span>Inicio</span>
        </a>
        <a href="{{ route('restaurantes.index') }}" class="nav-btn nav-btn-rest">
            <i class="fas fa-utensils"></i> <span>Explorar</span>
        </a>

        @if (Route::has('login'))
            @auth
                @if(auth()->user()->email === 'admin@turismo.ni')
                    <a href="{{ url('/dashboard') }}" class="nav-btn nav-btn-login hidden sm:flex">
                        <i class="fas fa-cog"></i> <span>Panel</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                    @csrf
                    <button type="submit" class="nav-btn nav-btn-logout">
                        <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Salir</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-btn nav-btn-login">
                    <i class="fas fa-user"></i> <span class="hidden sm:inline">Ingresar</span>
                </a>
            @endauth
        @endif
    </div>
</nav>

<div class="wrap">

    <div class="page-h">
        <h1>Mis Pedidos</h1>
        <p>Historial de tus pedidos realizados</p>
    </div>

    @if(session('success'))
        <div class="flash-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($pedidos->count() > 0)
        @foreach($pedidos as $pedido)
        @php
            $esGastrobar = $pedido->tipo_negocio === 'gastrobar';
            $estados     = $esGastrobar ? \App\Models\PedidoGastrobar::ESTADOS : \App\Models\Pedido::ESTADOS;
            $info        = $estados[$pedido->estado];
            $cancelado   = $pedido->estado === 'cancelado';
            $rutaShow    = $esGastrobar
                ? route('gastrobares.show', $pedido->establecimiento)
                : route('restaurantes.show', $pedido->establecimiento);
        @endphp

        <div class="pedido">

            {{-- Cabecera --}}
            <div class="pedido-top">
                <div>
                    <div class="pedido-rest-wrap">
                        <span class="tipo-tag {{ $esGastrobar ? 'tipo-tag-gastrobar' : 'tipo-tag-restaurante' }}">
                            {{ $esGastrobar ? 'Gastrobar' : 'Restaurante' }}
                        </span>
                        <a href="{{ $rutaShow }}" class="pedido-rest">
                            {{ $pedido->establecimiento->nombre }}
                        </a>
                    </div>
                    <div class="pedido-id">
                        #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}
                        · {{ $pedido->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="pedido-top-actions">
                    <span class="badge"
                          style="background:{{ $info['color'] }}18;color:{{ $info['color'] }};border:1.5px solid {{ $info['color'] }}30;">
                        <span class="badge-dot" style="background:{{ $info['color'] }};"></span>
                        {{ $info['label'] }}
                    </span>

                    {{-- Botón eliminar: solo si está cancelado --}}
                    @if($cancelado)
                    <form method="POST"
                          action="{{ route('pedidos.destroy', $pedido->id) }}"
                          class="inline m-0"
                          onsubmit="return confirm('¿Eliminar este pedido cancelado?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="tipo" value="{{ $esGastrobar ? 'gastrobar' : 'restaurante' }}">
                        <button type="submit" class="btn-eliminar">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Ítems --}}
            <div class="pedido-items">
                @foreach($pedido->items as $item)
                <div class="item">
                    <div>
                        <div class="item-info">
                            <span class="item-qty">{{ $item->cantidad }}×</span>
                            <span class="item-name">{{ $item->plato->nombre ?? 'Plato no disponible' }}</span>
                        </div>
                        @if($item->notas)
                        <div class="item-opts">{{ $item->notas }}</div>
                        @endif
                    </div>
                    <span class="item-price">C$ {{ number_format($item->subtotal, 0) }}</span>
                </div>
                @endforeach
            </div>

            {{-- Nota general --}}
            @if($pedido->notas)
            <div class="nota">
                <i class="fas fa-sticky-note" style="color:#f59e0b;margin-top:1px;flex-shrink:0;font-size:11px;"></i>
                {{ $pedido->notas }}
            </div>
            @endif

            {{-- Strip cancelado --}}
            @if($cancelado)
            <div class="cancelado-strip">
                <i class="fas fa-ban" style="font-size:11px;"></i> Pedido cancelado por el establecimiento
            </div>
            @endif

            {{-- Pie --}}
            <div class="pedido-foot">
                <div class="foot-meta">
                    @if(in_array($pedido->tipo, ['para_llevar', 'retiro']))
                        <i class="fas fa-bag-shopping"></i> Para llevar
                    @elseif($pedido->tipo === 'envio')
                        <i class="fas fa-motorcycle"></i> Envío
                    @else
                        <i class="fas fa-chair"></i> Mesa
                    @endif
                    <span>· {{ $pedido->items->count() }} platillo{{ $pedido->items->count() !== 1 ? 's' : '' }}</span>
                </div>
                <div class="foot-total">C$ <span>{{ number_format($pedido->total, 0) }}</span></div>
            </div>

        </div>
        @endforeach

        <div class="pagination-wrap">{{ $pedidos->links() }}</div>

    @else
        <div class="empty">
            <div class="empty-icon"><i class="fas fa-bag-shopping"></i></div>
            <h2>Aún no tienes pedidos</h2>
            <p>Explora los restaurantes y gastrobares y haz tu primer pedido</p>
            <a href="{{ route('restaurantes.index') }}" class="btn-blue">
                <i class="fas fa-utensils" style="font-size:11px;"></i> Explorar
            </a>
        </div>
    @endif

</div>

</body>
</html>
