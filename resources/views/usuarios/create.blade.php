@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-person-plus-fill text-warning me-2"></i> Nuevo Usuario
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Registrar un nuevo usuario en el sistema Gastro.ni
            </p>
        </div>
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
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

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4 p-lg-5">

                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf

                        {{-- ── Datos Personales ── --}}
                        <p class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.5px;">
                            <i class="bi bi-person me-1 text-warning"></i> Datos Personales
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Ej: María López"
                                       style="box-shadow:none;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                                           class="form-control border-start-0 @error('telefono') is-invalid @enderror"
                                           placeholder="Ej: 8888-8888"
                                           style="box-shadow:none;">
                                </div>
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
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           placeholder="correo@ejemplo.com"
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
                                        <option value="">Seleccionar rol...</option>
                                        <option value="admin"       {{ old('role') == 'admin'       ? 'selected' : '' }}>Administrador</option>
                                        <option value="restaurante" {{ old('role') == 'restaurante' ? 'selected' : '' }}>Restaurante</option>
                                        <option value="gastrobar"   {{ old('role') == 'gastrobar'   ? 'selected' : '' }}>Gastrobar</option>
                                        <option value="usuario"     {{ old('role') == 'usuario'     ? 'selected' : '' }}>Cliente</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password"
                                           class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                           placeholder="Mínimo 8 caracteres"
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
                                <label class="form-label fw-semibold small">Confirmar contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation"
                                           class="form-control border-start-0"
                                           placeholder="Repetir contraseña"
                                           style="box-shadow:none;">
                                </div>
                            </div>
                        </div>

                        {{-- ── Info adicional ── --}}
                        <div class="alert border-0 rounded-3 mb-4 d-flex align-items-start gap-2"
                             style="background:#fff8e1;border-left:3px solid #ffc107 !important;">
                            <i class="bi bi-info-circle-fill text-warning mt-1" style="flex-shrink:0;"></i>
                            <small class="text-muted">
                                El usuario quedará <strong>activo</strong> inmediatamente. Podrás suspenderlo desde el listado de usuarios en cualquier momento.
                            </small>
                        </div>

                        {{-- ── Botones ── --}}
                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning px-5 rounded-pill fw-semibold text-dark shadow-sm">
                                <i class="bi bi-person-plus me-1"></i> Crear Usuario
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
