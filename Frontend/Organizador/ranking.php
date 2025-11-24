<?php
include '../../Backend/conexion.php';
include '../../Backend/organizador/session_org.php';
?>
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
</head>
<body >
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <nav class="nav-links">
      <a href="dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="perfil/ver.php" class="nav-item"><i class="fa-solid fa-user-gear" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="../torneo/mis-torneos.php" class="nav-item"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="equipos.php" class="nav-item"><i class="fa-solid fa-people-group" style="color:#4dffb8;"></i> Equipos</a>
      <a href="ranking.php" class="nav-item active"><i class="fa-solid fa-ranking-star" style="color:#ffb84d;"></i> Ranking</a>
      <a href="solicitudes.php" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>

    <div class="logout">
      <a href="../inicio.php" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </aside>

  <!-- Overlay -->
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- ===== HEADER ===== -->
  <?php include './componentes/header.php'; ?>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
        <section class="estadisticas-section">
      <div class="estadisticas-header">
        <h2>🏆 Ranking</h2>

        <div class="ranking-tabs">
          <button class="tab-btn active" id="tabEquipos">Equipos</button>
          <button class="tab-btn" id="tabJugadores">Jugadores</button>
        </div>

        <!-- 🔍 Barra de búsqueda y filtro -->
        <div class="ranking-filtros mt-3">
          <input type="text" id="busquedaInput" class="filtro-input" placeholder="Buscar nombre...">
          <select id="filtroJuego" class="filtro-select">
            <option value="">Todos los juegos</option>
            <option value="Valorant">Valorant</option>
            <option value="League of Legends">League of Legends</option>
            <option value="FIFA">FIFA</option>
          </select>
        </div>
      </div>

      <div id="contenedorEquipos" class="ranking-container"></div>
      <div id="contenedorJugadores" class="ranking-container d-none"></div>
    </section>

  </main>

  <script src="ranking.js"></script>
</body>
</html>

