<?php
session_start();
include("../auth/conexion.php");
if (!isset($_SESSION['correo'])) {

    header("Location: ../Ver/Login.php");
    exit();

}
$_SESSION['desde_panel_inicio'] = true;

$correo = $_SESSION['correo'];

// Conexión a la base de datos (ajusta según tu configuración)
$conexion = new mysqli("localhost", "root", "", "restaurante",3307);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener el nombre usando el correo
$sql = "SELECT id ,nombre_usuario FROM usuarios WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($id_usuario, $nombre);
$stmt->fetch();
$stmt->close();
$conexion->close();

$_SESSION['id'] = $id_usuario;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Página Principal</title>
  <link rel="stylesheet" href="../css/estilos.css">
  <script src="../js/funcionalidad.js" defer></script>
<style>
  #fraseBienvenida {
  opacity: 0;
  transform: translateY(30px);
}
</style>

</head>
<body>

  <header class="top-bar">
    <a href=""></a>
    <a href="#" class="btn amarillo">Inicio</a>
    <a href="../front_principal/gestor_menu.php" class="btn azul">Gestion de menu</a>
    <a href="../front_principal/registro_ingrediente.php" class="btn verde">Inventario</a>
    <a href="../front_principal/Ver_inventario.php" class="btn red">Ver inventario</a>
    <div class="config-container">
      <button class="engranaje" onclick="toggleMenu()">⚙️</button>
      <div class="config-menu" id="configMenu">
       <a href="../css/estilos.css" id="cambiarTema">🌓 Cambiar Tema</a>
       <a href="../front_principal/perfil_usuario.php">👤 Perfil</a>
        <a href="../php/logout.php">🚪 Cerrar Sesión</a>
      </div>
    </div>
  </header>

  <main class="contenido">
    <h1>Bienvenido <span class="usuario">"<?php echo htmlspecialchars($nombre); ?>"</span></h1>
    <br><br>
    <div class="frase-bienvenida" id="fraseBienvenida">
  <h2>🌟 ¡Imagina y trae a la vida tus mejores ideas! 🌟</h2>
</div>

  </main>
  <!-- ... otros elementos HTML ... -->

  <!-- GSAP y tu animación personalizada -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    gsap.to("#fraseBienvenida", {
      duration: 1.2,
      y: 0,
      opacity: 1,
      ease: "power2.out",
      delay: 0.5
    });
  });
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
            bottom: 1rem;
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
            max-width: 320px;
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
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
          }
        </style>
        <div class="contenedor" id="notiCont"></div>
      `;

      const contenedor = shadow.getElementById("notiCont");

      // Agrupar si hay más de 5
      const avisos = data.avisos;
      const maxIndividual = 5;

      if (avisos.length > maxIndividual) {
        const agrupado = document.createElement("div");
        agrupado.className = "noti";
        agrupado.dataset.tipo = "warning";
        agrupado.innerHTML = `
          <button class="cerrar" onclick="this.parentNode.remove()">×</button>
          <div>
            <span class="icono">📦</span> ${avisos.length} notificaciones de inventario.
            <ul style="margin-top: 0.5rem; padding-left: 1rem;">
              ${avisos.slice(0, maxIndividual).map(msg => `<li>• ${msg}</li>`).join("")}
              <li>...y ${avisos.length - maxIndividual} más</li>
            </ul>
          </div>
        `;
        contenedor.appendChild(agrupado);
      } else {
        avisos.forEach(msg => {
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
      }

      setTimeout(() => host.remove(), 16000);
    })
    .catch(err => {
      console.error("⚠️ Error cargando notificaciones:", err);
    });
}

document.addEventListener("DOMContentLoaded", mostrarNotificaciones);
</script>


</body>
</html>
