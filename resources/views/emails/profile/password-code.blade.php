<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#080806;color:#ffffff;font-family:Segoe UI,Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#080806;padding:32px 14px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;">
                    <tr>
                        <td style="background:#11110e;border:1px solid #2b2b24;border-radius:24px;padding:38px;text-align:center;">
                            <p style="margin:0 0 12px;color:#fcca00;font-size:12px;font-weight:800;letter-spacing:2px;text-transform:uppercase;">Seguridad de cuenta</p>
                            <h1 style="margin:0 0 14px;font-size:28px;color:#ffffff;">Hola, {{ $name }}</h1>
                            <p style="margin:0 0 28px;color:#b7b7af;font-size:15px;line-height:1.7;">Usa este código para confirmar el cambio de tu contraseña.</p>
                            <div style="display:inline-block;background:#fcca00;border-radius:18px;padding:18px 28px;color:#090909;font-size:34px;font-weight:900;letter-spacing:8px;">{{ $code }}</div>
                            <p style="margin:28px 0 0;color:#85857e;font-size:13px;line-height:1.6;">El código vence en 10 minutos. Si no solicitaste este cambio, ignora este correo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
