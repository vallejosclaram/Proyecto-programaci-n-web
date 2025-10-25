
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Jugador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  
  <link rel="stylesheet" href="../style.css" />
  
  <script src="equipo.js"></script>
  <link rel="stylesheet" href="equipo.css" />
  <script src="visualizacion-equipos.js"></script>
</head>
<body>
  
<?php require_once __DIR__ . '/../../componentes/dashboardOrganizador.php'; ?>
  <main class="main-content" id="mainContent">
    <h1 class="page-title">Equipos y Torneos</h1>
    

    <section class="filters">
      <input type="text" id="searchInput" placeholder="Buscar equipo o jugador..." />
      <select id="filterTorneo">
        <option value="">Todos los torneos</option>
      </select>
    </section>

    <section id="teamsGrid" class="equipos-container"></section>
  </main>

  
  <div class="modal" id="playersModal" aria-hidden="true">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">Jugadores</h2>
        <button id="modalClose">✖</button>
      </div>
      <ul id="playersList" class="players-list"></ul>
    </div>
  </div>
  
</body>
</html>
