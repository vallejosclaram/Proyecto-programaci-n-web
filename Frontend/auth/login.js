document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const showPass = document.getElementById('showPass');
  const validEmail = document.getElementById('validemail');
  const validPass = document.getElementById('validpass');

  // proteger por si no existen en el HTML
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

    // --- GUARDAR SESIÓN / usuario logueado en localStorage ---
    // guardamos el objeto 'usuario' como la sesión actual bajo la clave 'jugador'
    // (esto permite que perfil.js detecte quién está logueado)
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
          // Si querés redirigir directo al perfil del jugador en vez del dashboard, sustituí la URL
          if (usuario.rol === 'organizador') {
            window.location.href = '../organizador/dashboard.html';
          } else {
            // ejemplo: redirigir al dashboard del jugador
            window.location.href = '../jugador/dashboard.html';
            // Si preferís ir directamente al perfil:
            // window.location.href = `../jugador/ver.html?jugador=${encodeURIComponent(usuario.usuario)}`;
          }
        });
      }
    } else {
      // fallback: navegar según rol
      if (usuario.rol === 'organizador') {
        window.location.href = '../organizador/dashboard.html';
      } else {
        window.location.href = '../jugador/dashboard.html';
      }
    }
  });
});
