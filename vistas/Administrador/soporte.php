<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

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

if (!Permisos::tienePermiso('responder_tickets', $id_admin)) {
    header('Location: ../error.php?msg=No tenés permiso para responder tickets');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Centro de Soporte</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
</head>
<body class="dashboard-page">
  <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="form-container">
      <h2>Centro de Soporte</h2>
      <p class="text-light mb-4">Visualizá las consultas enviadas por los usuarios y respondelas desde este panel.</p>

      <div class="filters mb-4 row g-3">
        <div class="col-md-4">
          <label for="filtroSearch" class="form-label">Buscar (asunto, usuario, descripción)</label>
          <input type="text" id="filtroSearch" class="form-control" placeholder="ej: bug en torneo">
        </div>
        <div class="col-md-3">
          <label for="filtroEstadoTicket" class="form-label">Estado</label>
          <select id="filtroEstadoTicket" class="form-select">
            <option value="">Todos</option>
            <option value="pendiente">Pendiente</option>
            <option value="respondido">Respondido</option>
          </select>
        </div>
        <div class="col-md-2">
          <label for="filtroFechaDesde" class="form-label">Desde</label>
          <input type="date" id="filtroFechaDesde" class="form-control">
        </div>
        <div class="col-md-2">
          <label for="filtroFechaHasta" class="form-label">Hasta</label>
          <input type="date" id="filtroFechaHasta" class="form-control">
        </div>
        <div class="col-md-1 d-flex align-items-end gap-1">
          <button id="btnFiltrarTickets" class="btn btn-primary w-100">Filtrar</button>
        </div>
        <div class="col-md-1 d-flex align-items-end gap-1">
          <button id="btnLimpiarTickets" class="btn btn-secondary w-100">Limpiar</button>
        </div>
      </div>

      <section id="tickets-container" class="tickets-blog">
        <div class="text-center text-light py-5">Cargando tickets...</div>
      </section>
    </div>
  </main>

  <div class="modal fade" id="modalRespuesta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title">Responder ticket</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="respuestaTicketInfo" class="small text-info mb-2"></p>
          <textarea id="respuestaTexto" class="form-control" rows="5" placeholder="Escribí la respuesta..." required></textarea>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-primary" id="btnPrepararEnvio">Enviar respuesta</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalConfirmarEnvio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title">Confirmar respuesta</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          ¿Deseás publicar esta respuesta para el ticket seleccionado?
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-success" id="btnConfirmarRespuesta">Sí, enviar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalMensaje" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title">Soporte</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalMensajeBody"></div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="soporte.js"></script>
</body>
</html>
