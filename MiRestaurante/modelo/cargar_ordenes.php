<?php
session_start();
require 'conexion.php';

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT * FROM ordenes WHERE usuario_id = ? ORDER BY id DESC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

$ordenes = [];
while ($row = $res->fetch_assoc()) {
  $id_orden = $row['id'];

  $detalles = [];
  $det = $conexion->prepare("SELECT nombre, precio FROM orden_detalles WHERE orden_id = ?");
  $det->bind_param("i", $id_orden);
  $det->execute();
  $res_det = $det->get_result();
  while ($d = $res_det->fetch_assoc()) {
    $detalles[] = $d;
  }

  $ordenes[] = array_merge($row, ["detalles" => $detalles]);
}

echo json_encode($ordenes);
?>