<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Denuncias</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="denuncias.css" />
  <script src="denuncias.js"></script>
</head>
<body>
  
 <?php require_once __DIR__ . '/../componentes/dashboardAdmin.php'; ?>

  <main class="main-content" id="mainContent">
    <h1>Denuncias</h1>
    <div class="filtros-denuncias">
    <input type="text" id="busquedaGlobal" placeholder="Buscar por usuario, torneo u organización...">
    <select id="filtroDenuncias">
      <option value="todas">Todas</option>
      <option value="1">1 denuncia</option>
      <option value="2">2 denuncias</option>
      <option value="3+">Más de 3</option>
    </select>
  </div>

  <div class="denuncias-secciones">
    
    <section class="denuncias-bloque">
      <h2>Denuncias de Usuarios</h2>
      <div class="denuncias-container" id="denunciasUsuariosContainer"></div></section>

   
    <section class="denuncias-bloque">
      <h2>Denuncias de Torneos</h2>
      <div class="denuncias-container" id="denunciasTorneosContainer"></div>
  </section>
  </div>

  </main>

</body>
</html>
