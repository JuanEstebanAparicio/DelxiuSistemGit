<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de platillos</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="../js/funcionalidad.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>.bg-blue-600 {
  background-color: #2563eb !important;
  font-weight: bold;
}</style>
<body>
  <header class="top-bar">
    <a href=""></a>
    <a href="../Ver/Inicio.php" class="btn amarillo">Inicio</a>
    <a href="#" class="btn azul">Gestion de menu</a>
    <a href="../front_principal/registro_ingrediente.php" class="btn verde">Inventario</a>
    <a href="../front_principal/Ver_inventario.php" class="btn red">Ver inventario</a>
    <div class="config-container">
      <button class="engranaje" onclick="toggleMenu()">⚙️</button>
      <div class="config-menu" id="configMenu">
        <a href=""></a>
        <a href="../css/estilos.css" id="cambiarTema">🌓 Cambiar Tema</a>
        <a href="../front_principal/ver_historial.php">⏳historial de inventario</a>
        <a href="../php/logout.php">🚪 Cerrar Sesión</a>
      </div>
    </div>
  </header> 
  <div class="flex h-screen">
    <div id="sidebar" class="w-64 bg-gray-800 text-white transition-all duration-300">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-700">
        <h2 class="text-lg font-semibold">Categorías</h2>
        <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-lg text-xl">+</button>
      </div>
      <div id="categoryList" class="p-2 space-y-2 overflow-y-auto max-h-[calc(100vh-4rem)]"></div>
    </div>

    <div class="flex-1 p-4">
      <button onclick="toggleSidebar()" class="mb-4 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Toggle Categorías</button>
      <div id="mainContent"></div>
<div id="contenedorPlatillos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
</div>



<script>
let categoriaSeleccionada = null;

function renderPlatillos(platillos) {
  const contenedor = document.getElementById("contenedorPlatillos");
  contenedor.innerHTML = "";

  platillos.forEach(platillo => {
    const div = document.createElement("div");
    div.className = "aspect-square w-40 bg-white rounded shadow flex flex-col items-center justify-between p-2 border";
    div.innerHTML = `
      <img src="${platillo.foto || '../img/default.png'}" class="w-full h-24 object-cover rounded">
      <div class="text-center">
        <h3 class="font-semibold text-sm">${platillo.nombre}</h3>
        <p class="text-gray-600 text-sm">$${platillo.precio.toFixed(2)}</p>
      </div>
    `;
    contenedor.appendChild(div);
  });

// Botón + como tarjeta cuadrada al final del catálogo
const btnCrear = document.createElement("div");
btnCrear.className = "aspect-square w-40";
btnCrear.innerHTML = `
  <button onclick="abrirModalPlatillo()" 
    <button onclick="abrirModalPlatillo()" class="w-full h-full border-2 border-dashed border-gray-400 flex items-center justify-center text-4xl text-gray-500 hover:bg-gray-100 rounded">
    +
  </button>
`;
contenedor.appendChild(btnCrear);

}

function cargarPlatillos(idCategoria = null) {
  categoriaSeleccionada = idCategoria;
  let url = "../back_principal/cargar_platillos.php";
  if (idCategoria) url += `?id_categoria=${idCategoria}`;

  fetch(url)
    .then(res => res.json())
    .then(data => renderPlatillos(data));
}

function cargarCategorias() {
  fetch('../back_principal/cargar_categorias.php')
    .then(response => response.json())
    .then(data => {
      const categoryList = document.getElementById("categoryList");
      categoryList.innerHTML = "";
      data.forEach(cat => {
        const wrapper = document.createElement("div");
        wrapper.className = "relative group";

        const btn = document.createElement("button");
        btn.className = "w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors text-white font-medium";
        btn.textContent = cat.nombre_categoria;
        btn.dataset.id = cat.id_categoria;
        btn.onclick = () => {
  seleccionarCategoria(cat.id_categoria);
};

if (cat.id_categoria == categoriaSeleccionadaId) {
  btn.classList.remove("bg-gray-700");
  btn.classList.add("bg-blue-600");
}



        const editBtn = document.createElement("button");
        editBtn.className = "absolute top-1 right-8 text-yellow-300 opacity-0 group-hover:opacity-100";
        editBtn.innerHTML = "✏️";
        editBtn.onclick = () => openModal(true, cat.id_categoria, cat.nombre_categoria);

        const deleteBtn = document.createElement("button");
        deleteBtn.className = "absolute top-1 right-2 text-red-400 opacity-0 group-hover:opacity-100";
        deleteBtn.innerHTML = "🗑️";
        deleteBtn.onclick = () => eliminarCategoria(cat.id_categoria);

        wrapper.appendChild(btn);
        wrapper.appendChild(editBtn);
        wrapper.appendChild(deleteBtn);
        categoryList.appendChild(wrapper);
      });
    });
}
function seleccionarCategoria(idCategoria) {
  categoriaSeleccionadaId = idCategoria;

  document.querySelectorAll('#categoryList button').forEach(b => {
    b.classList.remove("bg-blue-600");
    b.classList.add("bg-gray-700");
  });

  const boton = document.querySelector(`#categoryList button[data-id='${idCategoria}']`);
  if (boton) {
    boton.classList.remove("bg-gray-700");
    boton.classList.add("bg-blue-600");
  }

  cargarPlatillos(idCategoria);
}


document.addEventListener("DOMContentLoaded", () => {
  cargarCategorias();
  cargarPlatillos();
});
</script>




    </div>
  </div>

  <!-- Modal para crear/editar categoría -->
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-80 shadow-lg">
      <h2 class="text-lg font-semibold mb-4" id="modalTitulo">Nueva Categoría</h2>
      <form id="formCategoria">
        <input type="text" id="nuevaCategoria" class="w-full p-2 border border-gray-300 rounded mb-4" placeholder="Nombre de la categoría" required>
        <input type="hidden" id="categoriaEditId">
        <div class="flex justify-end space-x-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Guardar</button>
        </div>
        <p id="mensajeExito" class="text-green-600 text-sm mt-2 hidden">✅ Categoría guardada con éxito</p>
      </form>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("-ml-64");
    }

    function openModal(edit = false, id = null, nombre = "") {
      document.getElementById("modal").classList.remove("hidden");
      document.getElementById("mensajeExito").classList.add("hidden");
      document.getElementById("modalTitulo").textContent = edit ? "Editar Categoría" : "Nueva Categoría";
      document.getElementById("nuevaCategoria").value = nombre;
      document.getElementById("categoriaEditId").value = edit ? id : "";
    }

    function closeModal() {
      document.getElementById("modal").classList.add("hidden");
      document.getElementById("mensajeExito").classList.add("hidden");
    }

    function cargarCategorias() {
      fetch('../back_principal/cargar_categorias.php')
        .then(response => response.json())
        .then(data => {
          const categoryList = document.getElementById("categoryList");
          categoryList.innerHTML = "";
          data.forEach(cat => {
            const wrapper = document.createElement("div");
            wrapper.className = "relative group";

            const btn = document.createElement("button");
            btn.className = "w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors text-white font-medium";
            btn.textContent = cat.nombre_categoria;
            btn.dataset.id = cat.id_categoria; // 👈 Esto es crucial para poder seleccionar después
             btn.onclick = () => seleccionarCategoria(cat.id_categoria); // 👈 Esto reemplaza cualquier onclick previo

            const editBtn = document.createElement("button");
            editBtn.className = "absolute top-1 right-8 text-yellow-300 opacity-0 group-hover:opacity-100";
            editBtn.innerHTML = "✏️";
            editBtn.onclick = () => openModal(true, cat.id_categoria, cat.nombre_categoria);

            const deleteBtn = document.createElement("button");
            deleteBtn.className = "absolute top-1 right-2 text-red-400 opacity-0 group-hover:opacity-100";
            deleteBtn.innerHTML = "🗑️";
            deleteBtn.onclick = () => eliminarCategoria(cat.id_categoria);

            wrapper.appendChild(btn);
            wrapper.appendChild(editBtn);
            wrapper.appendChild(deleteBtn);
            categoryList.appendChild(wrapper);
          });
        });
    }

    function eliminarCategoria(id) {
      if (!confirm("¿Eliminar esta categoría?")) return;

      const formData = new FormData();
      formData.append("id_categoria", id);

      fetch("../back_principal/Eliminar_categoria.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(response => {
        if (response.success) cargarCategorias();
        else alert("Error al eliminar: " + (response.error || "Error desconocido"));
      });
    }

    document.getElementById("formCategoria").addEventListener("submit", function(e) {
      e.preventDefault();
      const nombre = document.getElementById("nuevaCategoria").value.trim();
      const id = document.getElementById("categoriaEditId").value;
      if (nombre) {
        const formData = new FormData();
        formData.append("nombre_categoria", nombre);
        if (id) formData.append("id_categoria", id);

        fetch("../back_principal/guardar_categoria.php", {
          method: "POST",
          body: formData
        })
        .then(res => res.json())
        .then(response => {
          if (response.success) {
  categoriaSeleccionadaId = response.id_categoria;
  document.getElementById("mensajeExito").classList.remove("hidden");

  cargarCategorias(); // 👈 repintará la lista, y el botón seleccionado quedará activo

  setTimeout(() => {
    document.getElementById("mensajeExito").classList.add("hidden");
    closeModal();
    document.getElementById("nuevaCategoria").value = "";
  }, 1500);
} else {
  alert("❌ Error: " + (response.error || "Error desconocido"));
}
        });
      }
    });

    cargarCategorias();
  </script>

<!-- Modal para crear platillo -->
<div id="modalPlatillo" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 overflow-y-auto">
  <div class="bg-white p-6 rounded-lg w-full max-w-2xl shadow-xl">
    <h2 class="text-xl font-bold mb-4">Crear Nuevo Platillo</h2>
    <form id="formPlatillo" enctype="multipart/form-data">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 font-medium">Nombre:</label>
          <input type="text" name="nombre" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div>
          <label class="block mb-1 font-medium">Precio ($):</label>
          <input type="number" step="0.01" name="precio" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div>
          <label class="block mb-1 font-medium">Categoría:</label>
          <select name="id_categoria" id="selectCategoria" class="w-full p-2 border border-gray-300 rounded" required></select>
        </div>
        <div>
          <label class="block mb-1 font-medium">Tiempo preparación (min):</label>
          <input type="number" name="tiempo_preparacion" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="col-span-2">
          <label class="block mb-1 font-medium">Descripción:</label>
          <textarea name="descripcion" rows="3" class="w-full p-2 border border-gray-300 rounded"></textarea>
        </div>
        <div class="col-span-2">
          <label class="block mb-1 font-medium">Foto:</label>
          <input type="file" name="foto" id="inputFoto" accept="image/*" class="w-full">
          <div class="mt-2">
            <img id="previewImagen" src="#" alt="Vista previa" class="w-32 h-32 object-cover rounded border border-gray-300 shadow hidden" />
          </div>
        </div>
      </div>

      <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Ingredientes Disponibles</h3>
        <div id="listaIngredientes" class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border p-2 rounded border-gray-300"></div>
      </div>

      <div class="flex justify-end mt-6 space-x-2">
        <button type="button" onclick="cerrarModalPlatillo()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded">Crear Platillo</button>
      </div>
    </form>
  </div>
</div>

<script>
let categoriaSeleccionadaId = null;

function abrirModalPlatillo() {
  document.getElementById("modalPlatillo").classList.remove("hidden");
  cargarIngredientesYcategorias();
}

function cerrarModalPlatillo() {
  document.getElementById("modalPlatillo").classList.add("hidden");
}

function cargarIngredientesYcategorias() {
  fetch('../back_principal/cargar_ingredientes_categorias.php')
    .then(res => res.json())
    .then(data => {
      const catSelect = document.getElementById("selectCategoria");
      catSelect.innerHTML = "";
      data.categorias.forEach(cat => {
        const option = document.createElement("option");
        option.value = cat.id_categoria;
        option.textContent = cat.nombre_categoria;
        if (cat.id_categoria == categoriaSeleccionadaId) option.selected = true;
        catSelect.appendChild(option);
      });

      const lista = document.getElementById("listaIngredientes");
      lista.innerHTML = "";
      data.ingredientes.forEach(ing => {
        const div = document.createElement("div");
        div.className = "flex items-center space-x-2";
        div.innerHTML = `
          <input type="checkbox" id="ing_${ing.id_Ingrediente}" value="${ing.id_Ingrediente}" class="checkboxIng">
          <label for="ing_${ing.id_Ingrediente}" class="flex-1">${ing.nombre} (${ing.unidad_medida})</label>
          <input type="number" step="0.01" min="0" placeholder="Cantidad" class="inputCantidad border rounded px-2 py-1 w-24" data-id="${ing.id_Ingrediente}">
        `;
        lista.appendChild(div);
      });
    });
}

document.getElementById("formPlatillo").addEventListener("submit", function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  const ingredientes = [];
  document.querySelectorAll(".checkboxIng:checked").forEach(chk => {
    const id = chk.value;
    const cantidadInput = document.querySelector(`.inputCantidad[data-id='${id}']`);
    const cantidad = parseFloat(cantidadInput.value || 0);
    if (cantidad > 0) ingredientes.push({ id, cantidad });
  });

  formData.append("ingredientes", JSON.stringify(ingredientes));

  fetch("../back_principal/guardar_platillo.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(response => {
    if (response.success) {
      alert("✅ Platillo creado exitosamente");
      cerrarModalPlatillo();
      form.reset();
      cargarPlatillosPorCategoria(categoriaSeleccionadaId);
    } else {
      alert("❌ Error al guardar: " + (response.error || "Error desconocido"));
    }
  })
  .catch(error => {
    console.error("Error al guardar platillo:", error);
    alert("❌ Error en la conexión con el servidor.");
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const inputFoto = document.getElementById("inputFoto");
  const preview = document.getElementById("previewImagen");

  if (inputFoto) {
    inputFoto.addEventListener("change", function () {
      const archivo = this.files[0];
      if (archivo) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.classList.remove("hidden");
        };
        reader.readAsDataURL(archivo);
      } else {
        preview.src = "#";
        preview.classList.add("hidden");
      }
    });
  }
});
</script>



</body>
</html>
