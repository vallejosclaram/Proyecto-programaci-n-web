<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <script src="perfil.js"></script>
  

</head>
<body>
  <?php require_once __DIR__ . '/../../componentes/dashboardOrganizador.php'; ?>

  <main class="main-content" id="mainContent">
    <section class="perfil-container">
        <h1 class="perfil-title">👤 Mi Perfil</h1>

        <div class="perfil-grid">
          <img id="perfilFoto" src="" alt="Foto de perfil" class="perfil-foto">
          <h2 id="perfilNombreApellido">-</h2>
          <p id="perfilDescripcion" class="tagline">-</p>
        <div class="perfil-item">
            <span class="perfil-label">Organización</span>
            <span id="perfilUsuario" class="perfil-value">-</span>
        </div>
        <div class="perfil-item">
            <span class="perfil-label">Email</span>
            <span id="perfilEmail" class="perfil-value">-</span>
        </div>

        <div class="perfil-item perfil-descripcion">
            <span class="perfil-label">Descripción</span>
            <p id="perfilDescripcion" class="perfil-value">-</p>
        </div>

        <div class="stats">
          <p>Torneos realizados: <span id="torneosTotales">0</span></p>
          <p>Ganados: <span id="torneosGanados">0</span> | Perdidos: <span id="torneosPerdidos">0</span></p>
        </div>
        
        <div class="contacto">
          <p>Email: <span id="perfilEmail">-</span></p>
        </div>

        </div>

        <div class="perfil-actions">
        <a href="editar.php" class="btn-editar">Editar perfil</a>
        </div>
    </section>
    </main>
</body>
</html>
