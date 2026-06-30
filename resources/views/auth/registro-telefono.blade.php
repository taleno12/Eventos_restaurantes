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
        .min-h-screen > div:first-child:not(.reg-root) { display: none !important; }
        nav, .min-h-screen > div:first-child svg { display: none !important; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .reg-root {
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
            justify-content: flex-start;
            padding: 3.5rem 1.5rem 2.5rem;
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
        .field input, .field select {
            width: 100%; background: #181818; border: 1px solid #262626;
            border-radius: 11px; padding: 0.85rem 1rem;
            color: #fff; font-size: 0.875rem; font-family: 'DM Sans', sans-serif;
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field input::placeholder { color: #383838; }
        .field input:focus, .field select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.14); }
        .field-error { font-size: 0.73rem; color: #2563eb; margin-top: 0.35rem; }

        .pass-strength { display: flex; gap: 4px; margin-top: 0.4rem; }
        .ps-bar { flex: 1; height: 3px; border-radius: 2px; background: #222; transition: background 0.3s; }
        .strength-label { font-size: 0.65rem; color: #555; margin-top: 0.3rem; }

        .btn-main {
            width: 100%; background: #2563eb; color: #000;
            border: none; border-radius: 11px; padding: 0.95rem;
            font-family: 'Syne', sans-serif; font-weight: 700;
            font-size: 0.8rem; letter-spacing: 0.1em; text-transform: uppercase;
            cursor: pointer; transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .btn-main:hover { background: #3b82f6; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,0.3); }
        .btn-main:active { transform: translateY(0); }

        .divider { display: flex; align-items: center; gap: 0.85rem; margin: 1.25rem 0 1rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #222; }
        .divider span { font-size: 0.6rem; color: #444; letter-spacing: 0.1em; text-transform: uppercase; white-space: nowrap; }

        .btn-google {
            width: 100%; background: #181818; border: 1px solid #262626;
            border-radius: 11px; padding: 0.85rem;
            color: #ccc; font-family: 'DM Sans', sans-serif; font-weight: 500;
            font-size: 0.85rem; display: flex; align-items: center; justify-content: center;
            gap: 0.6rem; text-decoration: none;
            transition: background 0.2s, border-color 0.2s, transform 0.15s;
        }
        .btn-google:hover { background: #1F1F1F; border-color: #333; transform: translateY(-1px); }

        .btn-login {
            display: block; width: 100%; margin-top: 0.85rem;
            background: transparent; border: 1px solid #262626;
            border-radius: 11px; padding: 0.85rem;
            color: #777; font-family: 'DM Sans', sans-serif; font-weight: 500;
            font-size: 0.85rem; text-align: center; text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-login:hover { border-color: #2563eb; color: #2563eb; }

        .back-link {
            display: block; text-align: center; margin-top: 1.75rem;
            font-size: 0.7rem; color: #3A3A3A; letter-spacing: 0.08em;
            text-transform: uppercase; text-decoration: none; transition: color 0.2s;
        }
        .back-link:hover { color: #2563eb; }

        @keyframes slideInLeft { from { opacity:0; transform: translateX(-40px); } to { opacity:1; transform: translateX(0); } }
        @keyframes fadeInUp { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }
        @keyframes wordReveal { 0% { opacity:0; transform: translateY(100%); } 100% { opacity:1; transform: translateY(0); } }
        @keyframes badgePop { 0% { opacity:0; transform: scale(0.7); } 70% { transform: scale(1.08); } 100% { opacity:1; transform: scale(1); } }
        @keyframes floatG { 0%, 100% { transform: translateY(0) rotate(-3deg); } 50% { transform: translateY(-18px) rotate(-1deg); } }
        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

        .fu { animation: fadeUp 0.55s cubic-bezier(0.22,1,0.36,1) both; }
        .d1{animation-delay:.05s} .d2{animation-delay:.12s} .d3{animation-delay:.19s}
        .d4{animation-delay:.26s} .d5{animation-delay:.33s} .d6{animation-delay:.4s}
        .d7{animation-delay:.47s} .d8{animation-delay:.54s} .d9{animation-delay:.61s}
    </style>

    <div class="reg-root">

        {{-- ══ PANEL IZQUIERDO ══ --}}
        <div class="panel-left">
            <div class="lp-logo">
                <div class="lb"><span>G</span></div>
                <span class="ln">GastroNicaragua</span>
            </div>

            <div class="lp-hero">
                <div class="badge">🍽 Únete a la comunidad</div>
                <div class="ht" aria-label="Crea tu cuenta y explora">
                    <span class="ht-wrap"><span class="ht-line">Crea tu</span></span>
                    <span class="ht-wrap"><span class="ht-line"><em>cuenta</em> y</span></span>
                    <span class="ht-wrap"><span class="ht-line">explora.</span></span>
                </div>
                <p class="hs">
                    Regístrate con tu número de teléfono y accede a los mejores
                    restaurantes, platillos y experiencias culinarias de Nicaragua.
                </p>
            </div>

            <div></div>
        </div>

        {{-- ══ PANEL DERECHO ══ --}}
        <div class="panel-right">
            <div class="right-inner">

                <div class="mobile-logo fu d1">
                    <div class="lb-m"><span>G</span></div>
                    <span class="ln-m">GastroNicaragua</span>
                </div>

                <div class="fh fu d1">
                    <h1>Crear cuenta</h1>
                    <p>Regístrate con tu número de teléfono.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-error fu d2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.telefono.store') }}">
                    @csrf

                    {{-- Nombre --}}
                    <div class="field fu d3">
                        <label for="name">Nombre completo</label>
                        <input id="name" type="text" name="name"
                               value="{{ old('name') }}"
                               placeholder="Juan Pérez"
                               required autofocus autocomplete="name"/>
                        @error('name') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="field fu d4">
                        <label for="telefono">Número de teléfono</label>
                        <input id="telefono" type="tel" name="telefono"
                               value="{{ old('telefono') }}"
                               placeholder="8888 8888"
                               required autocomplete="tel"/>
                        @error('telefono') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div class="field fu d5">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password"
                               placeholder="••••••••••••"
                               required autocomplete="new-password"
                               oninput="checkStrength(this.value)"/>
                        <div class="pass-strength">
                            <div class="ps-bar" id="b1"></div>
                            <div class="ps-bar" id="b2"></div>
                            <div class="ps-bar" id="b3"></div>
                            <div class="ps-bar" id="b4"></div>
                        </div>
                        <div class="strength-label" id="slabel">Mínimo 8 caracteres</div>
                        @error('password') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="field fu d6">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input id="password_confirmation" type="password"
                               name="password_confirmation"
                               placeholder="••••••••••••"
                               required autocomplete="new-password"/>
                        @error('password_confirmation') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Pregunta de seguridad --}}
                    <div class="field fu d6">
                        <label for="pregunta_seguridad">Pregunta de seguridad</label>
                        <select id="pregunta_seguridad" name="pregunta_seguridad" required>
                            <option value="" disabled {{ old('pregunta_seguridad') ? '' : 'selected' }}>Elige una pregunta</option>
                            <option value="¿Nombre de tu primera mascota?" {{ old('pregunta_seguridad') == '¿Nombre de tu primera mascota?' ? 'selected' : '' }}>¿Nombre de tu primera mascota?</option>
                            <option value="¿En qué municipio naciste?" {{ old('pregunta_seguridad') == '¿En qué municipio naciste?' ? 'selected' : '' }}>¿En qué municipio naciste?</option>
                            <option value="¿Nombre de tu mejor amigo/a de la infancia?" {{ old('pregunta_seguridad') == '¿Nombre de tu mejor amigo/a de la infancia?' ? 'selected' : '' }}>¿Nombre de tu mejor amigo/a de la infancia?</option>
                            <option value="¿Cuál es tu comida favorita?" {{ old('pregunta_seguridad') == '¿Cuál es tu comida favorita?' ? 'selected' : '' }}>¿Cuál es tu comida favorita?</option>
                            <option value="¿Nombre de tu primera escuela?" {{ old('pregunta_seguridad') == '¿Nombre de tu primera escuela?' ? 'selected' : '' }}>¿Nombre de tu primera escuela?</option>
                        </select>
                        @error('pregunta_seguridad') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Respuesta de seguridad --}}
                    <div class="field fu d6">
                        <label for="respuesta_seguridad">Respuesta</label>
                        <input id="respuesta_seguridad" type="text" name="respuesta_seguridad"
                               value="{{ old('respuesta_seguridad') }}"
                               placeholder="Tu respuesta"
                               required/>
                        <div class="field-error" style="color:#666; margin-top:0.35rem;">La usarás para recuperar tu cuenta si olvidas la contraseña.</div>
                        @error('respuesta_seguridad') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="fu d7">
                        <button type="submit" class="btn-main">Crear cuenta</button>
                    </div>
                </form>

                <div class="divider fu d7"><span>o regístrate con</span></div>

                <a href="{{ route('auth.google') }}" class="btn-google fu d7">
                    <svg width="17" height="17" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continuar con Google
                </a>

                <a href="{{ route('login.telefono') }}" class="btn-login fu d8">¿Ya tienes cuenta? Iniciar sesión</a>

                <a href="/" class="back-link fu d9">← Volver a la galería</a>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.cssText = 'margin:0;padding:0;background:#0D0D0D;overflow:hidden;';
            const root = document.querySelector('.reg-root');
            if (root) {
                let parent = root.parentElement;
                while (parent && parent !== document.body) {
                    parent.style.cssText = 'display:contents;';
                    parent = parent.parentElement;
                }
            }
        });

        function checkStrength(v) {
            const bars = [
                document.getElementById('b1'),
                document.getElementById('b2'),
                document.getElementById('b3'),
                document.getElementById('b4')
            ];
            const label = document.getElementById('slabel');
            let score = 0;
            if (v.length >= 8) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;
            const colors = ['#2563eb', '#2563eb', '#3b82f6', '#22CC66'];
            const labels = ['Muy débil', 'Débil', 'Aceptable', 'Segura'];
            bars.forEach((b, i) => {
                b.style.background = i < score ? colors[score - 1] : '#222';
            });
            if (v.length === 0) {
                label.textContent = 'Mínimo 8 caracteres';
                label.style.color = '#555';
            } else {
                label.textContent = labels[score - 1];
                label.style.color = colors[score - 1];
            }
        }
    </script>
</x-guest-layout>
