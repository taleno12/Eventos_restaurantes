@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('departamentos.index') }}"
           class="d-flex align-items-center justify-content-center rounded-3 text-secondary text-decoration-none bg-light"
           style="width:42px;height:42px;transition:background 0.2s;"
           onmouseover="this.style.background='#e2e8f0'"
           onmouseout="this.style.background='#f8f9fa'">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-pencil-square text-primary me-2"></i> Editar Departamento
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Modifica la información de <strong>{{ $departamento->nombre }}</strong>
            </p>
        </div>
    </div>

    {{-- ── Errores de validación ── --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-circle-fill me-2 fs-5 mt-1"></i>
                <div>
                    <p class="fw-bold mb-1">Corrige los siguientes errores:</p>
                    <ul class="mb-0 ps-3 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <form action="{{ route('departamentos.update', $departamento) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ── Card: Información del departamento ── --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white">
                    <div class="card-body p-4">

                        <p class="text-uppercase text-muted fw-bold mb-4 d-flex align-items-center gap-2" style="font-size:0.72rem;letter-spacing:0.5px;">
                            <i class="bi bi-geo-alt-fill text-primary"></i>
                            Información Geográfica
                        </p>

                        {{-- Nombre --}}
                        <div class="mb-2">
                            <label for="nombre" class="form-label fw-semibold text-dark">
                                Nombre del Departamento <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary">
                                    <i class="bi bi-map"></i>
                                </span>
                                <input type="text"
                                       name="nombre"
                                       id="nombre"
                                       value="{{ old('nombre', $departamento->nombre) }}"
                                       placeholder="Ej: León, Masaya, Madriz..."
                                       maxlength="100"
                                       required
                                       class="form-control @error('nombre') is-invalid @enderror">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Card: Agregar municipios ── --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white">
                    <div class="card-body p-4">

                        <p class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size:0.72rem;letter-spacing:0.5px;">
                            <i class="bi bi-tags-fill text-info"></i>
                            Agregar Municipios
                            <span class="text-muted fw-normal text-lowercase" style="letter-spacing:normal;">(opcional)</span>
                        </p>
                        <p class="text-muted small mb-3">
                            Escribe municipios separados por comas para añadirlos. Los existentes no se modifican.
                        </p>

                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary align-items-start pt-2">
                                <i class="bi bi-pin-map"></i>
                            </span>
                            <textarea name="municipios_lista"
                                      id="municipios_lista"
                                      rows="3"
                                      placeholder="Ej: Somoto, Palacagüina, Telpaneca"
                                      class="form-control"
                                      style="resize:none;">{{ old('municipios_lista') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- ── Card: Municipios actuales ── --}}
                @if($departamento->municipios->count() > 0)
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white">
                    <div class="card-body p-4">
                        <p class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center gap-2" style="font-size:0.72rem;letter-spacing:0.5px;">
                            <i class="bi bi-pin-map-fill text-success"></i>
                            Municipios Registrados ({{ $departamento->municipios->count() }})
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($departamento->municipios as $municipio)
                                <span class="badge rounded-pill px-3 py-2 fw-semibold"
                                      style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;font-size:0.75rem;">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $municipio->nombre }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── Acciones ── --}}
                <div class="d-flex align-items-center justify-content-between mt-4">
                    <a href="{{ route('departamentos.index') }}" class="btn btn-light rounded-pill px-4 fw-semibold">
                        <i class="bi bi-chevron-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="bi bi-floppy me-1"></i> Guardar Cambios
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
