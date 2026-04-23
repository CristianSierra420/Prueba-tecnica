<?php
// Punto de entrada: decide qué controlador ejecutar

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/database.php';

session_start();

// Página solicitada (por defecto: login)
$page = $_GET['page'] ?? 'login';

switch ($page) {

    // Público
    case 'blog':
        require_once ROOT . '/app/Controllers/PostController.php';
        (new PostController())->index();
        break;

    // Autenticación
    case 'login':
        require_once ROOT . '/app/Controllers/AuthController.php';
        (new AuthController())->login();
        break;

    case 'logout':
        require_once ROOT . '/app/Controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // Admin (requiere sesión)
    case 'admin':
        requireAuth();
        require_once ROOT . '/app/Controllers/AdminController.php';
        (new AdminController())->index();
        break;

    case 'admin.show':
        requireAuth();
        require_once ROOT . '/app/Controllers/AdminController.php';
        (new AdminController())->show();
        break;

    case 'admin.delete':
        requireAuth();
        require_once ROOT . '/app/Controllers/AdminController.php';
        (new AdminController())->delete();
        break;

    // Ruta no válida
    default:
        http_response_code(404);
        echo '<h1>404 — Página no encontrada</h1>';
        break;
}

// Valida si el usuario está autenticado
function requireAuth(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
}