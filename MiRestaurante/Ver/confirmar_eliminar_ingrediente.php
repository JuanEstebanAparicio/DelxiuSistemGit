<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
  echo "ID inválido.";
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmar eliminación</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white p-8 rounded shadow-lg max-w-md w-full text-center">
    <h2 class="text-xl font-bold text-red-600 mb-4">¿Eliminar ingrediente?</h2>
    <p class="mb-6 text-gray-700">
      ⚠️ Este ingrediente está registrado en uno o más platillos.<br>
      Si lo eliminas, también se eliminará de esos platillos.
    </p>
    <div class="flex justify-center gap-4">
      <a href="../modelo/Eliminar_Ingredientes.php?id=<?= $id ?>&confirmado=1"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Sí, eliminar</a>
      <a href="javascript:history.back()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Cancelar</a>
    </div>
  </div>

</body>
</html>
