<?php
// Controla el login y logout del administrador

require_once ROOT . '/app/Models/User.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        // Modelo de usuarios
        $this->userModel = new User();
    }

    // Muestra el login o procesa el formulario
    public function login(): void
    {
        // Si ya inició sesión, va al panel
        if (!empty($_SESSION['user_id'])) {
            header('Location: index.php?page=admin');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validación básica
            if (empty($username) || empty($password)) {
                $error = 'Usuario y contraseña son obligatorios.';
            } else {
                // Busca el usuario en BD
                $user = $this->userModel->findByUsername($username);

                // Verifica la contraseña con hash
                if ($user && password_verify($password, $user['password'])) {

                    // Guarda datos en sesión
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    header('Location: index.php?page=admin');
                    exit;

                } else {
                    $error = 'Usuario o contraseña incorrectos.';
                }
            }
        }

        // Carga la vista del login
        require_once ROOT . '/views/admin/login.php';
    }

    // Cierra la sesión
    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}