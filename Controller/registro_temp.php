<?php
require_once 'conexion.php';

$nombre = $_POST['nombre_usuario'] ?? '';
$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';
$restaurante = $_POST['nombre_restaurante'] ?? '';

if (!$nombre || !$correo || !$clave || !$restaurante) {
  exit('Faltan datos');
}

// Buscar si ya existe el correo en tabla principal
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario_correo = ?");
$stmt->execute([$correo]);
if ($stmt->fetchColumn() > 0) {
  exit('Este correo ya está registrado.');
}

// Buscar en usuario_temp
$stmt = $pdo->prepare("SELECT creado_en FROM usuarios_temp WHERE correo = ?");
$stmt->execute([$correo]);
$row = $stmt->fetch();

if ($row) {
  $fecha_creacion = strtotime($row['creado_en']);
  $ahora = time();
  
  // Si no han pasado 30 minutos, no dejar registrar otra vez
  if (($ahora - $fecha_creacion) < 1800) {
    exit('Ya se ha enviado un código. Revisa tu correo.');
  } else {
    // Expirado → eliminar entrada vieja
    $pdo->prepare("DELETE FROM usuarios_temp WHERE correo = ?")->execute([$correo]);
  }
}

// Crear nuevo código de verificación
$codigo = rand(100000, 999999);

// Insertar en tabla temporal
$stmt = $pdo->prepare("INSERT INTO usuarios_temp (nombre_usuario, correo, clave, nombre_restaurante, codigo, creado_en) VALUES (?, ?, ?, ?, ?, NOW())");

try {
  $stmt->execute([$nombre, $correo, password_hash($clave, PASSWORD_DEFAULT), $restaurante, $codigo]);
} catch (PDOException $e) {
  exit('Error al insertar en temporal: ' . $e->getMessage());
}

// Enviar correo usando PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../Libreries/PHPMailer-master/src/Exception.php';
require '../Libreries/PHPMailer-master/src/PHPMailer.php';
require '../Libreries/PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'delixiusistem@gmail.com';
  $mail->Password = 'ftlecotbnugecwpe';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  $mail->setFrom('delixiusistem@gmail.com', 'DELIXIUSYSTEM');
  $mail->addAddress($correo);
  $mail->isHTML(true);
  $mail->Subject = 'Tu código de verificación';
  $mail->Body = "<h2>Código de verificación</h2><p>Tu código es: <strong>$codigo</strong></p>";

  $mail->send();
  echo 'enviado';
} catch (Exception $e) {
  exit("No se pudo enviar el correo: {$mail->ErrorInfo}");
}
