<?php
// cargar_categorias.php
include '../auth/conexion.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($conexion) || !$conexion) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$usuario_id = 1;
$stmt = mysqli_prepare($conexion, "SELECT id_categoria, nombre_categoria FROM categorias_platillos WHERE usuario_id = ? ORDER BY nombre_categoria ASC");

if (!$stmt) {
    echo json_encode(["error" => "Error en la consulta: " . mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$categorias = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categorias[] = $row;
}

echo json_encode($categorias);

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>


