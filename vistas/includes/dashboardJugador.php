<?php

$script = $_SERVER['SCRIPT_NAME'] ?? '/';
$pos = strpos($script, '/vistas');
if ($pos !== false) {
  $BASE = substr($script, 0, $pos + strlen('/vistas'));
} else {
  
  $BASE = '/TP-LB-2025/Proyecto-programaci-n-web/vistas';
}

$BASE = rtrim($BASE, "/");
?>

<body>
    <div id="sidebarOverlay" class="sidebar-overlay" tabindex="-1" aria-hidden="true"></div>
             
    <header class="topbar"> 
      
      <div class="topbar-inner"> 
        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú" title="Abrir menú">☰</button> 
        
        <div class="topbar-center"> 
          <span class="topbar-logo">🎮 UPE-SPORT</span> 
        </div> 
        
        <div id="usuarioResumen" class="usuario-resumen" aria-live="polite"> 
          <div id="usuarioNombre">Hola, Jugador</div> 
          <small id="usuarioRol" class="text-muted"></small> 
        </div> 
      </div> 

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn">✖</button>
    </div>
    <nav class="nav-links">
    
      <a href="<?php echo $BASE; ?>/Jugador/dashboard.php" class="nav-item active">🏠 Dashboard</a>

      <a href="<?php echo $BASE; ?>/Jugador/perfil/ver.php" class="nav-item">👤 Mi perfil</a>

      <a href="<?php echo $BASE; ?>/Jugador/equipo/equipos.php" class="nav-item">🛡️ Equipos</a>

 
      <a href="<?php echo $BASE; ?>/Jugador/torneo/torneo.php" class="nav-item">🏆 Torneos</a>

      <a href="<?php echo $BASE; ?>/Jugador/ranking.php" class="nav-item">📊 Ranking</a>

      <a href="<?php echo $BASE; ?>/Jugador/partidas/partidas.php" class="nav-item">🖥️ Partidas</a>

      <a href="<?php echo $BASE; ?>/Jugador/soporte/soporte.php" class="nav-item">🙋‍♀️ Soporte</a>

      <a href="<?php echo $BASE; ?>/Jugador/puntaje/carga-puntaje.php" class="nav-item">🎯 Puntaje</a>

      <a href="<?php echo $BASE; ?>/Jugador/perfil/membresia.php" class="nav-item">🎟️ Membresía</a>
      
      <a href="<?php echo $BASE; ?>/Jugador/perfil/membresia.php" class="nav-item">🔔 Notificaciones</a>


    </nav>
    <div class="logout">
      <a href="<?php echo $BASE; ?>/auth/logout.php" class="nav-item logout-btn">🚪 Cerrar sesión</a>
    </div>
  </aside>

  

  
</body>
</html>
