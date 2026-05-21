{{-- resources/views/empleos/show.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $empleo->titulo }} | Gastro Nicaragua</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600,700" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <style>
            body { 
                font-family: 'Instrument Sans', sans-serif; 
                background-color: #f5f5f4; /* stone-50 */
                color: #1c1917; /* stone-900 */
            }
            .premium-title { font-family: 'Playfair Display', serif; }
            .hero-empleo {
                background: linear-gradient(135deg, #1c1917 0%, #292524 60%, #431407 100%);
            }
            .nav-blur {
                background-color: rgba(255, 255, 255, 0.8) !important;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            /* Clases personalizadas de transición para emular Tailwind */
            .transition-all-300 {
                transition: all 0.3s ease-in-out;
            }
            .sticky-sidebar {
                position: sticky;
                top: 90px;
            }
            /* Colores de redes sociales en hover */
            .hover-whatsapp:hover { background-color: #25d366 !important; color: white !important; }
            .hover-instagram:hover { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%) !important; color: white !important; }
            .hover-tiktok:hover { background-color: #000000 !important; color: white !important; }
            .hover-facebook:hover { background-color: #1877f2 !important; color: white !important; }
        </style>
    </head>
    <body class="antialiased">

        {{-- ── Barra de Navegación Premium ── --}}
        <nav class="navbar navbar-expand-lg fixed-top border-bottom border-light-subtle nav-blur py-3">
            <div class="container" style="max-width: 960px;">
                <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none">
                    <div class="bg-warning rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; background-color: #ea580c !important;">
                        <i class="bi bi-egg-fried text-white fs-5"></i>
                    </div>
                    <span class="fs-4 fw-bold tracking-tight premium-title fst-italic text-dark">
                        Gastro<span style="color: #ea580c;">Nicaragua</span>
                    </span>
                </a>
                <div class="ms-auto">
                    <a href="{{ route('empleos.index') }}" class="text-secondary fw-semibold text-decoration-none small transition-all-300 hover-link" style="color: #57534e !important;">
                        <i class="bi bi-chevron-left small me-1"></i> Volver a empleos
                    </a>
                </div>
            </div>
        </nav>

        {{-- ── Hero / Encabezado del Empleo ── --}}
        <header class="hero-empleo text-white" style="padding-top: 130px; padding-bottom: 50px;">
            <div class="container" style="max-width: 960px;">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge text-white px-3 py-2 rounded-pill uppercase tracking-wider text-xs fw-bold" style="background-color: #ea580c;">
                            {{ $empleo->restaurante->nombre }}
                        </span>
                        @if($empleo->tipo_contrato)
                            <span class="badge bg-white bg-opacity-10 border border-white border-opacity-10 text-white px-3 py-2 rounded-pill uppercase tracking-wider text-xs fw-bold">
                                <i class="bi bi-clock me-1"></i> {{ $empleo->tipo_contrato }}
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="premium-title display-5 fw-bold leading-tight my-2">
                        {{ $empleo->titulo }}
                    </h1>

                    <p class="text-light-subtle text-sm d-flex align-items-center gap-2 mb-0">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        <span class="fw-semibold text-white">{{ $empleo->departamento->nombre }}</span> 
                        @if($empleo->municipio)
                            <span class="text-white-50">—</span> {{ $empleo->municipio->nombre }}
                        @endif
                    </p>
                </div>
            </div>
        </header>

        {{-- ── Alertas del Sistema ── --}}
        <div class="container mt-4" style="max-width: 960px;">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center p-3 rounded-4 mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div class="fw-medium text-success-emphasis small">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center p-3 rounded-4 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                    <div class="fw-medium text-danger-emphasis small">{{ session('error') }}</div>
                </div>
            @endif
        </div>

        {{-- ── Contenido Principal ── --}}
        <main class="container py-4" style="max-width: 960px;">
            <div class="row g-4">
                
                {{-- Columna Izquierda: Detalles de la Vacante --}}
                <div class="col-12 col-lg-8 d-flex flex-column gap-4">
                    
                    {{-- Descripción --}}
                    <section class="card border-light-subtle rounded-4 p-4 shadow-sm bg-white">
                        <h2 class="h5 fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="color: #292524 !important;">
                            <i class="bi bi-text-paragraph" style="color: #ea580c;"></i> Descripción de la vacante
                        </h2>
                        <p class="text-secondary small leading-relaxed mb-0" style="white-space: pre-line; color: #57534e !important;">
                            {{ $empleo->descripcion }}
                        </p>
                    </section>

                    {{-- Requisitos --}}
                    <section class="card border-light-subtle rounded-4 p-4 shadow-sm bg-white">
                        <h2 class="h5 fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="color: #292524 !important;">
                            <i class="bi bi-card-checklist" style="color: #ea580c;"></i> Requisitos del puesto
                        </h2>
                        @if($empleo->requisitos)
                            <p class="text-secondary small leading-relaxed mb-0" style="white-space: pre-line; color: #57534e !important;">
                                {{ $empleo->requisitos }}
                            </p>
                        @else
                            <p class="text-muted small italic mb-0">
                                El restaurante no especificó requisitos adicionales para esta posición.
                            </p>
                        @endif
                    </section>
                </div>

                {{-- Columna Derecha: Tarjeta Lateral Fija --}}
                <div class="col-12 col-lg-4">
                    <div class="card border-light-subtle rounded-4 p-4 shadow-sm bg-white sticky-sidebar d-flex flex-column gap-4">
                        
                        {{-- Resumen Finanzas / Fechas --}}
                        <div>
                            <h3 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.72rem; letter-spacing: 1px;">Resumen de oferta</h3>
                            
                            <div class="d-flex flex-column gap-3">
                                {{-- Salario --}}
                                <div class="border-bottom border-light pb-2">
                                    <small class="text-muted d-block mb-1">Remuneración mensual</small>
                                    <span class="fs-5 fw-bold text-success">
                                        @if($empleo->salario)
                                            C$ {{ number_format($empleo->salario, 2) }}
                                        @else
                                            A convenir
                                        @endif
                                    </span>
                                </div>

                                {{-- Fecha Límite --}}
                                @if($empleo->fecha_limite)
                                    <div class="border-bottom border-light pb-2">
                                        <small class="text-muted d-block mb-1">Fecha límite para aplicar</small>
                                        <span class="small fw-semibold text-dark d-inline-flex align-items-center gap-1.5" style="color: #44403c !important;">
                                            <i class="bi bi-calendar-x text-danger"></i>
                                            {{ \Carbon\Carbon::parse($empleo->fecha_limite)->translatedFormat('d \d\e M, Y') }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Publicación --}}
                                <div class="border-bottom border-light pb-1">
                                    <small class="text-muted d-block mb-1">Publicado</small>
                                    <span class="small text-secondary fw-medium">
                                        {{ $empleo->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Redes Sociales del Restaurante --}}
                        <div>
                            <span class="text-uppercase text-muted fw-bold d-block mb-3" style="font-size: 0.72rem; letter-spacing: 1px;">Conoce el Establecimiento</span>
                            
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @if(!empty($empleo->restaurante->whatsapp))
                                    @php $phoneClean = preg_replace('/[^0-9]/', '', $empleo->restaurante->whatsapp); @endphp
                                    <a href="https://wa.me/{{ $phoneClean }}" target="_blank" title="Consultas rápidas por WhatsApp"
                                       class="rounded-circle bg-light text-success d-flex align-items-center justify-content-center transition-all-300 hover-whatsapp shadow-sm" style="width: 36px; height: 36px; text-decoration: none;">
                                        <i class="bi bi-whatsapp fs-5"></i>
                                    </a>
                                @endif

                                @if(!empty($empleo->restaurante->instagram))
                                    <a href="{{ $empleo->restaurante->instagram }}" target="_blank" title="Ver Instagram"
                                       class="rounded-circle bg-light text-danger d-flex align-items-center justify-content-center transition-all-300 hover-instagram shadow-sm" style="width: 36px; height: 36px; text-decoration: none;">
                                        <i class="bi bi-instagram fs-5"></i>
                                    </a>
                                @endif

                                @if(!empty($empleo->restaurante->tiktok))
                                    <a href="{{ $empleo->restaurante->tiktok }}" target="_blank" title="Ver TikTok"
                                       class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center transition-all-300 hover-tiktok shadow-sm" style="width: 36px; height: 36px; text-decoration: none;">
                                        <i class="bi bi-tiktok small"></i>
                                    </a>
                                @endif

                                @if(!empty($empleo->restaurante->facebook))
                                    <a href="{{ $empleo->restaurante->facebook }}" target="_blank" title="Ver Facebook"
                                       class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center transition-all-300 hover-facebook shadow-sm" style="width: 36px; height: 36px; text-decoration: none;">
                                        <i class="bi bi-facebook fs-5"></i>
                                    </a>
                                @endif

                                @if(empty($empleo->restaurante->whatsapp) && empty($empleo->restaurante->instagram) && empty($empleo->restaurante->tiktok) && empty($empleo->restaurante->facebook))
                                    <span class="small text-muted fst-italic">Sin redes configuradas.</span>
                                @endif
                            </div>
                        </div>

                        {{-- Botón / Modal de Postulación --}}
                        <div class="pt-1 w-100">
                            @include('components.apply-modal', ['empleo' => $empleo])
                        </div>
                    </div>
                </div>

            </div>
        </main>

        {{-- ── Pie de Página ── --}}
        <footer class="bg-dark text-white py-4 mt-5" style="background-color: #1c1917 !important;">
            <div class="container text-center">
                <p class="text-white-50 small text-uppercase tracking-wider mb-0" style="font-size: 0.7rem; letter-spacing: 1px;">
                    © 2026 Gastro Nicaragua — Experiencias Culinarias
                </p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>