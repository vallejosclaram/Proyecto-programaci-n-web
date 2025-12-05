<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 

session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];
exit;
if (!Permisos::tienePermiso("Crear equipo", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para crear un equipo"]);
    exit;
}

// Validar ID recibido
if (!isset($_GET["id"])) {
    die("Error: falta el ID del equipo.");
}

$equipo_id = intval($_GET["id"]);

// Traer datos del equipo
$sql = "SELECT * FROM equipo WHERE id_equipo = ? AND id_capitan_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$equipo_id, $usuario_id]);
$equipo = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$equipo) {
 
    die("Error: no se encontró el equipo o no tenés permisos.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Equipo - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css" />

  <script src="editar-equipo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="dashboard-page">
  <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="form-container">
      <h2>Editar Equipo</h2>

      <form id="editarEquipoForm" class="perfil-form" method="POST" action="">

        <input type="hidden" name="id_equipo" value="<?php echo $equipo["id_equipo"]; ?>">

        <label for="nombreEquipo" class="mt-3 mb-3">Nombre del equipo</label>
        <input 
            type="text" 
            name="nombreEquipo" 
            id="nombreEquipo" 
            class="form-control" 
            value="<?php echo htmlspecialchars($equipo['nombre']); ?>" 
            required 
        />

        <label for="cantidadJugadores" class="mt-3 mb-3">Cantidad de jugadores (máximo 10)</label>
        <input 
            type="number" 
            name="cantidadJugadores" 
            id="cantidadJugadores" 
            class="form-control" 
            min="1" 
            max="10"
            value="<?php echo htmlspecialchars($equipo['cantidad_jugadores']); ?>"
            required 
        />

        <label for="juegoEquipo" class="mt-3 mb-3">Juego</label>
        <select name="juegoEquipo" id="juegoEquipo" class="form-select" required>
            <option value="">Cargando juegos...</option>
        </select>

        <script>
         
          const juegoSeleccionado = "<?php echo $equipo['id_juego']; ?>";
        </script>

        <label for="descripcionEquipo" class="mt-3 mb-3">Descripción</label>
        <textarea 
            name="descripcionEquipo" 
            id="descripcionEquipo" 
            class="form-control" 
            rows="3"
        ><?php echo htmlspecialchars($equipo['descripcion']); ?></textarea>

        <div class="d-flex justify-content-between mt-4">
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <a href="equipos.php" class="btn btn-secondary">Volver</a>
        </div>

      </form>
    </div>
  </main>

  <!-- Modal -->
  <div class="modal fade" id="modalEditado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title">Equipo actualizado</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          Los cambios fueron guardados correctamente.
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <a href="equipos.php" class="btn btn-primary">Ir a mis equipos</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
