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
    <a href="../front_principal/gestor_menu.php" class="btn azul">Gestion de menu</a>
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
<script>
function mostrarNotificaciones() {
  fetch("../back_principal/notificaciones_ingredientes.php")
    .then(res => res.json())
    .then(data => {
      if (!data.avisos || data.avisos.length === 0) return;

      const host = document.createElement("div");
      const shadow = host.attachShadow({ mode: "open" });
      document.body.appendChild(host);

      shadow.innerHTML = `
        <style>
          .contenedor {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 999999;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            font-family: Arial, sans-serif;
          }

          .noti {
            background-color: #fff;
            border-left: 5px solid;
            border-radius: 0.5rem;
            padding: 1rem;
            padding-right: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            animation: slide-in 0.4s ease-out;
            position: relative;
            font-size: 14px;
            color: #1f2937;
            max-width: 300px;
          }

          .noti[data-tipo="error"] { border-color: #dc2626; background-color: #fee2e2; }
          .noti[data-tipo="warning"] { border-color: #ca8a04; background-color: #fef3c7; }
          .noti[data-tipo="info"] { border-color: #2563eb; background-color: #dbeafe; }

          .cerrar {
            position: absolute;
            top: 4px;
            right: 8px;
            background: none;
            border: none;
            font-size: 18px;
            color: #555;
            cursor: pointer;
          }

          .icono {
            font-size: 20px;
            margin-right: 0.5rem;
          }

          @keyframes slide-in {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
          }
        </style>
        <div class="contenedor" id="notiCont"></div>
      `;

      const contenedor = shadow.getElementById("notiCont");

      data.avisos.forEach(msg => {
        let tipo = "info";
        if (msg.includes("vencido")) tipo = "error";
        else if (msg.includes("bajo")) tipo = "warning";

        const icon = tipo === "error" ? "❌" : tipo === "warning" ? "⚠️" : "ℹ️";

        const noti = document.createElement("div");
        noti.className = "noti";
        noti.dataset.tipo = tipo;
        noti.innerHTML = `
          <button class="cerrar" onclick="this.parentNode.remove()">×</button>
          <div><span class="icono">${icon}</span>${msg}</div>
        `;

        contenedor.appendChild(noti);
      });

      setTimeout(() => host.remove(), 15000);
    })
    .catch(err => {
      console.error("⚠️ Error cargando notificaciones:", err);
    });
}
document.addEventListener("DOMContentLoaded", mostrarNotificaciones);
</script>

</body>
</html>