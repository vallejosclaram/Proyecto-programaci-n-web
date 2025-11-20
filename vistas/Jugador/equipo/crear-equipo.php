<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear Equipo - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css" />

  <script src="crear-equipo.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="dashboard-page">
  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="form-container">
      <h2>Crear Equipo</h2>

      <form id="crearEquipoForm" class="perfil-form" method="POST" action="procesar-crear-equipo.php">

        <label for="nombreEquipo" class="mt-3 mb-3">Nombre del equipo</label>
        <input type="text" name="nombreEquipo" id="nombreEquipo" class="form-control" required />

        <label for="cantidadJugadores" class="mt-3 mb-3">Cantidad de jugadores (máximo 10)</label>
        <input type="number" name="cantidadJugadores" id="cantidadJugadores" class="form-control" min="1" max="10" required />

        <label for="juegoEquipo" class="mt-3 mb-3">Juego</label>
        <select name="juegoEquipo" id="juegoEquipo" class="form-select" required>
          <option value="">Cargando juegos...</option>
        </select>

        <label for="descripcionEquipo" class="mt-3 mb-3">Descripción (opcional)</label>
        <textarea name="descripcionEquipo" id="descripcionEquipo" class="form-control" rows="3"></textarea>

        <div class="d-flex justify-content-between mt-4">
          <button type="submit" id="crearEquipo" name="crearEquipo" class="btn btn-primary">Crear equipo</button>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <a href="equipos.php" class="btn btn-secondary">Volver</a>
        </div>

      </form>
    </div>
  </main>

  <!-- Modal -->
  <div class="modal fade" id="modalCreado" tabindex="-1" aria-labelledby="modalCreadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title">Equipo creado</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          Tu equipo fue creado correctamente.
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <a href="equipos.php" class="btn btn-primary">Ir a mis equipos</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
