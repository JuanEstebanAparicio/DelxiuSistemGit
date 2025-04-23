<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../librerias/PHPMailer-master/src/Exception.php';
require '../librerias/PHPMailer-master/src/PHPMailer.php';
require '../librerias/PHPMailer-master/src/SMTP.php';

function enviarCodigoVerificacion($correoDestino, $codigo) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'delixiusistem@gmail.com'; 
        $mail->Password   = 'ftlecotbnugecwpe'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('delixiusistem@gmail.com', 'DELIXIUSISTEM');
        $mail->addAddress($correoDestino);

        $mail->isHTML(true);
        $mail->Subject = 'Código de verificación - Mi Restaurante';
        $mail->Body    = "<h2>Tu código de verificación es:</h2>
                          <p style='font-size: 20px;'><strong>$codigo</strong></p>
                          <p>Este código expirará en 30 minutos.</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>
