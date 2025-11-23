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

  // Datos de ejemplo de torneos
  const torneos = JSON.parse(localStorage.getItem('torneos')) || [
    {
      id: 1,
      nombre: 'Torneo Legends',
      organizador: 'Yo',
      juego: 'League of Legends',
      tipo: 'equipo',
      jugadoresPorEquipo: 5,
      fechaCierre: '2025-11-30',
      fechaInicio: '2025-12-05'
    },
    {
      id: 2,
      nombre: 'Torneo Solitario',
      organizador: 'Otra Org',
      juego: 'Valorant',
      tipo: 'individual',
      cupos: 32,
      fechaCierre: '2025-11-25',
      fechaInicio: '2025-12-01'
    },
    {
      id: 3,
      nombre: 'Torneo FPS',
      organizador: 'Yo',
      juego: 'CS:GO',
      tipo: 'equipo',
      jugadoresPorEquipo: 5,
      fechaCierre: '2025-11-28',
      fechaInicio: '2025-12-03'
    }
  ];

  const torneosPropios = document.getElementById('torneosPropios');
  const torneosOtros = document.getElementById('torneosOtros');
  const filtroJuego = document.getElementById('filtroJuego');
  const filtroEquipo = document.getElementById('filtroEquipo');

  function renderTorneos() {
    const juegoFilter = filtroJuego.value.toLowerCase();
    const equipoFilter = filtroEquipo.value.toLowerCase();

    torneosPropios.innerHTML = '';
    torneosOtros.innerHTML = '';

    torneos.forEach(t => {
      if ((juegoFilter && !t.juego.toLowerCase().includes(juegoFilter)) ||
          (equipoFilter && !t.nombre.toLowerCase().includes(equipoFilter))) return;

      const card = document.createElement('div');
      card.classList.add('torneo-card');

      card.innerHTML = `
        <h4>${t.nombre}</h4>
        <div class="torneo-info">
          <p>Juego: ${t.juego}</p>
          <p>Organizador: ${t.organizador}</p>
          ${t.tipo === 'equipo' ? `<p>Jugadores por equipo: ${t.jugadoresPorEquipo}</p>` : `<p>Cupos: ${t.cupos}</p>`}
          <p>Inscripción cierra: ${t.fechaCierre}</p>
          <p>Inicio: ${t.fechaInicio}</p>
        </div>
        ${t.organizador.toLowerCase() === 'yo' ? `
        <div class="torneo-actions">
          <button class="btn-editar" id="editar-${t.id}"><i class="fa-solid fa-pencil"></i> Editar</button>
          <button class="btn-ver-jugadores" id="ver-${t.id}"><i class="fa-solid fa-users"></i> Ver Jugadores</button>
        </div>
        ` : ''}
      `;

      if (t.organizador.toLowerCase() === 'yo') {
        torneosPropios.appendChild(card);
      } else {
        torneosOtros.appendChild(card);
      }

      // Eventos
      const btnEditar = card.querySelector(`#editar-${t.id}`);
      if (btnEditar) {
        btnEditar.addEventListener('click', () => {
          alert(`Editar torneo: ${t.nombre}`);
        
        });
      }

      const btnVer = card.querySelector(`#ver-${t.id}`);
      if (btnVer) {
        btnVer.addEventListener('click', () => {
          alert(`Ver jugadores de: ${t.nombre}`);
          
        });
      }
    });
  }

  filtroJuego.addEventListener('input', renderTorneos);
  filtroEquipo.addEventListener('input', renderTorneos);

  renderTorneos();


const jugadoresPorTorneo = {
  1: [
    { nombre: 'Jugador1', ranking: 1200 },
    { nombre: 'Jugador2', ranking: 980 },
    { nombre: 'Jugador3', ranking: 1100 }
  ],
  2: [
    { nombre: 'JugadorA', ranking: 1500 }
  ],
  3: [
    { nombre: 'JugadorX', ranking: 900 },
    { nombre: 'JugadorY', ranking: 870 }
  ]
};

const modalJugadores = document.getElementById('modalJugadores');
const closeJugadores = document.getElementById('closeJugadores');
const tablaJugadoresBody = document.querySelector('#tablaJugadores tbody');

const modalDenuncia = document.getElementById('modalDenuncia');
const closeDenuncia = document.getElementById('closeDenuncia');
const motivoSelect = document.getElementById('motivoSelect');
const btnConfirmar = document.getElementById('btnConfirmar');

const modalConfirmacion = document.getElementById('modalConfirmacion');
const closeConfirmacion = document.getElementById('closeConfirmacion');
const btnCerrarConfirmacion = document.getElementById('btnCerrarConfirmacion');

let jugadorSeleccionado = null;


document.querySelectorAll('.btn-ver-jugadores').forEach(btn=>{
  btn.addEventListener('click', e=>{
    const idTorneo = parseInt(btn.id.split('-')[1]);
    tablaJugadoresBody.innerHTML = '';
    const jugadores = jugadoresPorTorneo[idTorneo] || [];
    jugadores.forEach((j, idx)=>{
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${j.nombre}</td>
        <td>${j.ranking}</td>
        <td><button class="btn-denunciar" data-idx="${idx}" data-torneo="${idTorneo}">Denunciar</button></td>
      `;
      tablaJugadoresBody.appendChild(tr);
    });
    modalJugadores.style.display = 'block';

    // Asignar evento a cada denunciar
    document.querySelectorAll('.btn-denunciar').forEach(b=>{
      b.addEventListener('click', ev=>{
        jugadorSeleccionado = {idx: b.dataset.idx, torneo: b.dataset.torneo};
        modalJugadores.style.display = 'none';
        modalDenuncia.style.display = 'block';
      });
    });
  });
});

// Cerrar modales
closeJugadores.onclick = ()=> modalJugadores.style.display='none';
closeDenuncia.onclick = ()=> modalDenuncia.style.display='none';
closeConfirmacion.onclick = ()=> modalConfirmacion.style.display='none';
btnCerrarConfirmacion.onclick = ()=> modalConfirmacion.style.display='none';

// Confirmar denuncia
btnConfirmar.onclick = ()=>{
  const motivo = motivoSelect.value;
  if(!motivo){
    alert('Selecciona un motivo');
    return;
  }
  modalDenuncia.style.display = 'none';
  modalConfirmacion.style.display = 'block';
  motivoSelect.value = '';
};

// Cerrar modales clic fuera
window.onclick = function(event) {
  if (event.target == modalJugadores) modalJugadores.style.display = "none";
  if (event.target == modalDenuncia) modalDenuncia.style.display = "none";
  if (event.target == modalConfirmacion) modalConfirmacion.style.display = "none";
};


const calendarioTorneos = document.getElementById('calendarioTorneos');

function renderCalendario() {
  calendarioTorneos.innerHTML = '';
  torneos.filter(t => t.organizador.toLowerCase() === 'yo')
          .sort((a,b)=> new Date(a.fechaInicio) - new Date(b.fechaInicio))
          .forEach(t => {
    const li = document.createElement('li');
    li.textContent = `${t.nombre} - Inicio: ${t.fechaInicio}`;
    calendarioTorneos.appendChild(li);
  });
}

renderCalendario();


});