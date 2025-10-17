document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const showPass = document.getElementById('showPass');
  const validEmail = document.getElementById('validemail');
  const validPass = document.getElementById('validpass');

  showPass.addEventListener('change', () => {
    passwordInput.type = showPass.checked ? 'text' : 'password';
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    emailInput.classList.remove('is-invalid');
    passwordInput.classList.remove('is-invalid');
    validEmail.classList.add('d-none');
    validPass.classList.add('d-none');

    const email = emailInput.value.trim();
    const password = passwordInput.value;

    const jugador = JSON.parse(localStorage.getItem('jugador'));
    const organizador = JSON.parse(localStorage.getItem('organizador'));

    let usuario = null;

    if (jugador && jugador.email === email && jugador.password === password) {
      usuario = jugador;
    } else if (organizador && organizador.email === email && organizador.password === password) {
      usuario = organizador;
    }

    if (!usuario) {
      emailInput.classList.add('is-invalid');
      passwordInput.classList.add('is-invalid');
      validEmail.classList.remove('d-none');
      validPass.classList.remove('d-none');
      return;
    }

    const mensaje = document.getElementById('mensajeBienvenida');
    mensaje.textContent = `¡Hola ${usuario.usuario}! Has iniciado sesión como ${usuario.rol}.`;

    const modal = new bootstrap.Modal(document.getElementById('loginExitoso'));
    modal.show();

    document.getElementById('irDashboard').addEventListener('click', () => {
    if (usuario.rol === 'organizador') {
        window.location.href = '../organizador/dashboard.html';
    } else {
        window.location.href = '../jugador/dashboard.html';
    }
    });

});
});
