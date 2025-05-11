<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

include '../auth/conexion.php';
$id_usuario = $_SESSION['id_usuario'];

// Eliminar múltiple
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_seleccionados'])) {
  if (!empty($_POST['seleccion'])) {
    $ids = implode(',', array_map('intval', $_POST['seleccion']));
    $conexion->query("DELETE FROM historial_inventario WHERE id IN ($ids) AND usuario_id = $id_usuario");
  }
  header("Location: historial.php");
  exit();
}

$resultado = $conexion->query("SELECT id, nombre, cantidad, lote, fecha_vencimiento, accion, fecha_registro FROM historial_inventario WHERE usuario_id = $id_usuario ORDER BY fecha_registro DESC");
?>




<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/estilos.css">
  <script src="../js/funcionalidad.js" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <title>historial de inventario</title>
<style>
#tablaHistorial .btn {
  padding: 6px 12px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  margin: 0 2px;
}

#tablaHistorial .btn-delete {
  background-color: #dc3545;
  color: white;
}

#tablaHistorial .btn-delete:hover {
  background-color: #a71d2a;
}
</style>
</head>
<body>
    <header class="top-bar">
  <a href=""></a>
  <a href="../Ver/Inicio.php" class="btn amarillo">Inicio</a>
  <a href="#" class="btn azul">Próximamente</a>
  <a href="../front_principal/registro_ingrediente.php" class="btn verde">Inventario</a>
  <a href="../front_principal/Ver_inventario.php" class="btn red">Ver inventario</a>
  <div class="config-container">
    <button class="engranaje" onclick="toggleMenu()">⚙️</button>
    <div class="config-menu" id="configMenu">
      <a href=""></a>
      <a href="../css/estilos.css" id="cambiarTema">🌓 Cambiar Tema</a>
      <a href="../front_principal/ver_historial.php">Ver historial</a>
      <a href="../php/logout.php">🚪 Cerrar Sesión</a>
    </div>
  </div>
</header>
<h2 class="mb-4">📜 Historial de Movimientos</h2>
<form action="../back_principal/eliminar_historial.php" method="post" id="formEliminar">
  <button type="submit" class="btn btn-danger" onclick="return confirmarEliminacion()">🗑️ Eliminar seleccionados</button>
  <br><br>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th><input type="checkbox" id="seleccionarTodo"></th>
        <th>Nombre</th>
        <th>Cantidad</th>
        <th>Lote</th>
        <th>Fecha vencimiento</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
          <td><input type="checkbox" name="id[]" value="<?= $row['id'] ?>"></td>
          <td><?= $row['nombre'] ?></td>
          <td><?= $row['cantidad'] ?></td>
          <td><?= $row['lote'] ?></td>
          <td><?= $row['fecha_vencimiento'] ?></td>
          <td><?= $row['accion'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkAll = document.getElementById('seleccionarTodo');
  checkAll.addEventListener('change', function () {
    document.querySelectorAll('input[name="id[]"]').forEach(cb => cb.checked = this.checked);
  });
});

function confirmarEliminacion() {
  return confirm('¿Estás seguro de que deseas eliminar los registros seleccionados del historial?');
}
</script>

</body>
</html>