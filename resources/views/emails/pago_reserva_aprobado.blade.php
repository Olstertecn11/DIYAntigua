<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#050505;color:#ffffff;font-family:Segoe UI,Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#050505;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;">
                    <tr>
                        <td align="center" style="padding:12px 0 24px;">
                            <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true" alt="DIY Antigua" style="width:132px;height:auto;display:block;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#0b0b0b;border:1px solid #262626;border-radius:16px;padding:34px;">
                            <p style="margin:0 0 10px;color:#22c55e;font-size:12px;font-weight:800;letter-spacing:1.8px;text-transform:uppercase;">Pago aprobado</p>
                            <h1 style="margin:0 0 14px;font-size:28px;line-height:1.2;color:#ffffff;">Tu reserva está confirmada</h1>
                            <p style="margin:0 0 24px;color:#cfcfcf;font-size:15px;line-height:1.7;">
                                Gracias, {{ $reserva->nombre_cliente }}. Recibimos el pago de tu traslado y tu reserva quedó confirmada.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#111111;border:1px solid #242424;border-radius:12px;margin:0 0 24px;">
                                <tr>
                                    <td style="padding:18px;border-bottom:1px solid #242424;color:#9ca3af;">Código</td>
                                    <td align="right" style="padding:18px;border-bottom:1px solid #242424;color:#facc15;font-weight:800;">{{ $reserva->codigo_reserva }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:18px;border-bottom:1px solid #242424;color:#9ca3af;">Ruta</td>
                                    <td align="right" style="padding:18px;border-bottom:1px solid #242424;color:#ffffff;font-weight:700;">{{ $reserva->ruta?->origen?->nombre }} → {{ $reserva->ruta?->destino?->nombre }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:18px;border-bottom:1px solid #242424;color:#9ca3af;">Fecha y hora</td>
                                    <td align="right" style="padding:18px;border-bottom:1px solid #242424;color:#ffffff;font-weight:700;">{{ \Carbon\Carbon::parse($reserva->fecha_viaje)->format('d/m/Y') }} {{ $reserva->hora_viaje }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:18px;border-bottom:1px solid #242424;color:#9ca3af;">Referencia</td>
                                    <td align="right" style="padding:18px;border-bottom:1px solid #242424;color:#ffffff;font-weight:700;">{{ $transaction->provider_transaction_id ?: $transaction->reference }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:18px;color:#9ca3af;">Pagado</td>
                                    <td align="right" style="padding:18px;color:#22c55e;font-size:20px;font-weight:900;">Q{{ number_format($transaction->amount, 2) }}</td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                    <td style="border-radius:999px;background:#facc15;">
                                        <a href="{{ route('reservas.confirmar', $reserva->codigo_reserva) }}" style="display:inline-block;padding:14px 26px;color:#050505;text-decoration:none;font-weight:900;border-radius:999px;">Ver reserva</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:22px;color:#6b7280;font-size:12px;line-height:1.6;">
                            © {{ date('Y') }} DIY Antigua Private Transfers<br>
                            Conserva este correo como confirmación de pago.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
