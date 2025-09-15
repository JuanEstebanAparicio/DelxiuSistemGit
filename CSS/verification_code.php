<?php
function plantillaCodigo($nombre, $codigo) {
    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Verificación</title>
</head>
<body style="margin:0; padding:0; font-family:'Segoe UI', Arial, sans-serif; background:#f4f7fa;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:auto; background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <tr>
            <td style="background:linear-gradient(135deg,#4a90e2,#6a11cb); padding:30px; text-align:center; color:white;">
                <h1 style="margin:0; font-size:26px;">🔐 Verificación de seguridad</h1>
            </td>
        </tr>
        <tr>
            <td style="padding:30px; text-align:center; color:#333;">
                <p style="font-size:18px; margin-bottom:20px;">Hola <strong>{$nombre}</strong>,</p>
                <p style="font-size:16px; margin-bottom:30px;">Hemos recibido una solicitud para confirmar tu identidad.  
                Aquí tienes tu código de verificación exclusivo:</p>
                <div style="font-size:36px; font-weight:bold; color:#4a90e2; letter-spacing:6px; background:#f0f4ff; display:inline-block; padding:15px 25px; border-radius:8px;">
                    {$codigo}
                </div>
                <p style="margin-top:30px; font-size:14px; color:#666;">
                    Si no has solicitado este código, ignora este mensaje. Tu seguridad es nuestra prioridad.
                </p>
            </td>
        </tr>
        <tr>
            <td style="background:#f9f9f9; padding:20px; text-align:center; font-size:12px; color:#999;">
                © 2025 DelixiuSystem. Todos los derechos reservados.
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}
