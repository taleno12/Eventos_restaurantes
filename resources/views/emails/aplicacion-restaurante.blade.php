{{-- resources/views/emails/aplicacion-restaurante.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Aplicación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f4f5; color: #18181b; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1c1c1c 0%, #2d1f0e 100%); padding: 40px 40px 32px; text-align: center; }
        .header .logo { color: #f97316; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 16px; }
        .header h1 { color: #fff; font-size: 26px; font-weight: 700; line-height: 1.3; }
        .header p { color: #a1a1aa; margin-top: 8px; font-size: 14px; }
        .badge { display: inline-block; background: #f97316; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 4px 12px; border-radius: 100px; margin-bottom: 12px; }
        .body { padding: 40px; }
        .section-title { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #71717a; margin-bottom: 16px; border-bottom: 1px solid #f4f4f5; padding-bottom: 8px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 28px; }
        .info-item { background: #fafafa; border: 1px solid #e4e4e7; border-radius: 10px; padding: 14px 16px; }
        .info-item .label { font-size: 11px; color: #71717a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .info-item .value { font-size: 15px; color: #18181b; font-weight: 600; }
        .text-box { background: #fafafa; border: 1px solid #e4e4e7; border-radius: 10px; padding: 16px; margin-bottom: 28px; font-size: 14px; line-height: 1.7; color: #3f3f46; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 28px; }
        .chip { background: #fff7ed; border: 1px solid #fed7aa; color: #c2410c; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 100px; }
        .cta-block { background: linear-gradient(135deg, #1c1c1c, #2d1f0e); border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 28px; }
        .cta-block p { color: #a1a1aa; font-size: 13px; margin-bottom: 16px; }
        .cta-btn { display: inline-block; background: #f97316; color: #fff; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 8px; text-decoration: none; }
        .footer { padding: 24px 40px; background: #fafafa; border-top: 1px solid #e4e4e7; text-align: center; }
        .footer p { font-size: 12px; color: #a1a1aa; line-height: 1.6; }
        .footer strong { color: #f97316; }
        @media (max-width: 500px) {
            .info-grid { grid-template-columns: 1fr; }
            .body, .header { padding: 28px 24px; }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="logo">🍽 GastroNicaragua</div>
        <span class="badge">Nueva aplicación</span>
        <h1>{{ $aplicacion['nombre'] }} {{ $aplicacion['apellido'] }}<br>quiere trabajar contigo</h1>
        <p>Puesto: <strong style="color:#f97316">{{ $aplicacion['empleo_titulo'] }}</strong> — {{ $aplicacion['restaurante'] }}</p>
    </div>

    <!-- Body -->
    <div class="body">

        <!-- Datos personales -->
        <div class="section-title">📋 Datos del candidato</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Nombre completo</div>
                <div class="value">{{ $aplicacion['nombre'] }} {{ $aplicacion['apellido'] }}</div>
            </div>
            <div class="info-item">
                <div class="label">Edad</div>
                <div class="value">{{ $aplicacion['edad'] }} años</div>
            </div>
            <div class="info-item">
                <div class="label">Correo electrónico</div>
                <div class="value" style="font-size:13px">{{ $aplicacion['email'] }}</div>
            </div>
            <div class="info-item">
                <div class="label">Teléfono / WhatsApp</div>
                <div class="value">{{ $aplicacion['telefono'] }}</div>
            </div>
            <div class="info-item" style="grid-column: span 2">
                <div class="label">Municipio de residencia</div>
                <div class="value">{{ $aplicacion['municipio'] }}</div>
            </div>
        </div>

        <!-- Disponibilidad -->
        @if(!empty($aplicacion['disponibilidad']))
        <div class="section-title">🕐 Disponibilidad horaria</div>
        <div class="chips" style="margin-bottom: 28px">
            @foreach($aplicacion['disponibilidad'] as $turno)
                <span class="chip">{{ $turno }}</span>
            @endforeach
        </div>
        @endif

        <!-- Experiencia -->
        @if($aplicacion['experiencia'] && $aplicacion['experiencia'] !== 'No especificada')
        <div class="section-title">💼 Experiencia relevante</div>
        <div class="text-box">{{ $aplicacion['experiencia'] }}</div>
        @endif

        <!-- Mensaje -->
        @if(!empty($aplicacion['mensaje']))
        <div class="section-title">💬 Mensaje del candidato</div>
        <div class="text-box">{{ $aplicacion['mensaje'] }}</div>
        @endif

        <!-- CV adjunto -->
        @if(isset($curriculumPath) && $curriculumPath)
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:14px 16px; margin-bottom:28px; display:flex; align-items:center; gap:10px;">
            <span style="font-size:20px">📎</span>
            <div>
                <div style="font-size:13px; font-weight:700; color:#15803d;">Currículum adjunto</div>
                <div style="font-size:12px; color:#16a34a;">El CV del candidato se encuentra adjunto a este correo.</div>
            </div>
        </div>
        @else
        <div style="background:#fefce8; border:1px solid #fde68a; border-radius:10px; padding:14px 16px; margin-bottom:28px;">
            <div style="font-size:12px; color:#92400e;">ℹ️ El candidato no adjuntó currículum.</div>
        </div>
        @endif

        <!-- CTA -->
        <div class="cta-block">
            <p>¿Interesado en este candidato? Contáctalo directamente por WhatsApp o correo.</p>
            <a href="https://wa.me/505{{ preg_replace('/\D/', '', $aplicacion['telefono']) }}"
               class="cta-btn">💬 Contactar por WhatsApp</a>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Este correo fue enviado automáticamente por <strong>GastroNicaragua</strong><br>
        Plataforma de empleos para el sector gastronómico de Nicaragua.</p>
    </div>

</div>
</body>
</html>