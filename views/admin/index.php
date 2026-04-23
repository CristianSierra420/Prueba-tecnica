<?php
// Listado de posts en el admin

$pageTitle = 'Administrador — Publicaciones';
require_once ROOT . '/views/layouts/header.php';
?>

<div class="admin-header">
  <h1>Publicaciones</h1>
  <span class="badge"><?= count($posts) ?> en total</span>
</div>

<?php if (empty($posts)): ?>
  <div class="empty-state">
    <p>No hay publicaciones todavía.</p>
    <a href="index.php?page=blog" class="btn btn-primary">Ver el blog</a>
  </div>

<?php else: ?>
  <div class="table-wrapper">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Correo</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $post): ?>
          <tr>
            <td><?= $post['id'] ?></td>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= htmlspecialchars($post['email']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></td>
            <td class="actions">

              <!-- Botón ver detalle -->
              <a
                href="index.php?page=admin.show&id=<?= $post['id'] ?>"
                class="btn btn-outline btn-sm"
              >Ver</a>

              <!-- Formulario de eliminar (necesita POST por seguridad) -->
              <form
                method="POST"
                action="index.php?page=admin.delete"
                style="display:inline"
                onsubmit="return confirm('¿Seguro que quieres eliminar esta publicación?')"
              >
                <input type="hidden" name="id" value="<?= $post['id'] ?>" />
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
              </form>

            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once ROOT . '/views/layouts/footer.php'; ?>