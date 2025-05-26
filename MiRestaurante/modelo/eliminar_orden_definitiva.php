<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "No autenticado"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);

$stmt = $conexion->prepare("DELETE FROM ordenes WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['id_usuario']);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => "No se pudo eliminar permanentemente"]);
}
