document.addEventListener('DOMContentLoaded', function () {
    const tablaPendientes = document.getElementById('tabla-pendientes');
    const noPendientesMsg = document.getElementById('no-pendientes');
    const alertContainer = document.getElementById('alert-container');
    const modalConfirmar = new bootstrap.Modal(document.getElementById('confirmarModal'));

    const modalDetalle = document.getElementById('modal-detalle-partida');
    const modalResultado = document.getElementById('modal-resultado-propuesto');
    const btnConfirmarAceptar = document.getElementById('btnConfirmarAceptar');
    const btnConfirmarRechazar = document.getElementById('btnConfirmarRechazar');

    const selectTorneoBracket = document.getElementById('selectTorneoBracket');
    const btnVerBracket = document.getElementById('btnVerBracket');
    const bracketDrawContainer = document.getElementById('bracket-draw-container');

    const selectTorneoRanking = document.getElementById('selectTorneoRanking');
    const selectTipoRanking = document.getElementById('selectTipoRanking');
    const btnVerRanking = document.getElementById('btnVerRanking');
    const rankingContainer = document.getElementById('ranking-container');

    let partidaEnProceso = null;

    function mostrarAlerta(mensaje, tipo = 'success') {
        const alertHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        alertContainer.innerHTML = alertHTML;
        setTimeout(() => {
            const alertElement = alertContainer.querySelector('.alert');
            if (alertElement) {
                new bootstrap.Alert(alertElement).close();
            }
        }, 5000);
    }

    async function fetchBackend(accion, data = {}) {
        try {
            const response = await fetch('resultados-process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion, ...data })
            });
            return await response.json();
        } catch (error) {
            console.error('Error de red o JSON:', error);
            mostrarAlerta('Error de conexión con el servidor.', 'danger');
            return { success: false, error: 'Error de red' };
        }
    }

    async function cargarResultadosPendientes() {
        tablaPendientes.innerHTML = '<tr><td colspan="8" class="text-center">Cargando resultados pendientes...</td></tr>';

        const result = await fetchBackend('obtener_pendientes');

        tablaPendientes.innerHTML = '';
        noPendientesMsg.style.display = 'none';

        if (result.success && result.partidas.length > 0) {
            result.partidas.forEach(p => {
                const nombreP1 = p.tipo === 'individual' ? p.jugador1_email : p.equipo1_nombre;
                const nombreP2 = p.tipo === 'individual' ? p.jugador2_email : p.equipo2_nombre;

                const row = `
                    <tr>
                        <td>${p.id_partida}</td>
                        <td>Torneo ID ${p.id_torneo}</td>
                        <td>Ronda ${p.ronda}</td>
                        <td>${p.tipo.charAt(0).toUpperCase() + p.tipo.slice(1)}</td>
                        <td>${nombreP1 || 'N/A'}</td>
                        <td>${nombreP2 || 'N/A'}</td>
                        <td>
                            <span class="badge bg-warning text-dark">${p.resultado_propuesto}</span>
                            <br><small class="text-muted">Reportado por: ${p.propone_usuario}</small>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-accion"
                                data-accion="aceptar"
                                data-id-partida="${p.id_partida}"
                                data-resultado="${p.resultado_propuesto}"
                                data-detalle="${nombreP1} vs ${nombreP2} (Ronda ${p.ronda})">Aceptar</button>

                            <button class="btn btn-sm btn-danger btn-accion"
                                data-accion="rechazar"
                                data-id-partida="${p.id_partida}"
                                data-resultado="${p.resultado_propuesto}"
                                data-detalle="${nombreP1} vs ${nombreP2} (Ronda ${p.ronda})">Rechazar</button>
                        </td>
                    </tr>`;
                tablaPendientes.insertAdjacentHTML('beforeend', row);
            });
        } else {
            noPendientesMsg.style.display = 'block';
        }
    }

    tablaPendientes.addEventListener('click', function (e) {
        const target = e.target;

        if (target.classList.contains('btn-accion')) {
            partidaEnProceso = {
                id: target.dataset.idPartida,
                resultado: target.dataset.resultado,
                detalle: target.dataset.detalle,
                accion: target.dataset.accion
            };

            modalDetalle.textContent = partidaEnProceso.detalle;
            modalResultado.textContent = partidaEnProceso.resultado;

            if (partidaEnProceso.accion === 'rechazar') {
                document.getElementById('confirmarModalLabel').textContent = 'Confirmar Rechazo';
                document.querySelector('#confirmarModal .modal-body p:first-child').innerHTML =
                    '¿Está seguro de que desea <strong>rechazar</strong> este resultado?';

                btnConfirmarAceptar.style.display = 'none';
                btnConfirmarRechazar.style.display = 'inline-block';
            } else {
                document.getElementById('confirmarModalLabel').textContent = 'Confirmar Aceptación';
                document.querySelector('#confirmarModal .modal-body p:first-child').innerHTML =
                    '¿Está seguro de que desea <strong>aceptar</strong> el siguiente resultado?';

                btnConfirmarAceptar.style.display = 'inline-block';
                btnConfirmarRechazar.style.display = 'none';
            }

            modalConfirmar.show();
        }
    });

    async function procesarResultado(accion) {
        if (!partidaEnProceso) return;

        const data = { id_partida: partidaEnProceso.id };
        if (accion === 'aceptar_resultado') data.resultado = partidaEnProceso.resultado;

        const result = await fetchBackend(accion, data);

        modalConfirmar.hide();
        partidaEnProceso = null;

        if (result.success) {
            mostrarAlerta(result.message, 'success');
            cargarResultadosPendientes();
        } else {
            mostrarAlerta(result.error || 'Ocurrió un error al procesar la acción.', 'danger');
        }
    }

    btnConfirmarAceptar.addEventListener('click', () => procesarResultado('aceptar_resultado'));
    btnConfirmarRechazar.addEventListener('click', () => procesarResultado('rechazar_resultado'));

    async function cargarListaTorneos() {
        const result = await fetchBackend('listar_torneos');

        selectTorneoBracket.innerHTML = '<option value="">Seleccione un torneo</option>';
        selectTorneoRanking.innerHTML = '<option value="">Seleccione un torneo</option>';

        if (result.success && result.torneos.length > 0) {
            result.torneos.forEach(torneo => {
                const option = `<option value="${torneo.id_torneo}">${torneo.nombre} (ID: ${torneo.id_torneo})</option>`;
                selectTorneoBracket.insertAdjacentHTML('beforeend', option);
                selectTorneoRanking.insertAdjacentHTML('beforeend', option);
            });
        } else {
            selectTorneoBracket.innerHTML = '<option value="">No se encontraron torneos</option>';
            selectTorneoRanking.innerHTML = '<option value="">No se encontraron torneos</option>';
        }
    }

    selectTorneoBracket.addEventListener('change', () => {
        btnVerBracket.disabled = !selectTorneoBracket.value;
    });

    selectTorneoRanking.addEventListener('change', () => {
        btnVerRanking.disabled = !selectTorneoRanking.value;
    });

    btnVerBracket.addEventListener('click', async () => {
        const idTorneo = selectTorneoBracket.value;
        if (!idTorneo) return;

        bracketDrawContainer.innerHTML = '<p class="text-center">Cargando bracket...</p>';

        const result = await fetchBackend('obtener_bracket', { id_torneo: idTorneo });

        if (result.success && result.bracket.length > 0) {
            dibujarBracket(result.bracket);
        } else {
            bracketDrawContainer.innerHTML =
                '<p class="text-muted text-center">No se encontraron partidas o hubo un error al cargar el bracket.</p>';

            if (result.error) mostrarAlerta(result.error, 'danger');
        }
    });

    btnVerRanking.addEventListener('click', async () => {
        const idTorneo = selectTorneoRanking.value;
        const tipoRanking = selectTipoRanking.value;
        if (!idTorneo) return;

        rankingContainer.innerHTML = '<p class="text-center">Cargando ranking...</p>';

        const result = await fetchBackend('obtener_ranking', { id_torneo: idTorneo, tipo: tipoRanking });

        if (result.success && result.ranking.length > 0) {
            dibujarRanking(result.ranking, tipoRanking);
        } else {
            rankingContainer.innerHTML =
                '<p class="text-muted text-center">No hay datos de ranking disponibles para este torneo.</p>';

            if (result.error) mostrarAlerta(result.error, 'danger');
        }
    });

    function dibujarBracket(partidas) {
        bracketDrawContainer.innerHTML = '<h4>Estructura del Bracket</h4>';

        const rondas = partidas.reduce((acc, p) => {
            acc[p.ronda] = acc[p.ronda] || [];
            acc[p.ronda].push(p);
            return acc;
        }, {});

        const rondasOrdenadas = Object.keys(rondas).sort((a, b) => parseInt(a) - parseInt(b));

        let html = '<div class="bracket-display row">';

        rondasOrdenadas.forEach(rondaKey => {
            const partidosRonda = rondas[rondaKey];

            html += `
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <h5 class="text-center">Ronda ${rondaKey}</h5>
                    <ul class="list-group">`;

            partidosRonda.forEach(p => {
                const participante1 = p.tipo === 'individual' ? (p.jugador1 || 'Pendiente') : (p.equipo1 || 'Pendiente');
                const participante2 = p.tipo === 'individual' ? (p.jugador2 || 'Pendiente') : (p.equipo2 || 'Pendiente');
                const resultado = p.resultado_final
                    ? `<span class="badge bg-success">${p.resultado_final}</span>`
                    : `<span class="badge bg-secondary">${p.estado}</span>`;
                const fecha = p.fecha || 'N/D';

                html += `
                    <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">
                        <div>
                            <small class="d-block text-muted">ID: ${p.id_partida} (${fecha})</small>
                            <strong>${participante1}</strong> vs <strong>${participante2}</strong>
                        </div>
                        ${resultado}
                    </li>`;
            });

            html += `</ul></div>`;
        });

        html += '</div>';
        bracketDrawContainer.innerHTML = html;
    }

    function dibujarRanking(rankingData, tipo) {
        rankingContainer.innerHTML = '';

        const tipoNombre = tipo === 'individual' ? 'Jugador' : 'Equipo';

        let html = `
            <div class="table-scroll">
                <table class="table table-dark table-hover mt-3">
                    <thead>
                        <tr>
                            <th>Posición</th>
                            <th>${tipoNombre}</th>
                            <th>Puntos Totales</th>
                            <th>Victorias</th>
                            <th>Empates</th>
                            <th>Derrotas</th>
                        </tr>
                    </thead>
                    <tbody>`;

        rankingData.forEach((r, index) => {
            const posicion = index + 1;

            html += `
                <tr>
                    <td><span class="badge ${posicion <= 3 ? 'bg-warning text-dark' : 'bg-secondary'}">${posicion}</span></td>
                    <td>${r.nombre_entidad || 'Entidad Desconocida'}</td>
                    <td>${r.puntos}</td>
                    <td>${r.victorias}</td>
                    <td>${r.empates}</td>
                    <td>${r.derrotas}</td>
                </tr>`;
        });

        html += `</tbody></table></div>`;
        rankingContainer.innerHTML = html;
    }

    cargarResultadosPendientes();
    cargarListaTorneos();
});
