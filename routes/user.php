<?php
header('Content-Type: application/json; charset=utf-8');

// Opcional: evitar que los warnings se mezclen con el JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../app/Http/Controller/user_management/UserController.php';

$action = $_POST['action'] ?? '';

error_log("POST recibido en user.php: " . print_r($_POST, true));

$controller = new UserController();

switch ($action) {
    case 'register':
        $controller->registerUser();
        break;

    case 'verify':
        $controller->verifyUser();
        break;

    default:
        echo json_encode([
            'ok'    => false,
            'error' => 'Acción no válida'
        ]);
        break;
}