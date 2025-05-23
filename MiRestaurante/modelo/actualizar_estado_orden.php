<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require '../modelo/conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validar JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "error" => "JSON inválido",
        "detalle" => json_last_error_msg()
    ]);
    exit;
}

// Extraer datos
$id = intval($data['id'] ?? 0);
$estado = $data['estado'] ?? '';

// Validar estado
$estados_validos = ['pendiente', 'preparacion', 'listo', 'entregado', 'cancelado'];
if (!$id || !in_array($estado, $estados_validos)) {
    echo json_encode(["error" => "Datos inválidos"]);
    exit;
}

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

// Registrar en historial
$stmt_historial = $conexion->prepare("
    INSERT INTO historial_estado_orden (orden_id, nuevo_estado, fecha) 
    VALUES (?, ?, NOW())
");
$stmt_historial->bind_param("is", $id, $estado);
$stmt_historial->execute();
$stmt_historial->close();

// Actualizar estado de la orden
$stmt_actualizar = $conexion->prepare("
    UPDATE ordenes SET estado = ? WHERE id = ?
");
$stmt_actualizar->bind_param("si", $estado, $id);

if ($stmt_actualizar->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error al actualizar orden"]);
}
$stmt_actualizar->close();
exit;
l