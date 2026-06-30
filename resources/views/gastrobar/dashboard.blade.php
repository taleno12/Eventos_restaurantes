@extends('gastrobar.layout')
@section('title', 'Dashboard')

@section('content')

{{-- Perfil rápido --}}
<div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:14px;padding:18px 22px;display:flex;align-items:center;gap:16px;margin-bottom:24px;box-shadow:0 1px 4px var(--shadow);">
    <div style="width:52px;height:52px;border-radius:14px;overflow:hidden;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:white;flex-shrink:0;">
        @if($gastrobar->imagen_principal)
            <img src="{{ asset('storage/' . $gastrobar->imagen_principal) }}" style="width:100%;height:100%;object-fit:cover;">
        @else
            {{ strtoupper(substr($gastrobar->nombre, 0, 1)) }}
        @endif
    </div>
    <div>
        <div style="font-size:17px;font-weight:800;color:var(--text);">{{ $gastrobar->nombre }}</div>
        <div style="font-size:12px;color:var(--muted);">{{ auth()->user()->email }}</div>
    </div>
    <span style="margin-left:auto;background:var(--primary-light);border:1px solid var(--primary-border);color:var(--primary);font-size:10px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;padding:5px 12px;border-radius:999px;">
        Gastrobar
    </span>
</div>

{{-- Header --}}
<div class="page-header">
    <div>
        <div class="page-title">Bienvenido 👋</div>
        <div class="page-sub">Gestiona tu gastrobar desde este panel. Todo lo que necesitas en un solo lugar.</div>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:28px;">
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon purple"><i class="bi bi-calendar-check-fill"></i></div>
        <div>
            <div class="metric-value">{{ $totalEventos }}</div>
            <div class="metric-label">Eventos totales</div>
        </div>
    </div>
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon blue"><i class="bi bi-briefcase-fill"></i></div>
        <div>
            <div class="metric-value">{{ $totalEmpleos }}</div>
            <div class="metric-label">Empleos activos</div>
        </div>
    </div>
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon green"><i class="bi bi-images"></i></div>
        <div>
            <div class="metric-value">{{ count($gastrobar->galeria ?? []) }}</div>
            <div class="metric-label">Fotos en galería</div>
        </div>
    </div>
</div>

{{-- Acciones rápidas --}}
<div style="font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Acciones rápidas</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;margin-bottom:28px;">
    <a href="{{ route('gastrobar.eventos.create') }}"
       style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:16px;text-decoration:none;color:var(--text);display:flex;flex-direction:column;align-items:flex-start;gap:10px;transition:all 0.2s;font-weight:700;font-size:13px;"
       onmouseover="this.style.borderColor='var(--primary)';this.style.background='var(--primary-light)'"
       onmouseout="this.style.borderColor='var(--card-border)';this.style.background='var(--card-bg)'">
        <div style="width:34px;height:34px;background:var(--primary-light);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:15px;">
            <i class="bi bi-plus-circle-fill"></i>
        </div>
        Nuevo evento
    </a>
    <a href="{{ route('gastrobar.empleos.create') }}"
       style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:16px;text-decoration:none;color:var(--text);display:flex;flex-direction:column;align-items:flex-start;gap:10px;transition:all 0.2s;font-weight:700;font-size:13px;"
       onmouseover="this.style.borderColor='var(--primary)';this.style.background='var(--primary-light)'"
       onmouseout="this.style.borderColor='var(--card-border)';this.style.background='var(--card-bg)'">
        <div style="width:34px;height:34px;background:var(--primary-light);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:15px;">
            <i class="bi bi-plus-circle-fill"></i>
        </div>
        Nueva oferta
    </a>
    <a href="{{ route('gastrobar.galeria.index') }}"
       style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:16px;text-decoration:none;color:var(--text);display:flex;flex-direction:column;align-items:flex-start;gap:10px;transition:all 0.2s;font-weight:700;font-size:13px;"
       onmouseover="this.style.borderColor='var(--primary)';this.style.background='var(--primary-light)'"
       onmouseout="this.style.borderColor='var(--card-border)';this.style.background='var(--card-bg)'">
        <div style="width:34px;height:34px;background:var(--primary-light);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:15px;">
            <i class="bi bi-camera-fill"></i>
        </div>
        Subir fotos
    </a>
    <a href="{{ route('gastrobar.perfil.edit') }}"
       style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:16px;text-decoration:none;color:var(--text);display:flex;flex-direction:column;align-items:flex-start;gap:10px;transition:all 0.2s;font-weight:700;font-size:13px;"
       onmouseover="this.style.borderColor='var(--primary)';this.style.background='var(--primary-light)'"
       onmouseout="this.style.borderColor='var(--card-border)';this.style.background='var(--card-bg)'">
        <div style="width:34px;height:34px;background:var(--primary-light);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:15px;">
            <i class="bi bi-pencil-fill"></i>
        </div>
        Editar perfil
    </a>
</div>

{{-- Próximos eventos --}}
<div style="font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Próximos eventos</div>

@forelse($eventosProximos as $evento)
    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:14px;margin-bottom:8px;transition:border-color 0.2s;"
         onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--card-border)'">
        <div style="background:var(--primary-light);border:1px solid var(--primary-border);border-radius:10px;padding:8px 12px;text-align:center;flex-shrink:0;min-width:50px;">
            <div style="font-size:20px;font-weight:800;color:var(--primary);line-height:1;">{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d') }}</div>
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('M') }}</div>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-weight:700;font-size:14px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $evento->titulo }}</div>
            <div style="font-size:12px;color:var(--muted);">C$ {{ number_format($evento->precio, 0) }}</div>
        </div>
        <a href="{{ route('gastrobar.eventos.edit', $evento) }}"
           style="font-size:12px;color:var(--muted);text-decoration:none;white-space:nowrap;font-weight:600;">
            Ver <i class="bi bi-chevron-right" style="font-size:10px;"></i>
        </a>
    </div>
@empty
    <div style="background:var(--card-bg);border:1px dashed var(--card-border);border-radius:12px;padding:32px;text-align:center;color:var(--muted);">
        <i class="bi bi-calendar-x" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.4;"></i>
        <p style="font-size:13px;margin-bottom:14px;">No tienes eventos próximos.<br>¡Crea tu primer evento!</p>
        <a href="{{ route('gastrobar.eventos.create') }}" class="btn-primary-panel">
            <i class="bi bi-plus"></i> Crear evento
        </a>
    </div>
@endforelse

@endsection
