@extends('restaurante.layout')
@section('title', 'Nuevo Plato')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title" style="color:var(--text);">Nuevo Plato</div>
        <div class="page-sub" style="color:var(--muted);">Añade un plato al menú de {{ $restaurante->nombre }}</div>
    </div>
    <a href="{{ route('restaurante.platos.index') }}" class="btn btn-ghost" style="color:var(--muted);border-color:var(--card-border);">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-error" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#ef4444;">
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
            <div class="card card-body" style="background:var(--card-bg) !important;border-color:var(--card-border) !important;">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Información del plato</div>

                <div class="form-group">
                    <label class="form-label" style="color:var(--text);">Nombre del plato *</label>
                    <input type="text" name="nombre" class="form-control"
                           placeholder="Ej: Ceviche de camarón, Arroz con pollo..."
                           value="{{ old('nombre') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:var(--text);">Descripción <span style="color:var(--muted);font-weight:500;">(opcional)</span></label>
                    <textarea name="descripcion" class="form-control"
                              placeholder="Ingredientes, preparación, alérgenos...">{{ old('descripcion') }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color:var(--text);">Precio (C$) *</label>
                        <input type="number" name="precio" class="form-control"
                               placeholder="0" min="0" step="0.01"
                               value="{{ old('precio') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-semibold" style="font-size:13px;color:var(--text);">Categoría</label>
                        <select name="categoria_id" class="form-select">
                            <option value="">Sin categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Opciones del plato --}}
            <div class="card card-body" style="background:var(--card-bg) !important;border-color:var(--card-border) !important;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);">
                        Opciones del plato
                    </div>
                    <button type="button" class="btn btn-ghost" style="font-size:12px;padding:6px 12px;color:var(--primary);border-color:var(--primary-border);" id="btn-agregar-opcion">
                        + Agregar opción
                    </button>
                </div>
                <p style="font-size:12px;color:var(--muted);margin-bottom:16px;">
                    Ej: Tamaño (Individual / Familiar), Proteína (Pollo / Res), Extras (Con papas)
                </p>
                <div id="lista-opciones"></div>
            </div>
        </div>

        {{-- Derecha --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card card-body" style="background:var(--card-bg) !important;border-color:var(--card-border) !important;">
                <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Foto del plato</div>
                <div id="imagen-drop" style="border:2px dashed var(--input-border);border-radius:12px;overflow:hidden;cursor:pointer;position:relative;aspect-ratio:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;transition:border-color 0.2s;">
                    <img id="imagen-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                    <div id="imagen-placeholder" style="text-align:center;padding:20px;">
                        <i class="fas fa-camera" style="font-size:28px;color:var(--muted);display:block;margin-bottom:8px;"></i>
                        <p style="font-size:12px;color:var(--muted);">Haz clic para subir foto</p>
                    </div>
                    <input type="file" name="imagen" id="imagen-input" accept="image/*"
                           style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                </div>
                <p style="font-size:10px;color:var(--muted);margin-top:8px;">JPG, PNG, WEBP — máx. 2 MB</p>
            </div>

            <div class="card card-body" style="background:var(--card-bg) !important;border-color:var(--card-border) !important;">
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
                <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:12px;background:var(--primary);color:white;border:none;">
                    <i class="fas fa-plus"></i> Añadir al Menú
                </button>
                <a href="{{ route('restaurante.platos.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px;color:var(--muted);border-color:var(--card-border);">
                    Cancelar
                </a>
            </div>

        </div>
    </div>
</form>

{{-- Templates ocultos --}}
<<template id="tpl-opcion">
    <div class="opcion-item" style="border:1px solid var(--card-border);border-radius:10px;padding:16px;margin-bottom:12px;background:var(--card-bg);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:13px;font-weight:700;color:var(--text);">Opción #<<span class="num-opcion"></span></span>
            <button type="button" class="btn btn-ghost btn-eliminar-opcion" style="font-size:12px;color:#ef4444;padding:4px 10px;border-color:var(--card-border);">
                Eliminar
            </button>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:10px;margin-bottom:12px;align-items:end;">
            <div class="form-group" style="margin:0;">
                <label class="form-label" style="color:var(--text);">Nombre</label>
                <input type="text" name="" class="form-control inp-nombre" placeholder="Ej: Tamaño, Proteína...">
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label" style="color:var(--text);">Tipo</label>
                <select name="" class="form-select inp-tipo">
                    <option value="radio">Elige uno</option>
                    <option value="checkbox">Elige varios</option>
                </select>
            </div>
            <div style="padding-bottom:4px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;color:var(--text);">
                    <input type="checkbox" name="" value="1" class="inp-requerido" style="accent-color:var(--primary);">
                    Requerido
                </label>
            </div>
        </div>
        <div class="valores-lista" style="margin-bottom:10px;"></div>
        <button type="button" class="btn btn-ghost btn-agregar-valor" style="font-size:12px;padding:6px 12px;color:var(--primary);border-color:var(--primary-border);">
            + Agregar valor
        </button>
    </div>
</template>

<<template id="tpl-valor">
    <div class="valor-item" style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
        <input type="text" name="" class="form-control inp-valor" placeholder="Ej: Individual, Familiar..." style="flex:1;">
        <div style="display:flex;align-items:center;gap:0;border:1px solid var(--input-border);border-radius:8px;overflow:hidden;width:130px;">
            <span style="padding:0 8px;font-size:12px;color:var(--muted);background:var(--hover-bg);">C$</span>
            <input type="number" name="" class="form-control inp-precio" placeholder="0" min="0" step="0.01" value="0"
                   style="border:none;border-radius:0;width:80px;">
        </div>
        <button type="button" class="btn btn-ghost btn-eliminar-valor" style="padding:4px 10px;font-size:12px;color:#ef4444;border-color:var(--card-border);">✕</button>
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

// ── Opciones ──
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
        item.querySelector('.btn-agregar-valor').addEventListener('click', () => {
            agregarValor(item, oi);
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
