<?php
include '../../connection.php';
session_start();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  header('Location: ../../auth/login.php');
  exit;
}

// Obtener organizador
$stmtOrg = $conn->prepare('SELECT * FROM organizador WHERE id_usuario = ? LIMIT 1');
$stmtOrg->execute([$id_usuario]);
$organizador = $stmtOrg->fetch(PDO::FETCH_ASSOC);

// Obtener email
$stmtUser = $conn->prepare('SELECT email FROM usuario WHERE id_usuario = ? LIMIT 1');
$stmtUser->execute([$id_usuario]);
$userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
$organizador['email'] = $userRow['email'] ?? null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Perfil - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="editar.css">
</head>
<body>

  <!-- ===== HEADER  ===== -->
  <?php include '../componentes/header.php'; ?>

  <main class="main-content" id="mainContent">
    <h1>✏️ Editar Perfil</h1>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger">
        <?php
          $err = $_GET['error'];
          if ($err === 'datos') echo 'Por favor completa los campos requeridos.';
          elseif ($err === 'email') echo 'El email no tiene un formato válido.';
          elseif ($err === 'exists') echo 'El email ya está en uso por otro usuario.';
          elseif ($err === 'exception') echo 'Ocurrió un error en el servidor. Intenta nuevamente.';
          else echo 'Error desconocido.';
        ?>
      </div>
    <?php endif; ?>

    <form action="../../../Backend/organizador/actualizar_perfil.php" method="POST" enctype="multipart/form-data">

      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($organizador['nombre'] ?? ''); ?>" />

      <label>Apellido</label>
      <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($organizador['apellido'] ?? ''); ?>"/>


      <label>Email</label>
      <input name="email" class="form-control" value="<?php echo htmlspecialchars($organizador['email'] ?? ''); ?>" required/>

      <label>Descripción</label>
      <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($organizador['descripcion'] ?? ''); ?></textarea>

      <div class="perfil-actions mt-3">
        <a href="ver.php" class="btn btn-secondary">⬅️ Atrás</a>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </main>

  
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
          <a href="ver.html" class="btn btn-primary">Volver al perfil</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  
<script src="editar.js"></script>
  
</body>
</html>
