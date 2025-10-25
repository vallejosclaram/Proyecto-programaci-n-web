document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;

  menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });
  

  // Datos de ejemplo
  const denuncias = [
    { denunciante: "Juan", denunciado: "Pedro", motivo: "Mal comportamiento", fecha: "2025-10-18", estado: "Pendiente" },
    { denunciante: "María", denunciado: "Lucas", motivo: "No respetó reglas", fecha: "2025-10-17", estado: "Pendiente" },
    { denunciante: "Ana", denunciado: "Carlos", motivo: "Hizo trampa", fecha: "2025-10-16", estado: "Revisada" },
  ];

  const container = document.getElementById("denunciasContainer");

  function verPerfil(nombreJugador) {
    // Redirigir al perfil, por ejemplo:
    window.location.href = `../Jugador/perfil/ver.html?jugador=${nombreJugador}`;
  }

  function eliminarDenuncia(index) {
    if (confirm(`¿Eliminar esta denuncia?`)) {
      denuncias.splice(index, 1);
      renderDenuncias();
    }
  }


  function renderDenuncias() {
    container.innerHTML = "";
    denuncias.forEach((d, index) => {
      const card = document.createElement("div");
      card.classList.add("denuncia-card");

      card.innerHTML = `
        <div class="denuncia-info">
          <span><span class="perfil-label">Denunciante:</span> ${d.denunciante}</span>
          <span><span class="perfil-label">Denunciado:</span> ${d.denunciado}</span>
          <span><span class="perfil-label">Motivo:</span> ${d.motivo}</span>
          <span><span class="perfil-label">Fecha:</span> ${d.fecha}</span>
          <span><span class="perfil-label">Estado:</span> ${d.estado}</span>
        </div>
        <div class="denuncia-actions">
          <div class="dropdown">
          <button class="btn-primary dropbtn">Acciones ▼</button>
          <div class="dropdown-content">
            <button class="btn-warning">Suspender jugador</button>
            <button class="btn-primary" onclick="verPerfil('Pedro')">Ver perfil</button>
            <button class="btn-editar btn-delete">Eliminar</button>
          </div>
        </div>
        </div>
      `;

      // Eventos de botones
      card.querySelector(".btn-review")?.addEventListener("click", () => {
        denuncias[index].estado = "Revisada";
        renderDenuncias();
      });

      card.querySelector(".btn-delete").addEventListener("click", () => {
        if (confirm(`¿Eliminar denuncia de ${d.denunciante} contra ${d.denunciado}?`)) {
          denuncias.splice(index, 1);
          renderDenuncias();
        }
      });

      container.appendChild(card);
    });
  }

  renderDenuncias();
});

