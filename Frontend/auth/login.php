<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - UPE-SPORT</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card">
      <div class="text-center mb-4">
        <h2>Iniciar Sesión</h2>
      </div>

      <form id="loginForm" action="../../Backend/login/login.php" method="POST" class="text-start" novalidate>
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" placeholder="ejemplo@correo.com" required>
          <div class="invalid-feedback d-none" id="validemail">Correo incorrecto o no registrado</div>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" placeholder="********" required>
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="showPass">
            <label class="form-check-label" for="showPass">Mostrar contraseña</label>
          </div>
          <div class="invalid-feedback d-none" id="validpass">Contraseña incorrecta</div>
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
    
    
  <div class="modal fade" id="loginExitoso" tabindex="-1" aria-labelledby="loginExitosoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="loginExitosoLabel">🎮 ¡Bienvenido a UPE-SPORT!</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          <p id="mensajeBienvenida">Inicio de sesión exitoso.</p>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-primary" id="irDashboard">Ir al panel</button>
        </div>
      </div>
    </div>
  </div>


  <script src="login.js"></script>
</body>
</html>
