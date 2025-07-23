<?php
require __DIR__ . '/conexion.php';

// Habilitar errores para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$codigo = $_POST['codigo'] ?? '';
$correo = $_POST['correo'] ?? '';

if (!$codigo || !$correo) {
  exit('Datos incompletos');
}

// Verificar existencia en usuarios_temp
$stmt = $pdo->prepare("SELECT * FROM usuarios_temp WHERE correo = ? AND codigo = ?");
$stmt->execute([$correo, $codigo]);
$tempUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tempUser) {
  exit('codigo_invalido');
}

$creadoEn = strtotime($tempUser['creado_en']);
if (time() - $creadoEn > 1800) {
  $pdo->prepare("DELETE FROM usuarios_temp WHERE correo = ?")->execute([$correo]);
  exit('codigo_expirado');
}

try {
  // Generar token único
  $token = bin2hex(random_bytes(16));

  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
  exit('correo_invalido');
}

if (!preg_match('/^[0-9]{6}$/', $codigo)) {
  exit('codigo_invalido');
}

  // Insertar nuevo usuario
  $insert = $pdo->prepare("INSERT INTO usuarios (
    usuario_nombre,
    usuario_correo,
    usuario_clave,
    usuario_restaurante,
    usuario_estado,
    usuario_rol,
    usuario_token
  ) VALUES (?, ?, ?, ?, 'activo', 'usuario', ?)");

  $insert->execute([
    $tempUser['nombre_usuario'],
    $tempUser['correo'],
    $tempUser['clave'],
    $tempUser['nombre_restaurante'],
    $token
  ]);

  // Eliminar de temporal
  $pdo->prepare("DELETE FROM usuarios_temp WHERE correo = ?")->execute([$correo]);
  echo 'ok';
} catch (PDOException $e) {
  error_log("[ERROR DB]: " . $e->getMessage(), 3, __DIR__ . "/../error_registro.log");
  exit('error_insert');
}
