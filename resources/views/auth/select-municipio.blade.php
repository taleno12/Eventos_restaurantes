<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>¿De qué municipio eres? — GastroNicaragua</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700,800|playfair-display:700,700i" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: #0f0f0f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -40%; left: -20%;
            width: 80vw; height: 80vw;
            background: radial-gradient(circle, rgba(232,93,4,0.12) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -30%; right: -20%;
            width: 60vw; height: 60vw;
            background: radial-gradient(circle, rgba(232,93,4,0.07) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }

        .wrapper {
            position: relative; z-index: 1;
            width: 100%; max-width: 560px;
            display: flex; flex-direction: column;
            align-items: center; gap: 28px;
        }

        .logo-link {
            display: flex; flex-direction: column;
            align-items: center; gap: 12px;
            text-decoration: none;
            animation: fadeDown 0.8s cubic-bezier(0.23,1,0.32,1) both;
        }
        .logo-box {
            width: 56px; height: 56px;
            background: #e85d04; border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            transform: rotate(3deg);
            box-shadow: 0 20px 40px rgba(232,93,4,0.3);
            transition: transform 0.4s ease;
        }
        .logo-link:hover .logo-box { transform: rotate(0deg); }
        .logo-box i { color: white; font-size: 22px; }
        .logo-text {
            font-size: 11px; font-weight: 800;
            letter-spacing: 0.4em; text-transform: uppercase; color: #555;
        }

        /* Paso */
        .step-indicator {
            display: flex; align-items: center; gap: 8px;
            animation: fadeDown 0.8s cubic-bezier(0.23,1,0.32,1) 0.05s both;
        }
        .step { width: 32px; height: 4px; border-radius: 2px; }
        .step.done { background: #e85d04; }
        .step.active { background: #e85d04; opacity: 0.5; }
        .step.pending { background: #2a2a2a; }
        .step-label { font-size: 10px; font-weight: 700; color: #444; letter-spacing: 0.15em; text-transform: uppercase; margin-left: 4px; }

        .card {
            width: 100%;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 32px;
            padding: 40px;
            animation: fadeUp 0.9s cubic-bezier(0.23,1,0.32,1) 0.1s both;
        }

        .card-header { text-align: center; margin-bottom: 32px; }
        .icon-wrap {
            width: 56px; height: 56px;
            background: rgba(232,93,4,0.1);
            border: 1px solid rgba(232,93,4,0.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .icon-wrap i { color: #e85d04; font-size: 22px; }
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-style: italic;
            color: #fff; margin-bottom: 8px;
        }
        .card-subtitle {
            font-size: 11px; font-weight: 800;
            letter-spacing: 0.25em; text-transform: uppercase;
            color: #555; margin-bottom: 8px;
        }
        .card-subtitle span { color: #e85d04; }
        .card-desc { font-size: 13px; color: #555; line-height: 1.6; }

        /* Grid municipios */
        .mun-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 24px;
            max-height: 320px;
            overflow-y: auto;
            padding-right: 4px;
        }
        .mun-grid::-webkit-scrollbar { width: 4px; }
        .mun-grid::-webkit-scrollbar-track { background: #111; border-radius: 2px; }
        .mun-grid::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }

        @media (max-width: 480px) {
            .mun-grid { grid-template-columns: repeat(2, 1fr); }
            .card { padding: 24px; }
        }

        .mun-card {
            cursor: pointer;
            border: 1.5px solid #2a2a2a;
            border-radius: 14px;
            padding: 14px 10px;
            text-align: center;
            background: #111;
            font-size: 12px; font-weight: 700;
            color: #888;
            position: relative;
            transition: all 0.2s cubic-bezier(0.165,0.84,0.44,1);
            user-select: none;
        }
        .mun-card i {
            display: block; font-size: 14px;
            margin-bottom: 6px; color: #444;
            transition: color 0.2s;
        }
        .mun-card:hover {
            border-color: #e85d04;
            background: rgba(232,93,4,0.08);
            color: #e85d04;
            transform: translateY(-2px);
        }
        .mun-card:hover i { color: #e85d04; }
        .mun-card.selected {
            border-color: #e85d04;
            background: #e85d04;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(232,93,4,0.3);
        }
        .mun-card.selected i { color: #fff; }
        .check-badge {
            display: none;
            position: absolute; top: -7px; right: -7px;
            width: 20px; height: 20px;
            background: #fff; border: 2px solid #e85d04;
            border-radius: 50%;
            align-items: center; justify-content: center;
            font-size: 9px; color: #e85d04; font-weight: 900;
        }
        .mun-card.selected .check-badge { display: flex; }

        .btn-submit {
            width: 100%; background: #e85d04; color: #fff;
            border: none; border-radius: 14px; padding: 16px;
            font-size: 11px; font-weight: 800;
            letter-spacing: 0.2em; text-transform: uppercase;
            cursor: pointer; transition: all 0.3s ease;
            opacity: 0.35; pointer-events: none;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit.active { opacity: 1; pointer-events: all; }
        .btn-submit.active:hover {
            background: #c44d00;
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(232,93,4,0.35);
        }

        .skip-wrap {
            text-align: center;
            animation: fadeUp 1s cubic-bezier(0.23,1,0.32,1) 0.3s both;
        }
        .skip-link {
            font-size: 11px; font-weight: 600;
            color: #444; text-decoration: none;
            letter-spacing: 0.1em;
            transition: color 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .skip-link:hover { color: #888; }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Logo --}}
        <a href="/" class="logo-link">
            <div class="logo-box">
                <i class="fas fa-utensils"></i>
            </div>
            <span class="logo-text">Gastro Nicaragua</span>
        </a>

        {{-- Indicador de paso --}}
        <div class="step-indicator">
            <div class="step done"></div>
            <div class="step active"></div>
            <span class="step-label">Paso 2 de 2</span>
        </div>

        {{-- Card --}}
        <div class="card">

            <div class="card-header">
                <div class="icon-wrap">
                    <i class="fas fa-city"></i>
                </div>
                <h1 class="card-title">¿De qué municipio?</h1>
                <p class="card-subtitle">
                    Casi listo, <span>{{ auth()->user()->name }}</span>
                </p>
                <p class="card-desc">
                    Elige tu municipio para ver eventos y restaurantes<br>aún más cerca de ti.
                </p>
            </div>

            <form method="POST" action="{{ route('usuario.municipio.save') }}">
                @csrf
                <input type="hidden" name="municipio_id" id="municipioInput" value="">

                <div class="mun-grid">
                    @foreach($municipios as $mun)
                        <div class="mun-card" data-id="{{ $mun->id }}" onclick="selectMun({{ $mun->id }}, this)">
                            <span class="check-badge">✓</span>
                            <i class="fas fa-city"></i>
                            {{ $mun->nombre }}
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                    Explorar GastroNicaragua
                </button>
            </form>
        </div>

        {{-- Saltar --}}
        <div class="skip-wrap">
            <a href="{{ route('usuario.departamento.skip') }}" class="skip-link">
                <i class="fas fa-chevron-right" style="font-size:9px;"></i>
                Saltar — ver todo el contenido
            </a>
        </div>

    </div>

    <script>
    function selectMun(id, el) {
        document.querySelectorAll('.mun-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('municipioInput').value = id;
        document.getElementById('btnSubmit').classList.add('active');
    }
    </script>
</body>
</html>
