<?php
session_start();
header('Content-Type: application/json');
include '../auth/conexion.php';

// Validación de sesión
if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "Usuario no autenticado"]);
  exit;
}

$id = intval($_POST['id_platillo']);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$tiempo = intval($_POST['tiempo_preparacion'] ?? 0);
$categoria = intval($_POST['id_categoria'] ?? 0);
$foto_actual = $_POST['foto_actual'] ?? '';
$ingredientes = json_decode($_POST['ingredientes'] ?? '[]', true);

// Validar campos requeridos
if ($id <= 0 || $nombre === '' || $precio <= 0 || $categoria <= 0 || empty($ingredientes)) {
  echo json_encode(["error" => "Faltan datos requeridos"]);
  exit;
}

// Manejo de imagen nueva
$foto_path = $foto_actual;
if (!empty($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  if ($foto_actual && file_exists($foto_actual)) {
    unlink($foto_actual);
  }

  $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
  $foto_path = '../uploads/platillos/' . uniqid('platillo_') . "." . $ext;
  move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path);
}

// Actualizar platillo
$stmt = $conexion->prepare("
  UPDATE menu_platillo 
  SET nombre = ?, descripcion = ?, precio = ?, id_categoria = ?, tiempo_preparacion = ?, foto = ?
  WHERE id_platillo = ?
");
$stmt->bind_param("ssdissi", $nombre, $descripcion, $precio, $categoria, $tiempo, $foto_path, $id);
$stmt->execute();
$stmt->close();

// Limpiar ingredientes antiguos
$conexion->query("DELETE FROM menu_platillo_ingredientes WHERE platillo_id = $id");

// Insertar ingredientes nuevos
$stmt = $conexion->prepare("
  INSERT INTO menu_platillo_ingredientes (platillo_id, ingrediente_id, cantidad_necesaria)
  VALUES (?, ?, ?)
");
foreach ($ingredientes as $ing) {
  if (!isset($ing['id']) || !isset($ing['cantidad'])) continue;
  $ingId = intval($ing['id']);
  $cant = floatval($ing['cantidad']);
  $stmt->bind_param("iid", $id, $ingId, $cant);
  $stmt->execute();
}
$stmt->close();

echo json_encode(["success" => true]);
?>
