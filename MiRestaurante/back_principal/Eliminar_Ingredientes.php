<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

include '../auth/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$id = intval($_GET['id'] ?? 0);

// ✅ Incluir foto también aquí
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

// ✅ Eliminar imagen si existe
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

// 📝 Registrar en historial
$accion = 'Eliminación';
$historial = $conexion->prepare("INSERT INTO historial_inventario (nombre, cantidad, lote, fecha_vencimiento, accion, usuario_id)
VALUES (?, ?, ?, ?, ?, ?)");
$historial->bind_param("sdsssi", $nombre, $cantidad, $lote, $fecha_vencimiento, $accion, $id_usuario);
$historial->execute();
$historial->close();

$conexion->close();

echo "<script>alert('🗑️ Ingrediente eliminado correctamente'); window.location.href = '../front_principal/ver_inventario.php';</script>";
?>
