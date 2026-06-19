{{-- Sección Opciones del Plato --}}
<div class="card border-0 shadow-sm rounded-3 mt-4" id="opciones-container" style="background:var(--card-bg) !important;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-uppercase fw-bold mb-0" style="font-size:0.72rem;letter-spacing:0.12em;color:var(--muted);">
                <i class="bi bi-list-check text-primary me-1"></i> Opciones del plato
            </h6>
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-agregar-opcion">
                <i class="bi bi-plus-lg me-1"></i> Agregar opción
            </button>
        </div>

        <p class="small mb-3" style="color:var(--muted);">Ej: Tamaño (Individual / Familiar), Proteína (Pollo / Res), Extras (Con papas)</p>

        <div id="lista-opciones">
            @isset($plato)
                @foreach($plato->opciones as $oi => $opcion)
                <div class="opcion-item card border rounded-3 p-3 mb-3" style="background:var(--hover-bg);border-color:var(--card-border) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold small" style="color:var(--text);">Opción #<<span class="num-opcion">{{ $oi + 1 }}</span></span>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-eliminar-opcion">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-12 col-sm-5">
                            <label class="form-label small fw-semibold" style="color:var(--text);">Nombre de la opción</label>
                            <input type="text" name="opciones[{{ $oi }}][nombre]"
                                   class="form-control form-control-sm"
                                   placeholder="Ej: Tamaño, Proteína..."
                                   value="{{ $opcion->nombre }}">
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label small fw-semibold" style="color:var(--text);">Tipo</label>
                            <select name="opciones[{{ $oi }}][tipo]" class="form-select form-select-sm">
                                <option value="radio" {{ $opcion->tipo === 'radio' ? 'selected' : '' }}>Elige uno (radio)</option>
                                <option value="checkbox" {{ $opcion->tipo === 'checkbox' ? 'selected' : '' }}>Elige varios (checkbox)</option>
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
                                   placeholder="Ej: Individual, Familiar..."
                                   value="{{ $valor->valor }}">
                            <div class="input-group input-group-sm" style="max-width:140px;">
                                <span class="input-group-text" style="background:var(--input-bg);color:var(--muted);border-color:var(--input-border);">C$</span>
                                <input type="number" name="opciones[{{ $oi }}][valores][{{ $vi }}][precio_extra]"
                                       class="form-control form-control-sm"
                                       placeholder="0" min="0" step="0.01"
                                       value="{{ $valor->precio_extra }}">
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill btn-eliminar-valor">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-agregar-valor">
                        <i class="bi bi-plus me-1"></i> Agregar valor
                    </button>
                </div>
                @endforeach
            @endisset
        </div>
    </div>
</div>

{{-- Template oculto para nueva opción --}}
<<template id="tpl-opcion">
    <div class="opcion-item card border rounded-3 p-3 mb-3" style="background:var(--hover-bg);border-color:var(--card-border) !important;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold small" style="color:var(--text);">Opción #<<span class="num-opcion"></span></span>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-eliminar-opcion">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-12 col-sm-5">
                <label class="form-label small fw-semibold" style="color:var(--text);">Nombre de la opción</label>
                <input type="text" name="" class="form-control form-control-sm inp-nombre"
                       placeholder="Ej: Tamaño, Proteína...">
            </div>
            <div class="col-6 col-sm-4">
                <label class="form-label small fw-semibold" style="color:var(--text);">Tipo</label>
                <select name="" class="form-select form-select-sm inp-tipo">
                    <option value="radio">Elige uno (radio)</option>
                    <option value="checkbox">Elige varios (checkbox)</option>
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
            <i class="bi bi-plus me-1"></i> Agregar valor
        </button>
    </div>
</template>

{{-- Template oculto para nuevo valor --}}
<<template id="tpl-valor">
    <div class="valor-item d-flex gap-2 align-items-center mb-2">
        <input type="text" name="" class="form-control form-control-sm inp-valor"
               placeholder="Ej: Individual, Familiar...">
        <div class="input-group input-group-sm" style="max-width:140px;">
            <span class="input-group-text" style="background:var(--input-bg);color:var(--muted);border-color:var(--input-border);">C$</span>
            <input type="number" name="" class="form-control form-control-sm inp-precio"
                   placeholder="0" min="0" step="0.01" value="0">
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill btn-eliminar-valor">
            <i class="bi bi-x"></i>
        </button>
    </div>
</template>

@once
@push('scripts')
<script>
(function () {
    let opcionIdx = {{ isset($plato) ? $plato->opciones->count() : 0 }};

    function renumerar() {
        document.querySelectorAll('#lista-opciones .opcion-item').forEach((el, i) => {
            el.querySelector('.num-opcion').textContent = i + 1;
        });
    }

    function agregarValor(opcionEl, oi) {
        const vi = opcionEl.querySelectorAll('.valor-item').length;
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

        item.querySelector('.inp-nombre').name   = `opciones[${oi}][nombre]`;
        item.querySelector('.inp-tipo').name     = `opciones[${oi}][tipo]`;
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
@endpush
@endonce
