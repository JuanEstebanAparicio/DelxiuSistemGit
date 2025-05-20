<?php
session_start();
header('Content-Type: application/json');
require 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "No autenticado"]);
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_combo = intval($_POST['id'] ?? 0);

if ($id_combo <= 0) {
  echo json_encode(["error" => "ID de combo no válido"]);
  exit;
}

// Verifica que el combo pertenezca al usuario
$verifica = $conexion->prepare("SELECT id_combo FROM menu_combos WHERE id_combo = ? AND usuario_id = ?");
$verifica->bind_param("ii", $id_combo, $id_usuario);
$verifica->execute();
$res = $verifica->get_result();

if ($res->num_rows === 0) {
  echo json_encode(["error" => "Combo no encontrado"]);
  $verifica->close();
  exit;
}
$verifica->close();

// Elimina el combo (y detalles por ON DELETE CASCADE)
$delete = $conexion->prepare("DELETE FROM menu_combos WHERE id_combo = ? AND usuario_id = ?");
$delete->bind_param("ii", $id_combo, $id_usuario);
$success = $delete->execute();
$delete->close();

echo json_encode(["success" => $success]);
?>

