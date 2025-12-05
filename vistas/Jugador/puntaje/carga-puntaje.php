<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 
session_start();

if (!isset($_SESSION["id_usuario"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["id_usuario"];
$rol = $_SESSION["rol"] ?? '';
$nombre = $_SESSION["nombre"] ?? '';

if (!Permisos::tienePermiso("subir_puntaje", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para unirte a un torneo"]);
    exit;
}

try {
    
    $sqlTorneos = "
        SELECT DISTINCT t.id_torneo, t.nombre 
        FROM torneo t
        JOIN inscripcion i ON i.id_torneo = t.id_torneo
        WHERE i.id_jugador = :id_jugador
    ";
    $stmt = $conn->prepare($sqlTorneos);
    $stmt->bindParam(':id_jugador', $usuario_id);
    $stmt->execute();
    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    $sqlEquipos = "
        SELECT e.id_equipo, e.nombre 
        FROM equipo_jugador ej
        JOIN equipo e ON e.id_equipo = ej.id_equipo
        WHERE ej.id_jugador = :id_jugador
    ";
    $stmt2 = $conn->prepare($sqlEquipos);
    $stmt2->bindParam(':id_jugador', $usuario_id);
    $stmt2->execute();
    $equipos = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $torneos = [];
    $equipos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cargar Puntaje</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="puntaje.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css" />
    <script src="puntaje.js"></script>
</head>
<body>

<?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card p-4">
        <h2 class="text-center mb-4">Cargar Puntaje</h2>

        <form action="procesar-cargar-puntaje.php" method="POST">

          <div class="mb-3">
            <label for="torneo" class="form-label">Seleccionar Torneo</label>
            <select class="form-select" id="torneo" name="id_torneo" required>
              <option value="">Elegí un torneo</option>
              <?php foreach ($torneos as $t): ?>
                <option value="<?= $t['id_torneo'] ?>"><?= $t['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="equipo" class="form-label">Seleccionar Equipo</label>
            <select class="form-select" id="equipo" name="id_equipo" required>
              <option value="">Elegí tu equipo</option>
              <?php foreach ($equipos as $e): ?>
                <option value="<?= $e['id_equipo'] ?>"><?= $e['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="puntaje" class="form-label">Puntaje Obtenido</label>
            <input type="number" min="0" class="form-control" id="puntaje" name="puntaje_obtenido" required>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-3">
            Guardar Puntaje
          </button>

        </form>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
