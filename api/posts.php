<?php
// ============================================================
//  GET    /api/posts.php          → lista todos los posts
//  GET    /api/posts.php?id=5     → detalle de un post
//  POST   /api/posts.php          → crear un post (multipart/form-data)
//  DELETE /api/posts.php?id=5     → eliminar un post
// ============================================================

require_once __DIR__ . '/helpers.php';
require_once ROOT . '/app/Models/Post.php';

basicAuth();

$method    = $_SERVER['REQUEST_METHOD'];
$postModel = new Post();
$id        = isset($_GET['id']) ? (int) $_GET['id'] : null;

//  GET — listar todos o traer uno por ID
if ($method === 'GET') {

    if ($id) {
        // GET ?id=5 → detalle de un post
        $post = $postModel->getById($id);

        if (!$post) {
            json(['error' => 'Publicación no encontrada.'], 404);
        }

        json(['success' => true, 'data' => $post]);

    } else {
        // GET sin id → todos los posts
        $posts = $postModel->getAll();
        json(['success' => true, 'data' => $posts]);
    }
}

//  POST — crear un post
//  Acepta multipart/form-data para poder subir imagen
if ($method === 'POST') {

    $errors = [];

    $title   = trim($_POST['title']   ?? '');
    $content = trim($_POST['content'] ?? '');
    $email   = trim($_POST['email']   ?? '');

    if (empty($title))   $errors[] = 'El campo title es obligatorio.';
    if (empty($content)) $errors[] = 'El campo content es obligatorio.';
    if (empty($email))   $errors[] = 'El campo email es obligatorio.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
                         $errors[] = 'El email no tiene formato válido.';

    if (empty($_FILES['image']['name'])) {
        $errors[] = 'La imagen es obligatoria.';
    }

    if (!empty($errors)) {
        json(['success' => false, 'errors' => $errors], 422);
    }

    // Procesar imagen
    $uploadDir    = ROOT . '/public/uploads/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize      = 2 * 1024 * 1024;

    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes) || $_FILES['image']['size'] > $maxSize) {
        json(['success' => false, 'errors' => ['Imagen inválida. Solo JPG, PNG o GIF de máx. 2MB.']], 422);
    }

    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename  = uniqid('img_', true) . '.' . strtolower($extension);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);

    $created = $postModel->create([
        'title'   => $title,
        'content' => $content,
        'image'   => 'uploads/' . $filename,
        'email'   => $email,
    ]);

    if ($created) {
        json(['success' => true, 'message' => 'Publicación creada correctamente.'], 201);
    } else {
        json(['success' => false, 'errors' => ['Error al guardar en la base de datos.']], 500);
    }
}

//  DELETE — eliminar un post por ID
if ($method === 'DELETE') {

    if (!$id) {
        json(['error' => 'Se requiere el parámetro id.'], 422);
    }

    $post = $postModel->getById($id);

    if (!$post) {
        json(['error' => 'Publicación no encontrada.'], 404);
    }

    // Eliminamos la imagen del servidor
    $imagePath = ROOT . '/public/' . $post['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $postModel->delete($id);

    json(['success' => true, 'message' => 'Publicación eliminada correctamente.']);
}

// Si el método no es ninguno de los anteriores
json(['error' => 'Método no permitido.'], 405);