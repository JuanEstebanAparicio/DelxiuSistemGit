<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "Usuario no autenticado"]);
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id = intval($_POST['id_platillo']);

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$tiempo = intval($_POST['tiempo_preparacion'] ?? 0);
$categoria = intval($_POST['id_categoria'] ?? 0);

$estado_input = $_POST['estado'] ?? 'disponible';
$estado_final = in_array($estado_input, ['disponible', 'no_disponible']) ? $estado_input : 'disponible';

$foto_actual = $_POST['foto_actual'] ?? '';
$ingredientes = json_decode($_POST['ingredientes'] ?? '[]', true);

// Validaciones
if ($id <= 0 || $nombre === '' || $precio <= 0 || $categoria <= 0 || empty($ingredientes)) {
  echo json_encode(["error" => "Faltan datos requeridos"]);
  exit;
}

$estado_forzado = false;

// ⚠️ Verificar si algún ingrediente obliga a marcar el platillo como agotado
foreach ($ingredientes as $ing) {
  $id_ing = intval($ing['id']);
  $res = $conexion->query("SELECT cantidad, estado, fecha_vencimiento FROM inventario WHERE id_Ingrediente = $id_ing AND usuario_id = $id_usuario");
  if ($res && $row = $res->fetch_assoc()) {
    $cantidad = floatval($row['cantidad']);
    $estado_ing = $row['estado'];
    $vencido = $row['fecha_vencimiento'] && $row['fecha_vencimiento'] <= date("Y-m-d");

    if ($cantidad <= 0 || $estado_ing !== 'activo' || $vencido) {
      $estado_final = 'agotado';
      $estado_forzado = true;
      break;
    }
  }
}

// Manejar imagen
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
  SET nombre = ?, descripcion = ?, precio = ?, id_categoria = ?, tiempo_preparacion = ?, estado = ?, foto = ?
  WHERE id_platillo = ? AND usuario_id = ?
");
$stmt->bind_param("ssdisssii", $nombre, $descripcion, $precio, $categoria, $tiempo, $estado_final, $foto_path, $id, $id_usuario);
$stmt->execute();
$stmt->close();

// Limpiar e insertar ingredientes
$conexion->query("DELETE FROM menu_platillo_ingredientes WHERE platillo_id = $id");

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

// 🔁 Verificar si debe marcarse como agotado según insumos (segunda verificación post-DB)
$agotado = false;
$fecha_hoy = date("Y-m-d");

$sql = "
  SELECT i.estado, i.cantidad, i.fecha_vencimiento, mpi.cantidad_necesaria
  FROM menu_platillo_ingredientes mpi
  JOIN inventario i ON i.id_Ingrediente = mpi.ingrediente_id
  WHERE mpi.platillo_id = $id AND i.usuario_id = $id_usuario
";
$res = $conexion->query($sql);

while ($row = $res->fetch_assoc()) {
  $estadoIng = $row['estado'];
  $cantidad = floatval($row['cantidad']);
  $necesaria = floatval($row['cantidad_necesaria']);
  $vencido = $row['fecha_vencimiento'] && $row['fecha_vencimiento'] <= $fecha_hoy;

  if ($cantidad < $necesaria || $estadoIng !== 'activo' || $vencido) {
    $agotado = true;
    break;
  }
}

$estadoFinal = $agotado ? 'agotado' : $estado_final;
$conexion->query("UPDATE menu_platillo SET estado = '$estadoFinal' WHERE id_platillo = $id");

echo json_encode([
  "success" => true,
  "estado_final" => $estadoFinal,
  "estado_forzado" => $estado_forzado ? "agotado" : null
]);
?>