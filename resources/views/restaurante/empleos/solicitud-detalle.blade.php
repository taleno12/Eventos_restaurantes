@extends('restaurante.layout')

@section('title', 'Solicitud de ' . $solicitud->nombre_completo)

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('restaurante.solicitudes.index', $solicitud->empleo) }}"
           class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Volver a solicitudes
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4" style="background:var(--card-bg) !important;">
        <div class="card-body p-4">

            {{-- Header --}}
            <div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:60px;height:60px;background:var(--primary-light);">
                        <span class="fw-bold fs-3" style="color:var(--primary);">
                            {{ strtoupper(substr($solicitud->nombre,0,1)) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0" style="color:var(--text);">{{ $solicitud->nombre_completo }}</h4>
                        <p class="mb-0" style="color:var(--muted);">
                            {{ $solicitud->edad }} años · {{ $solicitud->municipio }}
                        </p>
                        <p class="small mb-0" style="color:var(--muted);">
                            Vacante: <strong style="color:var(--text);">{{ $solicitud->empleo->titulo }}</strong>
                        </p>
                    </div>
                </div>

                @php
                    $badgeColors = [
                        'nueva'      => ['bg' => 'var(--primary)', 'text' => 'white'],
                        'vista'      => ['bg' => 'var(--badge-gray-bg)', 'text' => 'var(--badge-gray-text)'],
                        'contactado' => ['bg' => 'rgba(22,163,74,0.1)', 'text' => '#22c55e'],
                        'descartado' => ['bg' => 'rgba(239,68,68,0.1)', 'text' => '#ef4444'],
                    ];
                    $labelMap = [
                        'nueva'      => 'Nueva',
                        'vista'      => 'Vista',
                        'contactado' => 'Contactado',
                        'descartado' => 'Descartado',
                    ];
                @endphp
                <span class="badge rounded-pill fs-6 px-3"
                      style="background:{{ $badgeColors[$solicitud->estado]['bg'] }};color:{{ $badgeColors[$solicitud->estado]['text'] }};">
                    {{ $labelMap[$solicitud->estado] }}
                </span>
            </div>

            {{-- Contacto --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <p class="small mb-1" style="color:var(--muted);">Email</p>
                    @if(str_ends_with($solicitud->email, '@telefono.gastronicaragua.local'))
                        <span class="fw-semibold" style="color:var(--muted);">
                            <i class="bi bi-telephone-fill text-primary me-1"></i>Registrado por teléfono
                        </span>
                    @else
                        <a href="mailto:{{ $solicitud->email }}" class="text-decoration-none fw-semibold" style="color:var(--text);">
                            <i class="bi bi-envelope-fill text-primary me-1"></i>{{ $solicitud->email }}
                        </a>
                    @endif
                </div>
                <div class="col-md-6">
                    <p class="small mb-1" style="color:var(--muted);">Teléfono / WhatsApp</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$solicitud->telefono) }}"
                       target="_blank" class="text-decoration-none fw-semibold" style="color:var(--text);">
                        <i class="bi bi-whatsapp text-success me-1"></i>{{ $solicitud->telefono }}
                    </a>
                </div>
            </div>

            {{-- Disponibilidad --}}
            @if($solicitud->disponibilidad && count($solicitud->disponibilidad))
                <div class="mb-4">
                    <p class="small mb-2" style="color:var(--muted);">Disponibilidad</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($solicitud->disponibilidad as $d)
                            <span class="badge rounded-pill px-3 py-2" style="background:var(--primary-light);color:var(--primary);">
                                {{ ucfirst(str_replace('_',' ',$d)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Experiencia --}}
            @if($solicitud->experiencia)
                <div class="mb-4">
                    <p class="small mb-2" style="color:var(--muted);">Experiencia</p>
                    <p style="color:var(--text);line-height:1.6;white-space:pre-line;">{{ $solicitud->experiencia }}</p>
                </div>
            @endif

            {{-- Mensaje --}}
            @if($solicitud->mensaje)
                <div class="mb-4">
                    <p class="small mb-2" style="color:var(--muted);">Mensaje del candidato</p>
                    <p style="color:var(--text);line-height:1.6;white-space:pre-line;">{{ $solicitud->mensaje }}</p>
                </div>
            @endif

            {{-- CV --}}
            @if($solicitud->curriculum)
                <a href="{{ asset('storage/' . $solicitud->curriculum) }}"
                   target="_blank"
                   class="btn btn-outline-primary rounded-pill mb-4">
                    <i class="bi bi-file-earmark-person me-1"></i> Ver Currículum
                </a>
            @endif

            <p class="small mb-4" style="color:var(--muted);">
                Recibida {{ $solicitud->created_at->diffForHumans() }}
            </p>

            {{-- Acciones --}}
            <div class="d-flex gap-2 border-top pt-3" style="border-color:var(--card-border) !important;">
                <form action="{{ route('restaurante.solicitudes.estado', $solicitud) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    @foreach(['contactado' => ['success','person-check'], 'descartado' => ['danger','person-x']] as $est => [$color, $icon])
                        @if($solicitud->estado !== $est)
                            <button type="submit" name="estado" value="{{ $est }}"
                                    class="btn btn-outline-{{ $color }} rounded-pill">
                                <i class="bi bi-{{ $icon }} me-1"></i> Marcar {{ ucfirst($est) }}
                            </button>
                        @endif
                    @endforeach
                </form>

                <form action="{{ route('restaurante.solicitudes.destroy', $solicitud) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar esta solicitud?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger rounded-pill">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
