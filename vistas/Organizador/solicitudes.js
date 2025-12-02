function showAlert(message, type) {
    const container = document.getElementById("alertContainer");
    
    const alert = document.createElement("div");
    alert.classList.add("gamer-alert", type);
    alert.textContent = message;

    container.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const overlay = document.getElementById('sidebarOverlay');
  const body = document.body;

  menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    overlay.classList.add('visible');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('visible');
    body.classList.remove('menu-open');
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('visible');
    body.classList.remove('menu-open');
  });
  

  const contenedor = document.getElementById('solicitudesContainer');

  fetch("http://localhost/Proyecto-programaci-n-web/Backend/solicitud/listar-solicitudes.php")
    .then(r => r.json())
    .then(resp => {
        contenedor.innerHTML = "";

        if (!resp.data || resp.data.length === 0) {
            contenedor.innerHTML = "<p>No hay solicitudes pendientes.</p>";
            return;
        }

        resp.data.forEach(s => {
            let nombre = s.jugador_nombre || s.equipo_nombre;
            let tipo = s.jugador_nombre ? "Jugador" : "Equipo";

            const card = document.createElement("div");
            card.classList.add("solicitud-card");

            card.innerHTML = `
              <div class="solicitud-info">
                <div class="solicitud-detalle">
                  <div class="solicitud-nombre">${nombre}</div>
                  <div class="solicitud-tipo">${tipo} quiere unirse a ${s.torneo_nombre}</div>
                  <small>${s.fecha_solicitud}</small>
                </div>
              </div>

              <div class="solicitud-acciones">
                <a class="btn-accion btn-aceptar"
                  href="/Proyecto-programaci-n-web/Backend/solicitud/aceptar.php?id_solicitud=${s.id_solicitud_torneo}">Aceptar</a>
                <a class="btn-accion btn-rechazar"
                  href="/Proyecto-programaci-n-web/Backend/solicitud/rechazar.php?id_solicitud=${s.id_solicitud_torneo}">Rechazar</a>
              </div>
            `;

            contenedor.appendChild(card);
        });
    });
});
