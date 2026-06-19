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
            color: #1e293b;
            background: #ffffff;
            padding: 40px;
        }

        /* ── ENCABEZADO ── */
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 28px;
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
            font-size: 26px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.5px;
        }
        .empresa-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
            letter-spacing: 0.3px;
        }
        .recibo-titulo {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .recibo-numero {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
            font-weight: 600;
        }

        /* ── BADGE ESTADO ── */
        .estado-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .estado-pagado    { background: #dbeafe; color: #1d4ed8; }
        .estado-pendiente { background: #fef3c7; color: #b45309; }
        .estado-anulado   { background: #fee2e2; color: #b91c1c; }

        /* ── CAJA MONTO DESTACADO ── */
        .caja-monto {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #2563eb;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
            display: table;
            width: 100%;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
        }
        .caja-monto-label {
            display: table-cell;
            font-size: 12px;
            color: #2563eb;
            vertical-align: middle;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .caja-monto-valor {
            display: table-cell;
            text-align: right;
            font-size: 28px;
            font-weight: 800;
            color: #2563eb;
            vertical-align: middle;
            letter-spacing: -0.5px;
        }

        /* ── DOS COLUMNAS ── */
        .columnas {
            display: table;
            width: 100%;
            margin-bottom: 24px;
        }
        .col-izq {
            display: table-cell;
            width: 55%;
            padding-right: 24px;
            vertical-align: top;
        }
        .col-der {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }

        /* ── SECCIONES ── */
        .seccion {
            margin-bottom: 20px;
        }
        .seccion-titulo {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #2563eb;
            letter-spacing: 1px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        /* ── TABLA DE DATOS ── */
        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
        }
        .tabla-datos tr {
            border-bottom: 1px solid #f1f5f9;
        }
        .tabla-datos tr:last-child {
            border-bottom: none;
        }
        .tabla-datos td {
            padding: 10px 0;
            vertical-align: top;
        }
        .label {
            color: #64748b;
            font-size: 11px;
            width: 42%;
            font-weight: 500;
        }
        .valor {
            font-weight: 700;
            font-size: 12px;
            width: 58%;
            color: #1e293b;
        }

        /* ── NOTAS ── */
        .caja-notas {
            background: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 14px 18px;
            border-radius: 0 8px 8px 0;
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
        }

        /* ── PIE ── */
        .pie {
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
            margin-top: 36px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
        .pie strong {
            color: #2563eb;
            font-weight: 700;
        }
        .pie-linea {
            width: 40px;
            height: 3px;
            background: #2563eb;
            margin: 0 auto 12px;
            border-radius: 2px;
        }

        /* ── MARCA DE AGUA SUTIL ── */
        .marca-agua {
            position: fixed;
            bottom: 80px;
            right: 40px;
            opacity: 0.03;
            font-size: 120px;
            font-weight: 800;
            color: #2563eb;
            transform: rotate(-15deg);
            pointer-events: none;
        }
    </style>
</head>
<body>

    {{-- MARCA DE AGUA --}}
    <div class="marca-agua">GN</div>

    {{-- ENCABEZADO --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-left">
                <div class="empresa-nombre">GastroNicaragua</div>
                <div class="empresa-sub">Sistema de Gestión Gastronómica</div>
            </div>
            <div class="header-right">
                <div class="recibo-titulo">Recibo de Pago</div>
                <div class="recibo-numero">{{ $pago->numero_pago }}</div>
                @if($pago->estado === 'pagado')
                    <span class="estado-badge estado-pagado">✓ Pagado</span>
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
                    <p style="color:#94a3b8; font-size:12px;">Contrato no disponible</p>
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
        <div class="pie-linea"></div>
        <strong>GastroNicaragua</strong> — Este documento es un comprobante oficial de pago.<br>
        Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
    </div>

</body>
</html>
