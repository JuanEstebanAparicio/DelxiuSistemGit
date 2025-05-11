<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

include '../auth/conexion.php';

$carpeta_destino = "../uploads/";
$nombre_archivo = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_usuario = $_SESSION['id_usuario'];

  // Validar existencia de claves antes de usarlas
  $required_fields = ['id', 'nombre', 'cantidad', 'cantidad_minima', 'unidad_medida','costo_unitario' ,'categoria', 'fecha_vencimiento', 'lote', 'descripcion', 'ubicacion_almacen', 'estado', 'proveedor'];
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
  $proveedor = trim($_POST['proveedor']);



  $fecha_actual = date('Y-m-d H:i:s');

  // Verificar si se subió nueva imagen
  if (!empty($_FILES['foto']['name'])) {
    $nombre_archivo = uniqid() . "_" . basename($_FILES["foto"]["name"]);
    $ruta_archivo = $carpeta_destino . $nombre_archivo;
if (!is_dir($carpeta_destino)) {
  mkdir($carpeta_destino, 0777, true);
}
    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta_archivo)) {
      die("Error al subir la imagen.");
    }

    $sql = "UPDATE inventario SET nombre = ?, cantidad = ?, cantidad_minima = ?, unidad_medida = ?, costo_unitario = ?, categoria = ?, fecha_vencimiento = ?, lote = ?, descripcion = ?, ubicacion_almacen = ?, estado = ?, proveedor = ?, ultima_actualizacion = ?, foto = ? WHERE id_Ingrediente = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
      die("Error en la preparación con imagen: " . $conexion->error);
    }
    $stmt->bind_param("sddsdssssssssssi", $nombre, $cantidad, $cantidad_minima, $unidad, $costo_unitario, $categoria, $fecha, $lote, $descripcion, $ubicacion_almacen, $estado, $proveedor, $fecha_actual, $nombre_archivo, $id, $id_usuario);
  } else {
    $sql = "UPDATE inventario SET nombre = ?, cantidad = ?, cantidad_minima = ?, unidad_medida = ?, costo_unitario = ?, categoria = ?, fecha_vencimiento = ?, lote = ?, descripcion = ?, ubicacion_almacen = ?, estado = ?, proveedor = ?, ultima_actualizacion = ? WHERE id_Ingrediente = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
      die("Error en la preparación sin imagen: " . $conexion->error);
    }
    $stmt->bind_param("sddsdsssssssssi", $nombre, $cantidad, $cantidad_minima, $unidad, $costo_unitario, $categoria, $fecha, $lote, $descripcion, $ubicacion_almacen, $estado, $proveedor, $fecha_actual, $id, $id_usuario);
  }

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