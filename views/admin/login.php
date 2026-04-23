<?php

$pageTitle = 'Iniciar sesión';
$isLoginPage = true;
require_once ROOT . '/views/layouts/header.php';
?>

<div class="auth-wrapper">
  <div class="auth-card">

    <h1>Panel de administración</h1>
    <p class="auth-subtitle">Ingresa tus credenciales para continuar</p>

    <?php if (!empty($error)): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login">

      <div class="field">
        <label for="username">Usuario</label>
        <input
          type="text"
          id="username"
          name="username"
          placeholder="admin"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
          required
          autofocus
        />
      </div>

      <div class="field">
        <label for="password">Contraseña</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="••••••••"
          required
        />
      </div>

      <button type="submit" class="btn btn-primary btn-block">
        Ingresar
      </button>

    </form>

  </div>
</div>

<?php require_once ROOT . '/views/layouts/footer.php'; ?>