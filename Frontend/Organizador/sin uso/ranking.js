document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-ranking');
    const filterSelect = document.getElementById('filter-torneo');
    const listado = document.getElementById('listado-ranking');
    const emptyState = document.getElementById('empty-ranking');

    const playerModal = new bootstrap.Modal(document.getElementById('playerModal'));
    const modalNombre = document.getElementById('modal-nombre');
    const modalTorneos = document.getElementById('modal-torneos');
    const modalPuntos = document.getElementById('modal-puntos');
    const modalTorneoActual = document.getElementById('modal-torneo-actual');

    const torneoModal = new bootstrap.Modal(document.getElementById('torneoModal'));
    const modalTorneoNombre = document.getElementById('modal-torneo-nombre');
    const modalTorneoEquipos = document.getElementById('modal-torneo-equipos');

    // Datos de ejemplo
    const listadoDatos = [
        {nombre:'Juan Pérez', juego:'FIFA 23', puntos:1200, torneo:'Torneo FIFA 23', tipo:'jugador', torneosJugados:5},
        {nombre:'María Gómez', juego:'FIFA 23', puntos:1150, torneo:'Torneo PES 2023', tipo:'jugador', torneosJugados:4},
        {nombre:'Torneo FIFA 23', tipo:'torneo', equipos:[
            {nombre:'Equipo A', puntos:300},
            {nombre:'Equipo B', puntos:250},
            {nombre:'Equipo C', puntos:200}
        ]},
        {nombre:'Torneo PES 2023', tipo:'torneo', equipos:[
            {nombre:'Equipo X', puntos:400},
            {nombre:'Equipo Y', puntos:350}
        ]}
    ];

    function renderListado(data) {
    listado.innerHTML = '';

    // Ordenar primero los jugadores por puntos descendente
    const jugadores = data.filter(d => d.tipo === 'jugador').sort((a, b) => b.puntos - a.puntos);
    const torneos = data.filter(d => d.tipo === 'torneo'); // torneos se muestran tal cual

    const orderedData = [...jugadores, ...torneos]; // combinamos para renderizar

    if(orderedData.length === 0){
        emptyState.classList.remove('d-none');
        return;
    } else {
        emptyState.classList.add('d-none');
    }

    orderedData.forEach(item => {
        const card = document.createElement('div');
        card.className = 'solicitud-card';
        card.style.cursor = item.tipo === 'jugador' || item.tipo === 'torneo' ? 'pointer' : 'default';

        const avatar = document.createElement('div');
        avatar.className = 'avatar';
        avatar.textContent = item.tipo === 'jugador' ? item.puntos : '-';

        const meta = document.createElement('div');
        meta.className = 'meta';
        const h5 = document.createElement('h5');
        h5.textContent = item.nombre;
        meta.appendChild(h5);

        if(item.tipo === 'jugador'){
            const juego = document.createElement('small');
            juego.textContent = `Juego: ${item.juego}`;
            const puntos = document.createElement('small');
            puntos.textContent = `Puntos: ${item.puntos} | Torneo: ${item.torneo}`;
            meta.appendChild(juego);
            meta.appendChild(document.createElement('br'));
            meta.appendChild(puntos);

            card.addEventListener('click', () => {
                modalNombre.innerHTML = `<strong>Nombre:</strong> ${item.nombre}`;
                modalTorneos.innerHTML = `<strong>Torneos jugados:</strong> ${item.torneosJugados}`;
                modalPuntos.innerHTML = `<strong>Puntos acumulados:</strong> ${item.puntos}`;
                modalTorneoActual.innerHTML = `<strong>Torneo actual:</strong> ${item.torneo}`;
                playerModal.show();
            });

        } else if(item.tipo === 'torneo'){
            card.addEventListener('click', () => {
                modalTorneoNombre.innerHTML = `<strong>Torneo:</strong> ${item.nombre}`;
                modalTorneoEquipos.innerHTML = '';

                // Ordenar los equipos por puntos descendente
                const equiposOrdenados = item.equipos.sort((a,b) => b.puntos - a.puntos);
                equiposOrdenados.forEach(e => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${e.nombre}</td><td>${e.puntos}</td>`;
                    modalTorneoEquipos.appendChild(tr);
                });
                torneoModal.show();
            });
        }

        card.appendChild(avatar);
        card.appendChild(meta);
        listado.appendChild(card);
    });
}

    // Render inicial
    renderListado(listadoDatos);

    // Filtrado
    function filtrarListado() {
        const query = searchInput.value.toLowerCase();
        const tipo = filterSelect.value;
        const filtered = listadoDatos.filter(j => {
            const matchTipo = tipo === 'all' ? true : (j.tipo === tipo);
            const matchSearch = j.nombre.toLowerCase().includes(query);
            return matchTipo && matchSearch;
        });
        renderListado(filtered);
    }

    searchInput.addEventListener('input', filtrarListado);
    filterSelect.addEventListener('change', filtrarListado);

    document.getElementById('clear-ranking').addEventListener('click', () => {
        searchInput.value = '';
        filterSelect.value = 'all';
        renderListado(listadoDatos);
    });
});

