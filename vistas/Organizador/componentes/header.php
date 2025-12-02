<?php
__DIR__ . '/../../connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioNombre = 'Usuario';
$usuarioRol = '';

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("
        SELECT r.nombre_rol 
        FROM rol r
        JOIN usuario_rol ur ON ur.id_rol = r.id_rol
        WHERE ur.id_usuario = ?
        LIMIT 1
    ");
    $stmt->execute([$id_usuario]);
    $rol = $stmt->fetchColumn();

    $usuarioRol = $rol ?: 'Usuario';

    if ($rol === 'organizador') {
        $stmt = $conn->prepare("SELECT nombre, apellido FROM organizador WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $org = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($org) {
            $usuarioNombre = $org['nombre'];
        }
    } else {
        $usuarioNombre = $_SESSION['email'] ?? 'Usuario';
    }
}
?>


<header class="topbar">
  <div class="topbar-inner">
    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
      <i class="fa-solid fa-bars"></i>
    </button>

    <div class="topbar-center">
      <span class="topbar-logo">🎮 UPE-SPORT</span>
    </div>

    <?php if (isset($_SESSION['id_usuario'])): ?>

      <div id="usuarioResumen" class="usuario-resumen" style="display:flex; align-items:center; gap:20px;">
      
          

          <div class="usuario-text" style="display:flex; flex-direction:column;">
            <a href="http://localhost/Proyecto-programaci-n-web/vistas/Organizador/perfil/ver.php" 
              id="usuarioGreeting"
              style="text-decoration:none; color:inherit; cursor:pointer;">
              Hola, <?= htmlspecialchars($usuarioNombre) ?>
            </a>
            <div id="usuarioRol"><?= htmlspecialchars($usuarioRol) ?></div>
          </div>

          <!-- Campanita de notificaciones (izquierda del nombre) -->
          <div class="notificaciones" style="position:relative; display:inline-block; vertical-align:middle;">
            <button id="btnNoti" class="btn-noti" aria-label="Notificaciones" style="background:none;border:none;color:inherit;">
              <i class="fa-solid fa-bell"></i>
              <span id="notiCount" class="noti-count" style="display:none; background:#ff4d4d; color:#fff; border-radius:12px; padding:2px 6px; font-size:12px; position:relative; left:6px; top:-10px;">0</span>
            </button>
            <div id="notiDropdown" class="noti-dropdown" style="display:none; position:absolute; right:0; top:28px; background:#fff; border:1px solid #ddd; width:320px; max-height:360px; overflow:auto; box-shadow:0 6px 18px rgba(0,0,0,0.1); z-index:9999;">
              <div style="padding:8px; border-bottom:1px solid #eee; font-weight:600;">Solicitudes</div>
              <div id="notiList" style="padding:8px;"></div>
              <div id="notiEmpty" style="padding:12px; color:#666; display:none;">No hay solicitudes.</div>
            </div>
          </div>
          
      </div>
      <?php endif; ?>
    </div>
</header>

<style>
  /* Estilos para el bloque de usuario en el header */
  .usuario-resumen .usuario-text {
    display:flex;
    flex-direction:column;
    align-items:center; /* centrar el rol debajo del saludo */
  }

  #usuarioGreeting {
    font-family: 'Orbitron', sans-serif;
    font-size: 16px; /* un poco más grande */
    margin-right: 10px;
    font-weight:600;
    line-height:1.1;
  }

  #usuarioRol {
    font-family: 'Orbitron', sans-serif;
    font-size: 13px;
    color:#666;
    margin-left: 4px;
    margin-top:5px;
  }
</style>
<script src="header.js"></script>
