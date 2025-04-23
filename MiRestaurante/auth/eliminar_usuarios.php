<?php
$conexion = new mysqli("localhost", "root", "", "restaurante",3307);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST['id'] ?? null;

// Validar
if (!$id) {
    echo "ID no recibido.";
    exit;
}

if ($id == 1) {
    echo "No se puede eliminar al administrador.";
    exit;
}

$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
     echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Usuario eliminado correctamente</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f1f1f1;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .alerta {
                background-color: #fff;
                border: 1px solidrgb(26, 37, 197);
                border-left: 5px solidrgb(24, 59, 175);
                padding: 20px 30px;
                border-radius: 8px;
                max-width: 400px;
                text-align: center;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
            .alerta h2 {
                color:rgb(46, 22, 184);
                margin-bottom: 10px;
            }
            .alerta p {
                margin-bottom: 20px;
            }
            .alerta a {
                text-decoration: none;
                background-color:rgb(53, 70, 220);
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .alerta a:hover {
                background-color:rgb(22, 46, 182);
            }
        </style>
    </head>
    <body>
        <div class="alerta">
            <h2>Usuario eliminado correctamente</h2>
            <p>se elimino el usuario.</p>
            <a href=../Ver/panel_admin.php>Volver</a>
        </div>
    </body>
    </html>';
    exit;
} else {
    echo "Error al eliminar usuario.";
}

$stmt->close();
$conexion->close();
?>

