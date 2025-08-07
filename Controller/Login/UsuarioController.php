<?php
require_once '../Model/Crud/Usuario_crud.php';

class UsuarioController {
    public function registrarManual($nombre, $correo, $clave, $restaurante) {
        $usuario = new Usuario_crud();

        return $usuario->crearDesdeTemporal([
            'nombre_usuario' => $nombre,
            'correo' => $correo,
            'clave' => password_hash($clave, PASSWORD_DEFAULT),
            'nombre_restaurante' => $restaurante
        ]);
    }
}

