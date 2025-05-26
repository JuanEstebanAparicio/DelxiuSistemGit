<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

$id_usuario = $_SESSION['id_usuario'];

$res = $conexion->query("
  SELECT * FROM ordenes 
  WHERE usuario_id = $id_usuario AND eliminada = 1 
  ORDER BY id DESC
");

$ordenes = [];
while ($o = $res->fetch_assoc()) {
  $detalles = $conexion->query("SELECT * FROM orden_detalles WHERE orden_id = {$o['id']}");
  $o['detalles'] = $detalles->fetch_all(MYSQLI_ASSOC);
  $ordenes[] = $o;
}

echo json_encode($ordenes);
