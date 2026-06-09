@extends('restaurante.layout')
@section('title', 'Nuevo Plato')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Nuevo Plato</div>
        <div class="page-sub">Añade un plato al menú de {{ $restaurante->nombre }}</div>
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

<form method="POST" action="{{ route('restaurante.platos.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del plato</div>

                <div class="form-group">
                    <label class="form-label">Nombre del plato *</label>
                    <input type="text" name="nombre" class="form-control"
                           placeholder="Ej: Ceviche de camarón, Arroz con pollo..."
                           value="{{ old('nombre') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción <span style="color:#444;font-weight:500;">(opcional)</span></label>
                    <textarea name="descripcion" class="form-control"
                              placeholder="Ingredientes, preparación, alérgenos...">{{ old('descripcion') }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Precio (C$) *</label>
                        <input type="number" name="precio" class="form-control"
                               placeholder="0" min="0" step="0.01"
                               value="{{ old('precio') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoría</label>
                        <input type="text" name="categoria" class="form-control"
                               placeholder="Ej: Entradas, Platos fuertes..."
                               value="{{ old('categoria') }}"
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
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Foto del plato</div>
                <div id="imagen-drop" style="border:2px dashed var(--border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;">
                    <img id="imagen-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                    <div id="imagen-placeholder" style="text-align:center;padding:20px;">
                        <i class="fas fa-camera" style="font-size:28px;color:#333;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:12px;color:var(--muted);">Haz clic para subir foto</p>
                    </div>
                    <input type="file" name="imagen" id="imagen-input" accept="image/*"
                           style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                </div>
                <p style="font-size:10px;color:#444;margin-top:8px;">JPG, PNG, WEBP — máx. 2 MB</p>
            </div>

            <div class="card card-body">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Estado</div>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:16px;">
                    <input type="hidden" name="activo" value="0">
                    <input type="checkbox" name="activo" value="1"
                           {{ old('activo', '1') == '1' ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--orange);cursor:pointer;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:white;">Disponible</div>
                        <div style="font-size:11px;color:var(--muted);">Visible en el menú público</div>
                    </div>
                </label>
                <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-plus"></i> Añadir al Menú
                </button>
                <a href="{{ route('restaurante.platos.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px;">
                    Cancelar
                </a>
            </div>

        </div>
    </div>
</form>
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
</script>
@endsection