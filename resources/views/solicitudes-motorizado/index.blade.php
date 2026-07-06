@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-scooter text-primary me-2"></i> Solicitudes de Motorizado
            </h1>
            <p class="text-muted small mb-0">Revisá y aprobá las solicitudes de nuevos motorizados.</p>
        </div>
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
                            <th class="ps-4 py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Solicitante</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Vehículo</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Cobertura</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Estado</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Fecha</th>
                            <th class="pe-4 py-3 text-end text-uppercase text-muted" style="font-size:0.72rem;letter-spacing:0.05em;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark" style="font-size:0.9rem;">{{ $solicitud->user->name }}</div>
                                    <div class="text-muted" style="font-size:0.78rem;">{{ $solicitud->user->telefono }}</div>
                                </td>
                                <td>
                                    <span class="text-capitalize" style="font-size:0.85rem;">{{ $solicitud->tipo_vehiculo }}</span>
                                    @if($solicitud->placa)
                                        <div class="text-muted" style="font-size:0.78rem;">{{ $solicitud->placa }}</div>
                                    @endif
                                </td>
                                <td style="font-size:0.85rem;">
                                    {{ $solicitud->departamento->nombre ?? '—' }}
                                    @if($solicitud->municipio)
                                        <div class="text-muted" style="font-size:0.78rem;">{{ $solicitud->municipio->nombre }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($solicitud->estado === 'pendiente')
                                        <span class="badge rounded-pill" style="background:#fef3c7;color:#92400e;font-weight:600;padding:6px 12px;">Pendiente</span>
                                    @elseif($solicitud->estado === 'aprobada')
                                        <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-weight:600;padding:6px 12px;">Aprobada</span>
                                    @else
                                        <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-weight:600;padding:6px 12px;">Rechazada</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:0.82rem;">
                                    {{ $solicitud->created_at->format('d/m/Y') }}
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.solicitudes-motorizado.show', $solicitud) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye"></i> Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    No hay solicitudes de motorizado por ahora.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>
</div>
@endsection
