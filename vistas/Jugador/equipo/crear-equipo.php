<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear Equipo - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&amp;family=Roboto&amp;display=swap" rel="stylesheet">
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
    <form id="crearEquipoForm" class="perfil-form">
      <label class="mt-3 mb-3">Nombre del equipo</label>
      <input type="text" id="nombreEquipo" class="form-control" required />

      <label class="mt-3 mb-3">Cantidad de jugadores (máximo 10)</label>
      <input type="number" id="cantidadJugadores" class="form-control" min="1" max="10" required />

      <label class="mt-3 mb-3">Juego principal</label>
      <select id="juegoEquipo" class="form-select" required>
        <option value="">Seleccionar juego</option>
        <option value="Valorant">Valorant</option>
        <option value="FIFA">FIFA</option>
        <option value="League of Legends">League of Legends</option>
        <option value="CS:GO">CS:GO</option>
        <option value="Rocket League">Rocket League</option>
      </select>

      <label class="mt-3 mb-3">Descripción (opcional)</label>
      <textarea id="descripcionEquipo" class="form-control" rows="3"></textarea>

     
      <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-primary">Crear equipo</button>
      </div>
       <div class="d-flex justify-content-between mt-4">
        <a href="equipos.html" class="btn btn-secondary">Volver</a>
      </div>
    </form>
    </div>
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


</body>
</html>