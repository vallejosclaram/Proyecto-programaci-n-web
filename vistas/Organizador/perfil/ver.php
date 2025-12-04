<?php
include '../../connection.php';
include '../../../Backend/organizador/perfil.php';

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
</head>
<body >

  <!-- Overlay -->
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- ===== HEADER  ===== -->
  <?php include '../componentes/header.php'; ?>

  <?php if (isset($_GET['actualizado'])): ?>
    <div class="alert alert-success text-center">✅ Perfil actualizado correctamente.</div>
  <?php endif; ?>
  <main class="main-content" id="mainContent">
  <div class="perfil-container" data-perfil-key="organizador_<?= $organizador['id_organizador'] ?>">
    <div class="avatar-section">
  <div class="avatar-wrapper">
    <img src="../img/avatar.jpeg" alt="Avatar del organizador" class="avatar-img" />
  </div>
  <h2 class="organizador-nombre">
    <?= htmlspecialchars($organizador['nombre'] . ' ' . $organizador['apellido']) ?>
  </h2>
  <p class="organizador-correo"><?= htmlspecialchars($organizador['email'] ?? 'Sin correo disponible') ?></p>
</div>
    <div class="perfil-info">
      <div class="info-card">
        <h4>Torneos Creados</h4>
        <p><?php echo (int)($torneos_creados ?? 0); ?></p>
      </div>
      <div class="info-card">
        <h4>Jugadores Inscritos</h4>
        <p><?php echo (int)($jugadores_inscritos ?? 0); ?></p>
      </div>
      <div class="info-card">
        <h4>Equipos Inscritos</h4>
        <p><?php echo $equipos_inscritos; ?></p>
      </div>


    <div class="perfil-botones">
      <?php
        // Determinar rol actual (si existe)
        $rol_actual = $_SESSION['rol'] ?? null;
        $id_usuario_actual = $_SESSION['id_usuario'] ?? null;
      ?>
        
        <button id="editProfileBtn" onclick="window.location.href='editar.php'">Editar Perfil</button>
        <button onclick="window.location.href='../torneo/mis-torneos.php'">Ver Torneos</button>
        <button onclick="window.location.href='configuracion.php'">Configuración</button>
      
    </div>

    <div class="perfil-descripcion">
      <h3>Descripción</h3>
      <p><?= htmlspecialchars($organizador['descripcion']) ?></p>
    </div>
  </div>
</main>
    <script src="perfil.js"></script>
</body>
</html>
