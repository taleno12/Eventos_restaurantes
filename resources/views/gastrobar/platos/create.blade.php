@extends('gastrobar.layout')
@section('title', 'Nuevo Plato')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title" style="color:var(--text);">Nuevo Plato</div>
        <div class="page-sub" style="color:var(--muted);">Añade un plato al menú de {{ $gastrobar->nombre }}</div>
    </div>
    <a href="{{ route('gastrobar.platos.index') }}" class="btn-secondary-panel">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="panel-alert panel-alert-error">
        <i class="bi bi-exclamation-circle-fill fs-5"></i>
        <ul style="margin:6px 0 0 16px;padding:0;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('gastrobar.platos.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Izquierda --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="panel-card">
                <div class="card-body">
                    <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del plato</div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:var(--text);">Nombre del plato <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nombre" class="form-control"
                               placeholder="Ej: Ceviche de camarón, Arroz con pollo..."
                               value="{{ old('nombre') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:var(--text);">Descripción <span style="color:var(--muted);font-weight:500;">(opcional)</span></label>
                        <textarea name="descripcion" class="form-control" rows="3"
                                  placeholder="Ingredientes, preparación, alérgenos...">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold small" style="color:var(--text);">Precio (C$) <span style="color:#ef4444;">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--input-bg) !important;color:var(--muted);border-color:var(--input-border) !important;">C$</span>
                                <input type="number" name="precio" class="form-control"
                                       placeholder="0" min="0" step="0.01"
                                       value="{{ old('precio') }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold small" style="color:var(--text);">Categoría <span style="color:var(--muted);font-weight:500;">(opcional)</span></label>
                            <select name="categoria" class="form-select">
                                <option value="">Sin categoría</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->nombre }}" {{ old('categoria') === $cat->nombre ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @if($categorias->isEmpty())
                                <div class="form-text" style="font-size:11px;">
                                    No tienes categorías aún. <a href="{{ route('gastrobar.categorias.index') }}">Crea una aquí</a>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Opciones --}}
            <div class="panel-card">
                <div class="card-body">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);">
                            Opciones del plato
                        </div>
                        <button type="button" class="btn-secondary-panel" style="font-size:12px;padding:6px 12px;" id="btn-agregar-opcion">
                            + Agregar opción
                        </button>
                    </div>
                    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;">
                        Ej: Tamaño (Individual / Familiar), Proteína (Pollo / Res), Extras (Con papas)
                    </p>
                    <div id="lista-opciones"></div>
                </div>
            </div>
        </div>

        {{-- Derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="panel-card">
                <div class="card-body">
                    <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Foto del plato</div>
                    <div id="imagen-drop" style="border:2px dashed var(--input-border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;">
                        <img id="imagen-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                        <div id="imagen-placeholder" style="text-align:center;padding:20px;">
                            <i class="bi bi-camera" style="font-size:28px;color:var(--muted);display:block;margin-bottom:8px;"></i>
                            <p style="font-size:12px;color:var(--muted);">Haz clic para subir foto</p>
                        </div>
                        <input type="file" name="imagen" id="imagen-input" accept="image/*"
                               style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    </div>
                    <p style="font-size:10px;color:var(--muted);margin-top:8px;">JPG, PNG, WEBP — máx. 2 MB</p>
                </div>
            </div>

            <div class="panel-card">
                <div class="card-body">
                    <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Estado</div>
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:16px;">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1"
                               {{ old('activo', '1') == '1' ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--primary);cursor:pointer;">
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--text);">Disponible</div>
                            <div style="font-size:11px;color:var(--muted);">Visible en el menú público</div>
                        </div>
                    </label>
                    <button type="submit" class="btn-primary-panel" style="width:100%;justify-content:center;padding:12px;">
                        <i class="bi bi-plus-lg"></i> Añadir al Menú
                    </button>
                    <a href="{{ route('gastrobar.platos.index') }}" class="btn-secondary-panel" style="width:100%;justify-content:center;margin-top:10px;">
                        Cancelar
                    </a>
                </div>
            </div>

        </div>
    </div>
</form>

<template id="tpl-opcion">
    <div class="opcion-item border rounded-3 p-3 mb-3" style="background:var(--hover-bg);border-color:var(--card-border) !important;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold small" style="color:var(--text);">Opción #<span class="num-opcion"></span></span>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-eliminar-opcion">Eliminar</button>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-12 col-sm-5">
                <label class="form-label small fw-semibold" style="color:var(--text);">Nombre</label>
                <input type="text" name="" class="form-control form-control-sm inp-nombre" placeholder="Ej: Tamaño...">
            </div>
            <div class="col-6 col-sm-4">
                <label class="form-label small fw-semibold" style="color:var(--text);">Tipo</label>
                <select name="" class="form-select form-select-sm inp-tipo">
                    <option value="radio">Elige uno</option>
                    <option value="checkbox">Elige varios</option>
                </select>
            </div>
            <div class="col-6 col-sm-3 d-flex align-items-end pb-1">
                <div class="form-check">
                    <input type="checkbox" name="" value="1" class="form-check-input inp-requerido">
                    <label class="form-check-label small" style="color:var(--text);">Requerido</label>
                </div>
            </div>
        </div>
        <div class="valores-lista mb-2"></div>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-agregar-valor">
            + Agregar valor
        </button>
    </div>
</template>

<template id="tpl-valor">
    <div class="valor-item d-flex gap-2 align-items-center mb-2">
        <input type="text" name="" class="form-control form-control-sm inp-valor" placeholder="Ej: Individual, Familiar...">
        <div class="input-group input-group-sm" style="max-width:140px;">
            <span class="input-group-text" style="background:var(--input-bg);color:var(--muted);border-color:var(--input-border);">C$</span>
            <input type="number" name="" class="form-control form-control-sm inp-precio" placeholder="0" min="0" step="0.01" value="0">
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill btn-eliminar-valor">
            <i class="bi bi-x"></i>
        </button>
    </div>
</template>
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

(function () {
    let opcionIdx = 0;

    function renumerar() {
        document.querySelectorAll('#lista-opciones .opcion-item').forEach((el, i) => {
            el.querySelector('.num-opcion').textContent = i + 1;
        });
    }

    function agregarValor(opcionEl, oi) {
        const vi  = opcionEl.querySelectorAll('.valor-item').length;
        const tpl = document.getElementById('tpl-valor').content.cloneNode(true);
        const item = tpl.querySelector('.valor-item');
        item.querySelector('.inp-valor').name  = `opciones[${oi}][valores][${vi}][valor]`;
        item.querySelector('.inp-precio').name = `opciones[${oi}][valores][${vi}][precio_extra]`;
        item.querySelector('.btn-eliminar-valor').addEventListener('click', () => item.remove());
        opcionEl.querySelector('.valores-lista').appendChild(item);
    }

    document.getElementById('btn-agregar-opcion').addEventListener('click', () => {
        const oi  = opcionIdx++;
        const tpl = document.getElementById('tpl-opcion').content.cloneNode(true);
        const item = tpl.querySelector('.opcion-item');

        item.querySelector('.inp-nombre').name    = `opciones[${oi}][nombre]`;
        item.querySelector('.inp-tipo').name      = `opciones[${oi}][tipo]`;
        item.querySelector('.inp-requerido').name = `opciones[${oi}][requerido]`;

        item.querySelector('.btn-eliminar-opcion').addEventListener('click', () => {
            item.remove(); renumerar();
        });

        document.getElementById('lista-opciones').appendChild(item);
        renumerar();
        agregarValor(item, oi);
    });

    document.getElementById('lista-opciones').addEventListener('click', e => {
        if (e.target.closest('.btn-eliminar-opcion')) {
            e.target.closest('.opcion-item').remove(); renumerar();
        }
        if (e.target.closest('.btn-eliminar-valor')) {
            e.target.closest('.valor-item').remove();
        }
        if (e.target.closest('.btn-agregar-valor')) {
            const opcionEl = e.target.closest('.opcion-item');
            const oi = [...document.querySelectorAll('#lista-opciones .opcion-item')].indexOf(opcionEl);
            agregarValor(opcionEl, oi);
        }
    });
})();
</script>
@endsection
