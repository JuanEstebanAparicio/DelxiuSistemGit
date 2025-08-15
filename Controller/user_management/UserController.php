<?php
require_once __DIR__ . '/../../Middleware/jsonResponse.php';
require_once __DIR__ . '/../../Model/Crud/UserModel.php';
require_once __DIR__ . '/../../Model/Crud/send_code.php'; // Aquí tienes EmailService

class UserController
{
    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return jsonResponse(['error' => 'Método no permitido'], 405);
        }

        // Recogemos los datos con los mismos nombres que en el form
        $name       = trim($_POST['user_name'] ?? '');
        $email      = trim($_POST['user_email'] ?? '');
        $restaurant = trim($_POST['user_restaurant'] ?? '');
        $password   = trim($_POST['user_password'] ?? '');

        // Validar que nada esté vacío
        if (empty($name) || empty($email) || empty($restaurant) || empty($password)) {
            return jsonResponse(['error' => 'Todos los campos son obligatorios'], 400);
        }

        $userModel = new UserModel();

        if ($userModel->emailExists($email)) {
            return jsonResponse(['error' => 'Este correo ya está registrado'], 409);
        }

        // Hashear la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario
        $userId = $userModel->createUser($name, $email, $hashedPassword, $restaurant);

        if (!$userId) {
            return jsonResponse(['error' => 'Ocurrió un error al registrar. Inténtalo más tarde.'], 500);
        }

        // Cargar config y enviar email de verificación
        $config = require __DIR__ . '/../../routes/config.php';
        $emailService = new EmailService($config);
        $verificationCode = $emailService->sendVerificationEmail($email, $name);

        if (!$verificationCode) {
            return jsonResponse(['error' => 'No se pudo enviar el correo de verificación.'], 500);
        }

        return jsonResponse([
            'success' => true,
            'message' => 'Registro exitoso. Revisa tu correo para verificar la cuenta.'
        ], 200);
    }
}
