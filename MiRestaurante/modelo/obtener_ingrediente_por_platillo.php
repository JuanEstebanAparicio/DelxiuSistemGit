<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

$id_platillo = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_platillo <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "ID de platillo no válido"]);
    exit;
}

$sql = "
    SELECT i.id_Ingrediente, i.nombre, i.unidad_medida, mpi.cantidad_necesaria
    FROM menu_platillo_ingredientes mpi
    JOIN inventario i ON i.id_Ingrediente = mpi.ingrediente_id
    WHERE mpi.platillo_id = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_platillo);
$stmt->execute();
$res = $stmt->get_result();

$ingredientes = [];
while ($row = $res->fetch_assoc()) {
    $ingredientes[] = [
        'id' => $row['id_Ingrediente'],
        'nombre' => $row['nombre'],
        'unidad' => $row['unidad_medida'],
        'cantidad_necesaria' => floatval($row['cantidad_necesaria']),
    ];
}

echo json_encode($ingredientes, JSON_UNESCAPED_UNICODE);
?>
