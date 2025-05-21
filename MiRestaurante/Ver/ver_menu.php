<?php
require '../modelo/conexion.php';

$token = $_GET['token'] ?? null;

if (!$token) {
    die("❌ Token inválido.");
}

// Buscar restaurante por token
$stmt = $conexion->prepare("SELECT id, nombre_restaurante FROM usuarios WHERE token_menu = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("❌ Restaurante no encontrado.");
}

$usuario = $resultado->fetch_assoc();
$id_usuario = $usuario['id'];
$nombre_restaurante = $usuario['nombre_restaurante'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú de <?= htmlspecialchars($nombre_restaurante) ?></title>
  <link rel="stylesheet" href="../css/estilos.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <header class="top-bar bg-gray-800 text-white px-6 py-4 shadow-md">
    <h1 class="text-2xl font-bold">Bienvenido a <?= htmlspecialchars($nombre_restaurante) ?></h1>
  </header>

  <div class="flex h-screen">
    <aside class="w-64 bg-gray-900 text-white">
      <div class="px-4 py-3 border-b border-gray-700">
        <h2 class="text-lg font-semibold">Categorías</h2>
      </div>
      <div id="categoryList" class="p-2 space-y-2 overflow-y-auto max-h-[calc(100vh-4rem)]"></div>
    </aside>

    <main class="flex-1 p-4 overflow-y-auto">
      <div id="contenedorPlatillos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
    </main>
  </div>

  <script>
    const usuarioId = <?= json_encode($id_usuario) ?>;
    let categoriaSeleccionadaId = null;

    function renderPlatillos(platillos) {
      const contenedor = document.getElementById("contenedorPlatillos");
      contenedor.innerHTML = "";

      platillos.forEach(platillo => {
        const div = document.createElement("div");
        div.className = "aspect-square w-40 bg-white rounded shadow p-2 border flex flex-col justify-between";

        div.innerHTML = `
          <img src="${platillo.foto && platillo.foto.trim() !== '' ? platillo.foto : '../uploads/platillos/default.png'}"
               onerror="this.onerror=null;this.src='../uploads/platillos/default.png';"
               class="w-full h-24 object-cover rounded">
          <div class="text-center mt-2">
            <h3 class="font-semibold text-sm">${platillo.nombre}</h3>
            <p class="text-gray-600 text-sm">$${parseFloat(platillo.precio).toFixed(2)}</p>
          </div>
        `;

        contenedor.appendChild(div);
      });
    }

    function seleccionarCategoria(idCategoria) {
      categoriaSeleccionadaId = idCategoria;
      document.querySelectorAll('#categoryList button').forEach(btn => {
        btn.classList.remove("bg-blue-600");
        btn.classList.add("bg-gray-700");
      });

      const btn = document.querySelector(`#categoryList button[data-id='${idCategoria}']`);
      if (btn) {
        btn.classList.remove("bg-gray-700");
        btn.classList.add("bg-blue-600");
      }

      cargarPlatillos(idCategoria);
    }

    function cargarCategorias() {
      fetch(`../modelo/cargar_categorias.php?id_usuario=${usuarioId}`)
        .then(res => res.json())
        .then(data => {
          const categoryList = document.getElementById("categoryList");
          categoryList.innerHTML = "";

          data.forEach(cat => {
            const btn = document.createElement("button");
            btn.className = "w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white font-medium";
            btn.textContent = cat.nombre_categoria;
            btn.dataset.id = cat.id_categoria;
            btn.onclick = () => seleccionarCategoria(cat.id_categoria);
            categoryList.appendChild(btn);
          });
const btnCombo = document.createElement("button");
btnCombo.textContent = "Combos 🧃";
btnCombo.className = "w-full text-left px-4 py-2 bg-purple-700 hover:bg-purple-600 rounded-lg text-white font-medium mt-2";
btnCombo.onclick = cargarCombosCliente;
categoryList.appendChild(btnCombo);
        });
      }

function cargarCombosCliente() {
  fetch(`../modelo/cargar_combos.php?id_usuario=${usuarioId}`)
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("contenedorPlatillos");
      contenedor.innerHTML = "";

      if (!data.length) {
        contenedor.innerHTML = "<p class='text-gray-600'>No hay combos disponibles.</p>";
        return;
      }

      data.forEach(combo => {
        const div = document.createElement("div");
        div.className = "bg-white p-4 border rounded shadow";

        div.innerHTML = `
          <h3 class="text-lg font-bold text-purple-800">${combo.nombre_combo}</h3>
          <p class="text-gray-600">${combo.descripcion || "Sin descripción"}</p>
          <p class="text-green-700 font-bold mt-2">$${parseFloat(combo.precio_combo).toFixed(2)}</p>
        `;

        contenedor.appendChild(div);
      });
    })
    .catch(err => {
      console.error("Error cargando combos:", err);
    });
}



    function cargarPlatillos(idCategoria = null) {
      let url = `../modelo/cargar_platillos.php?id_usuario=${usuarioId}`;
      if (idCategoria) url += `&id_categoria=${idCategoria}`;

      fetch(url)
        .then(res => res.json())
        .then(data => renderPlatillos(data));
    }

    document.addEventListener("DOMContentLoaded", () => {
      cargarCategorias();
      cargarPlatillos();
    });
  </script>
</body>
</html>
