document.addEventListener('DOMContentLoaded', () => {
      const jugador = JSON.parse(localStorage.getItem('jugador')) || {};

      document.getElementById('usuario').value = jugador.usuario || '';
      document.getElementById('email').value = jugador.email || '';
      document.getElementById('nombre').value = jugador.nombre || '';
      document.getElementById('apellido').value = jugador.apellido || '';
      document.getElementById('descripcion').value = jugador.descripcion || '';

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

      document.getElementById('editarPerfilForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const actualizado = {
          usuario: document.getElementById('usuario').value.trim(),
          email: document.getElementById('email').value.trim(),
          nombre: document.getElementById('nombre').value.trim(),
          apellido: document.getElementById('apellido').value.trim(),
          descripcion: document.getElementById('descripcion').value.trim(),
          password: jugador.password,
          rol: jugador.rol
        };

        localStorage.setItem('jugador', JSON.stringify(actualizado));

        const modal = new bootstrap.Modal(document.getElementById('modalGuardado'));
        modal.show();
      });
    });