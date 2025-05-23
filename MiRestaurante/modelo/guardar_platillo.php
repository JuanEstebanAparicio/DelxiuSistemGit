<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$nombre = trim($_POST['nombre'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');
$id_categoria = intval($_POST['id_categoria'] ?? 0);
$tiempo_preparacion = intval($_POST['tiempo_preparacion'] ?? 0);

// Validar estado proporcionado por el usuario
$estado_usuario = $_POST['estado'] ?? 'disponible';
$estado = in_array($estado_usuario, ['disponible', 'no_disponible']) ? $estado_usuario : 'disponible';

$foto = $_FILES['foto']['name'] ?? '';
$foto_actual = $_POST['foto_actual'] ?? '';
$ruta_foto = $foto_actual;

if ($foto) {
    $ruta = '../uploads/platillos/' . basename($foto);
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)) {
        $ruta_foto = $ruta;
    }
}

// Validación básica
if ($nombre === '' || $precio <= 0 || $id_categoria <= 0) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

// Validar ingredientes válidos
$ingredientes_validos = 0;
foreach ($_POST as $key => $val) {
    if (strpos($key, "cant_") === 0 && floatval($val) > 0) {
        $ingredientes_validos++;
    }
}
if ($ingredientes_validos === 0) {
    echo json_encode(["error" => "Debe seleccionar al menos un ingrediente con cantidad mayor a 0"]);
    exit;
}

// Guardar platillo
$stmt = $conexion->prepare("INSERT INTO menu_platillo 
    (usuario_id, nombre, precio, descripcion, id_categoria, tiempo_preparacion, estado, foto) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isdssiss", $id_usuario, $nombre, $precio, $descripcion, $id_categoria, $tiempo_preparacion, $estado, $ruta_foto);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Error al guardar platillo"]);
    exit;
}

$id_platillo = $stmt->insert_id;
$stmt->close();

// Insertar ingredientes
foreach ($_POST as $key => $val) {
    if (strpos($key, "cant_") === 0) {
        $id_ing = intval(substr($key, 5));
        $cantidad = floatval($val);
        if ($cantidad > 0) {
            $conexion->query("INSERT INTO menu_platillo_ingredientes 
                (platillo_id, ingrediente_id, cantidad_necesaria) 
                VALUES ($id_platillo, $id_ing, $cantidad)");
        }
    }
}

// 🔁 Verificar si debe estar agotado
if ($estado === 'disponible') {
    $agotado = false;
    $fecha_hoy = date("Y-m-d");

    $sql = "
        SELECT i.estado, i.cantidad, i.fecha_vencimiento, mpi.cantidad_necesaria
        FROM menu_platillo_ingredientes mpi
        JOIN inventario i ON i.id_Ingrediente = mpi.ingrediente_id
        WHERE mpi.platillo_id = $id_platillo AND i.usuario_id = $id_usuario
    ";
    $res = $conexion->query($sql);

    while ($row = $res->fetch_assoc()) {
        $estadoIng = $row['estado'];
        $cantidad = floatval($row['cantidad']);
        $necesaria = floatval($row['cantidad_necesaria']);
        $vencido = $row['fecha_vencimiento'] && $row['fecha_vencimiento'] <= $fecha_hoy;

        if ($cantidad < $necesaria || $estadoIng !== 'activo' || $vencido) {
            $agotado = true;
            break;
        }
    }

    if ($agotado) {
        $conexion->query("UPDATE menu_platillo SET estado = 'agotado' WHERE id_platillo = $id_platillo");
    }
}

require_once 'funciones_estado.php';
actualizarEstadoCombosPorPlatillo($conexion, $id_platillo, $id_usuario);

echo json_encode(["success" => true]);
