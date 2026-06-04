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

    {{-- Mensajes de error --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Por favor corrige los siguientes errores:</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contratos.update', $contrato) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">

                {{-- Sección 1: Establecimiento --}}
                <p class="text-uppercase fw-bold text-muted mb-3" style="font-size:0.7rem;letter-spacing:0.8px;">
                    <i class="bi bi-building me-1"></i> Datos del Establecimiento
                </p>

                <div class="row g-3 mb-4">

                    {{-- Tipo --}}
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold text-dark">Tipo de Establecimiento <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_establecimiento"
                                       id="tipoGastrobar" value="gastrobar"
                                       {{ $contrato->gastrobar_id ? 'checked' : '' }}>
                                <label class="form-check-label small" for="tipoGastrobar">Gastrobar</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_establecimiento"
                                       id="tipoRestaurante" value="restaurante"
                                       {{ $contrato->restaurante_id ? 'checked' : '' }}>
                                <label class="form-check-label small" for="tipoRestaurante">Restaurante</label>
                            </div>
                        </div>
                    </div>

                    {{-- Select Gastrobar --}}
                    <div class="col-md-6 {{ $contrato->restaurante_id ? 'd-none' : '' }}" id="selectGastrobarWrap">
                        <label class="form-label small fw-semibold text-dark">Gastrobar <span class="text-danger">*</span></label>
                        <select name="gastrobar_id" id="gastrobar_id" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                            <option value="">Seleccionar gastrobar</option>
                            @foreach($gastrobares as $g)
                                <option value="{{ $g->id }}" {{ old('gastrobar_id', $contrato->gastrobar_id) == $g->id ? 'selected' : '' }}>
                                    {{ $g->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select Restaurante --}}
                    <div class="col-md-6 {{ $contrato->gastrobar_id ? 'd-none' : '' }}" id="selectRestauranteWrap">
                        <label class="form-label small fw-semibold text-dark">Restaurante <span class="text-danger">*</span></label>
                        <select name="restaurante_id" id="restaurante_id" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                            <option value="">Seleccionar restaurante</option>
                            @foreach($restaurantes as $r)
                                <option value="{{ $r->id }}" {{ old('restaurante_id', $contrato->restaurante_id) == $r->id ? 'selected' : '' }}>
                                    {{ $r->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Representante Legal <span class="text-danger">*</span></label>
                        <input type="text" name="representante" class="form-control bg-light"
                               value="{{ old('representante', $contrato->representante) }}"
                               placeholder="Nombre completo" style="box-shadow:none;">
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
                        <select name="plan" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                            <option value="">Seleccionar plan</option>
                            @foreach(['gratuito', 'basico', 'premium'] as $p)
                                <option value="{{ $p }}" {{ old('plan', $contrato->plan) == $p ? 'selected' : '' }}>
                                    {{ ucfirst($p) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Fecha de Inicio <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_inicio" class="form-control bg-light"
                               value="{{ old('fecha_inicio', $contrato->fecha_inicio->format('Y-m-d')) }}"
                               style="box-shadow:none;">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Fecha de Vencimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_fin" class="form-control bg-light"
                               value="{{ old('fecha_fin', $contrato->fecha_fin->format('Y-m-d')) }}"
                               style="box-shadow:none;">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select bg-light" style="box-shadow:none;cursor:pointer;">
                            @foreach(['pendiente', 'activo', 'vencido', 'cancelado'] as $e)
                                <option value="{{ $e }}" {{ old('estado', $contrato->estado) == $e ? 'selected' : '' }}>
                                    {{ ucfirst($e) }}
                                </option>
                            @endforeach
                        </select>
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

{{-- Toggle gastrobar / restaurante --}}
<script>
    document.querySelectorAll('input[name="tipo_establecimiento"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const esGastrobar = this.value === 'gastrobar';
            document.getElementById('selectGastrobarWrap').classList.toggle('d-none', !esGastrobar);
            document.getElementById('selectRestauranteWrap').classList.toggle('d-none', esGastrobar);
        });
    });
</script>

@endsection
