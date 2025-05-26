<?php
header('Content-Type: application/json');
require '../modelo/conexion.php';

$id_platillo = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_usuario = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

if ($id_platillo <= 0 || $id_usuario <= 0) {
    echo json_encode(["error" => "Datos inválidos"]);
    exit;
}

// Consulta para obtener ingredientes requeridos y su disponibilidad
$sql = "
    SELECT i.cantidad AS stock, mpi.cantidad_necesaria
    FROM menu_platillo_ingredientes mpi
    JOIN inventario i ON i.id_Ingrediente = mpi.ingrediente_id
    WHERE mpi.platillo_id = ? AND i.usuario_id = ?
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_platillo, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$max = PHP_INT_MAX;
while ($row = $result->fetch_assoc()) {
    $stock = floatval($row['stock']);
    $necesario = floatval($row['cantidad_necesaria']);

    if ($necesario <= 0) continue;

    $posible = floor($stock / $necesario);
    if ($posible < $max) {
        $max = $posible;
    }
}

echo json_encode(["max_disponible" => max(0, $max)]);
?>