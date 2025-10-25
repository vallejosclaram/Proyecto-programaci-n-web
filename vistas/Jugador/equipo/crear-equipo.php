<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear Equipo - UPE-SPORT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css" />
   <script src="crear-equipo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

  <?php require_once __DIR__ . '/../../componentes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <h1>➕ Crear Equipo</h1>
    <form id="crearEquipoForm" class="perfil-form">
      <label>Nombre del equipo</label>
      <input type="text" id="nombreEquipo" class="form-control" required />

      <label>Cantidad de jugadores (máximo 10)</label>
      <input type="number" id="cantidadJugadores" class="form-control" min="1" max="10" required />

      <label>Juego principal</label>
      <select id="juegoEquipo" class="form-select" required>
        <option value="">Seleccionar juego</option>
        <option value="Valorant">Valorant</option>
        <option value="FIFA">FIFA</option>
        <option value="League of Legends">League of Legends</option>
        <option value="CS:GO">CS:GO</option>
        <option value="Rocket League">Rocket League</option>
      </select>

      <label>Descripción (opcional)</label>
      <textarea id="descripcionEquipo" class="form-control" rows="3"></textarea>

      <div class="d-flex justify-content-between mt-4">
        <a href="equipos.html" class="btn btn-secondary">Volver</a>
        <button type="submit" class="btn btn-primary">Crear equipo</button>
      </div>
    </form>
  </main>

 
  <div class="modal fade" id="modalCreado" tabindex="-1" aria-labelledby="modalCreadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalCreadoLabel">✅ Equipo creado</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          Tu equipo fue creado correctamente.
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <a href="equipos.html" class="btn btn-primary">Ir a mis equipos</a>
        </div>
      </div>
    </div>
  </div>

  <script src="crear-equipo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
