<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UPE-SPORT</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="hero-card text-center">
      <div class="brand mb-2">UPE-SPORT</div>
      <div class="text-muted small">Tu espacio para competencias y comunidad gamer</div>

      <h2>¡Bienvenido!</h2>
      <p class="tagline">Elegí tu rol para registrarte o iniciar sesión.</p>

      <div class="row g-3 mt-3">
        <div class="col-12 col-md-4 d-grid">
          <a href="jugador/crear-usuario.php" class="btn btn-lg">Jugador</a>
        </div>
        <div class="col-12 col-md-4 d-grid">
          <a href="organizador/crear-usuario.php" class="btn btn-lg">Organizador</a>
        </div>
        <div class="col-12 col-md-4 d-grid">
          <a href="auth/admin-login.php" class="btn btn-lg">Administrador</a>
        </div>
      </div>

      <div class="mt-4 text-muted small">
        ¿Ya tenés cuenta? <a href="auth/login.php">Iniciá sesión</a>
      </div>
    </div>
  </div>
</body>
</html>