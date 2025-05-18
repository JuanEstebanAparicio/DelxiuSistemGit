<?php
session_start();
include("../auth/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Ver/Login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$query = $conexion->prepare("SELECT nombre_usuario, nombre_restaurante FROM usuarios WHERE id = ?");
$query->bind_param("i", $id_usuario);
$query->execute();
$query->bind_result($nombre_usuario, $nombre_restaurante);
$query->fetch();
$query->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="../css/estilos.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f4f8;
      padding: 40px;
    }
    .contenedor {
      max-width: 450px;
      margin: auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 25px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      background-color: #2563eb;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background-color: #1e40af;
    }
    .mensaje {
      margin-top: 15px;
      text-align: center;
      font-size: 0.95em;
      color: red;
    }
  </style>
</head>
<body>

<div class="contenedor">
  <h2>Editar Perfil</h2>
  <form id="formPerfil">
    <label for="nombre_usuario">Nombre de Usuario</label>
    <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?= htmlspecialchars($nombre_usuario) ?>" required>

    <label for="nombre_restaurante">Nombre del Restaurante</label>
    <input type="text" id="nombre_restaurante" name="nombre_restaurante" value="<?= htmlspecialchars($nombre_restaurante) ?>" required>

    <label for="clave">Contraseña Actual</label>
    <input type="password" id="clave" name="clave" placeholder="Ingresa tu contraseña" required>

    <button type="submit">Guardar Cambios</button>
    <div class="mensaje" id="mensaje"></div>
  </form>
<center>
  <a href="../Ver/Inicio.php">Volver</a>
  </center>
</div>

<script>
document.getElementById("formPerfil").addEventListener("submit", function(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const mensaje = document.getElementById("mensaje");

  fetch("../back_principal/guardar_cambios_perfil.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      mensaje.textContent = "✅ Cambios guardados correctamente";
      mensaje.style.color = "green";
    } else {
      mensaje.textContent = "❌ " + (data.error || "Error desconocido");
      mensaje.style.color = "red";
    }
  })
  .catch(err => {
    console.error("Error:", err);
    mensaje.textContent = "❌ Error al conectar con el servidor.";
    mensaje.style.color = "red";
  });
});
</script>

</body>
</html>
