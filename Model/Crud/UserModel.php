<?php
error_log("Cargando UserModel desde: " . __FILE__);
require_once __DIR__ . '/../Entity/User.php';

class UserModel {
    
        private $db;

    public function __construct($db) {
        $this->db = $db;
    }

public function createUser(User $user): int {
    // ✅ 1. Tomamos la contraseña en claro desde el objeto
    $rawPassword = $user->getUserPassword() ?? '';

    // Validar longitud mínima
    if (strlen($rawPassword) < 6) {
        throw new Exception('La contraseña debe tener al menos 6 caracteres.');
    }

    // ✅ 2. Hasheamos la contraseña antes de guardarla
    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

    // ✅ 3. Revisar si el correo ya existe
    $sql = "SELECT user_id, user_status FROM users WHERE user_email = :email LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':email' => $user->getUserEmail()]);
    $row = $stmt->fetch();

    if ($row) {
        // Caso 1: Ya existe pero está pendiente → sobrescribir
        if ($row['user_status'] === 'pending') {
            $update = "UPDATE users 
                       SET user_name = :name,
                           user_password = :password,
                           user_restaurant = :restaurant,
                           created_at = NOW(),
                           verification_code = NULL,
                           code_expires_at = NULL
                       WHERE user_id = :id";
            $stmt = $this->db->prepare($update);
            $stmt->execute([
                ':name'       => $user->getUserName(),
                ':password'   => $hashedPassword, // 🔒 Guardamos el hash
                ':restaurant' => $user->getUserRestaurant(),
                ':id'         => $row['user_id']
            ]);
            return $row['user_id']; // devolvemos el mismo ID
        }

        // Caso 2: Ya existe y está activo → error
        throw new Exception("Este correo ya tiene una cuenta registrada.");
    }

    // ✅ 4. Si no existe, insertar normalmente
    $sql = "INSERT INTO users (user_name, user_email, user_password, user_restaurant, user_status, user_role, created_at) 
            VALUES (:name, :email, :password, :restaurant, :status, :role, :created)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':name'       => $user->getUserName(),
        ':email'      => $user->getUserEmail(),
        ':password'   => $hashedPassword, // 🔒 Guardamos el hash
        ':restaurant' => $user->getUserRestaurant(),
        ':status'     => $user->getUserStatus(),
        ':role'       => $user->getUserRole(),
        ':created'    => $user->getCreatedAt()
    ]);

    return $this->db->lastInsertId();
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
    public function activateUser(string $email): bool {
    $sql = "UPDATE users 
            SET user_status = 'active', verification_code = NULL, code_expires_at = NULL 
            WHERE user_email = :email";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':email' => $email]);
}

// Guardar token de recuperación
public function saveRecoveryToken(string $email, string $token): bool {
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $sql = "UPDATE users 
            SET recovery_token = :token, recovery_expires_at = :expires 
            WHERE user_email = :email";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':token'   => $token,
        ':expires' => $expiresAt,
        ':email'   => $email
    ]);
}

// Verificar token de recuperación
public function verifyRecoveryToken(string $token): ?string {
    $sql = "SELECT user_email 
            FROM users 
            WHERE recovery_token = :token AND recovery_expires_at > NOW()";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':token' => $token]);
    $row = $stmt->fetch();
    return $row ? $row['user_email'] : null;
}

// Actualizar contraseña y limpiar token
public function updatePassword(string $email, string $hashedPassword): bool {
    $sql = "UPDATE users 
            SET user_password = :pass, recovery_token = NULL, recovery_expires_at = NULL 
            WHERE user_email = :email";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':pass'  => $hashedPassword,
        ':email' => $email
    ]);
}





}
