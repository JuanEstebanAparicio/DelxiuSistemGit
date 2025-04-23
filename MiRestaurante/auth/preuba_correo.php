<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../librerias/PHPMailer-master/src/Exception.php';
require '../librerias/PHPMailer-master/src/PHPMailer.php';
require '../librerias/PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2; // Mostrar detalles del envío (para pruebas)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'delixiusistem@gmail.com';
    $mail->Password   = 'ftlecotbnugecwpe'; // Clave de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('delixiusistem@gmail.com', 'DELIXIUSISTEM');
    $mail->addReplyTo('delixiusistem@gmail.com', 'DELIXIUSISTEM'); // Opcional
    $mail->addAddress('apariciojuanesteban@gmail.com', 'Juan Esteban');

    $mail->isHTML(true);
    $mail->Subject = 'Confirmación de correo - DELIXIUSISTEM';

    // Cuerpo del correo en HTML
    $mail->Body = '
    <html>
      <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <div style="background-color: #ffffff; padding: 20px; border-radius: 10px;">
          <h2 style="color: #007BFF;">¡Bienvenido a DELIXIUSISTEM!</h2>
          <p>Hola <strong>Juan Esteban</strong>,</p>
          <p>Este es un mensaje de confirmación enviado desde nuestro sistema. Tu correo ha sido registrado exitosamente.</p>
          <p style="margin-top: 30px;">Gracias por confiar en nosotros.<br>El equipo de <strong>DELIXIUSISTEM</strong>.</p>
        </div>
      </body>
    </html>';

    // Texto alternativo (por si el cliente no soporta HTML)
    $mail->AltBody = "Bienvenido a DELIXIUSISTEM.\nTu correo ha sido registrado exitosamente.\nGracias por confiar en nosotros.";

    $mail->send();
    echo 'Correo enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>

