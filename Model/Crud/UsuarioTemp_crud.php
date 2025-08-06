
<?php
// UsuarioTempCrud.php
require_once(__DIR__ . '/../Entity/UsuarioTemp.php');

class UsuarioTempCrud {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insertar(UsuarioTemp $temp): bool {
        $sql = "SELECT 1 FROM usuarios WHERE usuario_correo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$temp->getCorreo()]);

        if ($stmt->fetch()) return false;

        $sql = "INSERT INTO usuarios_temp (nombre, correo, nombre_restaurante, clave, codigo, creado_en)
                VALUES (?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE codigo = VALUES(codigo), creado_en = NOW()";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $temp->getNombre(),
            $temp->getCorreo(),
            $temp->getRestaurante(),
            $temp->getClave(),
            $temp->getCodigo()
        ]);
    }

    public function obtenerPorCorreo($correo): mixed {
        $sql = "SELECT * FROM usuarios_temp WHERE correo = ? AND creado_en >= NOW() - INTERVAL 30 MINUTE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    public function eliminar($correo): bool {
        $sql = "DELETE FROM usuarios_temp WHERE correo = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$correo]);
    }
}
?>
