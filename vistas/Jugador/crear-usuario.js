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

  // Mostrar / ocultar contraseña
  showPass.addEventListener('change', () => {
   	passwordInput.type = showPass.checked ? 'text' : 'password';
   	confirmInput.type = showPass.checked ? 'text' : 'password';
  });

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    // Reset de clases
    [fechaInput, passwordInput, confirmInput, emailInput].forEach(input => {
      input.classList.remove('is-invalid');
    });
    validFecha.classList.add('d-none');
    validPass.classList.add('d-none');
    validEmail.classList.add('d-none');

    // Obtener valores
    const usuario = document.getElementById('usuario').value.trim();
    const email = emailInput.value.trim();
    const fechaNacimiento = fechaInput.value;
    const contrasena = passwordInput.value;
    const confirmPassword = confirmInput.value;

    let valido = true;

    // validación de edad
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    const cumple = new Date(nacimiento);
    cumple.setFullYear(cumple.getFullYear() + 13);

    if (hoy < cumple) {
      fechaInput.classList.add('is-invalid');
      validFecha.classList.remove('d-none');
      valido = false;
    }

    // Validación de contraseñas
    if (contrasena !== confirmPassword) {
      confirmInput.classList.add('is-invalid');
      validPass.classList.remove('d-none');
      valido = false;
    }

    // Validación de email HTML5
    if (!emailInput.checkValidity()) {
      emailInput.classList.add('is-invalid');
      validEmail.classList.remove('d-none');
      valido = false;
    }

    // Campos vacíos
    if (!usuario || !email || !fechaNacimiento || !contrasena || !confirmPassword) {
      form.classList.add('was-validated');
      valido = false;
    }

    if (!valido) return;

   
    const datos = {
      usuario: usuario,
      email: email,
      contrasena: contrasena,
      fechaNacimiento: fechaNacimiento,
      id_rol: 2   
    };

    
    localStorage.setItem('jugador', JSON.stringify(datos));

  try {
    
    const resp = await fetch('proces-crearjugador.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(datos)
    });
    const respuesta = await resp.json();

    const erroresDiv = document.getElementById('errores');

    if(respuesta.mensaje) {
          const modal = new bootstrap.Modal(document.getElementById('registroExitoso'));
          modal.show();
          form.reset();
    } else {
      
      erroresDiv.innerHTML = `<div class="alert alert-danger">${respuesta.error}</div>`;
      
    }
    
  } catch (error) {
    console.error('Error en la solicitud:');
  }
    
  });
});
