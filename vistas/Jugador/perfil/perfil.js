document.addEventListener('DOMContentLoaded', () => {
      const jugador = JSON.parse(localStorage.getItem('jugador')) || {};

      if (!jugador || !jugador.email) {
        document.getElementById('mainContent').innerHTML = `
          <h1>Perfil no disponible</h1>
          <p>No se encontraron datos de jugador. Iniciá sesión nuevamente.</p>
          <a href="../auth/login.html" class="btn btn-warning mt-3">Ir al login</a>
        `;
        return;
      }

      document.getElementById('perfilUsuario').textContent = jugador.usuario || '-';
      document.getElementById('perfilEmail').textContent = jugador.email || '-';
      document.getElementById('perfilNombre').textContent = jugador.nombre || '-';
      document.getElementById('perfilApellido').textContent = jugador.apellido || '-';
      document.getElementById('perfilDescripcion').textContent = jugador.descripcion || '-';

      const sidebar = document.getElementById('sidebar');
      const menuToggle = document.getElementById('menuToggle');
      const closeBtn = document.getElementById('closeBtn');
      const body = document.body;

      menuToggle.addEventListener('click', () => {
        sidebar.classList.add('open');
        body.classList.add('menu-open');
      });

      closeBtn.addEventListener('click', () => {
        sidebar.classList.remove('open');
        body.classList.remove('menu-open');
      });
    });