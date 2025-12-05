document.addEventListener('DOMContentLoaded', () => {
  const torneosPropios = document.getElementById('torneosPropios');
  const torneosOtros = document.getElementById('torneosOtros');
  const filtroJuego = document.getElementById('filtroJuego');
  const filtroEquipo = document.getElementById('filtroEquipo');
  const calendarioTorneos = document.getElementById('calendarioTorneos');

  // Modales
  const modalJugadores = document.getElementById('modalJugadores');
  const closeJugadores = document.getElementById('closeJugadores');
  const tablaJugadoresBody = document.getElementById('tablaJugadores').querySelector('tbody');

  const modalDenuncia = document.getElementById('modalDenuncia');
  const closeDenuncia = document.getElementById('closeDenuncia');
  const motivoSelect = document.getElementById('motivoSelect');
  const btnConfirmar = document.getElementById('btnConfirmar');
  const modalConfirmacion = document.getElementById('modalConfirmacion');
  const closeConfirmacion = document.getElementById('closeConfirmacion');
  const btnCerrarConfirmacion = document.getElementById('btnCerrarConfirmacion');

  // Sidebar
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

  // Input para denunciar
  let inputOtro = document.createElement('input');
  inputOtro.type = 'text';
  inputOtro.placeholder = 'Escribí el motivo';
  inputOtro.style.display = 'none';
  inputOtro.style.marginTop = '10px';
  motivoSelect.parentNode.appendChild(inputOtro);

  let torneosPropiosData = [];
  let torneosOtrosData = [];
  let torneoSeleccionado = null;

  // Carga torneos
  async function cargarTorneos() {
    try {
      const res1 = await fetch('process-torneo.php');             // torneos propios
      torneosPropiosData = await res1.json();
      console.log(torneosPropiosData);
      const res2 = await fetch('process-torneos-otros.php');      // torneos no inscripto
      torneosOtrosData = await res2.json();
      console.log(torneosOtrosData);
      renderTorneos();
      renderCalendario();

    } catch (err) {
      console.error('Error cargando torneos:', err);
    }
  }


   function renderTorneos() {
  const juegoFilter = filtroJuego.value.toLowerCase();
  const equipoFilter = filtroEquipo.value.toLowerCase();

  torneosPropios.innerHTML = '';
  torneosOtros.innerHTML = '';



  torneosPropiosData.forEach(t => {
    if (!pasaFiltros(t, juegoFilter, equipoFilter)) return;

    const card = crearCardTorneo(t);
    torneosPropios.appendChild(card);
  });

  
  

  torneosOtrosData.forEach(t => {
    if (!pasaFiltros(t, juegoFilter, equipoFilter)) return;

    const card = crearCardTorneo(t);
    torneosOtros.appendChild(card);
  });
}

function pasaFiltros(t, juegoFilter, equipoFilter) {
  const juego = t.juego?.toLowerCase() || '';
  const equipo = t.nombre?.toLowerCase() || '';

  if (juegoFilter && !juego.includes(juegoFilter)) return false;
  if (equipoFilter && !equipo.includes(equipoFilter)) return false;

  return true;
}

function crearCardTorneo(t) {
  const card = document.createElement('div');
  card.classList.add('torneo-card');

  card.innerHTML = `
    <h4>${t.nombre}</h4>
    <div class="torneo-info">
      <p>Juego: ${t.juego}</p>
      <p>Organizador: ${t.organizador_nombre} ${t.organizador_apellido}</p>
      <p>Tipo: ${t.tipo_torneo}</p>
      <p>Inscripción cierra: ${t.fecha_fin}</p>
      <p>Inicio: ${t.fecha_inicio}</p>
    </div>

    <div class="torneo-actions">
      <button class="btn-denunciar">Denunciar</button>
    </div>
  `;

  const accionesDiv = card.querySelector('.torneo-actions');

  
  //VER JUGADORES
  
  /*card.querySelector('.btn-ver-jugadores')
      .addEventListener('click', () => verJugadores(t.id_torneo));*/

 
  //DENUNCIAR
 
  const btnDenunciar = card.querySelector('.btn-denunciar');
  
    btnDenunciar.addEventListener('click', () => {
      torneoSeleccionado = t;
      motivoSelect.value = '';
      inputOtro.value = '';
      inputOtro.style.display = 'none';
      btnConfirmar.disabled = true;
      modalDenuncia.style.display = 'block';
    });
 

  
  //SUMARME
  
  

  if (t.inscripta > 0) {
    if (t.estado_inscripcion === 'pendiente') {
      btnSumarme.textContent = 'Pendiente';
    } else if(t.estado_inscripcion === 'aceptado') {
      btnSumarme.textContent = 'Aceptado';
    } else if (t.estado_inscripcion === 'rechazado') {
      btnSumarme.textContent = 'Rechazado';
    }
    btnSumarme.disabled = true;
  } else {
    const btnSumarme = document.createElement('button');
    btnSumarme.textContent = 'Sumarme';
    btnSumarme.classList.add('btn-sumarse', 'btn-primary');
    btnSumarme.addEventListener('click', async () => {
      try {
       
        const res = await fetch('procesar-solicitud.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_torneo: t.id_torneo })
        });

        const data = await res.json();
        
        if (data.success) {
          btnSumarme.textContent = 'Pendiente';
          btnSumarme.disabled = true;
          btnSumarme.classList.add('enviado');
        } else {
          alert('Error: ' + data.error);
        }
      } catch (err) {
        console.error(err);
        alert('Error enviando la solicitud.');
      }
    });
  }

  accionesDiv.appendChild(btnSumarme);

  return card;
}



  // Ver jugadores
  async function verJugadores(idTorneo) {
    try {
      const formData = new FormData();
      formData.append('id_torneo', idTorneo);

      const res = await fetch('procesar-ver-jugadores.php', {
        method: 'POST',
        body: formData
      });

      const jugadores = await res.json();
      tablaJugadoresBody.innerHTML = '';

      if (jugadores.error) {
        tablaJugadoresBody.innerHTML = `<tr><td colspan="2">${jugadores.error}</td></tr>`;
      } else if (jugadores.length === 0) {
        tablaJugadoresBody.innerHTML = `<tr><td colspan="2">No hay jugadores inscriptos</td></tr>`;
      } else {
        jugadores.forEach(ju => {
          const tr = document.createElement('tr');
          tr.innerHTML = `<td>${ju.nombre}</td><td>${ju.equipo ?? '-'}</td>`;
          tablaJugadoresBody.appendChild(tr);
        });
      }

      modalJugadores.style.display = 'inherit';
    } catch (err) {
      console.error(err);
    }
  }

  // Cerrar modales
  closeJugadores.addEventListener('click', () => modalJugadores.style.display = 'none');
  window.addEventListener('click', e => { if (e.target === modalJugadores) modalJugadores.style.display = 'none'; });

 closeDenuncia.addEventListener('click', () => {
  modalDenuncia.style.display = 'none';
});
  closeConfirmacion.addEventListener('click', () => modalConfirmacion.style.display = 'none');
  btnCerrarConfirmacion.addEventListener('click', () => modalConfirmacion.style.display = 'none');

  // Denuncia
  motivoSelect.addEventListener('change', () => {
    if (motivoSelect.value === 'otro') {
      inputOtro.style.display = 'block';
      btnConfirmar.disabled = inputOtro.value.trim() === '';
    } else {
      inputOtro.style.display = 'none';
      btnConfirmar.disabled = motivoSelect.value === '';
    }
  });

  inputOtro.addEventListener('input', () => {
    btnConfirmar.disabled = inputOtro.value.trim() === '';
  });

  // Enviar denuncia
  btnConfirmar.addEventListener('click', async () => {
    if (!torneoSeleccionado) return;

    let descripcion =
      motivoSelect.value === 'otro'
        ? inputOtro.value.trim()
        : motivoSelect.value;

    if (!descripcion) return;
    

    try {
      const res = await fetch('procesar-denuncia.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id_organizador: torneoSeleccionado.organizador,
          id_reportado: torneoSeleccionado.id_torneo,
          descripcion
        })
      });

      const data = await res.json();
      modalDenuncia.style.display = 'none';
    modalConfirmacion.style.display = 'block';
      
    } catch (err) {
      
      console.error(err);
    }
  });

  // Calendario
  function renderCalendario() {
    calendarioTorneos.innerHTML = '';

  const proximosPropios = torneosPropiosData
    .filter(t => new Date(t.fecha_inicio) >= new Date()) // solo futuros
    .sort((a, b) => new Date(a.fecha_inicio) - new Date(b.fecha_inicio)); // ordenar por fecha

  proximosPropios.forEach(t => {
    const li = document.createElement('li');
    li.textContent = `${t.nombre} - Inicio: ${t.fecha_inicio}`;
    calendarioTorneos.appendChild(li);
  });
  }

  filtroJuego.addEventListener('input', renderTorneos);
  filtroEquipo.addEventListener('input', renderTorneos);

  cargarTorneos();
});
