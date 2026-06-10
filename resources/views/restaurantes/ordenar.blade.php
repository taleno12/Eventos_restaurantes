<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ordenar — {{ $restaurante->nombre }} | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --orange: #ea580c; --orange-light: #fed7aa;
            --dark: #0c0a09; --stone: #fafaf9;
            --border: #e7e5e4; --text-muted: #78716c; --text-main: #1c1917;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── FOOTER SIEMPRE ABAJO ── */
        html, body { height: 100%; }
        html {
            min-height: 100%;
        }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--stone); color: var(--text-main);
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .page-content {
            flex: 1 1 auto !important;
            display: flex;
            flex-direction: column;
        }
        footer { flex-shrink: 0; }

        .premium-title { font-family: 'Playfair Display', serif; }

        /* TOPBAR */
        .topbar {
            background: var(--dark); padding: 0 40px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.06); position: sticky; top: 0; z-index: 100;
        }
        @media (max-width: 600px) { .topbar { padding: 0 16px; } }

        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .btn-volver {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.75); font-size: 12px; font-weight: 700;
            padding: 7px 16px; border-radius: 999px; text-decoration: none;
            transition: all 0.2s; letter-spacing: 0.04em;
        }
        .btn-volver:hover { background: rgba(234,88,12,0.2); color: white; border-color: rgba(234,88,12,0.4); }

        .topbar-title {
            color: white; font-size: 14px; font-weight: 700;
            display: flex; align-items: center; gap: 8px;
        }
        .topbar-title span { color: rgba(255,255,255,0.4); font-weight: 400; }

        /* MINI HERO */
        .mini-hero {
            background: var(--dark); padding: 28px 40px;
            display: flex; align-items: center; gap: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            position: relative; overflow: hidden;
        }
        .mini-hero::before {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(circle at 90% 50%, rgba(234,88,12,0.1) 0%, transparent 60%);
            pointer-events: none;
        }
        @media (max-width: 600px) { .mini-hero { padding: 20px 16px; } }

        .mini-hero-img {
            width: 64px; height: 64px; border-radius: 16px;
            object-fit: cover; border: 2px solid rgba(255,255,255,0.1); flex-shrink: 0;
            background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .mini-hero-info { flex: 1; min-width: 0; }
        .mini-hero-nombre { font-size: 20px; font-weight: 900; color: white; margin-bottom: 4px; }
        .mini-hero-sub { font-size: 12px; color: rgba(255,255,255,0.45); display: flex; align-items: center; gap: 6px; }
        .mini-hero-sub i { color: var(--orange); }

        /* LAYOUT */
        .page-wrap {
            max-width: 1100px; margin: 0 auto; padding: 36px 40px 80px;
            display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;
            flex: 1;
        }
        @media (max-width: 900px) { .page-wrap { grid-template-columns: 1fr; padding: 20px 16px 60px; } }

        /* CARD */
        .card { background: white; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 2px 16px rgba(0,0,0,0.04); overflow: hidden; }
        .card-body { padding: 28px 32px; }
        @media (max-width: 600px) { .card-body { padding: 18px 16px; } }

        .section-label {
            font-size: 10px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
            color: #a8a29e; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
        }
        .section-label i { color: var(--orange); font-size: 11px; }
        .section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* ── PLATO CARD HORIZONTAL ── */
        .plato-card {
            border: 1px solid #e7e5e4;
            border-radius: 14px;
            overflow: hidden;
            background: white;
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            flex-direction: row;
            align-items: stretch;
            gap: 0;
        }
        .plato-card:hover {
            border-color: #ea580c;
            box-shadow: 0 4px 20px rgba(234,88,12,0.12);
        }

        .plato-img-wrap {
            width: 110px; min-width: 110px;
            flex-shrink: 0; position: relative; overflow: hidden;
        }
        .plato-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.4s;
        }
        .plato-card:hover .plato-img-wrap img { transform: scale(1.06); }

        .plato-card-content {
            padding: 12px 16px;
            display: flex; flex-direction: column;
            flex: 1; gap: 4px; min-width: 0;
        }
        .plato-card-footer { margin-top: auto; padding-top: 8px; }

        .cat-badge-inline {
            background: #f5f5f4; color: #a8a29e;
            font-size: 9px; font-weight: 800;
            letter-spacing: 0.1em; text-transform: uppercase;
            padding: 2px 8px; border-radius: 999px;
            display: inline-block; margin-bottom: 2px; align-self: flex-start;
        }
        .cat-badge-overlay {
            position: absolute; top: 8px; left: 8px;
            background: rgba(255,255,255,0.92); border-radius: 999px;
            padding: 3px 10px; font-size: 10px; font-weight: 800;
            color: #78716c; letter-spacing: 0.06em; text-transform: uppercase;
        }

        /* CARRITO */
        .carrito-wrap { position: sticky; top: 76px; }
        .carrito-card {
            background: white; border-radius: 20px; border: 1px solid var(--border);
            box-shadow: 0 2px 16px rgba(0,0,0,0.04); overflow: hidden;
        }
        .carrito-header {
            background: var(--dark); padding: 18px 24px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .carrito-titulo { color: white; font-size: 14px; font-weight: 800; display: flex; align-items: center; gap: 8px; }
        .carrito-badge { background: var(--orange); color: white; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 800; }
        .carrito-vaciar { font-size: 11px; color: rgba(255,255,255,0.4); background: none; border: none; cursor: pointer; font-weight: 600; transition: color 0.2s; }
        .carrito-vaciar:hover { color: rgba(239,68,68,0.8); }
        .carrito-body { padding: 20px 24px; }
        .carrito-empty { text-align: center; padding: 32px 20px; color: #a8a29e; font-size: 13px; font-weight: 600; }
        .carrito-empty i { display: block; font-size: 32px; color: #e7e5e4; margin-bottom: 10px; }
        .carrito-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 0; border-bottom: 1px solid #f5f5f4;
        }
        .carrito-item:last-child { border-bottom: none; }
        .item-nombre { font-size: 13px; font-weight: 700; color: var(--text-main); }
        .item-precio-u { font-size: 12px; color: #a8a29e; }
        .qty-btn {
            width: 26px; height: 26px; border-radius: 50%; border: 1px solid #e7e5e4;
            background: white; cursor: pointer; font-size: 14px;
            display: flex; align-items: center; justify-content: center; color: #78716c;
            transition: all 0.15s;
        }
        .qty-btn:hover { border-color: var(--orange); color: var(--orange); }
        .qty-num { font-size: 13px; font-weight: 700; min-width: 20px; text-align: center; }
        .item-subtotal { font-size: 13px; font-weight: 800; color: var(--orange); min-width: 64px; text-align: right; }
        .carrito-total-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 0; border-top: 1px solid #e7e5e4; border-bottom: 1px solid #e7e5e4;
            margin: 4px 0 16px;
        }
        .total-label { font-weight: 700; color: var(--text-main); font-size: 14px; }
        .total-valor { font-size: 20px; font-weight: 900; color: var(--orange); }
        .radio-tipo {
            flex: 1; display: flex; align-items: center; gap: 8px;
            padding: 10px 14px; border: 1px solid #e7e5e4; border-radius: 10px;
            cursor: pointer; transition: all 0.2s;
        }
        .radio-tipo:has(input:checked) { border-color: var(--orange); background: #fff7ed; }
        .btn-confirmar {
            width: 100%; padding: 15px; border-radius: 12px; border: none;
            background: var(--orange); color: white; font-size: 14px; font-weight: 800;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            gap: 8px; transition: all 0.2s; font-family: inherit; letter-spacing: 0.02em;
        }
        .btn-confirmar:hover { background: #c2410c; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(234,88,12,0.35); }
        .btn-confirmar:disabled { background: #d6d3d1; cursor: not-allowed; transform: none; box-shadow: none; }

        /* FLOTANTE */
        #carrito-flotante {
            display: none; position: fixed; bottom: 24px; right: 24px; z-index: 999;
            background: var(--orange); color: white; border-radius: 999px;
            padding: 14px 22px; font-size: 13px; font-weight: 800; cursor: pointer;
            box-shadow: 0 8px 32px rgba(234,88,12,0.4); align-items: center; gap: 8px;
            transition: all 0.3s;
        }
        #carrito-flotante:hover { background: #c2410c; transform: translateY(-2px); }
        @media (min-width: 901px) { #carrito-flotante { display: none !important; } }
    </style>
</head>
<body class="min-h-screen flex flex-col">

   <div class="page-content flex-1 flex flex-col">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('restaurantes.show', $restaurante) }}" class="btn-volver">
                <i class="fas fa-arrow-left" style="font-size:10px;"></i> Volver
            </a>
            <div class="topbar-title">
                <i class="fas fa-utensils" style="color:#ea580c;font-size:12px;"></i>
                Ordenar <span>/ {{ $restaurante->nombre }}</span>
            </div>
        </div>
        @auth
            <a href="{{ route('pedidos.mis') }}"
               style="display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.75);font-size:12px;font-weight:700;padding:7px 16px;border-radius:999px;text-decoration:none;">
                <i class="fas fa-bag-shopping" style="font-size:10px;"></i> Mis Pedidos
            </a>
        @endauth
    </div>

    {{-- MINI HERO --}}
    <div class="mini-hero">
        <div class="mini-hero-img">
            @php
                $imgUrl = $restaurante->foto_portada
                    ? asset('storage/'.$restaurante->foto_portada)
                    : ($restaurante->imagenes && $restaurante->imagenes->count() > 0
                        ? asset('storage/'.$restaurante->imagenes->first()->ruta_foto)
                        : null);
            @endphp
            @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $restaurante->nombre }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <i class="fas fa-utensils" style="font-size:22px;color:rgba(255,255,255,0.3);"></i>
            @endif
        </div>
        <div class="mini-hero-info">
            <div class="mini-hero-nombre premium-title">{{ $restaurante->nombre }}</div>
            <div class="mini-hero-sub">
                <i class="fas fa-map-marker-alt"></i>
                {{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}
                @if($restaurante->especialidad)
                    <span style="opacity:0.4;">·</span> {{ $restaurante->especialidad }}
                @endif
            </div>
        </div>
    </div>

    {{-- CONTENIDO --}}
    <div class="page-wrap">

        {{-- COLUMNA MENÚ --}}
        <div>
            <div class="card">
                <div class="card-body">
                    <div class="section-label">
                        <i class="fas fa-utensils"></i> Menú
                        <span style="font-size:10px;color:#d6d3d1;font-weight:500;text-transform:none;letter-spacing:0;">
                            — {{ $platos->flatten()->count() }} platillos
                        </span>
                    </div>

                    @if(session('pedido_success'))
                        <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
                            <i class="fas fa-check-circle"></i> {{ session('pedido_success') }}
                        </div>
                    @endif

                    {{-- Tabs categorías --}}
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;">
                        <button onclick="filtrarCategoria('todas')" id="tab-todas"
                                style="padding:6px 16px;border-radius:999px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid #ea580c;background:#ea580c;color:white;transition:all 0.2s;">
                            Todos
                        </button>
                        @foreach($platos->keys() as $cat)
                            <button onclick="filtrarCategoria('{{ Str::slug($cat) }}')" id="tab-{{ Str::slug($cat) }}"
                                    style="padding:6px 16px;border-radius:999px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid #e7e5e4;background:white;color:#78716c;transition:all 0.2s;">
                                {{ $cat ?: 'Sin categoría' }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Lista platos HORIZONTAL --}}
                    <div id="platos-grid" style="display:flex;flex-direction:column;gap:12px;">
                        @foreach($platos as $categoria => $items)

                            <div class="cat-group-label" data-cat="{{ Str::slug($categoria) }}"
                                 style="font-size:10px;font-weight:800;letter-spacing:0.16em;text-transform:uppercase;color:#a8a29e;display:flex;align-items:center;gap:10px;margin-top:8px;">
                                <i class="fas fa-circle" style="color:#ea580c;font-size:6px;"></i>
                                {{ $categoria ?: 'Sin categoría' }}
                                <span style="flex:1;height:1px;background:#e7e5e4;display:block;"></span>
                            </div>

                            @foreach($items as $plato)
                            <div class="plato-card" data-categoria="{{ Str::slug($categoria) }}">

                                @if($plato->imagen)
                                    <div class="plato-img-wrap">
                                        <img src="{{ asset('storage/'.$plato->imagen) }}" alt="{{ $plato->nombre }}">
                                        <span class="cat-badge-overlay">{{ $categoria ?: 'Menú' }}</span>
                                    </div>
                                @else
                                    <div class="plato-img-wrap" style="background:#f5f5f4;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-utensils" style="font-size:24px;color:#d6d3d1;"></i>
                                    </div>
                                @endif

                                <div class="plato-card-content">
                                    @if(!$plato->imagen)
                                        <span class="cat-badge-inline">{{ $categoria ?: 'Menú' }}</span>
                                    @endif

                                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                                        <span style="font-weight:700;color:#1c1917;font-size:14px;line-height:1.3;flex:1;min-width:0;">{{ $plato->nombre }}</span>
                                        <span style="color:#ea580c;font-weight:800;font-size:15px;white-space:nowrap;">C$ {{ number_format($plato->precio, 0) }}</span>
                                    </div>

                                    @if($plato->descripcion)
                                        <p style="font-size:12px;color:#a8a29e;line-height:1.5;margin:0;">{{ Str::limit($plato->descripcion, 80) }}</p>
                                    @endif

                                    <div class="plato-card-footer">
                                        @auth
                                            <button onclick="agregarAlCarrito({{ $plato->id }}, '{{ addslashes($plato->nombre) }}', {{ $plato->precio }})"
                                                    id="btn-agregar-{{ $plato->id }}"
                                                    style="padding:7px 18px;border-radius:10px;border:none;background:#0c0a09;color:white;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:background 0.2s;"
                                                    onmouseover="this.style.background='#ea580c'"
                                                    onmouseout="this.style.background='#0c0a09'">
                                                <i class="fas fa-plus" style="font-size:10px;"></i> Agregar
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}"
                                               style="padding:7px 18px;border-radius:10px;border:1px solid #e7e5e4;background:white;color:#78716c;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all 0.2s;"
                                               onmouseover="this.style.borderColor='#ea580c';this.style.color='#ea580c'"
                                               onmouseout="this.style.borderColor='#e7e5e4';this.style.color='#78716c'">
                                                <i class="fas fa-sign-in-alt" style="font-size:10px;"></i> Inicia sesión para pedir
                                            </a>
                                        @endauth
                                    </div>
                                </div>

                            </div>
                            @endforeach
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        {{-- COLUMNA CARRITO --}}
        @auth
        <div class="carrito-wrap">
            <div class="carrito-card">
                <div class="carrito-header">
                    <div class="carrito-titulo">
                        <i class="fas fa-shopping-bag"></i>
                        Tu pedido
                        <span class="carrito-badge" id="carrito-count">0</span>
                    </div>
                    <button onclick="limpiarCarrito()" class="carrito-vaciar">
                        <i class="fas fa-trash" style="font-size:10px;"></i> Vaciar
                    </button>
                </div>
                <div class="carrito-body">
                    <div id="carrito-empty" class="carrito-empty">
                        <i class="fas fa-shopping-bag"></i>
                        Aún no has agregado nada.<br>¡Selecciona tus platillos!
                    </div>
                    <div id="carrito-items"></div>
                    <div id="carrito-checkout" style="display:none;">
                        <div class="carrito-total-row">
                            <span class="total-label">Total</span>
                            <span class="total-valor" id="carrito-total">C$ 0</span>
                        </div>
                        <form method="POST" action="{{ route('pedidos.store', $restaurante) }}" id="form-pedido">
                            @csrf
                            <div id="items-hidden"></div>
                            <div style="margin-bottom:12px;">
                                <label style="font-size:11px;font-weight:700;color:#78716c;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:8px;">Tipo de pedido</label>
                                <div style="display:flex;gap:8px;">
                                    <label class="radio-tipo" id="label-mesa">
                                        <input type="radio" name="tipo" value="mesa" checked style="accent-color:#ea580c;">
                                        <span style="font-size:13px;font-weight:600;color:#1c1917;">
                                            <i class="fas fa-chair" style="color:#ea580c;margin-right:4px;"></i> Mesa
                                        </span>
                                    </label>
                                    <label class="radio-tipo" id="label-llevar">
                                        <input type="radio" name="tipo" value="para_llevar" style="accent-color:#ea580c;">
                                        <span style="font-size:13px;font-weight:600;color:#1c1917;">
                                            <i class="fas fa-bag-shopping" style="color:#ea580c;margin-right:4px;"></i> Para llevar
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label style="font-size:11px;font-weight:700;color:#78716c;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:6px;">
                                    Notas <span style="font-weight:400;text-transform:none;">(opcional)</span>
                                </label>
                                <textarea name="notas" rows="2" maxlength="500"
                                          placeholder="Ej: Sin cebolla, alergia al maní..."
                                          style="width:100%;padding:10px 14px;border:1px solid #e7e5e4;border-radius:10px;font-size:13px;font-family:inherit;outline:none;resize:none;transition:border-color 0.2s;"
                                          onfocus="this.style.borderColor='#ea580c'"
                                          onblur="this.style.borderColor='#e7e5e4'"></textarea>
                            </div>
                            <button type="submit" class="btn-confirmar">
                                <i class="fas fa-paper-plane"></i> Confirmar Pedido
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="carrito-wrap">
            <div class="carrito-card">
                <div class="carrito-header">
                    <div class="carrito-titulo"><i class="fas fa-shopping-bag"></i> Tu pedido</div>
                </div>
                <div class="carrito-body" style="text-align:center;padding:32px 24px;">
                    <i class="fas fa-lock" style="font-size:28px;color:#e7e5e4;display:block;margin-bottom:12px;"></i>
                    <p style="font-size:14px;color:#78716c;font-weight:600;margin-bottom:16px;">Inicia sesión para hacer tu pedido</p>
                    <a href="{{ route('login') }}"
                       style="display:inline-flex;align-items:center;gap:7px;background:#ea580c;color:white;text-decoration:none;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:700;">
                        <i class="fas fa-sign-in-alt" style="font-size:11px;"></i> Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
        @endauth

    </div>{{-- /page-wrap --}}

    {{-- CARRITO FLOTANTE móvil --}}
    @auth
    <div id="carrito-flotante" onclick="scrollAlCarrito()">
        <i class="fas fa-shopping-bag"></i>
        <span id="flotante-count">0 items</span>
        <span style="opacity:0.6;">·</span>
        <span id="flotante-total">C$ 0</span>
    </div>
    @endauth

    </div>{{-- /page-content --}}

    {{-- FOOTER --}}
    <footer class="bg-stone-900 text-stone-300 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 pt-12 pb-8 sm:pt-16 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 mb-10">
                <div class="sm:col-span-2 lg:col-span-4 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl font-bold tracking-tight text-white premium-title italic">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    </div>
                    <p class="text-stone-400 text-sm leading-relaxed font-light">
                        La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                        Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                    </p>
                    <div class="flex items-center gap-3 pt-1">
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-600 hover:text-white transition-all text-xs no-underline"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Portal</h4>
                    <ul class="space-y-2.5 text-sm p-0 list-none m-0">
                        <li><a href="{{ route('home') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Inicio</a></li>
                        <li><a href="{{ route('restaurantes.index') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Restaurantes</a></li>
                        <li><a href="{{ route('gastrobares.index') }}" class="text-stone-400 hover:text-purple-400 transition-all inline-block no-underline">Gastrobares</a></li>
                        <li><a href="{{ route('empleos.index') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Bolsa de Empleos</a></li>
                        <li><a href="{{ route('contacto') }}" class="text-stone-400 hover:text-orange-500 transition-all inline-block no-underline">Contacto</a></li>
                    </ul>
                </div>
                <div class="lg:col-span-3 space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Destinos Destacados</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm text-stone-400 font-light">
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Masaya</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Granada</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>León</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>San Juan del Sur</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Estelí</span>
                        <span class="hover:text-white transition-colors cursor-pointer"><i class="fas fa-chevron-right text-[9px] text-orange-600 mr-1.5"></i>Matagalpa</span>
                    </div>
                </div>
            </div>
            <div class="border-t border-stone-800 pt-6 text-center text-xs text-stone-500 font-light flex flex-col sm:flex-row justify-between items-center gap-3">
                <p>&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-stone-500 hover:text-stone-400 no-underline">Política de Privacidad</a>
                    <a href="#" class="text-stone-500 hover:text-stone-400 no-underline">Términos de Servicio</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    let carrito = {};

    function agregarAlCarrito(id, nombre, precio) {
        if (carrito[id]) { carrito[id].cantidad++; }
        else { carrito[id] = { nombre, precio, cantidad: 1 }; }
        renderCarrito();
        const btn = document.getElementById(`btn-agregar-${id}`);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-check" style="font-size:10px;"></i> Agregado';
            btn.style.background = '#22c55e';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-plus" style="font-size:10px;"></i> Agregar';
                btn.style.background = '#0c0a09';
            }, 1000);
        }
    }

    function cambiarCantidad(id, delta) {
        if (!carrito[id]) return;
        carrito[id].cantidad += delta;
        if (carrito[id].cantidad <= 0) delete carrito[id];
        renderCarrito();
    }

    function limpiarCarrito() { carrito = {}; renderCarrito(); }

    function renderCarrito() {
        const ids = Object.keys(carrito);
        const emptyEl    = document.getElementById('carrito-empty');
        const checkoutEl = document.getElementById('carrito-checkout');
        const itemsEl    = document.getElementById('carrito-items');
        const hiddenEl   = document.getElementById('items-hidden');
        const countEl    = document.getElementById('carrito-count');
        const totalEl    = document.getElementById('carrito-total');
        const flotante   = document.getElementById('carrito-flotante');
        const flotCount  = document.getElementById('flotante-count');
        const flotTotal  = document.getElementById('flotante-total');

        if (!emptyEl) return;

        if (ids.length === 0) {
            emptyEl.style.display = 'block';
            checkoutEl.style.display = 'none';
            itemsEl.innerHTML = '';
            if (flotante) flotante.style.display = 'none';
            countEl.textContent = '0';
            return;
        }

        emptyEl.style.display = 'none';
        checkoutEl.style.display = 'block';
        if (flotante) flotante.style.display = 'flex';

        let total = 0, totalItems = 0, itemsHTML = '', hiddenHTML = '';

        ids.forEach((id, index) => {
            const item = carrito[id];
            const subtotal = item.precio * item.cantidad;
            total += subtotal; totalItems += item.cantidad;
            itemsHTML += `
                <div class="carrito-item">
                    <div style="flex:1;min-width:0;">
                        <div class="item-nombre">${item.nombre}</div>
                        <div class="item-precio-u">C$ ${item.precio.toLocaleString()} c/u</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <button class="qty-btn" onclick="cambiarCantidad(${id}, -1)">−</button>
                        <span class="qty-num">${item.cantidad}</span>
                        <button class="qty-btn" onclick="cambiarCantidad(${id}, 1)">+</button>
                    </div>
                    <div class="item-subtotal">C$ ${subtotal.toLocaleString()}</div>
                </div>`;
            hiddenHTML += `
                <input type="hidden" name="items[${index}][id]" value="${id}">
                <input type="hidden" name="items[${index}][cantidad]" value="${item.cantidad}">`;
        });

        itemsEl.innerHTML = itemsHTML;
        hiddenEl.innerHTML = hiddenHTML;
        countEl.textContent = totalItems;
        totalEl.textContent = `C$ ${total.toLocaleString()}`;
        if (flotCount) flotCount.textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
        if (flotTotal) flotTotal.textContent = `C$ ${total.toLocaleString()}`;
    }

    function scrollAlCarrito() {
        const el = document.querySelector('.carrito-wrap');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function filtrarCategoria(cat) {
        document.querySelectorAll('.plato-card').forEach(card => {
            card.style.display = (cat === 'todas' || card.dataset.categoria === cat) ? 'flex' : 'none';
        });
        document.querySelectorAll('.cat-group-label').forEach(label => {
            label.style.display = (cat === 'todas' || label.dataset.cat === cat) ? 'flex' : 'none';
        });
        document.querySelectorAll('[id^="tab-"]').forEach(btn => {
            const isActive = btn.id === `tab-${cat}`;
            btn.style.background  = isActive ? '#ea580c' : 'white';
            btn.style.color       = isActive ? 'white'   : '#78716c';
            btn.style.borderColor = isActive ? '#ea580c' : '#e7e5e4';
        });
    }

    renderCarrito();
    </script>

</body>
</html>
