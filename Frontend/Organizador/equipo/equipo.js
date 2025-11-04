document.addEventListener('DOMContentLoaded', () => {

const sidebar = document.getElementById('sidebar');
const menuToggle = document.getElementById('menuToggle');
const closeBtn = document.getElementById('closeBtn');
const body = document.body;
  const formEquipo = document.getElementById('formCrearEquipo');
  const toastEquipoEl = document.getElementById('toastEquipo');
  const toastEquipo = new bootstrap.Toast(toastEquipoEl);
  const modalEquipoEl = document.getElementById('crearEquipoModal');
  const modalEquipo = new bootstrap.Modal(modalEquipoEl);

  formEquipo.addEventListener('submit', function(e){
    e.preventDefault();

    // Aquí puedes agregar lógica para guardar equipo en localStorage o API
    // Ejemplo simple de agregar card dinámicamente
    const nombre = formEquipo.querySelector('input[type="text"]').value;
    const logo = formEquipo.querySelector('input[type="url"]').value;
    const juego = formEquipo.querySelector('select').value;

    const container = document.querySelector('.equipos-container');
    const card = document.createElement('div');
    card.className = 'equipo-card';
    card.innerHTML = `
      <img src="${logo}" alt="Logo del equipo">
      <h3>${nombre}</h3>
      <p><i class="fa-solid fa-gamepad"></i> ${juego}</p>
      <div class="card-actions">
        <button class="btn-ver"><i class="fa-solid fa-eye"></i></button>
        <button class="btn-editar"><i class="fa-solid fa-pen"></i></button>
        <button class="btn-eliminar"><i class="fa-solid fa-trash"></i></button>
      </div>
    `;
    container.appendChild(card);

    toastEquipo.show();
    formEquipo.reset();
    modalEquipo.hide();
  });

menuToggle.addEventListener('click', () => {
  sidebar.classList.add('open');
  body.classList.add('menu-open');
});

closeBtn.addEventListener('click', () => {
  sidebar.classList.remove('open');
  body.classList.remove('menu-open');
});

});
