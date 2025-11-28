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
        <div class="torneo-card">

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

});
