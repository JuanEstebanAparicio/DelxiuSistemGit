<?php
require_once __DIR__ . '/../../Model/Entity/Connection.php';
require_once __DIR__ . '/../../Model/Entity/User.php';
require_once __DIR__ . '/../../Model/Crud/UserModel.php';
require_once __DIR__ . '/../../Model/Crud/send_code.php';

class UserController {
    private $db;
    private $userModel;
    private $emailService;

    public function __construct() {
        // Conexión a BD
        $this->db = Connection::getConnection();
        $this->userModel = new UserModel($this->db);

        // Configuración del correo
        $config = require __DIR__ . '/../../routes/config.php';
        $this->emailService = new EmailService($config);
    }

    // Registro de usuario
    public function registerUser() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            // Datos del formulario
            $name       = $_POST['user_name']       ?? null;
            $email      = $_POST['user_email']      ?? null;
            $restaurant = $_POST['user_restaurant'] ?? null;
            $password   = $_POST['user_password']   ?? null;

            if (!$name || !$email || !$restaurant || !$password) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Todos los campos son obligatorios']);
                return;
            }

            // Crear objeto usuario
            $user = new User($name, $email, $password, $restaurant);

            // Guardar en BD
            $userId = $this->userModel->createUser($user);

            // 🚀 Enviar correo y obtener el código
            $sentCode = $this->emailService->sendVerificationEmail($email, $name);

            if (!$sentCode) {
                http_response_code(500);
                echo json_encode(['ok' => false, 'error' => 'No se pudo enviar el correo de verificación']);
                return;
            }

            // 🚀 Guardar en DB el mismo código que se envió
            try {
            $this->userModel->saveVerificationCode($userId, $sentCode);
            } catch (Exception $e) {
              echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
               return;
            }
            // ✅ Respuesta exitosa con el correo
            echo json_encode([
                'ok'     => true,
                'message'=> 'Usuario creado, código enviado',
                'email'  => $email
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'ok'    => false,
                'error' => $e->getMessage(),
                'line'  => $e->getLine()
            ]);
        }
    }
    
    

    // Verificar código de usuario
    public function verifyUser() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $email = $_POST['user_email'] ?? null;
            $code  = $_POST['verification_code'] ?? null;

            if (!$email || !$code) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Correo y código son obligatorios']);
                return;
            }

            if ($this->userModel->verifyCode($email, $code)) {
                $this->userModel->activateUser($email);
                echo json_encode(['ok' => true, 'message' => 'Usuario activado con éxito']);
            } else {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Código inválido o expirado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function recoverPassword() {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $email = $_POST['user_email'] ?? null;

        if (!$email) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'El correo es obligatorio']);
            return;
        }

        // Verificar si existe
        $stmt = $this->db->prepare("SELECT user_id, user_name FROM users WHERE user_email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'No existe una cuenta con ese correo']);
            return;
        }

        // Generar token y guardar
        $token = bin2hex(random_bytes(16));
        $this->userModel->saveRecoveryToken($email, $token);

        // Enviar correo
        $sent = $this->emailService->sendRecoveryEmail($email, $user['user_name'], $token);

        if (!$sent) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudo enviar el correo de recuperación']);
            return;
        }

        echo json_encode(['ok' => true, 'message' => 'Se ha enviado un enlace de recuperación a tu correo']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }
}


}




// 🚀 Router simple
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $controller = new UserController();

    switch ($action) {
    case 'register':
        $controller->registerUser();
        break;

    case 'verify':
        $controller->verifyUser();
        break;

    case 'recover':
        $controller->recoverPassword();
        break;

    default:
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'error' => 'Acción no válida']);
        break;
}





}
