<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

include '../modelo/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$id = intval($_GET['id'] ?? 0);

// 🔎 Verificar si el ingrediente está usado en algún platillo
$verifica = $conexion->prepare("
  SELECT COUNT(*) 
  FROM menu_platillo_ingredientes
  WHERE ingrediente_id = ?
");
$verifica->bind_param("i", $id);
$verifica->execute();
$verifica->bind_result($usado);
$verifica->fetch();
$verifica->close();

if ($usado > 0 && !isset($_GET['confirmado'])) {
  header("Location: ../Ver/confirmar_eliminar_ingrediente.php?id=$id");
  exit();
}


// ✅ Obtener datos previos para historial
$consulta = $conexion->prepare("SELECT nombre, cantidad, lote, fecha_vencimiento, foto FROM inventario WHERE id_Ingrediente = ? AND usuario_id = ?");
$consulta->bind_param("ii", $id, $id_usuario);
$consulta->execute();
$consulta->store_result();

if ($consulta->num_rows === 0) {
  echo "<script>alert('Ingrediente no encontrado'); window.history.back();</script>";
  exit();
}

$consulta->bind_result($nombre, $cantidad, $lote, $fecha_vencimiento, $foto);
$consulta->fetch();
$consulta->close();

// 🧹 Eliminar foto
if (!empty($foto)) {
  $ruta_foto = "../uploads/" . $foto;
  if (file_exists($ruta_foto)) {
    unlink($ruta_foto);
  }
}

// 🗑 Eliminar ingrediente
$eliminar = $conexion->prepare("DELETE FROM inventario WHERE id_Ingrediente = ? AND usuario_id = ?");
$eliminar->bind_param("ii", $id, $id_usuario);
$eliminar->execute();
$eliminar->close();

// 📝 Registrar historial
$accion = 'Eliminación';
$historial = $conexion->prepare("INSERT INTO historial_inventario (nombre, cantidad, lote, fecha_vencimiento, accion, usuario_id)
VALUES (?, ?, ?, ?, ?, ?)");
$historial->bind_param("sdsssi", $nombre, $cantidad, $lote, $fecha_vencimiento, $accion, $id_usuario);
$historial->execute();
$historial->close();

$conexion->close();

echo "<script>alert('🗑️ Ingrediente eliminado correctamente'); window.location.href = '../Ver/Ver_inventario.php';</script>";
?>
