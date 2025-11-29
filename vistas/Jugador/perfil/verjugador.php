<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];


$sql = "
    SELECT 
        j.nombre,
        j.apellido,
        u.email,
        j.biografia AS descripcion,
        j.puntaje AS ranking_general,

        (
            SELECT COUNT(*) 
            FROM equipo e
            WHERE e.id_capitan_usuario = j.id_jugador
        ) AS equipos_capitan,

        (
            SELECT COUNT(*) 
            FROM miembros_equipo me
            WHERE me.id_usuario = j.id_jugador
        ) AS equipos_miembro

    FROM usuario u
    INNER JOIN jugador j ON j.id_usuario = u.id_usuario
    WHERE u.id_usuario = :usuario_id
";

$stmt = $conn->prepare($sql);
$stmt->execute([":usuario_id" => $usuario_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (count($result) === 0) {
    die("Error: no se encontró el jugador asociado.");
}

$jugador = $result[0];

$avatar = !empty($jugador["avatar"]) ? "../img/avatars/" . $jugador["avatar"] : "../img/avatar.jpeg";

?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="perfil.css" />
  <script src="perfil.js"></script>
</head>
<body>

<?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

<main class="main-content" id="mainContent">
  <div class="perfil-container">

   
    <div class="avatar-section">
      <div class="avatar-wrapper">
        <img src="<?php echo $avatar; ?>" alt="Avatar del jugador" class="avatar-img" />
        
      </div>

      <h2 class="organizador-nombre">
        <?php echo htmlspecialchars($jugador["nombre"] . " " . $jugador["apellido"]); ?>
      </h2>

      <p class="organizador-correo"><?php echo htmlspecialchars($jugador["email"]); ?></p>
    </div>

    
    <div class="perfil-info">
      <div class="info-card">
        <h4>Torneos</h4>
        <p><?php echo $jugador["equipos_capitan"]; ?></p>
        <p><?php echo $jugador["equipos_miembro"]; ?></p>
      </div>

    <!--  <div class="info-card">
        <h4>Seguidores</h4>
        <p><?php echo $jugador["cantidad_seguidores"]; ?></p>
      </div> -->

      <div class="info-card">
        <h4>Ranking General</h4>
        <p><?php echo $jugador["ranking_general"]; ?></p>
      </div>
    </div>

    <!-- Botones -->
    <div class="perfil-botones">
      <button><a href="<?php echo $BASE; ?>/Jugador/torneo/torneo.php">Ver Torneos</a></button>
    </div>

    <!-- Descripción -->
    <div class="perfil-descripcion">
      <h3>Descripción</h3>
      <p>
        <?php echo !empty($jugador["descripcion"]) ? nl2br(htmlspecialchars($jugador["descripcion"])) : "Este jugador aún no agregó una descripción."; ?>
      </p>
    </div>

  </div>
</main>

</body>
</html>
