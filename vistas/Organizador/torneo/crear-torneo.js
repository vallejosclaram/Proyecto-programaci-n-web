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

  });


  document.getElementById('formTorneo').addEventListener('submit', (e) => {
      e.preventDefault();
      const torneo = {
        nombre: document.getElementById('nombre').value,
        juego: document.getElementById('juego').value,
        tipo: document.getElementById('tipo').value,
        fechaInscripcion: document.getElementById('fechaInscripcion').value,
        estado: document.getElementById('estado').value
      };

      let torneos = JSON.parse(localStorage.getItem('torneos')) || [];
      torneos.push(torneo);
      localStorage.setItem('torneos', JSON.stringify(torneos));
      alert('Torneo creado con éxito');
      e.target.reset();
    });