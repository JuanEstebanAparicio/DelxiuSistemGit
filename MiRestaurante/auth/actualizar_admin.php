<?php
$conexion = new mysqli("localhost", "root", "", "restaurante", 3307);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Datos del admin
$correoAdmin = "admin@gmail.com";
$nuevaClave = "admin123";

// Hasheamos la nueva clave
$claveHash = password_hash($nuevaClave, PASSWORD_DEFAULT);

// Verificamos si el admin ya existe
$sqlVerificar = "SELECT * FROM usuarios WHERE correo = ?";
$stmtVerificar = $conexion->prepare($sqlVerificar);
$stmtVerificar->bind_param("s", $correoAdmin);
$stmtVerificar->execute();
$resultado = $stmtVerificar->get_result();

if ($resultado->num_rows > 0) {
    // Si ya existe, actualizamos la contraseña
    $sqlActualizar = "UPDATE usuarios SET clave = ? WHERE correo = ?";
    $stmtActualizar = $conexion->prepare($sqlActualizar);
    $stmtActualizar->bind_param("ss", $claveHash, $correoAdmin);

    if ($stmtActualizar->execute()) {
        echo "✅ Contraseña del admin actualizada exitosamente.";
    } else {
        echo "❌ Error al actualizar la contraseña: " . $stmtActualizar->error;
    }

    $stmtActualizar->close();
} else {
    // Si no existe, lo insertamos
    $nombreUsuario = "admin";
    $nombreRestaurante = "Administrador Principal";
    $estado = "activo";

    $sqlInsertar = "INSERT INTO usuarios (nombre_usuario, clave, nombre_restaurante, correo, estado) VALUES (?, ?, ?, ?, ?)";
    $stmtInsertar = $conexion->prepare($sqlInsertar);
    $stmtInsertar->bind_param("sssss", $nombreUsuario, $claveHash, $nombreRestaurante, $correoAdmin, $estado);

    if ($stmtInsertar->execute()) {
        echo "✅ Admin creado con contraseña encriptada.";
    } else {
        echo "❌ Error al insertar al admin: " . $stmtInsertar->error;
    }

    $stmtInsertar->close();
}

$stmtVerificar->close();
$conexion->close();
?>
