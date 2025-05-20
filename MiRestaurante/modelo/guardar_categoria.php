<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No hay sesión activa"]);
    exit;
}

$usuario_id = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_categoria'] ?? '');
    $id_categoria = intval($_POST['id_categoria'] ?? 0);

    if ($nombre === '') {
        echo json_encode(["error" => "Nombre vacío"]);
        exit;
    }

    if ($id_categoria > 0) {
        // Editar categoría
        $stmt = mysqli_prepare($conexion, "UPDATE categorias_platillos SET nombre_categoria = ? WHERE id_categoria = ? AND usuario_id = ?");
        mysqli_stmt_bind_param($stmt, "sii", $nombre, $id_categoria, $usuario_id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(["success" => $success]);
    } else {
        // Verificar si ya existe la categoría para ese usuario
        $check = mysqli_prepare($conexion, "SELECT COUNT(*) FROM categorias_platillos WHERE nombre_categoria = ? AND usuario_id = ?");
        mysqli_stmt_bind_param($check, "si", $nombre, $usuario_id);
        mysqli_stmt_execute($check);
        mysqli_stmt_bind_result($check, $existe);
        mysqli_stmt_fetch($check);
        mysqli_stmt_close($check);

        if ($existe > 0) {
            echo json_encode(["error" => "La categoría ya existe."]);
            exit;
        }

        // Insertar nueva categoría
        $stmt = mysqli_prepare($conexion, "INSERT INTO categorias_platillos (nombre_categoria, usuario_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "si", $nombre, $usuario_id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(["success" => $success]);
    }

    mysqli_close($conexion);
} else {
    echo json_encode(["error" => "Método no permitido"]);
    http_response_code(405);
}
?>