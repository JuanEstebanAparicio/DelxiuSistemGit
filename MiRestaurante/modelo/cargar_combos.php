<?php
session_start();
header('Content-Type: application/json');
include '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "Sesión no iniciada"]);
    exit;
}

$usuario_id = $_SESSION['id_usuario'];

$sql = "SELECT id_combo, nombre_combo, descripcion, precio_combo FROM menu_combos 
        WHERE usuario_id = ? AND estado = 'activo' 
        ORDER BY created_at DESC";

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
];
}

echo json_encode($combos);
