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
  const paisSelect = document.getElementById('pais');

  // Cargar países

  getPais();

  async function getPais() {
    try {
        const response = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2');
        const paises = await response.json();

        paisSelect.innerHTML = '<option value="">Seleccionar país</option>';

        paises.forEach(pais => {
            const option = document.createElement("option");
            option.value = pais.cca2 || ""; 
            option.textContent = pais.name.common;
            paisSelect.appendChild(option);
        });

    } catch (error) {
        console.error("Error cargando paises:", error);
        paisSelect.innerHTML = '<option value="">Error al cargar</option>';
    }
}

  

  
  showPass.addEventListener('change', () => {
   	passwordInput.type = showPass.checked ? 'text' : 'password';
   	confirmInput.type = showPass.checked ? 'text' : 'password';
  });

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    
    [fechaInput, passwordInput, confirmInput, emailInput].forEach(input => {
      input.classList.remove('is-invalid');
    });
    validFecha.classList.add('d-none');
    validPass.classList.add('d-none');
    validEmail.classList.add('d-none');

    // Obtener valores
    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const email = emailInput.value.trim();
    const fechaNacimiento = fechaInput.value;
    const contrasena = passwordInput.value;
    const confirmPassword = confirmInput.value;
    const pais = paisSelect.value;

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
    if (!nombre || !apellido || !paisSelect || !email || !fechaNacimiento || !contrasena || !confirmPassword) {
      form.classList.add('was-validated');
      valido = false;
    }

    if (!valido) return;

   
    const datos = {
      nombre: nombre,
      apellido: apellido,
      email: email,
      contrasena: contrasena,
      fechaNacimiento: fechaNacimiento,
      pais: pais,
      id_rol: 2  
    };
     console.log(datos);
    
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
    console.error('Error en la solicitud:', error);
  }
    
  });
});
