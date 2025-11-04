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
<body >

  <?php require_once __DIR__ . '/../../componentes/dashboardJugador.php'; ?>

<main class="main-content" id="mainContent">
  <div class="perfil-container">
    <div class="avatar-section">
  <div class="avatar-wrapper">
    <img src="../img/avatar.jpeg" alt="Avatar del organizador" class="avatar-img" />
    <button class="btn-cambiar-avatar">Cambiar avatar</button>
  </div>
  <h2 class="organizador-nombre">Jugador</h2>
  <p class="organizador-correo">correo@ejemplo.com</p>
</div>
    <div class="perfil-info">
      <div class="info-card">
        <h4>Torneos</h4>
        <p>8</p>
      </div>
      <div class="info-card">
        <h4>Seguidores</h4>
        <p>56</p>
      </div>
      <div class="info-card">
        <h4>Ranking General</h4>
        <p>#12</p>
      </div>
    </div>

    <div class="perfil-botones">
      <button id="editProfileBtn">Editar Perfil</button>
      <button>Ver Torneos</button>
    </div>

    <div class="perfil-descripcion">
      <h3>Descripción</h3>
      <p></p>
    </div>
  </div>
</main>


</body>
</html>