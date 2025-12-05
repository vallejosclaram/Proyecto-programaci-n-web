<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];


if (!Permisos::tienePermiso("subir_puntaje", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para subir puntaje"]);
    exit;
}

try {

   
    $sqlJugador = "SELECT id_jugador FROM jugador WHERE id_usuario = :u LIMIT 1";
    $stJ = $conn->prepare($sqlJugador);
    $stJ->execute([":u" => $usuario_id]);
    $jug = $stJ->fetch(PDO::FETCH_ASSOC);

    if (!$jug) {
        $torneos = [];
        throw new Exception("No existe jugadora en la tabla jugador");
    }

    $id_jugador = $jug["id_jugador"];


    // 2) Torneos donde se inscribe individualmente
    $sqlInd = "
        SELECT t.id_torneo, t.nombre
        FROM torneo t
        INNER JOIN torneo_jugador tj ON tj.id_torneo = t.id_torneo
        WHERE tj.id_jugador = :j
    ";
    $stmtInd = $conn->prepare($sqlInd);
    $stmtInd->execute([":j" => $id_jugador]);
    $torneosInd = $stmtInd->fetchAll(PDO::FETCH_ASSOC);


    // 3) Torneos donde participa por equipo
    $sqlEq = "
        SELECT DISTINCT t.id_torneo, t.nombre
        FROM torneo t
        INNER JOIN torneo_equipo te ON te.id_torneo = t.id_torneo
        INNER JOIN miembros_equipo me ON me.id_equipo = te.id_equipo
        WHERE me.id_usuario = :u
    ";
    $stmtEq = $conn->prepare($sqlEq);
    $stmtEq->execute([":u" => $usuario_id]);
    $torneosEq = $stmtEq->fetchAll(PDO::FETCH_ASSOC);


    // 4) Unificar sin duplicar
    $torneos = [];

    foreach ($torneosInd as $t) {
        $torneos[$t["id_torneo"]] = $t["nombre"];
    }
    foreach ($torneosEq as $t) {
        $torneos[$t["id_torneo"]] = $t["nombre"];
    }

} catch (Exception $e) {
    $torneos = [];
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cargar Puntaje</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> 
  <link href="puntaje.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css" />
    <script src="puntaje.js"></script>
</head>
<body>

<?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

  
        <h2 class="text-center mb-4">Cargar resultados</h2>
         

<label for="selectTorneo">Seleccioná un torneo:</label>
<select id="selectTorneo" class="form-select mb-3">
    <option value="">-- Elegí un torneo --</option>
    <?php foreach ($torneos as $id => $nombre): ?>
    <option value="<?= $id ?>"><?= $nombre ?></option>
    <?php endforeach; ?>
</select>


<table class="table table-dark table-striped" id="tablaRondas">
    <thead>
        <tr>
            <th>Partida</th>
            <th>Ronda</th>
            <th>Resultado</th>
        </tr>
    </thead>

    <tbody>
        <?php for ($r = 1; $r <= 15; $r++): ?>
            <tr>
                <td>
                    <span class="nombre-torneo"></span> - Partida <?= $r ?>
                </td>

                <td>
                    Ronda <?= $r ?>
                </td>

                <td>
                    <input 
                        type="checkbox" 
                        class="check-resultado" 
                        data-ronda="<?= $r ?>"
                    >
                </td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>

<button class="btn btn-primary mt-3" id="btnGuardarResultados">Guardar Resultados</button>



<div class="mt-4">
  <h5>Subir evidencia (captura)</h5>
  <input type="file" id="fileEvidencia" accept="image/jpeg,image/png,image/webp" class="form-control mb-2">
  <button class="btn btn-secondary" id="btnSubirEvidencia">Subir evidencia</button>
  <small class="text-muted d-block mt-2">
    Formatos permitidos: JPG, PNG, WEBP. Máx: 8MB.
  </small>
</div>



        
    
  </div>
</div>


</body>
</html>
