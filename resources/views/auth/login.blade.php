<x-guest-layout>
    <style>
        @import url('https://fonts.bunny.net/css?family=instrument-sans:400,600|playfair-display:700,700i');
        
        .font-premium-serif { font-family: 'Playfair Display', serif; }
        .font-premium-sans { font-family: 'Instrument Sans', sans-serif; }
        
        nav img, .min-h-screen > div:first-child svg { display: none !important; }
        
        @keyframes premiumFadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes premiumFadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes premiumZoomIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-premium-down { animation: premiumFadeInDown 1s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }
        .animate-premium-up { animation: premiumFadeInUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }
        .animate-premium-zoom { animation: premiumZoomIn 1.2s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }

        .del-1 { animation-delay: 0.1s; }
        .del-2 { animation-delay: 0.2s; }
        .del-3 { animation-delay: 0.3s; }
        .del-4 { animation-delay: 0.4s; }
        .del-5 { animation-delay: 0.5s; }
        .del-6 { animation-delay: 0.6s; }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .input-premium {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid transparent !important;
        }
        .input-premium:focus {
            background-color: white !important;
            border-color: rgba(0,0,0,0.05) !important;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .btn-premium {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-premium:hover {
            background-color: #1a1a1a;
            letter-spacing: 0.25em;
            transform: translateY(-1px);
            box-shadow: 0 15px 30px -5px rgba(0,0,0,0.2);
        }

        .btn-google {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .btn-google:hover {
            background-color: #f8f8f8;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
    </style>

    <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-[#FAF9F7] font-premium-sans antialiased">
        
        <!-- Logo -->
        <div class="mb-14 text-center animate-premium-down">
            <a href="/" class="flex flex-col items-center gap-4 no-underline group">
                <div class="w-16 h-16 bg-black rounded-[1.6rem] flex items-center justify-center rotate-3 shadow-2xl transition-transform group-hover:rotate-0 duration-500">
                    <span class="text-white font-bold text-3xl">G</span>
                </div>
                <div class="space-y-1.5">
                    <span class="block font-black tracking-[0.4em] text-sm text-black uppercase">Gastro Nicaragua</span>
                    <span class="block h-[1px] w-full bg-black/10 scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></span>
                </div>
            </a>
        </div>

        <!-- Card -->
        <div class="w-full max-w-[440px] glass-card rounded-[3rem] p-12 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.06)] animate-premium-zoom del-1 relative">
            
            <!-- Detalle decorativo -->
            <div class="absolute top-8 right-8 w-8 h-8 flex flex-col gap-1.5 opacity-20">
                <span class="h-[1px] w-full bg-black"></span>
                <span class="h-[1px] w-full bg-black"></span>
                <span class="h-[1px] w-4 bg-black self-end"></span>
            </div>

            <div class="text-center mb-10">
                <h2 class="text-4xl font-premium-serif italic text-black mb-3">Login</h2>
                <div class="flex justify-center items-center gap-2">
                    <span class="h-[1px] w-8 bg-black/10"></span>
                    <p class="text-gray-400 text-[9px] uppercase tracking-[0.3em] font-bold">Portal de Administración</p>
                    <span class="h-[1px] w-8 bg-black/10"></span>
                </div>
            </div>

            <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

            @if(session('error'))
                <div class="mb-6 text-center text-xs text-red-500 bg-red-50 rounded-xl py-3 px-4">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-7">
                @csrf

                <!-- Email -->
                <div class="group animate-premium-up del-2">
                    <label for="email" class="block text-[9px] font-black uppercase tracking-[0.25em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-black">Correo Institucional</label>
                    <input id="email" 
                        class="input-premium block w-full bg-[#F3F2EE] border-none rounded-2xl px-7 py-5 outline-none focus:ring-1 focus:ring-black/5 text-sm text-black placeholder-gray-300" 
                        type="email" name="email" :value="old('email')" 
                        required autofocus autocomplete="username" 
                        placeholder="admin@turismo.ni" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 ml-4" />
                </div>

                <!-- Password -->
                <div class="group animate-premium-up del-3">
                    <label for="password" class="block text-[9px] font-black uppercase tracking-[0.25em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-black">Contraseña</label>
                    <input id="password" 
                        class="input-premium block w-full bg-[#F3F2EE] border-none rounded-2xl px-7 py-5 outline-none focus:ring-1 focus:ring-black/5 text-sm text-black placeholder-gray-300"
                        type="password" name="password"
                        required autocomplete="current-password" 
                        placeholder="••••••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 ml-4" />
                </div>

                <!-- Recordar / Recuperar -->
                <div class="flex items-center justify-between px-2 pt-1 animate-premium-up del-4">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" class="w-4 h-4 rounded-full border-gray-200 text-black shadow-sm focus:ring-black transition-all" name="remember">
                        <span class="ms-3 text-[11px] text-gray-400 group-hover:text-black transition-colors">Recordarme</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-[11px] text-gray-400 hover:text-black transition-all no-underline italic font-premium-serif" href="{{ route('password.request') }}">
                            ¿Problemas con el acceso?
                        </a>
                    @endif
                </div>

                <!-- Botón entrar -->
                <div class="pt-2 animate-premium-up del-5">
                    <button type="submit" class="btn-premium w-full bg-black text-white font-bold py-5 rounded-2xl uppercase tracking-[0.2em] text-[10px]">
                        Entrar al Sistema
                    </button>
                </div>
            </form>

            {{-- Separador Google --}}
            <div class="animate-premium-up del-5">
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-[1px] bg-black/8"></div>
                    <span class="text-[9px] font-black uppercase tracking-[0.25em] text-gray-300">o continúa con</span>
                    <div class="flex-1 h-[1px] bg-black/8"></div>
                </div>

                <!-- Botón Google -->
                <a href="{{ route('auth.google') }}"
                   class="btn-google flex items-center justify-center gap-3 w-full border border-black/8 bg-white text-black font-bold py-4 rounded-2xl text-[10px] uppercase tracking-[0.15em] no-underline">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuar con Google
                </a>
            </div>

            {{-- Registro --}}
            <div class="pt-6 text-center animate-premium-up del-6">
                <p class="text-[10px] text-gray-300 uppercase tracking-widest mb-4">¿No tienes cuenta?</p>
                <a href="{{ route('register') }}"
                   class="block w-full border border-black/10 text-black font-bold py-5 rounded-2xl uppercase tracking-[0.2em] text-[10px] hover:bg-black hover:text-white transition-all duration-300 no-underline text-center">
                    Crear Cuenta
                </a>
            </div>
        </div>

        <!-- Volver -->
        <div class="mt-12 group animate-premium-up del-6">
            <a href="/" class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-300 group-hover:text-black transition-all duration-500 no-underline flex items-center gap-3">
                <span class="h-[1px] w-4 bg-gray-200 group-hover:w-8 group-hover:bg-black transition-all duration-500"></span>
                Volver a la Galería
            </a>
        </div>
    </div>
</x-guest-layout>