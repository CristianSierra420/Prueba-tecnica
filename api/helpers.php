<?php
// Funciones comunes para la API

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config/database.php';

// Respuesta en JSON con código HTTP
function json(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Valida autenticación Basic
function basicAuth(): void
{
    // Obtiene el header Authorization
    $authHeader = $_SERVER['HTTP_AUTHORIZATION']
               ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
               ?? '';

    if (!str_starts_with($authHeader, 'Basic ')) {
        header('WWW-Authenticate: Basic realm="Blog API"');
        json(['error' => 'Se requiere autenticación.'], 401);
    }

    // Decodifica usuario y contraseña
    $encoded = substr($authHeader, 6);
    $decoded = base64_decode($encoded);
    $parts   = explode(':', $decoded, 2);

    if (count($parts) !== 2) {
        json(['error' => 'Credenciales inválidas.'], 401);
    }

    [$username, $password] = $parts;

    // Busca el usuario
    require_once ROOT . '/app/Models/User.php';
    $userModel = new User();
    $user = $userModel->findByUsername($username);

    // Verifica credenciales
    if (!$user || !password_verify($password, $user['password'])) {
        header('WWW-Authenticate: Basic realm="Blog API"');
        json(['error' => 'Credenciales incorrectas.'], 401);
    }
}

// Valida métodos HTTP permitidos
function allowMethods(array $methods): void
{
    if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
        json(['error' => 'Método no permitido.'], 405);
    }
}