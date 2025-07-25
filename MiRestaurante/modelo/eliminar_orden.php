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

$stmt = $conexion->prepare("UPDATE ordenes SET eliminada = 1 WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $id_usuario);
if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => "No se pudo mover a papelera"]);
}
