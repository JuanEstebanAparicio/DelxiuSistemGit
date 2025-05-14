<?php
session_start();
header('Content-Type: application/json');
include '../auth/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "Usuario no autenticado"]);
  exit;
}

$usuario_id = $_SESSION['id_usuario'];

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$tiempo = intval($_POST['tiempo_preparacion'] ?? 0);
$categoria = intval($_POST['id_categoria'] ?? 0);
$ingredientes = json_decode($_POST['ingredientes'] ?? '[]', true);


if ($nombre === '' || $precio <= 0 || $categoria === 0 || empty($ingredientes)) {
  echo json_encode(["error" => "Faltan datos requeridos"]);
  exit;
}

$foto_path = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
  $foto_path = '../uploads/platillos/' . uniqid('platillo_') . "." . $ext;

  $uploadDir = __DIR__ . '/../uploads/platillos/';
  if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
  }

  if (!move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
    echo json_encode(["error" => "Error al subir la imagen"]);
    exit;
  }
}


$stmt = $conexion->prepare("INSERT INTO menu_platillo (nombre, descripcion, precio, id_categoria, tiempo_preparacion, foto, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdissi", $nombre, $descripcion, $precio, $categoria, $tiempo, $foto_path, $usuario_id);
$stmt->execute();
$id_platillo = $stmt->insert_id;
$stmt->close();

$stmt = $conexion->prepare("INSERT INTO menu_platillo_ingredientes (platillo_id, ingrediente_id, cantidad_necesaria) VALUES (?, ?, ?)");
foreach ($ingredientes as $ing) {
  $id = intval($ing['id']);
  $cant = floatval($ing['cantidad']);
  $stmt->bind_param("iid", $id_platillo, $id, $cant);
  $stmt->execute();
}
$stmt->close();

echo json_encode(["success" => true]);
?>
