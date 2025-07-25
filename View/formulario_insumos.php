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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurante</title>
  <link rel="stylesheet" href="../CSS/insumos.css">
</head>
<body>
  <canvas id="bgCanvas"></canvas>
  <div class="overlay"></div>
  <div class="content">

  </div>

</body>
</html>
