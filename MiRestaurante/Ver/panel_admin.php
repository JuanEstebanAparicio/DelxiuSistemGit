<?php
// panel_admin.php
session_start();

// === CONEXIÓN A LA BASE DE DATOS ===
$conexion = new mysqli("localhost", "root", "", "restaurante",3307); // ← Cambia el nombre de la base

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// === BÚSQUEDA ===
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$sql = "SELECT * FROM usuarios WHERE 
        nombre_usuario LIKE '%$busqueda%' OR 
        correo LIKE '%$busqueda%' OR 
        nombre_restaurante LIKE '%$busqueda%'";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .contenedor {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .buscador {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .buscador input[type="text"] {
            padding: 10px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .buscador button {
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .buscador button:hover {
            background-color: #0052a3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #0066cc;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .boton {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .eliminar {
            background-color: #e74c3c;
            color: white;
        }

        .bloquear {
            background-color: #f39c12;
            color: white;
        }

        .activar {
            background-color: #2ecc71;
            color: white;
        }
    </style>
</head>
<body>
<center>
<a href="../Ver/Login.php">Volver</a>
</center>
<br>
    <div class="contenedor">
        <h2>Panel de Administración de Usuarios</h2>

        <form class="buscador" method="GET" action="panel_admin.php">
            <input type="text" name="busqueda" placeholder="Buscar por nombre de usuario, correo o restaurante..." value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit">Buscar</button>
        </form>

        <table>
            <tr>
                <th>Nombre de usuario</th>
                <th>Nombre de restaurante</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Eliminar</th>
                <th>Bloquear/Activar</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($fila['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_restaurante']) ?></td>
                    <td><?= htmlspecialchars($fila['correo']) ?></td>
                    <td><?= htmlspecialchars($fila['estado']) ?></td>
    
                    <td>
                        <?php if ($fila['rol'] != 'admin') { ?>
                            <form method="POST" action="../auth/eliminar_usuarios.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                                <button class="boton eliminar" type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</button>
                                </form>
                            </form>
                        <?php } else { echo "<span class='text-muted'>Admin</span>"; } ?>
                    </td>
                    <td>
                        <?php if ($fila['rol'] != 'admin') { ?>
                            <form action="../auth/cambiar_estado.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                            <input type="hidden" name="estado" value="<?php echo $fila['estado'] === 'activo' ? 'bloqueado' : 'activo'; ?>">
    
                            <button type="submit" style="background-color: <?php echo $fila['estado'] === 'activo' ? '#e74c3c' : '#2ecc71'; ?>; color: white; border: none; padding: 5px 10px; border-radius: 4px;">
                            <?php echo $fila['estado'] === 'activo' ? 'Bloquear' : 'Activar'; ?>

                            </button>
                            </form>

                        <?php } else { echo "-"; } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>


</body>
</html>

