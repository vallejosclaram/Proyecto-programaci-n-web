document.addEventListener('DOMContentLoaded', () => {
  const resultadosBody = document.getElementById('resultados-body');
  const rankingBody = document.getElementById('ranking-body');
  const modalDetallesBody = document.getElementById('modalDetallesBody');
  const btnAceptar = document.getElementById('btnAceptar');
  const btnRechazar = document.getElementById('btnRechazar');

  // Filtros resultados
  const filtroTipo = document.getElementById('filtroTipo');
  const filtroNombreTorneo = document.getElementById('filtroNombreTorneo');
  const filtroNombreJuego = document.getElementById('filtroNombreJuego');
  const btnFiltrar = document.getElementById('btnFiltrar');
  const btnLimpiar = document.getElementById('btnLimpiar');

  // Filtros ranking
  const filtroRankingJuego = document.getElementById('filtroRankingJuego');
  const filtroRankingTipo = document.getElementById('filtroRankingTipo');
  const btnFiltrarRanking = document.getElementById('btnFiltrarRanking');
  const btnLimpiarRanking = document.getElementById('btnLimpiarRanking');

  // Modales
  const modalConfirmarAceptar = new bootstrap.Modal(document.getElementById('modalConfirmarAceptar'));
  const modalConfirmarRechazar = new bootstrap.Modal(document.getElementById('modalConfirmarRechazar'));
  const modalMensaje = new bootstrap.Modal(document.getElementById('modalMensaje'));
  const modalMensajeBody = document.getElementById('modalMensajeBody');

  let idResultadoActual = null;
  let estadoResultadoActual = null;

  // Función para mostrar mensaje en modal
  function mostrarMensaje(mensaje, esError = false) {
    modalMensajeBody.innerHTML = `<p class="${esError ? 'text-danger' : 'text-success'}">${mensaje}</p>`;
    modalMensaje.show();
  }

  // Cargar resultados desde el backend con filtros opcionales
  async function cargarResultados(filtros = {}) {
    try {
      const res = await fetch('resultados-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(filtros)
      });
      const data = await res.json();

      if (!data.success) {
        resultadosBody.innerHTML = `<tr><td colspan="10">${data.error}</td></tr>`;
        return;
      }

      resultadosBody.innerHTML = '';
      data.resultados.forEach(r => {
        const tr = document.createElement('tr');
        const estadoValidacion = r.estado_validacion || 'pendiente';
        const estaAprobadoORechazado = estadoValidacion === 'aprobado' || estadoValidacion === 'rechazado';
        
        // Clase para el estado
        let claseEstado = '';
        if (estadoValidacion === 'aprobado') claseEstado = 'text-success';
        else if (estadoValidacion === 'rechazado') claseEstado = 'text-danger';
        else claseEstado = 'text-warning';

        tr.innerHTML = `
          
          <td>${r.juego || '-'}</td>
          <td>${r.torneo || '-'}</td>
          <td>${r.tipo_torneo || '-'}</td>
          <td>${r.jugador || '-'}</td>
          <td>${r.equipo || '-'}</td>
          <td>${r.puntaje_obtenido}</td>
          <td>${r.fecha_registro}</td>
          <td><span class="${claseEstado}">${estadoValidacion}</span></td>
          <td>
            <button class="btn btn-sm btn-info btn-detalles ${estaAprobadoORechazado ? 'disabled' : ''}"
                    data-id="${r.id_puntaje}"
                    data-estado="${estadoValidacion}"
                    data-desc="Juego: ${r.juego || '-'} | Torneo: ${r.torneo || '-'} | Tipo: ${r.tipo_torneo || '-'} | Puntaje: ${r.puntaje_obtenido}"
                    ${estaAprobadoORechazado ? 'disabled' : ''}>
              Ver detalles
            </button>
          </td>
        `;
        resultadosBody.appendChild(tr);
      });

      // Asignar eventos a botones "Ver detalles"
      document.querySelectorAll('.btn-detalles').forEach(btn => {
        btn.addEventListener('click', () => {
          if (btn.disabled) return;
          
          idResultadoActual = btn.dataset.id;
          estadoResultadoActual = btn.dataset.estado;
          
          // Deshabilitar botones si ya está aprobado o rechazado
          const estaAprobadoORechazado = estadoResultadoActual === 'aprobado' || estadoResultadoActual === 'rechazado';
          btnAceptar.disabled = estaAprobadoORechazado;
          btnRechazar.disabled = estaAprobadoORechazado;
          
          if (estaAprobadoORechazado) {
            btnAceptar.classList.add('disabled');
            btnRechazar.classList.add('disabled');
          } else {
            btnAceptar.classList.remove('disabled');
            btnRechazar.classList.remove('disabled');
          }
          
          modalDetallesBody.textContent = btn.dataset.desc;
          const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
          modal.show();
        });
      });

    } catch (err) {
      resultadosBody.innerHTML = `<tr><td colspan="10">Error de conexión</td></tr>`;
    }
  }

  // Cargar ranking desde el backend
  async function cargarRanking(filtros = {}) {
    try {
      const res = await fetch('resultados-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ accion: 'obtener_ranking', ...filtros })
      });
      const data = await res.json();

      if (!data.success) {
        rankingBody.innerHTML = `<tr><td colspan="4">${data.error || 'No hay datos de ranking'}</td></tr>`;
        return;
      }

      rankingBody.innerHTML = '';
      if (data.ranking && data.ranking.length > 0) {
        data.ranking.forEach((item, index) => {
          const tr = document.createElement('tr');
          const posicion = index + 1;
          let clasePos = '';
          if (posicion === 1) clasePos = 'text-warning';
          else if (posicion === 2) clasePos = 'text-secondary';
          else if (posicion === 3) clasePos = 'text-info';
          
          tr.innerHTML = `
            <td><span class="${clasePos}"><strong>${posicion}</strong></span></td>
            <td>${item.nombre || '-'}</td>
            <td>${item.juego || '-'}</td>
            <td><strong>${item.puntos_totales || 0}</strong></td>
          `;
          rankingBody.appendChild(tr);
        });
      } else {
        rankingBody.innerHTML = `<tr><td colspan="4">No hay datos de ranking disponibles</td></tr>`;
      }
    } catch (err) {
      rankingBody.innerHTML = `<tr><td colspan="4">Error de conexión</td></tr>`;
    }
  }

  // Acción de aceptar resultado (mostrar confirmación)
  btnAceptar.addEventListener('click', () => {
    if (btnAceptar.disabled || !idResultadoActual) return;
    modalConfirmarAceptar.show();
  });

  // Confirmar aceptación
  document.getElementById('btnConfirmarAceptar').addEventListener('click', async () => {
    if (!idResultadoActual) return;

    const payload = { accion: 'aceptar_resultado', id_puntaje: idResultadoActual };

    try {
      const res = await fetch('resultados-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json();

      modalConfirmarAceptar.hide();
      const modalDetalles = bootstrap.Modal.getInstance(document.getElementById('modalDetalles'));
      modalDetalles.hide();

      mostrarMensaje(data.message || data.error, !data.success);
      
      if (data.success) {
        cargarResultados();
        cargarRanking();
      }
    } catch (err) {
      modalConfirmarAceptar.hide();
      mostrarMensaje('Error de conexión con el servidor', true);
    }
  });

  // Acción de rechazar resultado (mostrar confirmación)
  btnRechazar.addEventListener('click', () => {
    if (btnRechazar.disabled || !idResultadoActual) return;
    modalConfirmarRechazar.show();
  });

  // Confirmar rechazo
  document.getElementById('btnConfirmarRechazar').addEventListener('click', async () => {
    if (!idResultadoActual) return;

    const payload = { accion: 'rechazar_resultado', id_puntaje: idResultadoActual };

    try {
      const res = await fetch('resultados-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json();

      modalConfirmarRechazar.hide();
      const modalDetalles = bootstrap.Modal.getInstance(document.getElementById('modalDetalles'));
      modalDetalles.hide();

      mostrarMensaje(data.message || data.error, !data.success);
      
      if (data.success) {
        cargarResultados();
        cargarRanking();
      }
    } catch (err) {
      modalConfirmarRechazar.hide();
      mostrarMensaje('Error de conexión con el servidor', true);
    }
  });

  // Acción de filtrar resultados
  btnFiltrar.addEventListener('click', () => {
    const filtros = {};
    
    if (filtroNombreJuego && filtroNombreJuego.value.trim()) {
      filtros.nombre_juego = filtroNombreJuego.value.trim();
    }
    
    if (filtroNombreTorneo && filtroNombreTorneo.value.trim()) {
      filtros.nombre_torneo = filtroNombreTorneo.value.trim();
    }
    
    if (filtroTipo && filtroTipo.value) {
      filtros.tipo_torneo = filtroTipo.value;
    }
    
    cargarResultados(filtros);
  });

  // Acción de limpiar filtros
  btnLimpiar.addEventListener('click', () => {
    if (filtroNombreJuego) filtroNombreJuego.value = '';
    if (filtroNombreTorneo) filtroNombreTorneo.value = '';
    if (filtroTipo) filtroTipo.value = '';
    cargarResultados();
  });

  // Acción de filtrar ranking
  btnFiltrarRanking.addEventListener('click', () => {
    const filtros = {};
    
    if (filtroRankingJuego && filtroRankingJuego.value.trim()) {
      filtros.nombre_juego = filtroRankingJuego.value.trim();
    }
    
    if (filtroRankingTipo && filtroRankingTipo.value) {
      filtros.tipo = filtroRankingTipo.value;
    }
    
    cargarRanking(filtros);
  });

  // Acción de limpiar filtros ranking
  btnLimpiarRanking.addEventListener('click', () => {
    if (filtroRankingJuego) filtroRankingJuego.value = '';
    if (filtroRankingTipo) filtroRankingTipo.value = '';
    cargarRanking();
  });

  // Inicializar
  cargarResultados();
  cargarRanking();
});
