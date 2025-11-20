<?php
require_once(__DIR__ . '/../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];
?>


<!DOCTYPE html>
<html lang="es"> 
  <head> <meta charset="utf-8" /> 
    <meta name="viewport" content="width=device-width,initial-scale=1" /> 
    <title>Dashboard - Jugador</title> 
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <script src="dashboard.js" defer>

    </script> <link rel="stylesheet" href="style.css"> 
  </head> 
  <body class="dashboard-page"> 
    <?php require_once __DIR__ . '/includes/dashboardJugador.php'; ?>
   <!-- <aside class="sidebar" id="sidebar"> 
      <div class="sidebar-header"> 
        <span class="logo">🎮 UPE-SPORT</span> 
        <button class="close-btn" id="closeBtn">✖</button> 
      </div> 
      <nav class="nav-links" role="navigation" aria-label="Menú principal"> 
        <a href="dashboard.php" class="nav-item active">🏠 Dashboard</a> 
        <a href="perfil/ver.php" class="nav-item">👤 Mi perfil</a> 
        <a href="equipo/equipos.php" class="nav-item">🛡️ Equipos</a> 
        <a href="torneo/torneo.php" class="nav-item">🏆 Torneos</a> 
        <a href="ranking.php" class="nav-item">📊 Ranking</a>
      </nav> 
      <div class="logout"> 
        <a href="../auth/login.html" class="nav-item logout-btn">🚪 Cerrar sesión</a>
      </div> 
    </aside> -->
    <!-- overlay para cuando el sidebar está abierto (clic para cerrar) --> 
    <div id="sidebarOverlay" class="sidebar-overlay" tabindex="-1" aria-hidden="true"></div>
              <!-- TOPBAR --> 
    <header class="topbar"> 
      <!-- Izquierda --> 
      <div class="topbar-inner"> 
        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú" title="Abrir menú">☰</button> 
        <!-- Centro --> 
        <div class="topbar-center"> 
          <span class="topbar-logo">🎮 UPE-SPORT</span> 
        </div> 
        <!-- Derecha --> 
        <div id="usuarioResumen" class="usuario-resumen" aria-live="polite"> 
          <div id="usuarioNombre">Hola, Jugador</div> 
          <small id="usuarioRol" class="text-muted"></small> 
        </div> 
      </div> 
    </header> 
    <main class="container py-4"> 
        <section class="main-content" id="mainContent" tabindex="-1"> 
          <div class="banner-container"> 
            <img src="img/videojuegos.jpg" alt="Publicidad de torneos" class="banner-img" 
            /> <!-- FILTRO flotante --> 
            <div class="banner-filters-floating"> 
              <div class="search-box"> 
                <span class="search-icon">🔍</span> 
                <input id="globalSearchInput" type="search" placeholder="Buscar usuarios, torneos o partidas..."> 
              </div> 
              <select id="searchScope" class="form-select"> 
                <option value="all">Todo</option> 
                <option value="players">Jugadores</option> 
                <option value="games">Partidas</option> 
                <option value="tournaments">Torneos</option> 
              </select> 
              <button id="clearSearchBtn" class="clear-btn" title="Limpiar">✖</button> 
            </div> 
          </div> 
        </section> 
       
        <section class="mb-4"> 
          <div class="d-flex align-items-center justify-content-between mb-2"> 
            <h2 class="section-title">Jugadores destacados</h2> 
            <div class="scroll-controls"> 
              <button class="btn btn-sm btn-light me-1" data-target="playersList" data-dir="-1">◀</button>
              <button class="btn btn-sm btn-light" data-target="playersList" data-dir="1">▶</button> 
            </div> 
          </div> 
          <div class="h-scroll" id="playersList" tabindex="0" aria-label="Lista de jugadores"></div> 
        </section> 
       
        <section class="mb-4"> 
          <div class="d-flex align-items-center justify-content-between mb-2"> 
            <h2 class="section-title">Partidas / Juegos</h2> 
            <div class="scroll-controls"> 
              <button class="btn btn-sm btn-light me-1" data-target="gamesList" data-dir="-1">◀</button> 
              <button class="btn btn-sm btn-light" data-target="gamesList" data-dir="1">▶</button> 
            </div> 
          </div> 
          <div class="h-scroll" id="gamesList" tabindex="0" aria-label="Lista de juegos"> <!-- cards --> </div> 
        </section> 
        <!-- Torneos próximos: horizontal --> 
        <section class="mb-4"> 
          <div class="d-flex align-items-center justify-content-between mb-2"> 
            <h2 class="section-title">Torneos próximos</h2> 
            <div class="scroll-controls"> 
              <button class="btn btn-sm btn-light me-1" data-target="tournamentsList" data-dir="-1">◀</button> 
              <button class="btn btn-sm btn-light" data-target="tournamentsList" data-dir="1">▶</button> 
            </div> 
          </div> 
          <div class="h-scroll" id="tournamentsList" tabindex="0" aria-label="Lista de torneos"> </div> 
        </section> 
      </main> 
      <div class="modal fade" id="modalInfo" tabindex="-1" aria-hidden="true"> 
        <div class="modal-dialog modal-dialog-centered"> 
          <div class="modal-content bg-dark text-white"> 
            <div class="modal-header border-0"> 
              <h5 id="modalTitle" class="modal-title"></h5> 
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button> 
            </div> 
            <div class="modal-body" id="modalBody"></div> 
            <div class="modal-footer border-0"> 
              <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> 
            </div> 
          </div> 
        </div> 
      </div> 
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> 
  </body> 
</html>