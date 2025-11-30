<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$logueado_id = $_SESSION["user"]["id"];

$jugador_id = isset($_GET['id']) ? (int)$_GET['id'] : $logueado_id;


$stmtJugador = $conn->prepare("SELECT nombre, apellido FROM jugador WHERE id_usuario = :id");
$stmtJugador->execute([':id' => $jugador_id]);
$jugador = $stmtJugador->fetch(PDO::FETCH_ASSOC);
if (!$jugador) {
    die("Jugador no encontrado.");
}


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
        JOIN solicitud_torneo st ON st.id_torneo = t.id_torneo
        WHERE st.id_usuario = :jugador_id
        ORDER BY t.fecha_inicio ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':jugador_id' => $jugador_id]);
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
  <title>Torneos de <?php echo htmlspecialchars($jugador['nombre']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="../torneo/torneo.css" />
  <script src="../torneo/torneo.js"></script>
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
      <input type="text" id="filtroEquipo" placeholder="Filtrar por tipo">
    </div>

    <h3>Torneos donde está <?php echo htmlspecialchars($jugador['nombre']); ?></h3>
    <div class="torneos-grid h-scroll" id="torneosPropios">
      <?php foreach ($torneos as $t): ?>
        <div class="torneo-card">
          <h4><?php echo htmlspecialchars($t['nombre']); ?></h4>
          <p>Juego: <?php echo htmlspecialchars($t['juego']); ?></p>
          <p>Tipo: <?php echo htmlspecialchars($t['tipo_torneo']); ?></p>
          <p>Organizador: <?php echo htmlspecialchars($t['organizador_nombre'] . ' ' . $t['organizador_apellido']); ?></p>
          <p>Fecha inicio: <?php echo htmlspecialchars($t['fecha_inicio']); ?></p>
        </div>
      <?php endforeach; ?>
      <?php if (empty($torneos)): ?>
        <p>No hay torneos para mostrar.</p>
        <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
