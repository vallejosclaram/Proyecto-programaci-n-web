<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Jugador</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="soporte.css" />
  <script src="soporte.js"></script>
</head>
<body>
 
<?php require_once __DIR__ . '/../componentes/dashboardOrganizador.php'; ?>

 <div class="soporte-container">
    <h2>Soporte</h2>
    <form id="formSoporte">
      <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="tu@email.com" required>
      </div>

      <div class="form-group">
        <label for="asunto">Asunto</label>
        <input type="text" id="asunto" name="asunto" placeholder="Ej: Problema con un torneo" required>
      </div>

      <div class="form-group">
        <label for="mensaje">Mensaje</label>
        <textarea id="mensaje" name="mensaje" placeholder="Describe tu queja o comentario..." required></textarea>
      </div>

      <button type="submit" class="btn-submit">Enviar</button>
    </form>
  </div>


</body>
</html>