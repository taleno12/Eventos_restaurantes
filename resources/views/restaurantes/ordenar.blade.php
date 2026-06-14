<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ordenar — {{ $restaurante->nombre }} | Gastro Nicaragua</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900|instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange:  #ea580c;
            --orange2: #c2410c;
            --border:  #e5e7eb;
            --bg:      #f9fafb;
            --text:    #111827;
            --muted:   #6b7280;
            --white:   #ffffff;
            --radius:  12px;
            --shadow:  0 1px 3px rgba(0,0,0,0.07), 0 4px 12px rgba(0,0,0,0.04);
        }

        html, body { height: 100%; }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--white);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        a { text-decoration: none; color: inherit; }

        /* ══ TOPBAR ══ */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 200;
            height: 56px;
            display: flex; align-items: center;
            padding: 0 24px; gap: 16px;
        }
        .btn-back {
            width: 34px; height: 34px; border-radius: 50%;
            border: 1.5px solid var(--border);
            background: var(--white); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--text); transition: all 0.18s; flex-shrink: 0;
        }
        .btn-back:hover { border-color: var(--orange); color: var(--orange); background: #fff7ed; }
        .topbar-brand {
            font-size: 15px; font-weight: 700; color: var(--text);
            display: flex; align-items: center; gap: 6px;
        }
        .topbar-brand .sep { color: var(--border); font-weight: 400; }
        .topbar-brand .name { color: var(--muted); font-weight: 500; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
        .btn-mis-pedidos {
            display: inline-flex; align-items: center; gap: 7px;
            border: 1.5px solid var(--border); border-radius: 999px;
            padding: 7px 16px; font-size: 13px; font-weight: 600;
            color: var(--muted); background: var(--white); cursor: pointer;
            transition: all 0.18s;
        }
        .btn-mis-pedidos:hover { border-color: var(--orange); color: var(--orange); }

        /* ══ HERO ══ */
        .rest-hero { border-bottom: 1px solid var(--border); padding: 20px 24px 0; background: var(--white); }
        .rest-hero-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: flex-start; gap: 16px; }
        .rest-logo {
            width: 80px; height: 80px; border-radius: 16px;
            border: 1.5px solid var(--border); object-fit: cover;
            background: var(--bg); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .rest-logo img { width: 100%; height: 100%; object-fit: cover; }
        .rest-info { flex: 1; padding-bottom: 20px; }
        .rest-info h1 { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .rest-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; font-size: 13px; color: var(--muted); }
        .rest-meta i { color: var(--orange); font-size: 11px; }
        .rest-meta-sep { color: var(--border); }

        /* ══ SEARCH ══ */
        .search-wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px 16px; background: var(--white); }
        .search-input-wrap { position: relative; max-width: 480px; }
        .search-input {
            width: 100%; padding: 10px 16px 10px 40px;
            border: 1.5px solid var(--border); border-radius: 999px;
            font-size: 14px; font-family: inherit; outline: none;
            background: var(--bg); transition: all 0.2s; color: var(--text);
        }
        .search-input:focus { border-color: var(--orange); background: var(--white); box-shadow: 0 0 0 3px rgba(234,88,12,0.08); }
        .search-input::placeholder { color: #9ca3af; }
        .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; pointer-events: none; }

        /* ══ CATS BAR ══ */
        .cats-bar { position: sticky; top: 56px; z-index: 100; background: var(--white); border-bottom: 1px solid var(--border); }
        .cats-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 24px;
            display: flex; align-items: center; gap: 4px;
            overflow-x: auto; scrollbar-width: none;
        }
        .cats-inner::-webkit-scrollbar { display: none; }
        .cat-pill {
            flex-shrink: 0; padding: 10px 18px;
            font-size: 13px; font-weight: 700; cursor: pointer;
            border: none; background: none; color: var(--muted);
            border-bottom: 2.5px solid transparent;
            transition: all 0.18s; white-space: nowrap; font-family: inherit;
        }
        .cat-pill:hover { color: var(--text); }
        .cat-pill.active { color: var(--orange); border-bottom-color: var(--orange); }

        /* ══ PAGE BODY ══ */
        .page-body {
            flex: 1;
            max-width: 1200px; margin: 0 auto; width: 100%;
            padding: 28px 24px 80px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 28px; align-items: start;
        }
        @media (max-width: 960px) {
            .page-body { grid-template-columns: 1fr; }
            .sidebar-col { display: none; }
        }

        /* ══ PLATO ROW ══ */
        .cat-section { margin-bottom: 32px; }
        .cat-section-title {
            font-size: 18px; font-weight: 800; color: var(--text);
            margin-bottom: 16px; padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }
        .platos-list { display: flex; flex-direction: column; gap: 1px; background: var(--border); border-radius: 14px; overflow: hidden; border: 1px solid var(--border); }

        .plato-row {
            display: flex; align-items: center;
            background: var(--white); padding: 18px 20px;
            gap: 16px; cursor: pointer;
            transition: background 0.15s;
        }
        .plato-row:hover { background: #fafaf9; }
        .plato-row:first-child { border-radius: 13px 13px 0 0; }
        .plato-row:last-child  { border-radius: 0 0 13px 13px; }
        .plato-row:only-child  { border-radius: 13px; }

        .plato-text { flex: 1; min-width: 0; }
        .plato-nombre { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 4px; line-height: 1.3; }
        .plato-desc { font-size: 13px; color: var(--muted); line-height: 1.5; margin-bottom: 8px;
                      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .plato-precio { font-size: 15px; font-weight: 800; color: var(--text); }

        .plato-img-side {
            width: 110px; height: 88px; border-radius: 12px; overflow: hidden;
            flex-shrink: 0; background: var(--bg); position: relative;
        }
        .plato-img-side img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .plato-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f3f4f6; }
        .plato-img-placeholder i { font-size: 22px; color: #d1d5db; }

        .btn-add-overlay {
            position: absolute; bottom: 6px; right: 6px;
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--orange); color: white; border: none;
            font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(234,88,12,0.4); transition: all 0.18s;
            font-weight: 700; line-height: 1;
        }
        .btn-add-overlay:hover { background: var(--orange2); transform: scale(1.1); }

        /* ══ SIDEBAR CARRITO ══ */
        .sidebar-col { position: sticky; top: calc(56px + 45px + 16px); }
        .carrito-card { background: var(--white); border-radius: 16px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
        .carrito-head { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .carrito-head-title { font-size: 16px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 8px; }
        .carrito-count-badge { background: var(--orange); color: white; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 800; }
        .btn-vaciar { font-size: 12px; color: var(--muted); background: none; border: none; cursor: pointer; font-weight: 600; transition: color 0.18s; font-family: inherit; }
        .btn-vaciar:hover { color: #dc2626; }
        .carrito-body { padding: 0; }
        .carrito-empty-state { padding: 48px 24px; text-align: center; }
        .carrito-empty-state .empty-img { width: 64px; height: 64px; margin: 0 auto 14px; display: flex; align-items: center; justify-content: center; }
        .carrito-empty-state p { font-size: 14px; color: var(--muted); font-weight: 500; line-height: 1.5; }

        .carrito-items-list { padding: 8px 0; }
        .carrito-item-row { display: flex; align-items: center; gap: 12px; padding: 12px 20px; border-bottom: 1px solid #f3f4f6; }
        .carrito-item-row:last-child { border-bottom: none; }
        .ci-name { font-size: 13px; font-weight: 700; color: var(--text); }
        .ci-unit  { font-size: 12px; color: var(--muted); }
        .qty-controls { display: flex; align-items: center; gap: 6px; margin-left: auto; flex-shrink: 0; }
        .qty-btn {
            width: 28px; height: 28px; border-radius: 50%;
            border: 1.5px solid var(--border); background: var(--white);
            cursor: pointer; font-size: 15px; font-weight: 700;
            display: flex; align-items: center; justify-content: center; color: var(--muted);
            transition: all 0.15s; line-height: 1;
        }
        .qty-btn:hover { border-color: var(--orange); color: var(--orange); }
        .qty-num { font-size: 14px; font-weight: 800; min-width: 22px; text-align: center; }
        .ci-subtotal { font-size: 14px; font-weight: 800; color: var(--text); min-width: 70px; text-align: right; flex-shrink: 0; }

        .carrito-summary { padding: 16px 20px; border-top: 1px solid var(--border); }
        .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .total-label { font-size: 15px; font-weight: 700; color: var(--text); }
        .total-valor { font-size: 22px; font-weight: 900; color: var(--orange); }

        .tipo-grid { display: flex; gap: 8px; margin-bottom: 14px; }
        .tipo-opt {
            flex: 1; display: flex; align-items: center; gap: 8px;
            padding: 10px 12px; border: 1.5px solid var(--border);
            border-radius: 10px; cursor: pointer; transition: all 0.18s;
            font-size: 13px; font-weight: 600; color: var(--muted);
        }
        .tipo-opt:has(input:checked) { border-color: var(--orange); background: #fff7ed; color: var(--orange); }
        .tipo-opt input { accent-color: var(--orange); }

        .notas-input {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 13px; font-family: inherit; outline: none; resize: none;
            transition: border-color 0.2s; color: var(--text); background: var(--white);
            margin-bottom: 14px;
        }
        .notas-input:focus { border-color: var(--orange); }
        .notas-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); display: block; margin-bottom: 6px; }

        .btn-confirmar {
            width: 100%; padding: 15px; border-radius: 12px; border: none;
            background: var(--orange); color: white; font-size: 15px; font-weight: 800;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            gap: 8px; transition: all 0.2s; font-family: inherit;
        }
        .btn-confirmar:hover { background: var(--orange2); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(234,88,12,0.3); }
        .btn-confirmar:disabled { background: #d1d5db; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ══ FLASH ══ */
        .flash-success {
            background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;
            border-radius: 10px; padding: 12px 16px; font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        }

        /* ══ FOOTER ══ */
        footer {
            background: #1c1917; color: #a8a29e;
            border-top: 1px solid #292524; flex-shrink: 0;
            margin-top: auto;
        }
        footer a { text-decoration: none; }

        /* ══ MODAL PLATO ══ */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 9000;
            background: rgba(0,0,0,0.55);
            align-items: flex-end;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }

        @media (min-width: 600px) {
            .modal-overlay { align-items: center; }
        }

        .modal-box {
            background: var(--white);
            width: 100%; max-width: 540px;
            border-radius: 20px 20px 0 0;
            max-height: 92vh;
            display: flex; flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.25s ease;
        }
        @media (min-width: 600px) {
            .modal-box { border-radius: 20px; max-height: 85vh; }
        }
        @keyframes slideUp {
            from { transform: translateY(40px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .modal-img {
            width: 100%; height: 220px; object-fit: cover; flex-shrink: 0;
            background: var(--bg);
            display: flex; align-items: center; justify-content: center;
        }
        .modal-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .modal-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f3f4f6; }
        .modal-img-placeholder i { font-size: 48px; color: #d1d5db; }

        .modal-content { padding: 22px 24px 0; flex: 1; overflow-y: auto; }
        .modal-nombre { font-size: 22px; font-weight: 900; color: var(--text); margin-bottom: 6px; line-height: 1.25; }
        .modal-desc { font-size: 14px; color: var(--muted); line-height: 1.65; margin-bottom: 14px; }
        .modal-precio { font-size: 24px; font-weight: 900; color: var(--text); margin-bottom: 20px; }

        /* cantidad dentro del modal */
        .modal-qty-row {
            display: flex; align-items: center; justify-content: center;
            gap: 16px; margin-bottom: 20px;
        }
        .modal-qty-btn {
            width: 38px; height: 38px; border-radius: 50%;
            border: 2px solid var(--border); background: var(--white);
            cursor: pointer; font-size: 20px; font-weight: 700;
            display: flex; align-items: center; justify-content: center; color: var(--text);
            transition: all 0.15s; line-height: 1;
        }
        .modal-qty-btn:hover { border-color: var(--orange); color: var(--orange); }
        .modal-qty-btn:disabled { opacity: 0.35; cursor: not-allowed; }
        .modal-qty-num { font-size: 22px; font-weight: 900; min-width: 36px; text-align: center; }

        .modal-footer {
            padding: 16px 24px 24px; flex-shrink: 0;
            border-top: 1px solid var(--border);
            background: var(--white);
        }
        .btn-agregar-modal {
            width: 100%; padding: 16px; border-radius: 14px; border: none;
            background: var(--orange); color: white; font-size: 16px; font-weight: 800;
            cursor: pointer; display: flex; align-items: center; justify-content: space-between;
            gap: 8px; transition: all 0.2s; font-family: inherit;
        }
        .btn-agregar-modal:hover { background: var(--orange2); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(234,88,12,0.3); }
        .btn-agregar-modal .bam-left { display: flex; align-items: center; gap: 10px; }
        .bam-badge {
            background: rgba(255,255,255,0.25); border-radius: 8px;
            padding: 3px 10px; font-size: 14px; font-weight: 800;
        }

        .modal-close {
            position: absolute; top: 14px; right: 14px;
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(0,0,0,0.4); border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 14px; z-index: 10; transition: background 0.18s;
        }
        .modal-close:hover { background: rgba(0,0,0,0.65); }
        .modal-img-wrap { position: relative; flex-shrink: 0; }

        @media (max-width: 600px) {
            .topbar { padding: 0 16px; }
            .rest-hero { padding: 16px 16px 0; }
            .search-wrap { padding: 0 16px 14px; }
            .cats-inner { padding: 0 16px; }
            .page-body { padding: 20px 16px 40px; }
            .plato-img-side { width: 88px; height: 72px; }
            .modal-img { height: 180px; }
        }
    </style>
</head>
<body>

{{-- ══ TOPBAR ══ --}}
<header class="topbar">
    <a href="{{ route('restaurantes.show', $restaurante) }}" class="btn-back">
        <i class="fas fa-arrow-left" style="font-size:12px;"></i>
    </a>
    <div class="topbar-brand">
        <i class="fas fa-utensils" style="color:var(--orange);font-size:12px;"></i>
        Ordenar
        <span class="sep">/</span>
        <span class="name">{{ $restaurante->nombre }}</span>
    </div>
    @auth
        <div class="topbar-right">
            <a href="{{ route('pedidos.mis') }}" class="btn-mis-pedidos">
                <i class="fas fa-bag-shopping" style="font-size:11px;"></i> Mis Pedidos
            </a>
        </div>
    @endauth
</header>

{{-- ══ HERO ══ --}}
<div class="rest-hero">
    <div class="rest-hero-inner">
        <div class="rest-logo">
            @php
                $imgUrl = $restaurante->foto_portada
                    ? asset('storage/'.$restaurante->foto_portada)
                    : ($restaurante->imagenes && $restaurante->imagenes->count() > 0
                        ? asset('storage/'.$restaurante->imagenes->first()->ruta_foto)
                        : null);
            @endphp
            @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $restaurante->nombre }}">
            @else
                <i class="fas fa-utensils" style="font-size:24px;color:#d1d5db;"></i>
            @endif
        </div>
        <div class="rest-info">
            <h1>{{ $restaurante->nombre }}</h1>
            <div class="rest-meta">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $restaurante->municipio->nombre }}, {{ $restaurante->departamento->nombre }}</span>
                @if($restaurante->especialidad)
                    <span class="rest-meta-sep">·</span>
                    <span>{{ $restaurante->especialidad }}</span>
                @endif
                @php $totalPlatillos = $platos->flatten()->count(); @endphp
                <span class="rest-meta-sep">·</span>
                <span>{{ $totalPlatillos }} platillos</span>
            </div>
        </div>
    </div>
</div>

{{-- ══ BÚSQUEDA ══ --}}
<div class="search-wrap" style="padding-top:14px;">
    <div class="search-input-wrap">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="search-platos" class="search-input" placeholder="Buscar productos...">
    </div>
</div>

{{-- ══ CATS ══ --}}
<div class="cats-bar">
    <div class="cats-inner">
        <button class="cat-pill active" onclick="filtrarCat('todas', this)">Menú</button>
        @foreach($platos->keys() as $cat)
            <button class="cat-pill" onclick="filtrarCat('{{ Str::slug($cat) }}', this)">
                {{ $cat ?: 'Sin categoría' }}
            </button>
        @endforeach
    </div>
</div>

{{-- ══ BODY ══ --}}
<div class="page-body">

    <main id="menu-col">

        @if(session('pedido_success'))
            <div class="flash-success">
                <i class="fas fa-check-circle"></i> {{ session('pedido_success') }}
            </div>
        @endif

        @foreach($platos as $categoria => $items)
        <div class="cat-section" data-cat="{{ Str::slug($categoria) }}" id="cat-{{ Str::slug($categoria) }}">
            <h2 class="cat-section-title">{{ $categoria ?: 'Sin categoría' }}</h2>
            <div class="platos-list">
                @foreach($items as $plato)
                <div class="plato-row"
                     data-nombre="{{ strtolower($plato->nombre) }}"
                     data-cat="{{ Str::slug($categoria) }}"
                     @auth
                     onclick="abrirModal({{ $plato->id }}, '{{ addslashes($plato->nombre) }}', {{ $plato->precio }}, '{{ addslashes($plato->descripcion ?? '') }}', '{{ $plato->imagen ? asset('storage/'.$plato->imagen) : '' }}')"
                     @endauth>

                    <div class="plato-text">
                        <div class="plato-nombre">{{ $plato->nombre }}</div>
                        @if($plato->descripcion)
                            <div class="plato-desc">{{ $plato->descripcion }}</div>
                        @endif
                        <div class="plato-precio">C$ {{ number_format($plato->precio, 0) }}</div>
                    </div>

                    <div class="plato-img-side">
                        @if($plato->imagen)
                            <img src="{{ asset('storage/'.$plato->imagen) }}" alt="{{ $plato->nombre }}" loading="lazy">
                        @else
                            <div class="plato-img-placeholder">
                                <i class="fas fa-utensils"></i>
                            </div>
                        @endif
                    </div>

                    @guest
                        <a href="{{ route('login') }}"
                           onclick="event.stopPropagation()"
                           style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:999px;border:1.5px solid var(--border);font-size:13px;font-weight:700;color:var(--muted);flex-shrink:0;transition:all 0.18s;"
                           onmouseover="this.style.borderColor='#ea580c';this.style.color='#ea580c';"
                           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)';">
                            <i class="fas fa-sign-in-alt" style="font-size:10px;"></i> Ingresar
                        </a>
                    @endguest

                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </main>

    {{-- CARRITO SIDEBAR --}}
    @auth
    <aside class="sidebar-col">
        <div class="carrito-card" id="carrito-card">
            <div class="carrito-head">
                <div class="carrito-head-title">
                    Mi pedido
                    <span class="carrito-count-badge" id="carrito-count">0</span>
                </div>
                <button class="btn-vaciar" onclick="vaciarCarrito()">
                    <i class="fas fa-trash" style="font-size:10px;margin-right:4px;"></i> Vaciar
                </button>
            </div>
            <div class="carrito-body">
                <div id="carrito-empty" class="carrito-empty-state">
                    <div class="empty-img">
                        <svg width="52" height="52" viewBox="0 0 52 52" fill="none">
                            <circle cx="26" cy="26" r="26" fill="#f3f4f6"/>
                            <path d="M16 18h3l2.7 13.5a2 2 0 001.97 1.5h9.66a2 2 0 001.97-1.68l1.59-8.82H18.5" stroke="#d1d5db" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            <circle cx="22" cy="36" r="1.5" fill="#d1d5db"/>
                            <circle cx="34" cy="36" r="1.5" fill="#d1d5db"/>
                        </svg>
                    </div>
                    <p>Tu pedido está vacío.<br>Agrega platillos del menú.</p>
                </div>
                <div id="carrito-items-list" class="carrito-items-list" style="display:none;"></div>
                <div id="carrito-summary" class="carrito-summary" style="display:none;">
                    <div class="total-row">
                        <span class="total-label">Total</span>
                        <span class="total-valor" id="total-valor">C$ 0</span>
                    </div>
                    <form method="POST" action="{{ route('pedidos.store', $restaurante) }}" id="form-pedido">
                        @csrf
                        <div id="hidden-items"></div>
                        <label class="notas-label">Tipo de pedido</label>
                        <div class="tipo-grid" style="margin-bottom:14px;">
                            <label class="tipo-opt">
                                <input type="radio" name="tipo" value="mesa" checked>
                                <i class="fas fa-chair" style="color:var(--orange);font-size:12px;"></i> Mesa
                            </label>
                            <label class="tipo-opt">
                                <input type="radio" name="tipo" value="para_llevar">
                                <i class="fas fa-bag-shopping" style="color:var(--orange);font-size:12px;"></i> Para llevar
                            </label>
                        </div>
                        <label class="notas-label">Notas <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span></label>
                        <textarea name="notas" rows="2" maxlength="500" class="notas-input"
                                  placeholder="Ej: Sin cebolla, alergia al maní..."></textarea>
                        <button type="submit" class="btn-confirmar">
                            <i class="fas fa-paper-plane" style="font-size:13px;"></i>
                            Confirmar pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>
    @else
    <aside class="sidebar-col">
        <div class="carrito-card">
            <div class="carrito-head">
                <div class="carrito-head-title">Mi pedido</div>
            </div>
            <div class="carrito-body">
                <div class="carrito-empty-state">
                    <i class="fas fa-lock" style="font-size:28px;color:#d1d5db;display:block;margin-bottom:12px;"></i>
                    <p style="margin-bottom:16px;">Inicia sesión para hacer tu pedido</p>
                    <a href="{{ route('login') }}"
                       style="display:inline-flex;align-items:center;gap:7px;background:var(--orange);color:white;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:700;">
                        <i class="fas fa-sign-in-alt" style="font-size:11px;"></i> Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
    </aside>
    @endauth

</div>

{{-- ══ MODAL PLATO ══ --}}
@auth
<div class="modal-overlay" id="plato-modal" onclick="cerrarModalOverlay(event)">
    <div class="modal-box">
        <div class="modal-img-wrap">
            <div class="modal-img" id="modal-img-wrap">
                <div class="modal-img-placeholder" id="modal-placeholder">
                    <i class="fas fa-utensils"></i>
                </div>
            </div>
            <button class="modal-close" onclick="cerrarModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-content">
            <div class="modal-nombre" id="modal-nombre">—</div>
            <div class="modal-desc" id="modal-desc"></div>
            <div class="modal-precio" id="modal-precio">C$ 0</div>

            <div class="modal-qty-row">
                <button class="modal-qty-btn" id="modal-qty-minus" onclick="modalCambiarQty(-1)">−</button>
                <span class="modal-qty-num" id="modal-qty-num">1</span>
                <button class="modal-qty-btn" onclick="modalCambiarQty(1)">+</button>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-agregar-modal" onclick="modalAgregar()">
                <div class="bam-left">
                    <span class="bam-badge" id="modal-badge">1</span>
                    <span>Agregar a mi pedido</span>
                </div>
                <span id="modal-total-precio">C$ 0</span>
            </button>
        </div>
    </div>
</div>
@endauth

{{-- ══ FOOTER ══ --}}
<footer>
    <div style="max-width:1200px;margin:0 auto;padding:48px 24px 32px;">
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:32px;margin-bottom:32px;">
            <div>
                <div style="margin-bottom:14px;">
                    <span style="font-family:'Playfair Display',serif;font-style:italic;font-weight:700;font-size:20px;color:#fff;">
                        Gastro<span style="color:#ea580c;">Nicaragua</span>
                    </span>
                </div>
                <p style="color:#a8a29e;font-size:14px;line-height:1.7;font-weight:300;margin-bottom:16px;">
                    La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                </p>
                <div style="display:flex;gap:10px;">
                    @foreach(['facebook-f','instagram','tiktok'] as $red)
                    <a href="#" style="width:32px;height:32px;border-radius:50%;background:#292524;display:flex;align-items:center;justify-content:center;color:#a8a29e;font-size:12px;transition:all 0.2s;"
                       onmouseover="this.style.background='#ea580c';this.style.color='#fff';"
                       onmouseout="this.style.background='#292524';this.style.color='#a8a29e';">
                        <i class="fab fa-{{ $red }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:#fff;margin-bottom:14px;">Portal</h4>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach([['home','Inicio'],['restaurantes.index','Restaurantes'],['gastrobares.index','Gastrobares'],['empleos.index','Empleos'],['contacto','Contacto']] as [$ruta, $label])
                    <a href="{{ route($ruta) }}" style="color:#a8a29e;font-size:14px;transition:color 0.2s;"
                       onmouseover="this.style.color='#f97316';" onmouseout="this.style.color='#a8a29e';">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:#fff;margin-bottom:14px;">Destinos</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    @foreach(['Masaya','Granada','León','San Juan del Sur','Estelí','Matagalpa'] as $d)
                    <span style="color:#a8a29e;font-size:13px;font-weight:300;cursor:pointer;transition:color 0.2s;"
                          onmouseover="this.style.color='#fff';" onmouseout="this.style.color='#a8a29e';">
                        <i class="fas fa-chevron-right" style="font-size:9px;color:#ea580c;margin-right:5px;"></i>{{ $d }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        <div style="border-top:1px solid #292524;padding-top:20px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:10px;">
            <p style="font-size:12px;color:#57534e;margin:0;">&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
            <div style="display:flex;gap:16px;">
                <a href="#" style="font-size:12px;color:#57534e;transition:color 0.2s;" onmouseover="this.style.color='#a8a29e';" onmouseout="this.style.color='#57534e';">Política de Privacidad</a>
                <a href="#" style="font-size:12px;color:#57534e;transition:color 0.2s;" onmouseover="this.style.color='#a8a29e';" onmouseout="this.style.color='#57534e';">Términos de Servicio</a>
            </div>
        </div>
    </div>
</footer>

<script>
// ── CARRITO ──
let carrito = {};

function agregarItem(id, nombre, precio, cantidad) {
    if (carrito[id]) carrito[id].cantidad += cantidad;
    else carrito[id] = { nombre, precio, cantidad };
    renderCarrito();
}

function cambiarCantidad(id, delta) {
    if (!carrito[id]) return;
    carrito[id].cantidad += delta;
    if (carrito[id].cantidad <= 0) delete carrito[id];
    renderCarrito();
}

function vaciarCarrito() { carrito = {}; renderCarrito(); }

function renderCarrito() {
    const ids = Object.keys(carrito);
    const emptyEl   = document.getElementById('carrito-empty');
    const itemsEl   = document.getElementById('carrito-items-list');
    const summaryEl = document.getElementById('carrito-summary');
    const countEl   = document.getElementById('carrito-count');
    const totalEl   = document.getElementById('total-valor');
    const hiddenEl  = document.getElementById('hidden-items');
    if (!emptyEl) return;

    if (ids.length === 0) {
        emptyEl.style.display   = 'block';
        itemsEl.style.display   = 'none';
        summaryEl.style.display = 'none';
        itemsEl.innerHTML = '';
        countEl.textContent = '0';
        return;
    }

    emptyEl.style.display   = 'none';
    itemsEl.style.display   = 'block';
    summaryEl.style.display = 'block';

    let total = 0, totalItems = 0, html = '', hidden = '';
    ids.forEach((id, idx) => {
        const { nombre, precio, cantidad } = carrito[id];
        const sub = precio * cantidad;
        total += sub; totalItems += cantidad;
        html += `
            <div class="carrito-item-row">
                <div style="flex:1;min-width:0;">
                    <div class="ci-name">${nombre}</div>
                    <div class="ci-unit">C$ ${precio.toLocaleString()} c/u</div>
                </div>
                <div class="qty-controls">
                    <button class="qty-btn" onclick="cambiarCantidad(${id}, -1)">−</button>
                    <span class="qty-num">${cantidad}</span>
                    <button class="qty-btn" onclick="cambiarCantidad(${id}, 1)">+</button>
                </div>
                <div class="ci-subtotal">C$ ${sub.toLocaleString()}</div>
            </div>`;
        hidden += `
            <input type="hidden" name="items[${idx}][id]" value="${id}">
            <input type="hidden" name="items[${idx}][cantidad]" value="${cantidad}">`;
    });

    itemsEl.innerHTML  = html;
    hiddenEl.innerHTML = hidden;
    countEl.textContent = totalItems;
    totalEl.textContent = `C$ ${total.toLocaleString()}`;
}

// ── MODAL ──
let modalData = { id: null, nombre: '', precio: 0, qty: 1 };

function abrirModal(id, nombre, precio, desc, imgUrl) {
    modalData = { id, nombre, precio, qty: 1 };

    document.getElementById('modal-nombre').textContent = nombre;
    document.getElementById('modal-desc').textContent   = desc;
    document.getElementById('modal-precio').textContent = `C$ ${precio.toLocaleString()}`;

    const wrap = document.getElementById('modal-img-wrap');
    if (imgUrl) {
        wrap.innerHTML = `<img src="${imgUrl}" alt="${nombre}" style="width:100%;height:100%;object-fit:cover;display:block;">`;
    } else {
        wrap.innerHTML = `<div class="modal-img-placeholder"><i class="fas fa-utensils"></i></div>`;
    }

    actualizarModalQty();
    document.getElementById('plato-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('plato-modal').classList.remove('open');
    document.body.style.overflow = '';
}

function cerrarModalOverlay(e) {
    if (e.target === document.getElementById('plato-modal')) cerrarModal();
}

function modalCambiarQty(delta) {
    modalData.qty = Math.max(1, modalData.qty + delta);
    actualizarModalQty();
}

function actualizarModalQty() {
    const { precio, qty } = modalData;
    document.getElementById('modal-qty-num').textContent   = qty;
    document.getElementById('modal-badge').textContent     = qty;
    document.getElementById('modal-total-precio').textContent = `C$ ${(precio * qty).toLocaleString()}`;
    document.getElementById('modal-qty-minus').disabled   = qty <= 1;
}

function modalAgregar() {
    const { id, nombre, precio, qty } = modalData;
    agregarItem(id, nombre, precio, qty);
    cerrarModal();
}

// ESC para cerrar
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });

// ── FILTRO CATEGORÍAS ──
function filtrarCat(cat, btn) {
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    if (btn) btn.classList.add('active');
    document.querySelectorAll('.cat-section').forEach(sec => {
        sec.style.display = (cat === 'todas' || sec.dataset.cat === cat) ? 'block' : 'none';
    });
    if (cat !== 'todas') {
        const el = document.getElementById(`cat-${cat}`);
        if (el) {
            const offset = 56 + 45 + 16;
            window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
        }
    }
}

// ── BÚSQUEDA ──
document.getElementById('search-platos')?.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.plato-row').forEach(row => {
        row.style.display = (!q || row.dataset.nombre.includes(q)) ? 'flex' : 'none';
    });
    document.querySelectorAll('.cat-section').forEach(sec => {
        const visible = Array.from(sec.querySelectorAll('.plato-row')).some(r => r.style.display !== 'none');
        sec.style.display = visible ? 'block' : 'none';
    });
});

renderCarrito();
</script>

</body>
</html>
