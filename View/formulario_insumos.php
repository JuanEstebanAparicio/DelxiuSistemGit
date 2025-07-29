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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Ingrediente</title>
  <link rel="stylesheet" href="../CSS/insumos.css">
</head>
<body>
  <div class="content">
    <h2>Registrar Ingrediente</h2>
    <form action="../Controller/almacen/insumos.php" method="POST" enctype="multipart/form-data">
      <label>Nombre del ingrediente:</label>
      <input type="text" name="nombre" placeholder="Ej: Tomate" required>

      <label>Cantidad:</label>
      <input type="number" name="cantidad" required>

      <label>Cantidad mínima:</label>
      <input type="number" name="cantidad_minima" required>

      <label>Unidad de medida:</label>
      <select name="unidad" required>
        <option value="">Seleccione unidad</option>
        <option value="Kg">Kg</option>
        <option value="Litro">Litro</option>
        <option value="Unidad">Unidad</option>
      </select>

      <label>Costo unitario:</label>
      <input type="number" step="0.01" name="costo_unitario" required>

      <label>Categoría:</label>
      <select name="categoria" required>
        <option value="">Seleccione categoría</option>
        <option value="Vegetal">Vegetal</option>
        <option value="Carne">Carne</option>
        <option value="Bebida">Bebida</option>
        <option value="Otro">Otro</option>
      </select>

      <label>Fecha de ingreso:</label>
      <input type="date" name="fecha_ingreso" required>

      <label>Fecha de vencimiento:</label>
      <input type="date" name="fecha_vencimiento">

      <label>Lote:</label>
      <input type="text" name="lote" required>

      <label>Descripción:</label>
      <textarea name="descripcion" rows="3"></textarea>

      <label>Ubicación en almacén:</label>
      <input type="text" name="ubicacion" required>

      <label>Estado:</label>
      <select name="estado" required>
        <option value="Activo">Activo</option>
        <option value="Agotado">Agotado</option>
      </select>

      <label>Proveedor:</label>
      <input type="text" name="proveedor" required>

      <label>Foto del ingrediente:</label>
      <input type="file" name="foto" accept="image/*">

      <button type="submit">Registrar Ingrediente</button>
    </form>
  </div>
</body>
</html>
