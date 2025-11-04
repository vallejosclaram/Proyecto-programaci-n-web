<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil - UPE-SPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="perfil.css" />
</head>
<body >
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <span class="logo">🎮 UPE-SPORT</span>
      <button class="close-btn" id="closeBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <nav class="nav-links">
      <a href="../dashboard.html" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="../perfil/ver.html" class="nav-item"><i class="fa-solid fa-user-gear" style="color:#c84dff;"></i> Mi perfil</a>
      <a href="../torneo/mis-torneos.html" class="nav-item"><i class="fa-solid fa-trophy" style="color:#ffb84d;"></i> Mis Torneos</a>
      <a href="../equipo/equipo.html" class="nav-item"><i class="fa-solid fa-people-group" style="color:#4dffb8;"></i> Equipos</a>
      <a href="solicitudes.html" class="nav-item"><i class="fa-solid fa-bell" style="color:#ff4d94;"></i> Solicitudes</a>
    </nav>

    <div class="logout">
      <a href="../auth/login.html" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </aside>

  <!-- Overlay -->
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- ===== HEADER / TOPBAR ===== -->
  <header class="topbar">
    <div class="topbar-inner">
      <!-- Botón de menú -->
      <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú"><i class="fa-solid fa-bars"></i></button>

      <!-- Logo central -->
      <div class="topbar-center">
        <span class="topbar-logo">🎮 UPE-SPORT</span>
      </div>

      <div id="usuarioResumen" class="usuario-resumen" aria-live="polite"> 
          <div id="usuarioNombre">Hola, Organizador </div> 
          <small id="usuarioRol" class="text-muted"></small> 
        </div> 
    </div>
  </header>


  <main class="main-content" id="mainContent">
  <div class="perfil-container">
    <div class="avatar-section">
  <div class="avatar-wrapper">
    <img src="../img/avatar.jpeg" alt="Avatar del organizador" class="avatar-img" />
    <button class="btn-cambiar-avatar">Cambiar avatar</button>
  </div>
  <h2 class="organizador-nombre">Nombre del Organizador</h2>
  <p class="organizador-correo">correo@ejemplo.com</p>
</div>
    <div class="perfil-info">
      <div class="info-card">
        <h4>Torneos Creados</h4>
        <p>8</p>
      </div>
      <div class="info-card">
        <h4>Jugadores Inscritos</h4>
        <p>125</p>
      </div>
      <div class="info-card">
        <h4>Seguidores</h4>
        <p>56</p>
      </div>
      <div class="info-card">
        <h4>Ranking General</h4>
        <p>#12</p>
      </div>
    </div>

    <div class="perfil-botones">
      <button id="editProfileBtn">Editar Perfil</button>
      <button>Ver Torneos</button>
      <button>Configuración</button>
    </div>

    <div class="perfil-descripcion">
      <h3>Descripción</h3>
      <p>Organizador activo en la comunidad de UPE-SPORT. Especialista en torneos de FPS y simuladores deportivos.</p>
    </div>
  </div>
</main>

    <script src="perfil.js"></script>
</body>
</html>
