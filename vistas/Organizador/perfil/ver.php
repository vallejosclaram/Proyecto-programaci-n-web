<?php
include '../../connection.php';
session_start();

// Verificar sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  header('Location: ../../auth/login.php');
  exit;
}

// Obtener datos del organizador por id_usuario
$stmtOrg = $conn->prepare('SELECT * FROM organizador WHERE id_usuario = ? LIMIT 1');
$stmtOrg->execute([$id_usuario]);
$organizador = $stmtOrg->fetch(PDO::FETCH_ASSOC);

if (!$organizador) {
  echo "<p>No se encontró tu perfil de organizador.</p>";
  exit;
}

// Obtener email del usuario
$stmtUser = $conn->prepare('SELECT email FROM usuario WHERE id_usuario = ? LIMIT 1');
$stmtUser->execute([$id_usuario]);
$userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
$organizador['email'] = $userRow['email'] ?? null;

// Contar torneos creados por este organizador
$stmtCount = $conn->prepare('SELECT COUNT(*) as total FROM torneo WHERE id_organizador = ?');
$stmtCount->execute([$organizador['id_organizador']]);
$countRow = $stmtCount->fetch(PDO::FETCH_ASSOC);
$torneos_creados = $countRow['total'] ?? 0;

// Contar jugadores inscritos en todos sus torneos (torneo_jugador)
$stmtJug = $conn->prepare('SELECT COUNT(*) as inscritos FROM torneo_jugador tj JOIN torneo t ON tj.id_torneo = t.id_torneo WHERE t.id_organizador = ?');
$stmtJug->execute([$organizador['id_organizador']]);
$jugRow = $stmtJug->fetch(PDO::FETCH_ASSOC);
$jugadores_inscritos = $jugRow['inscritos'] ?? 0;
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
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <nav class="nav-links">
      <a href="../dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="ver.php" class="nav-item"><i class="fa-solid fa-user-gear" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="../torneo/mis-torneos.php" class="nav-item"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="../../equipo/equipo.php" class="nav-item"><i class="fa-solid fa-people-group" style="color:#4dffb8;"></i> Equipos</a>
      <a href="../ranking.php" class="nav-item active"><i class="fa-solid fa-ranking-star" style="color:#ffb84d;"></i> Ranking</a>
      <a href="../solicitudes.php" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>

    <div class="logout">
      <a href="../../inicio.php" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </aside>

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
    <button class="btn-cambiar-avatar">Cambiar avatar</button>
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

    <div class="perfil-botones">
      <?php
        // Determinar rol actual (si existe)
        $rol_actual = $_SESSION['rol'] ?? null;
        $id_usuario_actual = $_SESSION['id_usuario'] ?? null;
      ?>
        
        <button id="editProfileBtn" onclick="window.location.href='editar.php'">Editar Perfil</button>
        <button onclick="window.location.href='../torneo/mis-torneos.php'">Ver Torneos</button>
        <button onclick="window.location.href='../configuracion.php'">Configuración</button>
      
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
