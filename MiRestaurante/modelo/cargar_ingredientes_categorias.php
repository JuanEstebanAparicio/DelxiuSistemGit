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

// Obtener ingredientes activos
$ingredientes = [];
$stmt = $conexion->prepare("SELECT id_Ingrediente, nombre, unidad_medida FROM inventario WHERE estado = 'activo' AND usuario_id = ? ORDER BY nombre ASC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $ingredientes[] = $row;
}
$stmt->close();

// Respuesta combinada
echo json_encode([
  "categorias" => $categorias,
  "ingredientes" => $ingredientes
]);
?>
