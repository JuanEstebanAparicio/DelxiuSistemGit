<?php
require_once('../Model/Entity/Conexion.php');
$conexion = Conexion::obtenerConexion();
$query = "SELECT * FROM insumos";
$resultado = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Ingredientes</title>
    <link rel="stylesheet" href="../CSS/insumos.css">
      <link rel="stylesheet" href="../CSS/modales.css">
  <link rel="stylesheet" href="../CSS/registroInsumo.css">
  <link rel="stylesheet" href="../CSS/tables.css">
    <style>
    .modal-content {
      max-height: 90vh;
      overflow-y: auto;
    }
  </style>
</head>
<body>
    


  <!-- MODAL PARA REGITRAR LOS INSUMOS  -->
  <div id="formModal" class="modal hidden">
    <div class="modal-content">
      <span class="close" onclick="hideModal('formModal')">&times;</span>
      <h2>Registrar Ingrediente</h2>
      <form action="/Proyecto_de_aula/Controller/almacen/insumos.php" method="POST" onsubmit="return validarFechas()">
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
        <input type="date" name="fecha_ingreso" id="fecha_ingreso" required readonly>

        <label>Fecha de vencimiento:</label>
        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" required>

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

        <button type="submit" class="modal-btn">Registrar Ingrediente</button>
      </form>
    </div>
  </div>
<script src="../JS/animations/modales.js"></script>
<script src="../JS/validar_fechas.js"></script>
<!-- ==========================================================================  -->
  <h2>Gestor de Ingredientes</h2>
<div class="content">
    <button onclick="showModal('formModal')" class="modal-btn">+ Registrar Ingrediente</button>
  </div>
  <!-- TABLA DE INSUMOS   -->

<div class="content">
  

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p class="success-msg">✅ Ingrediente registrado correctamente.</p>
    <?php endif; ?>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultado->fetch()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nombre']; ?></td>
                        <td><?= $row['cantidad']; ?></td>
                        <td><?= $row['unidad']; ?></td>
                        <td><?= $row['estado']; ?></td>
                        <td>
                            <a href="editar_ingrediente.php?id=<?= $row['id']; ?>" class="btn btn-edit">✏️ Editar</a>
                            <a href="eliminar_ingrediente.php?id=<?= $row['id']; ?>" 
                               onclick="return confirm('¿Estás seguro de eliminar este ingrediente?');"
                               class="btn btn-delete">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
    <!-- ==========================================================================  -->
</body>
</html>
