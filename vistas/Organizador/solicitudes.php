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
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="solicitudes.css" />
</head>
<body >


  <!-- ===== HEADER ===== -->
   <?php include './componentes/header.php'; ?>

  <!-- ===== CONTENIDO ===== -->
  <main class="main-content">
    <?php if (isset($_GET['ok'])): ?>
      <div class="alert alert-success text-center mt-3">
          ✔ Solicitud aceptada correctamente
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['rechazada'])): ?>
        <div class="alert alert-danger text-center mt-3">
            ✖ Solicitud rechazada
        </div>
    <?php endif; ?>
    <section class="solicitudes-section">
      <h2>📩 Solicitudes pendientes</h2>
      <div id="solicitudesContainer" class="solicitudes-container"></div>
    </section>
  </main>
      <script src="solicitudes.js"></script>
</body>
</html>
