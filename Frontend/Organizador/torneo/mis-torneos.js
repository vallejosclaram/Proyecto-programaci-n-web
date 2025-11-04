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

  const form = document.getElementById('formCrearTorneo');
  const toastEl = document.getElementById('toastTorneo');
  const toast = new bootstrap.Toast(toastEl);
  const modalEl = document.getElementById('crearTorneoModal');
  const modal = new bootstrap.Modal(modalEl);

form.addEventListener('submit', function(e){
    e.preventDefault();
    toast.show();
    form.reset();
    modal.hide(); // <--- correcto aquí
});
});
