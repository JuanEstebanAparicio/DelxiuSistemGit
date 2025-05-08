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
    <a href="#" class="btn azul">Próximamente</a>
    <a href="../front_principal/registro_ingrediente.php" class="btn verde">Inventario</a>

    <div class="config-container">
      <button class="engranaje" onclick="toggleMenu()">⚙️</button>
      <div class="config-menu" id="configMenu">
       <a href="../css/estilos.css" id="cambiarTema">🌓 Cambiar Tema</a>
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
</body>
</html>

</body>
</html>
