<?php
session_start();
header('Content-Type: application/json');
include '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "Usuario no autenticado"]);
  exit;
}

$usuario_id = $_SESSION['id_usuario'];

// Obtener categorías
$categorias = [];
$stmt = $conexion->prepare("SELECT id_categoria, nombre_categoria FROM categorias_platillos WHERE usuario_id = ? ORDER BY nombre_categoria ASC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $categorias[] = $row;
}
$stmt->close();

// Obtener TODOS los ingredientes (no solo activos)
$ingredientes = [];
$stmt = $conexion->prepare("
  SELECT id_Ingrediente, nombre, unidad_medida, estado, fecha_vencimiento
  FROM inventario
  WHERE usuario_id = ?
  ORDER BY nombre ASC
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $ingredientes[] = [
    "id_Ingrediente" => $row["id_Ingrediente"],
    "nombre" => $row["nombre"],
    "unidad_medida" => $row["unidad_medida"],
    "estado" => $row["estado"],
    "fecha_vencimiento" => $row["fecha_vencimiento"]
  ];
}
$stmt->close();

echo json_encode([
  "categorias" => $categorias,
  "ingredientes" => $ingredientes
]);
?>
