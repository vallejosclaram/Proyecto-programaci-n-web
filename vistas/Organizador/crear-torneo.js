document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('create-tournament-form');
    const tName = document.getElementById('tName');
    const tGame = document.getElementById('tGame');
    const tStart = document.getElementById('tStart');
    const tEnd = document.getElementById('tEnd');
    const tScoring = document.getElementById('tScoring');
    const tDesc = document.getElementById('tDesc');
    const teamsList = document.getElementById('teams-list');
    const addTeamBtn = document.getElementById('add-team');
    const resetTeamsBtn = document.getElementById('reset-teams');
    const alertPlaceholder = document.getElementById('alert-placeholder');
    const createdModal = new bootstrap.Modal(document.getElementById('createdModal'));

    const STORAGE_KEY = 'upe_torneos';

    // Helper: show alert
    function showAlert(message, type = 'danger', timeout = 4000) {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        alertPlaceholder.appendChild(wrapper);
        if (timeout) setTimeout(() => bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert')).close(), timeout);
    }

    // Helper: load torneos from localStorage
    function loadTorneos() {
        try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
        } catch (e) {
        return [];
        }
    }

    // Helper: save torneos
    function saveTorneos(list) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
        // Emit event to notify other windows/pages
        window.dispatchEvent(new CustomEvent('torneoCreado', { detail: { timestamp: Date.now() } }));
    }

    // Create team row
    function createTeamRow(value = '') {
        const row = document.createElement('div');
        row.className = 'team-row';

        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.placeholder = 'Nombre del equipo';
        input.value = value;
        input.maxLength = 80;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger';
        removeBtn.textContent = 'Eliminar';

        removeBtn.addEventListener('click', () => {
        row.remove();
        });

        row.appendChild(input);
        row.appendChild(removeBtn);
        teamsList.appendChild(row);
        input.focus();
    }

    // Inicial: agregar 2 equipos por defecto
    createTeamRow('');
    createTeamRow('');

    addTeamBtn.addEventListener('click', () => createTeamRow(''));

    resetTeamsBtn.addEventListener('click', () => {
        teamsList.innerHTML = '';
    });

    // Validaciones
    function validateForm() {
        // limpiar alertas previas
        alertPlaceholder.innerHTML = '';

        const nombre = tName.value.trim();
        const juego = tGame.value.trim();
        const fechaInicio = tStart.value;
        const fechaFin = tEnd.value;
        const equipos = Array.from(teamsList.querySelectorAll('input')).map(i => i.value.trim()).filter(v => v !== '');

        if (!nombre) { showAlert('El nombre del torneo es obligatorio.'); tName.focus(); return false; }
        if (!juego) { showAlert('El campo "Juego / Modalidad" es obligatorio.'); tGame.focus(); return false; }
        if (!fechaInicio) { showAlert('Debes seleccionar la fecha de inicio.'); tStart.focus(); return false; }

        // Fecha no anterior a hoy
        const hoy = new Date();
        hoy.setHours(0,0,0,0);
        const inicio = new Date(fechaInicio);
        if (inicio < hoy) { showAlert('La fecha de inicio no puede ser anterior al día de hoy.'); tStart.focus(); return false; }

        if (fechaFin) {
        const fin = new Date(fechaFin);
        if (fin < inicio) { showAlert('La fecha de fin no puede ser anterior a la fecha de inicio.'); tEnd.focus(); return false; }
        }

        if (equipos.length < 2) { showAlert('Se requieren al menos 2 equipos.'); return false; }

        // Nombre único
        const torneosExistentes = loadTorneos();
        const nombreDuplicado = torneosExistentes.some(t => t.nombre.toLowerCase() === nombre.toLowerCase());
        if (nombreDuplicado) { showAlert('Ya existe un torneo con ese nombre. Elige otro.'); tName.focus(); return false; }

        return {
        nombre,
        juego,
        fechaInicio,
        fechaFin: fechaFin || null,
        scoring: tScoring.value,
        descripcion: tDesc.value.trim(),
        equipos: equipos.map(name => ({ nombre: name, puntos: 0 })) // puntos iniciales 0
        };
    }

    // Submit
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const payload = validateForm();
        if (!payload) return;

        // Guardar
        const torneos = loadTorneos();
        torneos.push(payload);
        saveTorneos(torneos);

        // Mensaje éxito + modal pequeño
        createdModal.show();

        // Limpiar form (mantener sidebar)
        form.reset();
        teamsList.innerHTML = '';
        createTeamRow('');
        createTeamRow('');
    });

    // Soporte: si otra pestaña crea torneos, podríamos actualizar alguna UI (ejemplo)
    window.addEventListener('storage', (ev) => {
        if (ev.key === STORAGE_KEY) {
        // aquí podrías actualizar select de torneos si hay uno en esta página
        // por ahora mostramos una alerta pequeña
        showAlert('La lista de torneos se actualizó en otra ventana.', 'info', 3000);
        }
    });
});
