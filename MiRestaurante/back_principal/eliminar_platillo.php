<?php
session_start();
header('Content-Type: application/json');
include '../auth/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "Usuario no autenticado"]);
  exit;
}

$id_platillo = intval($_POST['id_platillo'] ?? 0);
$usuario_id = $_SESSION['id_usuario'];

if ($id_platillo <= 0) {
  echo json_encode(["error" => "ID inválido"]);
  exit;
}

// Obtener la ruta de imagen
$stmt = $conexion->prepare("SELECT foto FROM menu_platillo WHERE id_platillo = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id_platillo, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$platillo = $result->fetch_assoc();
$stmt->close();

// Eliminar la imagen si existe
if ($platillo && !empty($platillo['foto']) && file_exists($platillo['foto'])) {
  unlink($platillo['foto']);
}

// Eliminar ingredientes asociados
$stmt = $conexion->prepare("DELETE FROM menu_platillo_ingredientes WHERE platillo_id = ?");
$stmt->bind_param("i", $id_platillo);
$stmt->execute();
$stmt->close();

// Eliminar el platillo
$stmt = $conexion->prepare("DELETE FROM menu_platillo WHERE id_platillo = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id_platillo, $usuario_id);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true]);
?>
