<?php
require_once __DIR__ . '/../../Controller/conexion.php';


class Usuario {
    public function crearDesdeTemporal($temp) {
        global $pdo;

        $sql = "INSERT INTO usuarios (
            usuario_nombre, usuario_correo, usuario_clave, 
            usuario_restaurante, usuario_estado, usuario_rol, usuario_creacion
        ) VALUES (?, ?, ?, ?, 'activo', 'usuario', NOW())";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $temp['nombre_usuario'],
            $temp['correo'],
            $temp['clave'],
            $temp['nombre_restaurante']
        ]);
    }

    // Puedes agregar más métodos como:
    // buscarPorCorreo(), actualizarEstado(), eliminar(), etc.
}
