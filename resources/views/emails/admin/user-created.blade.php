<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Tu usuario fue creado</title>
</head>
<body style="margin:0;background:#f5f5f0;font-family:Arial,Helvetica,sans-serif;color:#111111;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f0;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;background:#ffffff;border-radius:24px;overflow:hidden;border:1px solid #e8e2c8;">
                    <tr>
                        <td style="background:#080806;padding:28px;text-align:center;">
                            <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true" alt="DYANTIGUA" style="width:110px;height:auto;display:block;margin:0 auto 14px;">
                            <div style="font-size:12px;font-weight:800;letter-spacing:3px;color:#FCCA00;text-transform:uppercase;">Acceso a plataforma</div>
                            <h1 style="margin:12px 0 0;font-size:28px;line-height:1.15;color:#ffffff;">Tu usuario fue creado</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;">
                            <p style="margin:0 0 14px;font-size:16px;line-height:1.6;">Hola {{ $user->name }},</p>
                            <p style="margin:0 0 20px;font-size:16px;line-height:1.6;color:#475569;">
                                Hemos creado tu acceso a DYANTIGUA. Por seguridad, primero debes definir tu propia contraseña antes de iniciar sesion.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:22px 0;background:#fbfaf2;border:1px solid #f0df8a;border-radius:18px;">
                                <tr>
                                    <td style="padding:18px;">
                                        <div style="font-size:11px;font-weight:800;letter-spacing:2px;color:#7a6100;text-transform:uppercase;">Datos de acceso</div>
                                        <p style="margin:12px 0 6px;font-size:14px;color:#475569;">Correo</p>
                                        <p style="margin:0 0 14px;font-size:16px;font-weight:800;color:#111111;">{{ $user->email }}</p>
                                        <p style="margin:0 0 6px;font-size:14px;color:#475569;">Tipo de usuario</p>
                                        <p style="margin:0;font-size:16px;font-weight:800;color:#111111;">{{ implode(', ', $roles) }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 24px;font-size:14px;line-height:1.6;color:#64748b;">
                                Este enlace es personal. Si no esperabas este correo, puedes ignorarlo o contactar a administracion.
                            </p>

                            <p style="margin:0;text-align:center;">
                                <a href="{{ $resetUrl }}" style="display:inline-block;background:#FCCA00;color:#111111;text-decoration:none;border-radius:999px;padding:14px 24px;font-size:13px;font-weight:900;text-transform:uppercase;letter-spacing:1px;">
                                    Crear mi contraseña
                                </a>
                            </p>

                            <p style="margin:24px 0 0;font-size:12px;line-height:1.6;color:#94a3b8;text-align:center;">
                                Si el boton no funciona, copia este enlace en tu navegador:<br>
                                <span style="word-break:break-all;color:#475569;">{{ $resetUrl }}</span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px;background:#111111;text-align:center;color:#9ca3af;font-size:12px;line-height:1.5;">
                            © {{ date('Y') }} DYANTIGUA Private Transfers
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
