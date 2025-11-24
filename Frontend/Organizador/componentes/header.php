<header class="topbar">
  <div class="topbar-inner">
    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
      <i class="fa-solid fa-bars"></i>
    </button>

    <div class="topbar-center">
      <span class="topbar-logo">🎮 UPE-SPORT</span>
    </div>

    <?php if (!empty($organizador)): ?>
      <div id="usuarioResumen" class="usuario-resumen">
        <div id="usuarioNombre">
          Hola, <?= htmlspecialchars($organizador['nombre'] ?? 'Usuario') ?>
        </div>
        <small id="usuarioRol" class="text-muted">Organizador</small>
      </div>
    <?php endif; ?>
  </div>
</header>
