<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de platillos</title>
    <link rel="stylesheet" href="../Ver/css/estilos.css">
    <script src="../controlador/js/funcionalidad.js" defer></script>
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
        <a href="../Ver/registro_ingrediente.php" class="btn verde">Inventario</a>
        <a href="../Ver/Ver_inventario.php" class="btn red">Ver inventario</a>
        <a href="../Ver/gestor_ordenes.php" class="btn amarillo">Gestor de ordenes </a>
    <div class="config-container">
      <button class="engranaje" onclick="toggleMenu()">⚙️</button>
      <div class="config-menu" id="configMenu">
        <a href=""></a>
        <a href="../css/estilos.css" id="cambiarTema">🌓 Cambiar Tema</a>
        <a href="../Ver/perfil_usuario.php">👤 Perfil</a>
        <a href="../controlador/php/logout.php">🚪 Cerrar Sesión</a>

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
      <button onclick="abrirModalCombo()" class="mb-4 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">+ Nuevo Combo</button>
      <div id="mainContent"></div>
<div id="contenedorPlatillos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">


</div>



<script>
  // Obtener usuarioId desde PHP si no existe aún
const usuarioId = <?= json_encode($_SESSION['id_usuario'] ?? 0) ?>;
function mostrarCombos() {
  fetch("../modelo/cargar_combos.php?t=" + new Date().getTime())
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("contenedorPlatillos");
      contenedor.innerHTML = "";

      data.forEach(combo => {
        const card = document.createElement("div");
        card.className = "p-4 bg-white rounded shadow border";

        card.innerHTML = `
          <h3 class="font-bold text-lg">${combo.nombre_combo}</h3>
          <p class="text-sm text-gray-600">${combo.descripcion || "Sin descripción"}</p>
          <p class="text-green-700 font-bold mt-2">$${combo.precio_combo.toFixed(2)}</p>
          <span class="text-xs px-2 py-1 rounded-full ${
  combo.estado === 'activo'
    ? 'bg-green-100 text-green-700'
    : combo.estado === 'agotado'
      ? 'bg-yellow-100 text-yellow-800'
      : 'bg-red-100 text-red-700'
}">${combo.estado}</span>

          <div class="mt-2 flex space-x-2">
            <button onclick="editarCombo(${combo.id})" class="text-yellow-500 hover:text-yellow-700">✏️</button>
            <button onclick="eliminarCombo(${combo.id})" class="text-red-500 hover:text-red-700">🗑️</button>
          </div>
        `;

        contenedor.appendChild(card);
      });
    })
    .catch(err => {
      console.error("Error al cargar combos:", err);
      alert("❌ Error cargando combos.");
    });
}






function editarCombo(id) {
  fetch(`../modelo/obtener_combo.php?id=${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert("❌ Error: " + data.error);
        return;
      }

      abrirModalCombo();
console.log("Datos del combo:", data);
      
      // Cargar datos al formulario
      document.getElementById("editIdCombo").value = data.id;
      document.querySelector("[name='nombre_combo']").value = data.nombre_combo;
      document.querySelector("[name='descripcion']").value = data.descripcion;
      document.querySelector("[name='precio_combo']").value = data.precio_combo;

      // Estado editable solo si es activo o inactivo
      const estado = data.estado;
      const estadoSelect = document.querySelector("[name='estado']");
      estadoSelect.value = ['activo', 'inactivo'].includes(estado) ? estado : 'inactivo';

      // IDs de platillos seleccionados
      const idsSeleccionados = data.platillos.map(id => parseInt(id));

      // Cargar todos los platillos para checkboxes
      fetch(`../modelo/cargar_platillos.php?id_usuario=${usuarioId}&modo=combo`)
        .then(res => res.json())
        .then(platillos => {
          const contenedor = document.getElementById("listaPlatillosCombo");
          contenedor.innerHTML = "";

          platillos.forEach(p => {
            const checked = idsSeleccionados.includes(p.id);
            const label = document.createElement("label");
            label.className = "flex items-center space-x-2";
            label.innerHTML = `
              <input type="checkbox" name="platillos[]" value="${p.id}" ${checked ? "checked" : ""} class="mr-2">
              <span>${p.nombre} - $${p.precio.toFixed(2)}</span>
            `;
            contenedor.appendChild(label);
          });
        });
    })
    .catch(err => {
      console.error("Error al obtener combo:", err);
      alert("❌ No se pudo obtener el combo.");
    });
}


function eliminarCombo(id) {
  if (!confirm("¿Eliminar este combo?")) return;

  const formData = new FormData();
  formData.append("id", id);

  fetch("../modelo/eliminar_combo.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(response => {
      if (response.success) {
        alert("✅ Combo eliminado");
        mostrarCombos();
      } else {
        alert("❌ Error: " + (response.error || "No se pudo eliminar el combo."));
      }
    })
    .catch(err => {
      console.error("Error al eliminar combo:", err);
      alert("❌ Error al conectar con el servidor.");
    });
}


let categoriaSeleccionada = null;
let categoriaSeleccionadaId = null;


function renderPlatillos(platillos) {
  const contenedor = document.getElementById("contenedorPlatillos");
  contenedor.innerHTML = "";

  platillos.forEach(platillo => {
    const div = document.createElement("div");
    div.className = "aspect-square w-40 bg-white rounded shadow p-2 border relative group flex flex-col justify-between";

    div.innerHTML = `
 <img src="${platillo.foto && platillo.foto.trim() !== '' ? platillo.foto : '../uploads/platillos/default.png'}"
     class="w-full h-24 object-cover rounded"
      onerror="this.onerror=null;this.src='../uploads/platillos/default.png';">




    <div class="absolute top-1 right-1 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
  <button onclick="editarPlatillo(${platillo.id})" class="text-orange-500">🖊️</button>
  <button onclick="eliminarPlatillo(${platillo.id}, '${platillo.nombre}')" class="text-red-500">🗑️</button>

</div>
    <div class="text-center mt-2">
      <h3 class="font-semibold text-sm">${platillo.nombre}</h3>
      <p class="text-gray-600 text-sm">$${platillo.precio.toFixed(2)}</p>
      <span class="inline-block text-xs px-2 py-1 rounded-full mt-1
  ${platillo.estado === 'disponible' ? 'bg-green-100 text-green-700' :
    platillo.estado === 'agotado' ? 'bg-yellow-100 text-yellow-700' :
    'bg-red-100 text-red-700'}">
  ${platillo.estado}
</span>
    </div>
  </div>
`;


    contenedor.appendChild(div);
  });

  const btnCrear = document.createElement("div");
  btnCrear.className = "aspect-square w-40";
  btnCrear.innerHTML = `
    <button onclick="abrirModalPlatillo()" class="w-full h-full border-2 border-dashed border-gray-400 flex items-center justify-center text-4xl text-gray-500 hover:bg-gray-100 rounded">
      +
    </button>
  `;
  contenedor.appendChild(btnCrear);
}

function editarPlatillo(id) {
  fetch(`../modelo/obtener_platillo.php?id=${id}`)
    .then(res => res.text())
    .then(text => {
      try {
        const data = JSON.parse(text);
        if (data.error) return alert("❌ Error: " + data.error);

        abrirModalPlatillo(); // Abre el modal

        document.getElementById("editIdPlatillo").value = data.id_platillo;
        document.getElementById("editFotoActual").value = data.foto || "";

        document.querySelector("#formPlatillo [name='nombre']").value = data.nombre;
        document.querySelector("#formPlatillo [name='precio']").value = data.precio;
        document.querySelector("#formPlatillo [name='descripcion']").value = data.descripcion;
        document.querySelector("#formPlatillo [name='tiempo_preparacion']").value = data.tiempo_preparacion;
       document.querySelector("[name='estado']").value = data.estado || 'disponible';



        setTimeout(() => {
          document.querySelector("#formPlatillo [name='id_categoria']").value = data.id_categoria;
        }, 200);

        fetch('../modelo/cargar_ingredientes_categorias.php')
  .then(res => res.json())
  .then(ingData => {
    const lista = document.getElementById("listaIngredientes");
    lista.innerHTML = "";
    ingData.ingredientes.forEach(ing => {
      const checked = data.ingredientes.find(i => i.id == ing.id_Ingrediente);
      const isVencido = ing.estado === 'vencido';
      const isInactivo = ['agotado', 'no disponible'].includes(ing.estado);
      
      const claseTexto = isVencido ? "text-red-600 line-through" :
                         isInactivo ? "text-gray-500 italic" : "";

      lista.innerHTML += `
        <div class="flex items-center space-x-2">
          <input type="checkbox" id="ing_${ing.id_Ingrediente}" value="${ing.id_Ingrediente}"
            class="checkboxIng" ${checked ? "checked" : ""}>
          <label for="ing_${ing.id_Ingrediente}" class="flex-1 ${claseTexto}">
            ${ing.nombre} (${ing.unidad_medida})
            ${isVencido ? ' - VENCIDO' : isInactivo ? ` - ${ing.estado.toUpperCase()}` : ''}
          </label>
          <input type="number" step="0.01" min="0" placeholder="Cantidad"
            class="inputCantidad border rounded px-2 py-1 w-24"
            data-id="${ing.id_Ingrediente}" value="${checked ? checked.cantidad : ""}">
        </div>
      `;
    });
  });


        const preview = document.getElementById("previewImagen");
        if (data.foto) {
          preview.src = data.foto;
          preview.classList.remove("hidden");
        } else {
          preview.src = "#";
          preview.classList.add("hidden");
        }

      } catch (e) {
        console.error("⚠️ Respuesta no es JSON válido:", text);
        alert("❌ Error inesperado al cargar platillo. Consulta la consola.");
      }
    })
    .catch(err => {
      console.error("❌ Error al conectarse con el servidor:", err);
      alert("❌ No se pudo conectar con el servidor.");
    });
}


function eliminarPlatillo(id, nombre) {
  if (!confirm(`¿Estás seguro de eliminar el platillo "${nombre}"?`)) return;

  const formData = new FormData();
  formData.append("id_platillo", id);

  // Mostrar spinner en botón (opcional)
  const originalText = document.body.innerHTML;
  document.body.style.pointerEvents = 'none';
  document.body.style.opacity = '0.5';

  fetch("../modelo/eliminar_platillo.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(response => {
    document.body.style.pointerEvents = '';
    document.body.style.opacity = '1';

    if (response.success) {
      alert(`✅ Platillo "${nombre}" eliminado correctamente`);
      cargarPlatillos(categoriaSeleccionadaId);
    } else {
      alert("❌ Error: " + (response.error || "Error desconocido"));
    }
  })
  .catch(err => {
    document.body.style.pointerEvents = '';
    document.body.style.opacity = '1';

    console.error("Error eliminando platillo:", err);
    alert("❌ Error al conectar con el servidor");
  });
}


function cargarPlatillos(idCategoria = null) {
  categoriaSeleccionada = idCategoria;
  let url = "../modelo/cargar_platillos.php";
  if (idCategoria) url += `?id_categoria=${idCategoria}`;

  fetch(url)
    .then(res => res.json())
    .then(data => renderPlatillos(data));
}

function cargarCategorias() {
  fetch('../modelo/cargar_categorias.php')
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


div.className = "relative aspect-square w-40 bg-white rounded shadow flex flex-col items-center justify-between p-2 border group"; // 👈 necesitas group

// Botón editar
const editBtn = document.createElement("button");
editBtn.className = "absolute top-1 right-8 text-yellow-300 opacity-0 group-hover:opacity-100";
editBtn.innerHTML = "✏️";
editBtn.onclick = () => editarPlatillo(platillo.id_platillo);
div.appendChild(editBtn);

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
      fetch('../modelo/cargar_categorias.php')
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
          const btnCombo = document.createElement("button");
          btnCombo.className = "w-full text-left px-4 py-2 bg-purple-700 hover:bg-purple-600 rounded-lg text-white font-medium mt-2";
          btnCombo.textContent = "Combos 🧃";
          btnCombo.onclick = mostrarCombos;
          categoryList.appendChild(btnCombo);
        });
    }

    function eliminarCategoria(id) {
      if (!confirm("¿Eliminar esta categoría?")) return;

      const formData = new FormData();
      formData.append("id_categoria", id);

      fetch("../modelo/Eliminar_categoria.php", {
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

        fetch("../modelo/guardar_categoria.php", {
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

<!-- Modal para crear/editar platillo -->
<div id="modalPlatillo" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 overflow-y-auto">
 <div class="bg-white p-6 rounded-lg w-full max-w-2xl shadow-xl max-h-[90vh] overflow-y-auto">
    <h2 id="tituloModalPlatillo" class="text-xl font-bold mb-4">Crear Nuevo Platillo</h2>

    <form id="formPlatillo" enctype="multipart/form-data">
      <input type="hidden" name="id_platillo" id="editIdPlatillo">
      <input type="hidden" name="foto_actual" id="editFotoActual">

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

        <div>
  <label class="block mb-1 font-medium">Estado del Platillo:</label>
  <select name="estado" class="w-full p-2 border border-gray-300 rounded">
    <option value="disponible">Disponible</option>
    <option value="no_disponible">No disponible</option>
  </select>
</div>


        <div class="col-span-2">
          <label class="block mb-1 font-medium">Foto:</label>
          <input type="file" name="foto" id="inputFoto" accept="image/*" class="w-full">
          <div class="mt-2">
            <img id="previewImagen" src="#" alt="Vista previa" class="w-32 h-32 object-cover rounded border border-gray-300 shadow hidden" />
          </div>
        </div>
      </div>
        
<div class="mt-4">
  <label class="block mb-1 font-medium">🔍 Buscar ingrediente:</label>
  <input type="text" id="buscadorIngrediente" placeholder="Escribe el nombre..." class="w-full p-2 border border-gray-300 rounded">
</div>

      
      <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Ingredientes Disponibles</h3>
        
        <div id="listaIngredientes" class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border p-2 rounded border-gray-300"></div>
      </div>

      <div class="flex justify-end mt-6 space-x-2">
        <button type="button" onclick="cerrarModalPlatillo()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded">Guardar Platillo</button>
      </div>
    </form>
  </div>
</div>

<script>

function aplicarFiltroIngredientes() {
  const filtro = document.getElementById("buscadorIngrediente").value.toLowerCase();
  const items = document.querySelectorAll("#listaIngredientes > div");
  items.forEach(item => {
    const nombre = item.textContent.toLowerCase();
    item.style.display = nombre.includes(filtro) ? "flex" : "none";
  });
}

function abrirModalPlatillo() {
  fetch('../modelo/cargar_ingredientes_categorias.php')
    .then(res => res.json())
    .then(data => {
      const ingredientes = data.ingredientes || [];
      const categorias = data.categorias || [];

      if (ingredientes.length === 0 || categorias.length === 0) {
        let msg = "❌ No se puede crear platillo:\n";
        if (categorias.length === 0) msg += "- No hay categorías registradas.\n";
        if (ingredientes.length === 0) msg += "- No hay ingredientes registrados.\n";
        alert(msg);
        return;
      }

      // Si hay ingredientes y categorías, abrir el modal normalmente
      document.getElementById("modalPlatillo").classList.remove("hidden");

      const titulo = document.getElementById("tituloModalPlatillo");
      const id = document.getElementById("editIdPlatillo").value;
      titulo.textContent = id ? "Editar Platillo" : "Crear Nuevo Platillo";

      cargarIngredientesYcategorias(data); // pasar datos para evitar doble llamada
    })
    .catch(err => {
      console.error("❌ Error al cargar datos:", err);
      alert("❌ No se pudo validar los datos para crear un platillo.");
    });
}

function cerrarModalPlatillo() {
  document.getElementById("modalPlatillo").classList.add("hidden");
  document.getElementById("formPlatillo").reset();
  document.getElementById("editIdPlatillo").value = "";
  document.getElementById("editFotoActual").value = "";
  document.getElementById("previewImagen").classList.add("hidden");
}

function cargarIngredientesYcategorias(data = null) {
  const load = data
    ? Promise.resolve(data)
    : fetch('../modulo/cargar_ingredientes_categorias.php').then(res => res.json());

  load.then(data => {
    const catSelect = document.getElementById("selectCategoria");
    const currentCat = document.getElementById("editIdPlatillo").value ? null : categoriaSeleccionadaId;
    catSelect.innerHTML = "";
    data.categorias.forEach(cat => {
      const option = document.createElement("option");
      option.value = cat.id_categoria;
      option.textContent = cat.nombre_categoria;
      if (cat.id_categoria == currentCat) option.selected = true;
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
        <input type="number" step="0.01" min="0" name="cant_${ing.id_Ingrediente}" placeholder="Cantidad" class="inputCantidad border rounded px-2 py-1 w-24" data-id="${ing.id_Ingrediente}">
      `;
      lista.appendChild(div);
    });
  });
}


document.getElementById("formPlatillo").addEventListener("submit", function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  // ✅ Asegura que sea un array
  const ingredientes = [];

  document.querySelectorAll(".checkboxIng:checked").forEach(chk => {
    const id = chk.value;
    const cantidadInput = document.querySelector(`.inputCantidad[data-id='${id}']`);
    const cantidad = parseFloat(cantidadInput.value || 0);
    if (cantidad > 0) ingredientes.push({ id, cantidad });
  });

  formData.append("ingredientes", JSON.stringify(ingredientes));

  const idPlatillo = form.querySelector("[name='id_platillo']").value;
  const endpoint = idPlatillo
    ? "../modelo/editar_platillos.php"
    : "../modelo/guardar_platillo.php";

  fetch(endpoint, {
    method: "POST",
    body: formData
  })
  .then(async res => {
    const text = await res.text();
    try {
      const json = JSON.parse(text);
      return json;
    } catch (e) {
      console.error("⚠️ Respuesta no es JSON válido:", text);
      throw new Error("Respuesta inválida del servidor");
    }
  })
  .then(response => {
    if (response.success) {
      alert("✅ Platillo guardado exitosamente");
      cerrarModalPlatillo();
      cargarPlatillos(categoriaSeleccionadaId);
    } else {
      alert("❌ Error: " + (response.error || "Error desconocido"));
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

  inputFoto.addEventListener("change", function () {
    const archivo = this.files[0];
    if (archivo) {
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.classList.remove("hidden");
      };
      reader.readAsDataURL(archivo);
    } else {
      preview.src = "#";
      preview.classList.add("hidden");
    }
  });

  const buscador = document.getElementById("buscadorIngrediente");
  if (buscador) {
    buscador.addEventListener("input", aplicarFiltroIngredientes);
  }
});
</script>

<script>
function mostrarNotificaciones() {
  fetch("../modelo/notificaciones_ingredientes.php")
    .then(res => res.json())
    .then(data => {
      if (!data.avisos || data.avisos.length === 0) return;

      const contenedor = document.createElement("div");
      contenedor.className = "fixed top-5 right-5 z-50 space-y-4 max-w-xs w-full";

      data.avisos.forEach(msg => {
        let tipo = 'info';
        if (msg.includes('vencido')) tipo = 'error';
        else if (msg.includes('bajo')) tipo = 'warning';

        const colores = {
          info:  ['#2563eb', '#dbeafe'],   // azul
          warning: ['#ca8a04', '#fef3c7'], // amarillo
          error: ['#dc2626', '#fee2e2'],   // rojo
        };

        const [border, fondo] = colores[tipo];
        const icono = tipo === 'error' ? '❌' : tipo === 'warning' ? '⚠️' : 'ℹ️';

        const aviso = document.createElement("div");
        aviso.style.backgroundColor = fondo;
        aviso.style.borderLeft = `5px solid ${border}`;
        aviso.className = `
          relative p-4 pr-8 rounded shadow-xl animate-slide-in
          text-gray-900 font-medium ring-1 ring-black ring-opacity-5
        `;

        aviso.innerHTML = `
          <button onclick="this.parentElement.remove()" 
                  class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-lg font-bold">×</button>
          <div class="flex items-start space-x-3">
            <div class="text-2xl leading-none">${icono}</div>
            <div class="text-sm">${msg}</div>
          </div>
        `;

        contenedor.appendChild(aviso);
      });

      document.body.appendChild(contenedor);
      setTimeout(() => contenedor.remove(), 15000);
    })
    .catch(err => {
      console.error("⚠️ Error cargando notificaciones:", err);
    });
}
document.addEventListener("DOMContentLoaded", mostrarNotificaciones);
</script>

<style>
@keyframes slide-in {
  from { opacity: 0; transform: translateX(40px); }
  to { opacity: 1; transform: translateX(0); }
}
.animate-slide-in {
  animation: slide-in 0.5s ease-out forwards;
}
</style>

<!-- Modal para crear combo -->
<div id="modalCombo" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 overflow-y-auto">
  <div class="bg-white p-6 rounded-lg w-full max-w-2xl shadow-xl">
    <h2 class="text-xl font-bold mb-4">Crear Nuevo Combo</h2>
    <form id="formCombo">
      <input type="hidden" name="id_combo" id="editIdCombo">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 font-medium">Nombre del Combo:</label>
          <input type="text" name="nombre_combo" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div>
          <label class="block mb-1 font-medium">Precio del Combo:</label>
          <input type="number" step="0.01" name="precio_combo" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
      </div>
      <div class="mt-4">
  <label class="block mb-1 font-medium">Descripción del Combo:</label>
  <textarea name="descripcion" rows="3" class="w-full p-2 border border-gray-300 rounded"></textarea>
</div>
<div>
  <label class="block mb-1 font-medium">Estado:</label>
  <select name="estado" class="w-full p-2 border border-gray-300 rounded">
    <option value="activo">Activo</option>
    <option value="inactivo">Inactivo</option>
  </select>
</div>
      <div class="mt-4">
        <label class="block font-medium mb-1">Seleccionar Platillos:</label>
        <div id="listaPlatillosCombo" class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border p-2 rounded border-gray-300"></div>
      </div>
      <div class="flex justify-end mt-6 space-x-2">
        <button type="button" onclick="cerrarModalCombo()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-purple-600 text-white hover:bg-purple-700 rounded">Guardar Combo</button>
      </div>
    </form>
  </div>
</div>

<script>
// FORM COMBO SUBMIT
document.getElementById("formCombo").addEventListener("submit", function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  const seleccionados = form.querySelectorAll("input[name='platillos[]']:checked");
  if (seleccionados.length === 0) {
    alert("❌ Debes seleccionar al menos un platillo para el combo.");
    return;
  }

  const btn = form.querySelector("button[type='submit']");
  btn.disabled = true;
  btn.textContent = "Guardando...";

  fetch("../modelo/guardar_combo.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(resp => {
   if (resp.success) {
  alert("✅ Combo guardado exitosamente");
  cerrarModalCombo();
  mostrarCombos(); // ← esto recarga la vista
}else {
      alert("❌ Error: " + (resp.error || "No se pudo guardar el combo"));
    }
  })
  .catch(err => {
    console.error("Error al guardar combo:", err);
    alert("❌ Error al conectar con el servidor");
  })
  .finally(() => {
    btn.disabled = false;
    btn.textContent = "Guardar Combo";
  });
});

// FUNCIONES COMBO
function abrirModalCombo() {
  document.getElementById("formCombo").reset();
  document.getElementById("editIdCombo").value = ""; // <- Limpia el campo oculto ID
  document.getElementById("listaPlatillosCombo").innerHTML = "";
  document.getElementById("modalCombo").classList.remove("hidden");

  cargarPlatillosParaCombo();
}


function cerrarModalCombo() {
  document.getElementById("modalCombo").classList.add("hidden");
  document.getElementById("formCombo").reset();
  document.getElementById("listaPlatillosCombo").innerHTML = '';
}

function cargarPlatillosParaCombo() {
  fetch("../modelo/cargar_platillos.php?modo=combo")
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("listaPlatillosCombo");
      contenedor.innerHTML = '';
      data.forEach(p => {
        const label = document.createElement("label");
        label.className = "flex items-center space-x-2";
        label.innerHTML = `
          <input type="checkbox" name="platillos[]" value="${p.id}" class="mr-2">
          <span>${p.nombre} - $${p.precio.toFixed(2)}</span>
        `;
        contenedor.appendChild(label);
      });
    });
}
</script>

</body>
</html>
