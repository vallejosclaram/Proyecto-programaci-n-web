document.addEventListener('DOMContentLoaded', () => {
  // ===== UI: sidebar =====
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

  // ===== Estado inicial =====
  let torneos = JSON.parse(localStorage.getItem('torneos')) || [];
  const jugador = JSON.parse(localStorage.getItem('jugador')) || { usuario: 'invitado' };

  // ===== Elementos DOM =====
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

  // ===== Utilidades =====
  function saveTorneos() { localStorage.setItem('torneos', JSON.stringify(torneos)); }

  function escapeHtml(text) {
    return String(text || '').replace(/[&<>"'`=\/]/g, s => ( {
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
      "'": '&#39;', '/': '&#x2F;', '`': '&#x60;', '=': '&#x3D;'
    })[s]);
  }

  function estaInscripto(torneo) {
    if (!torneo) return false;
    if (torneo.tipo === 'individual') {
      return Array.isArray(torneo.inscriptos) && torneo.inscriptos.includes(jugador.usuario);
    } else {
      return Array.isArray(torneo.inscriptosEquipos) &&
        torneo.inscriptosEquipos.some(eq => Array.isArray(eq.miembros) && eq.miembros.includes(jugador.usuario));
    }
  }

  // ===== Crear card de torneo (usada en listas) =====
  function crearCardTorneo(torneo, esPropio) {
    const card = document.createElement('div');
    card.className = 'card mb-3 p-3 torneo-card';
    const fechaLabel = torneo.fecha ? (new Date(torneo.fecha)).toLocaleDateString() : 'Sin fecha';
    card.innerHTML = `
      <div class="d-flex justify-content-between align-items-start gap-3">
        <div class="d-flex align-items-start gap-3" style="flex:1">
          <div class="torneo-date-badge">${escapeHtml(fechaLabel)}</div>
          <div style="flex:1">
            <h5 class="mb-1">${escapeHtml(torneo.nombre)}</h5>
            <p class="mb-1 small text-muted">${escapeHtml(torneo.juego || '')} • ${escapeHtml(torneo.tipo || '')}</p>
            <p class="mb-0 small text-secondary lugar-line"><strong>Sede:</strong> ${escapeHtml(torneo.lugar || torneo.sede || 'No especificado')}</p>
          </div>
        </div>
        <div class="text-end d-flex flex-column align-items-end gap-2">
          <div>
            <button class="btn btn-sm btn-secondary me-1" onclick="verTorneo('${torneo.id}')">Ver</button>
            <button class="btn btn-sm btn-outline-secondary" id="btnCalendario_${torneo.id}">Calendario</button>
          </div>
        </div>
      </div>
    `;

    // Handler para el botón "Calendario" (comportamiento según tenga fecha)
    setTimeout(() => {
      const btnCal = document.getElementById(`btnCalendario_${torneo.id}`);
      if (!btnCal) return;
      if (!torneo.fecha) {
        btnCal.classList.add('disabled');
        btnCal.title = 'Este torneo no tiene fecha asignada';
        btnCal.addEventListener('click', (e) => {
          e.preventDefault();
          alert('Este torneo no tiene fecha definida.');
        });
      } else {
        btnCal.addEventListener('click', () => {
          // si existe scheduleBoard, usar irACalendarizar (centra y resalta)
          irACalendarizar(torneo.id);
        });
      }
    }, 50);

    return card;
  }

  // ===== Mostrar listas (disponibles / mis torneos / calendario pequeño) =====
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
      const matchesText = texto === '' ||
        (t.nombre && t.nombre.toLowerCase().includes(texto)) ||
        (t.juego && t.juego.toLowerCase().includes(texto));

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

    // actualizar vista de scheduleBoard si está presente
    try { renderScheduleBoard(); } catch (err) { /* no bloquear la UI */ }
  }

  // Exponer verTorneo globalmente para botones inline
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
    const modalEl = document.getElementById('modalTorneo');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // handlers (usamos setTimeout/nextTick para que existan los elementos)
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

  // ===== irACalendarizar: busca fila en scheduleBoard y la resalta; si no existe, abre modal con fecha =====
  window.irACalendarizar = function (id) {
    const torneo = torneos.find(t => t.id === id);
    if (!torneo) return alert('Torneo no encontrado.');

    const board = document.getElementById('scheduleBoard');
    if (board) {
      const fila = board.querySelector(`.schedule-row[data-torneo-id="${id}"]`);
      if (fila) {
        fila.scrollIntoView({ behavior: 'smooth', block: 'center' });
        fila.classList.add('highlight');
        setTimeout(() => fila.classList.remove('highlight'), 2200);
        return;
      }
    }

    // fallback: mostrar modal con fecha y opciones si no existe scheduleBoard/fila
    const fecha = torneo.fecha ? (new Date(torneo.fecha)).toLocaleString() : 'Sin fecha definida';
    const modalHtml = `
      <div class="card p-3">
        <h5>${escapeHtml(torneo.nombre)}</h5>
        <p><strong>Fecha:</strong> ${escapeHtml(fecha)}</p>
        <p><strong>Sede:</strong> ${escapeHtml(torneo.lugar || torneo.sede || 'No especificado')}</p>
        <div class="mt-3 d-flex gap-2">
          <button id="abrirEnCalendario" class="btn btn-violeta">Abrir en calendario</button>
          <button id="copiarFecha" class="btn btn-secondary">Copiar fecha</button>
        </div>
      </div>
    `;
    if (modalTitulo && modalContenido) {
      modalTitulo.textContent = 'Calendario - ' + (torneo.nombre || '');
      modalContenido.innerHTML = modalHtml;
      const modalEl = document.getElementById('modalTorneo');
      const modal = new bootstrap.Modal(modalEl);
      modal.show();

      setTimeout(() => {
        document.getElementById('abrirEnCalendario')?.addEventListener('click', () => {
          if (!torneo.fecha) return alert('Fecha inválida para crear evento de calendario.');
          const start = new Date(torneo.fecha).toISOString().replace(/-|:|\.\d+/g,'');
          const url = `https://calendar.google.com/calendar/r/eventedit?text=${encodeURIComponent(torneo.nombre)}&dates=${start}/${start}`;
          window.open(url, '_blank');
        });
        document.getElementById('copiarFecha')?.addEventListener('click', () => {
          navigator.clipboard?.writeText(fecha).then(() => alert('Fecha copiada al portapapeles'));
        });
      }, 50);
    } else {
      alert(`Fecha: ${fecha}`);
    }
  };

  // ===== renderScheduleBoard: pinta la "tabla" de programación en #scheduleBoard =====
  function renderScheduleBoard() {
    const copy = [...torneos].sort((a, b) => {
      if (!a.fecha) return 1;
      if (!b.fecha) return -1;
      return new Date(a.fecha) - new Date(b.fecha);
    });

    const board = document.getElementById('scheduleBoard');
    if (!board) return;

    board.innerHTML = `
      <div class="schedule-header">
        <div style="text-align:center">Fecha</div>
        <div style="text-align:left">Evento</div>
        <div style="text-align:left">Lugar / Info</div>
        <div style="text-align:center">Acciones</div>
      </div>
    `;

    if (copy.length === 0) {
      board.innerHTML += `<div class="p-3 text-muted">No hay eventos programados.</div>`;
      return;
    }

    copy.forEach(t => {
      const fechaLabel = t.fecha ? (new Date(t.fecha)).toLocaleDateString() : 'Sin fecha';
      const lugar = t.lugar || t.sede || 'No especificado';
      const juego = t.juego || 'Desconocido';
      const tipo = t.tipo || '';

      const row = document.createElement('div');
      row.className = 'schedule-row';
      row.setAttribute('data-torneo-id', t.id || '');

      row.innerHTML = `
        <div class="schedule-date">${escapeHtml(fechaLabel)}</div>
        <div class="schedule-info">
          <h5>${escapeHtml(t.nombre)}</h5>
          <p><span class="badge-game">🎮 ${escapeHtml(juego)} ${tipo ? '• ' + escapeHtml(tipo) : ''}</span></p>
        </div>
        <div class="schedule-place">
          <div><strong>Sede:</strong> ${escapeHtml(lugar)}</div>
          <div><small>Organizador: ${escapeHtml(t.organizador || '—')}</small></div>
        </div>
        <div class="schedule-actions">
          <button class="btn btn-sm btn-violeta" onclick="verTorneo('${t.id}')">Ver</button>
        </div>
      `;
      board.appendChild(row);
    });
  }

  // ===== Safe wrapper para mostrarListas (no rompe si hay error) =====
  function mostrarListasSafe() {
    try { mostrarListas(); } catch (err) { console.error(err); }
  }

  // ===== Listeners de búsqueda/filtro y storage =====
  if (formBuscar) {
    formBuscar.addEventListener('submit', e => {
      e.preventDefault();
      mostrarListasSafe();
    });
  }
  if (inputBuscar) inputBuscar.addEventListener('input', mostrarListasSafe);
  if (filtroJuego) filtroJuego.addEventListener('change', mostrarListasSafe);

  // render inicial
  mostrarListasSafe();

  window.addEventListener('storage', e => {
    if (e.key === 'torneos') {
      try { torneos = JSON.parse(e.newValue || '[]'); } catch (err) { torneos = []; }
      mostrarListasSafe();
    }
  });
});
