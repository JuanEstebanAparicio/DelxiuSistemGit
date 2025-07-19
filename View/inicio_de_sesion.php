<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido al Restaurante</title>
    <link rel="stylesheet" href="../CSS/inicio.css">
</head>
<body>

    <h1>Bienvenido al Restaurante DelixiuSystem</h1>
    <p>Por favor, selecciona una opción para continuar:</p>

    <div class="botones">
        <a href="login.php"><button class="boton">Iniciar Sesión</button></a>
        <a href="registro.php"><button class="boton">Registrarse</button></a>
        <a href="menu.php"><button class="boton">Ver Menú</button></a>
    </div>

</body>
</html>
