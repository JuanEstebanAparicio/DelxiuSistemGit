<?php
// Controller/RecuperarPasswordController.php
require_once(__DIR__ . '/../../Model/Crud/Usuario_crud.php');
require_once 'Correo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
    $correo = trim($_POST['correo']);

    $stmt = $pdo->prepare("SELECT usuario_id FROM usuarios WHERE usuario_correo = ? AND usuario_estado = 'activo'");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo "No existe una cuenta activa con ese correo.";
        exit;
    }

    $usuario_id = $usuario['usuario_id'];
    $token = bin2hex(random_bytes(32));
    $enlace = "http://localhost/Proyecto%20de%20aula/Controller/Login/reset-password.php?token=$token";

    $update = $pdo->prepare("UPDATE usuarios SET recuperar_token = ? WHERE usuario_id = ?");
    $update->execute([$token, $usuario_id]);

    if (Correo::enviarRecuperacion($correo, $enlace)) {
        echo "Se ha enviado un enlace a tu correo para restablecer la contraseña.";
    } else {
        echo "Error al enviar el correo. Intenta de nuevo.";
    }
} else {
    echo "Solicitud no válida.";
}
?>

