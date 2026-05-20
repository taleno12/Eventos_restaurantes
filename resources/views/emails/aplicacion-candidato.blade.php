{{-- resources/views/emails/aplicacion-candidato.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aplicación recibida</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f4f5; color: #18181b; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1c1c1c 0%, #2d1f0e 100%); padding: 48px 40px; text-align: center; }
        .check { width: 64px; height: 64px; background: #f97316; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; }
        .header h1 { color: #fff; font-size: 24px; font-weight: 700; }
        .header p { color: #a1a1aa; margin-top: 8px; font-size: 14px; }
        .body { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 700; color: #18181b; margin-bottom: 12px; }
        .text { font-size: 14px; color: #52525b; line-height: 1.7; margin-bottom: 24px; }
        .summary-box { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px 24px; margin-bottom: 28px; }
        .summary-box .label { font-size: 11px; color: #c2410c; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #fed7aa; font-size: 13px; }
        .summary-row:last-child { border-bottom: none; }
        .summary-row .key { color: #78350f; font-weight: 600; }
        .summary-row .val { color: #1c1917; font-weight: 700; }
        .tips { background: #fafafa; border: 1px solid #e4e4e7; border-radius: 12px; padding: 20px 24px; margin-bottom: 28px; }
        .tips .tip-title { font-size: 13px; font-weight: 700; color: #18181b; margin-bottom: 12px; }
        .tip { display: flex; gap: 10px; margin-bottom: 10px; font-size: 13px; color: #52525b; line-height: 1.5; }
        .tip span { flex-shrink: 0; }
        .footer { padding: 24px 40px; background: #fafafa; border-top: 1px solid #e4e4e7; text-align: center; }
        .footer p { font-size: 12px; color: #a1a1aa; line-height: 1.6; }
        .footer strong { color: #f97316; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="check">✓</div>
        <h1>¡Aplicación enviada!</h1>
        <p>Tu solicitud fue recibida correctamente</p>
    </div>

    <div class="body">
        <div class="greeting">Hola, {{ $aplicacion['nombre'] }} 👋</div>
        <p class="text">
            Tu aplicación para el puesto de <strong>{{ $aplicacion['empleo_titulo'] }}</strong>
            en <strong>{{ $aplicacion['restaurante'] }}</strong> fue recibida con éxito.
            El empleador revisará tu perfil y se pondrá en contacto contigo pronto.
        </p>

        <!-- Resumen -->
        <div class="summary-box">
            <div class="label">📋 Resumen de tu aplicación</div>
            <div class="summary-row">
                <span class="key">Puesto</span>
                <span class="val">{{ $aplicacion['empleo_titulo'] }}</span>
            </div>
            <div class="summary-row">
                <span class="key">Establecimiento</span>
                <span class="val">{{ $aplicacion['restaurante'] }}</span>
            </div>
            <div class="summary-row">
                <span class="key">Tu correo</span>
                <span class="val">{{ $aplicacion['email'] }}</span>
            </div>
            <div class="summary-row">
                <span class="key">Tu teléfono</span>
                <span class="val">{{ $aplicacion['telefono'] }}</span>
            </div>
            @if(!empty($aplicacion['disponibilidad']))
            <div class="summary-row">
                <span class="key">Disponibilidad</span>
                <span class="val">{{ implode(', ', $aplicacion['disponibilidad']) }}</span>
            </div>
            @endif
        </div>

        <!-- Tips -->
        <div class="tips">
            <div class="tip-title">💡 Mientras esperas...</div>
            <div class="tip"><span>📱</span> Mantén tu WhatsApp activo al <strong>{{ $aplicacion['telefono'] }}</strong>, el empleador puede contactarte por ahí.</div>
            <div class="tip"><span>📧</span> Revisa tu correo, incluyendo la carpeta de spam, por si recibes una respuesta.</div>
            <div class="tip"><span>🎯</span> Puedes seguir aplicando a otras vacantes en GastroNicaragua mientras esperas.</div>
        </div>
    </div>

    <div class="footer">
        <p>Este mensaje fue generado automáticamente por <strong>GastroNicaragua</strong>.<br>
        Por favor no respondas a este correo.</p>
    </div>

</div>
</body>
</html>