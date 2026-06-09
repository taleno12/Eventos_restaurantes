@extends('restaurante.layout')
@section('title', 'Editar Plato')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Editar Plato</div>
        <div class="page-sub">{{ $plato->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.platos.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:6px 0 0 16px;padding:0;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('restaurante.platos.update', $plato) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del plato</div>

                <div class="form-group">
                    <label class="form-label">Nombre del plato *</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $plato->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ old('descripcion', $plato->descripcion) }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Precio (C$) *</label>
                        <input type="number" name="precio" class="form-control"
                               min="0" step="0.01"
                               value="{{ old('precio', $plato->precio) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoría</label>
                        <input type="text" name="categoria" class="form-control"
                               value="{{ old('categoria', $plato->categoria) }}"
                               list="categorias-sugeridas">
                        <datalist id="categorias-sugeridas">
                            <option value="Entradas">
                            <option value="Platos fuertes">
                            <option value="Mariscos">
                            <option value="Carnes">
                            <option value="Pastas">
                            <option value="Ensaladas">
                            <option value="Bebidas">
                            <option value="Postres">
                            <option value="Desayunos">
                        </datalist>
                    </div>
                </div>
            </div>
        </div>

        {{-- Derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Foto del plato</div>

                @if($plato->imagen)
                <div style="margin-bottom:10px;">
                    <p style="font-size:10px;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.1em;">Foto actual</p>
                    <img src="{{ asset('storage/'.$plato->imagen) }}"
                         style="width:100%;border-radius:10px;aspect-ratio:1;object-fit:cover;border:1px solid var(--border);">
                </div>
                @endif

                <div style="border:2px dashed var(--border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
                    <img id="imagen-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                    <div id="imagen-placeholder" style="text-align:center;padding:16px;">
                        <i class="fas fa-camera" style="font-size:22px;color:#333;display:block;margin-bottom:6px;"></i>
                        <p style="font-size:11px;color:var(--muted);">{{ $plato->imagen ? 'Cambiar foto' : 'Subir foto' }}</p>
                    </div>
                    <input type="file" name="imagen" id="imagen-input" accept="image/*"
                           style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                </div>
                <p style="font-size:10px;color:#444;margin-top:6px;">Deja vacío para mantener la foto actual</p>
            </div>

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Estado</div>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:16px;">
                    <input type="hidden" name="activo" value="0">
                    <input type="checkbox" name="activo" value="1"
                           {{ old('activo', $plato->activo) ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--orange);cursor:pointer;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:white;">Disponible</div>
                        <div style="font-size:11px;color:var(--muted);">Visible en el menú público</div>
                    </div>
                </label>
                <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('restaurante.platos.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px;">
                    Cancelar
                </a>
            </div>

        </div>
    </div>
</form>

{{-- Zona de peligro FUERA del form --}}
<div style="display:flex;justify-content:flex-end;margin-top:16px;">
    <div class="card card-body" style="border-color:#e74c3c22;width:300px;">
        <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:#e74c3c55;margin-bottom:12px;">Zona de peligro</div>
        <button type="button"
                onclick="eliminarPlato('{{ route('restaurante.platos.destroy', $plato) }}')"
                class="btn btn-danger" style="width:100%;justify-content:center;">
            <i class="fas fa-trash"></i> Eliminar Plato
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('imagen-input').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('imagen-preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('imagen-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});

function eliminarPlato(url) {
    if (!confirm('¿Eliminar este plato del menú? Esta acción no se puede deshacer.')) return;
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