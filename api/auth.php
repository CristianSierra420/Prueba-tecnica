<?php
//  api/auth.php  —  Endpoint público de autenticación
//
//  POST /api/auth.php
//  Body JSON: { "username": "admin", "password": "Admin1234" }
//
//  Respuesta exitosa:
//  { "success": true, "message": "Autenticación correcta", "user": "admin" }

require_once __DIR__ . '/helpers.php';

allowMethods(['POST']);

// Leemos el body JSON que envía el cliente
$body     = file_get_contents('php://input');
$data     = json_decode($body, true);

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($password)) {
    json(['error' => 'username y password son obligatorios.'], 422);
}

require_once ROOT . '/app/Models/User.php';
$userModel = new User();
$user      = $userModel->findByUsername($username);

if (!$user || !password_verify($password, $user['password'])) {
    json(['error' => 'Credenciales incorrectas.'], 401);
}

json([
    'success' => true,
    'message' => 'Autenticación correcta.',
    'user'    => $user['username'],
]);