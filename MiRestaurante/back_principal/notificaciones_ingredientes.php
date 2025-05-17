<?php
session_start();
header('Content-Type: application/json');
include '../auth/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$id_usuario = $_SESSION['id'];
$avisos = [];

// Insumos agotados
$sql_agotados = "SELECT nombre FROM inventario WHERE cantidad <= 0 AND usuario_id = ?";
$stmt = $conexion->prepare($sql_agotados);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $avisos[] = "⚠️ El insumo <strong>{$row['nombre']}</strong> está agotado.";
}
$stmt->close();

// Insumos con stock bajo
$sql_bajo = "SELECT nombre FROM inventario WHERE cantidad < cantidad_minima AND cantidad > 0 AND usuario_id = ?";
$stmt = $conexion->prepare($sql_bajo);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $avisos[] = "🔻 El insumo <strong>{$row['nombre']}</strong> tiene stock bajo.";
}
$stmt->close();

// Insumos vencidos
$sql_vencidos = "SELECT nombre FROM inventario WHERE fecha_vencimiento IS NOT NULL AND fecha_vencimiento <= CURDATE() AND usuario_id = ?";
$stmt = $conexion->prepare($sql_vencidos);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $avisos[] = "❌ El insumo <strong>{$row['nombre']}</strong> está vencido.";
}
$stmt->close();

echo json_encode(["avisos" => $avisos]);
?>
