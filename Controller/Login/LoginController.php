<?php
// archivo: Controller/LoginController.php

session_start();
require_once(__DIR__ . '/../../Model/Entity/Usuario.php');
require_once(__DIR__ . '/../../Model/Entity/conexion.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $clave  = $_POST['clave'] ?? '';
    $recordarme = isset($_POST['recordarme']);

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->buscarPorCorreo($correo);

    if ($usuario && password_verify($clave, $usuario['usuario_clave'])) {
        $_SESSION['usuario'] = [
            'id' => $usuario['usuario_id'],
            'nombre' => $usuario['usuario_nombre'],
            'rol' => $usuario['usuario_rol']
        ];

        if ($recordarme && !empty($usuario['usuario_token'])) {
            setcookie('remember_token', $usuario['usuario_token'], time() + (86400 * 30), "/", "", false, true); // 30 días, httpOnly
        }

        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Correo o contraseña incorrectos']);
    }
    exit;
}

http_response_code(405);

