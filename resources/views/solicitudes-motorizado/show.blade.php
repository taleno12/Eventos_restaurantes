@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; max-width:760px;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.solicitudes-motorizado.index') }}"
           class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center"
           style="width: 38px; height: 38px;">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-scooter text-primary me-2"></i> Detalle de Solicitud
            </h1>
            <p class="text-muted small mb-0">Revisá los datos y documentación antes de decidir.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- ── Datos del solicitante ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2"
                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <i class="bi bi-person text-primary"></i> Datos del solicitante
            </h6>
            <div class="row g-3">
                <div class="col-6">
                    <div class="text-muted small">Nombre</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->user->name }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Teléfono</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->user->telefono ?? '—' }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Email</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->user->email }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Fecha de solicitud</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->created_at->format('d/m/Y h:i A') }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Cédula</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->cedula ?? '—' }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Fecha de nacimiento</div>
                    <div class="fw-semibold text-dark">
                        {{ $solicitud->fecha_nacimiento ? \Carbon\Carbon::parse($solicitud->fecha_nacimiento)->format('d/m/Y') : '—' }}
                        @if($solicitud->fecha_nacimiento)
                            <span class="text-muted fw-normal">({{ $solicitud->edad }} años)</span>
                        @endif
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Género</div>
                    <div class="fw-semibold text-dark text-capitalize">{{ $solicitud->genero ?? '—' }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Contacto</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->contacto ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Vehículo y cobertura ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2"
                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <i class="bi bi-scooter text-primary"></i> Vehículo y cobertura
            </h6>
            <div class="row g-3">
                <div class="col-6">
                    <div class="text-muted small">Tipo de vehículo</div>
                    <div class="fw-semibold text-dark text-capitalize">{{ $solicitud->tipo_vehiculo }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Placa</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->placa ?? '—' }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Departamento</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->departamento->nombre ?? '—' }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Municipio</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->municipio->nombre ?? '—' }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Localidad</div>
                    <div class="fw-semibold text-dark">{{ $solicitud->localidad ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─ NUEVA SECCIÓN: Documentación Fotográfica ─ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2"
                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <i class="bi bi-images text-primary"></i> Documentación Fotográfica
            </h6>

            <div class="row g-3">
                {{-- Foto de Perfil --}}
                <div class="col-md-4">
                    <div class="text-muted small mb-2">Foto de Perfil</div>
                    @if($solicitud->foto_perfil)
                        <img src="{{ asset('storage/' . $solicitud->foto_perfil) }}"
                             alt="Foto de perfil"
                             class="img-fluid rounded-3 border"
                             style="width:100%; height:200px; object-fit:cover;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="height:200px;">
                            Sin foto
                        </div>
                    @endif
                </div>

                {{-- Foto de Licencia --}}
                <div class="col-md-4">
                    <div class="text-muted small mb-2">Licencia de Conducir</div>
                    @if($solicitud->foto_licencia)
                        <img src="{{ asset('storage/' . $solicitud->foto_licencia) }}"
                             alt="Licencia"
                             class="img-fluid rounded-3 border"
                             style="width:100%; height:200px; object-fit:cover;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="height:200px;">
                            Sin foto
                        </div>
                    @endif
                </div>

                {{-- Foto de Récord Policial --}}
                <div class="col-md-4">
                    <div class="text-muted small mb-2">Récord Policial</div>
                    @if($solicitud->foto_record_policial)
                        <img src="{{ asset('storage/' . $solicitud->foto_record_policial) }}"
                             alt="Récord policial"
                             class="img-fluid rounded-3 border"
                             style="width:100%; height:200px; object-fit:cover;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="height:200px;">
                            Sin foto
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Decisión / Estado ── --}}
    @if($solicitud->estado === 'pendiente')
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2"
                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-check2-square text-primary"></i> Decisión
                </h6>

                <form method="POST" action="{{ route('admin.solicitudes-motorizado.aprobar', $solicitud) }}" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success w-100 rounded-pill fw-semibold py-2">
                        <i class="bi bi-check-circle me-1"></i> Aprobar solicitud
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.solicitudes-motorizado.rechazar', $solicitud) }}">
                    @csrf
                    @method('PATCH')
                    <textarea name="motivo_rechazo" class="form-control bg-light rounded-3 mb-2" rows="2"
                              placeholder="Motivo de rechazo (opcional)" style="box-shadow:none;"></textarea>
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-semibold py-2">
                        <i class="bi bi-x-circle me-1"></i> Rechazar solicitud
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    Estado de la solicitud
                </h6>
                @if($solicitud->estado === 'aprobada')
                    <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-weight:600;padding:6px 12px;">Aprobada</span>
                @else
                    <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-weight:600;padding:6px 12px;">Rechazada</span>
                    @if($solicitud->motivo_rechazo)
                        <p class="text-muted mt-2 mb-0 small">{{ $solicitud->motivo_rechazo }}</p>
                    @endif
                @endif
                <p class="text-muted mt-3 mb-0 small">
                    Revisado por {{ $solicitud->revisadoPor->name ?? '—' }} el {{ optional($solicitud->revisado_at)->format('d/m/Y h:i A') }}
                </p>
            </div>
        </div>
    @endif

</div>
@endsection
