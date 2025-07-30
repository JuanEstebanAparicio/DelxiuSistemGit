<?php
require_once(__DIR__ . '/../../Model/Entity/conexion.php');


header('Content-Type: application/json');

try {
    global $pdo;
    $stmt = $pdo->prepare("SELECT correo FROM usuarios_temp ORDER BY creado_en DESC LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();

    if ($row) {
        echo json_encode(['status' => 'ok', 'correo' => $row['correo']]);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'No se encontró correo temporal']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => 'Error en la base de datos']);
}
