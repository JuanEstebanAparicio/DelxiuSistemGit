<?php
require_once(__DIR__ . '/../../Model/Entity/UsuarioTemp.php');
require_once(__DIR__ . '/../../Model/Entity/Usuario.php');

header('Content-Type: application/json');  

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'msg' => 'Método no permitido']);
    exit;
}

$correo = $_POST['correo'] ?? '';
$codigo = $_POST['codigo'] ?? '';

if (!$correo || !$codigo) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'msg' => 'Faltan datos']);
    exit;
}

$usuarioTemp = new UsuarioTemp();
$tempData = $usuarioTemp->obtenerPorCorreo($correo);

if (!$tempData || $tempData['codigo'] !== $codigo) {
    echo json_encode(['status' => 'error', 'msg' => 'Código inválido o expirado']);
    exit;
}

$usuario = new Usuario();
$usuario->crearDesdeTemporal($tempData);
$usuarioTemp->eliminar($correo);

echo json_encode(['status' => 'verificado']);
