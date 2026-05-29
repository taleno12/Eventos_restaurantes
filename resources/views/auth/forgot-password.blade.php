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
        .min-h-screen > div:first-child:not(.fp-root) { display: none !important; }
        nav, .min-h-screen > div:first-child svg { display: none !important; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .fp-root {
            font-family: 'DM Sans', sans-serif;
            display: flex;
            width: 100vw;
            min-height: 100vh;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #0D0D0D;
        }

        /* ── LEFT PANEL ── */
        .panel-left {
            display: none;
            width: 42%;
            background: #FF5500;
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

        .lp-logo { position: relative; display: flex; align-items: center; gap: 10px; animation: slideInLeft .6s cubic-bezier(.22,1,.36,1) .1s both; }
        .lb { width: 38px; height: 38px; background: #000; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .lb span { color: #FF5500; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.2rem; line-height: 1; }
        .ln { font-family: 'Syne', sans-serif; font-weight: 700; color: #000; font-size: 0.82rem; letter-spacing: 0.04em; }

        .lp-hero { position: relative; }
        .badge {
            display: inline-block;
            background: #000; color: #FF5500;
            font-size: 0.6rem; font-weight: 600;
            letter-spacing: 0.18em; text-transform: uppercase;
            padding: 0.3rem 0.7rem; border-radius: 999px;
            margin-bottom: 1.25rem;
            animation: badgePop .6s cubic-bezier(.22,1,.36,1) .3s both;
        }
        .ht {
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: clamp(2rem, 3.5vw, 3rem);
            color: #000; line-height: 1.05; letter-spacing: -0.02em;
        }
        .ht em { color: #fff; font-style: normal; }
        .hs { margin-top: 1rem; color: rgba(0,0,0,0.5); font-size: 0.875rem; line-height: 1.6; max-width: 320px; animation: fadeInUp .7s cubic-bezier(.22,1,.36,1) 1s both; }

        .stats { position: relative; display: flex; gap: 0.6rem; flex-wrap: wrap; animation: statsSlide .7s cubic-bezier(.22,1,.36,1) 1.15s both; }
        .sc { background: rgba(0,0,0,0.07); border: 1px solid rgba(0,0,0,0.1); border-radius: 12px; padding: 0.65rem 1rem; }
        .sc .n { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.2rem; color: #000; }
        .sc .l { font-size: 0.6rem; color: rgba(0,0,0,0.45); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px; }

        .ht-wrap { overflow: hidden; display: block; }
        .ht-line { display: block; animation: wordReveal .75s cubic-bezier(.22,1,.36,1) both; }
        .ht-line:nth-child(1) { animation-delay: .45s; }
        .ht-line:nth-child(2) { animation-delay: .62s; }
        .ht-line:nth-child(3) { animation-delay: .79s; }

        /* ── RIGHT PANEL ── */
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
        .lb-m { width: 38px; height: 38px; background: #FF5500; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .lb-m span { color: #000; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.2rem; }
        .ln-m { font-family: 'Syne', sans-serif; font-weight: 700; color: #fff; font-size: 0.82rem; letter-spacing: 0.04em; }

        /* icon circle */
        .icon-circle {
            width: 56px; height: 56px;
            background: rgba(255,85,0,0.12);
            border: 1px solid rgba(255,85,0,0.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
        }
        .icon-circle svg { width: 26px; height: 26px; stroke: #FF5500; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

        .fh { margin-bottom: 1.75rem; }
        .fh h1 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 2rem; color: #fff; letter-spacing: -0.02em; }
        .fh p { margin-top: 0.5rem; color: #555; font-size: 0.85rem; line-height: 1.6; }

        .session-ok {
            background: rgba(34,180,100,0.08); border: 1px solid rgba(34,180,100,0.2);
            border-left: 3px solid #22B464; border-radius: 10px;
            color: #22B464; font-size: 0.8rem; padding: 0.8rem 1rem; margin-bottom: 1.25rem;
        }
        .alert-error {
            background: rgba(255,85,0,0.08); border: 1px solid rgba(255,85,0,0.2);
            border-left: 3px solid #FF5500; border-radius: 10px;
            color: #FF7733; font-size: 0.8rem; padding: 0.8rem 1rem; margin-bottom: 1.25rem;
        }

        .field { margin-bottom: 1.25rem; }
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
        .field input:focus { border-color: #FF5500; box-shadow: 0 0 0 3px rgba(255,85,0,0.14); }
        .field-error { font-size: 0.73rem; color: #FF5500; margin-top: 0.35rem; }

        .btn-main {
            width: 100%; background: #FF5500; color: #000;
            border: none; border-radius: 11px; padding: 0.95rem;
            font-family: 'Syne', sans-serif; font-weight: 700;
            font-size: 0.8rem; letter-spacing: 0.1em; text-transform: uppercase;
            cursor: pointer; transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .btn-main:hover { background: #FF6A1A; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(255,85,0,0.3); }
        .btn-main:active { transform: translateY(0); }

        .back-link {
            display: block; text-align: center; margin-top: 1.75rem;
            font-size: 0.78rem; color: #FF5500; text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #FF7733; }

        .footer-link {
            display: block; text-align: center; margin-top: 0.75rem;
            font-size: 0.7rem; color: #3A3A3A; letter-spacing: 0.08em;
            text-transform: uppercase; text-decoration: none; transition: color 0.2s;
        }
        .footer-link:hover { color: #FF5500; }

        @keyframes slideInLeft { from{opacity:0;transform:translateX(-40px)} to{opacity:1;transform:translateX(0)} }
        @keyframes fadeInUp    { from{opacity:0;transform:translateY(20px)}  to{opacity:1;transform:translateY(0)} }
        @keyframes wordReveal  { 0%{opacity:0;transform:translateY(100%)} 100%{opacity:1;transform:translateY(0)} }
        @keyframes badgePop    { 0%{opacity:0;transform:scale(.7)} 70%{transform:scale(1.08)} 100%{opacity:1;transform:scale(1)} }
        @keyframes statsSlide  { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
        @keyframes floatG      { 0%,100%{transform:translateY(0) rotate(-3deg)} 50%{transform:translateY(-18px) rotate(-1deg)} }
        @keyframes fadeUp      { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

        .fu{animation:fadeUp .55s cubic-bezier(.22,1,.36,1) both}
        .d1{animation-delay:.05s} .d2{animation-delay:.12s} .d3{animation-delay:.19s}
        .d4{animation-delay:.26s} .d5{animation-delay:.33s} .d6{animation-delay:.4s}
    </style>

    <div class="fp-root">

        {{-- ══ PANEL IZQUIERDO ══ --}}
        <div class="panel-left">
            <div class="lp-logo">
                <div class="lb"><span>G</span></div>
                <span class="ln">GastroNicaragua</span>
            </div>

            <div class="lp-hero">
                <div class="badge">🔐 Recuperar acceso</div>
                <div class="ht">
                    <span class="ht-wrap"><span class="ht-line">Recupera</span></span>
                    <span class="ht-wrap"><span class="ht-line">tu <em>acceso</em></span></span>
                    <span class="ht-wrap"><span class="ht-line">fácilmente.</span></span>
                </div>
                <p class="hs">
                    Te enviaremos un enlace seguro a tu correo para que puedas
                    restablecer tu contraseña en pocos pasos.
                </p>
            </div>

            <div class="stats">
                <div class="sc"><div class="n">200+</div><div class="l">Restaurantes</div></div>
                <div class="sc"><div class="n">500+</div><div class="l">Platillos</div></div>
                <div class="sc"><div class="n">18</div><div class="l">Deptos</div></div>
            </div>
        </div>

        {{-- ══ PANEL DERECHO ══ --}}
        <div class="panel-right">
            <div class="right-inner">

                <div class="mobile-logo fu d1">
                    <div class="lb-m"><span>G</span></div>
                    <span class="ln-m">GastroNicaragua</span>
                </div>

                <div class="icon-circle fu d1">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>

                <div class="fh fu d2">
                    <h1>¿Olvidaste tu contraseña?</h1>
                    <p>Ingresa tu correo y te enviaremos un enlace para restablecerla.</p>
                </div>

                @if (session('status'))
                    <div class="session-ok fu d3">
                        ✓ {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-error fu d3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="field fu d3">
                        <label for="email">Correo electrónico</label>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="juan@correo.com"
                               required autofocus autocomplete="username"/>
                        @error('email') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="fu d4">
                        <button type="submit" class="btn-main">Enviar enlace de recuperación</button>
                    </div>
                </form>

                <a href="{{ route('login') }}" class="back-link fu d5">← Volver al inicio de sesión</a>
                <a href="/" class="footer-link fu d6">← Volver a la galería</a>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.cssText = 'margin:0;padding:0;background:#0D0D0D;overflow:hidden;';
            const root = document.querySelector('.fp-root');
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