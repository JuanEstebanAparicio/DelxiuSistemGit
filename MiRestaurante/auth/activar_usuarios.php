
<?php
$conexion = new mysqli("localhost", "root", "", "restaurante");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST['id'];

$sql = "UPDATE usuarios SET estado = 'activo' WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Usuario activado correctamente.";
} else {
    echo "Error al activar usuario.";
}
$stmt->close();
$conexion->close();
?>
