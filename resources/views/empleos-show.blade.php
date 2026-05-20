<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $empleo->titulo }} | Gastro Nicaragua</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
            .premium-title { font-family: 'Playfair Display', serif; }
            .hero-empleo {
                background: linear-gradient(135deg, #1c1917 0%, #292524 60%, #431407 100%);
            }
        </style>
    </head>
    <body class="bg-stone-50 text-stone-900 antialiased">

        <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-stone-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline">
                        <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                            <i class="fas fa-utensils text-white"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight premium-title italic text-stone-900">Gastro<span class="text-orange-600">Nicaragua</span></span>
                    </a>
                    <a href="{{ route('empleos.index') }}" class="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline">
                        <i class="fas fa-chevron-left text-xs mr-1"></i> Volver a empleos
                    </a>
                </div>
            </div>
        </nav>

        <header class="hero-empleo pt-36 pb-16 text-white">
            <div class="max-w-4xl mx-auto px-4">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-orange-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $empleo->restaurante->nombre }}
                        </span>
                        @if($empleo->tipo_contrato)
                            <span class="bg-white/10 border border-white/10 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                <i class="fas fa-clock mr-1"></i> {{ $empleo->tipo_contrato }}
                            </span>
                        @endif
                    </div>

                    <h1 class="premium-title text-4xl md:text-5xl font-bold leading-tight">
                        {{ $empleo->titulo }}
                    </h1>

                    <p class="text-stone-300 text-sm flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-orange-500"></i>
                        <span class="font-semibold text-white">{{ $empleo->departamento->nombre }}</span>
                        @if($empleo->municipio)
                            — {{ $empleo->municipio->nombre }}
                        @endif
                    </p>
                </div>
            </div>
        </header>

        {{-- Alertas de éxito / error --}}
        @if(session('success'))
            <div class="max-w-4xl mx-auto px-4 pt-6">
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-xl">
                    <i class="fas fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-4xl mx-auto px-4 pt-6">
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm font-medium px-4 py-3 rounded-xl">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <main class="max-w-4xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Columna Izquierda: Detalles del Puesto --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Descripción --}}
                    <section class="bg-white rounded-2xl p-6 border border-stone-200/60 shadow-sm">
                        <h2 class="text-lg font-bold text-stone-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-align-left text-orange-600 text-sm"></i> Descripción de la vacante
                        </h2>
                        <p class="text-stone-600 text-sm leading-relaxed whitespace-pre-line">
                            {{ $empleo->descripcion }}
                        </p>
                    </section>

                    {{-- Requisitos --}}
                    <section class="bg-white rounded-2xl p-6 border border-stone-200/60 shadow-sm">
                        <h2 class="text-lg font-bold text-stone-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-orange-600 text-sm"></i> Requisitos del puesto
                        </h2>
                        @if($empleo->requisitos)
                            <p class="text-stone-600 text-sm leading-relaxed whitespace-pre-line">
                                {{ $empleo->requisitos }}
                            </p>
                        @else
                            <p class="text-stone-400 text-sm italic">
                                El restaurante no especificó requisitos adicionales para esta posición.
                            </p>
                        @endif
                    </section>
                </div>

                {{-- Columna Derecha --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 border border-stone-200/60 shadow-sm sticky top-24 space-y-5">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-3">Resumen de oferta</h3>

                            <div class="space-y-4 text-sm">
                                {{-- Salario --}}
                                <div class="border-b border-stone-100 pb-3">
                                    <span class="text-xs text-stone-400 block mb-0.5">Remuneración mensual</span>
                                    <span class="text-lg font-bold text-green-600">
                                        @if($empleo->salario)
                                            C$ {{ number_format($empleo->salario, 2) }}
                                        @else
                                            A convenir
                                        @endif
                                    </span>
                                </div>

                                {{-- Fecha Límite --}}
                                @if($empleo->fecha_limite)
                                    <div class="border-b border-stone-100 pb-3">
                                        <span class="text-xs text-stone-400 block mb-0.5">Fecha límite para aplicar</span>
                                        <span class="text-sm font-semibold text-stone-700 flex items-center gap-1.5">
                                            <i class="far fa-calendar-times text-red-500"></i>
                                            {{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d \d\e M, Y') }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Publicación --}}
                                <div class="border-b border-stone-100 pb-3">
                                    <span class="text-xs text-stone-400 block mb-0.5">Publicado</span>
                                    <span class="text-xs font-medium text-stone-500">
                                        {{ $empleo->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Redes del Establecimiento --}}
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-stone-400 block mb-3">Conoce el Establecimiento</span>
                            <div class="flex flex-wrap items-center gap-2.5">
                                @if(!empty($empleo->restaurante->whatsapp))
                                    @php $phoneClean = preg_replace('/[^0-9]/', '', $empleo->restaurante->whatsapp); @endphp
                                    <a href="https://wa.me/{{ $phoneClean }}" target="_blank"
                                       class="w-9 h-9 rounded-full bg-green-50 hover:bg-green-500 text-green-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                    </a>
                                @endif
                                @if(!empty($empleo->restaurante->instagram))
                                    <a href="{{ $empleo->restaurante->instagram }}" target="_blank"
                                       class="w-9 h-9 rounded-full bg-pink-50 text-pink-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-instagram text-lg"></i>
                                    </a>
                                @endif
                                @if(!empty($empleo->restaurante->tiktok))
                                    <a href="{{ $empleo->restaurante->tiktok }}" target="_blank"
                                       class="w-9 h-9 rounded-full bg-stone-50 hover:bg-black text-stone-800 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-tiktok text-sm"></i>
                                    </a>
                                @endif
                                @if(!empty($empleo->restaurante->facebook))
                                    <a href="{{ $empleo->restaurante->facebook }}" target="_blank"
                                       class="w-9 h-9 rounded-full bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm no-underline">
                                        <i class="fab fa-facebook-f text-base"></i>
                                    </a>
                                @endif
                                @if(empty($empleo->restaurante->whatsapp) && empty($empleo->restaurante->instagram) && empty($empleo->restaurante->tiktok) && empty($empleo->restaurante->facebook))
                                    <span class="text-xs text-stone-400 italic">Sin redes configuradas.</span>
                                @endif
                            </div>
                        </div>

                        {{-- ✅ BOTÓN QUE ABRE EL MODAL (reemplaza el mailto) --}}
                        <div class="pt-1">
                            <button
                                type="button"
                                onclick="abrirModalAplicar()"
                                style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; background:#ea580c; color:#fff; font-weight:700; font-size:14px; padding:12px 16px; border-radius:12px; border:none; cursor:pointer; transition: background 0.2s; box-shadow: 0 2px 8px rgba(234,88,12,0.25);"
                                onmouseover="this.style.background='#c2410c'"
                                onmouseout="this.style.background='#ea580c'">
                                <i class="fas fa-paper-plane text-xs"></i>
                                Aplicar a esta vacante
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </main>

        <footer class="bg-stone-900 text-white py-12 mt-20">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <p class="text-stone-500 text-xs tracking-widest uppercase font-bold m-0">© 2026 Gastro Nicaragua — Experiencias Culinarias</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        {{-- ===== MODAL DE APLICACIÓN ===== --}}
        <!-- Overlay del modal -->
        <div id="applyModal"
             style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; padding:1rem; background:rgba(0,0,0,0.80); backdrop-filter:blur(4px);">

            <div style="position:relative; width:100%; max-width:680px; max-height:90vh; overflow-y:auto; border-radius:1rem; background:#1a1a1a; border:1px solid #2e2e2e; animation: gastroSlideUp 0.3s cubic-bezier(0.16,1,0.3,1);">

                <!-- Header del modal -->
                <div style="position:sticky; top:0; z-index:10; display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #2e2e2e; background:#1a1a1a;">
                    <div>
                        <p style="font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:#fb923c; margin:0 0 4px;">
                            {{ $empleo->restaurante->nombre ?? 'Restaurante' }}
                        </p>
                        <h2 style="font-size:20px; font-weight:800; color:#fff; margin:0;">
                            Aplicar: {{ $empleo->titulo }}
                        </h2>
                    </div>
                    <button type="button" onclick="cerrarModalAplicar()"
                            style="background:transparent; border:none; color:#6b7280; cursor:pointer; padding:8px; border-radius:8px;"
                            onmouseover="this.style.background='#2e2e2e'; this.style.color='#fff';"
                            onmouseout="this.style.background='transparent'; this.style.color='#6b7280';">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <form id="applyForm"
                      action="{{ route('empleos.aplicar', $empleo->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      style="padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">
                    @csrf
                    <input type="hidden" name="empleo_id"         value="{{ $empleo->id }}">
                    <input type="hidden" name="empleo_titulo"     value="{{ $empleo->titulo }}">
                    <input type="hidden" name="restaurante_email" value="{{ $empleo->restaurante->email ?? '' }}">

                    <!-- Nombre y Apellido -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Nombre <span style="color:#fb923c">*</span></label>
                            <input type="text" name="nombre" required placeholder="Tu nombre"
                                   style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'">
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Apellido <span style="color:#fb923c">*</span></label>
                            <input type="text" name="apellido" required placeholder="Tu apellido"
                                   style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'">
                        </div>
                    </div>

                    <!-- Email y Teléfono -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Correo electrónico <span style="color:#fb923c">*</span></label>
                            <input type="email" name="email" required placeholder="correo@ejemplo.com"
                                   style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'">
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Teléfono / WhatsApp <span style="color:#fb923c">*</span></label>
                            <input type="tel" name="telefono" required placeholder="+505 8888-0000"
                                   style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'">
                        </div>
                    </div>

                    <!-- Edad y Municipio -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Edad <span style="color:#fb923c">*</span></label>
                            <input type="number" name="edad" required min="18" max="70" placeholder="Ej: 23"
                                   style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'">
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Municipio donde vives <span style="color:#fb923c">*</span></label>
                            <input type="text" name="municipio" required placeholder="Ej: Masatepe"
                                   style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'">
                        </div>
                    </div>

                    <!-- Experiencia -->
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Experiencia relevante</label>
                        <textarea name="experiencia" rows="3" placeholder="Describe brevemente tu experiencia..."
                                  style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; resize:none; box-sizing:border-box;"
                                  onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'"></textarea>
                    </div>

                    <!-- Disponibilidad -->
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Disponibilidad horaria <span style="color:#fb923c">*</span></label>
                        <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:8px;">
                            @foreach(['Mañana', 'Tarde', 'Noche', 'Fines de semana'] as $turno)
                            <label style="display:flex; align-items:center; gap:8px; padding:8px 10px; border-radius:8px; border:1px solid #333; cursor:pointer;">
                                <input type="checkbox" name="disponibilidad[]" value="{{ $turno }}" style="width:16px; height:16px; accent-color:#f97316;">
                                <span style="font-size:12px; color:#d1d5db;">{{ $turno }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Currículum -->
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                            Currículum Vitae <span style="color:#6b7280; font-weight:400; text-transform:none;">(PDF, DOC — máx. 5MB)</span>
                        </label>
                        <label id="cvDropzone"
                               style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; height:110px; border-radius:8px; border:2px dashed #333; background:#252525; cursor:pointer; transition:border-color 0.2s; box-sizing:border-box;"
                               onmouseover="this.style.borderColor='#f97316'" onmouseout="if(!cvTieneArchivo){this.style.borderColor='#333'}">
                            <div id="cvPlaceholder" style="text-align:center;">
                                <svg style="margin:0 auto 8px; color:#4b5563; width:30px; height:30px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p style="font-size:13px; color:#6b7280; margin:0;">Arrastra tu CV aquí o <span style="color:#fb923c;">selecciona archivo</span></p>
                            </div>
                            <div id="cvSelected" style="display:none; text-align:center;">
                                <svg style="margin:0 auto 4px; color:#4ade80; width:26px; height:26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <p id="cvFileName" style="font-size:13px; color:#4ade80; font-weight:600; margin:0;"></p>
                            </div>
                            <input id="cvInput" type="file" name="curriculum" accept=".pdf,.doc,.docx" style="display:none;" onchange="handleCvChange(this)">
                        </label>
                    </div>

                    <!-- Mensaje adicional -->
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Mensaje adicional (opcional)</label>
                        <textarea name="mensaje" rows="2" placeholder="¿Algo más que quieras contarle al empleador?"
                                  style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; resize:none; box-sizing:border-box;"
                                  onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#333'"></textarea>
                    </div>

                    <!-- Botón enviar -->
                    <button type="submit" id="submitBtn"
                            style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; background:#f97316; color:#fff; font-weight:800; font-size:15px; padding:14px 24px; border-radius:12px; border:none; cursor:pointer; transition:background 0.2s;"
                            onmouseover="this.style.background='#ea6c00'" onmouseout="this.style.background='#f97316'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span id="submitText">Enviar mi aplicación</span>
                    </button>
                </form>
            </div>
        </div>

        <style>
            @keyframes gastroSlideUp {
                from { opacity:0; transform:translateY(24px) scale(0.97); }
                to   { opacity:1; transform:translateY(0) scale(1); }
            }
            @media (max-width: 580px) {
                #applyModal > div > form > div[style*="grid-template-columns:1fr 1fr"] {
                    grid-template-columns: 1fr !important;
                }
                #applyModal > div > form > div[style*="grid-template-columns:1fr 1fr 1fr 1fr"] {
                    grid-template-columns: 1fr 1fr !important;
                }
            }
        </style>

        <script>
            var cvTieneArchivo = false;

            function abrirModalAplicar() {
                var m = document.getElementById('applyModal');
                m.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function cerrarModalAplicar() {
                var m = document.getElementById('applyModal');
                m.style.display = 'none';
                document.body.style.overflow = '';
            }

            document.getElementById('applyModal').addEventListener('click', function(e) {
                if (e.target === this) cerrarModalAplicar();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') cerrarModalAplicar();
            });

            function handleCvChange(input) {
                if (input.files && input.files[0]) {
                    cvTieneArchivo = true;
                    document.getElementById('cvPlaceholder').style.display = 'none';
                    document.getElementById('cvSelected').style.display    = 'block';
                    document.getElementById('cvFileName').textContent       = input.files[0].name;
                    document.getElementById('cvDropzone').style.borderColor = '#22c55e';
                }
            }

            document.getElementById('applyForm').addEventListener('submit', function() {
                var btn = document.getElementById('submitBtn');
                document.getElementById('submitText').textContent = 'Enviando...';
                btn.disabled     = true;
                btn.style.opacity = '0.7';
                btn.style.cursor  = 'not-allowed';
            });
        </script>

    </body>
</html>