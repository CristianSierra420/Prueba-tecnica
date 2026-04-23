<?php
//Página pública de la prueba técnica: muestra el formulario para crear posts y la lista de publicaciones existentes
$pageTitle = 'Prueba tecnica PHP';
require_once ROOT . '/views/layouts/header.php';
?>

<section class="blog-layout">

  <!-- ======================================================
       COLUMNA IZQUIERDA: Formulario nueva publicación
  ====================================================== -->
  <aside class="form-panel">
    <h2>Nueva publicación</h2>

    <!-- Mensajes de éxito / error (los maneja el JS) -->
    <div id="form-message" class="alert" style="display:none"></div>

    <form id="post-form" novalidate>

      <div class="field">
        <label for="title">Título *</label>
        <input type="text" id="title" name="title" placeholder="Escribe un título..." />
        <span class="field-error" id="err-title"></span>
      </div>

      <div class="field">
        <label for="content">Contenido *</label>
        <textarea id="content" name="content" rows="5" placeholder="¿Qué quieres compartir?"></textarea>
        <span class="field-error" id="err-content"></span>
      </div>

      <div class="field">
        <label for="image">Imagen *</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif" />
        <small>JPG, PNG o GIF — máx. 2 MB</small>
        <span class="field-error" id="err-image"></span>
      </div>

      <div class="field">
        <label for="email">Correo electrónico *</label>
        <input type="email" id="email" name="email" placeholder="tucorreo@ejemplo.com" />
        <span class="field-error" id="err-email"></span>
      </div>

      <!-- Captcha de Google reCAPTCHA v2 -->
      <div class="field">
        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
        <span class="field-error" id="err-captcha"></span>
      </div>

      <button type="submit" class="btn btn-primary" id="submit-btn">
        Publicar
      </button>

    </form>
  </aside>

  <!-- ======================================================
       COLUMNA DERECHA: Listado de publicaciones
  ====================================================== -->
  <section class="posts-list" id="posts-container">
    <h2>Publicaciones</h2>

    <?php if (empty($posts)): ?>
      <p class="empty-state">Todavía no hay publicaciones. ¡Sé el primero!</p>
    <?php else: ?>
      <?php foreach ($posts as $post): ?>
        <article class="post-card">
          <img
            src="<?= BASE_URL . '/' . htmlspecialchars($post['image']) ?>"
            alt="<?= htmlspecialchars($post['title']) ?>"
            class="post-image"
          />
          <div class="post-body">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p class="post-meta">
              ✉️ <?= htmlspecialchars($post['email']) ?> &nbsp;·&nbsp;
              🕐 <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
            </p>
            <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>

  </section>

</section>

<!-- reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Script AJAX del formulario -->
<script>
document.getElementById('post-form').addEventListener('submit', async function(e) {
  e.preventDefault(); // Evita que la página se recargue

  // Limpiar errores anteriores
  clearErrors();

  // Validación del lado del cliente antes de enviar
  let valid = true;

  if (!this.title.value.trim()) {
    showError('err-title', 'El título es obligatorio.');
    valid = false;
  }
  if (!this.content.value.trim()) {
    showError('err-content', 'El contenido es obligatorio.');
    valid = false;
  }
  if (!this.image.files.length) {
    showError('err-image', 'La imagen es obligatoria.');
    valid = false;
  }
  if (!this.email.value.trim()) {
    showError('err-email', 'El correo es obligatorio.');
    valid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.value)) {
    showError('err-email', 'El correo no tiene un formato válido.');
    valid = false;
  }

  // Validar captcha
  const captchaResponse = grecaptcha.getResponse();
  if (!captchaResponse) {
    showError('err-captcha', 'Por favor confirma que no eres un bot.');
    valid = false;
  }

  if (!valid) return;

  // Deshabilitar botón mientras se envía
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.textContent = 'Publicando...';

  // Armamos el FormData con todos los campos incluida la imagen
  const formData = new FormData(this);

  try {
    // Enviamos al mismo URL del blog via POST
    const response = await fetch('index.php?page=blog', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (data.success) {
      // Mostrar mensaje de éxito
      showMessage('success', '¡Publicación creada con éxito! La página se actualizará.');
      this.reset();
      grecaptcha.reset();

      // Recargar la lista de posts después de 1.5 segundos
      setTimeout(() => location.reload(), 1500);
    } else {
      // Mostrar errores del servidor
      data.errors.forEach(err => {
        showMessage('error', err);
      });
    }

  } catch (err) {
    showMessage('error', 'Error de conexión. Intenta de nuevo.');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Publicar';
  }
});

function showError(fieldId, message) {
  const el = document.getElementById(fieldId);
  if (el) { el.textContent = message; el.style.display = 'block'; }
}

function clearErrors() {
  document.querySelectorAll('.field-error').forEach(el => {
    el.textContent = '';
    el.style.display = 'none';
  });
  const msg = document.getElementById('form-message');
  msg.style.display = 'none';
}

function showMessage(type, text) {
  const msg = document.getElementById('form-message');
  msg.className = 'alert alert-' + type;
  msg.textContent = text;
  msg.style.display = 'block';
}
</script>

<?php require_once ROOT . '/views/layouts/footer.php'; ?>