document.addEventListener('DOMContentLoaded', () => {
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

  const solicitudes = JSON.parse(localStorage.getItem('solicitudes')) || [
        { id: 1, tipo: 'individual', nombre: 'Jugador1', ranking: 1500, torneo: 'Torneo Legends' },
        { id: 2, tipo: 'individual', nombre: 'Jugador2', ranking: 1200, torneo: 'Torneo Solitario' },
        { id: 3, tipo: 'equipo', nombre: 'Equipo A', ranking: 2000, torneo: 'Torneo FPS' },
        { id: 4, tipo: 'equipo', nombre: 'Equipo B', ranking: 1800, torneo: 'Torneo Legends' }
      ];

      const containerInd = document.getElementById('solicitudesIndividual');
      const containerEq = document.getElementById('solicitudesEquipo');
      const modal = document.getElementById('modalSolicitud');
      const closeModal = document.getElementById('closeModal');
      const modalNombre = document.getElementById('modalNombre');
      const modalRanking = document.getElementById('modalRanking');
      const modalTitulo = document.getElementById('modalTitulo');

      function abrirModal(solicitud){
        modal.style.display = 'flex';
        modalNombre.textContent = solicitud.nombre;
        modalRanking.textContent = solicitud.ranking;
        modalTitulo.textContent = solicitud.torneo;
      }

      closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
      });

      window.addEventListener('click', (e) => {
        if(e.target === modal) modal.style.display = 'none';
      });

      function renderSolicitudes(){
        containerInd.innerHTML = '';
        containerEq.innerHTML = '';

        solicitudes.forEach(s => {
          const card = document.createElement('div');
          card.classList.add('solicitud-card');
          card.innerHTML = `
            <p><i class="fa-solid fa-user"></i> ${s.nombre}</p>
            <p><i class="fa-solid fa-trophy"></i> Ranking: ${s.ranking}</p>
            <p><i class="fa-solid fa-gamepad"></i> Torneo: ${s.torneo}</p>
          `;
          card.addEventListener('click', () => abrirModal(s));
          if(s.tipo === 'individual'){
            containerInd.appendChild(card);
          } else {
            containerEq.appendChild(card);
          }
        });
      }

      renderSolicitudes();

      // Botones aceptar/rechazar
      document.getElementById('btnAceptar').addEventListener('click', () => {
        alert('Solicitud aceptada');
        modal.style.display = 'none';
      });
      document.getElementById('btnRechazar').addEventListener('click', () => {
        alert('Solicitud rechazada');
        modal.style.display = 'none';
      });
});
