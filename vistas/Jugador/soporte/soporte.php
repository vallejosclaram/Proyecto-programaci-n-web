<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

// Verificamos si está logueado
if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$id_usuario = $_SESSION["user"]["id"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Soporte - Crear Ticket</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto&display=swap" rel="stylesheet" />
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="crear-ticket.js"></script>
</head>

<body>

  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="hero-card">

    <div class="text-center mb-4">
      <h2>Soporte Técnico</h2>
      <p class="text-muted small">Creá una consulta y nuestro equipo te responderá.</p>
    </div>

    <form id="formCrearTicket" class="text-start" method="POST" action="">
      <input type="hidden" id="idUsuario" value="<?= htmlspecialchars($id_usuario) ?>">

      <div class="mb-3">
        <label for="asunto" class="form-label">Asunto</label>
        <input type="text" class="form-control" id="asunto" name="asunto" placeholder="¿Cuál es el problema?" required />
        <div class="invalid-feedback d-none" id="errorAsunto">Ingresá un asunto válido.</div>
      </div>

      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Contanos más detalles..." required></textarea>
        <div class="invalid-feedback d-none" id="errorDescripcion">La descripción no puede estar vacía.</div>
      </div>

      <button type="submit" class="btn w-100 btn-primary">Enviar consulta</button>
    </form>

    <div class="mt-3 text-center small text-muted">
      ¿Querés volver? <a href="../index.php">Ir al inicio</a>
    </div>

  </div>
</div>

<!-- Modal de éxito -->
<div class="modal fade" id="ticketExitoso" tabindex="-1" aria-labelledby="ticketExitosoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketExitosoLabel">Consulta enviada</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Tu ticket fue creado correctamente. Te notificaremos cuando sea respondido.
      </div>
      <div class="modal-footer">
        <a href="../index.php" class="btn btn-primary">Volver al inicio</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
