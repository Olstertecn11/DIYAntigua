@php
    $origen = $reserva->ruta?->origen?->nombre ?? 'Origen';
    $destino = $reserva->ruta?->destino?->nombre ?? 'Destino';
    $fecha = \Carbon\Carbon::parse($reserva->fecha_viaje)->locale('es')->translatedFormat('d M Y');
    $estadoPago = strtoupper(str_replace('_', ' ', $reserva->estado_pago ?? 'pendiente'));
    $estadoViaje = strtoupper(str_replace('_', ' ', $reserva->estado_viaje ?? 'programado'));
    $referencia = $transaction?->provider_transaction_id ?: ($reserva->pago_referencia ?: $transaction?->reference);
    $autorizacion = $transaction?->authorization_code;
    $tarjeta = $transaction?->card_last_four ? strtoupper($transaction->card_brand ?: 'CARD') . ' **** ' . $transaction->card_last_four : 'No disponible';
    $notas = collect(explode("\n", (string) $reserva->notas_adicionales))
        ->mapWithKeys(function ($line) {
            $parts = explode(':', $line, 2);
            return count($parts) === 2 ? [trim($parts[0]) => trim($parts[1])] : [];
        });
    $recogida = $notas->get('RECOGIDA', 'Pendiente de confirmar');
    $puntoDestino = $notas->get('DESTINO', 'Pendiente de confirmar');
    $notaCliente = $notas->get('NOTAS', 'Ninguna');
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante de Reserva - {{ $reserva->codigo_reserva }}</title>
    <style>
        @page {
            margin: 24px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #363636;
            background: #f6f6f3;
            font-size: 12px;
            line-height: 1.45;
        }

        .page {
            background: #ffffff;
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid #deded6;
        }

        .hero {
            background: #000000;
            color: #ffffff;
            padding: 26px 30px 30px;
            border-left: 12px solid #FCCA00;
        }

        .hero-table,
        .route-table,
        .details-table,
        .payment-table,
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 126px;
            max-height: 60px;
        }

        .brand {
            font-size: 10px;
            color: #FCCA00;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .title {
            margin: 10px 0 8px;
            font-size: 30px;
            line-height: 1.05;
            font-weight: 800;
        }

        .subtitle {
            margin: 0;
            color: #d8d8d8;
            font-size: 12px;
        }

        .status-pill {
            display: inline-block;
            background: #FCCA00;
            border: 1px solid #FCCA00;
            color: #000000;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .code-card {
            margin-top: 18px;
            background: #1a1a1a;
            border: 1px solid #363636;
            border-radius: 16px;
            padding: 14px 16px;
        }

        .label {
            color: #6a6a6a;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 800;
        }

        .hero .label {
            color: #c9c9c9;
        }

        .code {
            color: #FCCA00;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .content {
            padding: 24px 30px 28px;
        }

        .ticket {
            border: 1px solid #deded6;
            border-radius: 20px;
            overflow: hidden;
        }

        .route-panel {
            padding: 22px 24px;
            background: #fbfbf8;
            border-bottom: 1px dashed #b8b8b0;
        }

        .route-place {
            font-size: 25px;
            font-weight: 800;
            line-height: 1.05;
            color: #000000;
        }

        .route-icon {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #FCCA00;
            color: #000000;
            text-align: center;
            vertical-align: middle;
            font-size: 24px;
            font-weight: 800;
        }

        .ticket-body {
            padding: 20px 24px 22px;
        }

        .details-table td {
            width: 33.333%;
            padding: 0 16px 18px 0;
            vertical-align: top;
        }

        .value {
            display: block;
            margin-top: 5px;
            font-size: 13px;
            color: #000000;
            font-weight: 800;
            word-break: break-word;
        }

        .payment-band {
            margin-top: 4px;
            background: #000000;
            color: #ffffff;
            border-radius: 16px;
            padding: 16px 18px;
        }

        .payment-table td {
            width: 33.333%;
            padding-right: 14px;
            vertical-align: top;
        }

        .payment-band .label {
            color: #c9c9c9;
        }

        .payment-band .value {
            color: #ffffff;
        }

        .total {
            color: #FCCA00 !important;
            font-size: 20px;
        }

        .qr-panel {
            margin-top: 18px;
            border: 1px solid #deded6;
            border-radius: 18px;
            padding: 16px 18px;
            background: #ffffff;
        }

        .qr-img {
            width: 118px;
            height: 118px;
            border: 1px solid #deded6;
            border-radius: 14px;
            padding: 8px;
        }

        .fallback-qr {
            width: 118px;
            height: 118px;
            border: 1px dashed #8a8a8a;
            border-radius: 14px;
            text-align: center;
            color: #5f5f5f;
            font-size: 10px;
            padding-top: 42px;
        }

        .qr-title {
            margin: 0 0 6px;
            color: #000000;
            font-size: 16px;
            font-weight: 800;
        }

        .qr-copy {
            margin: 0;
            color: #5f5f5f;
            font-size: 11px;
        }

        .notice {
            margin-top: 18px;
            background: rgba(252, 202, 0, .14);
            border: 1px solid rgba(252, 202, 0, .45);
            color: #363636;
            border-radius: 16px;
            padding: 13px 16px;
            font-size: 11px;
        }

        .footer {
            padding: 16px 30px 22px;
            border-top: 1px solid #deded6;
            color: #5f5f5f;
            font-size: 10px;
        }

        .footer strong {
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="hero">
            <table class="hero-table">
                <tr>
                    <td style="width: 62%; vertical-align: top;">
                        @if ($logoBase64)
                            <img src="{{ $logoBase64 }}" class="logo" alt="DYANTIGUA">
                        @endif
                        <div class="brand">DYANTIGUA Private Transfers</div>
                        <h1 class="title">Comprobante de traslado</h1>
                        <p class="subtitle">Tu constancia digital de reserva y pago para presentar el día del viaje.</p>
                    </td>
                    <td style="width: 38%; text-align: right; vertical-align: top;">
                        <span class="status-pill">{{ $estadoPago }}</span>
                        <div class="code-card">
                            <div class="label">Código de reserva</div>
                            <div class="code">{{ $reserva->codigo_reserva }}</div>
                            <div style="margin-top: 8px;" class="label">Estado del viaje</div>
                            <div style="font-weight: 800; color: #ffffff;">{{ $estadoViaje }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="content">
            <div class="ticket">
                <div class="route-panel">
                    <table class="route-table">
                        <tr>
                            <td style="width: 42%; vertical-align: middle;">
                                <div class="label">Origen</div>
                                <div class="route-place">{{ $origen }}</div>
                            </td>
                            <td style="width: 16%; text-align: center; vertical-align: middle;">
                                <div class="route-icon">→</div>
                            </td>
                            <td style="width: 42%; text-align: right; vertical-align: middle;">
                                <div class="label">Destino</div>
                                <div class="route-place">{{ $destino }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="ticket-body">
                    <table class="details-table">
                        <tr>
                            <td>
                                <span class="label">Pasajero</span>
                                <span class="value">{{ $reserva->nombre_cliente }}</span>
                            </td>
                            <td>
                                <span class="label">Fecha</span>
                                <span class="value">{{ $fecha }}</span>
                            </td>
                            <td>
                                <span class="label">Hora</span>
                                <span class="value">{{ $reserva->hora_viaje }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="label">Vehículo</span>
                                <span class="value">{{ strtoupper($reserva->tipo_vehiculo) }}</span>
                            </td>
                            <td>
                                <span class="label">Pasajeros</span>
                                <span class="value">{{ $reserva->pasajeros }}</span>
                            </td>
                            <td>
                                <span class="label">Teléfono</span>
                                <span class="value">{{ $reserva->telefono_cliente }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="label">Punto de recogida</span>
                                <span class="value">{{ $recogida }}</span>
                            </td>
                            <td>
                                <span class="label">Punto de llegada</span>
                                <span class="value">{{ $puntoDestino }}</span>
                            </td>
                            <td>
                                <span class="label">Correo</span>
                                <span class="value">{{ $reserva->correo_cliente }}</span>
                            </td>
                        </tr>
                    </table>

                    <div class="payment-band">
                        <table class="payment-table">
                            <tr>
                                <td>
                                    <span class="label">Total pagado</span>
                                    <span class="value total">Q{{ number_format((float) $reserva->precio_total, 2) }}</span>
                                </td>
                                <td>
                                    <span class="label">Referencia</span>
                                    <span class="value">{{ $referencia ?: 'Pendiente' }}</span>
                                </td>
                                <td>
                                    <span class="label">Tarjeta</span>
                                    <span class="value">{{ $tarjeta }}</span>
                                </td>
                            </tr>
                            @if ($autorizacion)
                                <tr>
                                    <td colspan="3" style="padding-top: 12px;">
                                        <span class="label">Autorización</span>
                                        <span class="value">{{ $autorizacion }}</span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="qr-panel">
                <table class="footer-table">
                    <tr>
                        <td style="width: 144px; vertical-align: middle;">
                            @if ($qrBase64)
                                <img src="{{ $qrBase64 }}" class="qr-img" alt="QR de reserva">
                            @else
                                <div class="fallback-qr">QR no disponible</div>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            <p class="qr-title">Validación rápida de reserva</p>
                            <p class="qr-copy">Escanea este código para abrir la reserva y confirmar los datos del traslado. También puedes presentar el código <strong>{{ $reserva->codigo_reserva }}</strong> al conductor.</p>
                            <p class="qr-copy" style="margin-top: 8px;"><strong>Notas:</strong> {{ $notaCliente }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="notice">
                Presenta este comprobante antes de abordar. Te recomendamos estar listo 10 minutos antes de la hora programada y mantener disponible el teléfono registrado.
            </div>
        </div>

        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td><strong>DYANTIGUA</strong><br>Servicios de transporte privado en Guatemala.</td>
                    <td style="text-align: right;">Emitido el {{ now()->format('d/m/Y H:i') }}<br>Documento generado electrónicamente.</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
