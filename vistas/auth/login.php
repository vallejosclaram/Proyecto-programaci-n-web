<?php
require_once(__DIR__ . '/../includes/globals.php');

if (isset($_SESSION['usuario'])) {
    header('Location: ' . $dirBase . '/index.php');
    die;
}

// CHEQUEA SI ES LA PRIMERA LLAMADO
$loginStatus = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once(__DIR__ . '/../includes/classes/conexion_db.php');

    $msg = '';
    $varClass = '';
    // php -r "echo password_hash('pass123', PASSWORD_BCRYPT).PHP_EOL;"
    // php -r "echo password_hash('pass456', PASSWORD_BCRYPT).PHP_EOL;"
    $queryGetUser = 'SELECT * FROM usuarios WHERE email = :email';
    $resultadoQuery = $conn->prepare($queryGetUser);
    $resultadoQuery->bindParam(':email', $_POST['email']);
    $resultadoQuery->execute();
    if ($row = $resultadoQuery->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($_POST['password'], $row['password'])) {
            // logueado correcto
            $_SESSION['usuario']['id'] = $row['id'];
            $_SESSION['usuario']['nombre'] = $row['nombre'];
            $_SESSION['usuario']['apellido'] = $row['apellido'];
            header('Location: ' . $dirBase . '/');
            die;
            // $loginStatus = true;
            // $msg = 'Bienvenido/a: '.$row['apellido'].' '.$row['nombre'];
            // $varClass = 'alert-success';
        } else {
            $loginStatus = false;
            $msg = 'El usuario o la contraseña no son correctas!!';
            $varClass = 'alert-danger';
        }
    } else {
        $loginStatus = false;
        $msg = 'El usuario o la contraseña no son correctas!!';
        $varClass = 'alert-danger';
    }
}
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
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card">
      <div class="text-center mb-4">
        <h2>Iniciar Sesión</h2>
      </div>

      <form id="loginForm" class="text-start" novalidate>
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
         <a href="../jugador/crear-usuario.html">Registrate como jugador</a>
      </div>
      <div class="mt-3 text-center small text-muted">
        <a href="../Organizador/crear-usuario.html">Registrate como organizador</a>
      </div>
      <div class="mt-3 text-center small text-muted">
            <a href="../inicio.html">Volver al inicio</a>
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
