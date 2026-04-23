<?php

//Detalle de un post en el admin

$pageTitle = 'Detalle — ' . htmlspecialchars($post['title']);
require_once ROOT . '/views/layouts/header.php';
?>

<div class="detail-header">
  <a href="index.php?page=admin" class="btn btn-outline">← Volver al listado</a>
</div>

<article class="post-detail">

  <h1><?= htmlspecialchars($post['title']) ?></h1>

  <div class="post-meta">
    <span>✉️ <?= htmlspecialchars($post['email']) ?></span>
    <span>🕐 <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
  </div>

  <img
    src="<?= BASE_URL . '/' . htmlspecialchars($post['image']) ?>"
    alt="<?= htmlspecialchars($post['title']) ?>"
    class="detail-image"
  />

  <div class="post-content">
    <?= nl2br(htmlspecialchars($post['content'])) ?>
  </div>

  <!-- Eliminar desde el detalle -->
  <div class="detail-actions">
    <form
      id="deleteForm"
      method="POST"
      action="index.php?page=admin.delete"
      onsubmit="return confirmDelete(this, <?= $post['id'] ?>)"
    >
      <input type="hidden" name="id" value="<?= $post['id'] ?>" />
      <button type="submit" class="btn btn-danger">Eliminar publicación</button>
    </form>
  </div>

</article>

<?php require_once ROOT . '/views/layouts/footer.php'; ?>