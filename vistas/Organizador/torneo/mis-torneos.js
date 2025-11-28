document.addEventListener('DOMContentLoaded', () => {

const sidebar = document.getElementById('sidebar');
const menuToggle = document.getElementById('menuToggle');
const closeBtn = document.getElementById('closeBtn');
const body = document.body;
const form = document.getElementById('formTorneo');
const toastEl = document.getElementById('toastTorneo');
const toast = new bootstrap.Toast(toastEl);
const modalEl = document.getElementById('crearTorneoModal');
const modal = new bootstrap.Modal(modalEl);

menuToggle.addEventListener('click', () => {
  sidebar.classList.add('open');
  body.classList.add('menu-open');
});

closeBtn.addEventListener('click', () => {
  sidebar.classList.remove('open');
  body.classList.remove('menu-open');
});

 form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("http://localhost/Proyecto-programaci-n-web/vistas/procesar-torneo.php", {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(msg => {
      console.log(msg);
      toast.show();
      form.reset();
      modal.hide();
    })
    .catch(err => {
      console.error("Error:", err);
    });
  });


});
