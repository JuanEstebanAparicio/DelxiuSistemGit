<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../auth/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No hay sesión activa"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_categoria = intval($_POST['id_categoria'] ?? 0);
    $usuario_id = $_SESSION['id_usuario'];

    if (!$id_categoria) {
        echo json_encode(["error" => "ID inválido"]);
        exit;
    }

    $stmt = mysqli_prepare($conexion, "DELETE FROM categorias_platillos WHERE id_categoria = ? AND usuario_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_categoria, $usuario_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode(["success" => $success]);
    mysqli_close($conexion);
} else {
    echo json_encode(["error" => "Método no permitido"]);
    http_response_code(405);
}
?>