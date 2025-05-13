<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No hay sesión activa"]);
    exit;
}
$usuario_id = $_SESSION['id_usuario'];

$conexion = mysqli_connect("localhost", "root", "", "restaurante", 3307);
if (!$conexion) {
    http_response_code(500);
    echo json_encode(["error" => "❌ Error de conexión: " . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_categoria'] ?? '');

    if ($nombre === '') {
        echo json_encode(["error" => "Datos incompletos"]);
        exit;
    }

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

    $stmt = mysqli_prepare($conexion, "INSERT INTO categorias_platillos (nombre_categoria, usuario_id) VALUES (?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $nombre, $usuario_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Consulta fallida: " . mysqli_error($conexion)]);
    }

    mysqli_close($conexion);
} else {
    echo json_encode(["error" => "Método no permitido"]);
    http_response_code(405);
}
?>
