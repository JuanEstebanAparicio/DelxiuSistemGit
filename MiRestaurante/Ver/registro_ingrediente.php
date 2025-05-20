<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestor de inventarios</title>
   <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////7 --> 
  <link rel="stylesheet" href="../css/estilos.css">
  <script src="../js/funcionalidad.js" defer></script>
 <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////7 --> 
  <style>
    body { background-color: #f8f9fa; padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    h1 { text-align: center; margin-bottom: 30px; color: #2c3e50; }
    form {
      max-width: 800px;
      margin: 0 auto;
      background: #ffffff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    label {
      display: block;
      font-weight: 600;
      margin-top: 20px;
      color: #34495e;
    }
    input, select, textarea {
      width: 100%;
      padding: 12px 15px;
      margin-top: 8px;
      border: 1px solid #ced4da;
      border-radius: 8px;
      font-size: 16px;
      background-color: #fdfdfd;
      transition: border-color 0.3s;
    }
    input:focus, select:focus, textarea:focus {
      border-color: #3498db;
      outline: none;
      box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }
    button {
      margin-top: 30px;
      background-color: #28a745;
      border: none;
      padding: 12px 25px;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #218838;
    }
  </style>
</head>

<body>
   <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////7 --> 
<header class="top-bar">
    <a href=""></a>
    <a href="../Ver/Inicio.php" class="btn amarillo">Inicio</a>
    <a href="../Ver/gestor_menu.php" class="btn azul">Gestion de menu</a>
    <a href="../front_principal/registro_ingrediente.php" class="btn verde">Inventario</a>
    <a href="../Ver/Ver_inventario.php" class="btn red">Ver inventario</a>



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
   <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////7 --> 
  <h1>Registrar Ingrediente</h1>
  <form action="../modelo/guardar_ingredientes.php" method="POST" enctype="multipart/form-data">

  <label for="nombre">Nombre del ingrediente</label>
  <input type="text" id="nombre" name="nombre" required maxlength="100" placeholder="Ej: Tomate">

  <label for="cantidad">Cantidad</label>
  <input type="number" id="cantidad" name="cantidad" step="0.01" min="0.01" required>

  <label for="cantidad_minima">Cantidad mínima (Se avisara cuando la cantidad este por debajo de esta)</label>
  <input type="number" id="cantidad_minima" name="cantidad_minima" step="0.01" min="0.01" required>

  <label for="unidad_medida">Unidad de medida</label>
  <select name="unidad_medida" id="unidad_medida" onchange="togglePersonalizado('unidad_medida','unidad_personalizada')" required>
    <option value="">Seleccione unidad</option>
    <option value="kg">Kilogramos</option>
    <option value="g">Gramos</option>
    <option value="l">Litros</option>
    <option value="ml">Mililitros</option>
    <option value="unidades">Unidades</option>
    <option value="otro">Otra...</option>
  </select>
  <input type="text" name="unidad_personalizada" id="unidad_personalizada" placeholder="Unidad personalizada" style="display:none;" maxlength="50">

  <label for="costo_unitario">Costo unitario ($)</label>
  <input type="number" id="costo_unitario" name="costo_unitario" step="0.01" min="0.01" required>

  <label for="categoria">Categoría</label>
  <select name="categoria" id="categoria" onchange="togglePersonalizado('categoria','categoria_personalizada')" required>
    <option value="">Seleccione categoría</option>
    <option value="Verduras">Verduras</option>
    <option value="Frutas">Frutas</option>
    <option value="Lácteos">Lácteos</option>
    <option value="Carnes">Carnes</option>
    <option value="Cereales y granos">Cereales y granos</option>
    <option value="Panadería">Panadería</option>
    <option value="Enlatados">Enlatados</option>
    <option value="Especias">Especias</option>
    <option value="Snacks">Snacks</option>
    <option value="Bebidas">Bebidas</option>
    <option value="otro">Otra...</option>
  </select>
  <input type="text" name="categoria_personalizada" id="categoria_personalizada" placeholder="Categoría personalizada" style="display:none;" maxlength="50">

  <label for="fecha_ingreso">Fecha de ingreso</label>
  <input type="text" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo date('Y-m-d'); ?>" readonly>

  <label for="fecha_vencimiento">Fecha de vencimiento</label>
  <input 
  type="date" 
  name="fecha_vencimiento" 
  id="fecha_vencimiento"
  min="<?= date('Y-m-d'); ?>" 
  required
>

  <label for="lote">Lote</label>
  <input type="text" id="lote" name="lote" maxlength="100">

  <label for="descripcion">Descripción</label>
  <textarea id="descripcion" name="descripcion" rows="3"></textarea>

  <label for="ubicacion_almacen">Ubicación en almacén</label>
  <input type="text" id="ubicacion_almacen" name="ubicacion_almacen" maxlength="100">

  <label for="estado">Estado</label>
  <select id="estado" name="estado">
    <option value="activo" selected>Activo</option>
    <option value="no disponible">No disponible</option>
  </select>

  <label for="proveedor">Proveedor</label>
  <input type="text" id="proveedor" name="proveedor" maxlength="100">

  <label for="foto">Foto del ingrediente</label>
  <input type="file" id="foto" name="foto" accept="image/*">

  <!-- Vista previa de la imagen -->
  <img id="preview" src="#" alt="Vista previa" style="display:none; max-width:200px; margin-top:10px;" />

  <button type="submit">Registrar Ingrediente</button>
</form>
  <script>
  function togglePersonalizado(selectId, inputId) {
    const select = document.getElementById(selectId);
    const input = document.getElementById(inputId);
    input.style.display = select.value === 'otro' ? 'block' : 'none';
    if (select.value !== 'otro') input.value = '';
  }

</script>

<script>
document.getElementById('categoria').addEventListener('change', function () {
    const inputPers = document.getElementById('categoria_personalizada');
    inputPers.style.display = this.value === 'otro' ? 'block' : 'none';
});
</script>

<script>
  document.getElementById('foto').addEventListener('change', function(event) {
    const input = event.target;
    const preview = document.getElementById('preview');

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  });
</script>

<script>
function mostrarNotificaciones() {
  fetch("../modelo/notificaciones_ingredientes.php")
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
