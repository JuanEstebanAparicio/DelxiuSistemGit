
<?php require_once __DIR__ . '/../Middleware/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>
  <header>
    <h1>Bienvenido al Panel de Control</h1>
    <p>Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>!</p>
    <a href="/Proyecto%20de%20aula/Controller/Login/LogoutController.php">Cerrar sesión</a>

  </header>

  <main>
    <section class="overview">
      <h2>Resumen del sistema</h2>
      <p>Aquí podrás acceder a todas las funciones principales.</p>
    </section>

    <section class="acciones">
      <ul>
        <li><a href="#">Gestionar menús</a></li>
        <li><a href="#">Ver pedidos</a></li>
        <li><a href="#">Editar perfil</a></li>
        <li><a href="#">Ajustes</a></li>
      </ul>
    </section>
  </main>
</body>
</html>
