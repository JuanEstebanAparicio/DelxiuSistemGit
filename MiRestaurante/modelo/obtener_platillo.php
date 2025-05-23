<?php
include '../modelo/conexion.php';
header('Content-Type: application/json');

$id = intval($_GET['id'] ?? 0);
if (!$id) {
  echo json_encode(["error" => "ID no válido"]);
  exit;
}

// Obtener platillo
$stmt = $conexion->prepare("SELECT * FROM menu_platillo WHERE id_platillo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$platillo = $result->fetch_assoc();
$stmt->close();

if (!$platillo) {
  echo json_encode(["error" => "Platillo no encontrado"]);
  exit;
}

// Obtener ingredientes con estado y vencimiento
$ingredientes = [];
$hoy = date("Y-m-d");
$hayVencido = false;

$stmt = $conexion->prepare("
  SELECT 
    ip.ingrediente_id AS id,
    ip.cantidad_necesaria AS cantidad,
    i.nombre,
    i.unidad_medida,
    i.estado,
    i.fecha_vencimiento
  FROM menu_platillo_ingredientes ip
  JOIN inventario i ON ip.ingrediente_id = i.id_Ingrediente
  WHERE ip.platillo_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$resIng = $stmt->get_result();

while ($row = $resIng->fetch_assoc()) {
  $vencido = ($row['fecha_vencimiento'] && $row['fecha_vencimiento'] <= $hoy);
  $noDisponible = in_array($row['estado'], ['agotado', 'no disponible', 'vencido']) || $vencido;

  if ($noDisponible) {
    $hayVencido = true;
  }

  $ingredientes[] = [
    "id" => intval($row["id"]),
    "cantidad" => floatval($row["cantidad"]),
    "nombre" => $row["nombre"],
    "unidad_medida" => $row["unidad_medida"],
    "estado" => $row["estado"],
    "vencido" => $vencido
  ];
}
$stmt->close();

$platillo["ingredientes"] = $ingredientes;

// Si el platillo está "disponible" pero tiene ingredientes vencidos, marcarlo como "no_disponible"
if ($platillo['estado'] === 'disponible' && $hayVencido) {
  $platillo['estado'] = 'no_disponible';
}

echo json_encode($platillo);
?>
