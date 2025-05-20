<!-- registro.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Restaurante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .form-container {
            width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        a {
            --link-color: #4facfe;

        }
        a {
      color: var(--link-color);
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Restaurante</h2>
        <form action="../modelo/procesar_registro.php" method="POST">
            <label>Nombre de usuario:</label>
            <input type="text" name="usuario" required>

            <label>Correo electrónico:</label>
            <input type="email" name="correo" required>

            <label>Nombre del restaurante:</label>
            <input type="text" name="restaurante" required>

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>

            <label>Confirmar contraseña:</label>
            <input type="password" name="confirmar_contrasena" required>

            <input type="submit" value="Registrar">
        </form>
        <br>
        <center>
        Si ya te registraste puedes<a href="../Ver/Login.php"> Iniciar sesión</a>
        </center>
    </div>
</body>
</html>

