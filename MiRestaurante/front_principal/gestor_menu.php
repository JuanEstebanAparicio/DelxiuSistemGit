<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de platillos</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="../js/funcionalidad.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
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
            document.getElementById("mensajeExito").classList.remove("hidden");
            cargarCategorias();
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
</body>
</html>
