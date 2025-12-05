document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const showPass = document.getElementById('showPass');
  const validEmail = document.getElementById('validemail');
  const validPass = document.getElementById('validpass');

 
  if (showPass) {
    showPass.addEventListener('change', () => {
      if (passwordInput) passwordInput.type = showPass.checked ? 'text' : 'password';
    });
  }

  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (emailInput) emailInput.classList.remove('is-invalid');
    if (passwordInput) passwordInput.classList.remove('is-invalid');
    if (validEmail) validEmail.classList.add('d-none');
    if (validPass) validPass.classList.add('d-none');

    const email = (emailInput && emailInput.value || '').trim();
    const password = (passwordInput && passwordInput.value) || '';

    const jugador = (() => {
      try { return JSON.parse(localStorage.getItem('jugador')) || null; } catch(e) { return null; }
    })();
    const organizador = (() => {
      try { return JSON.parse(localStorage.getItem('organizador')) || null; } catch(e) { return null; }
    })();

    let usuario = null;

    if (jugador && jugador.email === email && jugador.password === password) {
      usuario = jugador;
    } else if (organizador && organizador.email === email && organizador.password === password) {
      usuario = organizador;
    }

    if (!usuario) {
      if (emailInput) emailInput.classList.add('is-invalid');
      if (passwordInput) passwordInput.classList.add('is-invalid');
      if (validEmail) validEmail.classList.remove('d-none');
      if (validPass) validPass.classList.remove('d-none');
      return;
    }


    try {
      localStorage.setItem('jugador', JSON.stringify(usuario));
    } catch (err) {
      console.warn('No se pudo guardar la sesión en localStorage:', err);
    }

    const mensaje = document.getElementById('mensajeBienvenida');
    if (mensaje) mensaje.textContent = `¡Hola ${usuario.usuario}! Has iniciado sesión como ${usuario.rol}.`;

    const modalEl = document.getElementById('loginExitoso');
    if (modalEl) {
      const modal = new bootstrap.Modal(modalEl);
      modal.show();

      const btnIr = document.getElementById('irDashboard');
      if (btnIr) {
        btnIr.addEventListener('click', () => {

          if (usuario.rol === 'organizador') {
            window.location.href = '../organizador/dashboard.html';
          } else {
           
            window.location.href = '../jugador/dashboard.html';
      
          }
        });
      }
    } else {
      
      if (usuario.rol === 'organizador') {
        window.location.href = '../organizador/dashboard.html';
      } else {
        window.location.href = '../jugador/dashboard.html';
      }
    }
  });
});


