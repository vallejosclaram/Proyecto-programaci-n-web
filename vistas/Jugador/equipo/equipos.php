<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 

session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];

if (!Permisos::tienePermiso("Denunciar torneo", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para crear un equipo"]);
    exit;
}

if (!Permisos::tienePermiso("solicitar_equipo", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para crear un equipo"]);
    exit;
}



$sql = "SELECT id_cuentajuego FROM jugador WHERE id_usuario = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $usuario_id]);
$jugador = $stmt->fetch(PDO::FETCH_ASSOC);
    
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Equipos - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&amp;family=Roboto&amp;display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="equipo.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="equipos.js"></script>
  
</head>

<body>

  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      <h1 class="mb-2">🛡️ Equipos</h1>
      
      <?php if ($jugador["id_cuentajuego"]): ?>

      <a href="crear-equipo.php" class="btn btn-violeta">➕ Crear equipo</a>
      <?php else: ?>
        
          <a href="crear-equipo.php" class="btn btn-violeta" disabled>➕ Crear equipo</a>
        <?php endif; ?>

    </div>

    <form id="formBuscarEquipos" class="row g-3 align-items-end mb-5">
      <div class="col-md-5">
        <label for="buscadorEquipos" class="form-label">Buscar por nombre o juego</label>
        <input type="text" id="buscadorEquipos" class="form-control" placeholder="Ej: Valorant, Titanes..." />
      </div>
      
      <div class="col-md-3 text-end">
        <button type="submit" class="btn btn-violeta w-100">🔎 Buscar</button>
      </div>
    </form>

    <section class="mb-5">
      <h3>📌 Tus equipos</h3>
      <div class="scroll-row" id="misEquipos" name="misEquipos"></div>
    </section>

     <section class="mb-5" id="solicitudesSection">
      <h3>📥 Solicitudes recibidas</h3>
      <div class="scroll-row" id="listaSolicitudes" name="listaSolicitudes"></div>
    </section>

    <section class="mb-5">
      <h3>🧩 Equipos disponibles</h3>
      <div class="scroll-row" id="equiposDisponibles" name="equiposDisponibles"></div>
    </section>

   
  </main>

  <!--modales-->
  
  <div class="modal" id="modalEquipo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalEquipoTitulo">Detalles del equipo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalEquipoContenido"></div>
      </div>
    </div>
  </div>

 

    <div class="modal fade" id="modal-cambiar-capitan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Cambiar Capitan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <select id="select-miembros" class="form-select"></select>
      </div>

      <div class="modal-footer">
        <button id="btn-guardar-capitan" class="btn btn-primary">Guardar cambios</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-sin-permiso" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">No sos capitan de este equipo</h5>
        
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

      <div class="modal-footer">
        <p>Solo el capitan puede cambiar de capitán</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-cambiado" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Capitan cambiado</h5>
        
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

      <div class="modal-footer">
        <p>Ya no sos el capitan</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-confirmacion" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
    </div>
  </div>
</div>



  
</body>
</html>