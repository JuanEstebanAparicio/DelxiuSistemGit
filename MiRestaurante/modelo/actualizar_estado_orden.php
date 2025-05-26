<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require '../modelo/conexion.php';

// Validar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

// Leer JSON de entrada
$data = json_decode(file_get_contents("php://input"), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "error" => "JSON inválido",
        "detalle" => json_last_error_msg()
    ]);
    exit;
}

$id = intval($data['id'] ?? 0);
$estado = $data['estado'] ?? '';

// Validar valores
$estados_validos = ['pendiente', 'preparacion', 'listo', 'entregado', 'cancelado'];
if (!$id || !in_array($estado, $estados_validos)) {
    echo json_encode(["error" => "Datos inválidos"]);
    exit;
}

// Registrar historial
$stmt_historial = $conexion->prepare("INSERT INTO historial_estado_orden (orden_id, nuevo_estado, fecha) VALUES (?, ?, NOW())");
$stmt_historial->bind_param("is", $id, $estado);
$stmt_historial->execute();
$stmt_historial->close();

// Actualizar estado de la orden
$stmt = $conexion->prepare("UPDATE ordenes SET estado = ? WHERE id = ?");
$stmt->bind_param("si", $estado, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error al actualizar orden: " . $stmt->error]);
}
$stmt->close();
exit;
