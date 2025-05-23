<?php 
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../modelo/conexion.php';

// Prioridad: parámetro GET → sesión
$usuario_id = $_GET['id_usuario'] ?? ($_SESSION['id_usuario'] ?? null);

if (!$usuario_id) {
    echo json_encode(["error" => "No se proporcionó un usuario válido."]);
    exit;
}

$stmt = mysqli_prepare($conexion, "
    SELECT id_categoria, nombre_categoria 
    FROM categorias_platillos 
    WHERE usuario_id = ? 
    ORDER BY nombre_categoria ASC
");

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