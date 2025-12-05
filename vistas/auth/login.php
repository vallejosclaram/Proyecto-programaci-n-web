<?php 
  // Por si querés mostrar algo en futuro
  $errorCredenciales = $errorCredenciales ?? false;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - UPE-SPORT</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="style.css">
  <script src="login.js"></script>
</head>

<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card">
      <div class="text-center mb-4">
        <h2>Iniciar Sesión</h2>
      </div>

      <!-- SOLO SI ALGÚN DÍA USÁS FORM POST NORMAL -->
      <?php if ($errorCredenciales): ?>
        <div class="alert alert-danger">Credenciales incorrectas</div>
      <?php endif ?>

      <form id="loginForm" class="text-start">
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn w-100 btn-login">Entrar</button>
      </form>
      <div class="mt-3 text-center small text-muted">
        ¿Sos nuevo? 
      </div>
      <div class="mt-3 text-center small text-muted">
         <a href="../Jugador/crear-usuario.php">Registrate como jugador</a>
      </div>
      <div class="mt-3 text-center small text-muted">
        <a href="../Organizador/crear-usuario.php">Registrate como organizador</a>
      </div>
      <div class="mt-3 text-center small text-muted">
        <a href="../inicio.php">Volver al inicio</a>
      </div>
    </div>
  </div>
</body>
</html>
