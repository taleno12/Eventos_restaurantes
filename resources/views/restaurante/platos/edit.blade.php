@extends('restaurante.layout')
@section('title', 'Editar Plato')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-pencil-square text-primary me-2"></i> Editar Plato
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                {{ $plato->nombre }}
            </p>
        </div>
        <a href="{{ route('restaurante.platos.index') }}"
           class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center gap-2 px-3"
           style="height:38px;font-size:13px;font-weight:600;background:var(--card-bg);color:var(--text);border-color:var(--card-border) !important;">
            <i class="bi bi-arrow-left" style="color:var(--muted);"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2) !important;color:#ef4444;">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <ul class="mb-0 ps-2">
                    @foreach($errors->all() as $error)<li class="small">{{ $error }}</li>@endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('restaurante.platos.update', $plato) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4 align-items-start">

            {{-- Columna izquierda --}}
            <div class="col-12 col-lg-8 d-flex flex-column gap-4">

                {{-- Info --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase fw-bold mb-4" style="font-size:0.72rem;letter-spacing:0.12em;color:var(--muted);">
                            <i class="bi bi-info-circle text-primary me-1"></i> Información del plato
                        </h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small" style="color:var(--text);">Nombre del plato <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="nombre" class="form-control" style="box-shadow:none;background:var(--input-bg) !important;"
                                   value="{{ old('nombre', $plato->nombre) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small" style="color:var(--text);">Descripción</label>
                            <textarea name="descripcion" class="form-control"
                                      style="box-shadow:none;resize:none;background:var(--input-bg) !important;" rows="3">{{ old('descripcion', $plato->descripcion) }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small" style="color:var(--text);">Precio (C$) <span style="color:#ef4444;">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0" style="background:var(--input-bg) !important;color:var(--muted);border-color:var(--input-border) !important;">C$</span>
                                    <input type="number" name="precio" class="form-control border-start-0"
                                           style="box-shadow:none;background:var(--input-bg) !important;border-color:var(--input-border) !important;" min="0" step="0.01"
                                           value="{{ old('precio', $plato->precio) }}" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small d-flex align-items-center gap-2" style="color:var(--text);">
                                    Categoría
                                    <a href="{{ route('restaurante.categorias.index') }}"
                                       class="text-primary" style="font-size:11px;font-weight:500;" target="_blank">
                                        <i class="bi bi-plus-circle me-1"></i>Gestionar
                                    </a>
                                </label>
                                <select name="categoria_id" class="form-select" style="box-shadow:none;background:var(--input-bg) !important;border-color:var(--input-border) !important;">
                                    <option value="">Sin categoría</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('categoria_id', $plato->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Opciones --}}
                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-uppercase fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.12em;color:var(--muted);">
                                <i class="bi bi-list-check text-primary me-1"></i> Opciones del plato
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-agregar-opcion">
                                + Agregar opción
                            </button>
                        </div>
                        <p class="small mb-3" style="color:var(--muted);">Ej: Tamaño (Individual / Familiar), Proteína (Pollo / Res)</p>

                        <div id="lista-opciones">
                            @foreach($plato->opciones as $oi => $opcion)
                            <div class="opcion-item border rounded-3 p-3 mb-3" style="background:var(--hover-bg);border-color:var(--card-border) !important;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small" style="color:var(--text);">Opción #<<span class="num-opcion">{{ $oi + 1 }}</span></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-eliminar-opcion">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-12 col-sm-5">
                                        <label class="form-label small fw-semibold" style="color:var(--text);">Nombre</label>
                                        <input type="text" name="opciones[{{ $oi }}][nombre]"
                                               class="form-control form-control-sm"
                                               placeholder="Ej: Tamaño..."
                                               value="{{ $opcion->nombre }}">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <label class="form-label small fw-semibold" style="color:var(--text);">Tipo</label>
                                        <select name="opciones[{{ $oi }}][tipo]" class="form-select form-select-sm">
                                            <option value="radio" {{ $opcion->tipo === 'radio' ? 'selected' : '' }}>Elige uno</option>
                                            <option value="checkbox" {{ $opcion->tipo === 'checkbox' ? 'selected' : '' }}>Elige varios</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-3 d-flex align-items-end pb-1">
                                        <div class="form-check">
                                            <input type="checkbox" name="opciones[{{ $oi }}][requerido]" value="1"
                                                   class="form-check-input"
                                                   {{ $opcion->requerido ? 'checked' : '' }}>
                                            <label class="form-check-label small" style="color:var(--text);">Requerido</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="valores-lista mb-2">
                                    @foreach($opcion->valores as $vi => $valor)
                                    <div class="valor-item d-flex gap-2 align-items-center mb-2">
                                        <input type="text" name="opciones[{{ $oi }}][valores][{{ $vi }}][valor]"
                                               class="form-control form-control-sm"
                                               placeholder="Ej: Individual..."
                                               value="{{ $valor->valor }}">
                                        <div class="input-group input-group-sm" style="max-width:140px;">
                                            <span class="input-group-text" style="background:var(--input-bg);color:var(--muted);border-color:var(--input-border);">C$</span>
                                            <input type="number" name="opciones[{{ $oi }}][valores][{{ $vi }}][precio_extra]"
                                                   class="form-control form-control-sm"
                                                   min="0" step="0.01"
                                                   value="{{ $valor->precio_extra }}">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill btn-eliminar-valor">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-agregar-valor">
                                    + Agregar valor
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- Columna derecha --}}
            <div class="col-12 col-lg-4 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.12em;color:var(--muted);">
                            <i class="bi bi-image text-primary me-1"></i> Foto del plato
                        </h6>
                        @if($plato->imagen)
                        <div class="mb-3">
                            <p class="small fw-semibold mb-2" style="color:var(--muted);">Foto actual</p>
                            <img src="{{ asset('storage/'.$plato->imagen) }}"
                                 class="w-100 rounded-3 border" style="aspect-ratio:1;object-fit:cover;border-color:var(--card-border) !important;">
                        </div>
                        @endif
                        <label for="imagen-input" class="w-100 cursor-pointer">
                            <div class="rounded-3 d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                 style="aspect-ratio:1;border:2px dashed var(--input-border);cursor:pointer;transition:border-color 0.2s;background:var(--input-bg);"
                                 onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--input-border)'">
                                <div id="imagen-placeholder" class="text-center py-3">
                                    <i class="bi bi-camera fs-3 d-block mb-2" style="color:var(--muted);"></i>
                                    <p class="small mb-0" style="color:var(--muted);">{{ $plato->imagen ? 'Cambiar foto' : 'Subir foto' }}</p>
                                </div>
                                <img id="imagen-preview" src=""
                                     class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                                <input type="file" name="imagen" id="imagen-input" accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;">
                            </div>
                        </label>
                        <p class="small mt-2 mb-0" style="color:var(--muted);">Deja vacío para mantener la foto actual</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.12em;color:var(--muted);">
                            <i class="bi bi-toggle-on text-primary me-1"></i> Estado
                        </h6>
                        <div class="form-check mb-4">
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" name="activo" value="1" id="check-activo"
                                   class="form-check-input"
                                   {{ old('activo', $plato->activo) ? 'checked' : '' }}>
                            <label for="check-activo" class="form-check-label small fw-semibold" style="color:var(--text);">
                                Disponible
                                <span class="d-block fw-normal" style="font-size:11px;color:var(--muted);">Visible en el menú público</span>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill">
                            <i class="bi bi-save me-1"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('restaurante.platos.index') }}"
                           class="btn btn-light w-100 fw-semibold rounded-pill mt-2" style="background:var(--hover-bg);color:var(--text);border-color:var(--card-border);">
                            Cancelar
                        </a>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;border:1px solid rgba(239,68,68,0.3) !important;">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="font-size:0.72rem;letter-spacing:0.12em;color:#ef4444;">
                            <i class="bi bi-exclamation-triangle me-1"></i> Zona de peligro
                        </h6>
                        <button type="submit" form="form-delete-plato"
                                onclick="return confirm('¿Eliminar este plato del menú? Esta acción no se puede deshacer.')"
                                class="btn btn-outline-danger w-100 fw-semibold rounded-pill" style="font-size:13px;">
                            <i class="bi bi-trash me-1"></i> Eliminar plato
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <form id="form-delete-plato" method="POST"
          action="{{ route('restaurante.platos.destroy', $plato) }}" style="display:none;">
        @csrf @method('DELETE')
    </form>

</div>

{{-- Templates --}}
<<template id="tpl-opcion">
    <div class="opcion-item border rounded-3 p-3 mb-3" style="background:var(--hover-bg);border-color:var(--card-border) !important;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold small" style="color:var(--text);">Opción #<<span class="num-opcion"></span></span>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-eliminar-opcion">
                <i class="bi bi-trash"></i>
            </button>
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

<<template id="tpl-valor">
    <div class="valor-item d-flex gap-2 align-items-center mb-2">
        <input type="text" name="" class="form-control form-control-sm inp-valor" placeholder="Ej: Individual...">
        <div class="input-group input-group-sm" style="max-width:140px;">
            <span class="input-group-text" style="background:var(--input-bg);color:var(--muted);border-color:var(--input-border);">C$</span>
            <input type="number" name="" class="form-control form-control-sm inp-precio" placeholder="0" min="0" step="0.01" value="0">
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill btn-eliminar-valor">
            <i class="bi bi-x"></i>
        </button>
    </div>
</template>

<style>.cursor-pointer { cursor: pointer; }</style>

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
        preview.classList.remove('d-none');
        document.getElementById('imagen-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});

(function () {
    let opcionIdx = {{ $plato->opciones->count() }};

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
