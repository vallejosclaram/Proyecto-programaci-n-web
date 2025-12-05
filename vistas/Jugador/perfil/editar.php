<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 

session_start();

// Verificamos si está logueado
if (!isset($_SESSION["id_usuario"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["id_usuario"];
$rol = $_SESSION["rol"] ?? '';
$nombre = $_SESSION["nombre"] ?? '';




if (!Permisos::tienePermiso('Editar perfil', $usuario_id)) {
    echo json_encode(['success' => false, 'error' => 'No tenés permiso para editar el perfil']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Perfil - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="perfil.css" />
  <script src="editar.js"></script>
</head>
<body>

  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <h1>✏️ Editar Perfil</h1>

    <form id="editarPerfilForm" class="profile-form">

      <label for="email" class="mb-3 mt-3">Email</label>
      <input type="email" id="email" name="email" class="form-control" />

      <label for="nombre" class="mb-3 mt-3">Nombre</label>
      <input type="text" id="nombre" name="nombre" class="form-control"/>

      <label for="apellido" class="mb-3 mt-3">Apellido</label>
      <input type="text" id="apellido" class="form-control" name="apellido" />

      <label for="descripcion" class="mb-3 mt-3">Descripción</label>
      <textarea id="descripcion" class="form-control" name="descripcion" rows="3"></textarea>
     
       
    <div class="mt-3">
    <h4>Vincular cuenta de juego</h4>

    <input class="row form-control" 
           id="nicknameInput" 
           type="text"
           placeholder="Ingresá tu nickname de Valorant o CS2">

    <button class="btn btn-success mt-2" id="btnVincular">
        Vincular cuenta
    </button>

    <p id="vincularMsg" class="mt-2 text-info"></p>
    </div>
   
       <div class="perfil-botones mt-3">
      <div class="perfil-actions">
        <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>

        <a href="ver.php" class="btn btn-primary mt-3">Cancelar</a>
      </div>
      </div>

    </form>

  </main>



  <!-- Modal -->
  <div class="modal fade" id="modalGuardado" tabindex="-1" aria-labelledby="modalGuardadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalGuardadoLabel">✅ Perfil actualizado</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          Tus datos fueron guardados correctamente.
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <a href="ver.php" class="btn btn-primary">Volver al perfil</a>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
