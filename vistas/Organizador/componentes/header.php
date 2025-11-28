<?php
include __DIR__ . '/../../connection.php';

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
      <div id="usuarioResumen" class="usuario-resumen">
        <div id="usuarioNombre">
          Hola, <?= htmlspecialchars($usuarioNombre) ?>
        </div>
        <small id="usuarioRol" ><?= htmlspecialchars($usuarioRol) ?></small>
      </div>
    <?php endif; ?>
  </div>
</header>
