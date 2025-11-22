document.addEventListener('DOMContentLoaded', () => {
  const torneosPropios = document.getElementById('torneosPropios');
  const torneosOtros = document.getElementById('torneosOtros');
  const filtroJuego = document.getElementById('filtroJuego');
  const filtroEquipo = document.getElementById('filtroEquipo');
  const calendarioTorneos = document.getElementById('calendarioTorneos');
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


  let torneosData = [];

  async function cargarTorneos() {
    try {
      const res = await fetch('process-torneo.php');
      torneosData = await res.json();
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

  torneosData.forEach(t => {
    if ((juegoFilter && !t.juego.toLowerCase().includes(juegoFilter)) ||
        (equipoFilter && !t.nombre.toLowerCase().includes(equipoFilter))) return;

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
        <button class="btn-ver-jugadores" data-id="${t.id_torneo}">Ver Jugadores</button>
        <button class="btn-denunciar">Denunciar</button>
      </div>
    `;

    // Propios y otros
    if (t.id_organizador == '<?php echo $_SESSION["user"]["id"]; ?>') {
      torneosPropios.appendChild(card);
    } else {
      torneosOtros.appendChild(card);
    }

    // Botón Ver Jugadores
    const btnVer = card.querySelector('.btn-ver-jugadores');
    btnVer.addEventListener('click', () => {
      alert(`Ver jugadores del torneo: ${t.nombre}`);
    });

    // Botón Denunciar
    const btnDenunciar = card.querySelector('.btn-denunciar');
    if (t.id_organizador != '<?php echo $_SESSION["user"]["id"]; ?>') {
      btnDenunciar.addEventListener('click', async () => {
        const descripcion = prompt(`Escribí el motivo de la denuncia para: ${t.nombre}`);
        if (!descripcion) return;

        try {
          const res = await fetch('procesar-denuncia.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              id_organizador: t.id_organizador,
              id_reportado: t.id_torneo,
              descripcion
            })
          });

          const data = await res.json();
          if (data.success) {
            alert('Denuncia enviada correctamente.');
          } else {
            alert('Error: ' + data.error);
          }
        } catch (err) {
          console.error(err);
          alert('Error enviando la denuncia.');
        }
      });
    } else {
      btnDenunciar.disabled = true; // no se puede denunciar tu propio torneo
    }
  });
}


  function renderCalendario() {
    calendarioTorneos.innerHTML = '';
    const proximos = torneosData
      .filter(t => new Date(t.fecha_inicio) >= new Date())
      .sort((a,b)=> new Date(a.fecha_inicio) - new Date(b.fecha_inicio));

    proximos.forEach(t => {
      const li = document.createElement('li');
      li.textContent = `${t.nombre} - Inicio: ${t.fecha_inicio}`;
      calendarioTorneos.appendChild(li);
    });
  }

  filtroJuego.addEventListener('input', renderTorneos);
  filtroEquipo.addEventListener('input', renderTorneos);

  cargarTorneos();
});
