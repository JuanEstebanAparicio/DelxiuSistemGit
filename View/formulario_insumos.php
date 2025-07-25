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
  <title>Agregar Insumos</title>
  <link rel="stylesheet" href="../CSS/insumos.css">
</head>
<body>
  <div class="content">
    <h2>Agregar Insumos</h2>
    <form action="insertar_insumos.php" method="POST">
      <label for="nombre">Nombre (ID Producto):</label>
      <input type="number" id="nombre" name="nombre" required><br>

      <label for="n_lote">Número de Lote:</label>
      <input type="number" id="n_lote" name="n_lote" required><br>

      <label for="cantidad">Cantidad:</label>
      <input type="number" id="cantidad" name="cantidad" required><br>

      <label for="c_por_lote">Cantidad por Lote:</label>
      <input type="number" id="c_por_lote" name="c_por_lote" required><br>

      <label for="f_entrada">Fecha Entrada (YYYYMMDD):</label>
      <input type="number" id="f_entrada" name="f_entrada" required><br>

      <label for="f_caducidad">Fecha Caducidad (YYYYMMDD):</label>
      <input type="number" id="f_caducidad" name="f_caducidad" required><br>

      <button type="submit">Agregar Insumo</button>
    </form>
  </div>
</body>
</html>
