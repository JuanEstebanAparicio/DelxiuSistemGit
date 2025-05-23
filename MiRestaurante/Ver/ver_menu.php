<?php
require '../modelo/conexion.php';

$token = $_GET['token'] ?? null;
if (!$token) die("❌ Token inválido.");

$stmt = $conexion->prepare("SELECT id, nombre_restaurante FROM usuarios WHERE token_menu = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) die("❌ Restaurante no encontrado.");

$usuario = $resultado->fetch_assoc();
$id_usuario = $usuario['id'];
$nombre_restaurante = $usuario['nombre_restaurante'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú - <?= htmlspecialchars($nombre_restaurante) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<header class="bg-gray-800 text-white p-4 shadow">
  <h1 class="text-xl font-bold">Menú de <?= htmlspecialchars($nombre_restaurante) ?></h1>
</header>

<div class="flex">
  <aside class="w-64 bg-gray-900 text-white min-h-screen">
    <h2 class="p-4 font-bold text-lg border-b border-gray-700">Categorías</h2>
    <div id="categoryList" class="space-y-2 p-4 overflow-y-auto max-h-[calc(100vh-80px)]"></div>
  </aside>
  <main class="flex-1 p-6">
    <div id="contenedorPlatillos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
  </main>
</div>

<!-- BOTÓN BANDEJA -->
<button onclick="verBandeja()" class="fixed bottom-6 right-6 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full shadow-lg z-50">
  🛒 Ver Bandeja (<span id="bandejaCount">0</span>)
</button>

<!-- MODAL PLATILLO -->
<div id="modalDetallePlatillo" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg max-w-md w-full p-6 relative shadow-xl">
    <button onclick="cerrarModalDetalle()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-lg">&times;</button>
    <img id="modalImgPlatillo" src="" class="w-full h-40 object-cover rounded mb-4">
    <h2 id="modalNombrePlatillo" class="text-xl font-bold mb-2"></h2>
    <p id="modalDescripcionPlatillo" class="text-gray-600 mb-2"></p>
    <p id="modalPrecioPlatillo" class="text-green-700 font-bold text-lg mb-2"></p>
    <ul id="modalIngredientes" class="text-sm text-gray-700 mb-4 list-disc list-inside"></ul>
    <button id="btnOrdenar" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">Ordenar</button>
  </div>
</div>

<!-- MODAL BANDEJA -->
<div id="modalBandeja" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
  <div class="bg-white rounded shadow-xl p-6 w-full max-w-lg relative">
    <button onclick="cerrarModalBandeja()" class="absolute top-2 right-3 text-lg text-gray-500 hover:text-red-600">&times;</button>
    <h2 class="text-lg font-bold mb-4">🛒 Tu Bandeja</h2>
    <ul id="listaBandeja" class="mb-4 text-sm"></ul>
    <div class="text-right font-bold text-green-700" id="totalBandeja"></div>
    <form id="formOrden" class="space-y-3 mt-4">
  <input type="text" name="nombre_cliente" placeholder="Nombre del cliente" required class="w-full px-3 py-2 border rounded">
  <input type="tel" name="telefono" placeholder="Teléfono" class="w-full px-3 py-2 border rounded">
  <input type="text" name="mesa" placeholder="Mesa (ej: A1, 5)" required class="w-full px-3 py-2 border rounded">

  <input type="number" name="propina" placeholder="Propina (opcional)" step="0.01" class="w-full px-3 py-2 border rounded">

  <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">
    Confirmar Orden
  </button>
</form>

  </div>
  
</div>

<script>
const usuarioId = <?= json_encode($id_usuario) ?>;
let categoriaSeleccionadaId = null;
let carrito = JSON.parse(localStorage.getItem("bandeja")) || [];

function renderPlatillos(platillos) {
  const contenedor = document.getElementById("contenedorPlatillos");
  contenedor.innerHTML = "";

  platillos.forEach(p => {
    const div = document.createElement("div");
    div.className = "bg-white border rounded shadow hover:shadow-md p-2 flex flex-col cursor-pointer";
    div.addEventListener("click", () => mostrarModalPlatillo(p));


    div.innerHTML = `
      <img src="${p.foto || '../uploads/platillos/default.png'}" onerror="this.src='../uploads/platillos/default.png'" class="h-32 object-cover rounded">
      <h3 class="text-center mt-2 font-bold text-sm">${p.nombre}</h3>
      <p class="text-center text-sm text-gray-600">$${p.precio.toFixed(2)}</p>
      ${p.estado === 'agotado' ? `<p class="bg-red-500 text-white text-xs text-center rounded mt-1">NO DISPONIBLE</p>` : ""}
    `;

    contenedor.appendChild(div);
  });
}

function cargarCategorias() {
  fetch(`../modelo/cargar_categorias.php?id_usuario=${usuarioId}`)
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("categoryList");
      contenedor.innerHTML = "";
      data.forEach(cat => {
        const btn = document.createElement("button");
        btn.className = "block w-full px-4 py-2 text-left bg-gray-700 hover:bg-gray-600 rounded";
        btn.textContent = cat.nombre_categoria;
        btn.onclick = () => seleccionarCategoria(cat.id_categoria);
        contenedor.appendChild(btn);
      });
    });
}

function seleccionarCategoria(idCategoria) {
  categoriaSeleccionadaId = idCategoria;
  cargarPlatillos(idCategoria);
}

function cargarPlatillos(idCategoria = null) {
  let url = `../modelo/cargar_platillos.php?id_usuario=${usuarioId}&cliente=1`;
  if (idCategoria) url += `&id_categoria=${idCategoria}`;

  fetch(url)
    .then(res => res.json())
    .then(renderPlatillos);
}

function mostrarModalPlatillo(p) {
  document.getElementById("modalImgPlatillo").src = p.foto;
  document.getElementById("modalNombrePlatillo").textContent = p.nombre;
  document.getElementById("modalDescripcionPlatillo").textContent = p.descripcion || "Sin descripción.";
  document.getElementById("modalPrecioPlatillo").textContent = `$${p.precio.toFixed(2)}`;
  document.getElementById("btnOrdenar").disabled = p.estado === 'agotado';
  document.getElementById("btnOrdenar").onclick = () => agregarABandeja(p);

  const ul = document.getElementById("modalIngredientes");
  ul.innerHTML = "Cargando...";
  fetch(`../modelo/obtener_ingrediente_por_platillo.php?id=${p.id}`)
    .then(res => res.json())
    .then(data => {
      ul.innerHTML = "";
      data.forEach(ing => {
        const li = document.createElement("li");
        li.textContent = `${ing.nombre} (${ing.cantidad_necesaria} ${ing.unidad})`;
        ul.appendChild(li);
      });
    });

  document.getElementById("modalDetallePlatillo").classList.remove("hidden");
}

function cerrarModalDetalle() {
  document.getElementById("modalDetallePlatillo").classList.add("hidden");
}

function agregarABandeja(p) {
  if (p.estado === 'agotado') {
    mostrarToast("🚫 Este platillo está agotado", "bg-red-600");
    return;
  }

  carrito.push(p);
  localStorage.setItem("bandeja", JSON.stringify(carrito));
  actualizarContador();
  cerrarModalDetalle();
  mostrarToast(`✅ ${p.nombre} agregado a la bandeja`, "bg-green-600");
}


function actualizarContador() {
  document.getElementById("bandejaCount").textContent = carrito.length;
}

function verBandeja() {
  const lista = document.getElementById("listaBandeja");
  lista.innerHTML = "";
  let total = 0;

  if (!carrito.length) {
    lista.innerHTML = "<li>🚫 Bandeja vacía</li>";
  } else {
    carrito.forEach((p, index) => {
      const li = document.createElement("li");
      li.className = "flex justify-between items-center py-1";

      li.innerHTML = `
        <span>${p.nombre} - $${p.precio.toFixed(2)}</span>
        <button onclick="eliminarDeBandeja(${index})" class="text-red-500 hover:text-red-700 text-sm font-semibold ml-2">Eliminar</button>
      `;
      lista.appendChild(li);
      total += p.precio;
    });
  }

  document.getElementById("totalBandeja").textContent = `Total: $${total.toFixed(2)}`;
  document.getElementById("modalBandeja").classList.remove("hidden");
}


function cerrarModalBandeja() {
  document.getElementById("modalBandeja").classList.add("hidden");
}
document.getElementById("formOrden").addEventListener("submit", function(e) {
  e.preventDefault();

  if (!carrito.length) {
    mostrarToast("🚫 No hay productos en la bandeja", "bg-red-600");
    return;
  }

  const nombre = this.nombre_cliente.value.trim();
  const telefono = this.telefono.value.trim();
  const propina = parseFloat(this.propina.value) || 0;

  if (nombre === "") {
    mostrarToast("⚠️ Ingrese su nombre", "bg-yellow-600");
    return;
  }

  const mesa = this.mesa.value.trim();

if (!mesa) {
  mostrarToast("⚠️ Indica la mesa", "bg-yellow-600");
  return;
}

fetch("../modelo/guardar_orden.php", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
    usuario_id: usuarioId,
    nombre_cliente: nombre,
    telefono,
    mesa,
    propina,
    carrito
  })
})
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      mostrarToast("✅ Orden enviada correctamente");
      carrito = [];
      localStorage.removeItem("bandeja");
      actualizarContador();
      cerrarModalBandeja();
    } else {
      mostrarToast("❌ Error al enviar orden", "bg-red-600");
    }
  })
  .catch(err => {
    console.error("Error orden:", err);
    mostrarToast("❌ Fallo de conexión", "bg-red-600");
  });
});

document.addEventListener("DOMContentLoaded", () => {
  cargarCategorias();
  cargarPlatillos();
  actualizarContador();
});

function mostrarToast(mensaje, color = "bg-gray-800") {
  const toast = document.getElementById("toast");
  toast.textContent = mensaje;
  toast.className = `fixed bottom-5 right-5 text-white py-2 px-4 rounded shadow-lg z-50 transition-opacity duration-300 ${color}`;
  toast.classList.remove("hidden");
  toast.style.opacity = 1;

  setTimeout(() => {
    toast.style.opacity = 0;
    setTimeout(() => toast.classList.add("hidden"), 300);
  }, 2500);
}
function eliminarDeBandeja(index) {
  const eliminado = carrito.splice(index, 1)[0];
  localStorage.setItem("bandeja", JSON.stringify(carrito));
  mostrarToast(`❌ ${eliminado.nombre} eliminado`, "bg-red-600");
  actualizarContador();
  verBandeja();
}

</script>
<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 bg-gray-800 text-white py-2 px-4 rounded shadow-lg hidden z-50 transition-opacity duration-300"></div>

</body>
</html>


