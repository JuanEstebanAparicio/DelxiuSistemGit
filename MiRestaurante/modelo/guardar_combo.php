<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';
require_once 'funciones_estado.php';

if (!isset($_SESSION['id_usuario'])) {
  echo json_encode(["error" => "Sesión no iniciada"]);
  exit;
}

$usuario_id = $_SESSION['id_usuario'];
$id_combo = isset($_POST['id_combo']) && is_numeric($_POST['id_combo']) ? intval($_POST['id_combo']) : null;
$nombre = trim($_POST['nombre_combo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$estado_input = $_POST['estado'] ?? 'activo'; // solo se acepta activo/inactivo
$precio = floatval($_POST['precio_combo'] ?? 0);
$platillos = $_POST['platillos'] ?? [];

if ($nombre === '' || $precio <= 0 || empty($platillos)) {
  echo json_encode(["error" => "Faltan datos del combo"]);
  exit;
}

$estado_final = in_array($estado_input, ['activo', 'inactivo']) ? $estado_input : 'inactivo';

// ⚠️ Verificar si algún platillo está agotado
$agotado = false;
foreach ($platillos as $pid) {
  $pid = intval($pid);
  $res = $conexion->query("SELECT estado FROM menu_platillo WHERE id_platillo = $pid AND usuario_id = $usuario_id");
  if ($res && $row = $res->fetch_assoc()) {
    if ($row['estado'] === 'agotado') {
      $agotado = true;
      break;
    }
  }
}
if ($agotado) $estado_final = 'agotado';

if ($id_combo) {
  // 🔁 Actualizar combo
  $stmt = $conexion->prepare("UPDATE menu_combos 
    SET nombre_combo = ?, descripcion = ?, precio_combo = ?, estado = ? 
    WHERE id_combo = ? AND usuario_id = ?");
  $stmt->bind_param("ssdsii", $nombre, $descripcion, $precio, $estado_final, $id_combo, $usuario_id);

  if (!$stmt->execute()) {
    echo json_encode(["error" => "Error al actualizar combo: " . $stmt->error]);
    exit;
  }
  $stmt->close();
  $conexion->query("DELETE FROM menu_combo_detalles WHERE combo_id = $id_combo");

} else {
  // 🆕 Insertar nuevo combo
  $stmt = $conexion->prepare("INSERT INTO menu_combos 
    (usuario_id, nombre_combo, descripcion, precio_combo, estado) 
    VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issds", $usuario_id, $nombre, $descripcion, $precio, $estado_final);

  if (!$stmt->execute()) {
    echo json_encode(["error" => "Error al guardar combo: " . $stmt->error]);
    exit;
  }

  $id_combo = $stmt->insert_id;
  $stmt->close();
}

// 💾 Insertar platillos
$stmtDetalle = $conexion->prepare("INSERT INTO menu_combo_detalles (combo_id, platillo_id) VALUES (?, ?)");
foreach ($platillos as $pid) {
  $id_platillo = intval($pid);
  $stmtDetalle->bind_param("ii", $id_combo, $id_platillo);
  $stmtDetalle->execute();
}
$stmtDetalle->close();

echo json_encode(["success" => true, "estado" => $estado_final]);
