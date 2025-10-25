<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Jugador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="crear-torneo.css" />
  <script src="crear-torneo.js"></script>
</head>
<body>
 
<?php require_once __DIR__ . '/../../componentes/dashboardOrganizador.php'; ?>

<div class="form-container">
    <h2>Crear Nuevo Torneo</h2>
    <form id="formTorneo">
      <div class="form-group">
        <label for="nombre">Nombre del Torneo</label>
        <input type="text" id="nombre" name="nombre" placeholder="Torneo Legends" required>
      </div>

      <div class="form-group">
        <label for="juego">Juego</label>
        <input type="text" id="juego" name="juego" placeholder="League of Legends" required>
      </div>

      <div class="form-group">
        <label for="tipo">Tipo de Torneo</label>
        <select id="tipo" name="tipo" required>
          <option value="">Seleccionar</option>
          <option value="individual">Individual</option>
          <option value="equipo">En equipo</option>
        </select>
      </div>

      <div class="form-group">
        <label for="fechaInscripcion">Fecha de Inscripción</label>
        <input type="date" id="fechaInscripcion" name="fechaInscripcion" required>
      </div>

      <div class="form-group">
        <label for="estado">Estado</label>
        <select id="estado" name="estado" required>
          <option value="">Seleccionar</option>
          <option value="abierto">Abierto</option>
          <option value="cerrado">Cerrado</option>
        </select>
      </div>

      <button type="submit" class="btn-submit">Crear Torneo</button>
    </form>
  </div>


</body>
</html>