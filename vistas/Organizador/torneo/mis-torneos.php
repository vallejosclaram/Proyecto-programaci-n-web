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

// Obtener torneos (PDO)
$stmt = $conn->prepare("
  SELECT 
    t.id_torneo,
    t.nombre AS nombre_torneo,
    j.nombre AS juego,
    t.fecha_inicio,
    t.fecha_fin,
    et.descripcion AS estado,
    tt.descripcion AS tipo
  FROM torneo t
  JOIN juego j ON t.id_juego = j.id_juego
  JOIN estado_torneo et ON t.id_estado = et.id_estado
  JOIN tipo_torneo tt ON t.id_tipo = tt.id_tipo
  WHERE t.id_organizador = ?
");

$stmt->execute([$id_organizador]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <link rel="stylesheet" href="mis-torneos.css" />
</head>
<body>
  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <nav class="nav-links">
      <a href="../dashboard.php" class="nav-item"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="../perfil/ver.php" class="nav-item"><i class="fa-solid fa-user" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="mis-torneos.php" class="nav-item active"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="../equipo/equipo.php" class="nav-item"><i class="fa-solid fa-people-group" style="color:#4dffb8;"></i> Equipos</a>
      <a href="../ranking.php" class="nav-item active"><i class="fa-solid fa-ranking-star" style="color:#ffb84d;"></i> Ranking</a>
      <a href="../solicitudes.php" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>

    <div class="logout">
      <a href="../../inicio.php" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </aside>

  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- ===== HEADER ===== -->
  <?php include '../componentes/header.php'; ?>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <main class="main-content">
    <section class="torneos-section">
      <div class="torneos-header">
        <h2>Mis Torneos</h2>
        <button id="nuevoTorneoBtn" class="btn-nuevo-torneo" data-bs-toggle="modal" data-bs-target="#crearTorneoModal">
          <i class="fa-solid fa-plus"></i> Crear Torneo
        </button>
      </div>

      <?php foreach ($result as $row): ?>
          <div class="torneo-card">
            <img src="../assets/valorant.jpg" alt="<?= htmlspecialchars($row['juego']) ?>">
            <h3><?= htmlspecialchars($row['nombre_torneo']) ?></h3>
            <p><i class="fa-solid fa-gamepad"></i> <?= htmlspecialchars($row['juego']) ?></p>
            <p><i class="fa-solid fa-calendar"></i> <?= $row['fecha_inicio'] ?> - <?= $row['fecha_fin'] ?></p>
            <p><i class="fa-solid fa-flag"></i> <?= htmlspecialchars($row['estado']) ?> | <?= htmlspecialchars($row['tipo']) ?></p>
            <div class="card-actions">
              <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
              <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
              <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="torneo-card">
          <img src="../assets/fifa.jpg" alt="FIFA">
          <h3>FIFA Cup 2025</h3>
          <p><i class="fa-solid fa-calendar"></i> 12 Dic 2025</p>
          <p><i class="fa-solid fa-users"></i> 8 equipos</p>
          <div class="card-actions">
            <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
            <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
            <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      </div>
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
        <form id="formTorneo" class="form-container">

          <div class="form-group">
            <label for="nombre">Nombre del Torneo</label>
            <input type="text" id="nombre" name="nombre" placeholder="Torneo Legends" required>
          </div>

          <div class="form-group">
            <label for="juego">Juego</label>
            <input type="text" id="juego" name="juego" placeholder="League of Legends" required>
          </div>

          <div class="form-group">
            <label for="tipo">Tipo de Torneo</label>
            <select id="tipo" name="tipo" required>
              <option value="">Seleccionar</option>
              <option value="individual">Individual</option>
              <option value="equipo">En equipo</option>
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

          <div class="form-group">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required>
              <option value="">Seleccionar</option>
              <option value="abierto">Abierto</option>
              <option value="cerrado">Cerrado</option>
            </select>
          </div>

          <button type="submit" class="btn-submit btn-gradient">🎯 Crear Torneo</button>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="toastTorneo" class="toast align-items-center text-white bg-purple border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Torneo creado con éxito 🎉
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="mis-torneos.js"></script>
</body>
</html>
