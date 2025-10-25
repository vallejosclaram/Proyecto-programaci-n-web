document.addEventListener('DOMContentLoaded', () => {
  const organizador = JSON.parse(localStorage.getItem('organizador')) || {};

  /*if (!organizador || !organizador.email) {
    document.getElementById('mainContent').innerHTML = `
      <h1>Perfil no disponible</h1>
      <p>No se encontraron datos de organizador. Iniciá sesión nuevamente.</p>
      <a href="../auth/login.html" class="btn btn-warning mt-3">Ir al login</a>
    `;
    return;
  }*/

 
  document.getElementById('perfilFoto').src = organizador.foto || '../../componentes/imagenes/perfil-default.png';
  document.getElementById('perfilFoto').alt = `${organizador.nombre} ${organizador.apellido}`;
  document.getElementById('perfilNombreApellido').textContent = `${organizador.nombre || '-'} ${organizador.apellido || '-'}`;
  document.getElementById('perfilDescripcion').textContent = organizador.descripcion || '-';
  document.getElementById('torneosTotales').textContent = organizador.torneosTotales || 0;
  document.getElementById('torneosGanados').textContent = organizador.torneosGanados || 0;
  document.getElementById('torneosPerdidos').textContent = organizador.torneosPerdidos || 0;
  document.getElementById('perfilEmail').textContent = organizador.email || '-';
  document.getElementById('perfilUsuario').textContent = organizador.usuario || '-';

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
