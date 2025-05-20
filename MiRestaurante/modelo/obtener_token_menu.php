<?php
session_start();
header('Content-Type: application/json');
include("../modelo/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "Sesión no iniciada"]);
  exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar si ya tiene un token
$sql = "SELECT token_menu FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($token);
$stmt->fetch();
$stmt->close();

if (!$token) {
  // Generar token único
  $token = bin2hex(random_bytes(16));
  $stmt = $conexion->prepare("UPDATE usuarios SET token_menu = ? WHERE id = ?");
  $stmt->bind_param("si", $token, $id_usuario);
  $stmt->execute();
  $stmt->close();
}

echo json_encode([
  "token" => $token,
  "uid" => $id_usuario
]);
?>
