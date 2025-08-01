<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once(__DIR__ . '/../../Model/Entity/UsuarioTemp.php');
require_once(__DIR__ . '/Correo.php');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$nombre = $_POST['nombre_usuario'] ?? '';
$correo = $_POST['correo'] ?? '';
$restaurante = $_POST['nombre_restaurante'] ?? '';
$clave = $_POST['clave'] ?? '';

if (!$nombre || !$correo || !$restaurante || !$clave) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'msg' => 'Faltan campos obligatorios']);
    exit;
}

$claveHash = password_hash($clave, PASSWORD_DEFAULT);
$codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

$usuarioTemp = new UsuarioTemp();

if ($usuarioTemp->insertar($nombre, $correo, $restaurante, $claveHash, $codigo)) {
    if (Correo::enviar($correo, $codigo)) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Error al enviar el correo']);
    }
} else {
    echo json_encode(['status' => 'error', 'msg' => 'Correo ya registrado']);
}
