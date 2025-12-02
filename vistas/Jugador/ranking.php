<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ranking Organizador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="dashboard.js"></script>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="ranking.css" />
   <script src="ranking.js"></script>
</head>
<body >
  
  <?php require_once __DIR__ . '/../includes/dashboardJugador.php'; ?>


  <main class="main-content">
        <section class="estadisticas-section">
      <div class="estadisticas-header">
        <h2>🏆 Ranking</h2>

        <div class="ranking-tabs">
          <button class="tab-btn active" id="tabEquipos">Equipos</button>
          <button class="tab-btn" id="tabJugadores">Jugadores</button>
        </div>

        <!-- búsqueda-->
        <div class="ranking-filtros mt-3">
          <input type="text" id="busquedaInput" class="filtro-input" placeholder="Buscar nombre...">
          <select id="filtroJuego" class="filtro-select">
            <option value="">Todos los juegos</option>
            <option value="Valorant">Valorant</option>
            <option value="League of Legends">League of Legends</option>
          </select>
        </div>
      </div>

      <div id="contenedorEquipos" class="ranking-container"></div>
      <div id="contenedorJugadores" class="ranking-container d-none"></div>
    </section>

  </main>

 
</body>
</html>

