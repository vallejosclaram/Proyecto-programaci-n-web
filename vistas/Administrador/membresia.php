<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

if (empty($_SESSION['admin']['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['admin']['id'];
$rol = $_SESSION['admin']['rol'] ?? null;

if ($rol != 1) {
    echo "Acceso restringido";
    exit;
}

if (!Permisos::tienePermiso('⁠otorgar_membresia', $id_usuario)) {
    header('Location: ../error.php?msg=No tenés permiso para otorgar membresías');
    exit;
}

$sql = "
    SELECT s.id_membresia AS id_solicitud,
           s.id_usuario,
           s.membresia,
           s.estado,
           s.comprobante,
           j.nombre,
           j.apellido
    FROM solicitud_membresia s
    LEFT JOIN jugador j ON s.id_usuario = j.id_usuario
    WHERE s.estado = 0
";
$stmt = $conn->query($sql);
$solicitudes = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Solicitudes de Membresía</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
</head>
<body class="dashboard-page">
  <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?>

  <main class="main-content" id="mainContent">
    <div class="form-container">
      <h2>Solicitudes de membresía</h2>

      <div id="alerts"></div>

      <?php if (empty($solicitudes)): ?>
        <p>No hay solicitudes pendientes.</p>
      <?php else: ?>
        <table class="table table-dark table-hover mt-3">
          <thead>
            <tr>
              <th># Solicitud</th>
              <th>ID Usuario</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Membresía solicitada</th>
              <th>Comprobante</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($solicitudes as $s): ?>
              <tr>
                <td><?= htmlspecialchars($s['id_solicitud']) ?></td>
                <td><?= htmlspecialchars($s['id_usuario']) ?></td>
                <td><?= htmlspecialchars($s['nombre']) ?></td>
                <td><?= htmlspecialchars($s['apellido']) ?></td>
                <td><?= htmlspecialchars($s['membresia']) ?></td>
                <td><?= htmlspecialchars($s['comprobante']) ?></td>
                <td>
                  <button type="button"
                          class="btn btn-success btn-accion"
                          data-id="<?= htmlspecialchars($s['id_solicitud']) ?>"
                          data-accion="aceptar">
                    Aceptar
                  </button>
                  <button type="button"
                          class="btn btn-danger btn-accion"
                          data-id="<?= htmlspecialchars($s['id_solicitud']) ?>"
                          data-accion="rechazar">
                    Rechazar
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </main>

  <!-- Modal de confirmación -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title">Confirmar acción</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="confirmMessage">¿Estás seguro de realizar esta acción?</p>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="confirmBtn">Confirmar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="membresia.js"></script>
</body>
</html>
