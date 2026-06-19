@extends('restaurante.layout')
@section('title', 'Información y Ayuda')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Información y Ayuda</div>
        <div class="page-sub">Manual de uso, términos y condiciones del panel</div>
    </div>
</div>

{{-- Tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;border-bottom:1px solid var(--card-border);overflow-x:auto;">
    <button onclick="cambiarTab('manual')" id="tab-manual"
            style="padding:10px 18px;border:none;background:none;font-size:13px;font-weight:700;cursor:pointer;color:var(--primary);border-bottom:2px solid var(--primary);white-space:nowrap;">
        <i class="bi bi-book-fill"></i> Manual de uso
    </button>
    <button onclick="cambiarTab('terminos')" id="tab-terminos"
            style="padding:10px 18px;border:none;background:none;font-size:13px;font-weight:700;cursor:pointer;color:var(--muted);border-bottom:2px solid transparent;white-space:nowrap;">
        <i class="bi bi-file-text-fill"></i> Términos y Condiciones
    </button>
    <button onclick="cambiarTab('faq')" id="tab-faq"
            style="padding:10px 18px;border:none;background:none;font-size:13px;font-weight:700;cursor:pointer;color:var(--muted);border-bottom:2px solid transparent;white-space:nowrap;">
        <i class="bi bi-question-circle-fill"></i> Preguntas Frecuentes
    </button>
</div>

{{-- ══ MANUAL DE USO ══ --}}
<div id="content-manual">

    <div class="panel-card" style="margin-bottom:16px;">
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                <div style="width:44px;height:44px;background:#fff7ed;border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:18px;flex-shrink:0;">
                    <i class="bi bi-rocket-takeoff-fill"></i>
                </div>
                <div>
                    <div style="font-size:16px;font-weight:800;color:var(--text);">Bienvenido al Panel del Restaurante</div>
                    <div style="font-size:13px;color:#4a5568;">Todo lo que necesitas para gestionar tu negocio en GastroNicaragua</div>
                </div>
            </div>
            <p style="font-size:14px;color:#4a5568;line-height:1.8;">
                Este panel te permite administrar la presencia de tu restaurante en la plataforma:
                publicar eventos, ofertas de empleo, tu menú digital, recibir pedidos en tiempo real,
                gestionar tu galería de fotos y revisar las opiniones de tus clientes — todo en un solo lugar.
            </p>
        </div>
    </div>

    {{-- Secciones del manual --}}
    @php
        $secciones = [
            [
                'icono' => 'bi-grid-1x2-fill', 'color' => 'orange',
                'titulo' => 'Dashboard',
                'texto' => 'Tu pantalla principal. Aquí ves un resumen rápido: total de eventos, empleos activos, fotos en galería y tus próximos eventos. Las "Acciones rápidas" te llevan directo a crear contenido nuevo.'
            ],
            [
                'icono' => 'bi-calendar-event-fill', 'color' => 'blue',
                'titulo' => 'Mis Eventos',
                'texto' => 'Crea y administra los eventos de tu restaurante (promociones, noches especiales, festivales). Cada evento necesita: título, descripción, precio, fecha, ubicación e imagen principal. Puedes agregar fotos adicionales a una galería del evento. Los eventos publicados desde aquí son "normales" — los eventos destacados del banner principal son gestionados por nuestro equipo.'
            ],
            [
                'icono' => 'bi-briefcase-fill', 'color' => 'purple',
                'titulo' => 'Mis Empleos',
                'texto' => 'Publica ofertas de trabajo para tu restaurante. Incluye título del puesto, descripción, requisitos, tipo de contrato, salario (opcional) y fecha límite. Puedes activar o desactivar una oferta sin eliminarla — las inactivas no se muestran a los candidatos.'
            ],
            [
                'icono' => 'bi-egg-fried', 'color' => 'green',
                'titulo' => 'Menú',
                'texto' => 'Aquí construyes el menú digital de tu restaurante. Cada plato tiene nombre, descripción, precio, categoría (Entradas, Platos fuertes, Bebidas, etc.) y foto. Puedes activar/desactivar platos según disponibilidad. Los platos activos aparecen en tu página pública para que los clientes hagan pedidos.'
            ],
            [
                'icono' => 'bi-images', 'color' => 'orange',
                'titulo' => 'Galería',
                'texto' => 'Sube fotos de tu local, ambiente, platos y eventos para mostrar en tu página pública. Puedes subir hasta 10 fotos a la vez (máx. 3MB cada una) y eliminarlas individualmente cuando quieras.'
            ],
            [
                'icono' => 'bi-bag-fill', 'color' => 'blue',
                'titulo' => 'Pedidos',
                'texto' => 'Aquí recibes en tiempo real los pedidos que los clientes hacen desde tu menú público. Los pedidos se organizan por estado: Pendiente → Confirmado → En preparación → Listo → Entregado. Cambia el estado desde el menú desplegable de cada pedido para que el cliente vea el progreso. El panel se actualiza automáticamente cada 30 segundos.'
            ],
            [
                'icono' => 'bi-bar-chart-fill', 'color' => 'purple',
                'titulo' => 'Estadísticas',
                'texto' => 'Visualiza qué platos se venden más en los últimos 30 días, con gráficos de barras y distribución, además de un ranking completo con unidades vendidas e ingresos generados por plato. Útil para decidir qué promocionar o ajustar precios.'
            ],
            [
                'icono' => 'bi-star-fill', 'color' => 'orange',
                'titulo' => 'Reseñas',
                'texto' => 'Consulta todas las opiniones que tus clientes dejan en tu página pública: calificación promedio, distribución de estrellas y el detalle de cada reseña. Las reseñas de 3 estrellas o menos se marcan para que puedas darles seguimiento.'
            ],
            [
                'icono' => 'bi-shop-window', 'color' => 'green',
                'titulo' => 'Mi Perfil',
                'texto' => 'Edita la información pública de tu restaurante: nombre, especialidad, descripción, ubicación (departamento, municipio, dirección y coordenadas GPS), teléfono, WhatsApp, redes sociales y foto de portada. Esta información es la que ven los usuarios en tu página.'
            ],
        ];
    @endphp

    @foreach($secciones as $sec)
    <div class="panel-card" style="margin-bottom:12px;">
        <div class="card-body" style="display:flex;gap:14px;align-items:flex-start;">
            <div class="metric-icon {{ $sec['color'] }}" style="flex-shrink:0;">
                <i class="bi {{ $sec['icono'] }}"></i>
            </div>
            <div>
                <div style="font-size:14px;font-weight:800;color:var(--text);margin-bottom:4px;">{{ $sec['titulo'] }}</div>
                <p style="font-size:14px;color:#4a5568;line-height:1.7;margin:0;">{{ $sec['texto'] }}</p>
            </div>
        </div>
    </div>
    @endforeach

</div>

{{-- ══ TÉRMINOS Y CONDICIONES ══ --}}
<div id="content-terminos" style="display:none;">
    <div class="panel-card">
        <div class="card-body">
            <p style="font-size:11px;color:#4a5568;text-transform:uppercase;letter-spacing:0.1em;font-weight:700;margin-bottom:20px;">
                Última actualización: {{ now()->format('d \d\e F \d\e Y') }}
            </p>

            @php
                $terminos = [
                    [
                        'titulo' => '1. Aceptación de los términos',
                        'texto' => 'Al registrarte y utilizar el Panel del Restaurante de GastroNicaragua, aceptas cumplir con estos términos y condiciones. Si no estás de acuerdo, debes abstenerte de utilizar la plataforma.'
                    ],
                    [
                        'titulo' => '2. Cuenta y responsabilidad',
                        'texto' => 'Eres responsable de mantener la confidencialidad de tu cuenta y contraseña. Toda actividad realizada desde tu cuenta se considera autorizada por ti. Notifica de inmediato cualquier uso no autorizado a nuestro equipo de soporte.'
                    ],
                    [
                        'titulo' => '3. Contenido publicado',
                        'texto' => 'El restaurante es responsable de la veracidad de la información publicada (eventos, empleos, menú, fotos, precios y datos de contacto). GastroNicaragua se reserva el derecho de remover contenido que sea falso, ofensivo, ilegal o que infrinja derechos de terceros, sin previo aviso.'
                    ],
                    [
                        'titulo' => '4. Eventos destacados',
                        'texto' => 'Los eventos publicados desde el panel del restaurante se consideran "eventos normales". La promoción de eventos en el banner principal (eventos destacados) es gestionada exclusivamente por el equipo administrativo de GastroNicaragua y puede estar sujeta a criterios adicionales o costos asociados, los cuales se comunicarán por separado.'
                    ],
                    [
                        'titulo' => '5. Pedidos y menú digital',
                        'texto' => 'Los precios y disponibilidad de los platos publicados en el menú digital son responsabilidad exclusiva del restaurante. GastroNicaragua actúa únicamente como intermediario tecnológico que conecta al cliente con el restaurante; no participa en la preparación, calidad o entrega de los alimentos, ni en disputas relacionadas con pagos realizados directamente entre cliente y restaurante.'
                    ],
                    [
                        'titulo' => '6. Imágenes y propiedad intelectual',
                        'texto' => 'El restaurante garantiza tener los derechos necesarios sobre las imágenes y contenido que sube a la plataforma (fotos de platos, portada, galería). GastroNicaragua no se hace responsable por reclamos de terceros relacionados con derechos de autor sobre contenido subido por el restaurante.'
                    ],
                    [
                        'titulo' => '7. Reseñas de clientes',
                        'texto' => 'Las reseñas son opiniones de usuarios reales y reflejan su experiencia personal. GastroNicaragua no modifica ni elimina reseñas a solicitud del restaurante, salvo en casos de contenido que viole nuestras políticas de comunidad (lenguaje ofensivo, información falsa comprobable, spam).'
                    ],
                    [
                        'titulo' => '8. Disponibilidad del servicio',
                        'texto' => 'Nos esforzamos por mantener el panel disponible de forma continua, pero no garantizamos un funcionamiento ininterrumpido. Pueden existir mantenimientos programados o interrupciones técnicas que serán comunicadas con la mayor anticipación posible.'
                    ],
                    [
                        'titulo' => '9. Modificaciones',
                        'texto' => 'GastroNicaragua puede actualizar estos términos en cualquier momento. Los cambios significativos serán notificados a través del panel o por correo electrónico. El uso continuado de la plataforma después de dichos cambios constituye la aceptación de los nuevos términos.'
                    ],
                    [
                        'titulo' => '10. Contacto',
                        'texto' => 'Para dudas sobre estos términos, contáctanos a través de la sección de Soporte dentro del panel, vía WhatsApp al +505 8540 6068.'
                    ],
                ];
            @endphp

            @foreach($terminos as $i => $term)
            <div style="margin-bottom:{{ $i === count($terminos) - 1 ? '0' : '20px' }};">
                <div style="font-size:14px;font-weight:800;color:var(--text);margin-bottom:6px;">{{ $term['titulo'] }}</div>
                <p style="font-size:14px;color:#4a5568;line-height:1.8;margin:0;">{{ $term['texto'] }}</p>
            </div>
            @if($i !== count($terminos) - 1)
                <div style="height:1px;background:var(--card-border);margin:16px 0;"></div>
            @endif
            @endforeach

        </div>
    </div>
</div>

{{-- ══ FAQ ══ --}}
<div id="content-faq" style="display:none;">
    @php
        $faqs = [
            [
                'q' => '¿Cómo solicito que mi evento aparezca en el banner principal?',
                'a' => 'Los eventos destacados son gestionados por nuestro equipo administrativo. Crea tu evento normalmente desde "Mis Eventos" y luego contáctanos por Soporte para solicitar la promoción.'
            ],
            [
                'q' => '¿Por qué no puedo marcar mi evento como destacado?',
                'a' => 'Por diseño, los restaurantes solo pueden publicar eventos "normales". Esto asegura que el banner principal mantenga calidad y orden, coordinado directamente por el equipo de GastroNicaragua.'
            ],
            [
                'q' => '¿Qué pasa si subo una imagen muy grande?',
                'a' => 'El sistema solo acepta JPG, PNG o WEBP. El límite es 2MB para eventos y platos, y 3MB para galería y portada. Si tu imagen pesa más, redúcela antes de subirla.'
            ],
            [
                'q' => '¿Cómo cambio el estado de un pedido?',
                'a' => 'En la sección "Pedidos", cada tarjeta tiene un menú desplegable con los estados disponibles. Selecciona el nuevo estado y se guardará automáticamente — el cliente verá el cambio reflejado en su seguimiento.'
            ],
            [
                'q' => '¿Los clientes ven mi número de teléfono?',
                'a' => 'Solo si lo agregas en "Mi Perfil". El campo de WhatsApp habilita el botón de contacto directo en tu página pública; si lo dejas vacío, ese botón no aparecerá.'
            ],
            [
                'q' => '¿Puedo eliminar una reseña que considero injusta?',
                'a' => 'No directamente desde el panel. Si consideras que una reseña viola nuestras políticas (lenguaje ofensivo, información falsa), contáctanos por Soporte con el detalle del caso.'
            ],
            [
                'q' => '¿Cómo desactivo temporalmente un plato sin eliminarlo?',
                'a' => 'En "Menú", cada plato tiene un botón "Ocultar/Activar". Al ocultarlo, deja de aparecer en tu página pública pero conserva toda su información para reactivarlo después.'
            ],
            [
                'q' => '¿Con qué frecuencia se actualiza el panel de pedidos?',
                'a' => 'Automáticamente cada 30 segundos. También puedes recargar la página manualmente para ver pedidos nuevos al instante.'
            ],
        ];
    @endphp

    @foreach($faqs as $i => $faq)
    <div class="panel-card" style="margin-bottom:10px;">
        <div class="card-body" style="padding:16px 22px;">
            <button onclick="toggleFaq({{ $i }})"
                    style="width:100%;display:flex;justify-content:space-between;align-items:center;gap:12px;background:none;border:none;cursor:pointer;text-align:left;padding:0;">
                <span style="font-size:14px;font-weight:700;color:var(--text);">
                    <i class="bi bi-patch-question-fill" style="color:var(--primary);margin-right:8px;"></i>
                    {{ $faq['q'] }}
                </span>
                <i class="bi bi-chevron-down" id="faq-icon-{{ $i }}" style="color:#4a5568;transition:transform 0.2s;flex-shrink:0;"></i>
            </button>
            <div id="faq-answer-{{ $i }}" style="display:none;margin-top:12px;padding-top:12px;border-top:1px solid var(--card-border);">
                <p style="font-size:14px;color:#4a5568;line-height:1.7;margin:0;">{{ $faq['a'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@section('scripts')
<script>
function cambiarTab(tab) {
    ['manual','terminos','faq'].forEach(t => {
        document.getElementById(`content-${t}`).style.display = (t === tab) ? 'block' : 'none';
        const btn = document.getElementById(`tab-${t}`);
        btn.style.color = (t === tab) ? 'var(--primary)' : 'var(--muted)';
        btn.style.borderBottomColor = (t === tab) ? 'var(--primary)' : 'transparent';
    });
}

function toggleFaq(i) {
    const answer = document.getElementById(`faq-answer-${i}`);
    const icon = document.getElementById(`faq-icon-${i}`);
    const open = answer.style.display === 'block';
    answer.style.display = open ? 'none' : 'block';
    icon.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
}
</script>
@endsection
