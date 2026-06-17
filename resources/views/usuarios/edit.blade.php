@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Usuario
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Modificando: <strong>{{ $usuario->name }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
                <i class="bi bi-eye me-1"></i> Ver
            </a>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- ── Errores de validación ── --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                <strong>Por favor corrige los siguientes errores:</strong>
            </div>
            <ul class="mb-0 ps-4 mt-1">
                @foreach($errors->all() as $error)
                    <li style="font-size:0.88rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $activo = ($usuario->estado ?? 'activo') === 'activo';
        $rolConfig = [
            'admin'       => ['label'=>'Administrador','bg'=>'#fff3cd','color'=>'#856404','icon'=>'bi-shield-fill-check'],
            'restaurante' => ['label'=>'Restaurante',  'bg'=>'#cfe2ff','color'=>'#0a3878','icon'=>'bi-shop'],
            'gastrobar'   => ['label'=>'Gastrobar',    'bg'=>'#d1e7dd','color'=>'#0a3622','icon'=>'bi-cup-straw'],
            'usuario'     => ['label'=>'Cliente',      'bg'=>'#f8d7da','color'=>'#842029','icon'=>'bi-person-heart'],
        ];
        $rc = $rolConfig[$usuario->role] ?? ['label'=>ucfirst($usuario->role),'bg'=>'#e2e8f0','color'=>'#4a5568','icon'=>'bi-person'];
        $iniciales = strtoupper(substr($usuario->name ?? 'U', 0, 2));
        $colores = [
            'admin'       => ['bg'=>'#fff3cd','color'=>'#856404'],
            'restaurante' => ['bg'=>'#cfe2ff','color'=>'#0a3878'],
            'gastrobar'   => ['bg'=>'#d1e7dd','color'=>'#0a3622'],
            'usuario'     => ['bg'=>'#f8d7da','color'=>'#842029'],
        ];
        $c = $colores[$usuario->role] ?? ['bg'=>'#e2e8f0','color'=>'#4a5568'];

        // Determinar el teléfono a mostrar según el rol
        $telefonoMostrar = null;
        if ($usuario->role === 'restaurante' && $usuario->restaurante) {
            $telefonoMostrar = $usuario->restaurante->telefono;
        } elseif ($usuario->role === 'gastrobar' && $usuario->gastrobar) {
            $telefonoMostrar = $usuario->gastrobar->telefono;
        } else {
            $telefonoMostrar = $usuario->telefono;
        }
    @endphp

    <div class="row g-4">

        {{-- ── Columna Izquierda: Info actual ── --}}
        <div class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
                <div style="height:60px;background:linear-gradient(135deg,#ffc107 0%,#fd7e14 100%);"></div>
                <div class="card-body text-center px-3 pb-4" style="margin-top:-36px;">
                    <div class="rounded-3 border border-4 border-white overflow-hidden bg-light d-inline-flex align-items-center justify-content-center shadow mb-2"
                         style="width:64px;height:64px;">
                        @if(!empty($usuario->foto))
                            <img src="{{ Storage::url($usuario->foto) }}" alt="{{ $usuario->name }}" class="w-100 h-100" style="object-fit:cover;">
                        @else
                            <span class="fw-bold fs-5" style="color:{{ $c['color'] }};background:{{ $c['bg'] }};width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                {{ $iniciales }}
                            </span>
                        @endif
                    </div>
                    <h6 class="fw-bold text-dark text-uppercase mb-1 small">{{ $usuario->name }}</h6>
                    <p class="text-muted mb-2" style="font-size:0.72rem;">{{ $usuario->email }}</p>
                    <span class="badge px-2 py-1 fw-bold text-uppercase d-inline-flex align-items-center gap-1 mb-2"
                          style="background:{{ $rc['bg'] }};color:{{ $rc['color'] }};font-size:0.65rem;">
                        <i class="bi {{ $rc['icon'] }}" style="font-size:0.6rem;"></i> {{ $rc['label'] }}
                    </span>
                    <br>
                    @if($activo)
                        <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                              style="background:#e6fffa;color:#047481;border:1px solid #b2f5ea;font-size:0.65rem;">
                            <span class="bg-success rounded-circle" style="width:5px;height:5px;"></span> ACTIVO
                        </span>
                    @else
                        <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                              style="background:#fff5f5;color:#c53030;border:1px solid #fed7d7;font-size:0.65rem;">
                            <span class="bg-danger rounded-circle" style="width:5px;height:5px;"></span> SUSPENDIDO
                        </span>
                    @endif

                    <hr class="my-3">

                    <div class="text-start">
                        <p class="text-uppercase text-muted fw-bold mb-2" style="font-size:0.65rem;letter-spacing:0.5px;">Info de cuenta</p>
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-hash text-warning me-1"></i> ID: <strong>#{{ $usuario->id }}</strong>
                        </small>
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-calendar-plus text-warning me-1"></i> Registro: <strong>{{ $usuario->created_at->format('d/m/Y') }}</strong>
                        </small>
                        <small class="text-muted d-block">
                            <i class="bi bi-clock text-warning me-1"></i> {{ $usuario->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Columna Derecha: Formulario ── --}}
        <div class="col-12 col-lg-9">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4 p-lg-5">

                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        {{-- ── Datos Personales ── --}}
                        <p class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.5px;">
                            <i class="bi bi-person me-1 text-warning"></i> Datos Personales
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Ej: María López"
                                       autocomplete="off"
                                       style="box-shadow:none;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">
                                    @if($usuario->role === 'restaurante' || $usuario->role === 'gastrobar')
                                        Teléfono del {{ $usuario->role === 'restaurante' ? 'Restaurante' : 'Gastrobar' }}
                                        <span class="text-muted fw-normal">(desde el panel de {{ $usuario->role }})</span>
                                    @else
                                        Teléfono
                                    @endif
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telefono" value="{{ old('telefono', $telefonoMostrar) }}"
                                           class="form-control border-start-0 @error('telefono') is-invalid @enderror"
                                           placeholder="Ej: 8888-8888"
                                           autocomplete="off"
                                           style="box-shadow:none;"
                                           @if($usuario->role === 'restaurante' || $usuario->role === 'gastrobar') readonly @endif>
                                </div>
                                @if($usuario->role === 'restaurante' || $usuario->role === 'gastrobar')
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Edita el teléfono desde
                                        <a href="{{ $usuario->role === 'restaurante' ? route('admin.restaurantes.edit', $usuario->restaurante_id) : route('admin.gastrobares.edit', $usuario->gastrobar_id) }}" class="text-warning fw-semibold">
                                            el panel de {{ $usuario->role }}
                                        </a>
                                    </small>
                                @endif
                                @error('telefono')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── Acceso al Sistema ── --}}
                        <p class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.5px;">
                            <i class="bi bi-shield-lock me-1 text-warning"></i> Acceso al Sistema
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Correo electrónico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           placeholder="correo@ejemplo.com"
                                           autocomplete="off"
                                           style="box-shadow:none;">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Rol <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-badge"></i></span>
                                    <select name="role" class="form-select border-start-0 @error('role') is-invalid @enderror" style="box-shadow:none;cursor:pointer;">
                                        <option value="admin"       {{ old('role', $usuario->role) == 'admin'       ? 'selected' : '' }}>Administrador</option>
                                        <option value="restaurante" {{ old('role', $usuario->role) == 'restaurante' ? 'selected' : '' }}>Restaurante</option>
                                        <option value="gastrobar"   {{ old('role', $usuario->role) == 'gastrobar'   ? 'selected' : '' }}>Gastrobar</option>
                                        <option value="usuario"     {{ old('role', $usuario->role) == 'usuario'     ? 'selected' : '' }}>Cliente</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── Cambiar Contraseña (opcional) ── --}}
                        <p class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.5px;">
                            <i class="bi bi-key me-1 text-warning"></i> Cambiar Contraseña
                            <span class="text-muted fw-normal" style="font-size:0.68rem;">(opcional — dejar vacío para no cambiar)</span>
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Nueva contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password"
                                           class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                           placeholder="Mínimo 8 caracteres"
                                           autocomplete="new-password"
                                           style="box-shadow:none;">
                                    <button type="button" class="input-group-text bg-light border-start-0 text-muted" id="togglePassword" style="cursor:pointer;">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Confirmar nueva contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation"
                                           class="form-control border-start-0"
                                           placeholder="Repetir contraseña"
                                           autocomplete="new-password"
                                           style="box-shadow:none;">
                                </div>
                            </div>
                        </div>

                        {{-- ── Botones ── --}}
                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning px-5 rounded-pill fw-semibold text-dark shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
@endsection
