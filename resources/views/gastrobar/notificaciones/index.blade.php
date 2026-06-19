@extends('gastrobar.layout')
@section('title', 'Notificaciones')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Notificaciones</div>
        <div class="page-sub">
            @if($noLeidas > 0)
                Tienes <strong style="color:var(--primary);">{{ $noLeidas }}</strong> notificación{{ $noLeidas !== 1 ? 'es' : '' }} sin leer
            @else
                Estás al día con todas tus notificaciones
            @endif
        </div>
    </div>
    @if($noLeidas > 0)
    <form method="POST" action="{{ route('gastrobar.notificaciones.leer-todas') }}">
        @csrf
        <button type="submit" class="btn-secondary-panel">
            <i class="bi bi-check2-all"></i> Marcar todas como leídas
        </button>
    </form>
    @endif
</div>

@if(session('success'))
    <div class="panel-alert panel-alert-success">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

@if($notificaciones->count() > 0)
    @foreach($notificaciones as $notif)
    @php
        $estilos = [
            'orange' => ['bg' => 'rgba(245,158,11,0.1)', 'border' => 'rgba(245,158,11,0.2)', 'color' => '#f59e0b'],
            'red'    => ['bg' => 'rgba(239,68,68,0.1)', 'border' => 'rgba(239,68,68,0.2)', 'color' => '#ef4444'],
            'blue'   => ['bg' => 'var(--primary-light)', 'border' => 'var(--primary-border)', 'color' => 'var(--primary)'],
            'green'  => ['bg' => 'rgba(22,163,74,0.1)', 'border' => 'rgba(22,163,74,0.2)', 'color' => '#22c55e'],
        ];
        $estilo = $estilos[$notif->color] ?? $estilos['orange'];

        $urlValida = $notif->url && (
            str_starts_with($notif->url, '/') ||
            str_starts_with($notif->url, 'http://') ||
            str_starts_with($notif->url, 'https://')
        );
    @endphp

    {{-- Tarjeta clickeable --}}
    <div onclick="abrirModal('modal-{{ $notif->id }}')"
         style="background:var(--card-bg);border:1px solid {{ $notif->leida ? 'var(--card-border)' : $estilo['border'] }};border-radius:14px;padding:16px 20px;margin-bottom:10px;display:flex;align-items:flex-start;gap:14px;transition:all 0.2s;cursor:pointer;{{ !$notif->leida ? 'box-shadow:0 2px 12px var(--shadow);' : '' }}"
         onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 16px var(--shadow)'"
         onmouseout="this.style.transform='';this.style.boxShadow='{{ !$notif->leida ? '0 2px 12px var(--shadow)' : 'none' }}'">

        {{-- Ícono --}}
        <div style="width:40px;height:40px;border-radius:10px;background:{{ $estilo['bg'] }};border:1px solid {{ $estilo['border'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi {{ $notif->icono }}" style="color:{{ $estilo['color'] }};font-size:16px;"></i>
        </div>

        {{-- Contenido resumido --}}
        <div style="flex:1;min-width:0;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:4px;">
                <span style="font-size:14px;font-weight:{{ $notif->leida ? '600' : '800' }};color:var(--text);">
                    {{ $notif->titulo }}
                </span>
                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                    @if(!$notif->leida)
                        <span style="width:8px;height:8px;border-radius:50%;background:{{ $estilo['color'] }};display:inline-block;"></span>
                    @endif
                    <span style="font-size:11px;color:var(--muted);font-weight:600;">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <p style="font-size:13px;color:var(--muted);line-height:1.6;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:600px;">
                {{ $notif->mensaje }}
            </p>
        </div>

        <div style="flex-shrink:0;color:var(--muted);font-size:13px;align-self:center;">
            <i class="bi bi-chevron-right"></i>
        </div>
    </div>

    {{-- Modal de detalle --}}
    <div id="modal-{{ $notif->id }}"
         style="display:none;position:fixed;inset:0;background:var(--overlay-bg);z-index:9999;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px);"
         onclick="if(event.target===this)cerrarModal('modal-{{ $notif->id }}')">
        <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:18px;padding:28px;max-width:520px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,0.3);animation:slideUp 0.2s ease;">

            {{-- Header del modal --}}
            <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
                <div style="width:48px;height:48px;border-radius:12px;background:{{ $estilo['bg'] }};border:1px solid {{ $estilo['border'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi {{ $notif->icono }}" style="color:{{ $estilo['color'] }};font-size:20px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:800;color:var(--text);margin-bottom:4px;">{{ $notif->titulo }}</div>
                    <div style="font-size:12px;color:var(--muted);font-weight:600;">
                        <i class="bi bi-clock"></i> {{ $notif->created_at->format('d/m/Y H:i') }} · {{ $notif->created_at->diffForHumans() }}
                    </div>
                </div>
                <button onclick="cerrarModal('modal-{{ $notif->id }}')"
                        style="background:var(--hover-bg);border:1px solid var(--card-border);color:var(--muted);width:32px;height:32px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px;">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            {{-- Separador --}}
            <div style="height:1px;background:var(--card-border);margin-bottom:20px;"></div>

            {{-- Mensaje completo --}}
            <div style="font-size:14px;color:var(--text);line-height:1.7;margin-bottom:24px;white-space:pre-wrap;">{{ $notif->mensaje }}</div>

            {{-- Acciones --}}
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                @if($urlValida)
                    <a href="{{ $notif->url }}"
                       style="background:{{ $estilo['color'] }};color:white;border:none;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
                        <i class="bi bi-arrow-right-circle-fill"></i> Ver detalle
                    </a>
                @endif
                @if(!$notif->leida)
                    <form method="POST" action="{{ route('gastrobar.notificaciones.leer', $notif) }}" style="display:inline;">
                        @csrf
                        <button type="submit"
                                style="background:var(--hover-bg);color:var(--muted);border:1px solid var(--card-border);border-radius:10px;padding:9px 16px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:7px;">
                            <i class="bi bi-check2"></i> Marcar como leída
                        </button>
                    </form>
                @endif
                <button onclick="cerrarModal('modal-{{ $notif->id }}')"
                        style="background:none;color:var(--muted);border:none;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;padding:9px 4px;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    @endforeach

    <div style="margin-top:16px;">{{ $notificaciones->links() }}</div>

@else
    <div class="panel-card" style="background:var(--card-bg) !important;">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-bell-slash" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.25;"></i>
                <p style="font-size:14px;font-weight:700;margin-bottom:6px;color:var(--text);">Sin notificaciones</p>
                <p style="font-size:13px;color:var(--muted);">Aquí aparecerán los avisos importantes sobre tu membresía y cuenta.</p>
            </div>
        </div>
    </div>
@endif

@endsection

@section('scripts')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
<script>
    function abrirModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function cerrarModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach(m => {
                m.style.display = 'none';
            });
            document.body.style.overflow = '';
        }
    });
</script>
@endsection