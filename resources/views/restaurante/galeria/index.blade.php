@extends('restaurante.layout')
@section('title', 'Galería')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--text);">
                <i class="bi bi-images text-primary me-2"></i> Galería
            </h1>
            <p class="mb-0 small" style="color:var(--muted);">
                <i class="bi bi-circle-fill text-secondary me-1" style="font-size:6px;vertical-align:middle;"></i>
                Fotos de {{ $restaurante->nombre }}
            </p>
        </div>
    </div>

    {{-- Subir fotos --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4" style="background:var(--card-bg) !important;">
        <div class="card-header border-bottom py-3 px-4" style="background:var(--table-header) !important;">
            <span class="fw-bold" style="font-size:11px;text-transform:uppercase;letter-spacing:0.12em;color:var(--muted);">
                Subir nuevas fotos
            </span>
            <span style="font-size:11px;font-weight:500;color:var(--muted);">(máx. 10 a la vez)</span>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('restaurante.galeria.store') }}" enctype="multipart/form-data" id="form-galeria">
                @csrf

                <label for="fotos-input" id="drop-zone"
                       class="rounded-3 text-center d-block position-relative overflow-hidden"
                       style="border:2px dashed var(--input-border);padding:40px;cursor:pointer;transition:border-color 0.2s, background 0.2s;">
                    <i class="bi bi-cloud-upload d-block mb-3" style="font-size:32px;color:var(--muted);"></i>
                    <p class="mb-1" style="font-size:13px;color:var(--muted);">
                        Arrastra fotos aquí o <span class="fw-bold" style="color:var(--primary);">haz clic para seleccionar</span>
                    </p>
                    <p class="mb-0" style="font-size:11px;color:var(--muted);">JPG, PNG, WEBP — máx. 3 MB por foto</p>
                    <input type="file" name="fotos[]" id="fotos-input" multiple accept="image/*"
                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                           style="cursor:pointer;">
                </label>

                <div id="preview-grid" style="display:none;" class="mt-3">
                    <div id="preview-fotos" class="mb-3"
                         style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:10px;"></div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-upload me-1"></i> Subir <span id="count-label"></span>
                    </button>
                </div>
            </form>

            @if($errors->has('fotos') || $errors->has('fotos.*'))
                <div class="alert alert-danger border-0 shadow-sm mt-3 mb-0" role="alert" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2) !important;color:#ef4444;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill fs-5 mt-1"></i>
                        <div>
                            @foreach($errors->get('fotos.*') as $msgs)
                                @foreach($msgs as $msg)<div>{{ $msg }}</div>@endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Fotos actuales --}}
    <div class="card border-0 shadow-sm rounded-3" style="background:var(--card-bg) !important;">
        <div class="card-header border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="background:var(--table-header) !important;">
            <span class="fw-bold" style="font-size:11px;text-transform:uppercase;letter-spacing:0.12em;color:var(--muted);">
                Fotos actuales
            </span>
            <span style="font-size:11px;color:var(--muted);">
                {{ $fotos->count() }} foto{{ $fotos->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        <div class="card-body p-4">
            @if($fotos->count() > 0)
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
                    @foreach($fotos as $foto)
                    <div class="position-relative rounded-3 overflow-hidden"
                         style="aspect-ratio:1;border:1px solid var(--card-border);">
                        <img src="{{ asset('storage/'.$foto->ruta_foto) }}"
                             class="w-100 h-100"
                             style="object-fit:cover;display:block;transition:transform 0.3s;"
                             onmouseover="this.style.transform='scale(1.05)'"
                             onmouseout="this.style.transform='scale(1)'">
                        <form method="POST" action="{{ route('restaurante.galeria.destroy', $foto) }}"
                              onsubmit="return confirm('¿Eliminar esta foto?')"
                              style="position:absolute;top:6px;right:6px;">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                                    style="width:28px;height:28px;padding:0;border-radius:8px;font-size:11px;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5" style="color:var(--muted);">
                    <i class="bi bi-images d-block display-6 mb-3" style="opacity:0.3;"></i>
                    <span class="fs-6 d-block" style="color:var(--text);">No tienes fotos en tu galería aún.</span>
                    <span class="small">¡Sube tu primera foto!</span>
                </div>
            @endif
        </div>
    </div>

</div>

<style>
    .rounded-top-3 { border-radius: 0.5rem 0.5rem 0 0 !important; }
    #drop-zone:hover { border-color: var(--primary) !important; background: var(--primary-light); }
    #drop-zone.dragover { border-color: var(--primary) !important; background: var(--primary-light); }
</style>

@endsection

@section('scripts')
<script>
const input        = document.getElementById('fotos-input');
const previewGrid  = document.getElementById('preview-grid');
const previewFotos = document.getElementById('preview-fotos');
const countLabel   = document.getElementById('count-label');
const dropZone     = document.getElementById('drop-zone');

let archivosAcumulados = [];

function agregarArchivos(nuevos) {
    const MAX = 10;
    Array.from(nuevos).forEach(file => {
        const existe = archivosAcumulados.some(f => f.name === file.name && f.size === file.size);
        if (!existe && archivosAcumulados.length < MAX) {
            archivosAcumulados.push(file);
        }
    });
    sincronizarInput();
    renderPreviews();
}

function sincronizarInput() {
    const dt = new DataTransfer();
    archivosAcumulados.forEach(f => dt.items.add(f));
    input.files = dt.files;
}

function renderPreviews() {
    previewFotos.innerHTML = '';
    archivosAcumulados.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.cssText = 'aspect-ratio:1;border-radius:10px;overflow:hidden;border:1px solid var(--card-border);position:relative;';
            div.innerHTML = `
                <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">
                <button type="button" data-idx="${idx}"
                    style="position:absolute;top:4px;right:4px;background:rgba(220,38,38,0.85);border:none;color:white;width:22px;height:22px;border-radius:6px;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center;">
                    ✕
                </button>`;
            div.querySelector('button').addEventListener('click', () => {
                archivosAcumulados.splice(idx, 1);
                sincronizarInput();
                renderPreviews();
            });
            previewFotos.appendChild(div);
        };
        reader.readAsDataURL(file);
    });

    const total = archivosAcumulados.length;
    countLabel.textContent = total > 0 ? `(${total} foto${total !== 1 ? 's' : ''})` : '';
    previewGrid.style.display = total > 0 ? 'block' : 'none';
}

input.addEventListener('change', function () {
    agregarArchivos(this.files);
});

dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    agregarArchivos(e.dataTransfer.files);
});
</script>
@endsection
