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
        $this->userModel->saveVerificationCode($userId, $sentCode);

        // ✅ Respuesta exitosa
        echo json_encode(['ok' => true, 'message' => 'Usuario creado, código enviado']);
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
}