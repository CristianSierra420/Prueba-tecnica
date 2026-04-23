<?php
// Maneja la vista pública del blog y la creación de posts

require_once ROOT . '/app/Models/Post.php';

class PostController
{
    private Post $postModel;
    private string $uploadDir;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->uploadDir = ROOT . '/public/uploads/';
    }

    // Muestra los posts o procesa el formulario (AJAX)
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        $posts = $this->postModel->getAll();
        require_once ROOT . '/views/blog/index.php';
    }

    // Valida datos, sube imagen y guarda el post
    private function store(): void
    {
        header('Content-Type: application/json');

        $errors = [];

        // Datos del formulario
        $title   = trim($_POST['title']   ?? '');
        $content = trim($_POST['content'] ?? '');
        $email   = trim($_POST['email']   ?? '');

        // Validación básica
        if (empty($title))   $errors[] = 'El título es obligatorio.';
        if (empty($content)) $errors[] = 'El contenido es obligatorio.';
        if (empty($email))   $errors[] = 'El correo es obligatorio.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo no es válido.';
        }

        // Validar imagen
        if (empty($_FILES['image']['name'])) {
            $errors[] = 'La imagen es obligatoria.';
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }

        // Subir imagen
        $imagePath = $this->uploadImage($_FILES['image']);

        if ($imagePath === false) {
            echo json_encode([
                'success' => false,
                'errors'  => ['Solo imágenes JPG, PNG o GIF (máx 2MB).']
            ]);
            return;
        }

        // Guardar en BD
        $created = $this->postModel->create([
            'title'   => $title,
            'content' => $content,
            'image'   => $imagePath,
            'email'   => $email,
        ]);

        echo json_encode(
            $created
                ? ['success' => true, 'message' => 'Publicación creada']
                : ['success' => false, 'errors' => ['Error al guardar']]
        );
    }

    // Valida y mueve la imagen a /uploads
    private function uploadImage(array $file): string|false
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024;

        // Validar tipo real del archivo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) return false;
        if ($file['size'] > $maxSize) return false;

        // Nombre único para evitar duplicados
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename  = uniqid('img_', true) . '.' . strtolower($extension);
        $destPath  = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) return false;

        return 'uploads/' . $filename;
    }
}