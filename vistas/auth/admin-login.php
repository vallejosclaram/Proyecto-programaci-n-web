<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ingreso Administrador - UPE-SPORT</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="admin-login.js"></script>

  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="hero-card p-4 shadow">
      <div class="text-center mb-4">
        <div class="brand">UPE-SPORT</div>
        <div class="text-muted small">Panel de administración</div>
      </div>

      <form id="adminLoginForm" class="text-start" method="POST" action="admin-login-process.php" novalidate>
        <div class="mb-3">
          <label for="adminUser" class="form-label">Usuario</label>
          <input type="text" class="form-control" id="adminUser" name="user" required>
          <div class="invalid-feedback">Ingrese su usuario</div>
        </div>

        <div class="mb-3">
          <label for="adminPass" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="adminPass" name="password" required>
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="showAdminPass">
            <label class="form-check-label" for="showAdminPass">Mostrar contraseña</label>
          </div>
          <div class="invalid-feedback">Ingrese su contraseña</div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
      </form>

      <div class="mt-3 text-center small text-muted">
        <a href="../inicio.html">Volver al inicio</a>
      </div>
    </div>
  </div>
</body>
</html>
