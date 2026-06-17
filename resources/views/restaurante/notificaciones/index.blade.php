@extends('restaurante.layout')
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
    <form method="POST" action="{{ route('restaurante.notificaciones.leer-todas') }}">
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
            'orange' => ['bg' => '#fff7ed', 'border' => '#fed7aa', 'color' => '#c2410c'],
            'red'    => ['bg' => '#fef2f2', 'border' => '#fecaca', 'color' => '#dc2626'],
            'blue'   => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'color' => '#1d4ed8'],
            'green'  => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'color' => '#15803d'],
        ];
        $estilo = $estilos[$notif->color] ?? $estilos['orange'];
    @endphp
    <div style="background:{{ $notif->leida ? 'var(--card-bg)' : 'white' }};border:1px solid {{ $notif->leida ? 'var(--card-border)' : $estilo['border'] }};border-radius:14px;padding:16px 20px;margin-bottom:10px;display:flex;align-items:flex-start;gap:14px;transition:all 0.2s;{{ !$notif->leida ? 'box-shadow:0 2px 12px rgba(0,0,0,0.06);' : '' }}">

        {{-- Ícono --}}
        <div style="width:40px;height:40px;border-radius:10px;background:{{ $estilo['bg'] }};border:1px solid {{ $estilo['border'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi {{ $notif->icono }}" style="color:{{ $estilo['color'] }};font-size:16px;"></i>
        </div>

        {{-- Contenido --}}
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
            <p style="font-size:13px;color:var(--muted);line-height:1.6;margin:0 0 10px;">{{ $notif->mensaje }}</p>
            <div style="display:flex;align-items:center;gap:10px;">
                @if($notif->url)
                    <a href="{{ $notif->url }}"
                       style="font-size:12px;font-weight:700;color:{{ $estilo['color'] }};text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                        <i class="bi bi-arrow-right-circle-fill"></i> Ir a soporte
                    </a>
                @endif
                @if(!$notif->leida)
                    <form method="POST" action="{{ route('restaurante.notificaciones.leer', $notif) }}" style="display:inline;">
                        @csrf
                        <button type="submit"
                                style="font-size:12px;font-weight:600;color:var(--muted);background:none;border:none;cursor:pointer;font-family:inherit;padding:0;display:inline-flex;align-items:center;gap:4px;">
                            <i class="bi bi-check2"></i> Marcar como leída
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <div style="margin-top:16px;">{{ $notificaciones->links() }}</div>

@else
    <div class="panel-card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-bell-slash" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.25;"></i>
                <p style="font-size:14px;font-weight:700;margin-bottom:6px;">Sin notificaciones</p>
                <p style="font-size:13px;color:var(--muted);">Aquí aparecerán los avisos importantes sobre tu membresía y cuenta.</p>
            </div>
        </div>
    </div>
@endif
@endsection