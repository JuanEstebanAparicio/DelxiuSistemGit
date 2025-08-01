<?php
// archivo: models/UsuarioTemp.php
require_once(__DIR__ . '/conexion.php');


class UsuarioTemp {
    public function insertar($nombre, $correo, $restaurante, $clave, $codigo) {
        global $pdo;

        $sql = "SELECT 1 FROM usuarios WHERE usuario_correo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$correo]);
        if ($stmt->fetch()) return false; // ya existe en usuarios

        $sql = "INSERT INTO usuarios_temp (nombre_usuario, correo, nombre_restaurante, clave, codigo, creado_en)
                VALUES (?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE codigo = VALUES(codigo), creado_en = NOW()";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$nombre, $correo, $restaurante, $clave, $codigo]);
    }

    public function obtenerPorCorreo($correo) {
        global $pdo;

        $sql = "SELECT * FROM usuarios_temp WHERE correo = ? AND creado_en >= NOW() - INTERVAL 30 MINUTE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    public function eliminar($correo) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM usuarios_temp WHERE correo = ?");
        return $stmt->execute([$correo]);
    }
}

// archivo: models/Usuario.php
require_once(__DIR__ . '/conexion.php');


class UsuarioController {
    public function crearDesdeTemporal($temp) {
        global $pdo;

        $sql = "INSERT INTO usuarios (usuario_nombre, usuario_correo, usuario_clave, usuario_restaurante, usuario_estado, usuario_rol, usuario_creacion)
                VALUES (?, ?, ?, ?, 'activo', 'usuario', NOW())";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $temp['nombre_usuario'],
            $temp['correo'],
            $temp['clave'],
            $temp['nombre_restaurante']
        ]);
    }
}
