document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formCrearJugador');
  const showPass = document.getElementById('showPass');
  const passwordInput = document.getElementById('password');
  const confirmInput = document.getElementById('confirmPassword');
  const fechaInput = document.getElementById('fechaNacimiento');
  const validFecha = document.getElementById('validfecha');
  const validPass = document.getElementById('validpass');
  const emailInput = document.getElementById('email');
const validEmail = document.getElementById('validemail');

  
  showPass.addEventListener('change', () => {
    passwordInput.type = showPass.checked ? 'text' : 'password';
    confirmInput.type = showPass.checked ? 'text' : 'password';
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    
    [fechaInput, passwordInput, confirmInput].forEach(input => {
      input.classList.remove('is-invalid');
    });
    validFecha.classList.add('d-none');
    validPass.classList.add('d-none');

    const usuario = document.getElementById('usuario').value.trim();
    const email = document.getElementById('email').value.trim();
    const fechaNacimiento = fechaInput.value;
    const password = passwordInput.value;
    const confirmPassword = confirmInput.value;

    let valido = true;

    
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    const edad = hoy.getFullYear() - nacimiento.getFullYear();
    const cumple = new Date(nacimiento.setFullYear(nacimiento.getFullYear() + 13));
    const esMayor = hoy >= cumple;

    if (!esMayor) {
      fechaInput.classList.add('is-invalid');
      validFecha.classList.remove('d-none');
      valido = false;
    }

    if (password !== confirmPassword) {
      confirmInput.classList.add('is-invalid');
      validPass.classList.remove('d-none');
      valido = false;
    }
    if (!emailInput.checkValidity()) {
        emailInput.classList.add('is-invalid');
        validEmail.classList.remove('d-none');
        } else {
        emailInput.classList.remove('is-invalid');
        validEmail.classList.add('d-none');
        }

    if (!usuario || !email || !fechaNacimiento || !password || !confirmPassword) {
      form.classList.add('was-validated');
      valido = false;
    }

    if (!valido) return;

    // Enviar datos al backend para crear el organizador
    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('email', email);
    formData.append('password', password);

    fetch('../../Backend/organizador/crear_organizador.php', {
      method: 'POST',
      body: formData,
      credentials: 'include'
    })
      .then(r => r.json())
      .then(data => {
        if (data && data.success) {
          // redirigir al login del frontend
          window.location.href = '../auth/login.php?registered=1';
        } else {
          const message = (data && data.error) ? data.error : 'Error desconocido';
          alert('No se pudo registrar: ' + message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Error en la petición al servidor');
      });
  });
});
