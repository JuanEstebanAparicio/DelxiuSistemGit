<?php
session_start();
require '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
  die("Acceso no autorizado");
}
$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestor de Órdenes</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../Ver/css/estilos.css">
    <script src="../controlador/js/funcionalidad.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-800">
<header class="top-bar">
    <a href=""></a>
    <a href="../Ver/Inicio.php" class="btn amarillo">Inicio</a>
    <a href="../Ver/gestor_menu.php" class="btn azul">Gestion de menu</a>
    <a href="#" class="btn verde">Inventario</a>
    <a href="../Ver/Ver_inventario.php" class="btn red">Ver inventario</a>
    <a href="../Ver/gestor_ordenes.php" class="btn amarillo">Gestor de ordenes </a>


    <div class="config-container">
      <button class="engranaje" onclick="toggleMenu()">⚙️</button>
      <div class="config-menu" id="configMenu">
       <a href="../css/estilos.css" id="cambiarTema">🌓 Cambiar Tema</a>
       <a href="../Ver/perfil_usuario.php">👤 Perfil</a>
       <a href="../Ver/ver_historial.php">Ver historial</a>
        <a href="../php/logout.php">🚪 Cerrar Sesión</a>
      </div>
    </div>
    
  </header>



  <header class="bg-gray-800 text-white p-4 shadow">
    <h1 class="text-xl font-bold">📋 Gestor de Órdenes</h1>
  </header>

  <main class="p-6">
    <div class="mb-4">
      <button onclick="cargarOrdenes()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        🔄 Actualizar Órdenes
      </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="kanbanOrdenes">
  <div>
    <h2 class="text-lg font-bold mb-2 text-yellow-800">🕒 Pendientes</h2>
    <div id="ordenesPendientes" class="space-y-4"></div>
  </div>
  <div>
    <h2 class="text-lg font-bold mb-2 text-blue-800">🍳 En Preparación</h2>
    <div id="ordenesPreparacion" class="space-y-4"></div>
  </div>
  <div>
    <h2 class="text-lg font-bold mb-2 text-green-800">✅ Listas / Entregadas</h2>
    <div id="ordenesListas" class="space-y-4"></div>
  </div>
</div>

  </main>

<script>

function actualizarEstado(id, nuevoEstado) {
  fetch('../modelo/actualizar_estado_orden.php', {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, estado: nuevoEstado })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      cargarOrdenes(); // Recargar lista
    } else {
      alert("❌ Error al cambiar estado");
    }
  })
  .catch(err => {
    console.error("Error al actualizar estado:", err);
    alert("❌ Error de conexión");
  });
}


function cargarOrdenes() {
  fetch('../modelo/cargar_ordenes.php')
    .then(res => res.json())
    .then(data => {
      const pendientes = document.getElementById("ordenesPendientes");
      const preparacion = document.getElementById("ordenesPreparacion");
      const listas = document.getElementById("ordenesListas");

      pendientes.innerHTML = preparacion.innerHTML = listas.innerHTML = "";

      data.forEach(o => {
        const div = document.createElement("div");
        div.className = "bg-white border rounded shadow p-4";

        const productos = o.detalles.map(p => {
          const precio = parseFloat(p.precio) || 0;
          return `<li>${p.nombre} - $${precio.toFixed(2)}</li>`;
        }).join("");

        // Botones según estado
        let botones = "";
        if (o.estado === 'pendiente') {
          botones += `
            <button onclick="actualizarEstado(${o.id}, 'preparacion')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm">👨‍🍳 Aceptar</button>
            <button onclick="actualizarEstado(${o.id}, 'cancelado')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">❌ Cancelar</button>`;
        } else if (o.estado === 'preparacion') {
          botones += `
            <button onclick="actualizarEstado(${o.id}, 'listo')" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-sm">✅ Marcar Listo</button>`;
        } else if (o.estado === 'listo') {
          botones += `
            <button onclick="actualizarEstado(${o.id}, 'entregado')" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-sm">📦 Entregar</button>`;
        }

        botones += `
          <button onclick="eliminarOrden(${o.id})" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">🗑️ Eliminar</button>`;

        div.innerHTML = `
          <h2 class="text-md font-bold">🧾 Orden #${o.id}</h2>
          <p><strong>Cliente:</strong> ${o.cliente_nombre}</p>
          <p><strong>Tel:</strong> ${o.cliente_contacto}</p>
          <p><strong>Mesa:</strong> ${o.mesa ?? '-'}</p>
          <p><strong>Total:</strong> $${parseFloat(o.total).toFixed(2)} <span class="text-gray-500 text-sm">(Propina: $${parseFloat(o.propina).toFixed(2)})</span></p>
          <ul class="mt-2 list-disc ml-4 text-sm">${productos}</ul>
          <div class="flex flex-wrap gap-2 mt-4">${botones}</div>
        `;

        // Agregar a columna correspondiente
        if (o.estado === 'pendiente') pendientes.appendChild(div);
        else if (o.estado === 'preparacion') preparacion.appendChild(div);
        else listas.appendChild(div);
      });
    });
}


function marcarEntregada(id) {
  if (!confirm("¿Marcar esta orden como entregada?")) return;
  fetch(`../modelo/actualizar_estado_orden.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, estado: "entregada" })
  }).then(() => cargarOrdenes());
}

function eliminarOrden(id) {
  if (!confirm("¿Eliminar esta orden definitivamente?")) return;
  fetch(`../modelo/eliminar_orden.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  }).then(() => cargarOrdenes());
}

document.addEventListener("DOMContentLoaded", cargarOrdenes);
</script>

</body>
</html>