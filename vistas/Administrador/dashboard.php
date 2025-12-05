<?php
require_once(__DIR__ . '/../connection.php');
session_start();
if (!isset($_SESSION["admin"]["id"])) {
    die("Error: no hay usuario administrador logueado.");
}

$usuario_id = $_SESSION["admin"]["id"];
$rol = $_SESSION["admin"]["rol"] ?? null;

if ($rol != 1) {
    die("Acceso restringido: no sos administrador.");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Administrador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="dashboard-page">
  <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?>

 

  

  <main class="main-content" id="mainContent">
  <h1>Bienvenido al panel del administrador</h1>
  <p class="text-light">Usá el menú o las tarjetas para acceder a tus funcionalidades.</p>

  <section class="dashboard-cards">
    
    <div class="dashboard-card">
      <h3>Solicitudes</h3>
      <p>Revisá las solicitudes de creación  en torneos.</p>
      <a href="solicitudes.php">Ir a Solicitudes</a>
    </div>
    <div class="dashboard-card">
      <h3>Resultados</h3>
      <p>Validá y actualizá los resultados de los torneos.</p>
      <a href="resultados.php">Ir a Resultados</a>
    </div>
    <div class="dashboard-card">
      <h3>Soporte</h3>
      <p>Respondé tickets enviados por los usuarios desde soporte.</p>
      <a href="soporte.php">Ir a Soporte</a>
    </div>
    <div class="dashboard-card">
      <h3>Denuncias</h3>
      <p>Visualiza las denuncias de usuarios y torneos.</p>
      <a href="denuncias.php">Ir a Denuncias</a>
    </div>
    <div class="dashboard-card">
      <h3>Membresias</h3>
      <p>Visualiza las solicitudes los jugadores.</p>
      <a href="membresia.php">Ir a Membresias</a>
    </div>
  </section>
</main>


  <script src="dashboard.js"></script>
</body>
</html>
