<?php
require_once(__DIR__ . '/conexion.php');


class Usuario {
    public function crearDesdeTemporal($temp) {
        global $pdo;

        $token = bin2hex(random_bytes(32)); // Token seguro de 64 caracteres

        $sql = "INSERT INTO usuarios (
            usuario_nombre, usuario_correo, usuario_clave, 
            usuario_restaurante, usuario_estado, usuario_rol, 
            usuario_token, usuario_creacion
        ) VALUES (?, ?, ?, ?, 'activo', 'usuario', ?, NOW())";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $temp['nombre_usuario'],
            $temp['correo'],
            $temp['clave'],
            $temp['nombre_restaurante'],
            $token
        ]);
    }
public function buscarPorCorreo($correo) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario_correo = ? LIMIT 1");
    $stmt->execute([$correo]);
    return $stmt->fetch();
}


}
