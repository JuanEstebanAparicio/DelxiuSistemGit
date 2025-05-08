<?php
// mostrar_imagen.php
include '../auth/conexion.php';

if (!isset($_GET['id_Ingrediente'])) {
    http_response_code(400);
    exit('ID no proporcionado');
}

$id = $_GET['id_Ingrediente'];

$stmt = $pdo->prepare("SELECT foto FROM inventario WHERE id_Ingrediente = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row && $row['foto']) {
    header("Content-Type: image/jpeg"); // o png según el formato
    echo $row['foto'];
} else {
    http_response_code(404);
    echo 'Imagen no encontrada';
}
?>
