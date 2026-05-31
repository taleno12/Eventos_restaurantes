@extends('restaurante.layout')
@section('title', 'Galería')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Galería</div>
        <div class="page-sub">Fotos de {{ $restaurante->nombre }}</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Formulario subir fotos --}}
<div class="card card-body" style="margin-bottom:20px;">
    <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">
        Subir nuevas fotos <span style="color:#444;font-weight:600;">(máx. 10 a la vez)</span>
    </div>
    <form method="POST" action="{{ route('restaurante.galeria.store') }}" enctype="multipart/form-data" id="form-galeria">
        @csrf
        <div id="drop-zone" style="border:2px dashed var(--border);border-radius:12px;padding:36px;text-align:center;cursor:pointer;position:relative;transition:border-color 0.2s;">
            <i class="fas fa-cloud-upload-alt" style="font-size:32px;color:#333;margin-bottom:12px;display:block;"></i>
            <p style="font-size:13px;color:var(--muted);">Arrastra fotos aquí o <span style="color:var(--orange);font-weight:700;">haz clic para seleccionar</span></p>
            <p style="font-size:11px;color:#444;margin-top:6px;">JPG, PNG, WEBP — máx. 3 MB por foto</p>
            <input type="file" name="fotos[]" id="fotos-input" multiple accept="image/*"
                   style="position:absolute;inset:0;opacity:0;cursor:pointer;">
        </div>

        {{-- Preview --}}
        <div id="preview-grid" style="display:none;margin-top:16px;">
            <div id="preview-fotos" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:10px;margin-bottom:16px;"></div>
            <button type="submit" class="btn btn-orange">
                <i class="fas fa-upload"></i> Subir <span id="count-label"></span>
            </button>
        </div>
    </form>

    @if($errors->has('fotos') || $errors->has('fotos.*'))
        <div class="alert alert-error" style="margin-top:12px;">
            @foreach($errors->get('fotos.*') as $msgs)
                @foreach($msgs as $msg)<div>{{ $msg }}</div>@endforeach
            @endforeach
        </div>
    @endif
</div>

{{-- Grid de fotos existentes --}}
<div class="card card-body">
    <div style="font-size:11px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">
        Fotos actuales <span style="color:#444;">— {{ $fotos->count() }} foto{{ $fotos->count() !== 1 ? 's' : '' }}</span>
    </div>

    @if($fotos->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
            @foreach($fotos as $foto)
            <div style="position:relative;border-radius:12px;overflow:hidden;aspect-ratio:1;border:1px solid var(--border);group" class="foto-item">
                <img src="{{ asset('storage/'.$foto->ruta_foto) }}"
                     style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.3s;"
                     onmouseover="this.style.transform='scale(1.05)'"
                     onmouseout="this.style.transform='scale(1)'">
                <form method="POST" action="{{ route('restaurante.galeria.destroy', $foto) }}"
                      onsubmit="return confirm('¿Eliminar esta foto?')"
                      style="position:absolute;top:6px;right:6px;">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="background:rgba(0,0,0,0.75);border:none;color:#e74c3c;width:28px;height:28px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:11px;backdrop-filter:blur(4px);">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-images"></i>
            <p>No tienes fotos en tu galería aún.<br>¡Sube tu primera foto!</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
const input       = document.getElementById('fotos-input');
const previewGrid = document.getElementById('preview-grid');
const previewFotos = document.getElementById('preview-fotos');
const countLabel  = document.getElementById('count-label');
const dropZone    = document.getElementById('drop-zone');

input.addEventListener('change', function () {
    const files = Array.from(this.files);
    if (!files.length) return;

    previewFotos.innerHTML = '';
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.cssText = 'aspect-ratio:1;border-radius:10px;overflow:hidden;border:1px solid var(--border);';
            div.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            previewFotos.appendChild(div);
        };
        reader.readAsDataURL(file);
    });

    countLabel.textContent = `(${files.length} foto${files.length !== 1 ? 's' : ''})`;
    previewGrid.style.display = 'block';
});

// Highlight al arrastrar
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor = 'var(--orange)'; });
dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = 'var(--border)'; });
dropZone.addEventListener('drop', () => { dropZone.style.borderColor = 'var(--border)'; });
</script>
@endsection