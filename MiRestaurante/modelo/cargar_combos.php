<?php
session_start();
header('Content-Type: application/json');
require 'conexion.php';

$usuario_id = $_GET['id_usuario'] ?? ($_SESSION['id_usuario'] ?? 0);

if (!$usuario_id) {
    echo json_encode(["error" => "No autenticado o falta id_usuario"]);
    exit;
}

// Cliente (ver_menu) => solo combos activos
if (isset($_GET['id_usuario'])) {
    $sql = "SELECT id_combo, nombre_combo, descripcion, precio_combo, estado
            FROM menu_combos
            WHERE usuario_id = ? AND estado = 'activo'
            ORDER BY created_at DESC";
} else {
    // Usuario logueado (gestor) => todos los combos
    $sql = "SELECT id_combo, nombre_combo, descripcion, precio_combo, estado
            FROM menu_combos
            WHERE usuario_id = ?
            ORDER BY created_at DESC";
}

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$combos = [];
while ($row = $result->fetch_assoc()) {
    $combos[] = [
        'id' => $row['id_combo'],
        'nombre_combo' => $row['nombre_combo'],
        'descripcion' => $row['descripcion'],
        'precio_combo' => floatval($row['precio_combo']),
        'estado' => $row['estado'],
    ];
}

echo json_encode($combos);
?>

