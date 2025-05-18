<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú del Restaurante</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<header class="top-bar bg-blue-600 text-white p-4">
  <div class="max-w-6xl mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Menú del Restaurante</h1>
    <a href="Inicio.php" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-blue-100">Volver al inicio</a>
  </div>
</header>

<div class="flex h-screen">
  <aside class="w-64 bg-gray-800 text-white p-4">
    <h2 class="text-xl font-semibold mb-4">Categorías</h2>
    <div id="categoryList" class="space-y-2 overflow-y-auto max-h-[calc(100vh-6rem)]"></div>
  </aside>

  <main class="flex-1 p-6">
    <div id="contenedorPlatillos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"></div>
  </main>
</div>

<script>
let categoriaSeleccionadaId = null;

function cargarCategorias() {
  fetch('../back_principal/cargar_categorias.php')
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById("categoryList");
      container.innerHTML = "";
      data.forEach(cat => {
        const btn = document.createElement("button");
        btn.className = "block w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg";
        btn.textContent = cat.nombre_categoria;
        btn.onclick = () => seleccionarCategoria(cat.id_categoria);

        if (cat.id_categoria == categoriaSeleccionadaId) {
          btn.classList.add("bg-blue-500");
        }

        container.appendChild(btn);
      });
    });
}

function seleccionarCategoria(idCategoria) {
  categoriaSeleccionadaId = idCategoria;
  cargarPlatillos(idCategoria);
}

function cargarPlatillos(idCategoria = null) {
  let url = "../back_principal/cargar_platillos.php";
  if (idCategoria) url += `?id_categoria=${idCategoria}`;

  fetch(url)
    .then(res => res.json())
    .then(data => renderPlatillos(data));
}

function renderPlatillos(platillos) {
  const contenedor = document.getElementById("contenedorPlatillos");
  contenedor.innerHTML = "";

  platillos.forEach(platillo => {
    const div = document.createElement("div");
    div.className = "bg-white rounded-lg shadow p-4 flex flex-col justify-between";

    const imgSrc = platillo.foto?.trim()
      ? platillo.foto
      : "../uploads/platillos/default.png";

    div.innerHTML = `
      <img src="${imgSrc}" onerror="this.src='../uploads/platillos/default.png';" class="w-full h-32 object-cover rounded mb-4">
      <h3 class="text-lg font-semibold">${platillo.nombre}</h3>
      <p class="text-gray-600 mb-2">$${platillo.precio.toFixed(2)}</p>
      <p class="text-sm text-gray-500">${platillo.descripcion}</p>
    `;

    contenedor.appendChild(div);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  cargarCategorias();
  cargarPlatillos();
});
</script>

</body>
</html>
