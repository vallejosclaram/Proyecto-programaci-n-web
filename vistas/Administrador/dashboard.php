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
    <p>Usá el menú para acceder a tus funcionalidades.</p>
  </main>

  <script src="dashboard.js"></script>
</body>
</html>
