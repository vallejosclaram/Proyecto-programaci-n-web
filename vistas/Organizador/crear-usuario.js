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

  if (showPass) {
    showPass.addEventListener('change', () => {
      const type = showPass.checked ? 'text' : 'password';
      passwordInput.type = type;
      confirmInput.type = type;
    });
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Limpiar errores previos
    [fechaInput, passwordInput, confirmInput, emailInput].forEach(input =>
      input.classList.remove('is-invalid')
    );
    validFecha.classList.add('d-none');
    validPass.classList.add('d-none');
    validEmail.classList.add('d-none');

    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const usuario = document.getElementById('usuario').value.trim();
    const email = emailInput.value.trim();
    const fechaNacimiento = fechaInput.value;
    const password = passwordInput.value;
    const confirmPassword = confirmInput.value;

    let valido = true;

    // Validar edad mínima 13 años
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    const cumple = new Date(nacimiento.setFullYear(nacimiento.getFullYear() + 13));
    if (hoy < cumple) {
      fechaInput.classList.add('is-invalid');
      validFecha.classList.remove('d-none');
      valido = false;
    }

    // Validar contraseñas iguales
    if (password !== confirmPassword) {
      confirmInput.classList.add('is-invalid');
      validPass.classList.remove('d-none');
      valido = false;
    }

    // Validar email
    if (!emailInput.checkValidity()) {
      emailInput.classList.add('is-invalid');
      validEmail.classList.remove('d-none');
      valido = false;
    }

    // Validar campos vacíos
    if (!usuario || !nombre || !apellido || !email || !fechaNacimiento || !password || !confirmPassword) {
      form.classList.add('was-validated');
      valido = false;
    }

    if (!valido) return;

    // Preparar FormData
    const formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('apellido', apellido);
    formData.append('usuario', usuario);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('fechaNacimiento', fechaNacimiento);

    try {
      const res = await fetch("http://localhost/Proyecto-programaci-n-web/vistas/crear-organizador.php", {
        method: 'POST',
        body: formData,
        credentials: 'include'
      });

      const data = await res.json();
      console.log('Registro response:', data);

      if (!data.success) {
        alert(data.error || 'No se pudo registrar el organizador');
        return;
      }

      // Mostrar modal de éxito
      const modal = new bootstrap.Modal(document.getElementById('registroExitoso'));
      modal.show();

      // Redirigir automáticamente al dashboard del organizador después de 2 segundos
      /*setTimeout(() => {
        window.location.href = '../auth/login.php';
      }, 2000);*/

    } catch (err) {
      console.error('Error en la petición:', err);
      alert('Error en la conexión con el servidor');
    }

  });

});

