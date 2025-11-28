<?php
include '../connection.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Organizador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css" />
</head>
<body >
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <nav class="nav-links">
      <a href="dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="perfil/ver.php" class="nav-item"><i class="fa-solid fa-user-gear" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="../Organizador/torneo/mis-torneos.php" class="nav-item"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="../Organizador/equipo/equipo.php" class="nav-item"><i class="fa-solid fa-people-group" style="color:#4dffb8;"></i> Equipos</a>
      <a href="ranking.php" class="nav-item active"><i class="fa-solid fa-ranking-star" style="color:#ffb84d;"></i> Ranking</a>
      <a href="solicitudes.php" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>

    <div class="logout">
      <a href="../inicio.php" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </aside>

  <!-- Overlay -->
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- ===== HEADER / TOPBAR ===== -->
  <?php include 'componentes/header.php'; ?>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
    <h1>Panel del Organizador</h1>

    <div class="row g-4">
      <div class="col-md-4">
        <a class="card card-link" href="torneo/mis-torneos.php">
          <i class="fa-solid fa-trophy" style="color:#ffb84d;"></i>
          <h4>Torneos Activos</h4>
          <p>3 torneos en curso</p>
        </a>
      </div>
      <div class="col-md-4">
        <a class="card card-link" href="equipo/equipo.php">
          <i class="fa-solid fa-users" style="color:#4dffb8;"></i>
          <h4>Equipos Registrados</h4>
          <p>12 equipos totales</p>
        </a>
      </div>
      <div class="col-md-4">
        <a class="card card-link" href="solicitudes.php">
          <i class="fa-solid fa-bell" style="color:#ff4d94;"></i>
          <h4>Solicitudes Pendientes</h4>
          <p>5 por revisar</p>
        </a>
      </div>
    </div>

    <div class="row g-4 mt-4">
      <div class="col-md-6">
        <a class="card card-link" href="ranking.php">
          <i class="fa-solid fa-chart-pie" style="color:#c84dff;"></i>
          <h4>Ranking</h4>
          <p>Visualiza rendimiento y participación</p>
        </a>
      </div>

      <div class="col-md-6">
        <a class="card card-link" href="torneo/mis-torneos.php">
          <i class="fa-solid fa-plus-circle" style="color:#5ecbff;"></i>
          <h4>Crear Nuevo Torneo</h4>
          <p>Comienza un nuevo evento competitivo</p>
        </a>
      </div>
    </div>
        <!-- ===== ACTIVIDAD RECIENTE ===== -->
    <section class="actividad-reciente mt-5">
      <h2>Actividad Reciente</h2>
      <ul id="actividadLista" class="actividad-lista">
        <!-- Se rellena dinámicamente desde JS -->
      </ul>
    </section>
  </main>

  <script src="dashboard.js"></script>
</body>
</html>
