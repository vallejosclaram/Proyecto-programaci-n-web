<?php
include '../../connection.php';

session_start();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  echo "<p>No estás autenticado.</p>";
  exit;
}

// Obtener ID del organizador (PDO)
$stmtOrg = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ?");
$stmtOrg->execute([$id_usuario]);
$org = $stmtOrg->fetch(PDO::FETCH_ASSOC);
$id_organizador = $org['id_organizador'] ?? null;

if (!$id_organizador) {
  echo "<p>No se encontró tu perfil de organizador.</p>";
  exit;
}

// Obtener torneos del organizador
$stmt = $conn->prepare("
  SELECT 
    t.id_torneo,
    t.nombre AS nombre_torneo,
    j.nombre AS juego,
    t.fecha_inicio,
    t.fecha_fin,
    e.descripcion AS estado,
    tt.descripcion AS tipo
  FROM torneo t
  JOIN juego j ON t.id_juego = j.id_juego
  JOIN estado_torneo e ON t.id_estado = e.id_estado
  JOIN tipo_torneo tt ON t.id_tipo = tt.id_tipo
  WHERE t.id_organizador = ?
");

$stmt->execute([$id_organizador]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para asignar imágenes según el juego
function imagenJuego($juego) {
  $juego = strtolower($juego);

  if (strpos($juego, "valorant") !== false) return "../img/valorant.jpg";
  if (stripos($juego, "counter") !== false) return "../img/Counter-Strike.jpg";

  return "../img/default.png";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Torneos - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="mis-torneos.css">
  <style>
    .torneos-grid {
      display: grid !important;
      grid-template-columns: repeat(3, 1fr) !important;
      gap: 20px !important;
    }
    </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <nav class="nav-links">
      <a href="../dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="../perfil/ver.php" class="nav-item"><i class="fa-solid fa-user-gear" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="../torneo/mis-torneos.php" class="nav-item"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="../ranking.php" class="nav-item active"><i class="fa-solid fa-ranking-star" style="color:#ffb84d;"></i> Ranking</a>
      <a href="../solicitudes.php" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>

    <div class="logout">
      <a href="../../inicio.php" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </aside>

  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- HEADER -->
  <?php include '../componentes/header.php'; ?>

  <!-- CONTENIDO -->
  <main class="main-content">
    <section class="torneos-section">

      <div class="torneos-header">
        <h2>Mis Torneos</h2>
        <button id="nuevoTorneoBtn" class="btn-nuevo-torneo" data-bs-toggle="modal" data-bs-target="#crearTorneoModal">
          <i class="fa-solid fa-plus"></i> Crear Torneo
        </button>
      </div>

      <!-- GRID -->
      <div class="torneos-grid">

        <?php foreach ($result as $row): ?>
          <div class="torneo-card" data-id="<?= $row['id_torneo'] ?>">

            <!-- Imagen dinámica -->
            <img src="<?= imagenJuego($row['juego']) ?>" alt="<?= htmlspecialchars($row['juego']) ?>">

            <h3><?= htmlspecialchars($row['nombre_torneo']) ?></h3>

            <p><i class="fa-solid fa-gamepad"></i> 
              <strong>Juego:</strong> <?= htmlspecialchars($row['juego']) ?>
            </p>

            <p><i class="fa-solid fa-calendar"></i> 
              <strong>Fecha:</strong>
              <?= date("d/m/Y", strtotime($row['fecha_inicio'])) ?> -
              <?= date("d/m/Y", strtotime($row['fecha_fin'])) ?>
            </p>

            <p><i class="fa-solid fa-flag"></i> 
              <strong>Estado:</strong> <?= htmlspecialchars($row['estado']) ?>
            </p>

            <p><i class="fa-solid fa-layer-group"></i> 
              <strong>Tipo:</strong> <?= htmlspecialchars($row['tipo']) ?>
            </p>

            <div class="card-actions">
              <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
              <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
              <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
            </div>
          </div>
        <?php endforeach; ?>

      </div><!-- /torneos-grid -->

    </section>
  </main>

  <!-- Modal Crear Torneo -->
<div class="modal fade" id="crearTorneoModal" tabindex="-1" aria-labelledby="crearTorneoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">

      <div class="modal-header border-0">
        <h5 class="modal-title" id="crearTorneoLabel">🎮 Crear Nuevo Torneo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="formTorneo" class="form-container" action="" method="POST">

          <div class="form-group">
            <label for="nombre">Nombre del Torneo</label>
            <input type="text" id="nombre" name="nombre" placeholder="Torneo Legends" required>
          </div>

          <div class="form-group">
            <label class="form-label">Juego</label>
            <select class="form-select" id="juego" name="juego" required>
              <option value="" disabled selected>Seleccione...</option>
              <option value="Valorant">Valorant</option>
              <option value="Counter Strike">Counter Strike</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Tipo</label>
            <select class="form-select" name="tipo" id="tipo" required>
              <option value="individual">Individual</option>
              <option value="equipo">Equipo</option>
            </select>
          </div>

          <div class="form-group">
            <label for="fechaInscripcion">Fecha de Inicio</label>
            <input type="date" id="fechaInscripcion" name="fechaInscripcion" required>
          </div>

          <div class="form-group">
            <label for="fechaInscripcionFin">Fecha de Fin</label>
            <input type="date" id="fechaInscripcionFin" name="fechaInscripcionFin" required>
          </div>

          <button type="submit" class="btn-submit btn-gradient">🎯 Crear Torneo</button>
        </form>
      </div>

    </div>
  </div>
</div>

  <!-- Toast -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toastTorneo" class="toast align-items-center text-white bg-purple border-0" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">Torneo creado con éxito 🎉</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
  <!-- MODAL GAMER PRO -->
    <div class="modal fade" id="verTorneoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content gamer-modal">

        <!-- IMAGEN -->
        <div class="gamer-banner">
          <img id="verImagenJuego" class="gamer-banner-img" src="" alt="">
        </div>

        <!-- Cerrar -->
        <button class="btn-close position-absolute end-0 m-3" data-bs-dismiss="modal"></button>

        <!-- TÍTULO -->
        <div class="gamer-title-box">
          <h2 id="verNombre"></h2>
        </div>

        <div class="modal-body">

          <!-- ORGANIZADOR -->
          <p >
            👤 Organizador: <strong id="verOrganizador"></strong>
          </p>

          <!-- ESTADO -->
          <div id="estadoBadge"></div>

          <!-- INFO DEL TORNEO (COLUMNA) -->
          <div class="gamer-info-box">
            <p>🎮 <strong>Juego:</strong> <span id="verJuego"></span></p>
            <p>⏳ <strong>Inicio:</strong> <span id="verInicio"></span></p>
            <p>🏁 <strong>Fin:</strong> <span id="verFin"></span></p>
            <p><i class="fa-solid fa-users-rays" style="color:#ffb84d;"></i><strong>Tipo:</strong> <span id="verTipo"></span></p>
          </div>

          <!-- JUGADORES -->
          <div id="bloqueJugadores" class="gamer-jugadores-box"></div>

        </div>

      </div>
    </div>
  </div>
  <!-- MODAL EDITAR TORNEO -->
  <div class="modal fade" id="editarTorneoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content custom-modal">

        <div class="modal-header border-0">
          <h5 class="modal-title">✏️ Editar Torneo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <form id="formEditarTorneo">

            <input type="hidden" id="editarIdTorneo">

            <div class="form-group">
              <label>Nombre del Torneo</label>
              <input type="text" id="editarNombre" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Juego</label>
              <select id="editarJuego" class="form-select">
                <option value="Valorant">Valorant</option>
                <option value="Counter Strike">Counter Strike</option>
              </select>
            </div>

            <div class="form-group">
              <label>Tipo</label>
              <select id="editarTipo" class="form-select">
                <option value="Individual">Individual</option>
                <option value="Equipo">Equipo</option>
              </select>
            </div>

            <div class="form-group">
              <label>Inicio</label>
              <input type="date" id="editarInicio" class="form-control">
            </div>

            <div class="form-group">
              <label>Fin</label>
              <input type="date" id="editarFin" class="form-control">
            </div>

            <div class="form-group">
              <label>Estado</label>
              <select id="editarEstado" class="form-select">
                <option value="1">Activo</option>
                <option value="2">Cerrado</option>
              </select>
            </div>

            <button class="btn btn-primary w-100 mt-3">Guardar cambios</button>

          </form>

        </div>
      </div>
    </div>
  </div>
  <!-- MODAL ELIMINAR TORNEO -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background:#1c1c2b; border:2px solid #ff4d6d; border-radius:15px; color:white;">

        <div class="modal-header" style="border-bottom:1px solid #ff4d6d;">
          <h5 class="modal-title">⚠️ Eliminar Torneo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <p id="deleteText">¿Seguro que deseas eliminar este torneo?</p>
          <p class="text-danger"><b>Esta acción es permanente.</b></p>
        </div>

        <div class="modal-footer" style="border-top:1px solid #ff4d6d;">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

          <button id="btnConfirmDelete" class="btn btn-danger">Eliminar</button>
        </div>

      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="mis-torneos.js"></script>

</body>
</html>
