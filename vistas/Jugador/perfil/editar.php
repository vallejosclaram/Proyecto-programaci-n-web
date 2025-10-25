<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Perfil - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../style.css" />
</head>
<body>
  
  <?php require_once __DIR__ . '/../../componentes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <h1>✏️ Editar Perfil</h1>
    <form id="editarPerfilForm" class="perfil-form">
      <label>Usuario</label>
      <input type="text" id="usuario" class="form-control" required />

      <label>Email</label>
      <input type="email" id="email" class="form-control" required />

      <label>Nombre</label>
      <input type="text" id="nombre" class="form-control" />

      <label>Apellido</label>
      <input type="text" id="apellido" class="form-control" />

      <label>Descripción</label>
      <textarea id="descripcion" class="form-control" rows="3"></textarea>

      <div class="perfil-actions">
        <a href="ver.html" class="btn-editar">Atras</a>
        </div>
      <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
    </form>
  </main>

  
  <div class="modal fade" id="modalGuardado" tabindex="-1" aria-labelledby="modalGuardadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalGuardadoLabel">✅ Perfil actualizado</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          Tus datos fueron guardados correctamente.
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <a href="ver.html" class="btn btn-primary">Volver al perfil</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  
<script src="editar.js"></script>
  
</body>
</html>
