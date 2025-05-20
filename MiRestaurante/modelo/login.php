<?php
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "restaurante", 3307);
if($conexion->connect_error){
    die("Error de conexión: " . $conexion->connect_error);
}

// Captura de datos del formulario
$correo = $_POST['correo'];
$clave = $_POST['clave'];

// Consulta para buscar el usuario
$sql = "SELECT * FROM usuarios WHERE correo=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $fila = $resultado->fetch_assoc();

    //Verifica si el usuario esta bloqueado
    if ($fila['estado'] === 'bloqueado') {
        echo "<script>alert('Su cuenta no está disponible para uso.'); window.history.back();</script>";
        exit;
    }


    // Verificar contraseña encriptada
    if (password_verify($clave, $fila['clave'])) {
        // Guardar datos en sesión
        $_SESSION['usuario'] = $fila['nombre_usuario'];
        $_SESSION['restaurante'] = $fila['nombre_restaurante'];
        $_SESSION['correo'] = $fila['correo']; // esto es importante para validar si es admin
        $_SESSION['id_usuario'] = $fila['id'];
        // Redirigir según si es administrador o usuario normal
        if ($correo === 'admin@gmail.com') {
            header("Location: ../Ver/panel_admin.php");
        } else {
            header("Location: ../Ver/Inicio.php");
        }

    } else {
        echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Correo no registrado'); window.history.back();</script>";
}

// Cierre de conexión
$stmt->close();
$conexion->close();
?>
