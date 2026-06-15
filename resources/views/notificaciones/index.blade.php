@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── ENCABEZADO ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color:#1a1a1a;">
                <i class="bi bi-bell-fill me-2" style="color:#ea580c;"></i>Notificaciones
            </h2>
            <p class="text-muted mb-0 small">Avisos de contratos y pagos próximos a vencer o vencidos</p>
        </div>
        @if($totalNoLeidas > 0)
            <form action="{{ route('notificaciones.marcarTodasLeidas') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-secondary fw-semibold">
                    <i class="bi bi-check2-all me-1"></i> Marcar todas como leídas
                </button>
            </form>
        @endif
    </div>

    {{-- ── ALERTAS ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── FILTROS ── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('notificaciones.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="contrato_por_vencer" {{ request('tipo') === 'contrato_por_vencer' ? 'selected' : '' }}>Contrato por vencer</option>
                        <option value="contrato_vencido"    {{ request('tipo') === 'contrato_vencido'    ? 'selected' : '' }}>Contrato vencido</option>
                        <option value="pago_pendiente"      {{ request('tipo') === 'pago_pendiente'      ? 'selected' : '' }}>Pago pendiente</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todas</option>
                        <option value="no_leidas" {{ request('estado') === 'no_leidas' ? 'selected' : '' }}>No leídas</option>
                        <option value="leidas"    {{ request('estado') === 'leidas'    ? 'selected' : '' }}>Leídas</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100" style="background:#ea580c; border-color:#ea580c;">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── LISTADO ── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-list-ul me-2" style="color:#ea580c;"></i>
                Historial de Notificaciones
                <span class="badge ms-2 text-white" style="background:#ea580c;">{{ $notificaciones->total() }}</span>
            </h6>
        </div>

        <div class="card-body p-0">
            @if($notificaciones->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell-slash display-4 d-block mb-3" style="color:#d1d5db;"></i>
                    <p class="mb-1 fw-semibold">No hay notificaciones</p>
                    <p class="small">Aquí aparecerán los avisos de contratos y pagos próximos a vencer.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($notificaciones as $n)
                        <div class="list-group-item d-flex align-items-start gap-3 py-3 {{ $n->leida ? '' : 'bg-light' }}">

                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:42px;height:42px;background:rgba(0,0,0,0.05);">
                                <i class="bi {{ $n->icono }} fs-5 text-{{ $n->color }}"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold">{{ $n->titulo }}</span>
                                    @if(! $n->leida)
                                        <span class="badge rounded-pill" style="background:#ea580c;">Nueva</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-1">{{ $n->mensaje }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $n->created_at->format('d/m/Y H:i') }}
                                    </small>
                                    @if($n->contrato)
                                        <a href="{{ route('contratos.show', $n->contrato) }}" class="small fw-semibold text-decoration-none" style="color:#ea580c;">
                                            Ver contrato
                                        </a>
                                    @endif
                                    @if($n->pago)
                                        <a href="{{ route('pagos.show', $n->pago) }}" class="small fw-semibold text-decoration-none" style="color:#ea580c;">
                                            Ver pago
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex gap-1">
                                @if(! $n->leida)
                                    <form action="{{ route('notificaciones.marcarLeida', $n) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Marcar como leída">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notificaciones.destroy', $n) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                            onclick="return confirm('¿Eliminar esta notificación?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if($notificaciones->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $notificaciones->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
