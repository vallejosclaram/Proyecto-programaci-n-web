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
  <link rel="stylesheet" href="style-organizador.css" />
  <script src="solicitudes.js"></script>
</head>
<body >
 
<?php require_once __DIR__ . '/../componentes/dashboardOrganizador.php'; ?>

  <main class="main-content">
    <section class="solicitudes-section">
      <h2>📩 Solicitudes pendientes</h2>
      <div id="solicitudesContainer" class="solicitudes-container"></div>
    </section>
  </main>


  
</body>
</html>
