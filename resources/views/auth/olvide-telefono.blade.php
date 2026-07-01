<x-guest-layout>
    <style>
        @import url('https://fonts.bunny.net/css?family=syne:700,800|dm-sans:400,500,600');

        body { background: #0D0D0D !important; margin: 0 !important; padding: 0 !important; }
        body > div:first-child,
        .min-h-screen {
            display: block !important;
            align-items: unset !important;
            justify-content: unset !important;
            padding: 0 !important;
            background: transparent !important;
            min-height: unset !important;
        }
        .min-h-screen > div:first-child:not(.ot-root) { display: none !important; }
        nav, .min-h-screen > div:first-child svg { display: none !important; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .ot-root {
            font-family: 'DM Sans', sans-serif;
            display: flex;
            width: 100vw;
            min-height: 100vh;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #0D0D0D;
        }

        .panel-left {
            display: none;
            width: 42%;
            background: #2563eb;
            position: relative;
            overflow: hidden;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem;
            flex-shrink: 0;
        }
        @media (min-width: 860px) { .panel-left { display: flex; } }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(0,0,0,0.07) 1px, transparent 1px);
            background-size: 22px 22px;
            pointer-events: none;
        }

        .panel-left::after {
            content: 'G';
            position: absolute;
            bottom: -2rem;
            right: -1.5rem;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 18rem;
            color: rgba(0,0,0,0.05);
            line-height: 1;
            pointer-events: none;
            user-select: none;
            animation: floatG 6s ease-in-out infinite;
            transform-origin: center bottom;
        }

        .lp-logo { position: relative; display: flex; align-items: center; gap: 10px; animation: slideInLeft 0.6s cubic-bezier(0.22,1,0.36,1) 0.1s both; }
        .lb { width: 38px; height: 38px; background: #000; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .lb span { color: #2563eb; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.2rem; line-height: 1; }
        .ln { font-family: 'Syne', sans-serif; font-weight: 700; color: #000; font-size: 0.82rem; letter-spacing: 0.04em; }

        .lp-hero { position: relative; }
        .badge {
            display: inline-block;
            background: #000; color: #2563eb;
            font-size: 0.6rem; font-weight: 600;
            letter-spacing: 0.18em; text-transform: uppercase;
            padding: 0.3rem 0.7rem; border-radius: 999px;
            margin-bottom: 1.25rem;
            animation: badgePop 0.6s cubic-bezier(0.22,1,0.36,1) 0.3s both;
        }
        .ht {
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: clamp(2rem, 3.5vw, 3rem);
            color: #000; line-height: 1.05; letter-spacing: -0.02em;
        }
        .ht em { color: #fff; font-style: normal; }
        .ht-wrap { overflow: hidden; display: block; }
        .ht-line { display: block; animation: wordReveal 0.75s cubic-bezier(0.22,1,0.36,1) both; }
        .ht-line:nth-child(1) { animation-delay: 0.45s; }
        .ht-line:nth-child(2) { animation-delay: 0.62s; }
        .ht-line:nth-child(3) { animation-delay: 0.79s; }
        .hs { margin-top: 1rem; color: rgba(0,0,0,0.5); font-size: 0.875rem; line-height: 1.6; max-width: 320px; animation: fadeInUp 0.7s cubic-bezier(0.22,1,0.36,1) 1s both; }

        .panel-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
            background: #0D0D0D;
            overflow-y: auto;
        }

        .right-inner { width: 100%; max-width: 400px; }

        .mobile-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; justify-content: center; }
        @media (min-width: 860px) { .mobile-logo { display: none; } }
        .lb-m { width: 38px; height: 38px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .lb-m span { color: #000; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.2rem; }
        .ln-m { font-family: 'Syne', sans-serif; font-weight: 700; color: #fff; font-size: 0.82rem; letter-spacing: 0.04em; }

        .fh { margin-bottom: 1.75rem; }
        .fh h1 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 2rem; color: #fff; letter-spacing: -0.02em; }
        .fh p { margin-top: 0.4rem; color: #666; font-size: 0.85rem; }

        .alert-error {
            background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.2);
            border-left: 3px solid #2563eb; border-radius: 10px;
            color: #60a5fa; font-size: 0.8rem; padding: 0.8rem 1rem; margin-bottom: 1.25rem;
        }

        .field { margin-bottom: 1.1rem; }
        .field label {
            display: block; font-size: 0.65rem; font-weight: 600;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: #555; margin-bottom: 0.45rem;
        }
        .field input {
            width: 100%; background: #181818; border: 1px solid #262626;
            border-radius: 11px; padding: 0.85rem 1rem;
            color: #fff; font-size: 0.875rem; font-family: 'DM Sans', sans-serif;
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field input::placeholder { color: #383838; }
        .field input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.14); }

        .btn-main {
            width: 100%; background: #2563eb; color: #000;
            border: none; border-radius: 11px; padding: 0.95rem;
            font-family: 'Syne', sans-serif; font-weight: 700;
            font-size: 0.8rem; letter-spacing: 0.1em; text-transform: uppercase;
            cursor: pointer; transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .btn-main:hover { background: #3b82f6; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,0.3); }
        .btn-main:active { transform: translateY(0); }

        .back-link {
            display: block; text-align: center; margin-top: 1.75rem;
            font-size: 0.7rem; color: #3A3A3A; letter-spacing: 0.08em;
            text-transform: uppercase; text-decoration: none; transition: color 0.2s;
        }
        .back-link:hover { color: #2563eb; }

        .info-text { color: #666; font-size: 0.85rem; line-height: 1.6; text-align: center; margin-bottom: 1.5rem; }

        @keyframes slideInLeft { from { opacity:0; transform: translateX(-40px); } to { opacity:1; transform: translateX(0); } }
        @keyframes fadeInUp { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }
        @keyframes wordReveal { 0% { opacity:0; transform: translateY(100%); } 100% { opacity:1; transform: translateY(0); } }
        @keyframes badgePop { 0% { opacity:0; transform: scale(0.7); } 70% { transform: scale(1.08); } 100% { opacity:1; transform: scale(1); } }
        @keyframes floatG { 0%, 100% { transform: translateY(0) rotate(-3deg); } 50% { transform: translateY(-18px) rotate(-1deg); } }
        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

        .fu { animation: fadeUp 0.55s cubic-bezier(0.22,1,0.36,1) both; }
        .d1{animation-delay:.05s} .d2{animation-delay:.12s} .d3{animation-delay:.19s}
        .d4{animation-delay:.26s} .d5{animation-delay:.33s} .d6{animation-delay:.4s}
    </style>

    <div class="ot-root">

        {{-- ══ PANEL IZQUIERDO ══ --}}
        <div class="panel-left">
            <div class="lp-logo">
                <div class="lb"><span>G</span></div>
                <span class="ln">GastroNicaragua</span>
            </div>

            <div class="lp-hero">
                <div class="badge">🍽 Gastronomía · Nicaragua</div>
                <div class="ht" aria-label="Recupera tu cuenta">
                    <span class="ht-wrap"><span class="ht-line">Recupera</span></span>
                    <span class="ht-wrap"><span class="ht-line">tu <em>cuenta</em></span></span>
                    <span class="ht-wrap"><span class="ht-line">fácilmente.</span></span>
                </div>
                <p class="hs">
                    Ingresa tu número de teléfono y te ayudamos a recuperar
                    el acceso a tu cuenta de GastroNicaragua.
                </p>
            </div>
        </div>

        {{-- ══ PANEL DERECHO ══ --}}
        <div class="panel-right">
            <div class="right-inner">

                <div class="mobile-logo fu d1">
                    <div class="lb-m"><span>G</span></div>
                    <span class="ln-m">GastroNicaragua</span>
                </div>

                <div class="fh fu d1">
                    <h1>¿Olvidaste tu contraseña?</h1>
                    <p>Ingresa tu número de teléfono para continuar.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-error fu d2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <p class="info-text fu d2">
                    Te haremos tu pregunta de seguridad para verificar que eres tú.
                </p>

                <form method="POST" action="{{ route('password.buscar') }}">
                    @csrf

                    <div class="field fu d3">
                        <label for="telefono">Número de teléfono</label>
                        <input id="telefono" type="tel" name="telefono"
                               value="{{ old('telefono') }}"
                               required autofocus autocomplete="tel"/>
                    </div>

                    <div class="fu d4">
                        <button type="submit" class="btn-main">Continuar</button>
                    </div>
                </form>

                <a href="{{ route('login.telefono') }}" class="back-link fu d5">← Volver a iniciar sesión</a>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.cssText = 'margin:0;padding:0;background:#0D0D0D;overflow:hidden;';
            const root = document.querySelector('.ot-root');
            if (root) {
                let parent = root.parentElement;
                while (parent && parent !== document.body) {
                    parent.style.cssText = 'display:contents;';
                    parent = parent.parentElement;
                }
            }
        });
    </script>
</x-guest-layout>
