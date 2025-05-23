<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "No autenticado"]);
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);

if ($id <= 0) {
  echo json_encode(["error" => "ID inválido"]);
  exit;
}

// Verificar que la orden pertenece al usuario
$stmt = $conexion->prepare("SELECT id FROM ordenes WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
  echo json_encode(["error" => "Orden no encontrada"]);
  exit;
}
$stmt->close();

// Eliminar orden (y por cascada sus detalles e historial)
$stmt_del = $conexion->prepare("DELETE FROM ordenes WHERE id = ?");
$stmt_del->bind_param("i", $id);

if ($stmt_del->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => "No se pudo eliminar"]);
}
$stmt_del->close();
