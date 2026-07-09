{{-- resources/views/incidentes/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #2d3748;">
                <i class="bi bi-exclamation-triangle text-primary me-2"></i> Incidentes de Pedido
            </h1>
            <p class="text-muted mb-0 small">Reportes enviados por clientes, negocios y motorizados durante entregas.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tarjetas resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width:44px;height:44px;font-size:1.2rem;">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $totalIncidentes }}</div>
                        <small class="text-muted">Total incidentes</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width:44px;height:44px;font-size:1.2rem;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $totalAbiertos }}</div>
                        <small class="text-muted">Abiertos</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width:44px;height:44px;font-size:1.2rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $totalResueltos }}</div>
                        <small class="text-muted">Resueltos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-flag text-primary me-2"></i> Reportes de Incidentes
                </h6>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('incidentes.index') }}" class="btn btn-outline-secondary {{ !$estado ? 'active' : '' }}">Todos</a>
                    <a href="{{ route('incidentes.index', ['estado' => 'abierto']) }}" class="btn btn-outline-danger {{ $estado === 'abierto' ? 'active' : '' }}">Abiertos</a>
                    <a href="{{ route('incidentes.index', ['estado' => 'resuelto']) }}" class="btn btn-outline-success {{ $estado === 'resuelto' ? 'active' : '' }}">Resueltos</a>
                </div>
            </div>

            @if ($incidentes->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    <p class="small mb-0">No hay incidentes para mostrar.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th>Pedido</th>
                                <th>Reportado por</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-end">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incidentes as $incidente)
                                @php
                                    $pedido = $incidente->pedido;
                                    $esGastrobar = $pedido instanceof \App\Models\PedidoGastrobar;
                                    $nombreLugar = $esGastrobar
                                        ? ($pedido->gastrobar->nombre ?? 'Gastrobar')
                                        : ($pedido->restaurante->nombre ?? 'Restaurante');

                                    $tipoIconos = [
                                        'cliente'    => ['bi-person', 'primary'],
                                        'negocio'    => ['bi-shop', 'warning'],
                                        'motorizado' => ['bi-scooter', 'info'],
                                    ];
                                    [$tipoIcono, $tipoColor] = $tipoIconos[$incidente->tipo] ?? ['bi-question-circle', 'secondary'];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold small">
                                            <i class="bi {{ $esGastrobar ? 'bi-cup-straw' : 'bi-shop' }} text-muted me-1"></i>
                                            {{ $nombreLugar }}
                                        </div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            Pedido #{{ str_pad($pedido->id ?? 0, 4, '0', STR_PAD_LEFT) }}
                                            · {{ $esGastrobar ? 'Gastrobar' : 'Restaurante' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $tipoColor }} bg-opacity-10 text-{{ $tipoColor }} px-3 py-2 mb-1">
                                            <i class="bi {{ $tipoIcono }} me-1"></i>{{ ucfirst($incidente->tipo) }}
                                        </span>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            {{ $incidente->reportante->name ?? 'Usuario eliminado' }}
                                        </div>
                                    </td>
                                    <td class="small text-muted" style="max-width:260px;">
                                        {{ \Illuminate\Support\Str::limit($incidente->descripcion, 80) }}
                                    </td>
                                    <td class="small text-muted">{{ $incidente->created_at->format('d/m/Y h:i A') }}</td>
                                    <td>
                                        @if ($incidente->estado === 'abierto')
                                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">Abierto</span>
                                        @else
                                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">Resuelto</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($incidente->estado === 'abierto')
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#resolverModal{{ $incidente->id }}">
                                                <i class="bi bi-check2-square me-1"></i>Resolver
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#verResolucionModal{{ $incidente->id }}">
                                                Ver resolución
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Modal para resolver --}}
                                @if ($incidente->estado === 'abierto')
                                    <div class="modal fade" id="resolverModal{{ $incidente->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content rounded-3">
                                                <form action="{{ route('incidentes.resolver', $incidente) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold">
                                                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                                            Resolver incidente #{{ $incidente->id }}
                                                        </h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-light border small mb-3">
                                                            <strong>{{ ucfirst($incidente->tipo) }} reportó:</strong><br>
                                                            {{ $incidente->descripcion }}
                                                        </div>

                                                        <label class="form-label small fw-semibold">¿Qué pasó y cómo se resolvió?</label>
                                                        <textarea name="resolucion" class="form-control mb-3" rows="3" minlength="5" maxlength="1000" required
                                                            placeholder="Ej: Se reasignó un nuevo motorizado, el pedido llegó con 15 min de retraso."></textarea>

                                                        <label class="form-label small fw-semibold">Estado final del pedido</label>
                                                        <select name="estado_pedido" class="form-select" required>
                                                            <option value="">Seleccionar...</option>
                                                            <option value="entregado">Entregado</option>
                                                            <option value="en_preparacion">Reintentar (volver a en preparación)</option>
                                                            <option value="cancelado">Cancelado</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-check2 me-1"></i>Marcar como resuelto
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Modal solo lectura de la resolución --}}
                                    <div class="modal fade" id="verResolucionModal{{ $incidente->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content rounded-3">
                                                <div class="modal-header">
                                                    <h6 class="modal-title fw-bold">
                                                        <i class="bi bi-check-circle text-success me-2"></i>
                                                        Incidente #{{ $incidente->id }} resuelto
                                                    </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-light border small mb-3">
                                                        <strong>{{ ucfirst($incidente->tipo) }} reportó:</strong><br>
                                                        {{ $incidente->descripcion }}
                                                    </div>
                                                    <label class="form-label small fw-semibold text-muted">Resolución</label>
                                                    <p class="small">{{ $incidente->resolucion }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $incidentes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
