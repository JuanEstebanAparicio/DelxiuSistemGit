<?php
session_start();
include '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  header('Location: ../Ver/Login.php');
  exit();
}

if (!isset($_POST['id']) || !is_array($_POST['id'])) {
  echo "<script>alert('❌ No se seleccionó ningún registro'); window.history.back();</script>";
  exit();
}

$ids = $_POST['id'];
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids)); // todos son enteros

$stmt = $conexion->prepare("DELETE FROM historial_inventario WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);

if ($stmt->execute()) {
  echo "<script>alert('✅ Registros eliminados correctamente'); window.location.href = '../Ver/Ver_historial.php';</script>";
} else {
  echo "<script>alert('❌ Error al eliminar registros'); window.history.back();</script>";
}
?>
