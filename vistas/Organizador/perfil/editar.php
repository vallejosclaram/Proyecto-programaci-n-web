<?php
include '../../connection.php';

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

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <nav class="nav-links">
      <a href="../dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="ver.php" class="nav-item"><i class="fa-solid fa-user-gear" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="../torneo/mis-torneos.php" class="nav-item"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="../../equipo/equipo.php" class="nav-item"><i class="fa-solid fa-people-group" style="color:#4dffb8;"></i> Equipos</a>
      <a href="../ranking.php" class="nav-item active"><i class="fa-solid fa-ranking-star" style="color:#ffb84d;"></i> Ranking</a>
      <a href="../solicitudes.php" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>
    <div class="logout">
      <a href="../../inicio.php" class="nav-item logout-btn">🚪 Cerrar sesión</a>
    </div>
  </aside>

    <!-- Overlay -->
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- ===== HEADER  ===== -->
  <?php include '../componentes/header.php'; ?>

  <main class="main-content" id="mainContent">
    <h1>✏️ Editar Perfil</h1>
    <form action="../../../Backend/organizador/actualizar_perfil.php" method="POST" enctype="multipart/form-data">

      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($organizador['nombre']); ?>" />

      <label>Apellido</label>
      <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($organizador['apellido']); ?>"/>


      <label>Email</label>
      <input  class="form-control" value="<?php echo htmlspecialchars($organizador['email']); ?>" required/>

      <label>Descripción</label>
      <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($organizador['descripcion']); ?></textarea>

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
