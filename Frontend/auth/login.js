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

    
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    fetch('../../Backend/login/login.php', {
      method: 'POST',
      body: formData,
      credentials: 'include'
    }).then(r => r.json()).then(data => {
      if (!data || data.error || !data.success) {
        if (emailInput) emailInput.classList.add('is-invalid');
        if (passwordInput) passwordInput.classList.add('is-invalid');
        if (validEmail) validEmail.classList.remove('d-none');
        if (validPass) validPass.classList.remove('d-none');
        return;
      }

      
      const role = data.rol || 'usuario';
      const sessionObj = {
        id_usuario: data.id_usuario || null,
        id_organizador: data.id_organizador || null,
        rol: role,
        nombre: data.nombre || '',
        apellido: data.apellido || ''
      };
      try {
        if (role === 'organizador') localStorage.setItem('organizador', JSON.stringify(sessionObj));
        else localStorage.setItem('jugador', JSON.stringify(sessionObj));
      } catch (err) { console.warn('No se pudo guardar sesión en localStorage', err); }

      const mensaje = document.getElementById('mensajeBienvenida');
      if (mensaje) mensaje.textContent = `¡Hola ${sessionObj.nombre || 'Usuario'}! Has iniciado sesión como ${role}.`;

      const modalEl = document.getElementById('loginExitoso');
      if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        const btnIr = document.getElementById('irDashboard');
        if (btnIr) {
          btnIr.addEventListener('click', () => {
            if (role === 'organizador') {
              window.location.href = '../Organizador/dashboard.php';
            } else {
              window.location.href = '../Jugador/dashboard.php';
            }
          });
        }
      } else {
        if (role === 'organizador') window.location.href = '../Organizador/dashboard.php';
        else window.location.href = '../Jugador/dashboard.php';
      }
    }).catch(err => {
      console.error('Error al llamar login.php', err);
      if (emailInput) emailInput.classList.add('is-invalid');
      if (passwordInput) passwordInput.classList.add('is-invalid');
      if (validEmail) validEmail.classList.remove('d-none');
      if (validPass) validPass.classList.remove('d-none');
    });
  });
});


