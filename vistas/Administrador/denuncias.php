<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

// Validar sesión y rol
if (empty($_SESSION['admin']['id'])) {
    echo json_encode(['success' => false, 'error' => 'No hay usuario administrador logueado']);
    exit;
}
$id_usuario = $_SESSION['admin']['id'];
$rol = $_SESSION['admin']['rol'] ?? null;

if ($rol != 1) {
    echo json_encode(['success' => false, 'error' => 'Acceso restringido']);
    exit;
}

// Validar permiso de visualizar denuncias
if (!Permisos::tienePermiso('Visualizar denuncia', $id_usuario)) {
    header('Location: ../error.php?msg=No tenés permiso para visualizar denuncias');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Denuncias</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="dashboard-page">
  <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="form-container">
      <h2>Denuncias de Usuarios</h2>
      <div id="denuncias-usuarios-container" class="mt-4">
        <table class="table table-striped table-dark">
          <thead>
            <tr>
              <th>ID</th>
              <th>Reportador</th>
              <th>Reportado</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="denuncias-usuarios-body"></tbody>
        </table>
      </div>

      <h2 class="mt-5">Denuncias de Torneos</h2>
      <div id="denuncias-torneos-container" class="mt-4">
        <table class="table table-striped table-dark">
          <thead>
            <tr>
              <th>ID</th>
              <th>Reportador</th>
              <th>Organizador</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="denuncias-torneos-body"></tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="dashboard.php" class="btn btn-secondary">Volver</a>
      </div>
    </div>
  </main>

  <!-- Modal Detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalDetallesLabel">Detalles de la denuncia</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalDetallesBody"></div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-danger" id="btnBloquear">Bloquear</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="denuncias.js"></script>
</body>
</html>
