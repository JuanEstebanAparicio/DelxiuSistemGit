<?php
require_once __DIR__ . '/../Entity/User.php';

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Crear usuario en estado "pending"
    public function createUser(User $user): int {
        $sql = "INSERT INTO users 
                (user_name, user_email, user_password, user_restaurant, user_status, user_role, created_at) 
                VALUES (:name, :email, :password, :restaurant, :status, :role, :created_at)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name'       => $user->getUserName(),
            ':email'      => $user->getUserEmail(),
            ':password'   => password_hash($user->getUserPassword(), PASSWORD_BCRYPT),
            ':restaurant' => $user->getUserRestaurant(),
            ':status'     => $user->getUserStatus(),
            ':role'       => $user->getUserRole(),
            ':created_at' => $user->getCreatedAt()
        ]);

        return (int)$this->db->lastInsertId();
    }

    // Guardar código de verificación
    public function saveVerificationCode(int $userId, string $code): void {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $sql = "UPDATE users SET verification_code = :code, code_expires_at = :expires WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':code'   => $code,
            ':expires'=> $expiresAt,
            ':id'     => $userId
        ]);
    }

    // Verificar código ingresado
    public function verifyCode(string $email, string $code): bool {
        $sql = "SELECT user_id, verification_code, code_expires_at 
                FROM users 
                WHERE user_email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) return false;

        // Comparar código y verificar si aún no expiró
        if ($row['verification_code'] === $code && strtotime($row['code_expires_at']) > time()) {
            return true;
        }
        return false;
    }

    // Activar usuario
    public function activateUser(string $email): void {
        $sql = "UPDATE users 
                SET user_status = 'active', verification_code = NULL, code_expires_at = NULL 
                WHERE user_email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
    }
}
