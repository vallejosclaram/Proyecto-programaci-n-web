document.addEventListener('DOMContentLoaded', () => {
  const ticketsContainer = document.getElementById('tickets-container');
  const filtroSearch = document.getElementById('filtroSearch');
  const filtroEstado = document.getElementById('filtroEstadoTicket');
  const filtroFechaDesde = document.getElementById('filtroFechaDesde');
  const filtroFechaHasta = document.getElementById('filtroFechaHasta');
  const btnFiltrar = document.getElementById('btnFiltrarTickets');
  const btnLimpiar = document.getElementById('btnLimpiarTickets');
  const modalRespuestaEl = document.getElementById('modalRespuesta');
  const modalConfirmarEnvioEl = document.getElementById('modalConfirmarEnvio');
  const modalMensajeEl = document.getElementById('modalMensaje');
  const respuestaTexto = document.getElementById('respuestaTexto');
  const respuestaTicketInfo = document.getElementById('respuestaTicketInfo');
  const btnPrepararEnvio = document.getElementById('btnPrepararEnvio');
  const btnConfirmarRespuesta = document.getElementById('btnConfirmarRespuesta');
  const modalMensajeBody = document.getElementById('modalMensajeBody');

  const modalRespuesta = new bootstrap.Modal(modalRespuestaEl);
  const modalConfirmarEnvio = new bootstrap.Modal(modalConfirmarEnvioEl);
  const modalMensaje = new bootstrap.Modal(modalMensajeEl);

  let ticketSeleccionado = null;
  let filtrosActivos = {};

  function mostrarMensaje(msg, esError = false) {
    modalMensajeBody.innerHTML = `<p class="${esError ? 'text-danger' : 'text-success'}">${msg}</p>`;
    modalMensaje.show();
  }

  async function cargarTickets(filtros = {}) {
    filtrosActivos = { ...filtros };
    ticketsContainer.innerHTML = `<div class="text-center text-muted py-5">Cargando tickets...</div>`;
    try {
      const res = await fetch('soporte-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(filtrosActivos)
      });
      const data = await res.json();

      if (!data.success) {
        ticketsContainer.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
        return;
      }

      renderTickets(data.tickets || []);
    } catch (error) {
      ticketsContainer.innerHTML = `<div class="alert alert-danger">Error de conexión</div>`;
    }
  }

  function renderTickets(tickets) {
    if (!tickets.length) {
      ticketsContainer.innerHTML = `<div class="text-center text-muted py-5"></div>`;
      return;
    }

    ticketsContainer.innerHTML = '';
    tickets.forEach(ticket => {
      const card = document.createElement('article');
      card.className = 'card bg-dark text-white shadow-sm mb-4';
      const badgeClass = ticket.respondido ? 'bg-success' : 'bg-warning text-dark';

      card.innerHTML = `
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <h5 class="card-title mb-1">${ticket.asunto}</h5>
              <small class="text-muted">#${ticket.id_ticket} · ${ticket.usuario} · ${ticket.fecha_creacion}</small>
            </div>
            <span class="badge ${badgeClass}">${ticket.respondido ? 'Respondido' : 'Pendiente'}</span>
          </div>
          <p class="card-text">${ticket.descripcion || 'Sin descripción'}</p>
          <div class="mt-4">
            <h6>Respuestas</h6>
            ${renderRespuestas(ticket.respuestas)}
          </div>
          <div class="mt-3 text-end">
            <button class="btn btn-primary btn-sm btn-responder" data-id="${ticket.id_ticket}" data-asunto="${ticket.asunto}">
              Responder
            </button>
          </div>
        </div>
      `;

      ticketsContainer.appendChild(card);
    });

    document.querySelectorAll('.btn-responder').forEach(btn => {
      btn.addEventListener('click', () => {
        ticketSeleccionado = btn.dataset.id;
        respuestaTexto.value = '';
        respuestaTicketInfo.textContent = `Ticket #${ticketSeleccionado} · ${btn.dataset.asunto}`;
        modalRespuesta.show();
      });
    });
  }

  function renderRespuestas(respuestas = []) {
    if (!respuestas.length) {
      return `<div class="text-muted small">Sin respuestas aún.</div>`;
    }
    return `
      <div class="list-group list-group-flush">
        ${respuestas.map(r => `
          <div class="list-group-item bg-transparent text-white">
            <div class="d-flex justify-content-between">
              <strong>${r.admin}</strong>
              <small class="text-muted">${r.fecha_respuesta}</small>
            </div>
            <p class="mb-0">${r.respuesta}</p>
          </div>
        `).join('')}
      </div>
    `;
  }

  btnPrepararEnvio.addEventListener('click', () => {
    const texto = respuestaTexto.value.trim();
    if (!ticketSeleccionado || !texto) {
      mostrarMensaje('La respuesta no puede estar vacía.', true);
      return;
    }
    modalConfirmarEnvio.show();
  });

  btnConfirmarRespuesta.addEventListener('click', async () => {
    const texto = respuestaTexto.value.trim();
    if (!ticketSeleccionado || !texto) {
      mostrarMensaje('La respuesta no puede estar vacía.', true);
      modalConfirmarEnvio.hide();
      return;
    }

    try {
      const res = await fetch('soporte-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          accion: 'responder_ticket',
          id_ticket: ticketSeleccionado,
          respuesta: texto
        })
      });
      const data = await res.json();
      modalConfirmarEnvio.hide();
      modalRespuesta.hide();
      mostrarMensaje(data.message || data.error, !data.success);
      if (data.success) {
        cargarTickets(filtrosActivos);
      }
    } catch (error) {
      modalConfirmarEnvio.hide();
      mostrarMensaje('Error de conexión con el servidor', true);
    }
  });

  btnFiltrar.addEventListener('click', () => {
    const filtros = {};
    if (filtroSearch?.value.trim()) filtros.search = filtroSearch.value.trim();
    if (filtroEstado?.value) filtros.estado = filtroEstado.value;
    if (filtroFechaDesde?.value) filtros.fecha_desde = filtroFechaDesde.value;
    if (filtroFechaHasta?.value) filtros.fecha_hasta = filtroFechaHasta.value;
    cargarTickets(filtros);
  });

  btnLimpiar.addEventListener('click', () => {
    if (filtroSearch) filtroSearch.value = '';
    if (filtroEstado) filtroEstado.value = '';
    if (filtroFechaDesde) filtroFechaDesde.value = '';
    if (filtroFechaHasta) filtroFechaHasta.value = '';
    filtrosActivos = {};
    cargarTickets();
  });

  cargarTickets();
});
