@extends('restaurante.layout')

@section('title', 'Solicitudes — ' . $empleo->titulo)

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('restaurante.empleos.index') }}"
                   class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Mis Empleos
                </a>
            </div>
            <h4 class="fw-bold mb-0 mt-2" style="color:var(--text);">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Solicitudes recibidas
            </h4>
            <p class="small mb-0" style="color:var(--muted);">Vacante: <strong style="color:var(--text);">{{ $empleo->titulo }}</strong></p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge rounded-pill fs-6 px-3" style="background:var(--primary);color:white;">
                {{ $solicitudes->count() }} {{ $solicitudes->count() === 1 ? 'solicitud' : 'solicitudes' }}
            </span>
        </div>
    </div>

    @if($solicitudes->isEmpty())
        {{-- Estado vacío --}}
        <div class="card border-0 shadow-sm rounded-4 text-center py-5" style="background:var(--card-bg) !important;">
            <div class="card-body">
                <div class="mb-3">
                    <span style="font-size:3rem;">📭</span>
                </div>
                <h5 class="fw-bold" style="color:var(--text);">Aún no hay solicitudes</h5>
                <p class="small" style="color:var(--muted);">Cuando alguien aplique a <strong style="color:var(--text);">{{ $empleo->titulo }}</strong> aparecerá aquí.</p>
            </div>
        </div>
    @else
        {{-- Filtros rápidos --}}
        <div class="d-flex gap-2 flex-wrap mb-3">
            <button class="btn btn-sm btn-outline-secondary rounded-pill filtro-btn active" data-estado="todos" style="color:var(--text);border-color:var(--card-border);">
                Todos <span class="badge ms-1" style="background:var(--muted);color:var(--card-bg);">{{ $solicitudes->count() }}</span>
            </button>
            @foreach(['vista' => ['label'=>'Vistas','color'=>'secondary'], 'contactado' => ['label'=>'Contactados','color'=>'success'], 'descartado' => ['label'=>'Descartados','color'=>'danger']] as $est => $cfg)
                @php $cnt = $solicitudes->where('estado', $est)->count(); @endphp
                @if($cnt > 0)
                    <button class="btn btn-sm btn-outline-{{ $cfg['color'] }} rounded-pill filtro-btn" data-estado="{{ $est }}">
                        {{ $cfg['label'] }} <span class="badge bg-{{ $cfg['color'] }} ms-1">{{ $cnt }}</span>
                    </button>
                @endif
            @endforeach
        </div>

        {{-- Tarjetas de solicitudes --}}
        <div class="row g-3" id="solicitudes-grid">
            @foreach($solicitudes as $sol)
            <div class="col-12 col-md-6 col-xl-4 solicitud-card" data-estado="{{ $sol->estado }}">
                <div class="card border-0 shadow-sm rounded-4 h-100" style="background:var(--card-bg) !important;">
                    <div class="card-body p-4">

                        {{-- Header candidato --}}
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:46px;height:46px;background:var(--primary-light);">
                                    <span class="fw-bold fs-5" style="color:var(--primary);">{{ strtoupper(substr($sol->nombre,0,1)) }}</span>
                                </div>
                                <div>
                                    <p class="fw-bold mb-0" style="color:var(--text);">{{ $sol->nombre_completo }}</p>
                                    <p class="small mb-0" style="color:var(--muted);">{{ $sol->edad }} años · {{ $sol->municipio }}</p>
                                </div>
                            </div>
                            {{-- Badge estado --}}
                            @php
                                $badgeMap = [
                                    'nueva'      => 'primary',
                                    'vista'      => 'secondary',
                                    'contactado' => 'success',
                                    'descartado' => 'danger',
                                ];
                                $labelMap = [
                                    'nueva'      => 'Nueva',
                                    'vista'      => 'Vista',
                                    'contactado' => 'Contactado',
                                    'descartado' => 'Descartado',
                                ];
                                $badgeColors = [
                                    'nueva'      => ['bg' => 'var(--primary)', 'text' => 'white'],
                                    'vista'      => ['bg' => 'var(--badge-gray-bg)', 'text' => 'var(--badge-gray-text)'],
                                    'contactado' => ['bg' => 'rgba(22,163,74,0.1)', 'text' => '#22c55e'],
                                    'descartado' => ['bg' => 'rgba(239,68,68,0.1)', 'text' => '#ef4444'],
                                ];
                            @endphp
                            <span class="badge rounded-pill" style="background:{{ $badgeColors[$sol->estado]['bg'] }};color:{{ $badgeColors[$sol->estado]['text'] }};border:1px solid {{ $sol->estado === 'contactado' ? 'rgba(22,163,74,0.2)' : ($sol->estado === 'descartado' ? 'rgba(239,68,68,0.2)' : 'var(--card-border)') }};">
                                {{ $labelMap[$sol->estado] }}
                            </span>
                        </div>

                        {{-- Contacto --}}
                        <div class="d-flex flex-column gap-1 mb-3">
                            <a href="mailto:{{ $sol->email }}" class="text-decoration-none small" style="color:var(--muted);">
                                <i class="bi bi-envelope-fill text-primary me-1"></i>{{ $sol->email }}
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$sol->telefono) }}"
                               target="_blank" class="text-decoration-none small" style="color:var(--muted);">
                                <i class="bi bi-whatsapp text-success me-1"></i>{{ $sol->telefono }}
                            </a>
                        </div>

                        {{-- Experiencia --}}
                        @if($sol->experiencia)
                            <p class="small mb-3" style="line-height:1.5;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;color:var(--muted);">
                                {{ $sol->experiencia }}
                            </p>
                        @endif

                        {{-- CV --}}
                        @if($sol->curriculum)
                            <a href="{{ asset('storage/' . $sol->curriculum) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary rounded-pill mb-3 w-100">
                                <i class="bi bi-file-earmark-person me-1"></i> Ver Currículum
                            </a>
                        @endif

                        {{-- Acciones --}}
                        <div class="d-flex gap-2 justify-content-between align-items-center border-top pt-3" style="border-color:var(--card-border) !important;">
                            {{-- Cambiar estado --}}
                            <form action="{{ route('restaurante.solicitudes.estado', $sol) }}" method="POST" class="d-flex gap-1 flex-wrap">
                                @csrf
                                @method('PATCH')
                                @foreach(['contactado' => ['success','person-check'], 'descartado' => ['danger','person-x']] as $est => [$color, $icon])
                                    @if($sol->estado !== $est)
                                        <button type="submit" name="estado" value="{{ $est }}"
                                                class="btn btn-sm btn-outline-{{ $color }} rounded-pill"
                                                title="{{ ucfirst($est) }}">
                                            <i class="bi bi-{{ $icon }}"></i>
                                        </button>
                                    @endif
                                @endforeach
                            </form>

                            {{-- Eliminar --}}
                            <form action="{{ route('restaurante.solicitudes.destroy', $sol) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta solicitud?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>

                        <p style="font-size:11px;margin-top:8px;margin-bottom:0;color:var(--muted);">
                            Recibida {{ $sol->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>

<script>
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const estado = this.dataset.estado;
        document.querySelectorAll('.solicitud-card').forEach(card => {
            card.style.display = (estado === 'todos' || card.dataset.estado === estado) ? '' : 'none';
        });
    });
});
</script>
@endsection
