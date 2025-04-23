<?php
require 'conexion.php';
session_start();

$codigo_ingresado = $_POST['codigo'];
$correo = $_SESSION['registro']['correo'] ?? null;

if (!$correo) {
    echo "Sesión expirada.";
    exit;
}

// Verificamos si el código existe y no ha expirado (30 minutos)
$sql = "SELECT * FROM codigos_verificacion WHERE correo = ? AND codigo = ? AND fecha_envio >= NOW() - INTERVAL 30 MINUTE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $correo, $codigo_ingresado);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    // Insertar al usuario
    $datos = $_SESSION['registro'];
    $insertar = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo, clave, nombre_restaurante, estado, rol) VALUES (?, ?, ?, ?, 'activo', 'usuario')");
    $insertar->bind_param("ssss", $datos['nombre_usuario'], $datos['correo'], $datos['clave'], $datos['nombre_restaurante']);
    $insertar->execute();

    // Eliminar código de verificación
    $conn->query("DELETE FROM codigos_verificacion WHERE correo = '$correo'");

    echo "¡Registro completado con éxito!";
    session_destroy();
} else {
    echo "Código incorrecto o expirado.";
}
?>
