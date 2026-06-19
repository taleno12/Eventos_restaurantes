@extends('restaurante.layout')
@section('title', 'Soporte Técnico')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Soporte Técnico</div>
        <div class="page-sub">¿Tienes algún problema o duda? Estamos aquí para ayudarte</div>
    </div>
    {{-- Estado del sistema --}}
    <div class="panel-card" style="padding:12px 18px;display:flex;align-items:center;gap:12px;border-left:3px solid #22c55e;">
        <div style="width:8px;height:8px;background:#22c55e;border-radius:50%;animation:pulse 2s infinite;"></div>
        <div>
            <div style="font-size:12px;font-weight:700;color:var(--text);">Sistema operativo</div>
            <div style="font-size:11px;color:#4a5568;">Sin incidencias reportadas</div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="panel-alert panel-alert-success">
        <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
    </div>
@endif

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

    {{-- Columna izquierda: formulario --}}
    <div class="panel-card">
        <div class="card-header">Enviar un mensaje</div>
        <div class="card-body">
            <p style="font-size:14px;color:#4a5568;margin-bottom:20px;line-height:1.6;">
                Completa el formulario y serás redirigido a WhatsApp con tu mensaje listo para enviar.
                Nuestro equipo de soporte te responderá lo antes posible.
            </p>

            <form id="form-soporte" onsubmit="enviarSoporte(event)">
                <div style="margin-bottom:16px;">
                    <label class="form-label fw-semibold" style="font-size:13px;">Asunto *</label>
                    <select id="asunto" class="form-select" required>
                        <option value="">Selecciona un tema</option>
                        <option value="Problema técnico">Problema técnico / Error en el panel</option>
                        <option value="Duda sobre eventos">Duda sobre eventos</option>
                        <option value="Duda sobre empleos">Duda sobre ofertas de empleo</option>
                        <option value="Duda sobre menú">Duda sobre el menú / platos</option>
                        <option value="Duda sobre pedidos">Duda sobre pedidos</option>
                        <option value="Duda sobre mi perfil">Duda sobre mi perfil público</option>
                        <option value="Solicitud de evento destacado">Solicitud de evento destacado</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div style="margin-bottom:16px;">
                    <label class="form-label fw-semibold" style="font-size:13px;">Describe tu problema o consulta *</label>
                    <textarea id="mensaje" class="form-control" rows="5" required
                              placeholder="Cuéntanos con detalle qué necesitas, incluyendo capturas de pantalla si es posible (las podrás adjuntar directamente en WhatsApp)."></textarea>
                </div>

                <button type="submit" class="btn-primary-panel" style="width:100%;justify-content:center;padding:12px;background:#22c55e;box-shadow:0 2px 8px rgba(34,197,94,0.25);">
                    <i class="bi bi-whatsapp" style="font-size:16px;"></i> Enviar por WhatsApp
                </button>
            </form>
        </div>
    </div>

    {{-- Columna derecha: info de contacto --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        <div class="panel-card">
            <div class="card-header">Horario de atención</div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:14px;">
                    <span style="color:#4a5568;font-weight:600;"><i class="bi bi-calendar-week" style="color:var(--primary);margin-right:6px;"></i>Lunes a Viernes</span>
                    <span style="font-weight:700;color:var(--text);">8:00 AM – 6:00 PM</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:14px;">
                    <span style="color:#4a5568;font-weight:600;"><i class="bi bi-calendar-week" style="color:var(--primary);margin-right:6px;"></i>Sábados</span>
                    <span style="font-weight:700;color:var(--text);">9:00 AM – 1:00 PM</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:14px;">
                    <span style="color:#4a5568;font-weight:600;"><i class="bi bi-calendar-x" style="color:#a8a29e;margin-right:6px;"></i>Domingos</span>
                    <span style="font-weight:700;color:#a8a29e;">Cerrado</span>
                </div>
            </div>
        </div>

        <div class="panel-card">
            <div class="card-header">Preguntas frecuentes</div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:3px;">
                        <i class="bi bi-question-circle-fill" style="color:var(--primary);margin-right:6px;"></i>
                        ¿Cómo destaco un evento en el banner principal?
                    </div>
                    <p style="font-size:14px;color:#4a5568;line-height:1.5;margin-left:20px;">
                        Los eventos destacados son gestionados por nuestro equipo. Contáctanos para solicitarlo.
                    </p>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:3px;">
                        <i class="bi bi-question-circle-fill" style="color:var(--primary);margin-right:6px;"></i>
                        Subí una foto pero no aparece
                    </div>
                    <p style="font-size:14px;color:#4a5568;line-height:1.5;margin-left:20px;">
                        Verifica que sea JPG, PNG o WEBP y pese menos de 3MB. Si el problema persiste, contáctanos.
                    </p>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:3px;">
                        <i class="bi bi-question-circle-fill" style="color:var(--primary);margin-right:6px;"></i>
                        Un pedido no me llegó
                    </div>
                    <p style="font-size:14px;color:#4a5568;line-height:1.5;margin-left:20px;">
                        Revisa la sección Pedidos, se actualiza cada 30 seg. Si no aparece, contáctanos con el nombre del cliente.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
    50% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
}
</style>
@endsection

@section('scripts')
<script>
function enviarSoporte(e) {
    e.preventDefault();
    const asunto  = document.getElementById('asunto').value;
    const mensaje = document.getElementById('mensaje').value;
    const restaurante = "{{ $restaurante->nombre }}";

    const texto = `Hola, soy *${restaurante}* (panel de restaurante).\n\n` +
                  `*Asunto:* ${asunto}\n\n` +
                  `*Mensaje:*\n${mensaje}`;

    const url = `https://wa.me/50583767512?text=${encodeURIComponent(texto)}`;
    window.open(url, '_blank');
}
</script>
@endsection
