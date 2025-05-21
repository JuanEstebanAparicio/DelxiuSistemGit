<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "No autenticado"]);
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_combo = intval($_GET['id'] ?? 0);

// ✅ INCLUYE ESTADO
$stmt = $conexion->prepare("SELECT id_combo, nombre_combo, descripcion, precio_combo, estado FROM menu_combos WHERE id_combo = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id_combo, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  echo json_encode(["error" => "Combo no encontrado"]);
  exit;
}

$combo = $res->fetch_assoc();

// Obtener IDs de platillos del combo
$detalles = $conexion->prepare("SELECT platillo_id FROM menu_combo_detalles WHERE combo_id = ?");
$detalles->bind_param("i", $id_combo);
$detalles->execute();
$result = $detalles->get_result();

$platillos = [];
while ($r = $result->fetch_assoc()) {
  $platillos[] = intval($r['platillo_id']);
}

// ✅ INCLUIR ESTADO en la respuesta
$response = [
  "id" => $combo["id_combo"],
  "nombre_combo" => $combo["nombre_combo"],
  "descripcion" => $combo["descripcion"],
  "precio_combo" => floatval($combo["precio_combo"]),
  "estado" => $combo["estado"],
  "platillos" => $platillos
];

echo json_encode($response);
?>
