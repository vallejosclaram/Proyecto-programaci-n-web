document.addEventListener('DOMContentLoaded', () => {

  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;
  const form = document.getElementById('formTorneo');
  const toastEl = document.getElementById('toastTorneo');
  const toast = new bootstrap.Toast(toastEl);
  const modalEl = document.getElementById('crearTorneoModal');
  const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

  // SIDEBAR
  menuToggle?.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });

  // Evitar focus en elemento oculto del modal
  if (modalEl) {
    modalEl.addEventListener("hidden.bs.modal", () => {
      document.activeElement.blur();
    });
  }

  // FORMULARIO CREAR TORNEO
  form?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("http://localhost/Proyecto-programaci-n-web/vistas/crear-torneo.php", {
      method: 'POST',
      body: formData
    })
    .then(res => res.json()) // <-- directamente JSON
    .then(data => {

      if (!data || data.status !== "ok") {
        console.error("Error en el servidor", data);
        return;
      }

      // Mostrar toast
      toast.show();

      // Reset form y cerrar modal
      form.reset();
      modal.hide();

      // Generar ruta de imagen usando mismo criterio que PHP
      let imagenRuta = "../img/default.png";
      if (data.juego.toLowerCase().includes("valorant")) imagenRuta = "../img/valorant.jpg";
      else if (data.juego.toLowerCase().includes("counter")) imagenRuta = "../img/Counter-Strike.jpg";

      // Construir card
      const nuevaCard = `

        <div class="torneo-card" data-id="${data.id_torneo}">

          <img src="${imagenRuta}" alt="${data.juego}">

          <h3>${data.nombre}</h3>

          <p><i class="fa-solid fa-gamepad"></i> 
            <strong>Juego:</strong> ${data.juego}
          </p>

          <p><i class="fa-solid fa-calendar"></i> 
            <strong>Fecha:</strong>
            ${data.fecha_inicio.split('-').reverse().join('/')} -
            ${data.fecha_fin.split('-').reverse().join('/')}
          </p>

          <p><i class="fa-solid fa-flag"></i> 
            <strong>Estado:</strong> ${data.estado}
          </p>

          <p><i class="fa-solid fa-layer-group"></i> 
            <strong>Tipo:</strong> ${data.tipo}
          </p>

          <div class="card-actions">
            <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
            <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
            <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      `;

      // Insertar al inicio del grid
      document.querySelector(".torneos-grid").insertAdjacentHTML("afterbegin", nuevaCard);

    })
    .catch(err => {
      console.error("Error:", err);
    });
  });
  document.addEventListener('click', async function(e) {
    if (e.target.closest('.btn-ver')) {

        const card = e.target.closest('.torneo-card');
        const idTorneo = card.getAttribute('data-id');
        // 💥 TOMO LA MISMA IMAGEN DE LA CARD (YA RESUELTA EN PHP)
        const imgCard = card.querySelector("img").getAttribute("src");

        // Asignarla al modal
        document.getElementById("verImagenJuego").src = imgCard;

        const formData = new FormData();
        formData.append("id_torneo", idTorneo);

        const res = await fetch("http://localhost/Proyecto-programaci-n-web/vistas/obtener-torneo.php" ,{
            method: "POST",
            body: formData
        });

        const json = await res.json();

        if (json.status !== "ok") {
            console.error(json.msg);
            return;
        }

        const t = json.data;

        // ORGANIZADOR
        document.getElementById("verOrganizador").textContent = t.organizador;

        // ESTADO (bolita verde o roja)
        const estado = t.estado.trim().toLowerCase();
        let badgeHTML = "";

        if (estado === "activo") {
            badgeHTML = `
                <span class="badge-estado" 
                      style="background:#38ff73; color:#000; box-shadow:0 0 12px #38ff73;">
                    🟢 Activo
                </span>
            `;
        } 
        else if (estado === "cerrado") {
            badgeHTML = `
                <span class="badge-estado" 
                      style="background:#ff4d4d; color:#000; box-shadow:0 0 12px #ff4d4d;">
                    🔴 Cerrado
                </span>
            `;
        }
        else if (estado === "bloqueado") {
            badgeHTML = `
                <span class="badge-estado" 
                      style="background:#ffcc00; color:#000; box-shadow:0 0 12px #ffcc00;">
                    🔒 Bloqueado
                </span>
            `;
        }

        document.getElementById("estadoBadge").innerHTML = badgeHTML;

        // Info básica
        document.getElementById("verNombre").textContent = t.nombre_torneo;
        document.getElementById("verJuego").textContent = t.juego;
        document.getElementById("verTipo").textContent = t.tipo;

        document.getElementById("verInicio").textContent = 
            t.fecha_inicio.split("-").reverse().join("/");
        document.getElementById("verFin").textContent = 
            t.fecha_fin.split("-").reverse().join("/");

        // ===============================
      // JUGADORES / EQUIPOS DEL TORNEO
      // ===============================
      const detalle = json.detalle;
      const bloque = document.getElementById("bloqueJugadores");

      if (t.tipo === "Individual") {

          bloque.innerHTML = `
              <h4>🧍 Jugadores Inscritos</h4>
              <ul>
                  ${detalle.length > 0 
                      ? detalle.map(j => `<li>${j}</li>`).join("")
                      : "<li>No hay jugadores registrados.</li>"}
              </ul>
          `;

      } else {

          let html = `<h4>👥 Equipos Registrados</h4>`;

          detalle.forEach(eq => {

              html += `
                  <div class="equipo-box">
                      <h5>🏆 ${eq.equipo}</h5>
                      <ul>
                          ${
                              eq.jugadores.length > 0
                              ? eq.jugadores.map(j => `<li>${j.nombre} (${j.email})</li>`).join("")
                              : "<li>Sin jugadores.</li>"
                          }
                      </ul>
                  </div>
              `;
          });

          bloque.innerHTML = html;
      }

        const modal = new bootstrap.Modal(document.getElementById('verTorneoModal'));
        modal.show();
    }
});
document.addEventListener("click", async (e) => {

  if (e.target.closest(".btn-editar")) {

    const card = e.target.closest(".torneo-card");
    const id = card.getAttribute("data-id");

    // Traer datos desde el backend
    const fd = new FormData();
    fd.append("id_torneo", id);

    const r = await fetch("http://localhost/Proyecto-programaci-n-web/vistas/obtener-torneo.php", {
      method: "POST",
      body: fd
    });

    const json = await r.json();
    const t = json.data;

    // Cargar campos
    document.getElementById("editarIdTorneo").value = id;
    document.getElementById("editarNombre").value = t.nombre_torneo;
    document.getElementById("editarJuego").value = t.juego;
    document.getElementById("editarTipo").value = t.tipo;
    document.getElementById("editarInicio").value = t.fecha_inicio;
    document.getElementById("editarFin").value = t.fecha_fin;

    let estadoValue = 1;
    if (t.estado === "Cerrado") estadoValue = 2;
    if (t.estado === "Bloqueado") estadoValue = 3;

    document.getElementById("editarEstado").value = estadoValue;

    new bootstrap.Modal("#editarTorneoModal").show();
  }
});
document.getElementById("formEditarTorneo").addEventListener("submit", async (e) => {
  e.preventDefault();

  const fd = new FormData();
  fd.append("id", document.getElementById("editarIdTorneo").value);
  fd.append("nombre", document.getElementById("editarNombre").value);
  fd.append("juego", document.getElementById("editarJuego").value);
  fd.append("tipo", document.getElementById("editarTipo").value);
  fd.append("inicio", document.getElementById("editarInicio").value);
  fd.append("fin", document.getElementById("editarFin").value);
  fd.append("estado", document.getElementById("editarEstado").value);

  const res = await fetch("http://localhost/Proyecto-programaci-n-web/vistas/editar-torneo.php", {
    method: "POST",
    body: fd
  });

  const json = await res.json();

  if (json.status === "ok") {
    location.reload(); // ❗ O puedo actualizar la card sin refrescar si querés
  }
});
document.addEventListener("click", async (e) => {

    const btn = e.target.closest(".btn-eliminar");
    if (!btn) return;

    const card = btn.closest(".torneo-card");
    const id = card.getAttribute("data-id");
    console.log("ID: ", id);

    if (!id) {
        console.error("❌ No existe data-id en la card");
        return;
    }

    if (!confirm("⚠️ ¿Seguro que quieres eliminar este torneo?")) {
        return;
    }

    const fd = new FormData();
    fd.append("id_torneo", id);

    const res = await fetch("http://localhost/Proyecto-programaci-n-web/vistas/eliminar-torneo.php", {
        method: "POST",
        body: fd
    });

    const json = await res.json();

    if (json.status === "ok") {
        card.remove();
        console.log("✔ Torneo eliminado");
    } else {
        alert("❌ Error al eliminar el torneo.");
        console.error(json.msg);
    }
});




});
