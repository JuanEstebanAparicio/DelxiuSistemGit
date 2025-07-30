<?php
// archivo: Middleware/auth.php

session_start();

if (!isset($_SESSION['usuario'])) {
    if (isset($_COOKIE['remember_token'])) {
        require_once __DIR__ . '/../conexion.php';

        $token = $_COOKIE['remember_token'];
        $stmt = $pdo->prepare("SELECT usuario_id, usuario_nombre, usuario_rol FROM usuarios WHERE usuario_token = ? AND usuario_estado = 'activo'");
        $stmt->execute([$token]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $_SESSION['usuario'] = [
                'id' => $usuario['usuario_id'],
                'nombre' => $usuario['usuario_nombre'],
                'rol' => $usuario['usuario_rol']
            ];
            return; // acceso concedido
        }
    }

    // Si no hay sesión ni token válido
    header('Location: ../View/inicio_de_pag.php');
    exit();
}

