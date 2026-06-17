@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i> Usuarios del Sistema
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 6px; vertical-align: middle;"></i>
                Administradores, propietarios y personal registrado
            </p>
        </div>
        <a href="{{ route('usuarios.create') }}" class="btn btn-warning px-4 rounded-pill shadow-sm fw-semibold text-dark">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Usuario
        </a>
    </div>

    {{-- ── Alertas ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center"><i class="bi bi-check-circle-fill me-2 fs-5"></i><div>{{ session('success') }}</div></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center"><i class="bi bi-exclamation-circle-fill me-2 fs-5"></i><div>{{ session('error') }}</div></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Métricas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-sm-4 col-lg-2-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-warning bg-opacity-10 p-2" style="width:44px;height:44px;">
                        <i class="bi bi-people-fill text-warning fs-5"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.7rem;letter-spacing:0.5px;">Total Cuentas</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.4rem;">{{ $totalUsuarios }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-danger bg-opacity-10 p-2" style="width:44px;height:44px;">
                        <i class="bi bi-shield-fill-check text-danger fs-5"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.7rem;letter-spacing:0.5px;">Admins</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.4rem;">{{ $totalAdmins }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 p-2" style="width:44px;height:44px;">
                        <i class="bi bi-shop text-primary fs-5"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.7rem;letter-spacing:0.5px;">Restaurantes</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.4rem;">{{ $totalRestaurantes }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-success bg-opacity-10 p-2" style="width:44px;height:44px;">
                        <i class="bi bi-cup-straw text-success fs-5"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.7rem;letter-spacing:0.5px;">Gastrobares</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.4rem;">{{ $totalGastrobares }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-info bg-opacity-10 p-2" style="width:44px;height:44px;">
                        <i class="bi bi-person-vcard text-info fs-5"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-0" style="font-size:0.7rem;letter-spacing:0.5px;">Personal</p>
                        <h3 class="fw-black text-dark mb-0" style="font-size:1.4rem;">{{ $totalTrabajadores }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Barra de Filtros (única) ── --}}
    @php $rolActivo = request('role', ''); $mostrarPersonal = ($rolActivo === 'personal'); @endphp

    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('usuarios.index') }}" class="row g-3 align-items-center">
            <div class="col-12 col-sm-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                           class="form-control bg-light border-start-0 ps-0"
                           placeholder="{{ $mostrarPersonal ? 'Buscar por nombre, cédula o cargo...' : 'Buscar por nombre o email...' }}"
                           style="box-shadow:none;">
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-badge"></i></span>
                    <select name="role" id="rolSelect" class="form-select bg-light border-start-0 ps-0" style="box-shadow:none;cursor:pointer;">
                        <option value="">Todos los roles</option>
                        <option value="restaurante" {{ $rolActivo == 'restaurante' ? 'selected' : '' }}>Propietario de Restaurante</option>
                        <option value="gastrobar"   {{ $rolActivo == 'gastrobar'   ? 'selected' : '' }}>Propietario de Gastrobar</option>
                        <option value="personal"    {{ $rolActivo == 'personal'    ? 'selected' : '' }}>Personal</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-5 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-funnel-fill text-warning"></i> Filtrar
                </button>
                @if(request('buscar') || request('role'))
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center" title="Limpiar">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- TABLA: PERSONAL                            --}}
    {{-- ══════════════════════════════════════════ --}}
    @if($mostrarPersonal)
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background-color:#f8f9fa;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Trabajador</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Cédula</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Cargo</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Departamentos</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Estado</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Ingreso</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;width:100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($trabajadores as $trabajador)
                        <tr class="border-bottom" style="border-color:#edf2f7 !important;">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center me-2 shadow-sm" style="width:40px;height:40px;flex-shrink:0;">
                                        @if(!empty($trabajador->foto))
                                            <img src="{{ Storage::url($trabajador->foto) }}" alt="{{ $trabajador->nombre_completo }}" class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            <span class="fw-bold" style="font-size:0.75rem;color:#0369a1;background:#e0f2fe;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                                {{ strtoupper(substr($trabajador->nombre, 0, 1) . substr($trabajador->apellido, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-uppercase" style="font-size:0.88rem;">{{ $trabajador->nombre_completo }}</span>
                                        @if($trabajador->email)
                                            <small class="text-muted" style="font-size:0.75rem;">{{ $trabajador->email }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3"><span class="text-dark small font-monospace">{{ $trabajador->cedula ?? '—' }}</span></td>
                            <td class="py-3">
                                @if($trabajador->cargo)
                                    <span class="badge px-2 py-1 fw-semibold" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;font-size:0.72rem;">{{ $trabajador->cargo }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($trabajador->departamentos->take(3) as $dep)
                                        <span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:#fefce8;color:#854d0e;border:1px solid #fde68a;font-size:0.68rem;">{{ $dep->nombre }}</span>
                                    @empty
                                        <span class="text-muted small">Sin asignar</span>
                                    @endforelse
                                    @if($trabajador->departamentos->count() > 3)
                                        <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size:0.68rem;">+{{ $trabajador->departamentos->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                @if(($trabajador->estado ?? 'activo') === 'activo')
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background:#e6fffa;color:#047481;border:1px solid #b2f5ea;font-size:0.72rem;">
                                        <span class="bg-success rounded-circle" style="width:5px;height:5px;"></span> ACTIVO
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background:#fff5f5;color:#c53030;border:1px solid #fed7d7;font-size:0.72rem;">
                                        <span class="bg-danger rounded-circle" style="width:5px;height:5px;"></span> INACTIVO
                                    </span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="text-dark small">{{ $trabajador->fecha_ingreso ? $trabajador->fecha_ingreso->format('d/m/Y') : '—' }}</span>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('trabajadores.show', $trabajador->id) }}" class="text-secondary p-1 action-icon-view" title="Ver detalle"><i class="bi bi-eye fs-5"></i></a>
                                    <a href="{{ route('trabajadores.edit', $trabajador->id) }}" class="text-secondary p-1 action-icon-edit" title="Editar"><i class="bi bi-pencil fs-5"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-people d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block mb-2">No se encontró personal registrado.</span>
                                <a href="{{ route('trabajadores.create') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold text-dark">Agregar Personal</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-end">
        {{ $trabajadores->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- TABLA: USUARIOS (admin/restaurante/gastrobar) --}}
    {{-- ══════════════════════════════════════════ --}}
    @else
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background-color:#f8f9fa;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Usuario</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Rol</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Teléfono</th>
                            <th class="py-3 text-secondary border-0 text-center" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Estado</th>
                            <th class="py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;">Registro</th>
                            <th class="text-end pe-4 py-3 text-secondary border-0" style="font-size:0.75rem;letter-spacing:0.5px;font-weight:600;width:160px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($usuarios as $usuario)
                        <tr class="border-bottom" style="border-color:#edf2f7 !important;">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-pill me-3" style="width:4px;height:32px;"></div>
                                    <div class="rounded-3 border overflow-hidden bg-light d-flex align-items-center justify-content-center me-2 shadow-sm" style="width:40px;height:40px;flex-shrink:0;">
                                        @if(!empty($usuario->foto))
                                            <img src="{{ Storage::url($usuario->foto) }}" alt="{{ $usuario->name }}" class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            @php
                                                $iniciales = strtoupper(substr($usuario->name ?? 'U', 0, 2));
                                                $colores = ['admin'=>['bg'=>'#fff3cd','color'=>'#856404'],'restaurante'=>['bg'=>'#cfe2ff','color'=>'#0a3878'],'gastrobar'=>['bg'=>'#d1e7dd','color'=>'#0a3622']];
                                                $c = $colores[$usuario->role] ?? ['bg'=>'#e2e8f0','color'=>'#4a5568'];
                                            @endphp
                                            <span class="fw-bold" style="font-size:0.75rem;color:{{ $c['color'] }};background:{{ $c['bg'] }};width:100%;height:100%;display:flex;align-items:center;justify-content:center;">{{ $iniciales }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block text-uppercase" style="font-size:0.88rem;">{{ $usuario->name }}</span>
                                        <small class="text-muted" style="font-size:0.75rem;">{{ $usuario->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @php
                                    $rolConfig = ['admin'=>['label'=>'Administrador','bg'=>'#fff3cd','color'=>'#856404','icon'=>'bi-shield-fill-check'],'restaurante'=>['label'=>'Propietario Restaurante','bg'=>'#cfe2ff','color'=>'#0a3878','icon'=>'bi-shop'],'gastrobar'=>['label'=>'Propietario Gastrobar','bg'=>'#d1e7dd','color'=>'#0a3622','icon'=>'bi-cup-straw']];
                                    $rc = $rolConfig[$usuario->role] ?? ['label'=>ucfirst($usuario->role),'bg'=>'#e2e8f0','color'=>'#4a5568','icon'=>'bi-person'];
                                @endphp
                                <span class="badge px-2 py-1 fw-bold text-uppercase d-inline-flex align-items-center gap-1" style="background:{{ $rc['bg'] }};color:{{ $rc['color'] }};font-size:0.7rem;letter-spacing:0.3px;">
                                    <i class="bi {{ $rc['icon'] }}" style="font-size:0.65rem;"></i> {{ $rc['label'] }}
                                </span>
                            </td>
                            <td class="py-3">
                                @php
                                    $telefonoMostrar = null;

                                    // Si es propietario de restaurante, mostrar teléfono del restaurante
                                    if ($usuario->role === 'restaurante' && $usuario->restaurante) {
                                        $telefonoMostrar = $usuario->restaurante->telefono;
                                    }
                                    // Si es propietario de gastrobar, mostrar teléfono del gastrobar
                                    elseif ($usuario->role === 'gastrobar' && $usuario->gastrobar) {
                                        $telefonoMostrar = $usuario->gastrobar->telefono;
                                    }
                                    // Para admins u otros, usar teléfono del usuario directamente
                                    else {
                                        $telefonoMostrar = $usuario->telefono;
                                    }
                                @endphp

                                @if(!empty($telefonoMostrar))
                                    <span class="text-dark small"><i class="bi bi-telephone text-muted me-1" style="font-size:0.75rem;"></i>{{ $telefonoMostrar }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @php $estado = $usuario->estado ?? 'activo'; @endphp
                                @if($estado === 'activo')
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background:#e6fffa;color:#047481;border:1px solid #b2f5ea;font-size:0.72rem;"><span class="bg-success rounded-circle" style="width:5px;height:5px;"></span> ACTIVO</span>
                                @elseif($estado === 'suspendido')
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background:#fff5f5;color:#c53030;border:1px solid #fed7d7;font-size:0.72rem;"><span class="bg-danger rounded-circle" style="width:5px;height:5px;"></span> SUSPENDIDO</span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2 py-1 fw-normal" style="font-size:0.72rem;">INACTIVO</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="text-dark small">{{ $usuario->created_at->format('d/m/Y') }}</span><br>
                                <small class="text-muted" style="font-size:0.72rem;">{{ $usuario->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('usuarios.show', $usuario->id) }}" class="text-secondary p-1 action-icon-view" title="Ver detalle"><i class="bi bi-eye fs-5"></i></a>
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-secondary p-1 action-icon-edit" title="Editar"><i class="bi bi-pencil fs-5"></i></a>
                                    @if($usuario->role === 'admin')
                                    <form action="{{ route('usuarios.toggle', $usuario->id) }}" method="POST" class="d-inline m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-link p-1 m-0 border-0 align-baseline action-icon-toggle" style="box-shadow:none;text-decoration:none;"
                                                onclick="return confirm('¿{{ ($usuario->estado ?? 'activo') === 'activo' ? 'Suspender este usuario?' : 'Reactivar este usuario?' }}')">
                                            <i class="bi {{ ($usuario->estado ?? 'activo') === 'activo' ? 'bi-lock' : 'bi-unlock' }} fs-5 text-secondary"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar este usuario? Esta acción no se puede deshacer.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-secondary p-1 m-0 border-0 align-baseline action-icon-delete" style="box-shadow:none;text-decoration:none;"><i class="bi bi-trash fs-5"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-people d-block display-6 text-muted mb-3"></i>
                                <span class="fs-6 d-block mb-2">No se encontraron usuarios.</span>
                                <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold text-dark">Agregar Primer Usuario</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-end">
        {{ $usuarios->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

<style>
    .action-icon-view:hover   { color: #0d6efd !important; }
    .action-icon-edit:hover   { color: #ffc107 !important; }
    .action-icon-delete:hover { color: #dc3545 !important; }
    .action-icon-toggle:hover i { color: #198754 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    @media (min-width: 992px) { .col-lg-2-4 { flex: 0 0 20%; max-width: 20%; } }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
