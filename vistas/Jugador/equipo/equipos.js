document.addEventListener("DOMContentLoaded", () => {

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
    cargarMisEquipos();


    async function cargarEquipos() {
        try {
            const response = await fetch("get_equipo.php");
            const data = await response.json();

            renderEquiposDisponibles(data.disponibles);
            renderSolicitudes(data.solicitudes);

        } catch (error) {
            console.error("Error al cargar los equipos:", error);
            document.getElementById("equiposDisponibles").innerHTML =
                `<p class="text-danger">No se pudieron cargar los equipos.</p>`;
        }
    }


    async function cargarMisEquipos() {
        const res = await fetch("get_misequipos.php");
        const data = await res.json();
        if (data.error) return;
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

                        <button class="btn btn-primary btn-editar-equipo" data-id="${eq.id}">
                            Cambiar capitan
                        </button>
                    </div>
                </div>`;
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
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ equipo: idEquipo, usuario: nuevoCapitan })
        });

        const data = await resp.json();

        if (data.ok) {
            new bootstrap.Modal(document.getElementById("modal-cambiado")).show();
        } else {
            new bootstrap.Modal(document.getElementById("modal-sin-permiso")).show();
        }
    });



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
                        <button class="btn btn-success btn-solicitud" data-id="${eq.id_equipo}">
                            Enviar solicitud
                        </button>
                    </div>
                </div>`;
        });
    }


 
    document.addEventListener("click", async e => {

        const btn = e.target.closest(".btn-solicitud");
        if (!btn) return;

        const idEquipo = btn.dataset.id;
        const equipoRes = await fetch("get_equipo_detalle.php?id=" + idEquipo);
        const equipoData = await equipoRes.json();
        const juegoEquipo = equipoData.id_juego;
        
        console.log(equipoData);

        
        const validar = await fetch("verificar_cuentajuego.php");
        const tieneCuenta = await validar.json();

        if (!tieneCuenta.ok) {
            document.getElementById("modal-confirmacion").innerHTML = `
                <div class="modal-dialog modal-dialog-centered"> 
                    <div class="modal-content bg-dark text-white border-secondary">
                        <div class="modal-header">
                            <h5 class="modal-title">Falta tu Cuenta de Juego</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Para enviar solicitudes a equipos necesitás cargar primero tu Cuenta de Juego en el perfil.
                        </div>
                    </div>
                </div>`;

            new bootstrap.Modal(document.getElementById("modal-confirmacion")).show();
            return;
        }


        //Verifica en qué juego
    const vRes = await fetch("../../../API/valorant/db-valorant.json");
    const vJSON = await vRes.json();

    // Buscar por nickname 
    const vData = vJSON.usuarios.filter(u => 
        u.puuid === tieneCuenta.id_cuentajuego
    );

  
    const csRes = await fetch("../../../API/Counter-strike/db-cs.json");
    const csJSON = await csRes.json();

   
    const csData = csJSON.jugadores.filter(j => 
        j.steamId64 === tieneCuenta.id_cuentajuego
    );
   
    let juegoCuenta = null;

    if (vData.length > 0) {
    juegoCuenta = 1; 
    } 
    else if (csData.length > 0) {
        juegoCuenta = 2; 
    } 
    else {
        console.log("Tu cuenta no pertenece a ningún juego compatible.");
        return;
    }

    
    if (juegoCuenta !== juegoEquipo) {
    console.log(`
        Tu cuenta no coincide con el juego del equipo.
    `);
    return;
    }

        if(juegoCuenta === juegoEquipo){
        
        const resp = await fetch("solicitud-equipo.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ equipo: idEquipo })
        });

        const data = await resp.json();

        const exito = document.getElementById("modal-confirmacion");

        if (data.ok) {
            exito.innerHTML = `
                <div class="modal-dialog modal-dialog-centered"> 
                    <div class="modal-content bg-dark text-white border-secondary">
                        <div class="modal-header">
                            <h5 class="modal-title">Solicitud Enviada</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                </div>`;
        } else {
            exito.innerHTML = `
                <div class="modal-dialog modal-dialog-centered"> 
                    <div class="modal-content bg-dark text-white border-secondary">
                        <div class="modal-header">
                            <h5 class="modal-title">Solicitud ya enviada previamente</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                </div>`;
        }

        new bootstrap.Modal(document.getElementById("modal-confirmacion")).show();
    }
    });




    function renderSolicitudes(listado) {
        const c = document.getElementById("listaSolicitudes");

        if (!listado || listado.length === 0) {
            return;
        }

        c.innerHTML = "";

        listado.forEach(s => {
            c.innerHTML += `
                <div class="card mb-3 bg-dark text-white border-secondary" id="sol-${s.id_solicitud}">
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
                </div>`;
        });
    }




    async function aceptar(idSolicitud) {

        const card = document.getElementById("sol-" + idSolicitud);

        const resp = await fetch("aceptar-solicitud.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ solicitud: idSolicitud })
        });

        const data = await resp.json();

        if (data.ok) {
            if (card) card.remove();

            document.getElementById("modal-confirmacion").innerHTML = `
                <div class="modal-dialog modal-dialog-centered"> 
                    <div class="modal-content bg-dark text-white border-secondary">
                        <div class="modal-header">
                            <h5 class="modal-title">Solicitud aceptada</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                </div>`;
            new bootstrap.Modal(document.getElementById("modal-confirmacion")).show();
        }
    }


    async function rechazar(idSolicitud) {

        const card = document.getElementById("sol-" + idSolicitud);

        const resp = await fetch("rechazar-solicitud.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ solicitud: idSolicitud })
        });

        const data = await resp.json();

        if (data.ok) {
            if (card) card.remove();
        }
    }


    document.addEventListener("click", e => {
        if (e.target.classList.contains("btn-aceptar")) {
            aceptar(e.target.dataset.id);
        }
        if (e.target.classList.contains("btn-rechazar")) {
            rechazar(e.target.dataset.id);
        }
        if (e.target.classList.contains("btn-ver-equipo")) {
            verEquipo(e.target.dataset.id);
        }
    });



    async function verEquipo(id) {
        const response = await fetch("get_equipo_detalle.php?id=" + id);
        const eq = await response.json();

        document.getElementById("modalEquipoTitulo").textContent = eq.nombre;

        document.getElementById("modalEquipoContenido").innerHTML = `
            <p><strong>Juego:</strong> ${eq.juego}</p>
            <p><strong>Descripción:</strong> ${eq.descripcion}</p>
            <p><strong>Capitana:</strong> ${eq.capitan}</p>
        `;

        new bootstrap.Modal(document.getElementById("modalEquipo")).show();
    }

});
