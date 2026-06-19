@extends('gastrobar.layout')
@section('title', 'Reseñas')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Reseñas</div>
        <div class="page-sub">Lo que tus clientes opinan de {{ $gastrobar->nombre }}</div>
    </div>
</div>

{{-- Resumen --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon orange"><i class="bi bi-star-fill"></i></div>
        <div>
            <div class="metric-value">{{ $avgRating ?? '—' }}</div>
            <div class="metric-label">Calificación promedio</div>
        </div>
    </div>
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon blue"><i class="bi bi-chat-square-text-fill"></i></div>
        <div>
            <div class="metric-value">{{ $totalReviews }}</div>
            <div class="metric-label">Reseñas totales</div>
        </div>
    </div>
    <div class="metric-card" style="display:flex;align-items:center;gap:14px;">
        <div class="metric-icon green"><i class="bi bi-emoji-smile-fill"></i></div>
        <div>
            @php $positivas = ($conteoEstrellas[5]['cantidad'] ?? 0) + ($conteoEstrellas[4]['cantidad'] ?? 0); @endphp
            <div class="metric-value">{{ $totalReviews > 0 ? round(($positivas / $totalReviews) * 100) : 0 }}%</div>
            <div class="metric-label">Reseñas positivas (4-5★)</div>
        </div>
    </div>
</div>

@if($totalReviews > 0)
{{-- Distribución por estrellas --}}
<div class="panel-card" style="margin-bottom:20px;">
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:24px;">
            <div style="text-align:center;min-width:80px;">
                <div style="font-size:42px;font-weight:900;color:var(--primary);line-height:1;">{{ $avgRating }}</div>
                <div
                    style="font-size:11px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-top:4px;">
                    de 5</div>
                <div style="margin-top:6px;">
                    @for($i = 1; $i <= 5; $i++) <span
                        style="color:{{ $i <= round($avgRating) ? '#f59e0b' : '#e7e5e4' }};font-size:14px;">★</span>
                        @endfor
                </div>
            </div>
            <div style="flex:1;">
                @foreach($conteoEstrellas as $estrella => $data)
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <span style="font-size:12px;font-weight:700;color:var(--muted);min-width:14px;">{{ $estrella
                        }}</span>
                    <i class="bi bi-star-fill" style="font-size:11px;color:#f59e0b;"></i>
                    <div style="flex:1;height:8px;background:#f0f1f4;border-radius:999px;overflow:hidden;">
                        <div
                            style="height:100%;background:var(--primary);border-radius:999px;width:{{ $data['porcentaje'] }}%;transition:width 0.8s ease;">
                        </div>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:var(--muted);min-width:28px;text-align:right;">{{
                        $data['cantidad'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Lista de reseñas --}}
<div class="panel-card">
    <div class="card-header">Todas las reseñas — {{ $totalReviews }}</div>
    <div class="card-body">
        @foreach($reviews as $review)
        <div style="border-bottom:1px solid var(--card-border);padding-bottom:18px;margin-bottom:18px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div
                        style="width:38px;height:38px;border-radius:50%;overflow:hidden;flex-shrink:0;background:#fff7ed;display:flex;align-items:center;justify-content:center;">
                        @if($review->user->avatar)
                        <img src="{{ $review->user->avatar }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <span style="font-size:15px;font-weight:800;color:var(--primary);">{{
                            strtoupper(substr($review->user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:14px;font-weight:700;color:var(--text);margin:0;">{{ $review->user->name }}
                        </p>
                        <p style="font-size:11px;color:var(--muted);margin:0;">{{ $review->created_at->diffForHumans()
                            }}</p>
                    </div>
                </div>
                <div style="display:flex;gap:2px;flex-shrink:0;">
                    @for($i = 1; $i <= 5; $i++) <span
                        style="color:{{ $i <= $review->rating ? '#f59e0b' : '#e7e5e4' }};font-size:15px;">★</span>
                        @endfor
                </div>
            </div>

            @if($review->title)
            <p style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:4px;">{{ $review->title }}</p>
            @endif
            @if($review->body)
            <p style="font-size:13.5px;color:var(--muted);line-height:1.7;margin:0;">{{ $review->body }}</p>
            @endif

            @if($review->rating <= 3) <div
                style="margin-top:10px;display:inline-flex;align-items:center;gap:6px;background:#fff7ed;border:1px solid #fed7aa;color:#c2410c;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;">
                <i class="bi bi-exclamation-circle-fill"></i> Considera contactar a este cliente para mejorar su
                experiencia
        </div>
        @endif
    </div>
    @endforeach

    <div>{{ $reviews->links() }}</div>
</div>
</div>

@else
<div class="panel-card">
    <div class="card-body">
        <div class="empty-state">
            <i class="bi bi-chat-square-heart"
                style="font-size:40px;display:block;margin-bottom:12px;opacity:0.25;"></i>
            <p style="font-size:14px;font-weight:700;margin-bottom:6px;">Aún no tienes reseñas</p>
            <p style="font-size:13px;color:var(--muted);">Cuando los clientes visiten tu página y dejen su opinión,
                aparecerán aquí.</p>
        </div>
    </div>
</div>
@endif

@endsection