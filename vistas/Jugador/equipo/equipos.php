<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Equipos - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&amp;family=Roboto&amp;display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="equipos.js"></script>
  
</head>

<body>

  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      <h1 class="mb-2">🛡️ Equipos</h1>
      <a href="crear-equipo.html" class="btn btn-violeta">➕ Crear equipo</a>
    </div>

    <form id="formBuscarEquipos" class="row g-3 align-items-end mb-5">
      <div class="col-md-5">
        <label for="buscadorEquipos" class="form-label">Buscar por nombre o juego</label>
        <input type="text" id="buscadorEquipos" class="form-control" placeholder="Ej: Valorant, Titanes..." />
      </div>
      <div class="col-md-4">
        <label for="selectJuego" class="form-label">Filtrar por juego</label>
        <select id="selectJuego" class="form-select">
          <option value="">Todos los juegos</option>
          <option value="Valorant">Valorant</option>
          <option value="FIFA">FIFA</option>
          <option value="League of Legends">League of Legends</option>
          <option value="CS:GO">CS:GO</option>
          <option value="Rocket League">Rocket League</option>
        </select>
      </div>
      <div class="col-md-3 text-end">
        <button type="submit" class="btn btn-violeta w-100">🔎 Buscar</button>
      </div>
    </form>

    <section class="mb-5">
      <h3>📌 Tus equipos</h3>
      <div id="misEquipos"></div>
    </section>

    <section class="mb-5">
      <h3>🧩 Equipos disponibles</h3>
      <div id="equiposDisponibles"></div>
    </section>

    <section class="mb-5" id="solicitudesSection" style="display:none;">
      <h3>📥 Solicitudes recibidas</h3>
      <div id="listaSolicitudes"></div>
    </section>
  </main>

  
  <div class="modal fade" id="modalEquipo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalEquipoTitulo">Detalles del equipo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalEquipoContenido"></div>
      </div>
    </div>
  </div>

  
</body>
</html>
