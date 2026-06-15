@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-person-circle text-warning me-2"></i> Detalle de Usuario
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Información completa del usuario del sistema
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning px-4 rounded-pill shadow-sm fw-semibold text-dark">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- ── Alertas ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $rolConfig = [
            'admin'       => ['label'=>'Administrador','bg'=>'#fff3cd','color'=>'#856404','icon'=>'bi-shield-fill-check'],
            'restaurante' => ['label'=>'Restaurante',  'bg'=>'#cfe2ff','color'=>'#0a3878','icon'=>'bi-shop'],
            'gastrobar'   => ['label'=>'Gastrobar',    'bg'=>'#d1e7dd','color'=>'#0a3622','icon'=>'bi-cup-straw'],
            'usuario'     => ['label'=>'Cliente',      'bg'=>'#f8d7da','color'=>'#842029','icon'=>'bi-person-heart'],
        ];
        $rc = $rolConfig[$usuario->role] ?? ['label'=>ucfirst($usuario->role),'bg'=>'#e2e8f0','color'=>'#4a5568','icon'=>'bi-person'];
        $activo = ($usuario->estado ?? 'activo') === 'activo';
        $iniciales = strtoupper(substr($usuario->name ?? 'U', 0, 2));
        $colores = [
            'admin'       => ['bg'=>'#fff3cd','color'=>'#856404'],
            'restaurante' => ['bg'=>'#cfe2ff','color'=>'#0a3878'],
            'gastrobar'   => ['bg'=>'#d1e7dd','color'=>'#0a3622'],
            'usuario'     => ['bg'=>'#f8d7da','color'=>'#842029'],
        ];
        $c = $colores[$usuario->role] ?? ['bg'=>'#e2e8f0','color'=>'#4a5568'];
    @endphp

    <div class="row g-4">

        {{-- ── Columna Izquierda: Perfil ── --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
                <div style="height:80px;background:linear-gradient(135deg,#ffc107 0%,#fd7e14 100%);"></div>

                <div class="card-body text-center px-4 pb-4" style="margin-top:-48px;">
                    <div class="rounded-3 border border-4 border-white overflow-hidden bg-light d-inline-flex align-items-center justify-content-center shadow mb-3"
                         style="width:80px;height:80px;">
                        @if(!empty($usuario->foto))
                            <img src="{{ Storage::url($usuario->foto) }}" alt="{{ $usuario->name }}" class="w-100 h-100" style="object-fit:cover;">
                        @else
                            <span class="fw-bold fs-4" style="color:{{ $c['color'] }};background:{{ $c['bg'] }};width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                {{ $iniciales }}
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-bold text-dark text-uppercase mb-1">{{ $usuario->name }}</h5>
                    <p class="text-muted small mb-2">{{ $usuario->email }}</p>

                    <span class="badge px-3 py-1 fw-bold text-uppercase d-inline-flex align-items-center gap-1 mb-3"
                          style="background:{{ $rc['bg'] }};color:{{ $rc['color'] }};font-size:0.72rem;">
                        <i class="bi {{ $rc['icon'] }}" style="font-size:0.65rem;"></i>
                        {{ $rc['label'] }}
                    </span><br>

                    @if($activo)
                        <span class="badge rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                              style="background:#e6fffa;color:#047481;border:1px solid #b2f5ea;font-size:0.72rem;">
                            <span class="bg-success rounded-circle" style="width:5px;height:5px;"></span> ACTIVO
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                              style="background:#fff5f5;color:#c53030;border:1px solid #fed7d7;font-size:0.72rem;">
                            <span class="bg-danger rounded-circle" style="width:5px;height:5px;"></span> SUSPENDIDO
                        </span>
                    @endif

                    <hr class="my-3">

                    <div class="d-grid gap-2">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning rounded-pill fw-semibold text-dark">
                            <i class="bi bi-pencil me-1"></i> Editar Usuario
                        </a>

                        <form action="{{ route('usuarios.toggle', $usuario->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="btn w-100 rounded-pill fw-semibold {{ $activo ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                    onclick="return confirm('{{ $activo ? '¿Suspender este usuario?' : '¿Reactivar este usuario?' }}')">
                                <i class="bi {{ $activo ? 'bi-lock' : 'bi-unlock' }} me-1"></i>
                                {{ $activo ? 'Suspender' : 'Activar' }}
                            </button>
                        </form>

                        @if($usuario->id !== auth()->id())
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este usuario? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-semibold">
                                <i class="bi bi-trash me-1"></i> Eliminar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Columna Derecha: Información ── --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-4">

            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <p class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.5px;">
                        <i class="bi bi-person me-1 text-warning"></i> Datos Personales
                    </p>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">Nombre completo</label>
                            <p class="fw-semibold text-dark mb-0">{{ $usuario->name }}</p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">Correo electrónico</label>
                            <p class="fw-semibold text-dark mb-0">{{ $usuario->email }}</p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">Teléfono</label>
                            <p class="fw-semibold text-dark mb-0">{{ $usuario->telefono ?? '—' }}</p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">Rol en el sistema</label>
                            <p class="fw-semibold text-dark mb-0">{{ $rc['label'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <p class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.5px;">
                        <i class="bi bi-shield-lock me-1 text-warning"></i> Información de Cuenta
                    </p>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">ID de usuario</label>
                            <p class="fw-semibold text-dark mb-0">#{{ $usuario->id }}</p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">Estado de cuenta</label>
                            <p class="mb-0">
                                @if($activo)
                                    <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Activo</span>
                                @else
                                    <span class="text-danger fw-semibold"><i class="bi bi-x-circle-fill me-1"></i>Suspendido</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">Fecha de registro</label>
                            <p class="fw-semibold text-dark mb-0">
                                {{ $usuario->created_at->format('d/m/Y H:i') }}
                                <small class="text-muted d-block" style="font-size:0.72rem;">{{ $usuario->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="text-muted" style="font-size:0.75rem;">Última actualización</label>
                            <p class="fw-semibold text-dark mb-0">
                                {{ $usuario->updated_at->format('d/m/Y H:i') }}
                                <small class="text-muted d-block" style="font-size:0.72rem;">{{ $usuario->updated_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    .btn-outline-danger:hover, .btn-outline-success:hover { opacity: 0.85; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
