<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

// Validar sesión y rol
if (empty($_SESSION['admin']['id'])) {
    header("Location: login.php");
    exit;
}
$id_admin = $_SESSION['admin']['id'];
$rol = $_SESSION['admin']['rol'] ?? null;

if ($rol != 1) {
    echo "Acceso restringido";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Solicitudes de Creación de Torneo</title>
  <!-- Tipografías y estilos -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
</head>
<body class="dashboard-page">
  <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="form-container">
      <h2>Solicitudes de creación de torneo</h2>

      <div class="filters mb-3 row g-3">
        <div class="col-md-3">
          <label for="filtroNombreTorneo" class="form-label">Nombre del torneo</label>
          <input type="text" id="filtroNombreTorneo" class="form-control" placeholder="Ej: Valorant Cup">
        </div>
        <div class="col-md-3">
          <label for="filtroJuego" class="form-label">Juego</label>
          <input type="text" id="filtroJuego" class="form-control" placeholder="Ej: Valorant">
        </div>
        <div class="col-md-3">
          <label for="filtroTipo" class="form-label">Tipo</label>
          <select id="filtroTipo" class="form-select">
            <option value="">Todos</option>
            <option value="1">Individual</option>
            <option value="2">Equipo</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="filtroEstado" class="form-label">Estado</label>
          <select id="filtroEstado" class="form-select">
            <option value="">Todos</option>
            <option value="pendiente">Pendiente</option>
            <option value="aprobado">Aprobado</option>
            <option value="rechazado">Rechazado</option>
          </select>
        </div>
        <div class="col-12">
          <button id="btnFiltrar" class="btn btn-primary">Filtrar</button>
          <button id="btnLimpiar" class="btn btn-secondary">Limpiar filtros</button>
        </div>
      </div>

      <!-- Tabla de solicitudes -->
      <table class="table table-dark table-hover">
        <thead>
          <tr>
            <th>ID Solicitud</th>
            <th>Nombre Torneo</th>
            <th>Juego</th>
            <th>Tipo</th>
            <th>Usuario</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="solicitudes-body">
          <tr><td colspan="9">Cargando solicitudes...</td></tr>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal de detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark">
        <div class="modal-header">
          <h5 class="modal-title">Detalles de la solicitud</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalDetallesBody"></div>
        <div class="modal-footer">
          <button type="button" id="btnAceptar" class="btn btn-success">Aceptar</button>
          <button type="button" id="btnRechazar" class="btn btn-danger">Rechazar</button>
        </div>
      </div>
    </div>
  </div>

<!-- Modal Confirmar Aceptación -->
<div class="modal fade" id="modalConfirmarAceptar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Confirmar aceptación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        ¿Deseás aprobar esta solicitud y crear el torneo?
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-success" id="btnConfirmarAceptar">Sí, aprobar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Confirmar Rechazo -->
<div class="modal fade" id="modalConfirmarRechazar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Confirmar rechazo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        ¿Deseás rechazar esta solicitud?
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-danger" id="btnConfirmarRechazar">Sí, rechazar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Mensaje -->
<div class="modal fade" id="modalMensaje" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Información</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalMensajeBody"></div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="solicitudes.js"></script>
</body>
</html>
