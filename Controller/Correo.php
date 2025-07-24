<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../Libreries/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../Libreries/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../Libreries/PHPMailer-master/src/SMTP.php';

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
}



//delixiusistem@gmail.com
// ftlecotbnugecwpe