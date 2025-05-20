<?php
include '../modelo/conexion.php';
header('Content-Type: application/json');

$id = intval($_GET['id'] ?? 0);
if (!$id) {
  echo json_encode(["error" => "ID no válido"]);
  exit;
}

// Obtener platillo
$stmt = $conexion->prepare("SELECT * FROM menu_platillo WHERE id_platillo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$platillo = $result->fetch_assoc();
$stmt->close();

if (!$platillo) {
  echo json_encode(["error" => "Platillo no encontrado"]);
  exit;
}

// Ingredientes del platillo
$ingredientes = [];
$stmt = $conexion->prepare("SELECT ingrediente_id as id, cantidad_necesaria as cantidad FROM menu_platillo_ingredientes WHERE platillo_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resIng = $stmt->get_result();
while ($row = $resIng->fetch_assoc()) {
  $ingredientes[] = $row;
}
$stmt->close();

$platillo['ingredientes'] = $ingredientes;

echo json_encode($platillo);
?>
