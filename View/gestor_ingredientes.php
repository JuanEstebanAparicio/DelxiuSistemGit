<?php
require_once('../Model/Entity/Conexion.php');
$conexion = Conexion::obtenerConexion();
$query = "SELECT * FROM insumos";
$resultado = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Ingredientes</title>
    <link rel="stylesheet" href="../CSS/insumos.css">
</head>
<body>
    <div class="content">
        <h2>Gestor de Ingredientes</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p style="color: green;">✅ Ingrediente registrado correctamente.</p>
        <?php endif; ?>

        <a href="formulario_insumos.php">
            <button>➕ Agregar Insumo</button>
        </a>

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nombre']; ?></td>
                        <td><?= $row['cantidad']; ?></td>
                        <td><?= $row['unidad']; ?></td>
                        <td><?= $row['estado']; ?></td>
                        <td>
                            <a href="editar_ingrediente.php?id=<?= $row['id']; ?>">
                                <button>✏️ Editar</button>
                            </a>
                            <a href="eliminar_ingrediente.php?id=<?= $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este ingrediente?');">
                                <button style="background-color: red; color: white;">🗑️ Eliminar</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
