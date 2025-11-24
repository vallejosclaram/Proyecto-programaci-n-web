document.addEventListener('DOMContentLoaded', () => {
  const usuariosBody = document.getElementById('denuncias-usuarios-body');
  const torneosBody = document.getElementById('denuncias-torneos-body');
  const modalDetallesBody = document.getElementById('modalDetallesBody');
  const btnBloquear = document.getElementById('btnBloquear');

  let accionActual = null;
  let idObjetivo = null;

  // Cargar denuncias desde el backend
  async function cargarDenuncias() {
    try {
      const res = await fetch('denuncias-process.php');
      const data = await res.json();

      if (!data.success) {
        usuariosBody.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
        torneosBody.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
        return;
      }

      // Renderizar denuncias de usuarios
      usuariosBody.innerHTML = '';
      data.usuario.forEach(d => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${d.id_denuncia}</td>
          <td>${d.reportador || '-'}</td>
          <td>${d.reportado || '-'}</td>
          <td>${d.fecha_creacion}</td>
          <td>
            <button class="btn btn-sm btn-info btn-detalles"
                    data-tipo="usuario"
                    data-id="${d.id_reportado}"
                    data-desc="${d.descripcion}"
                    data-bloqueado-hasta="${d.bloqueado_hasta || ''}">
              Ver detalles
            </button>
          </td>
        `;
        usuariosBody.appendChild(tr);
      });

      // Renderizar denuncias de torneos
      torneosBody.innerHTML = '';
      data.torneo.forEach(d => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${d.id_denuncia}</td>
          <td>${d.reportador || '-'}</td>
          <td>${d.organizador || '-'}</td>
          <td>${d.fecha_creacion}</td>
          <td>
            <button class="btn btn-sm btn-info btn-detalles"
                    data-tipo="torneo"
                    data-id="${d.id_organizador}"
                    data-desc="${d.descripcion}">
              Ver detalles
            </button>
          </td>
        `;
        torneosBody.appendChild(tr);
      });

      // Asignar eventos a botones "Ver detalles"
      document.querySelectorAll('.btn-detalles').forEach(btn => {
        btn.addEventListener('click', () => {
          accionActual = btn.dataset.tipo === 'usuario' ? 'bloquear_usuario' : 'bloquear_torneo';
          idObjetivo = btn.dataset.id;
          const bloqueadoHasta = btn.dataset.bloqueadoHasta || '';
          
          // Mostrar descripción
          modalDetallesBody.innerHTML = `<p>${btn.dataset.desc}</p>`;
          
          // Si hay fecha de bloqueo, mostrar mensaje y ocultar botón
          if (bloqueadoHasta && bloqueadoHasta !== 'null' && bloqueadoHasta !== '' && bloqueadoHasta !== '0000-00-00 00:00:00') {
            try {
              const fechaBloqueo = new Date(bloqueadoHasta);
              if (!isNaN(fechaBloqueo.getTime())) {
                const fechaFormateada = fechaBloqueo.toLocaleDateString('es-ES', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                  hour: '2-digit',
                  minute: '2-digit'
                });
                modalDetallesBody.innerHTML += `<p class="text-warning mt-3"><strong>Este usuario está bloqueado hasta: ${fechaFormateada}</strong></p>`;
                btnBloquear.style.display = 'none';
              } else {
                btnBloquear.style.display = 'block';
              }
            } catch (e) {
              btnBloquear.style.display = 'block';
            }
          } else {
            btnBloquear.style.display = 'block';
          }
          
          const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
          modal.show();
        });
      });

    } catch (err) {
      usuariosBody.innerHTML = `<tr><td colspan="5">Error de conexión</td></tr>`;
      torneosBody.innerHTML = `<tr><td colspan="5">Error de conexión</td></tr>`;
    }
  }

  // Acción de bloquear desde el modal
  btnBloquear.addEventListener('click', async () => {
    if (!accionActual || !idObjetivo) return;

    const payload = { accion: accionActual };
    if (accionActual === 'bloquear_usuario') {
      payload.id_reportado = idObjetivo;
    } else {
      payload.id_torneo = idObjetivo;
    }

    try {
      const res = await fetch('denuncias-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      alert(data.message || data.error);

      const modal = bootstrap.Modal.getInstance(document.getElementById('modalDetalles'));
      modal.hide();
      cargarDenuncias();
    } catch (err) {
      alert('Error de conexión con el servidor');
    }
  });

  // Restablecer el botón cuando se cierra el modal
  const modalDetalles = document.getElementById('modalDetalles');
  modalDetalles.addEventListener('hidden.bs.modal', () => {
    btnBloquear.style.display = 'block';
    modalDetallesBody.innerHTML = '';
    accionActual = null;
    idObjetivo = null;
  });

  // Inicializar
  cargarDenuncias();
});
