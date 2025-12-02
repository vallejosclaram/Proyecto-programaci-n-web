document.addEventListener('DOMContentLoaded', () => {
  const solicitudesBody = document.getElementById('solicitudes-body');
  const modalDetallesBody = document.getElementById('modalDetallesBody');
  const btnAceptar = document.getElementById('btnAceptar');
  const btnRechazar = document.getElementById('btnRechazar');
  const filtroNombreTorneo = document.getElementById('filtroNombreTorneo');
  const filtroJuego = document.getElementById('filtroJuego');
  const filtroTipo = document.getElementById('filtroTipo');
  const filtroEstado = document.getElementById('filtroEstado');
  const btnFiltrar = document.getElementById('btnFiltrar');
  const btnLimpiar = document.getElementById('btnLimpiar');
  const modalDetallesEl = document.getElementById('modalDetalles');
  const modalConfirmarAceptarEl = document.getElementById('modalConfirmarAceptar');
  const modalConfirmarRechazarEl = document.getElementById('modalConfirmarRechazar');
  const modalMensajeEl = document.getElementById('modalMensaje');
  const modalMensajeBody = document.getElementById('modalMensajeBody');
  const btnConfirmarAceptar = document.getElementById('btnConfirmarAceptar');
  const btnConfirmarRechazar = document.getElementById('btnConfirmarRechazar');

  const modalDetalles = new bootstrap.Modal(modalDetallesEl);
  const modalConfirmarAceptar = new bootstrap.Modal(modalConfirmarAceptarEl);
  const modalConfirmarRechazar = new bootstrap.Modal(modalConfirmarRechazarEl);
  const modalMensaje = new bootstrap.Modal(modalMensajeEl);

  let idSolicitudActual = null;
  let filtrosActivos = {};

  function mostrarMensaje(mensaje, esError = false) {
    modalMensajeBody.innerHTML = `<p class="${esError ? 'text-danger' : 'text-success'}">${mensaje}</p>`;
    modalMensaje.show();
  }

  // Cargar solicitudes desde el backend
  async function cargarSolicitudes(filtros = {}) {
    filtrosActivos = { ...filtros };
    try {
      const res = await fetch('solicitudes-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(filtrosActivos)
      });
      const data = await res.json();

      if (!data.success) {
        solicitudesBody.innerHTML = `<tr><td colspan="9">${data.error}</td></tr>`;
        return;
      }

      solicitudesBody.innerHTML = '';
      data.solicitudes.forEach(s => {
        const estaProcesada = s.estado !== 'pendiente';
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${s.id_solicitud_creacion}</td>
          <td>${s.nombre || '-'}</td>
          <td>${s.juego || '-'}</td>
          <td>${s.tipo_torneo || '-'}</td>
          <td>${s.usuario || '-'}</td>
          <td>${s.fecha_inicio || '-'}</td>
          <td>${s.fecha_fin || '-'}</td>
          <td class="${estaProcesada ? (s.estado === 'aprobado' ? 'text-success' : 'text-danger') : 'text-warning'}">${s.estado}</td>
          <td>
            <button class="btn btn-sm btn-info btn-detalles"
                    data-id="${s.id_solicitud_creacion}"
                    data-desc="Torneo: ${s.nombre || '-'} | Juego: ${s.juego || '-'} | Tipo: ${s.tipo_torneo || '-'} | Usuario: ${s.usuario || '-'} | Estado: ${s.estado}"
                    data-estado="${s.estado}"
                    data-descripcion="${(s.descripcion || '').replace(/"/g, '&quot;')}">
              Ver detalles
            </button>
          </td>
        `;
        solicitudesBody.appendChild(tr);
      });

      // Asignar eventos a botones "Ver detalles"
      document.querySelectorAll('.btn-detalles').forEach(btn => {
        btn.addEventListener('click', () => {
          idSolicitudActual = btn.dataset.id;
          const descripcion = btn.dataset.descripcion || '-';
          const estado = btn.dataset.estado || 'pendiente';
          const estaProcesada = estado !== 'pendiente';

          modalDetallesBody.innerHTML = `
            <p>${btn.dataset.desc}</p>
            <hr>
            <p><strong>Descripción:</strong></p>
            <p>${descripcion}</p>
          `;

          if (estaProcesada) {
            modalDetallesBody.innerHTML += `<div class="alert alert-warning mt-3" role="alert">
              Esta solicitud ya fue ${estado}. No se puede modificar.
            </div>`;
          }

          modalDetalles.show();

          btnAceptar.disabled = estaProcesada;
          btnRechazar.disabled = estaProcesada;
          if (estaProcesada) {
            btnAceptar.classList.add('disabled');
            btnRechazar.classList.add('disabled');
          } else {
            btnAceptar.classList.remove('disabled');
            btnRechazar.classList.remove('disabled');
          }
        });
      });

    } catch (err) {
      solicitudesBody.innerHTML = `<tr><td colspan="9">Error de conexión</td></tr>`;
    }
  }

  // Acción de aceptar solicitud
  btnAceptar.addEventListener('click', () => {
    if (!idSolicitudActual || btnAceptar.disabled) return;
    modalConfirmarAceptar.show();
  });

  btnConfirmarAceptar.addEventListener('click', async () => {
    await procesarSolicitud('aceptar_solicitud_creacion');
    modalConfirmarAceptar.hide();
  });

  // Acción de rechazar solicitud
  btnRechazar.addEventListener('click', () => {
    if (!idSolicitudActual || btnRechazar.disabled) return;
    modalConfirmarRechazar.show();
  });

  btnConfirmarRechazar.addEventListener('click', async () => {
    await procesarSolicitud('rechazar_solicitud_creacion');
    modalConfirmarRechazar.hide();
  });

  async function procesarSolicitud(accion) {
    if (!idSolicitudActual) return;

    const payload = { accion, id_solicitud_creacion: idSolicitudActual };

    try {
      const res = await fetch('solicitudes-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json();

      modalDetalles.hide();
      mostrarMensaje(data.message || data.error, !data.success);

      if (data.success) {
        cargarSolicitudes(filtrosActivos);
      }
    } catch (err) {
      mostrarMensaje('Error de conexión con el servidor', true);
    }
  }

  // Filtros
  btnFiltrar?.addEventListener('click', () => {
    const filtros = {};
    if (filtroNombreTorneo?.value.trim()) filtros.nombre_torneo = filtroNombreTorneo.value.trim();
    if (filtroJuego?.value.trim()) filtros.nombre_juego = filtroJuego.value.trim();
    if (filtroTipo?.value) filtros.tipo_torneo = filtroTipo.value;
    if (filtroEstado?.value) filtros.estado = filtroEstado.value;

    cargarSolicitudes(filtros);
  });

  btnLimpiar?.addEventListener('click', () => {
    if (filtroNombreTorneo) filtroNombreTorneo.value = '';
    if (filtroJuego) filtroJuego.value = '';
    if (filtroTipo) filtroTipo.value = '';
    if (filtroEstado) filtroEstado.value = '';
    filtrosActivos = {};
    cargarSolicitudes();
  });

  // Inicializar
  cargarSolicitudes();

  // Reset botones al cerrar modal
  modalDetallesEl.addEventListener('hidden.bs.modal', () => {
    idSolicitudActual = null;
    btnAceptar.disabled = false;
    btnRechazar.disabled = false;
    btnAceptar.classList.remove('disabled');
    btnRechazar.classList.remove('disabled');
  });
});
