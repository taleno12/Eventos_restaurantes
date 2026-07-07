@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-scooter text-primary me-2"></i> Motorizados
            </h1>
            <p class="text-muted small mb-0">Gestioná el estado de las cuentas de motorizados aprobados.</p>
        </div>

        <form method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   class="form-control form-control-sm rounded-3"
                   placeholder="Buscar por nombre o teléfono..." style="width:240px;">
            <button type="submit" class="btn btn-sm btn-primary rounded-3">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f9fafb;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Motorizado</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Vehículo</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Estado</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Desde</th>
                            <th class="pe-4 py-3 text-end text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($motorizados as $motorizado)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark" style="font-size:0.9rem;">{{ $motorizado->name }}</div>
                                    <div class="text-muted" style="font-size:0.78rem;">{{ $motorizado->telefono ?? '—' }}</div>
                                </td>
                                <td>
                                    <span class="text-capitalize" style="font-size:0.85rem;">{{ $motorizado->vehiculo ?? '—' }}</span>
                                    @if($motorizado->placa)
                                        <div class="text-muted" style="font-size:0.78rem;">{{ $motorizado->placa }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if(($motorizado->estado ?? 'activo') === 'activo')
                                        <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-weight:600;padding:6px 12px;">Activo</span>
                                    @else
                                        <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-weight:600;padding:6px 12px;">Suspendido</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:0.82rem;">
                                    {{ $motorizado->created_at->format('d/m/Y') }}
                                </td>
                                <td class="pe-4 text-end">
                                    <form action="{{ route('usuarios.toggle', $motorizado) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if(($motorizado->estado ?? 'activo') === 'activo')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                                <i class="bi bi-slash-circle"></i> Suspender
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-3">
                                                <i class="bi bi-check-circle"></i> Reactivar
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No hay motorizados registrados por ahora.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $motorizados->links() }}
    </div>
</div>
@endsection
