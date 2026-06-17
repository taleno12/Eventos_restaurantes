{{-- resources/views/components/apply-modal.blade.php --}}

<!-- Botón que activa el modal -->
<button
    type="button"
    onclick="abrirModalAplicar()"
    class="w-full flex items-center justify-center gap-2 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 text-base cursor-pointer border-0"
    style="outline: none; background:#2563eb;"
    onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
    </svg>
    Aplicar a esta vacante
</button>

<!-- ===== MODAL ===== -->
<div id="applyModal"
     style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; padding:1rem; background:rgba(0,0,0,0.80); backdrop-filter:blur(4px);">

    <div class="modal-card"
         style="position:relative; width:100%; max-width:680px; max-height:90vh; overflow-y:auto; border-radius:1rem; background:#1a1a1a; border:1px solid #2e2e2e;">

        <!-- Header -->
        <div style="position:sticky; top:0; z-index:10; display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #2e2e2e; background:#1a1a1a;">
            <div>
                <p style="font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:#60a5fa; margin:0 0 4px;">
                    {{ $empleo->restaurante->nombre ?? 'Restaurante' }}
                </p>
                <h2 style="font-size:20px; font-weight:800; color:#fff; margin:0;">
                    Aplicar: {{ $empleo->titulo }}
                </h2>
            </div>
            <button type="button"
                    onclick="cerrarModalAplicar()"
                    style="background:transparent; border:none; color:#6b7280; cursor:pointer; padding:8px; border-radius:8px; line-height:1;"
                    onmouseover="this.style.background='#2e2e2e'; this.style.color='#fff';"
                    onmouseout="this.style.background='transparent'; this.style.color='#6b7280';">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="applyForm"
              action="{{ route('empleos.aplicar', $empleo->id) }}"
              method="POST"
              enctype="multipart/form-data"
              style="padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">
            @csrf

            <input type="hidden" name="empleo_id"         value="{{ $empleo->id }}">
            <input type="hidden" name="empleo_titulo"     value="{{ $empleo->titulo }}">
            <input type="hidden" name="restaurante_email" value="{{ $empleo->restaurante->email ?? '' }}">

            <!-- Nombre y Apellido -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                        Nombre <span style="color:#60a5fa">*</span>
                    </label>
                    <input type="text" name="nombre" required placeholder="Tu nombre"
                           value="{{ old('nombre') }}"
                           style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none;"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                        Apellido <span style="color:#60a5fa">*</span>
                    </label>
                    <input type="text" name="apellido" required placeholder="Tu apellido"
                           value="{{ old('apellido') }}"
                           style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none;"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">
                </div>
            </div>

            <!-- Email y Teléfono -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                        Correo electrónico <span style="color:#60a5fa">*</span>
                    </label>
                    <input type="email" name="email" required placeholder="correo@ejemplo.com"
                           value="{{ old('email') }}"
                           style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none;"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                        Teléfono / WhatsApp <span style="color:#60a5fa">*</span>
                    </label>
                    <input type="tel" name="telefono" required placeholder="+505 8888-0000"
                           value="{{ old('telefono') }}"
                           style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none;"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">
                </div>
            </div>

            <!-- Edad y Municipio -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                        Edad <span style="color:#60a5fa">*</span>
                    </label>
                    <input type="number" name="edad" required min="18" max="70" placeholder="Ej: 23"
                           value="{{ old('edad') }}"
                           style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none;"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                        Municipio donde vives <span style="color:#60a5fa">*</span>
                    </label>
                    <input type="text" name="municipio" required placeholder="Ej: Masatepe"
                           value="{{ old('municipio') }}"
                           style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none;"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">
                </div>
            </div>

            <!-- Experiencia -->
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                    Experiencia relevante
                </label>
                <textarea name="experiencia" rows="3"
                          placeholder="Describe brevemente tu experiencia en el área..."
                          style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; resize:none;"
                          onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">{{ old('experiencia') }}</textarea>
            </div>

            <!-- Currículum -->
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                    Currículum Vitae <span style="color:#6b7280; font-weight:400; text-transform:none;">(PDF, DOC — máx. 5MB)</span>
                </label>
                <label id="cvDropzone"
                       for="cvInput"
                       style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; height:120px; border-radius:8px; border:2px dashed #333; background:#252525; cursor:pointer; transition:border-color 0.2s;"
                       onmouseover="this.style.borderColor='#2563eb'" onmouseout="if(!cvTienArchivo) this.style.borderColor='#333'">
                    <div id="cvPlaceholder" style="text-align:center;">
                        <svg style="margin:0 auto 8px; color:#4b5563; width:32px; height:32px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p style="font-size:13px; color:#6b7280; margin:0;">Arrastra tu CV aquí o <span style="color:#60a5fa;">selecciona archivo</span></p>
                    </div>
                    <div id="cvSelected" style="display:none; text-align:center;">
                        <svg style="margin:0 auto 4px; color:#4ade80; width:28px; height:28px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p id="cvFileName" style="font-size:13px; color:#4ade80; font-weight:600; margin:0;"></p>
                    </div>
                    <input id="cvInput" type="file" name="curriculum" accept=".pdf,.doc,.docx"
                           style="display:none;" onchange="handleCvChange(this)">
                </label>
            </div>

            <!-- Mensaje adicional -->
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                    Mensaje adicional (opcional)
                </label>
                <textarea name="mensaje" rows="2"
                          placeholder="¿Algo más que quieras contarle al empleador?"
                          style="width:100%; padding:12px 14px; border-radius:8px; background:#252525; border:1px solid #333; color:#fff; font-size:14px; outline:none; resize:none;"
                          onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#333'">{{ old('mensaje') }}</textarea>
            </div>

            <!-- Botón Submit -->
            <button type="submit"
                    style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; background:#2563eb; color:#fff; font-weight:800; font-size:15px; padding:14px 24px; border-radius:12px; border:none; cursor:pointer;"
                    onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Enviar mi aplicación
            </button>

        </form>
    </div>
</div>

<style>
    .modal-card { animation: gastroSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes gastroSlideUp {
        from { opacity:0; transform:translateY(24px) scale(0.97); }
        to   { opacity:1; transform:translateY(0) scale(1); }
    }
    @media (max-width: 600px) {
        #applyModal .modal-card > form > div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    var cvTienArchivo = false;

    function abrirModalAplicar() {
        var m = document.getElementById('applyModal');
        m.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalAplicar() {
        var m = document.getElementById('applyModal');
        m.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('applyModal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalAplicar();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModalAplicar();
    });

    function handleCvChange(input) {
        if (input.files && input.files[0]) {
            cvTienArchivo = true;
            document.getElementById('cvPlaceholder').style.display = 'none';
            document.getElementById('cvSelected').style.display    = 'block';
            document.getElementById('cvFileName').textContent       = input.files[0].name;
            document.getElementById('cvDropzone').style.borderColor = '#22c55e';
        }
    }

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            abrirModalAplicar();
        });
    @endif
</script>
