<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];


try {
    $sql = "
        SELECT t.*, j.nombre AS juego, et.descripcion AS estado_torneo, tt.descripcion AS tipo_torneo,
               u.nombre AS organizador_nombre, u.apellido AS organizador_apellido
        FROM torneo t
        JOIN juego j ON j.id_juego = t.id_juego
        JOIN estado_torneo et ON et.id_estado = t.id_estado
        JOIN tipo_torneo tt ON tt.id_tipo = t.id_tipo
        JOIN organizador o ON o.id_organizador = t.id_organizador
        JOIN usuarios u ON u.id_usuario = o.id_usuario
        WHERE t.id_estado = 1
        ORDER BY t.fecha_inicio ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $torneos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Jugador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="torneo.css" />
  <script>
    const torneosData = <?php echo json_encode($torneos); ?>;
  </script>
  <script src="torneo.js"></script>
</head>
<body>

  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <div class="torneos-wrapper">
    <div class="columna-calendario">
      <h3>Próximas partidas</h3>
      <ul id="calendarioTorneos"></ul>
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
      <div class="modal-body">
      <table id="tablaJugadores">
        <thead>
          <tr>
            <th>Jugadores</th>
            <th>Equipo</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
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
      <button id="btnConfirmar" disabled>✔</button> <!-- botón en pausa -->
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

  <div class="modal" id="modalElegirEquipo">
  <div class="modal-content">
    <span class="close" id="cerrarElegirEquipo">&times;</span>
    <h3>Seleccioná tu equipo</h3>

    <select id="selectEquipoJugador"></select>

    <button id="btnEnviarEquipo" class="btn-primary">Confirmar</button>
  </div>
</div>

</body>
</html>
