<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código de verificación</title>
</head>

<body style="margin:0; padding:0; background:#f1f5f9; font-family: Arial, Helvetica, sans-serif; color:#0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:560px; background:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 20px 60px rgba(15,23,42,0.12);">

                    <tr>
                        <td
                            style="background:linear-gradient(135deg,#020617,#0f172a); padding:32px 28px; text-align:center;">
                            <div
                                style="display:inline-block; background:linear-gradient(135deg,#facc15,#fb923c); color:#020617; font-weight:900; font-size:18px; padding:14px 18px; border-radius:18px; margin-bottom:16px;">
                                DIY
                            </div>

                            <h1
                                style="margin:0; color:#ffffff; font-size:26px; font-weight:900; letter-spacing:-0.5px;">
                                Verifica tu correo
                            </h1>

                            <p style="margin:10px 0 0; color:#cbd5e1; font-size:15px; line-height:1.6;">
                                Estás a un paso de continuar con tu reserva en DIY Antigua.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:34px 28px; text-align:center;">
                            <p style="margin:0 0 18px; color:#475569; font-size:16px; line-height:1.7;">
                                Usa el siguiente código de verificación para confirmar tu correo electrónico y continuar
                                con el proceso de reserva.
                            </p>

                            <div
                                style="margin:28px auto; max-width:300px; background:#fffbeb; border:1px solid #fde68a; border-radius:22px; padding:24px;">
                                <p
                                    style="margin:0 0 10px; color:#92400e; font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:1.5px;">
                                    Código de verificación
                                </p>

                                <div style="font-size:42px; font-weight:900; letter-spacing:12px; color:#0f172a;">
                                    {{ $code }}
                                </div>
                            </div>

                            <p style="margin:0; color:#64748b; font-size:14px; line-height:1.6;">
                                Este código vence en <strong style="color:#0f172a;">10 minutos</strong>.
                                Si no solicitaste este código, puedes ignorar este correo.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 28px 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:18px;">
                                <tr>
                                    <td style="padding:18px;">
                                        <p style="margin:0; color:#334155; font-size:13px; line-height:1.6;">
                                            <strong>Consejo de seguridad:</strong>
                                            no compartas este código con nadie. DIY Antigua nunca te pedirá este código
                                            por teléfono o redes sociales.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="background:#f8fafc; padding:22px 28px; text-align:center; border-top:1px solid #e2e8f0;">
                            <p style="margin:0; color:#94a3b8; font-size:12px; line-height:1.5;">
                                © {{ date('Y') }} DIY Antigua. Traslados privados en Guatemala.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
