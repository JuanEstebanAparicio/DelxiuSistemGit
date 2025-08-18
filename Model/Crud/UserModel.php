<?php
require_once __DIR__ . '/../Entity/Connection.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        // Llamamos al método estático de tu clase Conexion
        $this->db = Conexion::getConnection();
    }

    /**
     * Verifica si un correo ya está registrado
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * Crea un usuario nuevo (con estado pendiente por defecto)
     */
    public function createUser(string $name, string $email, string $password, string $restaurant): ?int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (user_name, user_email, user_password, user_restaurant, user_status, user_role, created_at)
             VALUES (?, ?, ?, ?, 'pending', 'user', NOW())"
        );
        if ($stmt->execute([$name, $email, $password, $restaurant])) {
            return (int) $this->db->lastInsertId();
        }
        return null;
    }

    /**
     * Guarda el código de verificación en la BD
     */
    public function saveVerificationCode(int $userId, string $code): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET verification_code = ?, 
                code_expires_at = DATE_ADD(NOW(), INTERVAL 10 MINUTE) 
            WHERE user_id = ?
        ");
        return $stmt->execute([$code, $userId]);
    }
}
