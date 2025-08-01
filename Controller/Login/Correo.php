<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../Libreries/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../Libreries/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../Libreries/PHPMailer-master/src/SMTP.php';


class Correo {
    public static function enviar($correoDestino, $codigo) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'delixiusistem@gmail.com';
            $mail->Password = 'ftlecotbnugecwpe';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('delixiusistem@gmail.com', 'DelixiuSystem');
            $mail->addAddress($correoDestino);

            $mail->isHTML(true);
            $mail->Subject = 'Código de Verificación';
            $mail->Body = '
  <div style="max-width: 480px; margin: auto; padding: 20px; font-family: Arial, sans-serif; border: 1px solid #e0e0e0; border-radius: 8px; background: #ffffff;">
    <h2 style="color: #009688; text-align: center;">Verifica tu correo</h2>
    <p style="font-size: 16px;">Gracias por registrarte en <strong>DelixiuSystem</strong>.</p>
    <p style="font-size: 15px;">Tu código de verificación es:</p>
    <div style="font-size: 30px; font-weight: bold; color: #333; text-align: center; padding: 12px 0; background: #f4f4f4; border-radius: 6px; letter-spacing: 6px;">
      ' . $codigo . '
    </div>
    <p style="font-size: 13px; color: #777; text-align: center; margin-top: 20px;">
      Este código es válido por 30 minutos.
    </p>
    <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">
    <p style="font-size: 12px; color: #aaa; text-align: center;">
      © ' . date('Y') . ' DelixiuSystem. Todos los derechos reservados.
    </p>
  </div>
';


            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

 public static function enviarRecuperacion($correoDestino, $enlace) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'delixiusistem@gmail.com';
            $mail->Password = 'ftlecotbnugecwpe';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('delixiusistem@gmail.com', 'DelixiuSystem');
            $mail->addAddress($correoDestino);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperar tu contraseña - DelixiuSystem';
            $mail->Body = "
            <html><body style='font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px;'>
                <div style='max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                    <h2 style='color: #333;'>Recuperar contraseña</h2>
                    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
                    <p style='text-align: center;'>
                        <a href='$enlace' style='display: inline-block; background: #007BFF; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>Restablecer contraseña</a>
                    </p>
                    <p>Si tú no realizaste esta solicitud, puedes ignorar este mensaje.</p>
                    <p style='color: gray;'>Este enlace es válido por tiempo limitado.</p>
                </div>
            </body></html>";
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }






}



//delixiusistem@gmail.com
// ftlecotbnugecwpe