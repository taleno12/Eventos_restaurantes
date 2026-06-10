@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- ── ENCABEZADO ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color:#1a1a1a;">
                <i class="bi bi-people me-2" style="color:#ea580c;"></i>Usuarios del Sistema
            </h2>
            <p class="text-muted mb-0 small">Gestión de cuentas y roles</p>
        </div>
    </div>

    {{-- ── ALERTAS ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── TARJETAS DE RESUMEN ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Total usuarios</p>
                            <p class="fw-bold fs-4 mb-0">{{ $totalUsuarios }}</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#fff3ee;">
                            <i class="bi bi-people fs-5" style="color:#ea580c;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Administradores</p>
                            <p class="fw-bold fs-4 mb-0">{{ $totalAdmins }}</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#fef9c3;">
                            <i class="bi bi-shield-check fs-5" style="color:#a16207;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Restaurantes</p>
                            <p class="fw-bold fs-4 mb-0">{{ $totalRestaurantes }}</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#dcfce7;">
                            <i class="bi bi-shop fs-5" style="color:#16a34a;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Clientes</p>
                            <p class="fw-bold fs-4 mb-0">{{ $totalClientes }}</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:#e0f2fe;">
                            <i class="bi bi-person fs-5" style="color:#0284c7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FILTROS ── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('usuarios.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                           class="form-control" placeholder="Buscar por nombre o correo...">
                </div>
                <div class="col-6 col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Todos los roles</option>
                        <option value="admin"       {{ request('role') === 'admin'       ? 'selected' : '' }}>Administrador</option>
                        <option value="restaurante" {{ request('role') === 'restaurante' ? 'selected' : '' }}>Restaurante</option>
                        <option value="user"        {{ request('role') === 'user'        ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button type="submit" class="btn w-100 fw-semibold text-white" style="background:#ea580c;">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                </div>
                @if(request()->hasAny(['buscar','role']))
                <div class="col-6 col-md-2">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- ── TABLA ── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-list-ul me-2" style="color:#ea580c;"></i>
                Lista de usuarios
                <span class="badge ms-2" style="background:#fff3ee;color:#ea580c;">
                    {{ $usuarios->total() }}
                </span>
            </h6>
        </div>

        @if($usuarios->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people display-4 d-block mb-3" style="color:#d1d5db;"></i>
                <p class="fw-semibold mb-1">No se encontraron usuarios</p>
                <p class="small">Intenta con otros filtros.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f9fafb;">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-semibold">Usuario</th>
                        <th class="py-3 text-muted small fw-semibold">Correo</th>
                        <th class="py-3 text-muted small fw-semibold">Rol</th>
                        <th class="py-3 text-muted small fw-semibold">Estado</th>
                        <th class="py-3 text-muted small fw-semibold">Registro</th>
                        <th class="py-3 text-muted small fw-semibold text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        {{-- Avatar + nombre --}}
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($usuario->avatar)
                                    <img src="{{ $usuario->avatar }}" alt="{{ $usuario->name }}"
                                         class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                         style="width:40px;height:40px;background:#ea580c;font-size:16px;">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0 small">{{ $usuario->name }}</p>
                                    @if($usuario->google_id)
                                        <span class="badge" style="background:#e8f0fe;color:#1a73e8;font-size:10px;">
                                            <i class="bi bi-google me-1"></i>Google
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Correo --}}
                        <td class="py-3 small text-muted">{{ $usuario->email }}</td>

                        {{-- Rol --}}
                        <td class="py-3">
                            @if($usuario->role === 'admin')
                                <span class="badge px-2 py-1" style="background:#fef9c3;color:#a16207;">
                                    <i class="bi bi-shield-check me-1"></i>Admin
                                </span>
                            @elseif($usuario->role === 'restaurante')
                                <span class="badge px-2 py-1" style="background:#dcfce7;color:#16a34a;">
                                    <i class="bi bi-shop me-1"></i>Restaurante
                                </span>
                            @else
                                <span class="badge px-2 py-1" style="background:#e0f2fe;color:#0284c7;">
                                    <i class="bi bi-person me-1"></i>Cliente
                                </span>
                            @endif
                        </td>

                        {{-- Estado --}}
                        <td class="py-3">
                            @if($usuario->email_verified_at)
                                <span class="badge px-2 py-1" style="background:#dcfce7;color:#16a34a;">
                                    <i class="bi bi-check-circle me-1"></i>Activo
                                </span>
                            @else
                                <span class="badge px-2 py-1" style="background:#fee2e2;color:#dc2626;">
                                    <i class="bi bi-x-circle me-1"></i>Inactivo
                                </span>
                            @endif
                        </td>

                        {{-- Fecha registro --}}
                        <td class="py-3 small text-muted">
                            {{ $usuario->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Acciones --}}
                        <td class="py-3 pe-4 text-end">
                            <div class="d-flex gap-1 justify-content-end">

                                {{-- Editar rol --}}
                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Editar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $usuario->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Activar / Desactivar --}}
                                @if($usuario->id !== auth()->id())
                                <form action="{{ route('usuarios.toggle', $usuario) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $usuario->email_verified_at ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $usuario->email_verified_at ? 'Desactivar' : 'Activar' }}"
                                            onclick="return confirm('{{ $usuario->email_verified_at ? '¿Desactivar este usuario?' : '¿Activar este usuario?' }}')">
                                        <i class="bi bi-{{ $usuario->email_verified_at ? 'person-dash' : 'person-check' }}"></i>
                                    </button>
                                </form>

                                {{-- Eliminar --}}
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar"
                                            onclick="return confirm('¿Eliminar a {{ $usuario->name }}? Esta acción no se puede deshacer.')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>

                    {{-- ── MODAL EDITAR ROL ── --}}
                    <div class="modal fade" id="modalEdit{{ $usuario->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom">
                                    <h6 class="modal-title fw-bold">
                                        <i class="bi bi-pencil me-2" style="color:#ea580c;"></i>
                                        Editar usuario
                                    </h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Nombre</label>
                                            <input type="text" name="name" value="{{ $usuario->name }}"
                                                   class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Correo electrónico</label>
                                            <input type="email" name="email" value="{{ $usuario->email }}"
                                                   class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Rol</label>
                                            <select name="role" class="form-select">
                                                <option value="user"        {{ $usuario->role === 'user'        ? 'selected' : '' }}>Cliente</option>
                                                <option value="restaurante" {{ $usuario->role === 'restaurante' ? 'selected' : '' }}>Restaurante</option>
                                                <option value="admin"       {{ $usuario->role === 'admin'       ? 'selected' : '' }}>Administrador</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn fw-semibold text-white" style="background:#ea580c;">
                                            <i class="bi bi-check-lg me-1"></i>Guardar cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($usuarios->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $usuarios->links() }}
        </div>
        @endif

        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
