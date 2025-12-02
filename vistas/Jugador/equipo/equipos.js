document.addEventListener("DOMContentLoaded", () => {
    //cargarEquipos();

    const menuToggle = document.getElementById('menuToggle');
    const closeBtn = document.getElementById('closeBtn');
    document.getElementById("formBuscarEquipos").addEventListener("submit", e => {
        e.preventDefault();
        cargarEquipos();
    });


    
 menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
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
                    Cambiar capitan
                </button>

            </div>
        </div>
    `;
        console.log('id', eq.id);
    });
}



document.addEventListener("click", e => {
     const btnEditar = e.target.closest(".btn-editar-equipo");
    if (!btnEditar) return;

    const id = btnEditar.dataset.id;

    new bootstrap.Modal(document.getElementById("modal-cambiar-capitan")).show();

    
   
    cargarMiembrosEquipo(id);
});

async function cargarMiembrosEquipo(idEquipo) {
    const resp = await fetch("miembros-equipo.php?id=" + idEquipo);
    const data = await resp.json();
    console.log('Miembros del equipo:', data);
    const select = document.getElementById("select-miembros");
    select.innerHTML = "";
    
    data.miembros.forEach(m => {
        const opt = document.createElement("option");
        opt.value = m.id_usuario;
        opt.textContent = m.nombre;
        select.appendChild(opt);
    });

    
    document.getElementById("btn-guardar-capitan").dataset.equipo = idEquipo;
}


    document.getElementById("btn-guardar-capitan").addEventListener("click", async () => {
    const idEquipo = document.getElementById("btn-guardar-capitan").dataset.equipo;
    const nuevoCapitan = document.getElementById("select-miembros").value;

    const resp = await fetch("process-editarequipo.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            equipo: idEquipo,
            usuario: nuevoCapitan
        })
    });
console.log('Respuesta');
    const data = await resp.json();
    
    if (data.ok) {
         new bootstrap.Modal(document.getElementById("modal-cambiado")).show();
        cerrarModal();
        
    } else {
         new bootstrap.Modal(document.getElementById("modal-sin-permiso")).show();
        console.log(data.error);
    }
});

function cerrarModal() {
    document.getElementById("modal-cambiar-capitan").classList.remove("activo");
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

                    <button class="btn btn-success btn-ver-equipo" data-id="${eq.id_equipo}">
                        Ver detalles
                    </button>
                    <button class="btn btn-success btn-solicitud" data-id="${eq.id_equipo}">
                        Enviar solicitud
                    </button>
                </div>
            </div>
        `;
    });
}

document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".btn-solicitud");
    if (!btn) return;

    const idEquipo = btn.dataset.id;

    const resp = await fetch("solicitud-equipo.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ equipo: idEquipo })
    });

    const data = await resp.json();
    console.log("Respuesta solicitud:", data);
    
    const exito =  document.getElementById("modal-confirmacion");
    if (data.ok) {
        
        exito.innerHTML = `
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header">
                <h5 class="modal-title">Solicitud Enviada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
`;
new bootstrap.Modal(document.getElementById("modal-confirmacion")).show();
    } else {
       exito.innerHTML = `
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header">
                <h5 class="modal-title">Ya se había enviado solicitud anteriormente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
`;
new bootstrap.Modal(document.getElementById("modal-confirmacion")).show();
    }
});



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
                    <button class="btn btn-success btn-aceptar" data-id="${s.id_solicitud}">
                        Aceptar
                    </button>
                    <button class="btn btn-danger btn-rechazar" data-id="${s.id_solicitud}">
                        Rechazar
                    </button>
                </div>
                
            </div>
        `;
    });
}


document.addEventListener("click", e => {
    if (e.target.classList.contains("btn-aceptar")) {
        aceptar(e.target.dataset.id);
    }
});

document.addEventListener("click", e => {
    if (e.target.classList.contains("btn-rechazar")) {
        rechazar(e.target.dataset.id);
    }
});

document.addEventListener("click", e => {
    if (e.target.classList.contains("btn-ver-equipo")) {
        verEquipo(e.target.dataset.id);
    }
});

async function aceptar(idSolicitud) {
    const respuesta = document.getElementById("modal-confirmacion");
    const resp = await fetch("aceptar-solicitud.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ solicitud: idSolicitud })
    });
    const data = await resp.json();
    if (data.ok) {
        respuesta.innerHTML = `
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header">
                <h5 class="modal-title">Solicitud aceptada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
`;
new bootstrap.Modal(document.getElementById("modal-confirmacion")).show();
        cargarEquipos();
    } else {
        respuesta.innerHTML = `
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header">
                <h5 class="modal-title">Solicitud rechazada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
`;
new bootstrap.Modal(document.getElementById("modal-confirmacion")).show();
        console.error("Error al gestionar la solicitud:", data.error);
    }
}

async function rechazar(idSolicitud) {

    const resp = await fetch("aceptar-solicitud.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ solicitud: idSolicitud })
    });
    const data = await resp.json();
    if (data.ok) {
        cargarEquipos();
    } else {
        console.error("Error al gestionar la solicitud:", data.error);
    }
}

async function verEquipo(id) {

    
    const response = await fetch("get_equipo_detalle.php?id=" + id);

    const data = await response.json();

    const eq = data;
    console.log('Detalle del equipo:', data);
   
    document.getElementById("modalEquipoTitulo").textContent = eq.nombre;

    document.getElementById("modalEquipoContenido").innerHTML = `
        <p><strong>Juego:</strong> ${eq.juego}</p>
        <p><strong>Descripción:</strong> ${eq.descripcion}</p>
        <p><strong>Capitana:</strong> ${eq.capitan}</p>
    `;

    new bootstrap.Modal(document.getElementById("modalEquipo")).show();
}

});