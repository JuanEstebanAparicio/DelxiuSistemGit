<?php 
session_start();
header('Content-Type: application/json');
include("../auth/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "Sesión no iniciada"]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
$nombre_restaurante = trim($_POST['nombre_restaurante'] ?? '');
$clave_actual = $_POST['clave'] ?? '';

if ($nombre_usuario === '' || $nombre_restaurante === '' || $clave_actual === '') {
    echo json_encode(["error" => "Todos los campos son requeridos"]);
    exit;
}

$stmt = $conexion->prepare("SELECT clave FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($clave_hash);
$stmt->fetch();
$stmt->close();

if (!$clave_hash || !password_verify($clave_actual, $clave_hash)) {
    echo json_encode(["error" => "Contraseña incorrecta"]);
    exit;
}

$stmt = $conexion->prepare("UPDATE usuarios SET nombre_usuario = ?, nombre_restaurante = ? WHERE id = ?");
$stmt->bind_param("ssi", $nombre_usuario, $nombre_restaurante, $id_usuario);
$ok = $stmt->execute();
$stmt->close();

echo json_encode($ok ? ["success" => true] : ["error" => "No se pudo actualizar el perfil"]);
?>
