<?php
// cambiar_estado.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nuevo_estado = $_POST['estado'];

    $conexion = new mysqli("localhost", "root", "", "restaurante", 3307);

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $stmt = $conexion->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id);
    
    if ($stmt->execute()) {
        header("Location: ../Ver/panel_admin.php"); // Redirige de vuelta al panel
    } else {
        echo "Error al actualizar el estado: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>

