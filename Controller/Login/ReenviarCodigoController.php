<?php
require_once '../Model/Entity/UsuarioTemp.php';
require_once '../Controller/Login/Correo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';

    if (empty($correo)) {
        echo json_encode(['status' => 'error', 'msg' => 'Correo requerido']);
        exit;
    }

    $usuarioTemp = new UsuarioTemp();
    $datos = $usuarioTemp->reenviarCodigoExistente($correo);

    if (!$datos) {
        echo json_encode(['status' => 'error', 'msg' => 'No se encontró ningún registro temporal activo']);
        exit;
    }

    $codigo = $datos['codigo'];
    $reenviado = Correo::enviar($correo, $codigo);

    if ($reenviado) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Error al enviar el correo']);
    }

    exit;
}

http_response_code(405);
