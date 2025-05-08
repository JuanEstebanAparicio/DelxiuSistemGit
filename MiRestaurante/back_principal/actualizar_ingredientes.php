<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

include '../auth/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_usuario = $_SESSION['id_usuario'];

  // Validar existencia de claves antes de usarlas
  $required_fields = ['id', 'nombre', 'cantidad', 'cantidad_minima', 'unidad_medida','costo_unitario' ,'categoria', 'fecha_vencimiento', 'lote', 'descripcion', 'ubicacion_almacen', 'estado'];
  foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
      die("Error: Faltan datos requeridos: $field");
    }
  }

  $id = (int) $_POST['id'];
  $nombre = trim($_POST['nombre']);
  $cantidad = (float) $_POST['cantidad'];
  $cantidad_minima = (float) $_POST['cantidad_minima'];
  $unidad = trim($_POST['unidad_medida']);
  $costo_unitario = trim($_POST['costo_unitario']);
  $categoria = trim($_POST['categoria']);
  $fecha = $_POST['fecha_vencimiento'] ?: null;
  $lote = $_POST['lote'] ?: null;
  $descripcion = trim($_POST['descripcion']);
  $ubicacion_almacen = trim($_POST['ubicacion_almacen']);
  $estado = trim($_POST['estado']);
  $fecha_actual = date('Y-m-d H:i:s');

  $sql = "UPDATE inventario SET nombre = ?, cantidad = ?, cantidad_minima = ?, unidad_medida = ?, costo_unitario = ?, categoria = ?, fecha_vencimiento = ?, lote = ?, descripcion = ?,ubicacion_almacen = ?, estado = ?, ultima_actualizacion = ? WHERE id_Ingrediente = ? AND usuario_id = ?";
  $stmt = $conexion->prepare($sql);
  if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
  }

  $stmt->bind_param("sddsdsssssssii", $nombre, $cantidad, $cantidad_minima, $unidad, $costo_unitario, $categoria, $fecha, $lote, $descripcion, $ubicacion_almacen, $estado, $fecha_actual, $id, $id_usuario);

  if ($stmt->execute()) {
    header("Location: ../front_principal/ver_inventario.php?actualizado=1");
    exit();
  } else {
    echo "Error al actualizar: " . $stmt->error;
  }
} else {
  echo "Método inválido.";
}
?>
