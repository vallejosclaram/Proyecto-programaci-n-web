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

  const torneoId = 0; 
    let torneos = JSON.parse(localStorage.getItem('torneos')) || [];
    const torneo = torneos[torneoId];

    
    if(torneo){
      document.getElementById('nombre').value = torneo.nombre || '';
      document.getElementById('juego').value = torneo.juego || '';
      document.getElementById('tipo').value = torneo.tipo || '';
      document.getElementById('fechaInscripcion').value = torneo.fechaInscripcion || '';
      document.getElementById('estado').value = torneo.estado || '';
    }

    
    document.getElementById('formEditarTorneo').addEventListener('submit', (e)=>{
      e.preventDefault();
      torneos[torneoId] = {
        nombre: document.getElementById('nombre').value,
        juego: document.getElementById('juego').value,
        tipo: document.getElementById('tipo').value,
        fechaInscripcion: document.getElementById('fechaInscripcion').value,
        estado: document.getElementById('estado').value
      };
      localStorage.setItem('torneos', JSON.stringify(torneos));
      alert('Torneo actualizado con éxito');
    });

  });


