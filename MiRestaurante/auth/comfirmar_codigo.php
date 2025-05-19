<?php
require 'conexion.php';
session_start();

$codigo_ingresado = $_POST['codigo'];
$correo = $_SESSION['registro']['correo'] ?? null;

if (!$correo) {
    echo "Sesión expirada.";
    exit;
}

$sql = "SELECT * FROM codigos_verificacion WHERE correo = ? AND codigo = ? AND fecha_envio >= NOW() - INTERVAL 30 MINUTE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $correo, $codigo_ingresado);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $datos = $_SESSION['registro'];
    $token_menu = bin2hex(random_bytes(16));
    echo "Token generado: " . $token_menu . "<br>"; // <- ⬅️ Aquí mismo
    if (!$token_menu) {
        echo "⚠️ No se pudo generar el token.";
        exit;
    }

    $estado = 'activo';
    $rol = 'usuario';

    $insertar = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo, clave, nombre_restaurante, estado, rol, token_menu) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertar->bind_param("sssssss", 
        $datos['nombre_usuario'], 
        $datos['correo'], 
        $datos['clave'], 
        $datos['nombre_restaurante'], 
        $estado, 
        $rol,
        $token_menu
    );

    if ($insertar->execute()) {
        $conn->query("DELETE FROM codigos_verificacion WHERE correo = '$correo'");
        echo "¡Registro completado con éxito!";
        session_destroy();
    } else {
        echo "❌ Error al insertar usuario: " . $insertar->error;
    }
} else {
    echo "Código incorrecto o expirado.";
}
?>
