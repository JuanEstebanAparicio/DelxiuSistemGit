<?php
declare(strict_types=1);

// 1) Configuración de errores (antes de todo)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
error_reporting(E_ALL);

$logDir = __DIR__ . '/../storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}
ini_set('error_log', $logDir . '/php_errors.log');

// 2) Handlers globales
set_error_handler(function (int $severity, string $message, string $file, int $line) {
    // Convierte notices/warnings en excepciones para atraparlas abajo
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    $payload = [
        'ok'    => false,
        'type'  => get_class($e),
        'error' => $e->getMessage(),
        'file'  => $e->getFile(),
        'line'  => $e->getLine(),
    ];
    error_log('[EXCEPTION] ' . json_encode($payload, JSON_UNESCAPED_UNICODE));
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
});

register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        $payload = [
            'ok'    => false,
            'type'  => 'FatalError',
            'error' => $e['message'],
            'file'  => $e['file'],
            'line'  => $e['line'],
        ];
        error_log('[FATAL] ' . json_encode($payload, JSON_UNESCAPED_UNICODE));
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
});

// 3) Puntos de control rápidos (elimina luego)
error_log('[DEBUG] routes/register.php START');

// 4) Carga del controlador
require_once __DIR__ . '/../Controller/user_management/UserController.php';

// 5) Ejecución
$controller = new UserController(); // ajusta namespace si aplica
$controller->registerUser();