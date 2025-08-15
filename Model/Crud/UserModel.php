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

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() !== false;
    }

    public function createUser(string $name, string $email, string $password, string $restaurant): ?int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (user_name, user_email, user_password, user_restaurant)
             VALUES (?, ?, ?, ?)"
        );
        if ($stmt->execute([$name, $email, $password, $restaurant])) {
            return (int) $this->db->lastInsertId();
        }
        return null;
    }
}

