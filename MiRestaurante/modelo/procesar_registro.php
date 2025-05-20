<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../librerias/PHPMailer-master/src/Exception.php';
require '../librerias/PHPMailer-master/src/PHPMailer.php';
require '../librerias/PHPMailer-master/src/SMTP.php';

// Conexión a base de datos
$conn = new mysqli("localhost", "root", "", "restaurante",3307);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$usuario = $_POST['usuario'];
$correo = $_POST['correo'];
$restaurante = $_POST['restaurante'];
$contrasena = $_POST['contrasena'];
$confirmar = $_POST['confirmar_contrasena'];

// Validaciones básicas
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Correo inválido");
}

if ($contrasena !== $confirmar) {
    die("Las contraseñas no coinciden");
}

$verificarCorreo = $conn->prepare("SELECT correo FROM usuarios WHERE correo = ?");
$verificarCorreo->bind_param("s", $correo);
$verificarCorreo->execute();
$verificarCorreo->store_result();

if ($verificarCorreo->num_rows > 0) {
    $verificarCorreo->close();
    $conn->close();
    echo '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Correo en uso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .alerta {
            background-color: #fff;
            border: 1px solid #dc3545;
            border-left: 5px solid #dc3545;
            padding: 20px 30px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .alerta h2 {
            color: #dc3545;
            margin-bottom: 10px;
        }
        .alerta p {
            margin-bottom: 20px;
        }
        .alerta a {
            text-decoration: none;
            background-color: #dc3545;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .alerta a:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>
    <div class="alerta">
        <h2>Correo en uso</h2>
        <p>Este correo ya tiene una cuenta en uso.</p>
        <a href="javascript:history.back()">Volver</a>
    </div>
</body>
</html>';
exit;
}
$verificarCorreo->close();

// Generar código de verificación
$codigo = rand(100000, 999999);

// Guardar temporalmente en base de datos
$hashed = password_hash($contrasena, PASSWORD_DEFAULT);
$sql = "INSERT INTO usuarios_temp (usuario, correo, restaurante, contrasena, codigo_verificacion)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $usuario, $correo, $restaurante, $hashed, $codigo);
$stmt->execute();
$stmt->close();

// Enviar correo con el código
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'delixiusistem@gmail.com';
    $mail->Password   = 'ftlecotbnugecwpe'; // Clave de app
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('delixiusistem@gmail.com', 'DELIXIUSISTEM');
    $mail->addAddress($correo);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Código de verificación';
    $mail->Body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 10px;">
        <div style="text-align: center; padding-bottom: 10px;">
            <h2 style="color: #0d6efd;">DELIXIUSISTEM</h2>
        </div>
        <p>Hola,</p>
        <p>Gracias por registrarte en <strong>DELIXIUSISTEM</strong>.</p>
        <p>Tu código de verificación es:</p>
        <div style="text-align: center; margin: 20px 0;">
            <span style="font-size: 32px; color: #0d6efd; font-weight: bold;">' . $codigo . '</span>
        </div>
        <p>Este código es válido por 30 minutos. Por favor, ingrésalo en el formulario para completar tu registro.</p>
        <br>
        <p style="color: #555;">Si no solicitaste este código, puedes ignorar este mensaje.</p>
        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
        <p style="font-size: 12px; color: #888; text-align: center;">
            &copy; ' . date("Y") . ' DELIXIUSISTEM. Todos los derechos reservados.
        </p>
    </div>
';


    $mail->send();
    header("Location: ../Ver/verificar.php?correo=$correo");

} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}

$conn->close();
?>
