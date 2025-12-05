document.addEventListener('DOMContentLoaded', () => {
  const solicitudesBody = document.getElementById('solicitudes-body');
  const modalDetallesBody = document.getElementById('modalDetallesBody');
  const btnAceptar = document.getElementById('btnAceptar');
  const btnRechazar = document.getElementById('btnRechazar');

  const modalDetallesEl = document.getElementById('modalDetalles');
  const modalMensajeEl = document.getElementById('modalMensaje');
  const modalConfirmarAceptarEl = document.getElementById('modalConfirmarAceptar');
  const modalConfirmarRechazarEl = document.getElementById('modalConfirmarRechazar');

  const modalDetalles = new bootstrap.Modal(modalDetallesEl);
  const modalMensaje = new bootstrap.Modal(modalMensajeEl);
  const modalConfirmarAceptar = modalConfirmarAceptarEl ? new bootstrap.Modal(modalConfirmarAceptarEl) : null;
  const modalConfirmarRechazar = modalConfirmarRechazarEl ? new bootstrap.Modal(modalConfirmarRechazarEl) : null;

  const modalMensajeBody = document.getElementById('modalMensajeBody');

  let idSolicitudActual = null;

  function mostrarMensaje(msg) {
    modalMensajeBody.textContent = msg;
    modalMensaje.show();
  }

  async function cargarSolicitudes() {
    const juego = document.getElementById('filtroJuego').value;
    const tipo = document.getElementById('filtroTipo').value;

    try {
      const res = await fetch(
        'solicitudes-process.php?juego=' + encodeURIComponent(juego) +
        '&tipo=' + encodeURIComponent(tipo)
      );
      const text = await res.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        mostrarMensaje("Respuesta inválida del servidor:\n" + text);
        return;
      }

      if (!data.success) {
        solicitudesBody.innerHTML = `<tr><td colspan="9">${data.error}</td></tr>`;
        return;
      }

      solicitudesBody.innerHTML = '';
      data.solicitudes.forEach(s => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${s.id_solicitud_creacion}</td>
          <td>${s.nombre || '-'}</td>
          <td>${s.juego || '-'}</td>
          <td>${s.tipo_torneo || '-'}</td>
          <td>${s.usuario || '-'}</td>
          <td>${s.fecha_inicio || '-'}</td>
          <td>${s.fecha_fin || '-'}</td>
          <td>${s.estado}</td>
          <td>
            <button class="btn btn-sm btn-info btn-detalles"
                    data-id="${s.id_solicitud_creacion}"
                    data-desc="Torneo: ${s.nombre || '-'} | Juego: ${s.juego || '-'} | Tipo: ${s.tipo_torneo || '-'} | Usuario: ${s.usuario || '-'} | Estado: ${s.estado}">
              Ver detalles
            </button>
          </td>
        `;
        solicitudesBody.appendChild(tr);
      });

      document.querySelectorAll('.btn-detalles').forEach(btn => {
        btn.addEventListener('click', () => {
          idSolicitudActual = btn.dataset.id;
          modalDetallesBody.textContent = btn.dataset.desc;
          modalDetalles.show();
        });
      });
    } catch (err) {
      solicitudesBody.innerHTML = `<tr><td colspan="9">Error de conexión</td></tr>`;
    }
  }

  document.getElementById('btnFiltrar').addEventListener('click', () => {
    cargarSolicitudes();
  });

  document.getElementById('btnLimpiar').addEventListener('click', () => {
    document.getElementById('filtroJuego').value = '';
    document.getElementById('filtroTipo').value = '';
    cargarSolicitudes();
  });

  btnAceptar.addEventListener('click', () => {
    if (!idSolicitudActual) return;
    if (modalConfirmarAceptar) {
      modalConfirmarAceptar.show();
    } else {
      confirmarAceptar();
    }
  });

  btnRechazar.addEventListener('click', () => {
    if (!idSolicitudActual) return;
    if (modalConfirmarRechazar) {
      modalConfirmarRechazar.show();
    } else {
      confirmarRechazar();
    }
  });

  const btnConfirmarAceptar = document.getElementById('btnConfirmarAceptar');
  const btnConfirmarRechazar = document.getElementById('btnConfirmarRechazar');

  if (btnConfirmarAceptar) {
    btnConfirmarAceptar.addEventListener('click', confirmarAceptar);
  }
  if (btnConfirmarRechazar) {
    btnConfirmarRechazar.addEventListener('click', confirmarRechazar);
  }

  async function confirmarAceptar() {
    if (!idSolicitudActual) return;

    const payload = { accion: 'aceptar_solicitud_creacion', id_solicitud_creacion: idSolicitudActual };

    try {
      const res = await fetch('solicitudes-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const text = await res.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        mostrarMensaje("Respuesta inválida del servidor:\n" + text);
        return;
      }

      try { modalDetalles.hide(); } catch {}
      try { modalConfirmarAceptar && modalConfirmarAceptar.hide(); } catch {}

      mostrarMensaje(data.message || data.error);
      cargarSolicitudes();
    } catch (err) {
      mostrarMensaje('Error de conexión con el servidor');
    }
  }

  async function confirmarRechazar() {
    if (!idSolicitudActual) return;

    const payload = { accion: 'rechazar_solicitud_creacion', id_solicitud_creacion: idSolicitudActual };

    try {
      const res = await fetch('solicitudes-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const text = await res.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        mostrarMensaje("Respuesta inválida del servidor:\n" + text);
        return;
      }

      try { modalDetalles.hide(); } catch {}
      try { modalConfirmarRechazar && modalConfirmarRechazar.hide(); } catch {}

      mostrarMensaje(data.message || data.error);
      cargarSolicitudes();
    } catch (err) {
      mostrarMensaje('Error de conexión con el servidor');
    }
  }

  cargarSolicitudes();
});
