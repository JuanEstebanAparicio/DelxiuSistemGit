<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Ver/Login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "restaurante", 3307);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];
$nombre = $_POST['nombre'];
$categoria = $_POST['categoria'];
$cantidad = $_POST['cantidad'];
$unidad = $_POST['unidad_medida'];
$fecha_compra = $_POST['fecha_compra'] ?: null;
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?: null;
$proveedor = $_POST['proveedor'];
$observaciones = $_POST['observaciones'];

$sql = "INSERT INTO inventario (id_usuario, nombre, categoria, cantidad, unidad_medida, fecha_compra, fecha_vencimiento, proveedor, observaciones)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issdsssss", $id_usuario, $nombre, $categoria, $cantidad, $unidad, $fecha_compra, $fecha_vencimiento, $proveedor, $observaciones);

if ($stmt->execute()) {
    header("Location: ../inventario/registro_ingrediente.php?msg=ok");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
