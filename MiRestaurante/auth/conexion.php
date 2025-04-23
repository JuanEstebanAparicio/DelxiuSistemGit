<?php
$conexion = mysqli_connect("localhost", "root", "", "restaurante",3307);

if (!$conexion) {
    die("❌ Error al conectar con la base de datos: " . mysqli_connect_error());
}
?>
