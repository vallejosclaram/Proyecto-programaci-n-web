<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
</head>
<body>

  <?php require_once __DIR__ . '/../../componentes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <section class="perfil-container">
        <h1 class="perfil-title">👤 Mi Perfil</h1>
        <p class="perfil-subtitle">Tu identidad dentro de UPE-SPORT:</p>

        <div class="perfil-grid">
        <div class="perfil-item">
            <span class="perfil-label">Usuario</span>
            <span id="perfilUsuario" class="perfil-value">-</span>
        </div>
        <div class="perfil-item">
            <span class="perfil-label">Email</span>
            <span id="perfilEmail" class="perfil-value">-</span>
        </div>
        <div class="perfil-item">
            <span class="perfil-label">Nombre</span>
            <span id="perfilNombre" class="perfil-value">-</span>
        </div>
        <div class="perfil-item">
            <span class="perfil-label">Apellido</span>
            <span id="perfilApellido" class="perfil-value">-</span>
        </div>
        <div class="perfil-item perfil-descripcion">
            <span class="perfil-label">Descripción</span>
            <p id="perfilDescripcion" class="perfil-value">-</p>
        </div>
        </div>

        <div class="perfil-actions">
        <a href="editar.html" class="btn-editar">Editar perfil</a>
        </div>
    </section>
    </main>


    <script src="perfil.js"></script>
</body>
</html>
