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
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;">
                    <tr>
                        <td align="center" style="padding:12px 0 24px;">
                            <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true" alt="DYANTIGUA" style="width:120px;height:auto;display:block;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#0b0b0b;border:1px solid #262626;border-radius:16px;padding:34px;text-align:center;">
                            <p style="margin:0 0 10px;color:#facc15;font-size:12px;font-weight:800;letter-spacing:1.8px;text-transform:uppercase;">Verificación de correo</p>
                            <h1 style="margin:0 0 14px;font-size:26px;line-height:1.2;color:#ffffff;">Confirma tu reserva</h1>
                            <p style="margin:0 0 26px;color:#cfcfcf;font-size:15px;line-height:1.7;">
                                Ingresa este código para continuar con tu reserva en DYANTIGUA.
                            </p>
                            <div style="display:inline-block;background:#111111;border:1px solid #333333;border-radius:14px;padding:18px 30px;color:#facc15;font-size:36px;font-weight:900;letter-spacing:10px;">
                                {{ $code }}
                            </div>
                            <p style="margin:26px 0 0;color:#8b8b8b;font-size:13px;line-height:1.6;">
                                Este código vence en 10 minutos y fue solicitado para {{ $email }}.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:22px;color:#6b7280;font-size:12px;line-height:1.6;">
                            © {{ date('Y') }} DYANTIGUA Private Transfers
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
