document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;
  if (sidebar && menuToggle && closeBtn) {
    menuToggle.addEventListener('click', () => {
      sidebar.classList.add('open');
      body.classList.add('menu-open');
    });
    closeBtn.addEventListener('click', () => {
      sidebar.classList.remove('open');
      body.classList.remove('menu-open');
    });
  }

  let torneos = JSON.parse(localStorage.getItem('torneos')) || [];
  const jugador = JSON.parse(localStorage.getItem('jugador')) || { usuario: 'invitado' };

  const formBuscar = document.getElementById('formBuscarTorneos');
  const inputBuscar = document.getElementById('buscadorTorneos');
  const filtroJuego = document.getElementById('filtroJuego');
  const listaTorneos = document.getElementById('listaTorneos');
  const misTorneos = document.getElementById('misTorneos');
  const calendarioTorneos = document.getElementById('calendarioTorneos');
  const modalTitulo = document.getElementById('modalTorneoTitulo');
  const modalContenido = document.getElementById('modalTorneoContenido');
  const seccionResultados = document.getElementById('seccionResultados');
  const resultadoFormContainer = document.getElementById('resultadoFormContainer');
  const seccionDenuncias = document.getElementById('seccionDenuncias');
  const denunciaContainer = document.getElementById('denunciaContainer');

  function saveTorneos() { localStorage.setItem('torneos', JSON.stringify(torneos)); }
  function escapeHtml(text) {
    return String(text || '').replace(/[&<>"'`=\/]/g, s => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
      "'": '&#39;', '/': '&#x2F;', '`': '&#x60;', '=': '&#x3D;'
    })[s]);
  }

  function estaInscripto(torneo) {
    if (!torneo) return false;
    if (torneo.tipo === 'individual') {
      return Array.isArray(torneo.inscriptos) && torneo.inscriptos.includes(jugador.usuario);
    } else {
      return Array.isArray(torneo.inscriptosEquipos) && torneo.inscriptosEquipos.some(eq => Array.isArray(eq.miembros) && eq.miembros.includes(jugador.usuario));
    }
  }

  function crearCardTorneo(torneo, esPropio) {
    const card = document.createElement('div');
    card.className = 'card mb-3 p-3';
    card.innerHTML = `
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h5 class="mb-1">${escapeHtml(torneo.nombre)}</h5>
          <p class="mb-1"><strong>Juego:</strong> ${escapeHtml(torneo.juego)}</p>
          <p class="mb-1"><strong>Tipo:</strong> ${escapeHtml(torneo.tipo)}</p>
          <p class="mb-1 text-truncate" style="max-width:520px;"><strong>Fecha:</strong> ${escapeHtml(torneo.fecha || 'Sin fecha')}</p>
        </div>
        <div class="text-end">
          <button class="btn btn-sm btn-secondary mb-2" onclick="verTorneo('${torneo.id}')">Ver torneo</button>
          ${esPropio ? `<button class="btn btn-sm btn-outline-secondary" onclick="irACalendarizar('${torneo.id}')">Calendario</button>` : ''}
        </div>
      </div>
    `;
    return card;
  }

  function mostrarListas() {
    const texto = (inputBuscar?.value || '').toLowerCase();
    const juego = (filtroJuego?.value || '').toLowerCase();

    if (listaTorneos) listaTorneos.innerHTML = '';
    if (misTorneos) misTorneos.innerHTML = '';
    if (calendarioTorneos) calendarioTorneos.innerHTML = '';
    if (resultadoFormContainer) resultadoFormContainer.innerHTML = '';
    if (denunciaContainer) denunciaContainer.innerHTML = '';
    if (seccionResultados) seccionResultados.style.display = 'none';
    if (seccionDenuncias) seccionDenuncias.style.display = 'none';

    torneos.forEach(t => {
      const matchesText = texto === '' || (t.nombre && t.nombre.toLowerCase().includes(texto)) || (t.juego && t.juego.toLowerCase().includes(texto));
      const matchesJuego = juego === '' || (t.juego && t.juego.toLowerCase() === juego);
      if (!matchesText || !matchesJuego) return;

      const esPropio = estaInscripto(t) || (t.organizador === jugador.usuario);
      if (esPropio) {
        misTorneos?.appendChild(crearCardTorneo(t, true));
        if (calendarioTorneos) {
          const calItem = document.createElement('div');
          calItem.className = 'card mb-2 p-2';
          calItem.innerHTML = `<strong>${escapeHtml(t.nombre)}</strong> — ${escapeHtml(t.fecha || 'Sin fecha')} — ${escapeHtml(t.tipo)}`;
          calendarioTorneos.appendChild(calItem);
        }
      } else {
        listaTorneos?.appendChild(crearCardTorneo(t, false));
      }
    });

    if (listaTorneos && listaTorneos.children.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'text-muted';
      empty.innerText = 'No hay torneos disponibles que coincidan con la búsqueda.';
      listaTorneos.appendChild(empty);
    }
    if (misTorneos && misTorneos.children.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'text-muted';
      empty.innerText = 'No estás inscripto en torneos que coincidan con la búsqueda.';
      misTorneos.appendChild(empty);
    }
    if (calendarioTorneos && calendarioTorneos.children.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'text-muted';
      empty.innerText = 'No hay entradas en tu calendario para la búsqueda actual.';
      calendarioTorneos.appendChild(empty);
    }
  }

  window.verTorneo = function (id) {
    const torneo = torneos.find(t => t.id === id);
    if (!torneo) return alert('Torneo no encontrado');
    if (!modalTitulo || !modalContenido) return;

    modalTitulo.textContent = torneo.nombre;
    const inscripto = estaInscripto(torneo);
    const esOrganizador = torneo.organizador === jugador.usuario;

    let contenido = `
      <p><strong>Juego:</strong> ${escapeHtml(torneo.juego)}</p>
      <p><strong>Tipo:</strong> ${escapeHtml(torneo.tipo)}</p>
      <p><strong>Fecha:</strong> ${escapeHtml(torneo.fecha || 'Sin fecha')}</p>
      <p><strong>Formato:</strong> ${escapeHtml(torneo.formato || 'Sin formato')}</p>
      <p><strong>Organizador:</strong> ${escapeHtml(torneo.organizador || 'Desconocido')}</p>
      <hr>
    `;

    if (!inscripto) {
      if (torneo.tipo === 'individual') {
        contenido += `<button class="btn btn-outline-success mb-2" id="btnInscribirIndividual">Inscribirme individual</button>`;
      } else {
        contenido += `<button class="btn btn-outline-success mb-2" id="btnInscribirEquipo">Inscribir equipo</button>`;
      }
    } else {
      contenido += `<div class="mb-2"><span class="badge bg-success">Ya inscripto</span></div>`;
      contenido += `<button class="btn btn-outline-info mb-2" id="btnCheckin">Realizar check-in</button>`;
    }

    contenido += `
      <div class="d-grid gap-2 mt-3">
        <button class="btn btn-outline-secondary" id="btnVerBracket">Visualizar bracket</button>
        <button class="btn btn-outline-warning" id="btnCargarResultado">Enviar resultado de partido</button>
        <button class="btn btn-outline-danger" id="btnDenunciar">Denunciar torneo</button>
      </div>
    `;

    if (esOrganizador) {
      contenido += `<hr><div><strong>Acciones de organizador</strong><div class="mt-2"><button class="btn btn-sm btn-primary" id="btnGestionarCarga">Ver sección de carga de resultados</button></div></div>`;
    }

    modalContenido.innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('modalTorneo'));
    modal.show();

    setTimeout(() => {
      const btnInscribirIndividual = document.getElementById('btnInscribirIndividual');
      const btnInscribirEquipo = document.getElementById('btnInscribirEquipo');
      const btnCheckin = document.getElementById('btnCheckin');
      const btnVerBracket = document.getElementById('btnVerBracket');
      const btnCargarResultado = document.getElementById('btnCargarResultado');
      const btnDenunciar = document.getElementById('btnDenunciar');
      const btnGestionarCarga = document.getElementById('btnGestionarCarga');

      if (btnInscribirIndividual) {
        btnInscribirIndividual.addEventListener('click', () => {
          torneo.inscriptos = torneo.inscriptos || [];
          if (torneo.inscriptos.includes(jugador.usuario)) return alert('Ya estás inscripto');
          torneo.inscriptos.push(jugador.usuario);
          saveTorneos();
          alert('Inscripción individual realizada');
          mostrarListasSafe();
          modal.hide();
        });
      }

      if (btnInscribirEquipo) {
        btnInscribirEquipo.addEventListener('click', () => {
          const equipo = prompt('Ingresá el nombre de tu equipo para inscribirlo');
          if (!equipo) return alert('Operación cancelada');
          torneo.inscriptosEquipos = torneo.inscriptosEquipos || [];
          torneo.inscriptosEquipos.push({ nombre: equipo, miembros: [jugador.usuario] });
          saveTorneos();
          alert('Equipo inscripto en el torneo');
          mostrarListasSafe();
          modal.hide();
        });
      }

      if (btnCheckin) {
        btnCheckin.addEventListener('click', () => {
          torneo.checkins = torneo.checkins || [];
          if (torneo.checkins.includes(jugador.usuario)) return alert('Ya realizaste check-in');
          torneo.checkins.push(jugador.usuario);
          saveTorneos();
          alert('Check-in realizado');
          mostrarListasSafe();
          modal.hide();
        });
      }

      if (btnVerBracket) {
        btnVerBracket.addEventListener('click', () => {
          alert('Mostrando bracket (placeholder). Implementar visualizador real si hace falta.');
        });
      }

      if (btnCargarResultado) {
        btnCargarResultado.addEventListener('click', () => {
          if (seccionResultados) seccionResultados.style.display = 'block';
          if (resultadoFormContainer) {
            resultadoFormContainer.innerHTML = `
              <div class="card p-3">
                <h6>Enviar resultado para ${escapeHtml(torneo.nombre)}</h6>
                <label class="form-label">Rival / Equipo o Jugador</label>
                <input type="text" id="resRival" class="form-control mb-2" />
                <label class="form-label">Tu puntaje</label>
                <input type="number" id="resTu" class="form-control mb-2" />
                <label class="form-label">Puntaje rival</label>
                <input type="number" id="resRivalScore" class="form-control mb-2" />
                <button class="btn btn-violeta mt-2" id="btnEnviarResultado">Enviar resultado</button>
              </div>
            `;
            const btnEnviarResultado = document.getElementById('btnEnviarResultado');
            btnEnviarResultado?.addEventListener('click', () => {
              const rival = document.getElementById('resRival')?.value;
              const tu = document.getElementById('resTu')?.value;
              const rivalScore = document.getElementById('resRivalScore')?.value;
              if (!rival || tu === '' || rivalScore === '') return alert('Completá todos los campos');
              torneo.resultados = torneo.resultados || [];
              torneo.resultados.push({ quien: jugador.usuario, rival, tu: Number(tu), rivalScore: Number(rivalScore), fecha: new Date().toISOString() });
              saveTorneos();
              alert('Resultado enviado (será verificado por organizador)');
              if (resultadoFormContainer) resultadoFormContainer.innerHTML = '';
              if (seccionResultados) seccionResultados.style.display = 'none';
            });
          }
          modal.hide();
        });
      }

      if (btnDenunciar) {
        btnDenunciar.addEventListener('click', () => {
          if (seccionDenuncias) seccionDenuncias.style.display = 'block';
          if (denunciaContainer) {
            denunciaContainer.innerHTML = `
              <div class="card p-3">
                <h6>Denunciar ${escapeHtml(torneo.nombre)}</h6>
                <label class="form-label">Motivo</label>
                <textarea id="motivoDenuncia" class="form-control mb-2"></textarea>
                <button class="btn btn-danger" id="btnEnviarDenuncia">Enviar denuncia</button>
              </div>
            `;
            document.getElementById('btnEnviarDenuncia')?.addEventListener('click', () => {
              const motivo = document.getElementById('motivoDenuncia')?.value.trim();
              if (!motivo) return alert('Describí el motivo');
              torneo.denuncias = torneo.denuncias || [];
              torneo.denuncias.push({ quien: jugador.usuario, motivo, fecha: new Date().toISOString() });
              saveTorneos();
              alert('Denuncia enviada al organizador/moderación');
              if (denunciaContainer) denunciaContainer.innerHTML = '';
              if (seccionDenuncias) seccionDenuncias.style.display = 'none';
            });
          }
          modal.hide();
        });
      }

      if (btnGestionarCarga) {
        btnGestionarCarga.addEventListener('click', () => {
          const listaResultados = (torneo.resultados || []).map(r => `<li>${escapeHtml(r.quien)} ${r.tu} - ${r.rivalScore} vs ${escapeHtml(r.rival)} (${new Date(r.fecha).toLocaleString()})</li>`).join('');
          alert(`Sección de carga / resultados:\n\n${listaResultados || 'Sin resultados pendientes'}`);
        });
      }
    }, 50);
  };

  window.irACalendarizar = function (id) {
    const torneo = torneos.find(t => t.id === id);
    if (!torneo) return alert('Torneo no encontrado');
    alert(`Calendario para: ${torneo.nombre} — Fecha: ${torneo.fecha || 'Sin fecha'}`);
  };

  function mostrarListasSafe() {
    try { mostrarListas(); } catch (err) { console.error(err); }
  }

  if (formBuscar) {
    formBuscar.addEventListener('submit', e => {
      e.preventDefault();
      mostrarListasSafe();
    });
  }
  if (inputBuscar) inputBuscar.addEventListener('input', mostrarListasSafe);
  if (filtroJuego) filtroJuego.addEventListener('change', mostrarListasSafe);

  mostrarListasSafe();

  window.addEventListener('storage', e => {
    if (e.key === 'torneos') {
      try { torneos = JSON.parse(e.newValue || '[]'); } catch (err) { torneos = []; }
      mostrarListasSafe();
    }
  });
});