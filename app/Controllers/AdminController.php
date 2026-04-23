<?php
// app/Controllers/AdminController.php
// Controla el panel de administración de publicaciones

require_once ROOT . '/app/Models/Post.php';

class AdminController
{
    private Post $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    // Lista todas las publicaciones
    public function index(): void
    {
        $posts = $this->postModel->getAll();
        require_once ROOT . '/views/admin/index.php';
    }

    // Muestra el detalle de un post
    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: index.php?page=admin');
            exit;
        }

        $post = $this->postModel->getById($id);

        // Si no existe, vuelve al listado
        if (!$post) {
            header('Location: index.php?page=admin');
            exit;
        }

        require_once ROOT . '/views/admin/show.php';
    }

    // Elimina un post y su imagen
    public function delete(): void
    {
        // Solo permite eliminar por POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $post = $this->postModel->getById($id);

            if ($post) {
                // Borra la imagen del servidor
                $imagePath = ROOT . '/public/' . $post['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Borra el registro en BD
                $this->postModel->delete($id);
            }
        }

        header('Location: index.php?page=admin');
        exit;
    }
}