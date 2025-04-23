<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "restaurante", 3307);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Si ya se envió el formulario de verificación
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $codigo_ingresado = $_POST['codigo'];

    // Buscar al usuario temporal
    $sql = "SELECT * FROM usuarios_temp WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario_temp = $resultado->fetch_assoc();

        if ($usuario_temp['codigo_verificacion'] == $codigo_ingresado) {
            // Insertar usuario en tabla final "usuarios"
            $sql_insert = "INSERT INTO usuarios (nombre_usuario, correo, clave, nombre_restaurante)
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param(
                "ssss",
                $usuario_temp['usuario'],
                $usuario_temp['correo'],
                $usuario_temp['contrasena'],
                $usuario_temp['restaurante']
            );
            $stmt_insert->execute();

            // Eliminar de usuarios_temp
            $sql_delete = "DELETE FROM usuarios_temp WHERE correo = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("s", $correo);
            $stmt_delete->execute();

            echo '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro exitoso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .alerta {
            background-color: #fff;
            border: 1px solidrgb(55, 216, 136);
            border-left: 5px solidrgb(45, 223, 119);
            padding: 20px 30px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .alerta h2 {
            color:rgb(45, 243, 121);
            margin-bottom: 10px;
        }
        .alerta p {
            margin-bottom: 20px;
        }
        .alerta a {
            text-decoration: none;
            background-color:rgb(35, 245, 115);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .alerta a:hover {
            background-color:rgb(54, 243, 95);
        }
    </style>
</head>
<body>
    <div class="alerta">
        <h2>Registrado correctamente</h2>
        <p>Puede iniciar sesion.</p>
        <a href="../Ver/Login.php">Iniciar sesion</a>
    </div>
</body>
</html>';

        } else {
            echo '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Codigo incorrecto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .alerta {
            background-color: #fff;
            border: 1px solid #dc3545;
            border-left: 5px solid #dc3545;
            padding: 20px 30px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .alerta h2 {
            color: #dc3545;
            margin-bottom: 10px;
        }
        .alerta p {
            margin-bottom: 20px;
        }
        .alerta a {
            text-decoration: none;
            background-color: #dc3545;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .alerta a:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>
    <div class="alerta">
        <h2>Codigo Incorrecto</h2>
        <p>Verifique bien y vuelva a ingresarlo.</p>
        <a href="javascript:history.back()">Volver a ingresar</a>
    </div>
</body>
</html>';
        }
    } else {
        echo "<div class='mensaje error'>❌ No se encontró ningun codigo asociado.</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Mostrar formulario de verificación
    $correo = $_GET['correo'] ?? '';
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Verificación de Cuenta</title>
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background: linear-gradient(to right, #4facfe, #00f2fe);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            .verificacion-form {
                background-color: white;
                padding: 2rem 3rem;
                border-radius: 1rem;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                text-align: center;
                max-width: 400px;
                width: 100%;
            }

            .verificacion-form h2 {
                margin-bottom: 1rem;
                color: #333;
            }

            label {
                display: block;
                text-align: left;
                font-weight: 500;
                margin-bottom: 0.5rem;
                color: #555;
            }

            input[type="text"] {
                width: 100%;
                padding: 0.8rem;
                border-radius: 0.5rem;
                border: 1px solid #ccc;
                margin-bottom: 1.5rem;
                font-size: 1rem;
            }

            button {
                background: #4facfe;
                color: white;
                padding: 0.8rem 2rem;
                border: none;
                border-radius: 0.5rem;
                font-size: 1rem;
                cursor: pointer;
                transition: background 0.3s ease;
            }

            button:hover {
                background: #00c3ff;
            }

            .mensaje {
                text-align: center;
                font-weight: bold;
                margin-top: 20px;
                padding: 15px;
                border-radius: 8px;
                max-width: 500px;
                margin-left: auto;
                margin-right: auto;
            }

            .exito {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>
    </head>
    <body>
        <div class="verificacion-form">
            <a href="../Ver/registro.php">Volver</a>
            <h2>Verificar cuenta</h2>
            <form method="POST" action="verificar.php">
                <input type="hidden" name="correo" value="<?php echo htmlspecialchars($correo); ?>">
                <label for="codigo">Código de verificación:</label>
                <input type="text" name="codigo" id="codigo" required>
                <button type="submit">Verificar</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
