document.addEventListener("DOMContentLoaded", () => {
    cargarEquipos();

    document.getElementById("formBuscarEquipos").addEventListener("submit", e => {
        e.preventDefault();
        cargarEquipos();
    });

    document.getElementById("buscadorEquipos").addEventListener("input", cargarEquipos);
    document.getElementById("selectJuego").addEventListener("change", cargarEquipos);
});


async function cargarEquipos() {
    const q = document.getElementById("buscadorEquipos").value.trim();
    const juego = document.getElementById("selectJuego").value;

    const url = `procesar-equipos.php?q=${encodeURIComponent(q)}&juego=${encodeURIComponent(juego)}`;
    const response = await fetch(url);
    const data = await response.json();

    renderMisEquipos(data.misEquipos);
    renderEquiposDisponibles(data.disponibles);
    renderSolicitudes(data.solicitudes);
}



function renderMisEquipos(equipos) {
    const c = document.getElementById("misEquipos");
    c.innerHTML = "";

    if (!equipos || equipos.length === 0) {
        c.innerHTML = `<p class="text-muted">No estás en ningún equipo.</p>`;
        return;
    }

    equipos.forEach(eq => {
        c.innerHTML += `
            <div class="card mb-3 bg-dark text-white border-secondary">
                <div class="card-body">
                    <h5>${eq.nombre}</h5>
                    <p>${eq.descripcion}</p>
                    <p><strong>Juego:</strong> ${eq.juego}</p>

                    <button class="btn btn-violeta btn-ver-equipo" data-id="${eq.id_equipo}">
                        Ver detalles
                    </button>
                </div>
            </div>
        `;
    });
}



function renderEquiposDisponibles(equipos) {
    const c = document.getElementById("equiposDisponibles");
    c.innerHTML = "";

    if (!equipos || equipos.length === 0) {
        c.innerHTML = `<p class="text-muted">No hay equipos disponibles.</p>`;
        return;
    }

    equipos.forEach(eq => {
        c.innerHTML += `
            <div class="card mb-3 bg-dark text-white border-secondary">
                <div class="card-body">
                    <h5>${eq.nombre}</h5>
                    <p>${eq.descripcion}</p>
                    <p><strong>Juego:</strong> ${eq.juego}</p>

                    <button class="btn btn-success btn-ver-equipo" data-id="${eq.id_equipo}">
                        Ver detalles
                    </button>
                </div>
            </div>
        `;
    });
}



function renderSolicitudes(listado) {
    const section = document.getElementById("solicitudesSection");
    const c = document.getElementById("listaSolicitudes");

    if (!listado || listado.length === 0) {
        section.style.display = "none";
        return;
    }

    section.style.display = "block";
    c.innerHTML = "";

    listado.forEach(s => {
        c.innerHTML += `
            <div class="card mb-3 bg-dark text-white border-secondary">
                <div class="card-body">
                    <p><strong>Usuario:</strong> ${s.usuario}</p>
                    <p><strong>Equipo:</strong> ${s.equipo}</p>
                    <p><strong>Fecha:</strong> ${s.fecha_solicitud}</p>
                </div>
            </div>
        `;
    });
}



document.addEventListener("click", e => {
    if (e.target.classList.contains("btn-ver-equipo")) {
        verEquipo(e.target.dataset.id);
    }
});


async function verEquipo(id) {
    const response = await fetch(`get_equipo.php?id=${id}`);
    const data = await response.json();

    document.getElementById("modalEquipoTitulo").textContent = data.nombre;

    document.getElementById("modalEquipoContenido").innerHTML = `
        <p><strong>Juego:</strong> ${data.juego}</p>
        <p><strong>Descripción:</strong> ${data.descripcion}</p>
        <p><strong>Capitana:</strong> ${data.capitan}</p>
    `;

    new bootstrap.Modal(document.getElementById("modalEquipo")).show();
}
