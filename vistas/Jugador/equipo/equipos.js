document.addEventListener("DOMContentLoaded", () => {
    //cargarEquipos();

    const menuToggle = document.getElementById('menuToggle');
    const closeBtn = document.getElementById('closeBtn');
    document.getElementById("formBuscarEquipos").addEventListener("submit", e => {
        e.preventDefault();
        cargarEquipos();
    });

   // document.getElementById("buscadorEquipos").addEventListener("input", cargarEquipos);
    //document.getElementById("selectJuego").addEventListener("change", cargarEquipos);

    
 menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });
});

cargarEquipos();


async function cargarEquipos() {
    //const q = document.getElementById("buscadorEquipos").value.trim();
  
    try {
        const response = await fetch("get_equipo.php");
        const data = await response.json();
        console.log('Equipos cargados:', data);
        

        
        renderEquiposDisponibles(data.disponibles);

       
        renderSolicitudes(data.solicitudes);
    } catch (error) {
        console.error("Error al cargar los equipos:", error);
        document.getElementById("equiposDisponibles").innerHTML = `<p class="text-danger">No se pudieron cargar los equipos.</p>`;
    }
}


cargarMisEquipos();

async function cargarMisEquipos() {
    const res = await fetch("get_misequipos.php");

    const data = await res.json();
    
    if (data.error) {
        console.error(data.error);
        return;
    }
    
    renderMisEquipos(data.misEquipos);
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

                <button class="btn btn-primary btn-editar-equipo" 
                    data-id="${eq.id}">
                    Editar equipo
                </button>

            </div>
        </div>
    `;
        
    });
}



document.addEventListener("click", e => {
    const btn = e.target.closest(".btn-editar-equipo");
    if (!btn) return;

    const id = btn.dataset.id;
    window.location.href = `editar.php?id=${id}`;
});


async function editarEquipo(id){

    const response = await fetch("process-editar-equipo.php?id=" + id);
            const data = await response.json();

            document.getElementById("edit-id").value = data.id;
            document.getElementById("edit-nombre").value = data.nombre;
            document.getElementById("edit-descripcion").value = data.descripcion;
            document.getElementById("edit-juego").value = data.juego;

            modalEditar.show();
}



function renderEquiposDisponibles(equipos) {
    const c = document.getElementById("equiposDisponibles");
    c.innerHTML = "";

    console.log('equipos disponibles:', equipos);

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

                    <button class="btn btn-success btn-ver-equipo" data-id="${eq.id}">
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
        console.log('No hay solicitudes');
        return;
    }


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
    const response = await fetch("get_equipo.php");
    const data = await response.json();
    console.log(data);
    document.getElementById("modalEquipoTitulo").textContent = data.nombre;

    document.getElementById("modalEquipoContenido").innerHTML = `
        <p><strong>Juego:</strong> ${data.juego}</p>
        <p><strong>Descripción:</strong> ${data.descripcion}</p>
        <p><strong>Capitana:</strong> ${data.capitan}</p>
    `;

    new bootstrap.Modal(document.getElementById("modalEquipo")).show();
}
