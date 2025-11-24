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

// Validar permiso de visualizar resultados
if (!Permisos::tienePermiso('gestionar_puntaje', $id_usuario)) {
    header('Location: ../error.php?msg=No tenés permiso para visualizar resultados');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Resultados</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css" />

</head>
<body class="dashboard-page">
  <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="form-container">
      <h2>Resultados cargados por usuarios</h2>
      <div id="resultados-container" class="mt-4">
      <div class="filters mb-3 row g-3">
        <div class="col-md-4">
          <label for="filtroNombreJuego" class="form-label">Buscar por nombre de juego</label>
          <input type="text" id="filtroNombreJuego" class="form-control" placeholder="Nombre del juego">
        </div>
        <div class="col-md-4">
          <label for="filtroNombreTorneo" class="form-label">Buscar por nombre de torneo</label>
          <input type="text" id="filtroNombreTorneo" class="form-control" placeholder="Nombre del torneo">
        </div>
        <div class="col-md-4">
          <label for="filtroTipo" class="form-label">Tipo de torneo</label>
          <select id="filtroTipo" class="form-select">
            <option value="">Todos los tipos</option>
            <option value="1">Individual</option>
            <option value="2">Equipo</option>
          </select>
        </div>
        <div class="col-12">
          <button id="btnFiltrar" class="btn btn-primary">Filtrar</button>
          <button id="btnLimpiar" class="btn btn-secondary">Limpiar filtros</button>
        </div>
      </div>

        <table class="table table-striped table-dark">
          <thead>
            <tr>
              
              <th>Juego</th>
              <th>Torneo</th>
              <th>Tipo</th>
              <th>Jugador</th>
              <th>Equipo</th>
              <th>Puntaje</th>
              <th>Fecha</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="resultados-body"></tbody>
        </table>
      </div>

      <h2 class="mt-5">Ranking</h2>
      <div id="ranking-container" class="mt-4">
        <div class="filters mb-3 row g-3">
          <div class="col-md-6">
            <label for="filtroRankingJuego" class="form-label">Filtrar por juego</label>
            <input type="text" id="filtroRankingJuego" class="form-control" placeholder="Nombre del juego">
          </div>
          <div class="col-md-6">
            <label for="filtroRankingTipo" class="form-label">Tipo</label>
            <select id="filtroRankingTipo" class="form-select">
              <option value="">Todos</option>
              <option value="individual">Individual</option>
              <option value="equipo">Equipo</option>
            </select>
          </div>
          <div class="col-12">
            <button id="btnFiltrarRanking" class="btn btn-primary">Filtrar Ranking</button>
            <button id="btnLimpiarRanking" class="btn btn-secondary">Limpiar</button>
          </div>
        </div>
        <table class="table table-striped table-dark">
          <thead>
            <tr>
              <th>Posición</th>
              <th>Jugador/Equipo</th>
              <th>Juego</th>
              <th>Puntos Totales</th>
            </tr>
          </thead>
          <tbody id="ranking-body"></tbody>
        </table>
      </div>

      
    </div>
  </main>

  <!-- Modal Detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalDetallesLabel">Detalles del resultado</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalDetallesBody"></div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-success" id="btnAceptar">Aceptar</button>
          <button type="button" class="btn btn-danger" id="btnRechazar">Rechazar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Confirmación Aceptar -->
  <div class="modal fade" id="modalConfirmarAceptar" tabindex="-1" aria-labelledby="modalConfirmarAceptarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalConfirmarAceptarLabel">Confirmar Aceptación</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>¿Estás seguro de que deseas aceptar este resultado? Esta acción actualizará el ranking.</p>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-success" id="btnConfirmarAceptar">Sí, aceptar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Confirmación Rechazar -->
  <div class="modal fade" id="modalConfirmarRechazar" tabindex="-1" aria-labelledby="modalConfirmarRechazarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalConfirmarRechazarLabel">Confirmar Rechazo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>¿Estás seguro de que deseas rechazar este resultado?</p>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-danger" id="btnConfirmarRechazar">Sí, rechazar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Mensaje -->
  <div class="modal fade" id="modalMensaje" tabindex="-1" aria-labelledby="modalMensajeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalMensajeLabel">Mensaje</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalMensajeBody"></div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="resultados.js"></script>
</body>
</html>
