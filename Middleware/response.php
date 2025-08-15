<?php
// Middleware/response.php

function jsonResponse(string $status, string $code, string $message, array $data = [], int $httpCode = 200): void {
    // Cabeceras
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpCode);
    }

    // Cuerpo
    $payload = array_merge([
        'status'  => $status,
        'code'    => $code,
        'message' => $message
    ], $data);

    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}
