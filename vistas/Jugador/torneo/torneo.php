
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Jugador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="torneo.css" />
  <script src="torneo.js"></script>
</head>
<body>

 
  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <div class="torneos-wrapper">

    
    <div class="columna-calendario">
      <h3>Próximas partidas</h3>
      <ul id="calendarioTorneos">
       
      </ul>
    </div>

    
    <div class="columna-torneos">
      <div class="filtros">
        <input type="text" id="filtroJuego" placeholder="Filtrar por juego">
        <input type="text" id="filtroEquipo" placeholder="Filtrar por equipo">
      </div>

      <h3>Mis Torneos</h3>
      <div class="torneos-grid" id="torneosPropios"></div>

      <h3>Otros Torneos</h3>
      <div class="torneos-grid" id="torneosOtros"></div>
    </div>
  </div>

 
  <div class="modal" id="modalJugadores">
    <div class="modal-content">
      <span class="close" id="closeJugadores">&times;</span>
      <h3>Jugadores del Torneo</h3>
      <table id="tablaJugadores">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Ranking</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div class="modal" id="modalDenuncia">
    <div class="modal-content">
      <span class="close" id="closeDenuncia">&times;</span>
      <h3>Motivo de la denuncia</h3>
      <select id="motivoSelect">
        <option value="">Seleccionar motivo</option>
        <option value="bullying">Bullying</option>
        <option value="comentarios">Comentarios fuera de lugar</option>
        <option value="no responsable">No responsable</option>
        <option value="otro">Otro</option>
      </select>
      <button id="btnConfirmar">✔</button>
    </div>
  </div>

  <div class="modal" id="modalConfirmacion">
    <div class="modal-content">
      <span class="close" id="closeConfirmacion">&times;</span>
      <h3>Usuario denunciado</h3>
      <p>La denuncia se ha registrado correctamente.</p>
      <button id="btnCerrarConfirmacion">Cerrar</button>
    </div>
  </div>
</body>
</html>
