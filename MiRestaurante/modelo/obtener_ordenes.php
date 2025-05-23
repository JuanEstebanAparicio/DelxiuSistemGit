<?php
require 'conexion.php';

$id_usuario = intval($_GET['id_usuario'] ?? 0);
$ordenes = [];

$sql = "
  SELECT o.id_orden, o.nombre_cliente, o.telefono, o.fecha, o.estado, o.propina, 
         GROUP_CONCAT(CONCAT(p.nombre, ' ($', p.precio, ')') SEPARATOR '\\n') AS detalle,
         SUM(p.precio) + o.propina AS total
  FROM ordenes o
  JOIN orden_detalle d ON o.id_orden = d.orden_id
  JOIN menu_platillo p ON d.platillo_id = p.id_platillo
  WHERE o.usuario_id = ?
  GROUP BY o.id_orden
  ORDER BY o.fecha DESC
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
  $ordenes[] = [
    "id" => $row["id_orden"],
    "nombre_cliente" => $row["nombre_cliente"],
    "telefono" => $row["telefono"],
    "fecha" => $row["fecha"],
    "estado" => $row["estado"],
    "detalle" => $row["detalle"],
    "total" => floatval($row["total"])
  ];
}

echo json_encode($ordenes);
