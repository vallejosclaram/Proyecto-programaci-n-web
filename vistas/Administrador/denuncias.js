document.addEventListener('DOMContentLoaded', () => {
  const usuariosBody = document.getElementById('denuncias-usuarios-body');
  const torneosBody = document.getElementById('denuncias-torneos-body');
  const modalDetallesBody = document.getElementById('modalDetallesBody');
  const btnBloquear = document.getElementById('btnBloquear');
  const modalConfirmarElm = document.getElementById('modalConfirmar');
  const btnConfirmarBloquear = document.getElementById('btnConfirmarBloquear');

  let accionActual = null;
  let idObjetivo = null;
  let entidadNombre = '';
  let rawUsuarios = [];
  let rawTorneos = [];
  const modalMensajeElm = document.getElementById('modalMensaje');
  let modalMensajeBody = document.getElementById('modalMensajeBody');
  const filterJuego = document.getElementById('filterJuego');
  const filterTorneo = document.getElementById('filterTorneo');
  const filterTipo = document.getElementById('filterTipo');
  const btnClearFilters = document.getElementById('btnClearFilters');
  const filterUsuario = document.getElementById('filterUsuario'); // Campo de búsqueda de usuario

  // 🎯 INICIALIZACIÓN ÚNICA DE INSTANCIAS DE MODAL
  const modalDetallesElm = document.getElementById('modalDetalles');
  const modalDetalles = modalDetallesElm ? new bootstrap.Modal(modalDetallesElm) : null;
  const modalConfirmarInst = modalConfirmarElm ? new bootstrap.Modal(modalConfirmarElm) : null;
  const modalMensajeInst = modalMensajeElm ? new bootstrap.Modal(modalMensajeElm) : null;

  async function cargarDenuncias() {
    try {
      const res = await fetch('denuncias-process.php');
      const data = await res.json();
      console.debug('Denuncias (raw):', data);

      if (!data.success) {
        usuariosBody.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
        torneosBody.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
        return;
      }

      rawUsuarios = data.usuario;
      rawTorneos = data.torneo;

      usuariosBody.innerHTML = '';
      renderUsuarios(data.usuario);

      torneosBody.innerHTML = '';
      renderTorneos(data.torneo);

      populateFilterOptions(data);

    } catch (err) {
      usuariosBody.innerHTML = `<tr><td colspan="5">Error de conexión</td></tr>`;
      torneosBody.innerHTML = `<tr><td colspan="5">Error de conexión</td></tr>`;
    }
  }

  async function sendBlockRequest() {
    if (!accionActual || !idObjetivo) return;
    const payload = { accion: accionActual };
    if (accionActual === 'bloquear_usuario') payload.id_reportado = parseInt(idObjetivo, 10);
    else payload.id_torneo = parseInt(idObjetivo, 10);

    try {
      btnConfirmarBloquear.disabled = true;
      console.debug('Sending payload', payload);
      const res = await fetch('denuncias-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      console.debug('block response', data);
      const msg = data.message || data.error || 'Acción procesada';
      const affected = data.affected !== undefined ? ' (affected: ' + data.affected + ')' : '';
      
      if (modalConfirmarInst) modalConfirmarInst.hide();
      if (modalDetalles) modalDetalles.hide();
      
      showMessageModal(msg + affected);
      
      cargarDenuncias();
      btnConfirmarBloquear.disabled = false;
    } catch (err) {
      console.error(err);
      showMessageModal('Error de conexión con el servidor');
      btnConfirmarBloquear.disabled = false;
    }
  }

  btnBloquear.addEventListener('click', () => {
    console.debug('btnBloquear clicked', {accionActual, idObjetivo, entidadNombre});
    const tipo = accionActual === 'bloquear_usuario' ? 'Usuario' : 'Torneo';
    const bodyElm = document.getElementById('modalConfirmarBody');
    if (bodyElm) bodyElm.innerHTML = `Se bloqueará el ${tipo} <strong>${entidadNombre}</strong> seleccionado por 15 días.`;
    
    if (modalConfirmarInst) {
      modalConfirmarInst.show();
    } else {
      console.warn('modalConfirmar not found or not initialized; blocking directly');
      sendBlockRequest();
    }
  });

  btnConfirmarBloquear.addEventListener('click', async () => {
    await sendBlockRequest();
  });

  if (modalDetallesElm) {
      modalDetallesElm.addEventListener('hidden.bs.modal', () => {
        btnBloquear.style.display = 'block';
        btnBloquear.disabled = false;
        btnBloquear.innerText = 'Bloquear';
        modalDetallesBody.innerHTML = '';
        accionActual = null;
        idObjetivo = null;
      });
  }


  cargarDenuncias();

  function showMessageModal(msg) {
    if (modalMensajeInst && modalMensajeBody) { 
      modalMensajeBody.innerHTML = msg;
      modalMensajeInst.show();
    } else {
      console.warn('showMessageModal fallback:', msg);
    }
  }

  function populateFilterOptions(data) {
    filterJuego.innerHTML = '<option value="">Todos los juegos</option>';
    filterTorneo.innerHTML = '<option value="">Todos los torneos</option>';
    filterTipo.innerHTML = '<option value="">Todos los tipos</option>';
    if (filterUsuario) filterUsuario.value = '';

    const juegos = {};
    const torneos = {};
    const tipos = {};

    (data.juegos || []).forEach(d => { if (d.id_juego) juegos[d.id_juego] = d.nombre; });
    (data.torneos || []).forEach(d => { if (d.id_torneo) torneos[d.id_torneo] = d.nombre; });
    (data.tipos_torneo || []).forEach(d => { if (d.id_tipo) tipos[d.id_tipo] = d.descripcion; });

    Object.keys(juegos).forEach(k => {
      const opt = document.createElement('option'); opt.value = k; opt.innerText = juegos[k]; filterJuego.appendChild(opt);
    });
    Object.keys(torneos).forEach(k => {
      const opt = document.createElement('option'); opt.value = k; opt.innerText = torneos[k]; filterTorneo.appendChild(opt);
    });
    Object.keys(tipos).forEach(k => {
      const opt = document.createElement('option'); opt.value = k; opt.innerText = tipos[k]; filterTipo.appendChild(opt);
    });

    [filterJuego, filterTorneo, filterTipo].forEach(el => el.addEventListener('change', applyFilters));
    if (filterUsuario) filterUsuario.addEventListener('input', applyFilters);
    
    btnClearFilters.addEventListener('click', () => {
      filterJuego.value = ''; 
      filterTorneo.value = ''; 
      filterTipo.value = ''; 
      if (filterUsuario) filterUsuario.value = '';
      applyFilters();
    });
  }

  function applyFilters() {
    // 1. FILTRADO DE TORNEOS (Juego, Torneo, Tipo)
    const j = filterJuego.value ? parseInt(filterJuego.value, 10) : '';
    const t = filterTorneo.value ? parseInt(filterTorneo.value, 10) : '';
    const tp = filterTipo.value ? parseInt(filterTipo.value, 10) : '';
    
    const fTorneos = rawTorneos.filter(d => {
      if (j && (d.id_juego == null || parseInt(d.id_juego, 10) !== j)) return false;
      if (t && (d.id_torneo == null || parseInt(d.id_torneo, 10) !== t)) return false;
      if (tp && (d.id_tipo == null || parseInt(d.id_tipo, 10) !== tp)) return false;
      return true;
    });
    renderTorneos(fTorneos);
    
    // 2. FILTRADO DE USUARIOS (Solo por nombre de usuario)
    const uQuery = filterUsuario ? filterUsuario.value.toLowerCase().trim() : '';

    const fUsuarios = rawUsuarios.filter(d => { 
      // Filtrar por nombre de usuario (la columna 'reportado' contiene el email del usuario)
      if (uQuery && (d.reportado || '').toLowerCase().indexOf(uQuery) === -1) {
        return false;
      }
      // Se mantiene el orden por fecha de creación establecido en PHP
      return true;
    });
    renderUsuarios(fUsuarios);
  }

  function renderUsuarios(items) {
    usuariosBody.innerHTML = '';
    items.forEach(d => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${d.id_denuncia}</td>
        <td>${d.reportador || '-'}</td>
        <td>${d.reportado || '-'}</td>
        <td>${d.fecha_creacion}</td>
        <td>${d.estado_usuario || ''}${d.fecha_fin_bloqueo_usuario ? ` (bloqueado hasta ${d.fecha_fin_bloqueo_usuario})` : ''}</td>
        <td>
          <button class="btn btn-sm btn-info btn-detalles"
                  data-tipo="usuario"
                  data-id="${d.id_reportado}"
                  data-desc="${d.descripcion}"
                  data-estado="${d.estado_usuario || ''}"
                  data-reportado="${d.reportado || ''}"
                  data-fecha-fin="${d.fecha_fin_bloqueo_usuario || ''}">
            Ver detalles
          </button>
        </td>
      `;
      usuariosBody.appendChild(tr);
    });
    attachDetailHandlers();
  }

  function renderTorneos(items) {
    torneosBody.innerHTML = '';
    items.forEach(d => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${d.id_denuncia}</td>
        <td>${d.reportador || '-'}</td>
        <td>${d.torneo || '-'}</td>
        <td>${d.fecha_creacion}</td>
        <td>${d.estado_torneo || ''}${d.fecha_fin_bloqueo_torneo ? ` (bloqueado hasta ${d.fecha_fin_bloqueo_torneo})` : ''}</td>
        <td>
          <button class="btn btn-sm btn-info btn-detalles"
                  data-tipo="torneo"
                  data-id="${d.id_torneo}"
                  data-desc="${d.descripcion}"
                  data-torneo="${d.torneo || ''}"
                  data-id-juego="${d.id_juego || ''}"
                  data-juego="${d.juego || ''}"
                  data-id-tipo="${d.id_tipo || ''}"
                  data-tipo-desc="${d.tipo_torneo || ''}"
                  data-estado="${d.estado_torneo || ''}"
                  data-fecha-fin="${d.fecha_fin_bloqueo_torneo || ''}">
            Ver detalles
          </button>
        </td>
      `;
      torneosBody.appendChild(tr);
    });
    attachDetailHandlers();
  }

  function attachDetailHandlers() {
    document.querySelectorAll('.btn-detalles').forEach(btn => {
      btn.onclick = () => {
        console.debug('detalles click', btn.dataset);
        accionActual = btn.dataset.tipo === 'usuario' ? 'bloquear_usuario' : 'bloquear_torneo';
        idObjetivo = btn.dataset.id;
        const fechaFin = btn.dataset.fechaFin || '';
        const torneoNombre = btn.dataset.torneo || '';
        const juegoNombre = btn.dataset.juego || '';
        const tipoDesc = btn.dataset.tipoDesc || '';
        const reportado = btn.dataset.reportado || '';
        entidadNombre = btn.dataset.tipo === 'usuario' ? reportado || `Usuario ${idObjetivo}` : (btn.dataset.torneo || `Torneo ${idObjetivo}`);

        modalDetallesBody.innerHTML = `
          <p>${btn.dataset.desc}</p>
          <p><strong>Estado actual:</strong> ${btn.dataset.estado || 'Desconocido'}</p>
          ${reportado ? `<p><strong>Usuario reportado:</strong> ${reportado}</p>` : ''}
          ${torneoNombre ? `<p><strong>Torneo:</strong> ${torneoNombre}</p>` : ''}
          ${juegoNombre ? `<p><strong>Juego:</strong> ${juegoNombre}</p>` : ''}
          ${tipoDesc ? `<p><strong>Tipo:</strong> ${tipoDesc}</p>` : ''}
          ${fechaFin ? `<p><strong>Bloqueado hasta:</strong> ${fechaFin}</p>` : ''}
        `;

        if (fechaFin && new Date(fechaFin.replace(' ', 'T')) > new Date()) {
          btnBloquear.disabled = true;
          btnBloquear.innerText = 'Bloqueado';
        } else {
          btnBloquear.disabled = false;
          btnBloquear.innerText = 'Bloquear';
        }

        if (modalDetalles) {
            modalDetalles.show();
        } else {
            console.error('Modal de detalles no inicializado.');
        }
      };
    });
  }
  
});