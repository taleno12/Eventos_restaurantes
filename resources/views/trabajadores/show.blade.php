@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('trabajadores.index') }}"
               class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
               style="width: 38px; height: 38px;">
                <i class="bi bi-arrow-left text-secondary"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold text-dark">
                    <i class="bi bi-person-fill text-warning me-2"></i> {{ $trabajador->nombre_completo }}
                </h1>
                <p class="text-muted small mb-0">Detalle del trabajador registrado en el sistema.</p>
            </div>
        </div>
        <a href="{{ route('trabajadores.edit', $trabajador->id) }}"
           class="btn btn-warning fw-semibold px-4 rounded-pill text-dark shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Editar Trabajador
        </a>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- HERO — Portada del Trabajador             --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden position-relative"
         style="min-height: 220px; background-color: #18181b;">

        @if($trabajador->foto)
            <img src="{{ asset('storage/' . $trabajador->foto) }}"
                 alt="{{ $trabajador->nombre_completo }}"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit: cover; opacity: 0.25;">
        @endif

        <div class="position-absolute bottom-0 start-0 w-100 h-100"
             style="background: linear-gradient(to top, rgba(10,10,10,0.92) 0%, rgba(24,24,27,0.55) 60%, transparent 100%);"></div>

        <div class="position-relative p-4 p-md-5 d-flex align-items-end gap-4" style="min-height: 220px; z-index: 2;">

            <div class="rounded-3 border border-warning overflow-hidden flex-shrink-0 shadow"
                 style="width: 80px; height: 80px; background:#27272a;">
                @if($trabajador->foto)
                    <img src="{{ asset('storage/' . $trabajador->foto) }}"
                         alt="{{ $trabajador->nombre_completo }}"
                         class="w-100 h-100" style="object-fit: cover;">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-person-fill text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                @endif
            </div>

            <div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if($trabajador->cargo)
                        <span class="badge rounded-pill fw-bold px-3 py-2"
                              style="font-size: 0.65rem; letter-spacing: 0.08em; background: rgba(255,193,7,0.2); border: 1px solid rgba(255,193,7,0.4); color: #fde68a;">
                            <i class="bi bi-briefcase me-1"></i>{{ $trabajador->cargo }}
                        </span>
                    @endif
                    @if($trabajador->estado === 'activo')
                        <span class="badge rounded-pill fw-bold px-3 py-2"
                              style="font-size: 0.65rem; letter-spacing: 0.08em; background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.4); color: #6ee7b7;">
                            <i class="bi bi-circle-fill me-1" style="font-size:0.4rem; vertical-align: middle;"></i>ACTIVO
                        </span>
                    @else
                        <span class="badge rounded-pill fw-bold px-3 py-2"
                              style="font-size: 0.65rem; letter-spacing: 0.08em; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #d1d5db;">
                            INACTIVO
                        </span>
                    @endif
                </div>

                <h2 class="fw-bold text-white mb-1" style="font-size: 1.8rem; line-height: 1.1;">
                    {{ $trabajador->nombre_completo }}
                </h2>
                <p class="mb-0" style="color: #9ca3af; font-size: 0.85rem;">
                    <i class="bi bi-card-text me-1"></i> {{ $trabajador->cedula }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-envelope me-1"></i> {{ $trabajador->email }}
                </p>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- GRID: Columna info + Columna derecha      --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="row g-4">

        {{-- ── Columna Izquierda ── --}}
        <div class="col-12 col-md-4 d-flex flex-column gap-4">

            {{-- Datos Personales --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-person text-warning"></i> Datos Personales
                    </h6>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="d-flex align-items-center justify-content-center rounded-3 bg-light flex-shrink-0"
                              style="width:34px;height:34px;">
                            <i class="bi bi-envelope" style="color:#9333ea; font-size:0.8rem;"></i>
                        </span>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Correo</p>
                            <a href="mailto:{{ $trabajador->email }}" class="text-dark text-decoration-none small">{{ $trabajador->email }}</a>
                        </div>
                    </div>

                    @if($trabajador->telefono)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                              style="width:34px;height:34px;background:#f0fdf4;">
                            <i class="bi bi-whatsapp" style="color:#25d366;font-size:0.8rem;"></i>
                        </span>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Teléfono</p>
                            <span class="text-dark small">{{ $trabajador->telefono }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                              style="width:34px;height:34px;background:#eff6ff;">
                            <i class="bi bi-card-text" style="color:#3b82f6;font-size:0.8rem;"></i>
                        </span>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Cédula</p>
                            <span class="text-dark small">{{ $trabajador->cedula }}</span>
                        </div>
                    </div>

                    @if($trabajador->fecha_ingreso)
                    <div class="d-flex align-items-center gap-3">
                        <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                              style="width:34px;height:34px;background:#fffbeb;">
                            <i class="bi bi-calendar-event" style="color:#f59e0b;font-size:0.8rem;"></i>
                        </span>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Fecha de Ingreso</p>
                            <span class="text-dark small">{{ $trabajador->fecha_ingreso->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Datos Laborales --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-briefcase text-warning"></i> Datos Laborales
                    </h6>

                    @if($trabajador->cargo)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                              style="width:34px;height:34px;background:#fffbeb;">
                            <i class="bi bi-briefcase" style="color:#f59e0b;font-size:0.8rem;"></i>
                        </span>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Cargo</p>
                            <span class="text-dark small">{{ $trabajador->cargo }}</span>
                        </div>
                    </div>
                    @endif

                    @if($trabajador->salario)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                              style="width:34px;height:34px;background:#f0fdf4;">
                            <i class="bi bi-cash-stack" style="color:#16a34a;font-size:0.8rem;"></i>
                        </span>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Salario</p>
                            <span class="text-dark small fw-semibold">C$ {{ number_format($trabajador->salario, 2) }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex align-items-center gap-3">
                        <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                              style="width:34px;height:34px;background:#f3f4f6;">
                            <i class="bi bi-toggle-on" style="color:#6b7280;font-size:0.8rem;"></i>
                        </span>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Estado</p>
                            @if($trabajador->estado === 'activo')
                                <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                      style="background-color:#e6fffa;color:#047481;border:1px solid #b2f5ea;font-size:0.72rem;">
                                    <span class="bg-success rounded-circle" style="width:5px;height:5px;"></span> ACTIVO
                                </span>
                            @else
                                <span class="badge rounded-pill bg-light text-muted border px-2 py-1 fw-normal" style="font-size:0.72rem;">
                                    INACTIVO
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- Observaciones --}}
            @if($trabajador->observaciones)
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-text-paragraph text-warning"></i> Observaciones
                    </h6>
                    <p class="text-dark small mb-0" style="line-height: 1.6;">{{ $trabajador->observaciones }}</p>
                </div>
            </div>
            @endif

        </div>

        {{-- ── Columna Derecha ── --}}
        <div class="col-12 col-md-8 d-flex flex-column gap-4">

            {{-- Departamentos Asignados --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-geo-alt text-warning"></i> Departamentos Asignados
                    </h6>

                    @forelse($trabajador->departamentos as $depto)
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-2"
                             style="background:#f8fafc; border: 1px solid #edf2f7;">
                            <span class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                  style="width:34px;height:34px;background:#fff1f2;">
                                <i class="bi bi-geo-alt-fill" style="color:#f43f5e;font-size:0.8rem;"></i>
                            </span>
                            <span class="fw-semibold text-dark">{{ $depto->nombre }}</span>
                        </div>
                    @empty
                        <p class="text-muted small fst-italic mb-0">Sin departamentos asignados.</p>
                    @endforelse
                </div>
            </div>

            {{-- Restaurantes Disponibles --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-shop text-warning"></i> Restaurantes en sus Departamentos
                    </h6>

                    @if(count($restaurantes) > 0)
                        <div class="row g-3">
                            @foreach($restaurantes as $rest)
                            <div class="col-12 col-sm-6">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border"
                                     style="background:#f8fafc; border-color:#edf2f7 !important;">
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm"
                                         style="width:42px;height:42px;">
                                        @if($rest->foto_portada)
                                            <img src="{{ asset('storage/' . $rest->foto_portada) }}"
                                                 alt="{{ $rest->nombre }}"
                                                 class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            <i class="bi bi-shop text-warning fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark small d-block text-uppercase">{{ $rest->nombre }}</span>
                                        @if($rest->especialidad)
                                            <span class="text-muted" style="font-size:0.72rem;">{{ $rest->especialidad }}</span>
                                        @endif
                                        @if($rest->departamento)
                                            <span class="d-block text-muted" style="font-size:0.7rem;">
                                                <i class="bi bi-geo-alt-fill text-danger" style="font-size:0.65rem;"></i>
                                                {{ $rest->departamento->nombre }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small fst-italic mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            No hay restaurantes registrados en los departamentos asignados.
                        </p>
                    @endif

                </div>
            </div>

            {{-- Gastrobares Disponibles --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-cup-straw text-warning"></i> Gastrobares en sus Departamentos
                    </h6>

                    @if(count($gastrobares) > 0)
                        <div class="row g-3">
                            @foreach($gastrobares as $gastro)
                            <div class="col-12 col-sm-6">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border"
                                     style="background:#f8fafc; border-color:#edf2f7 !important;">
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm"
                                         style="width:42px;height:42px;">
                                        @if($gastro->foto_portada)
                                            <img src="{{ asset('storage/' . $gastro->foto_portada) }}"
                                                 alt="{{ $gastro->nombre }}"
                                                 class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            <i class="bi bi-cup-straw text-warning fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark small d-block text-uppercase">{{ $gastro->nombre }}</span>
                                        @if($gastro->tipo_bar)
                                            <span class="text-muted" style="font-size:0.72rem;">{{ $gastro->tipo_bar }}</span>
                                        @endif
                                        @if($gastro->departamento)
                                            <span class="d-block text-muted" style="font-size:0.7rem;">
                                                <i class="bi bi-geo-alt-fill text-danger" style="font-size:0.65rem;"></i>
                                                {{ $gastro->departamento->nombre }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small fst-italic mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            No hay gastrobares registrados en los departamentos asignados.
                        </p>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- Footer de acciones                        --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-body p-4 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
            <p class="text-muted small mb-0">
                <i class="bi bi-clock me-1"></i>
                Registrado: {{ $trabajador->created_at->format('d/m/Y') }}
                @if($trabajador->updated_at && $trabajador->updated_at != $trabajador->created_at)
                    · Actualizado: {{ $trabajador->updated_at->diffForHumans() }}
                @endif
            </p>
            <div class="d-flex gap-2">
                <a href="{{ route('trabajadores.edit', $trabajador->id) }}"
                   class="btn btn-warning fw-semibold px-4 rounded-pill text-dark shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
                <form action="{{ route('trabajadores.destroy', $trabajador->id) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este trabajador? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger fw-semibold px-4 rounded-pill">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
