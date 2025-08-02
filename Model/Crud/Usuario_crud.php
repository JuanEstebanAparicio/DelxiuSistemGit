<?php
// UsuarioCrud.php
require_once(__DIR__ . '/../Entity/Usuario.php');

class UsuarioCrud {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function crearDesdeTemporal(Usuario $usuario): bool {
        $token = bin2hex(random_bytes(32));

        $sql = "INSERT INTO usuarios (usuario_nombre, usuario_correo, usuario_clave, usuario_restaurante, usuario_estado, usuario_rol, usuario_token, usuario_creacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $usuario->getNombre(),
            $usuario->getCorreo(),
            $usuario->getClave(),
            $usuario->getRestaurante(),
            $usuario->getEstado(),
            $usuario->getRol(),
            $token
        ]);
    }

    public function buscarPorCorreo($correo): mixed {
        $sql = "SELECT * FROM usuarios WHERE usuario_correo = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }
}

?>