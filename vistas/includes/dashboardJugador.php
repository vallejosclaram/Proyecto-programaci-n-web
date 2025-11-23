
<body>
  <!-- overlay para cuando el sidebar está abierto (clic para cerrar) -->
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
      <a href="dashboard.php" class="nav-item active">🏠 Dashboard</a>
      <a href="perfil/ver.php" class="nav-item">👤 Mi perfil</a>
      <a href="equipo/equipos.php" class="nav-item">🛡️ Equipos</a>
      <a href="torneo/torneo.php" class="nav-item">🏆 Torneos</a>
      <a href="ranking.php" class="nav-item">📊 Ranking</a>
    </nav>
    <div class="logout">
      <a href="../auth/logout.php" class="nav-item logout-btn">🚪 Cerrar sesión</a>
    </div>
  </aside>

  

  
</body>
</html>
