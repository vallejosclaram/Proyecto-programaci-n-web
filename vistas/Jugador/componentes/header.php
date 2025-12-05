   <aside class="sidebar" id="sidebar"> 
      <div class="sidebar-header"> 
        <span class="logo">🎮 UPE-SPORT</span> 
        <button class="close-btn" id="closeBtn">✖</button> 
      </div> 
      <nav class="nav-links" role="navigation" aria-label="Menú principal"> 
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/dashboard.php" class="nav-item ">🏠 Dashboard</a> 
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/perfil/ver.php" class="nav-item">👤 Mi perfil</a> 
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/equipo/equipos.php" class="nav-item">🛡️ Equipos</a> 
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/torneo/torneo.php" class="nav-item">🏆 Torneos</a> 
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/ranking.php" class="nav-item">📊 Ranking</a>
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/partidas/partidas.php" class="nav-item">🖥️ Partidas</a>
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/puntaje/carga-puntaje.php" class="nav-item">🎯 Puntaje</a>
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/soporte/soporte.php" class="nav-item">🙋‍♀️ Soporte</a>
        <a href="/Proyecto-programaci-n-web/vistas/Jugador/perfil/membresia.php" class="nav-item">🎟️ Membresia</a>
      </nav> 
      <div class="logout"> 
        <a href="/Proyecto-programaci-n-web/vistas/auth/logout.php" class="nav-item logout-btn">🚪 Cerrar sesión</a>
      </div> 
    </aside> 
    
    <div id="sidebarOverlay" class="sidebar-overlay" tabindex="-1" aria-hidden="true"></div>
             
    <header class="topbar"> 
      
      <div class="topbar-inner"> 
        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú" title="Abrir menú">☰</button> 
        
        <div class="topbar-center"> 
          <span class="topbar-logo">🎮 UPE-SPORT</span> 
        </div> 
        
        <div id="usuarioResumen" class="usuario-resumen" aria-live="polite"> 
          <div id="usuarioNombre">Hola, <?php echo htmlspecialchars($nombre); ?></div>
          <small id="usuarioRol" class="text-muted"><?php echo htmlspecialchars($rol); ?></small>
        </div> 
      </div>
    </header> 