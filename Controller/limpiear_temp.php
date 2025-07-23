<?php
// archivo: cron/limpiar_temp.php
require_once '../Controller/conexion.php';

try {
    $sql1 = "DELETE FROM usuarios_temp WHERE creado_en < NOW() - INTERVAL 30 MINUTE";
    $sql2 = "DELETE FROM usuarios WHERE usuario_estado = 'inactivo' AND usuario_creacion < NOW() - INTERVAL 1 HOUR"; // opcional

    $pdo->exec($sql1);
    echo "Usuarios temporales eliminados.\n";

    // $pdo->exec($sql2);
    // echo "Usuarios inactivos eliminados.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    http_response_code(500);
}
