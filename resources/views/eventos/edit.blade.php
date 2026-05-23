<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Editar Anuncio Gastronómico') }}
        </h2>
    </x-slot>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .field-label {
            display: block;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 8px;
        }
        .field-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            font-size: 14px;
            color: #1c1917;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
            background: white;
        }
        .field-input:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 4px rgba(234,88,12,0.08);
        }
        select.field-input { background: #fafaf9; cursor: pointer; }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 10px;
            margin-top: 14px;
        }
        .photo-thumb {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            background: #f5f5f4;
        }
        .photo-thumb img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        .photo-thumb .delete-btn {
            position: absolute;
            top: 5px; right: 5px;
            width: 24px; height: 24px;
            background: rgba(220,38,38,0.85);
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 10px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .photo-thumb:hover .delete-btn { opacity: 1; }
        .photo-add-btn {
            aspect-ratio: 1;
            border-radius: 12px;
            border: 2px dashed #d1d5db;
            background: #fafaf9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 600;
            position: relative;
        }
        .photo-add-btn:hover {
            border-color: #ea580c;
            color: #ea580c;
            background: #fff7ed;
        }
        .photo-add-btn input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%; height: 100%;
        }
        #new-photos-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .new-thumb {
            position: relative;
            width: 80px; height: 80px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #fed7aa;
        }
        .new-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .new-thumb .remove-new {
            position: absolute;
            top: 3px; right: 3px;
            width: 18px; height: 18px;
            background: rgba(220,38,38,0.85);
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 9px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
    </style>

    <div class="py-12 animate-fade-in bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:16px 20px;">
                    <p style="font-weight:800;font-size:13px;color:#dc2626;margin-bottom:8px;">
                        <i class="fas fa-exclamation-circle"></i> Corrige los siguientes errores:
                    </p>
                    <ul style="margin:0;padding-left:18px;font-size:13px;color:#b91c1c;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- CARD PRINCIPAL --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-[1.5rem] border border-gray-100">

                <div class="bg-gradient-to-r from-zinc-900 to-zinc-800 p-8">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-500/20 p-3 rounded-xl border border-yellow-500/30">
                            <i class="fas fa-edit text-yellow-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-lg">Modificar Evento: <span class="text-yellow-400">{{ $evento->titulo }}</span></h3>
                            <p class="text-gray-400 text-xs mt-0.5">Actualiza la información necesaria para tus clientes.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('eventos.update', $evento->id) }}" method="POST" enctype="multipart/form-data" id="edit-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            {{-- COL IZQUIERDA --}}
                            <div class="space-y-5">

                                <div>
                                    <label class="field-label">Título del Evento</label>
                                    <input type="text" name="titulo" value="{{ old('titulo', $evento->titulo) }}" required maxlength="100"
                                           class="field-input" placeholder="Ej: Festival del Vigorón">
                                </div>

                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                    <div>
                                        <label class="field-label">Fecha</label>
                                        <input type="date" name="fecha_evento"
                                               value="{{ old('fecha_evento', \Carbon\Carbon::parse($evento->fecha_evento)->format('Y-m-d')) }}"
                                               required class="field-input">
                                    </div>
                                    <div>
                                        <label class="field-label">Precio (C$)</label>
                                        <input type="number" name="precio" step="0.01"
                                               value="{{ old('precio', $evento->precio) }}"
                                               required class="field-input" placeholder="0.00">
                                    </div>
                                </div>

                                <div>
                                    <label class="field-label">Departamento</label>
                                    <select id="departamento_id" name="departamento_id" required class="field-input">
                                        <option value="" disabled>Seleccionar...</option>
                                        @foreach($departamentos as $dep)
                                            <option value="{{ $dep->id }}" {{ $evento->departamento_id == $dep->id ? 'selected' : '' }}>
                                                {{ $dep->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="field-label">Municipio</label>
                                    <select id="municipio_id" name="municipio_id" required class="field-input">
                                        <option value="" disabled>Seleccionar...</option>
                                        @foreach($municipios as $mun)
                                            <option value="{{ $mun->id }}" {{ $evento->municipio_id == $mun->id ? 'selected' : '' }}>
                                                {{ $mun->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="field-label">Restaurante / Local</label>
                                    <select id="restaurante_id" name="restaurante_id" required class="field-input">
                                        @foreach($restaurantes as $rest)
                                            <option value="{{ $rest->id }}" {{ $evento->restaurante_id == $rest->id ? 'selected' : '' }}>
                                                {{ $rest->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="field-label">Es un evento destacado?</label>
                                    <select name="is_destacado" class="field-input">
                                        <option value="0" {{ !$evento->is_destacado ? 'selected' : '' }}>Evento Normal</option>
                                        <option value="1" {{ $evento->is_destacado ? 'selected' : '' }}>Evento Destacado (Banner Principal)</option>
                                    </select>
                                </div>

                            </div>

                            {{-- COL DERECHA --}}
                            <div class="space-y-5">

                                <div>
                                    <label class="field-label">Imagen Principal</label>
                                    <div style="position:relative;border:2px dashed #e5e7eb;border-radius:18px;overflow:hidden;background:#fafaf9;min-height:180px;display:flex;align-items:center;justify-content:center;transition:border-color 0.2s;"
                                         id="drop-zone">
                                        <input type="file" name="imagen" id="imagen" accept="image/*"
                                               style="position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;z-index:10;">
                                        <div id="preview-container" class="{{ $evento->imagen ? 'hidden' : '' }}"
                                             style="text-align:center;padding:20px;pointer-events:none;">
                                            <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:#d1d5db;"></i>
                                            <p style="font-size:12px;color:#9ca3af;margin-top:8px;">Subir nueva imagen (Max. 2MB)</p>
                                        </div>
                                        <img id="image-preview"
                                             src="{{ $evento->imagen ? asset('storage/' . $evento->imagen) : '' }}"
                                             class="{{ $evento->imagen ? '' : 'hidden' }}"
                                             style="width:100%;height:180px;object-fit:cover;pointer-events:none;">
                                    </div>
                                    @if($evento->imagen)
                                        <p style="font-size:11px;color:#9ca3af;margin-top:6px;">
                                            <i class="fas fa-info-circle"></i> Haz clic para reemplazar la imagen actual
                                        </p>
                                    @endif
                                </div>

                                <div>
                                    <label class="field-label">Descripcion del Evento</label>
                                    <textarea name="descripcion" rows="5" maxlength="500"
                                              class="field-input" style="resize:none;"
                                              placeholder="Cuentanos mas sobre el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                                </div>

                            </div>
                        </div>

                        {{-- BOTONES --}}
                        <div style="margin-top:32px;padding-top:24px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;">
                            <a href="{{ route('eventos.index') }}"
                               style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:#9ca3af;text-decoration:none;">
                                <i class="fas fa-arrow-left"></i> Cancelar y volver
                            </a>
                            <button type="submit"
                                    style="background:#eab308;color:white;padding:14px 32px;border-radius:12px;font-weight:800;font-size:14px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(234,179,8,0.3);"
                                    onmouseover="this.style.background='#ca8a04'"
                                    onmouseout="this.style.background='#eab308'">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- CARD GALERIA DE FOTOS --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-[1.5rem] border border-gray-100">

                <div style="padding:28px 32px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:14px;">
                    <div style="width:44px;height:44px;background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-images" style="color:#ea580c;font-size:17px;"></i>
                    </div>
                    <div>
                        <h3 style="font-weight:800;font-size:16px;color:#1c1917;margin:0;">Galeria de Fotos Adicionales</h3>
                        <p style="font-size:12px;color:#9ca3af;margin:4px 0 0;">Las fotos adicionales se muestran en la pagina publica del evento.</p>
                    </div>
                </div>

                <div style="padding:28px 32px;">

                    {{-- Fotos existentes --}}
                    @if($evento->imagenes && $evento->imagenes->count() > 0)
                        <div>
                            <p class="field-label" style="margin-bottom:12px;">Fotos actuales</p>
                            <div class="photo-grid">
                                @foreach($evento->imagenes as $foto)
                                    <div class="photo-thumb">
                                        {{-- ← CORREGIDO: $foto->ruta en lugar de $foto->ruta_foto --}}
                                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto del evento">
                                        <form action="{{ route('evento.imagenes.destroy', $foto->id) }}" method="POST"
                                              style="margin:0;" onsubmit="return confirm('Eliminar esta foto?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="delete-btn" title="Eliminar foto">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach

                                {{-- Boton agregar mas --}}
                                <label class="photo-add-btn">
                                    <input type="file" id="add-more-photos" accept="image/*" multiple>
                                    <i class="fas fa-plus" style="font-size:18px;"></i>
                                    <span>Anadir</span>
                                </label>
                            </div>
                        </div>
                    @else
                        <p style="font-size:13px;color:#9ca3af;font-style:italic;margin-bottom:16px;">
                            Este evento aun no tiene fotos adicionales.
                        </p>
                        <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;border:2px dashed #e5e7eb;border-radius:16px;padding:36px 24px;background:#fafaf9;cursor:pointer;transition:all 0.2s;text-align:center;"
                               id="upload-zone-label">
                            <input type="file" id="add-more-photos" accept="image/*" multiple style="display:none;">
                            <i class="fas fa-cloud-upload-alt" style="font-size:32px;color:#d1d5db;"></i>
                            <div>
                                <p style="font-size:14px;font-weight:700;color:#6b7280;margin:0;">Arrastra o haz clic para subir fotos</p>
                                <p style="font-size:12px;color:#9ca3af;margin:4px 0 0;">JPG, PNG o WEBP - Puedes subir varias a la vez</p>
                            </div>
                        </label>
                    @endif

                    {{-- Preview fotos seleccionadas --}}
                    <div id="new-photos-preview"></div>

                    {{-- Formulario subida fotos adicionales --}}
                    <form action="{{ route('evento.imagenes.store', $evento->id) }}" method="POST"
                          enctype="multipart/form-data" id="photos-form" style="margin-top:16px;">
                        @csrf
                        <input type="file" name="fotos[]" id="fotos-hidden" accept="image/*" multiple style="display:none;">
                        <div id="upload-actions" style="display:none;gap:10px;align-items:center;">
                            <span id="files-count" style="font-size:13px;font-weight:600;color:#6b7280;"></span>
                            <button type="submit"
                                    style="background:#ea580c;color:white;padding:10px 24px;border-radius:10px;font-weight:700;font-size:13px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                                    onmouseover="this.style.background='#c2410c'"
                                    onmouseout="this.style.background='#ea580c'">
                                <i class="fas fa-upload"></i> Subir fotos seleccionadas
                            </button>
                            <button type="button" id="cancel-upload"
                                    style="background:transparent;border:1px solid #e5e7eb;color:#6b7280;padding:10px 20px;border-radius:10px;font-weight:600;font-size:13px;cursor:pointer;">
                                Cancelar
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Preview imagen principal
            const imgInput   = document.getElementById('imagen');
            const imgPreview = document.getElementById('image-preview');
            const imgHolder  = document.getElementById('preview-container');
            const dropZone   = document.getElementById('drop-zone');

            if (imgInput) {
                imgInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            imgPreview.src = e.target.result;
                            imgPreview.classList.remove('hidden');
                            if (imgHolder) imgHolder.classList.add('hidden');
                            dropZone.style.borderColor = '#ea580c';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Cargar municipios al cambiar departamento
            const depSelect  = document.getElementById('departamento_id');
            const munSelect  = document.getElementById('municipio_id');
            const restSelect = document.getElementById('restaurante_id');

            if (depSelect) {
                depSelect.addEventListener('change', function () {
                    const depId = this.value;
                    munSelect.disabled = true;
                    munSelect.innerHTML = '<option>Cargando municipios...</option>';
                    restSelect.disabled = true;
                    restSelect.innerHTML = '<option>Seleccionar municipio primero...</option>';

                    if (depId) {
                        fetch(`/api/departamentos/${depId}/municipios`)
                            .then(r => r.json())
                            .then(data => {
                                munSelect.innerHTML = '<option value="" disabled selected>Seleccionar municipio...</option>';
                                data.forEach(mun => {
                                    const opt = document.createElement('option');
                                    opt.value = mun.id;
                                    opt.textContent = mun.nombre;
                                    munSelect.appendChild(opt);
                                });
                                munSelect.disabled = false;
                            })
                            .catch(() => {
                                munSelect.innerHTML = '<option>Error de carga</option>';
                            });
                    }
                });
            }

            // Cargar restaurantes al cambiar municipio
            if (munSelect) {
                munSelect.addEventListener('change', function () {
                    const munId = this.value;
                    restSelect.disabled = true;
                    restSelect.innerHTML = '<option>Cargando locales...</option>';

                    if (munId) {
                        fetch(`/api/municipios/${munId}/restaurantes`)
                            .then(r => r.json())
                            .then(data => {
                                restSelect.innerHTML = '<option value="" disabled selected>Seleccionar local...</option>';
                                data.forEach(rest => {
                                    const opt = document.createElement('option');
                                    opt.value = rest.id;
                                    opt.textContent = rest.nombre;
                                    restSelect.appendChild(opt);
                                });
                                restSelect.disabled = false;
                            })
                            .catch(() => {
                                restSelect.innerHTML = '<option>Error de carga</option>';
                            });
                    }
                });
            }

            // Galeria: preview fotos adicionales
            const addPhotosInput = document.getElementById('add-more-photos');
            const fotosHidden    = document.getElementById('fotos-hidden');
            const previewWrap    = document.getElementById('new-photos-preview');
            const uploadActions  = document.getElementById('upload-actions');
            const filesCount     = document.getElementById('files-count');
            const cancelUpload   = document.getElementById('cancel-upload');

            let selectedFiles = [];

            function syncFilesToHidden() {
                const dt = new DataTransfer();
                selectedFiles.forEach(f => dt.items.add(f));
                if (fotosHidden) fotosHidden.files = dt.files;
            }

            function renderPreviews() {
                if (!previewWrap) return;
                previewWrap.innerHTML = '';
                selectedFiles.forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const wrap = document.createElement('div');
                        wrap.className = 'new-thumb';
                        wrap.innerHTML = `
                            <img src="${e.target.result}" alt="">
                            <button type="button" class="remove-new" data-idx="${idx}">
                                <i class="fas fa-times"></i>
                            </button>`;
                        previewWrap.appendChild(wrap);
                        wrap.querySelector('.remove-new').addEventListener('click', function () {
                            selectedFiles.splice(parseInt(this.dataset.idx), 1);
                            syncFilesToHidden();
                            renderPreviews();
                            updateActions();
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }

            function updateActions() {
                if (!uploadActions) return;
                if (selectedFiles.length > 0) {
                    uploadActions.style.display = 'flex';
                    if (filesCount) filesCount.textContent = `${selectedFiles.length} foto${selectedFiles.length > 1 ? 's' : ''} lista${selectedFiles.length > 1 ? 's' : ''}`;
                } else {
                    uploadActions.style.display = 'none';
                }
            }

            if (addPhotosInput) {
                addPhotosInput.addEventListener('change', function () {
                    Array.from(this.files).forEach(f => selectedFiles.push(f));
                    syncFilesToHidden();
                    renderPreviews();
                    updateActions();
                    this.value = '';
                });
            }

            if (cancelUpload) {
                cancelUpload.addEventListener('click', function () {
                    selectedFiles = [];
                    syncFilesToHidden();
                    renderPreviews();
                    updateActions();
                });
            }

            const uploadZoneLabel = document.getElementById('upload-zone-label');
            if (uploadZoneLabel) {
                uploadZoneLabel.addEventListener('mouseenter', () => uploadZoneLabel.style.borderColor = '#ea580c');
                uploadZoneLabel.addEventListener('mouseleave', () => uploadZoneLabel.style.borderColor = '#e5e7eb');
            }
        });
    </script>

</x-app-layout>