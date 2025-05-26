<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
 
  <style>
    :root {
      --bg-color: #f0f0f0;
      --container-bg: #ffffff;
      --text-color: #333;
      --input-bg: #ffffff;
      --input-border: #ccc;
      --input-text-color: #000;
      --button-bg: #4facfe;
      --button-hover: #00c3ff;
      --link-color: #007bff;
    }

    body.dark {
      --bg-color: #121212;
      --container-bg: #2c2c2c;
      --text-color: #f5f5f5;
      --input-bg: #3a3a3a;
      --input-border: #555;
      --input-text-color: #fff;
      --button-bg: #007bff;
      --button-hover: #0056b3;
      --link-color: #4facfe;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--bg-color);
      color: var(--text-color);
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: background-color 0.3s, color 0.3s;
    }

    .login-container {
      background-color: var(--container-bg);
      color: var(--text-color);
      padding: 3rem 2.5rem;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
      text-align: center;
      transition: background-color 0.3s, color 0.3s;
    }

    h1 {
      margin-bottom: 2rem;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.8rem;
      margin: 0.5rem 0 1.5rem 0;
      background-color: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 0.5rem;
      font-size: 1rem;
      color: var(--input-text-color);
      transition: background-color 0.3s, border-color 0.3s, color 0.3s;
    }

    button {
      background-color: var(--button-bg);
      color: white;
      padding: 0.8rem 2rem;
      border: none;
      border-radius: 0.5rem;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: var(--button-hover);
    }

    a {
      color: var(--link-color);
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    .darkmode-toggle {
      position: absolute;
      top: 1rem;
      right: 1rem;
    }
  </style>
</head>

<body>


  <div class="login-container">
    <h1>Inicie Sesión</h1>
    <form action="../modelo/login.php" method="POST" autocomplete="on">
      <label for="correo">Correo</label><br><br>
      <input type="email" name="correo" id="correo" placeholder="Correo" autocomplete="username" required><br><br>

      <label for="clave">Contraseña</label><br><br>
      <input type="password" name="clave" id="clave" placeholder="Contraseña" autocomplete="current-password" required>
      <br><br>

      <button type="submit" name="accion" value="login">Iniciar Sesión</button>
      <br><br>
      ¿Aún no tienes una cuenta? <a href="../Ver/registro.php">Registrese</a>
    </form>
  </div>


</body>
</html>
