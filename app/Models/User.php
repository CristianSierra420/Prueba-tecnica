<?php
// Maneja consultas relacionadas con usuarios

class User
{
    private PDO $db;

    public function __construct()
    {
        // Conexión a la base de datos
        $this->db = getDB();
    }

    // Busca un usuario por username (para login)
    public function findByUsername(string $username): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE username = ?'
        );

        $stmt->execute([$username]);

        return $stmt->fetch();
    }
}