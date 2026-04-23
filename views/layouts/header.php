<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle ?? 'Blog') ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css" />
</head>
<body>

<header class="site-header">
  <div class="container">
    <?php if (!empty($isLoginPage)): ?>
      <span class="logo">✏️ Mi Blog</span>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/index.php?page=blog" class="logo">✏️ Mi Blog</a>
    <?php endif; ?>

    <nav>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <span class="nav-user">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
        <a href="<?= BASE_URL ?>/index.php?page=admin" class="btn btn-outline">Panel admin</a>
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="btn btn-danger">Cerrar sesión</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">