<?php
function actualizarEstadoPlatillosPorIngrediente(mysqli $conexion, int $id_ingrediente, int $usuario_id): void {
  $sql = "
    SELECT DISTINCT mp.id_platillo
    FROM menu_platillo_ingredientes mpi
    JOIN menu_platillo mp ON mpi.platillo_id = mp.id_platillo
    WHERE mpi.ingrediente_id = ? AND mp.usuario_id = ?
  ";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ii", $id_ingrediente, $usuario_id);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $platillo_id = $row['id_platillo'];

    $sqlIng = "
      SELECT 
        i.cantidad, i.estado, i.fecha_vencimiento,
        mpi.cantidad_necesaria
      FROM menu_platillo_ingredientes mpi
      JOIN inventario i ON i.id_Ingrediente = mpi.ingrediente_id
      WHERE mpi.platillo_id = ? AND i.usuario_id = ?
    ";
    $stmtIng = $conexion->prepare($sqlIng);
    $stmtIng->bind_param("ii", $platillo_id, $usuario_id);
    $stmtIng->execute();
    $resIng = $stmtIng->get_result();

    $agotado = false;
    $fecha_hoy = date("Y-m-d");

    while ($ing = $resIng->fetch_assoc()) {
      $cantidad_disponible = floatval($ing['cantidad']);
      $cantidad_requerida = floatval($ing['cantidad_necesaria']);
      $estado = $ing['estado'];
      $vencido = $ing['fecha_vencimiento'] && $ing['fecha_vencimiento'] <= $fecha_hoy;

      if (
        $cantidad_disponible < $cantidad_requerida ||
        $estado !== 'activo' ||
        $vencido
      ) {
        $agotado = true;
        break;
      }
    }

    $nuevo_estado = $agotado ? 'agotado' : 'disponible';
    $update = $conexion->prepare("UPDATE menu_platillo SET estado = ? WHERE id_platillo = ?");
    $update->bind_param("si", $nuevo_estado, $platillo_id);
    $update->execute();

    $stmtIng->close();
    $update->close();
  }

  $stmt->close();
}

function actualizarEstadoCombosPorPlatillo(mysqli $conexion, int $id_platillo, int $usuario_id): void {
  $sql = "
    SELECT DISTINCT mc.id_combo
    FROM menu_combo_detalles mcd
    JOIN menu_combos mc ON mcd.combo_id = mc.id_combo
    WHERE mcd.platillo_id = ? AND mc.usuario_id = ?
  ";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ii", $id_platillo, $usuario_id);
  $stmt->execute();
  $res = $stmt->get_result();

  while ($combo = $res->fetch_assoc()) {
    $combo_id = $combo['id_combo'];

    $sqlPlatillos = "
      SELECT mp.estado
      FROM menu_combo_detalles mcd
      JOIN menu_platillo mp ON mcd.platillo_id = mp.id_platillo
      WHERE mcd.combo_id = ? AND mp.usuario_id = ?
    ";
    $check = $conexion->prepare($sqlPlatillos);
    $check->bind_param("ii", $combo_id, $usuario_id);
    $check->execute();
    $resultPlatillos = $check->get_result();

    $agotado = false;
    while ($row = $resultPlatillos->fetch_assoc()) {
      if ($row['estado'] !== 'disponible') {
        $agotado = true;
        break;
      }
    }

    $estado = $agotado ? 'agotado' : 'activo';
    $update = $conexion->prepare("UPDATE menu_combos SET estado = ? WHERE id_combo = ?");
    $update->bind_param("si", $estado, $combo_id);
    $update->execute();
    $update->close();
    $check->close();
  }

  $stmt->close();
}




?>