<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$usuario_id = $data['usuario_id'] ?? null;
$cliente_nombre = trim($data['nombre_cliente'] ?? '');
$cliente_contacto = trim($data['telefono'] ?? '');
$mesa = trim($data['mesa'] ?? '');
$orden_items = $data['carrito'] ?? [];
$propina = floatval($data['propina'] ?? 0);

if (!$usuario_id || empty($orden_items) || $cliente_nombre === '' || $mesa === '') {
    echo json_encode(["success" => false, "error" => "Datos incompletos para guardar orden"]);
    exit;
}

$total = array_sum(array_map(fn($p) => floatval($p['precio']), $orden_items)) + $propina;

// Guardar orden
$stmt = $conexion->prepare("
    INSERT INTO ordenes (usuario_id, cliente_nombre, cliente_contacto, mesa, nota, total, propina)
    VALUES (?, ?, ?, ?, '', ?, ?)
");
$stmt->bind_param("isssdd", $usuario_id, $cliente_nombre, $cliente_contacto, $mesa, $total, $propina);
$stmt->execute();
$orden_id = $stmt->insert_id;
$stmt->close();

// Detalles
$stmt_detalle = $conexion->prepare("
    INSERT INTO orden_detalles (orden_id, platillo_id, nombre, precio)
    VALUES (?, ?, ?, ?)
");
foreach ($orden_items as $item) {
    $stmt_detalle->bind_param("iisd", $orden_id, $item['id'], $item['nombre'], $item['precio']);
    $stmt_detalle->execute();
}
$stmt_detalle->close();

echo json_encode(["success" => true, "orden_id" => $orden_id]);
