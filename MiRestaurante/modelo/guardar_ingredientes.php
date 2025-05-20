<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

include '../modelo/conexion.php';

$carpeta_destino = "../uploads/";
$nombre_archivo = "";

$id_usuario = $_SESSION['id_usuario'];

$nombre = $_POST['nombre'] ?? '';
$cantidad = floatval($_POST['cantidad'] ?? 0);
$cantidad_minima = floatval($_POST['cantidad_minima'] ?? 0);

$unidad_medida = ($_POST['unidad_medida'] === 'otro' && !empty($_POST['unidad_personalizada']))
    ? trim($_POST['unidad_personalizada'])
    : trim($_POST['unidad_medida']);

$costo_unitario = floatval($_POST['costo_unitario'] ?? 0);

$categorias_validas = [
  "Verduras", "Frutas", "Lácteos", "Carnes", "Cereales y granos", 
  "Panadería", "Enlatados", "Especias", "Snacks", "Bebidas"
];

$categoria = '';
if (isset($_POST['categoria'])) {
  if ($_POST['categoria'] === 'otro' && isset($_POST['categoria_personalizada']) && trim($_POST['categoria_personalizada']) !== '') {
    $categoria = trim($_POST['categoria_personalizada']);
  } elseif (in_array($_POST['categoria'], $categorias_validas)) {
    $categoria = trim($_POST['categoria']);
  }
}

if (empty($categoria)) {
  echo "<script>alert('❌ Debes seleccionar una categoría o escribir una personalizada'); window.history.back();</script>";
  exit();
}

$lote = (isset($_POST['lote']) && $_POST['lote'] === 'otro' && !empty($_POST['lote_personalizado']))
    ? trim($_POST['lote_personalizado'])
    : trim($_POST['lote'] ?? '');

$fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
$descripcion = $_POST['descripcion'] ?? NULL;
$ubicacion_almacen = $_POST['ubicacion_almacen'] ?? NULL;
$estado = $_POST['estado'] ?? 'activo';
$proveedor = $_POST['proveedor'] ?? NULL;

$nombre_archivo = NULL;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
  $nombre_archivo = "ingrediente_" . uniqid() . "." . $ext;

  if (!is_dir($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true);
  }

  $tmp_name = $_FILES['foto']['tmp_name'];
  if (!move_uploaded_file($tmp_name, $carpeta_destino . $nombre_archivo)) {
    echo "<script>alert('Error al guardar la imagen'); window.history.back();</script>";
    exit();
  }
}

if (empty($nombre) || empty($unidad_medida) || empty($categoria)) {
  echo "<script>alert('Campos obligatorios incompletos'); window.history.back();</script>";
  exit();
}

if ($cantidad <= 0) {
  echo "<script>alert('❌ La cantidad debe ser mayor a cero'); window.history.back();</script>";
  exit();
}

if ($cantidad_minima < 0) {
  echo "<script>alert('❌ La cantidad mínima no puede ser negativa'); window.history.back();</script>";
  exit();
}

$verificar = $conexion->prepare("SELECT id_Ingrediente, cantidad, estado FROM inventario WHERE nombre = ? AND lote = ? AND fecha_vencimiento = ? AND usuario_id = ?");
$verificar->bind_param("sssi", $nombre, $lote, $fecha_vencimiento, $id_usuario);
$verificar->execute();
$verificar->store_result();

if ($verificar->num_rows > 0) {
  $verificar->bind_result($idExistente, $cantidadExistente, $estadoExistente);
  $verificar->fetch();

  if ($estadoExistente !== 'activo') {
    echo "<script>alert('Ya existe un ingrediente con este nombre, lote y fecha de vencimiento, pero no está activo. No se permite duplicar.'); window.history.back();</script>";
    $verificar->close();
    $conexion->close();
    exit();
  }

  $nuevaCantidad = $cantidadExistente + $cantidad;
  $actualizar = $conexion->prepare("UPDATE inventario SET cantidad = ? WHERE id_Ingrediente = ?");
  $actualizar->bind_param("di", $nuevaCantidad, $idExistente);
  $actualizar->execute();
  $actualizar->close();

  $accion = 'Modificación';
  $idRef = $idExistente;
} else {
  $hoy = date('Y-m-d');

  if (!empty($fecha_vencimiento) && $fecha_vencimiento < $hoy) {
    echo "<script>alert('❌ No se puede registrar un ingrediente ya vencido'); window.history.back();</script>";
    exit();
  }

  $stmt = $conexion->prepare("INSERT INTO inventario (
    nombre, cantidad, cantidad_minima, unidad_medida, costo_unitario, categoria,
    fecha_vencimiento, lote, descripcion, ubicacion_almacen, estado,
    proveedor, foto, usuario_id
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param("sddssssssssssi",
    $nombre, $cantidad, $cantidad_minima, $unidad_medida,
    $costo_unitario, $categoria, $fecha_vencimiento, $lote,
    $descripcion, $ubicacion_almacen, $estado, $proveedor,
    $nombre_archivo, $id_usuario
  );
  if (!$stmt->execute()) {

    echo "<script>alert('Error al registrar'); window.history.back();</script>";
    $stmt->close();
    $conexion->close();
    exit();
  }

  $idRef = $stmt->insert_id;
  $stmt->close();
  $accion = 'Ingreso';
}

$historial = $conexion->prepare("INSERT INTO historial_inventario (
  nombre, cantidad, lote, fecha_vencimiento, accion, usuario_id
) VALUES (?, ?, ?, ?, ?, ?)");
$historial->bind_param("sdsssi", $nombre, $cantidad, $lote, $fecha_vencimiento, $accion, $id_usuario);
$historial->execute();
$historial->close();

$verificar->close();
$conexion->close();

echo "<script>alert('✅ Ingrediente registrado correctamente'); window.location.href = '../Ver/registro_ingrediente.php?mensaje=registrado';</script>";
?>
