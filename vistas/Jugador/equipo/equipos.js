document.addEventListener('DOMContentLoaded', () => {
  const jugador = JSON.parse(localStorage.getItem('jugador')) || { usuario: 'invitado' };
  let equipos = JSON.parse(localStorage.getItem('equipos')) || [];

  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;
  const formBuscar = document.getElementById('formBuscarEquipos');
  const inputBuscar = document.getElementById('buscadorEquipos');
  const selectJuego = document.getElementById('selectJuego');
  const contMisEquipos = document.getElementById('misEquipos');
  const contDisponibles = document.getElementById('equiposDisponibles');
  const solicitudesSection = document.getElementById('solicitudesSection');
  const listaSolicitudes = document.getElementById('listaSolicitudes');

  if (menuToggle && sidebar && closeBtn) {
    menuToggle.addEventListener('click', () => {
      sidebar.classList.add('open');
      body.classList.add('menu-open');
    });
    closeBtn.addEventListener('click', () => {
      sidebar.classList.remove('open');
      body.classList.remove('menu-open');
    });
  }

  function guardarEquipos() {
    localStorage.setItem('equipos', JSON.stringify(equipos));
  }

  function recargarDatos() {
    equipos = JSON.parse(localStorage.getItem('equipos')) || [];
    mostrarSolicitudes();
    mostrarEquipos();
  }

  function mostrarEquipos() {
    const texto = (inputBuscar ? inputBuscar.value : '').toLowerCase();
    const juegoSeleccionado = (selectJuego ? selectJuego.value : '').toLowerCase();

    contMisEquipos.innerHTML = '';
    contDisponibles.innerHTML = '';

    const mis = equipos.filter(eq => Array.isArray(eq.miembros) && eq.miembros.includes(jugador.usuario));
    mis.forEach(eq => {
      const card = crearCardEquipo(eq, { mostrarSolicitar: false, mostrarEstado: true });
      contMisEquipos.appendChild(card);
    });

    const disponibles = equipos.filter(eq => !eq.miembros.includes(jugador.usuario));
    disponibles.forEach(eq => {
      const coincideTexto = texto === '' || eq.nombre.toLowerCase().includes(texto) || eq.juego.toLowerCase().includes(texto);
      const coincideJuego = juegoSeleccionado === '' || eq.juego.toLowerCase() === juegoSeleccionado;
      if (coincideTexto && coincideJuego) {
        const card = crearCardEquipo(eq, { mostrarSolicitar: true, mostrarEstado: true });
        contDisponibles.appendChild(card);
      }
    });

    if (contDisponibles.children.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'text-muted';
      empty.innerText = 'No hay equipos disponibles que coincidan con la búsqueda.';
      contDisponibles.appendChild(empty);
    }

    if (contMisEquipos.children.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'text-muted';
      empty.innerText = 'No estás en ningún equipo aún.';
      contMisEquipos.appendChild(empty);
    }
  }

  function crearCardEquipo(eq, opts = {}) {
    const { mostrarSolicitar = true, mostrarEstado = true } = opts;
    const card = document.createElement('div');
    card.className = 'card mb-3 p-3';

    const descripcion = eq.descripcion || 'Sin descripción';
    const miembrosCount = Array.isArray(eq.miembros) ? eq.miembros.length : 0;
    const capacidad = eq.cantidad || 0;

    card.innerHTML = `
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h5 class="mb-1">${escapeHtml(eq.nombre)}</h5>
          <p class="mb-1"><strong>Juego:</strong> ${escapeHtml(eq.juego)}</p>
          <p class="mb-1 text-truncate" style="max-width:480px;"><strong>Descripción:</strong> ${escapeHtml(descripcion)}</p>
          <p class="mb-0"><strong>Jugadores:</strong> ${miembrosCount} / ${capacidad}</p>
        </div>
        <div class="text-end">
          <button class="btn btn-sm btn-secondary mb-2" onclick="verDetalle('${eq.id}')">Ver detalle</button>
          ${mostrarSolicitar && !eq.miembros.includes(jugador.usuario) ? `<br><button class="btn btn-sm btn-outline-primary" onclick="enviarSolicitud('${eq.id}')">Solicitar inscripción</button>` : ''}
          ${eq.miembros.includes(jugador.usuario) ? `<div class="mt-2 badge bg-success">Sos miembro</div>` : ''}
        </div>
      </div>
    `;
    return card;
  }

  function escapeHtml(text) {
    return String(text).replace(/[&<>"'`=\/]/g, function (s) {
      return ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;',
        '`': '&#x60;',
        '=': '&#x3D;'
      })[s];
    });
  }

  function mostrarSolicitudes() {
    listaSolicitudes.innerHTML = '';
    const misCapitanes = equipos.filter(eq => eq.capitan === jugador.usuario);
    if (misCapitanes.length === 0) {
      solicitudesSection.style.display = 'none';
      return;
    }
    solicitudesSection.style.display = 'block';
    misCapitanes.forEach(eq => {
      if (!Array.isArray(eq.solicitudes) || eq.solicitudes.length === 0) return;
      eq.solicitudes.forEach(solicitante => {
        const item = document.createElement('div');
        item.className = 'card mb-2 p-3';
        item.innerHTML = `
          <p class="mb-2"><strong>${escapeHtml(solicitante)}</strong> quiere unirse a <strong>${escapeHtml(eq.nombre)}</strong></p>
          <div>
            <button class="btn btn-success btn-sm me-2" onclick="aceptar('${eq.id}','${solicitante}')">Aceptar</button>
            <button class="btn btn-danger btn-sm" onclick="rechazar('${eq.id}','${solicitante}')">Rechazar</button>
          </div>
        `;
        listaSolicitudes.appendChild(item);
      });
    });
  }

  window.verDetalle = function (idEquipo) {
    const equipo = equipos.find(e => e.id === idEquipo);
    if (!equipo) return alert('Equipo no encontrado');

    const modalTitulo = document.getElementById('modalEquipoTitulo');
    const modalContenido = document.getElementById('modalEquipoContenido');

    modalTitulo.textContent = equipo.nombre;
    const miembrosList = Array.isArray(equipo.miembros) ? equipo.miembros : [];
    const solicitudesList = Array.isArray(equipo.solicitudes) ? equipo.solicitudes : [];

    modalContenido.innerHTML = `
      <p><strong>Juego:</strong> ${escapeHtml(equipo.juego)}</p>
      <p><strong>Descripción:</strong> ${escapeHtml(equipo.descripcion || 'Sin descripción')}</p>
      <p><strong>Capitán:</strong> ${escapeHtml(equipo.capitan)}</p>
      <p><strong>Jugadores:</strong> ${miembrosList.length} / ${escapeHtml(String(equipo.cantidad || 0))}</p>
      <p><strong>Miembros:</strong> ${miembrosList.length ? escapeHtml(miembrosList.join(', ')) : 'Sin miembros'}</p>
      <div id="modalEquipoAcciones" class="mt-3"></div>
    `;

    const acciones = document.getElementById('modalEquipoAcciones');
    acciones.innerHTML = '';

    if (miembrosList.includes(jugador.usuario) && equipo.capitan !== jugador.usuario) {
      const abandonarBtn = document.createElement('button');
      abandonarBtn.className = 'btn btn-warning';
      abandonarBtn.textContent = 'Abandonar equipo';
      abandonarBtn.onclick = () => {
        if (!confirm('¿Querés abandonar este equipo?')) return;
        equipo.miembros = equipo.miembros.filter(m => m !== jugador.usuario);
        guardarEquipos();
        recargarDatos();
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalEquipo'));
        modal.hide();
      };
      acciones.appendChild(abandonarBtn);
    }

    if (!miembrosList.includes(jugador.usuario)) {
      const yaSolicitado = solicitudesList.includes(jugador.usuario);
      const solicitarBtn = document.createElement('button');
      solicitarBtn.className = 'btn btn-primary';
      solicitarBtn.textContent = yaSolicitado ? 'Solicitud enviada' : 'Solicitar inscripción';
      solicitarBtn.disabled = yaSolicitado;
      solicitarBtn.onclick = () => {
        if (yaSolicitado) return;
        window.enviarSolicitud(idEquipo);
        solicitarBtn.textContent = 'Solicitud enviada';
        solicitarBtn.disabled = true;
      };
      acciones.appendChild(solicitarBtn);
    }

    if (equipo.capitan === jugador.usuario) {
      const hr = document.createElement('hr');
      acciones.appendChild(hr);
      const tituloSolicitudes = document.createElement('div');
      tituloSolicitudes.className = 'mb-2';
      tituloSolicitudes.innerHTML = `<strong>Solicitudes (${solicitudesList.length})</strong>`;
      acciones.appendChild(tituloSolicitudes);

      if (solicitudesList.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'text-muted';
        empty.innerText = 'No hay solicitudes';
        acciones.appendChild(empty);
      } else {
        solicitudesList.forEach(solic => {
          const fila = document.createElement('div');
          fila.className = 'd-flex align-items-center mb-2';
          fila.innerHTML = `<div class="me-2">${escapeHtml(solic)}</div>`;
          const aceptarBtn = document.createElement('button');
          aceptarBtn.className = 'btn btn-success btn-sm me-2';
          aceptarBtn.textContent = 'Aceptar';
          aceptarBtn.onclick = () => { window.aceptar(idEquipo, solic); };
          const rechazarBtn = document.createElement('button');
          rechazarBtn.className = 'btn btn-danger btn-sm';
          rechazarBtn.textContent = 'Rechazar';
          rechazarBtn.onclick = () => { window.rechazar(idEquipo, solic); };
          fila.appendChild(aceptarBtn);
          fila.appendChild(rechazarBtn);
          acciones.appendChild(fila);
        });
      }
    }

    const modal = new bootstrap.Modal(document.getElementById('modalEquipo'));
    modal.show();
  };

  window.enviarSolicitud = function (idEquipo) {
    const equipo = equipos.find(e => e.id === idEquipo);
    if (!equipo) return alert('Equipo no encontrado');
    const usuario = jugador.usuario;
    if (equipo.miembros.includes(usuario)) return alert('Ya sos miembro de este equipo');
    equipo.solicitudes = equipo.solicitudes || [];
    if (equipo.solicitudes.includes(usuario)) return alert('Ya enviaste una solicitud a este equipo');
    equipo.solicitudes.push(usuario);
    guardarEquipos();
    recargarDatos();
    alert('Solicitud enviada al capitán');
  };

  window.aceptar = function (idEquipo, usuario) {
    const equipo = equipos.find(e => e.id === idEquipo);
    if (!equipo) return alert('Equipo no encontrado');
    equipo.solicitudes = (equipo.solicitudes || []).filter(s => s !== usuario);
    equipo.miembros = equipo.miembros || [];
    if (!equipo.miembros.includes(usuario)) equipo.miembros.push(usuario);
    guardarEquipos();
    recargarDatos();
    alert(`${usuario} fue aceptado`);
  };

  window.rechazar = function (idEquipo, usuario) {
    const equipo = equipos.find(e => e.id === idEquipo);
    if (!equipo) return alert('Equipo no encontrado');
    equipo.solicitudes = (equipo.solicitudes || []).filter(s => s !== usuario);
    guardarEquipos();
    recargarDatos();
    alert(`${usuario} fue rechazado`);
  };

  if (formBuscar) {
    formBuscar.addEventListener('submit', function (e) {
      e.preventDefault();
      mostrarEquipos();
    });
  }
  if (inputBuscar) inputBuscar.addEventListener('input', mostrarEquipos);
  if (selectJuego) selectJuego.addEventListener('change', mostrarEquipos);

  mostrarSolicitudes();
  mostrarEquipos();

  window.addEventListener('storage', (e) => {
    if (e.key === 'equipos') recargarDatos();
  });
});
