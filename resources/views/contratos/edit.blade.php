@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark fw-bold">
                <i class="bi bi-pencil-square text-warning me-2"></i>
                Editar Contrato #{{ $contrato->numero_contrato }}
            </h1>
            <p class="text-muted mb-0 small">Modifica los datos del contrato del establecimiento.</p>
        </div>
        <a href="{{ route('contratos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Mensajes de error (excluye 'establecimiento' para no duplicar) --}}
    @if($errors->any())
        @php
            $mensajesFiltrados = collect($errors->all())->filter(function($msg) use ($errors) {
                return $msg !== $errors->first('establecimiento');
            });
        @endphp
        @if($mensajesFiltrados->isNotEmpty())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach($mensajesFiltrados as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    @php
        $establecimientoActual = $contrato->gastrobar ?? $contrato->restaurante;
        $tipoActual            = $contrato->gastrobar_id ? 'gastrobar' : 'restaurante';
        $deptoActualId         = $establecimientoActual?->departamento_id;
        $municipioActualId     = $establecimientoActual?->municipio_id;
        $estabActualId         = $establecimientoActual?->id;

        $municipiosActuales = $deptoActualId
            ? \App\Models\Municipio::where('departamento_id', $deptoActualId)->orderBy('nombre')->get()
            : collect();

        $establecimientosActuales = $municipioActualId
            ? ($tipoActual === 'gastrobar'
                ? \App\Models\Gastrobar::where('municipio_id', $municipioActualId)->orderBy('nombre')->get()
                : \App\Models\Restaurante::where('municipio_id', $municipioActualId)->orderBy('nombre')->get())
            : collect();
    @endphp

    <form action="{{ route('contratos.update', $contrato) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">

                {{-- Sección 1: Establecimiento --}}
                <p class="text-uppercase fw-bold text-muted mb-3" style="font-size:0.7rem;letter-spacing:0.8px;">
                    <i class="bi bi-building me-1"></i> Datos del Establecimiento
                </p>

                {{-- ── Alerta de establecimiento duplicado (solo aquí) ── --}}
                @error('establecimiento')
                    <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2 mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>{{ $message }}</div>
                    </div>
                @enderror

                <div class="row g-3 mb-4">

                    {{-- Tipo --}}
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold text-dark">Tipo de Establecimiento <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_establecimiento"
                                       id="tipoGastrobar" value="gastrobar"
                                       {{ old('tipo_establecimiento', $tipoActual) === 'gastrobar' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="tipoGastrobar">Gastrobar</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_establecimiento"
                                       id="tipoRestaurante" value="restaurante"
                                       {{ old('tipo_establecimiento', $tipoActual) === 'restaurante' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="tipoRestaurante">Restaurante</label>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 1: Departamento --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">
                            <i class="bi bi-geo-alt text-warning me-1"></i>Departamento <span class="text-danger">*</span>
                        </label>
                        <select id="edit_departamento_id" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                            <option value="">Seleccionar departamento</option>
                            @foreach($departamentos as $d)
                                <option value="{{ $d->id }}" {{ $deptoActualId == $d->id ? 'selected' : '' }}>
                                    {{ $d->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Paso 2: Municipio --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">
                            <i class="bi bi-pin-map text-warning me-1"></i>Municipio <span class="text-danger">*</span>
                        </label>
                        <select id="edit_municipio_id" class="form-select bg-light" style="box-shadow:none;cursor:pointer;"
                                {{ $municipiosActuales->isEmpty() ? 'disabled' : '' }}>
                            <option value="">
                                {{ $municipiosActuales->isEmpty() ? 'Primero selecciona departamento' : 'Seleccionar municipio' }}
                            </option>
                            @foreach($municipiosActuales as $m)
                                <option value="{{ $m->id }}" {{ $municipioActualId == $m->id ? 'selected' : '' }}>
                                    {{ $m->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Paso 3: Establecimiento --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark" id="edit_labelEstablecimiento">
                            <i class="bi bi-{{ $tipoActual === 'gastrobar' ? 'cup-straw' : 'shop' }} text-warning me-1"></i>
                            {{ $tipoActual === 'gastrobar' ? 'Gastrobar' : 'Restaurante' }} <span class="text-danger">*</span>
                        </label>
                        <select id="edit_establecimiento_id" class="form-select bg-light" style="box-shadow:none;cursor:pointer;"
                                {{ $establecimientosActuales->isEmpty() ? 'disabled' : '' }}>
                            <option value="">
                                {{ $establecimientosActuales->isEmpty() ? 'Primero selecciona municipio' : 'Seleccionar' }}
                            </option>
                            @foreach($establecimientosActuales as $item)
                                <option value="{{ $item->id }}" {{ $estabActualId == $item->id ? 'selected' : '' }}>
                                    {{ $item->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="gastrobar_id"   id="edit_hidden_gastrobar"
                               value="{{ old('gastrobar_id', $contrato->gastrobar_id) }}">
                        <input type="hidden" name="restaurante_id" id="edit_hidden_restaurante"
                               value="{{ old('restaurante_id', $contrato->restaurante_id) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Representante Legal <span class="text-danger">*</span></label>
                        <input type="text" name="representante"
                               class="form-control bg-light @error('representante') is-invalid @enderror"
                               value="{{ old('representante', $contrato->representante) }}"
                               placeholder="Nombre completo" style="box-shadow:none;">
                        @error('representante')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Dirección Física</label>
                        <input type="text" name="direccion" class="form-control bg-light"
                               value="{{ old('direccion', $contrato->direccion) }}"
                               placeholder="Dirección del local" style="box-shadow:none;">
                    </div>
                </div>

                <hr style="border-color:#edf2f7;">

                {{-- Sección 2: Plan y condiciones --}}
                <p class="text-uppercase fw-bold text-muted mb-3 mt-3" style="font-size:0.7rem;letter-spacing:0.8px;">
                    <i class="bi bi-credit-card me-1"></i> Plan y Condiciones Comerciales
                </p>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Plan <span class="text-danger">*</span></label>
                        <select name="plan" class="form-select bg-light @error('plan') is-invalid @enderror" style="box-shadow:none;cursor:pointer;">
                            <option value="">Seleccionar plan</option>
                            @foreach(['basico', 'premium'] as $p)
                                <option value="{{ $p }}" {{ old('plan', $contrato->plan) == $p ? 'selected' : '' }}>
                                    {{ ucfirst($p) }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Fecha de Inicio <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_inicio"
                               class="form-control bg-light @error('fecha_inicio') is-invalid @enderror"
                               value="{{ old('fecha_inicio', $contrato->fecha_inicio->format('Y-m-d')) }}"
                               style="box-shadow:none;">
                        @error('fecha_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Fecha de Vencimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_fin"
                               class="form-control bg-light @error('fecha_fin') is-invalid @enderror"
                               value="{{ old('fecha_fin', $contrato->fecha_fin->format('Y-m-d')) }}"
                               style="box-shadow:none;">
                        @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select bg-light @error('estado') is-invalid @enderror" style="box-shadow:none;cursor:pointer;">
                            @foreach(['pendiente', 'activo', 'vencido', 'cancelado'] as $e)
                                <option value="{{ $e }}" {{ old('estado', $contrato->estado) == $e ? 'selected' : '' }}>
                                    {{ ucfirst($e) }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Monto (C$)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted small">C$</span>
                            <input type="number" name="monto" class="form-control bg-light"
                                   value="{{ old('monto', $contrato->monto) }}"
                                   placeholder="0.00" min="0" step="0.01" style="box-shadow:none;">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Forma de Pago</label>
                        <select name="forma_pago" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                            <option value="">Seleccionar</option>
                            @foreach(['mensual', 'trimestral', 'anual'] as $fp)
                                <option value="{{ $fp }}" {{ old('forma_pago', $contrato->forma_pago) == $fp ? 'selected' : '' }}>
                                    {{ ucfirst($fp) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('contratos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                Cancelar
            </a>
            <button type="submit" class="btn btn-warning rounded-pill px-5 fw-semibold text-dark shadow-sm">
                <i class="bi bi-save me-1"></i> Actualizar Contrato
            </button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const urlMunicipios       = '{{ route("contratos.ajax.municipios") }}';
    const urlEstablecimientos = '{{ route("contratos.ajax.establecimientos") }}';

    const selectDepto           = document.getElementById('edit_departamento_id');
    const selectMunicipio       = document.getElementById('edit_municipio_id');
    const selectEstablecimiento = document.getElementById('edit_establecimiento_id');
    const labelEstablecimiento  = document.getElementById('edit_labelEstablecimiento');
    const hiddenGastrobar       = document.getElementById('edit_hidden_gastrobar');
    const hiddenRestaurante     = document.getElementById('edit_hidden_restaurante');

    function getTipo() {
        return document.querySelector('input[name="tipo_establecimiento"]:checked').value;
    }

    function resetSelect(sel, placeholder) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        sel.disabled = true;
    }

    // ── Cambio de tipo ──
    document.querySelectorAll('input[name="tipo_establecimiento"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const esGastrobar = this.value === 'gastrobar';
            labelEstablecimiento.innerHTML = `<i class="bi bi-${esGastrobar ? 'cup-straw' : 'shop'} text-warning me-1"></i>${esGastrobar ? 'Gastrobar' : 'Restaurante'} <span class="text-danger">*</span>`;
            hiddenGastrobar.value   = '';
            hiddenRestaurante.value = '';
            if (selectMunicipio.value) {
                cargarEstablecimientos(selectMunicipio.value, this.value);
            } else {
                resetSelect(selectEstablecimiento, 'Primero selecciona municipio');
            }
        });
    });

    // ── Cambio de departamento → municipios ──
    selectDepto.addEventListener('change', function () {
        resetSelect(selectMunicipio, 'Cargando...');
        resetSelect(selectEstablecimiento, 'Primero selecciona municipio');
        hiddenGastrobar.value   = '';
        hiddenRestaurante.value = '';

        if (!this.value) {
            resetSelect(selectMunicipio, 'Primero selecciona departamento');
            return;
        }

        fetch(`${urlMunicipios}?departamento_id=${this.value}`)
            .then(r => r.json())
            .then(municipios => {
                selectMunicipio.innerHTML = '<option value="">Seleccionar municipio</option>';
                municipios.forEach(m => {
                    selectMunicipio.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
                });
                selectMunicipio.disabled = false;
            });
    });

    // ── Cambio de municipio → establecimientos ──
    selectMunicipio.addEventListener('change', function () {
        resetSelect(selectEstablecimiento, 'Cargando...');
        hiddenGastrobar.value   = '';
        hiddenRestaurante.value = '';

        if (!this.value) {
            resetSelect(selectEstablecimiento, 'Primero selecciona municipio');
            return;
        }

        cargarEstablecimientos(this.value, getTipo());
    });

    function cargarEstablecimientos(municipioId, tipo) {
        fetch(`${urlEstablecimientos}?municipio_id=${municipioId}&tipo=${tipo}`)
            .then(r => r.json())
            .then(items => {
                const label = tipo === 'gastrobar' ? 'gastrobar' : 'restaurante';
                selectEstablecimiento.innerHTML = `<option value="">Seleccionar ${label}</option>`;
                if (items.length === 0) {
                    selectEstablecimiento.innerHTML = `<option value="">No hay ${label}es en este municipio</option>`;
                } else {
                    items.forEach(item => {
                        const sub      = item.especialidad ? ` — ${item.especialidad}` : (item.tipo_bar ? ` — ${item.tipo_bar}` : '');
                        const selected = item.id == {{ $estabActualId ?? 'null' }} ? 'selected' : '';
                        selectEstablecimiento.innerHTML += `<option value="${item.id}" ${selected}>${item.nombre}${sub}</option>`;
                    });
                }
                selectEstablecimiento.disabled = false;
            });
    }

    // ── Al seleccionar establecimiento → hidden inputs ──
    selectEstablecimiento.addEventListener('change', function () {
        const tipo = getTipo();
        hiddenGastrobar.value   = tipo === 'gastrobar'   ? this.value : '';
        hiddenRestaurante.value = tipo === 'restaurante' ? this.value : '';
    });

});
</script>

@endsection
