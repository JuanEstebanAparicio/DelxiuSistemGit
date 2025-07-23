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
            $mail->Body = "<p>Tu código es: <strong>$codigo</strong></p><p>Expira en 30 minutos.</p>";


            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}



//delixiusistem@gmail.com
// ftlecotbnugecwpe