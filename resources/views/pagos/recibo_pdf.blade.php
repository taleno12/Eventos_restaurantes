<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo {{ $pago->numero_pago }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            background: #ffffff;
            padding: 40px;
        }

        /* ── ENCABEZADO ── */
        .header {
            border-bottom: 3px solid #ea580c;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header-inner {
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }
        .empresa-nombre {
            font-size: 22px;
            font-weight: bold;
            color: #ea580c;
        }
        .empresa-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        .recibo-titulo {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
        }
        .recibo-numero {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* ── BADGE ESTADO ── */
        .estado-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 6px;
        }
        .estado-pagado    { background: #dcfce7; color: #16a34a; }
        .estado-pendiente { background: #fef9c3; color: #a16207; }
        .estado-anulado   { background: #fee2e2; color: #dc2626; }

        /* ── SECCIONES ── */
        .seccion {
            margin-bottom: 20px;
        }
        .seccion-titulo {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #ea580c;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        /* ── TABLA DE DATOS ── */
        .tabla-datos {
            width: 100%;
        }
        .tabla-datos td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label {
            color: #6b7280;
            font-size: 11px;
            width: 40%;
        }
        .valor {
            font-weight: bold;
            font-size: 13px;
            width: 60%;
        }

        /* ── DOS COLUMNAS ── */
        .columnas {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .col-izq {
            display: table-cell;
            width: 58%;
            padding-right: 16px;
            vertical-align: top;
        }
        .col-der {
            display: table-cell;
            width: 42%;
            vertical-align: top;
        }

        /* ── CAJA MONTO ── */
        .caja-monto {
            background: #fff3ee;
            border: 1px solid #ea580c;
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .caja-monto-label {
            display: table-cell;
            font-size: 12px;
            color: #ea580c;
            vertical-align: middle;
        }
        .caja-monto-valor {
            display: table-cell;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #ea580c;
            vertical-align: middle;
        }

        /* ── NOTAS ── */
        .caja-notas {
            background: #f9fafb;
            border-left: 3px solid #ea580c;
            padding: 10px 14px;
            border-radius: 0 4px 4px 0;
            font-size: 12px;
            color: #374151;
            margin-bottom: 20px;
        }

        /* ── PIE ── */
        .pie {
            border-top: 1px solid #e5e7eb;
            padding-top: 14px;
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
        }
        .pie strong {
            color: #ea580c;
        }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-left">
                <div class="empresa-nombre">GastroNicaragua</div>
                <div class="empresa-sub">Sistema de Gestión Gastronómica</div>
            </div>
            <div class="header-right">
                <div class="recibo-titulo">RECIBO DE PAGO</div>
                <div class="recibo-numero">{{ $pago->numero_pago }}</div>
                @if($pago->estado === 'pagado')
                    <span class="estado-badge estado-pagado">✔ Pagado</span>
                @elseif($pago->estado === 'pendiente')
                    <span class="estado-badge estado-pendiente">⏳ Pendiente</span>
                @else
                    <span class="estado-badge estado-anulado">✖ Anulado</span>
                @endif
            </div>
        </div>
    </div>

    {{-- CAJA MONTO DESTACADO --}}
    <div class="caja-monto">
        <div class="caja-monto-label">Monto del Pago</div>
        <div class="caja-monto-valor">C$ {{ number_format($pago->monto, 2) }}</div>
    </div>

    {{-- DOS COLUMNAS --}}
    <div class="columnas">

        {{-- Columna izquierda: datos del pago --}}
        <div class="col-izq">
            <div class="seccion">
                <div class="seccion-titulo">Detalles del Pago</div>
                <table class="tabla-datos">
                    <tr>
                        <td class="label">Fecha de pago</td>
                        <td class="valor">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Método</td>
                        <td class="valor">{{ $pago->metodo_pago_label }}</td>
                    </tr>
                    @if($pago->referencia)
                    <tr>
                        <td class="label">Referencia</td>
                        <td class="valor">{{ $pago->referencia }}</td>
                    </tr>
                    @endif
                    @if($pago->periodo_inicio && $pago->periodo_fin)
                    <tr>
                        <td class="label">Período</td>
                        <td class="valor">
                            {{ $pago->periodo_inicio->format('d/m/Y') }} al {{ $pago->periodo_fin->format('d/m/Y') }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label">Registrado</td>
                        <td class="valor">{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($pago->registradoPor)
                    <tr>
                        <td class="label">Registrado por</td>
                        <td class="valor">{{ $pago->registradoPor->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Columna derecha: datos del contrato --}}
        <div class="col-der">
            <div class="seccion">
                <div class="seccion-titulo">Contrato y Establecimiento</div>
                @if($pago->contrato)
                    @php $est = $pago->contrato->establecimiento(); @endphp
                    <table class="tabla-datos">
                        <tr>
                            <td class="label">N° Contrato</td>
                            <td class="valor">{{ $pago->contrato->numero_contrato }}</td>
                        </tr>
                        @if($est)
                        <tr>
                            <td class="label">Establecimiento</td>
                            <td class="valor">{{ $est->nombre }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tipo</td>
                            <td class="valor">{{ $pago->contrato->gastrobar_id ? 'Gastrobar' : 'Restaurante' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="label">Plan</td>
                            <td class="valor">{{ ucfirst($pago->contrato->plan ?? '—') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Representante</td>
                            <td class="valor">{{ $pago->contrato->representante ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Vigencia</td>
                            <td class="valor">
                                {{ $pago->contrato->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                al
                                {{ $pago->contrato->fecha_fin?->format('d/m/Y') ?? '—' }}
                            </td>
                        </tr>
                    </table>
                @else
                    <p style="color:#9ca3af; font-size:12px;">Contrato no disponible</p>
                @endif
            </div>
        </div>

    </div>

    {{-- NOTAS --}}
    @if($pago->notas)
    <div class="seccion">
        <div class="seccion-titulo">Notas</div>
        <div class="caja-notas">{{ $pago->notas }}</div>
    </div>
    @endif

    {{-- PIE --}}
    <div class="pie">
        <strong>GastroNicaragua</strong> — Este documento es un comprobante oficial de pago.<br>
        Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
    </div>

</body>
</html>
