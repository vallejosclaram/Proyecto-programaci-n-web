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
  const erroresDiv = document.getElementById('errores');

  // Cargar países
  cargarPaises();

  async function cargarPaises() {
    try {
      const res = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2');
      const paises = await res.json();
      paisSelect.innerHTML = '<option value="">Seleccionar país</option>';
      paises.forEach(pais => {
        const option = document.createElement('option');
        option.value = pais.cca2 || '';
        option.textContent = pais.name.common;
        paisSelect.appendChild(option);
      });
    } catch (err) {
      paisSelect.innerHTML = '<option value="">Error al cargar países</option>';
      console.error(err);
    }
  }

  // Mostrar/ocultar contraseña
  showPass.addEventListener('change', () => {
    const tipo = showPass.checked ? 'text' : 'password';
    passwordInput.type = tipo;
    confirmInput.type = tipo;
  });

  // Enviar formulario
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Reset validaciones
    [fechaInput, passwordInput, confirmInput, emailInput].forEach(input => input.classList.remove('is-invalid'));
    [validFecha, validPass, validEmail].forEach(el => el.classList.add('d-none'));
    erroresDiv.innerHTML = '';

    // Valores
    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const email = emailInput.value.trim();
    const fechaNacimiento = fechaInput.value;
    const contrasena = passwordInput.value;
    const confirmPassword = confirmInput.value;
    const pais = paisSelect.value;

    let valido = true;

    // Edad mínima 13 años
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    const cumple13 = new Date(nacimiento);
    cumple13.setFullYear(cumple13.getFullYear() + 13);
    if (hoy < cumple13) {
      fechaInput.classList.add('is-invalid');
      validFecha.classList.remove('d-none');
      valido = false;
    }

    // Contraseñas coinciden
    if (contrasena !== confirmPassword) {
      confirmInput.classList.add('is-invalid');
      validPass.classList.remove('d-none');
      valido = false;
    }

    // Email válido
    if (!emailInput.checkValidity()) {
      emailInput.classList.add('is-invalid');
      validEmail.classList.remove('d-none');
      valido = false;
    }

    // Campos obligatorios
    if (!nombre || !apellido || !pais || !email || !fechaNacimiento || !contrasena || !confirmPassword) {
      valido = false;
    }

    if (!valido) return;

    // Preparar datos
    const datos = { nombre, apellido, email, contrasena, fechaNacimiento, pais };

    try {
      const res = await fetch('http://localhost/Proyecto-programaci-n-web/Backend/jugador/crear-jugador.php', { // PHP que te preparé antes
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      });
      const data = await res.json();

      if (data.success) {
        // Mostrar modal éxito
        const modal = new bootstrap.Modal(document.getElementById('registroExitoso'));
        modal.show();
        form.reset();
      } else {
        erroresDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
      }
    } catch (err) {
      console.error(err);
      erroresDiv.innerHTML = `<div class="alert alert-danger">Error en la conexión con el servidor</div>`;
    }
  });
});


