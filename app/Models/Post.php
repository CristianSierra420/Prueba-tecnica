<?php
// Maneja las operaciones sobre la tabla posts

class Post
{
    private PDO $db;

    public function __construct()
    {
        // Conexión a la base de datos
        $this->db = getDB();
    }

    // Lista todas las publicaciones (más recientes primero)
    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM posts ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    // Obtiene un post por ID
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM posts WHERE id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crea un nuevo post
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO posts (title, content, image, email)
             VALUES (:title, :content, :image, :email)'
        );

        return $stmt->execute([
            ':title'   => $data['title'],
            ':content' => $data['content'],
            ':image'   => $data['image'],
            ':email'   => $data['email'],
        ]);
    }

    // Elimina un post por ID
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM posts WHERE id = ?'
        );
        return $stmt->execute([$id]);
    }
}