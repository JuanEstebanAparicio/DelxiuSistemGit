<?php
session_start();
header('Content-Type: application/json');
include '../auth/conexion.php';

$usuario_id = $_GET['id_usuario'] ?? ($_SESSION['id_usuario'] ?? null);
if (!$usuario_id) {
    echo json_encode([]);
    exit;
}

$id_categoria = isset($_GET['id_categoria']) ? intval($_GET['id_categoria']) : null;

$platillos = [];

if ($id_categoria) {
    $stmt = $conexion->prepare("
        SELECT id_platillo, nombre, precio, foto 
        FROM menu_platillo 
        WHERE usuario_id = ? AND id_categoria = ? 
        ORDER BY id_platillo DESC
    ");
    $stmt->bind_param("ii", $usuario_id, $id_categoria);
} else {
    $stmt = $conexion->prepare("
        SELECT id_platillo, nombre, precio, foto 
        FROM menu_platillo 
        WHERE usuario_id = ? 
        ORDER BY id_platillo DESC
    ");
    $stmt->bind_param("i", $usuario_id);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $platillos[] = [
        'id' => $row['id_platillo'],
        'nombre' => $row['nombre'],
        'precio' => floatval($row['precio']),
        'foto' => $row['foto'] ?: '../uploads/platillos/default.png'
    ];
}

$stmt->close();
$conexion->close();

echo json_encode($platillos);
