<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Jugador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="solicitudes.css" />
  <script src="solicitudes.js"></script>
</head>
<body>
 
<?php require_once __DIR__ . '/../../componentes/dashboardOrganizador.php'; ?>

  <div class="solicitudes-container">
    <h3>Torneos Individuales</h3>
    <div class="solicitudes-grid" id="solicitudesIndividual"></div>

    <h3>Torneos en Equipo</h3>
    <div class="solicitudes-grid" id="solicitudesEquipo"></div>
  </div>

  <!-- Modal -->
  <div class="modal" id="modalSolicitud">
    <div class="modal-content">
      <span class="close-modal" id="closeModal">&times;</span>
      <h4 id="modalTitulo">Solicitud</h4>
      <p><strong>Jugador/Equipo:</strong> <span id="modalNombre"></span></p>
      <p><strong>Posición en ranking:</strong> <span id="modalRanking"></span></p>
      <div style="text-align:center; margin-top:1rem;">
        <button class="btn-aceptar" id="btnAceptar">Aceptar</button>
        <button class="btn-rechazar" id="btnRechazar">Rechazar</button>
      </div>
    </div>
  </div>
  
</body>
</html>
