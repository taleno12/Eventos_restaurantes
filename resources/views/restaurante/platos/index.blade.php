@extends('restaurante.layout')
@section('title', 'Menú')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Menú</div>
        <div class="page-sub">Platos de {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.platos.create') }}" class="btn btn-orange">
        <i class="fas fa-plus"></i> Nuevo Plato
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

@if($platos->isEmpty())
    <div class="card">
        <div class="empty-state">
            <i class="fas fa-utensils"></i>
            <p>No tienes platos en tu menú aún.</p>
            <a href="{{ route('restaurante.platos.create') }}" class="btn btn-orange">
                <i class="fas fa-plus"></i> Añadir primer plato
            </a>
        </div>
    </div>
@else
    @foreach($platos as $categoria => $items)
    <div style="margin-bottom:28px;">
        {{-- Header categoría --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
            <span style="font-size:11px;font-weight:900;letter-spacing:0.18em;text-transform:uppercase;color:var(--orange);">
                {{ $categoria ?: 'Sin categoría' }}
            </span>
            <div style="flex:1;height:1px;background:var(--border);"></div>
            <span style="font-size:11px;color:var(--muted);">{{ $items->count() }} plato{{ $items->count() !== 1 ? 's' : '' }}</span>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
            @foreach($items as $plato)
            <div class="card" style="display:flex;flex-direction:column;overflow:hidden;transition:border-color 0.2s;"
                 onmouseover="this.style.borderColor='var(--orange)'" onmouseout="this.style.borderColor='var(--border)'">

                {{-- Imagen --}}
                <div style="position:relative;aspect-ratio:16/9;overflow:hidden;background:#1a1a1a;">
                    @if($plato->imagen)
                        <img src="{{ asset('storage/'.$plato->imagen) }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-utensils" style="font-size:32px;color:#333;"></i>
                        </div>
                    @endif
                    {{-- Badge activo/inactivo --}}
                    <div style="position:absolute;top:8px;right:8px;">
                        @if($plato->activo)
                            <span class="badge badge-green"><i class="fas fa-circle" style="font-size:7px;"></i> Activo</span>
                        @else
                            <span class="badge badge-red"><i class="fas fa-circle" style="font-size:7px;"></i> Inactivo</span>
                        @endif
                    </div>
                </div>

                {{-- Info --}}
                <div style="padding:14px;flex:1;display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                        <span style="font-weight:700;color:white;font-size:14px;">{{ $plato->nombre }}</span>
                        <span style="color:var(--orange);font-weight:800;font-size:15px;white-space:nowrap;">
                            C$ {{ number_format($plato->precio, 0) }}
                        </span>
                    </div>
                    @if($plato->descripcion)
                        <p style="font-size:12px;color:var(--muted);line-height:1.5;">
                            {{ Str::limit($plato->descripcion, 70) }}
                        </p>
                    @endif
                </div>

                {{-- Acciones --}}
                <div style="padding:10px 14px;border-top:1px solid var(--border);display:flex;gap:8px;">
                    {{-- Toggle activo --}}
                    <form method="POST" action="{{ route('restaurante.platos.toggle', $plato) }}" style="flex:1;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-ghost" style="width:100%;padding:6px;font-size:11px;">
                            @if($plato->activo)
                                <i class="fas fa-eye-slash"></i> Ocultar
                            @else
                                <i class="fas fa-eye"></i> Activar
                            @endif
                        </button>
                    </form>
                    <a href="{{ route('restaurante.platos.edit', $plato) }}" class="btn btn-ghost" style="padding:6px 12px;">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button type="button"
                            onclick="eliminarPlato('{{ route('restaurante.platos.destroy', $plato) }}')"
                            class="btn btn-danger" style="padding:6px 12px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@endif
@endsection

@section('scripts')
<script>
function eliminarPlato(url) {
    if (!confirm('¿Eliminar este plato del menú?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection