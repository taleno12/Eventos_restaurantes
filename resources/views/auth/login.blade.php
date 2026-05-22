<x-guest-layout>
    <!-- Estilos Premium Directos con Animaciones de Carga -->
    <style>
        @import url('https://fonts.bunny.net/css?family=instrument-sans:400,600|playfair-display:700,700i');
        
        .font-premium-serif { font-family: 'Playfair Display', serif; }
        .font-premium-sans { font-family: 'Instrument Sans', sans-serif; }
        
        /* Ocultar el logo gigante por defecto del layout si aparece */
        nav img, .min-h-screen > div:first-child svg { display: none !important; }
        
        /* Definición de Animaciones Keyframes */
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

        /* Clases de Animación con Retardo (Cascada) */
        .animate-premium-down { animation: premiumFadeInDown 1s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }
        .animate-premium-up { animation: premiumFadeInUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }
        .animate-premium-zoom { animation: premiumZoomIn 1.2s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }

        /* Retardos específicos para el efecto cascada */
        .del-1 { animation-delay: 0.1s; }
        .del-2 { animation-delay: 0.2s; }
        .del-3 { animation-delay: 0.3s; }
        .del-4 { animation-delay: 0.4s; }
        .del-5 { animation-delay: 0.5s; }

        /* Estilos de Tarjeta y Micro-interacciones */
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
    </style>

    <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-[#FAF9F7] font-premium-sans antialiased">
        
        <!-- Logo Gastro.ni con Animación Down -->
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

        <!-- Card de Login con Animación ZoomIn Sutil -->
        <div class="w-full max-w-[440px] glass-card rounded-[3rem] p-12 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.06)] animate-premium-zoom del-1 relative">
            
            <!-- Detalle decorativo premium -->
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

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-7">
                @csrf

                <!-- Email Address con Animación Up en Cascada -->
                <div class="group animate-premium-up del-2">
                    <label for="email" class="block text-[9px] font-black uppercase tracking-[0.25em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-black">Correo Institucional</label>
                    <input id="email" 
                        class="input-premium block w-full bg-[#F3F2EE] border-none rounded-2xl px-7 py-5 outline-none focus:ring-1 focus:ring-black/5 text-sm text-black placeholder-gray-300" 
                        type="email" name="email" :value="old('email')" 
                        required autofocus autocomplete="username" 
                        placeholder="admin@turismo.ni" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 ml-4" />
                </div>

                <!-- Password con Animación Up en Cascada -->
                <div class="group animate-premium-up del-3">
                    <label for="password" class="block text-[9px] font-black uppercase tracking-[0.25em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-black">Contraseña</label>
                    <input id="password" 
                        class="input-premium block w-full bg-[#F3F2EE] border-none rounded-2xl px-7 py-5 outline-none focus:ring-1 focus:ring-black/5 text-sm text-black placeholder-gray-300"
                        type="password"
                        name="password"
                        required autocomplete="current-password" 
                        placeholder="••••••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 ml-4" />
                </div>

                <!-- Opciones con Animación Up en Cascada -->
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

                <!-- Botón con Animación Up en Cascada y Efecto Hover -->
                <div class="pt-6 animate-premium-up del-5">
                    <button type="submit" class="btn-premium w-full bg-black text-white font-bold py-5 rounded-2xl uppercase tracking-[0.2em] text-[10px]">
                        Entrar al Sistema
                    </button>
                </div>
            </form>

            {{-- Separador + Botón Registro --}}
<div class="pt-4 text-center animate-premium-up del-5">
    <p class="text-[10px] text-gray-300 uppercase tracking-widest mb-4">¿No tienes cuenta?</p>
    <a href="{{ route('register') }}"
       class="block w-full border border-black/10 text-black font-bold py-5 rounded-2xl uppercase tracking-[0.2em] text-[10px] hover:bg-black hover:text-white transition-all duration-300 no-underline text-center">
        Crear Cuenta
    </a>
</div>
        </div>

        <!-- Volver a la Galería con Animación Up Final -->
        <div class="mt-12 group animate-premium-up del-5">
            <a href="/" class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-300 group-hover:text-black transition-all duration-500 no-underline flex items-center gap-3">
                <span class="h-[1px] w-4 bg-gray-200 group-hover:w-8 group-hover:bg-black transition-all duration-500"></span>
                Volver a la Galería
            </a>
        </div>
    </div>
</x-guest-layout>