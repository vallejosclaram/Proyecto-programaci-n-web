<?php
include '../../connection.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Equipos - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="equipo.css" />
</head>
<body>

  <!-- ===== HEADER ===== -->
  <?php include '../componentes/header.php'; ?>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <main class="main-content">
    <section class="equipo-section">
      <div class="equipo-header">
        <h2>Mis Equipos</h2>
        <button button id="nuevoEquipoBtn" class="btn-nuevo-torneo" data-bs-toggle="modal" data-bs-target="#crearEquipoModal">
          <i class="fa-solid fa-plus"></i> Crear Equipo
        </button>
      </div>

      <div class="equipo-container">
        <div class="equipo-card">
          <img src="https://via.placeholder.com/100" alt="Logo Equipo" class="equipo-logo">
          <div class="equipo-nombre">Equipo Fénix</div>
          <div class="jugadores-info"><i class="fas fa-user"></i> 5 Jugadores</div>
          <div class="card-actions">
            <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
            <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
            <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>

        <div class="equipo-card">
          <img src="https://via.placeholder.com/100" alt="Logo Equipo" class="equipo-logo">
          <div class="equipo-nombre">Dark Wolves</div>
          <div class="jugadores-info"><i class="fas fa-user"></i> 6 Jugadores</div>
          <div class="card-actions">
            <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
            <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
            <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
        <div class="equipo-card">
          <img src="https://via.placeholder.com/100" alt="Logo Equipo" class="equipo-logo">
          <div class="equipo-nombre">Neon Titans</div>
          <div class="jugadores-info"><i class="fas fa-user"></i> 4 Jugadores</div>
          <div class="card-actions">
            <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
            <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
            <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      </div>
      </div>
    </section>
  </main>

  <!-- Modal Crear Equipo -->
<div class="modal fade" id="crearEquipoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-3 shadow-lg border-0">
      <div class="modal-header border-0">
        <h5 class="modal-title">Crear Equipo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formCrearEquipo">
          <div class="mb-3">
            <label class="form-label">Nombre del Equipo</label>
            <input type="text" class="form-control bg-secondary text-white border-0 rounded-pill" placeholder="Ej: Team Phoenix" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Logo del Equipo (URL)</label>
            <input type="url" class="form-control bg-secondary text-white border-0 rounded-pill" placeholder="https://..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">Juego</label>
            <select class="form-select bg-secondary text-white border-0 rounded-pill" required>
              <option value="">Selecciona un juego</option>
              <option value="valorant">Valorant</option>
              <option value="fifa">FIFA</option>
              <option value="lol">League of Legends</option>
            </select>
          </div>
          <button type="submit" class="btn btn-purple w-100 rounded-pill">Crear Equipo</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="toastEquipo" class="toast align-items-center text-white bg-purple border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Equipo creado con éxito 🎉
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="equipo.js"></script>
</body>
</html>

