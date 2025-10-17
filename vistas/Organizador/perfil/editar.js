document.addEventListener('DOMContentLoaded', () => {
      const organizador = JSON.parse(localStorage.getItem('organizador')) || {};

      document.getElementById('usuario').value = organizador.usuario || '';
      document.getElementById('email').value = organizador.email || '';
      document.getElementById('nombre').value = organizador.nombre || '';
      document.getElementById('apellido').value = organizador.apellido || '';
      document.getElementById('descripcion').value = organizador.descripcion || '';

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
          password: organizador.password,
          rol: organizador.rol
        };

        localStorage.setItem('organizador', JSON.stringify(actualizado));

        const modal = new bootstrap.Modal(document.getElementById('modalGuardado'));
        modal.show();
      });
    });