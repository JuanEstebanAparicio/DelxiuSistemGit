<?php
session_start();
header('Content-Type: application/json');
require '../modelo/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$usuario_id = $data['usuario_id'] ?? null;
$cliente_nombre = trim($data['nombre_cliente'] ?? '');
$cliente_contacto = trim($data['telefono'] ?? '');
$mesa = trim($data['mesa'] ?? '');
$orden_items = $data['carrito'] ?? [];
$propina = floatval($data['propina'] ?? 0);

if (!$usuario_id || empty($orden_items) || $cliente_nombre === '' || $mesa === '') {
    echo json_encode(["success" => false, "error" => "Datos incompletos para guardar orden"]);
    exit;
}

foreach ($orden_items as $item) {
    $id_platillo = intval($item['id']);
    $nombre_platillo = $item['nombre'];
    $cantidad = intval($item['cantidad'] ?? 1);

    $sql = "
        SELECT i.id_Ingrediente, i.nombre, i.cantidad, mpi.cantidad_necesaria
        FROM menu_platillo_ingredientes mpi
        JOIN inventario i ON i.id_Ingrediente = mpi.ingrediente_id
        WHERE mpi.platillo_id = ? AND i.usuario_id = ?
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_platillo, $usuario_id);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $stock = floatval($row['cantidad']);
        $necesario = floatval($row['cantidad_necesaria']) * $cantidad;

        if ($stock < $necesario) {
            echo json_encode([
                "success" => false,
                "error" => "No hay suficientes ingredientes para preparar '$nombre_platillo'"
            ]);
            exit;
        }
    }
}

// 🧮 Calcular total
$total = array_sum(array_map(fn($p) => floatval($p['precio']), $orden_items)) + $propina;

// 📝 Guardar orden
$stmt = $conexion->prepare("
    INSERT INTO ordenes (usuario_id, cliente_nombre, cliente_contacto, mesa, nota, total, propina)
    VALUES (?, ?, ?, ?, '', ?, ?)
");
$stmt->bind_param("isssdd", $usuario_id, $cliente_nombre, $cliente_contacto, $mesa, $total, $propina);
$stmt->execute();
$orden_id = $stmt->insert_id;
$stmt->close();

// 🧾 Insertar detalles de la orden
$stmt_detalle = $conexion->prepare("
    INSERT INTO orden_detalles (orden_id, platillo_id, nombre, precio)
    VALUES (?, ?, ?, ?)
");

foreach ($orden_items as $item) {
    $id = intval($item['id']);
    $nombre = $item['nombre'];
    $precio = floatval($item['precio']);
    $cantidad = intval($item['cantidad'] ?? 1);

    for ($i = 0; $i < $cantidad; $i++) {
        $stmt_detalle->bind_param("iisd", $orden_id, $id, $nombre, $precio);
        $stmt_detalle->execute();
    }

    // 🧾 Restar ingredientes del inventario
    $sqlIngredientes = "
        SELECT mpi.ingrediente_id, mpi.cantidad_necesaria
        FROM menu_platillo_ingredientes mpi
        WHERE mpi.platillo_id = ?
    ";
    $stmt_ing = $conexion->prepare($sqlIngredientes);
    $stmt_ing->bind_param("i", $id);
    $stmt_ing->execute();
    $res_ing = $stmt_ing->get_result();

    while ($rowIng = $res_ing->fetch_assoc()) {
        $ingrediente_id = $rowIng['ingrediente_id'];
        $cantidad_necesaria_total = $rowIng['cantidad_necesaria'] * $cantidad;

        // Actualizar cantidad del inventario
        $sqlUpdate = "
            UPDATE inventario
            SET cantidad = cantidad - ?, estado = IF(cantidad - ? <= 0, 'agotado', estado)
            WHERE id_Ingrediente = ? AND usuario_id = ?
        ";
        $stmt_upd = $conexion->prepare($sqlUpdate);
        $stmt_upd->bind_param("ddii", $cantidad_necesaria_total, $cantidad_necesaria_total, $ingrediente_id, $usuario_id);
        $stmt_upd->execute();
        $stmt_upd->close();

        // Verificar si ingrediente está agotado y marcar platillos relacionados como agotado
        $sqlCheck = "
            SELECT cantidad FROM inventario
            WHERE id_Ingrediente = ? AND usuario_id = ?
        ";
        $stmt_chk = $conexion->prepare($sqlCheck);
        $stmt_chk->bind_param("ii", $ingrediente_id, $usuario_id);
        $stmt_chk->execute();
        $result_chk = $stmt_chk->get_result();
        $row_chk = $result_chk->fetch_assoc();

        if ($row_chk && floatval($row_chk['cantidad']) <= 0) {
            // Marcar todos los platillos que usen este ingrediente como agotado
            $sqlAgotarPlatillos = "
                UPDATE menu_platillo mp
                JOIN menu_platillo_ingredientes mpi ON mp.id_platillo = mpi.platillo_id
                SET mp.estado = 'agotado'
                WHERE mpi.ingrediente_id = ? AND mp.usuario_id = ?
            ";
            $stmt_agota = $conexion->prepare($sqlAgotarPlatillos);
            $stmt_agota->bind_param("ii", $ingrediente_id, $usuario_id);
            $stmt_agota->execute();
            $stmt_agota->close();
        }
        $stmt_chk->close();
    }
    $stmt_ing->close();
}

$stmt_detalle->close();
$conexion->close();

echo json_encode(["success" => true, "orden_id" => $orden_id]);

?>