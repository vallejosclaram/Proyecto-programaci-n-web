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
  <script src="dashboard.js"></script>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="style-organizador.css" />
  
</head>
<body >
  
    <?php require_once __DIR__ . '/../componentes/dashboardOrganizador.php'; ?>


  <main class="main-content">
    <h1>Panel del Organizador</h1>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card card-link" data-link="torneos-activos.html">
          <i class="fa-solid fa-trophy" style="color:#ffb84d;"></i>
          <h4>Torneos Activos</h4>
          <p>3 torneos en curso</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-link" data-link="equipos.html">
          <i class="fa-solid fa-users" style="color:#4dffb8;"></i>
          <h4>Equipos Registrados</h4>
          <p>12 equipos totales</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-link" data-link="solicitudes.html">
          <i class="fa-solid fa-bell" style="color:#ff4d94;"></i>
          <h4>Solicitudes Pendientes</h4>
          <p>5 por revisar</p>
        </div>
      </div>
    </div>

    <div class="row g-4 mt-4">
      <div class="col-md-6">
        <div class="card card-link" data-link="estadisticas.html">
          <i class="fa-solid fa-chart-pie" style="color:#c84dff;"></i>
          <h4>Ranking</h4>
          <p>Visualiza rendimiento y participación</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card card-link" data-link="crear-torneo.html">
          <i class="fa-solid fa-plus-circle" style="color:#5ecbff;"></i>
          <h4>Crear Nuevo Torneo</h4>
          <p>Comienza un nuevo evento competitivo</p>
        </div>
      </div>
    </div>

    <section class="actividad-reciente mt-5">
      <h2>Actividad Reciente</h2>
      <ul id="actividadLista" class="actividad-lista"></ul>
    </section>
  </main>

</body>
</html>
