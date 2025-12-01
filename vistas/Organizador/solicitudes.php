<?php
include '../connection.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Solicitudes - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="dashboard.js"></script>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="solicitudes.css" />
</head>
<body >
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <nav class="nav-links">
      <a href="dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="../Organizador/perfil/ver.php" class="nav-item"><i class="fa-solid fa-user-gear" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="../Organizador/torneo/mis-torneos.php" class="nav-item"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="ranking.php" class="nav-item active"><i class="fa-solid fa-ranking-star" style="color:#ffb84d;"></i> Ranking</a>
      <a href="solicitudes.php" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>

    <div class="logout">
      <a href="../inicio.php" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </aside>

  <!-- Overlay -->
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- ===== HEADER ===== -->
  <?php include './componentes/header.php'; ?>

  <!-- ===== CONTENIDO ===== -->
  <main class="main-content">
    <section class="solicitudes-section">
      <h2>📩 Solicitudes pendientes</h2>
      <div id="solicitudesContainer" class="solicitudes-container"></div>
    </section>
  </main>


  <script src="solicitudes.js"></script>
</body>
</html>
