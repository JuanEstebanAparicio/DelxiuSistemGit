<?php
session_start();
header('Content-Type: application/json');
include '../modelo/conexion.php';

$usuario_id = $_GET['id_usuario'] ?? ($_SESSION['id_usuario'] ?? null);
if (!$usuario_id) {
    echo json_encode([]);
    exit;
}

$id_categoria = isset($_GET['id_categoria']) ? intval($_GET['id_categoria']) : null;
$modoCliente = isset($_GET['cliente']); // si viene desde ver_menu

$platillos = [];

if ($id_categoria) {
    $sql = "SELECT id_platillo, nombre, precio, foto, estado 
            FROM menu_platillo 
            WHERE usuario_id = ? AND id_categoria = ?";
    if ($modoCliente) {
        $sql .= " AND estado = 'disponible'";
    }
    $sql .= " ORDER BY id_platillo DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $id_categoria);
} else {
    $sql = "SELECT id_platillo, nombre, precio, foto, estado 
            FROM menu_platillo 
            WHERE usuario_id = ?";
    if ($modoCliente) {
        $sql .= " AND estado = 'disponible'";
    }
    $sql .= " ORDER BY id_platillo DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $platillos[] = [
        'id' => $row['id_platillo'],
        'nombre' => $row['nombre'],
        'precio' => floatval($row['precio']),
        'foto' => $row['foto'] ?: '../uploads/platillos/default.png',
        'estado' => $row['estado']
    ];
}

$stmt->close();
$conexion->close();

echo json_encode($platillos);
?>