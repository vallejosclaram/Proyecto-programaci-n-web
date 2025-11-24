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
    <link rel="stylesheet" href="style.css"> 
  </head> 
  <body class="dashboard-page"> 
    
   <?php require_once __DIR__ . '/../includes/dashboardJugador.php'; ?>
    
    <div id="sidebarOverlay" class="sidebar-overlay" tabindex="-1" aria-hidden="true"></div>
    <main class="container py-4"> 
        <section class="main-content" id="mainContent" tabindex="-1"> 
          <div class="banner-container"> 
            <img src="img/videojuegos.jpg" alt="Publicidad de torneos" class="banner-img" />
             <!-- FILTRO--> 
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
      <script src="dashboard.js"></script>
  </body> 
</html>