window.onload = function() {
    const formCrear = document.getElementById('formCrear');
    const showPass = document.getElementById('showPass');

    // Mostrar / Ocultar contraseña
    showPass.addEventListener('change', () => {
        const pass = document.getElementById('password');
        pass.type = showPass.checked ? 'text' : 'password';
    });

    // Validación en tiempo real
    document.getElementById('usuario').addEventListener('input', validarUsuario);
    document.getElementById('email').addEventListener('input', validarEmail);
    document.getElementById('fechaNacimiento').addEventListener('input', validarFecha);
    document.getElementById('password').addEventListener('input', validarPassword);
    document.getElementById('confirmPassword').addEventListener('input', validarConfirmPassword);

    formCrear.addEventListener('submit', registrarUsuario);
};

// Validaciones individuales
function validarUsuario(){
    const usuario = document.getElementById('usuario');
    const validuser = document.getElementById('validuser');
    const regexUser = /^[A-Za-z0-9_]+$/;
    if(!regexUser.test(usuario.value.trim())){
        usuario.classList.add('is-invalid');
        usuario.classList.remove('is-valid');
        validuser.style.display='block';
        return false;
    } else {
        usuario.classList.add('is-valid');
        usuario.classList.remove('is-invalid');
        validuser.style.display='none';
        return true;
    }
}

function validarEmail(){
    const email = document.getElementById('email');
    const validemail = document.getElementById('validemail');
    if(!email.value.includes('@')){
        email.classList.add('is-invalid');
        email.classList.remove('is-valid');
        validemail.style.display='block';
        return false;
    } else {
        email.classList.add('is-valid');
        email.classList.remove('is-invalid');
        validemail.style.display='none';
        return true;
    }
}

function validarFecha(){
    const fechaNacimiento = document.getElementById('fechaNacimiento');
    const validfecha = document.getElementById('validfecha');
    const hoy = new Date();
    const fecha = new Date(fechaNacimiento.value);
    let edad = hoy.getFullYear() - fecha.getFullYear();
    let m = hoy.getMonth() - fecha.getMonth();
    if(m < 0 || (m===0 && hoy.getDate()<fecha.getDate())) edad--;
    if(edad < 13){
        fechaNacimiento.classList.add('is-invalid');
        fechaNacimiento.classList.remove('is-valid');
        validfecha.style.display='block';
        return false;
    } else {
        fechaNacimiento.classList.add('is-valid');
        fechaNacimiento.classList.remove('is-invalid');
        validfecha.style.display='none';
        return true;
    }
}

function validarPassword(){
    const password = document.getElementById('password');
    const validpass1 = document.getElementById('validpass1');
    const regexPass = /^(?=.*[0-9]).{8,}$/;
    if(!regexPass.test(password.value.trim())){
        password.classList.add('is-invalid');
        password.classList.remove('is-valid');
        validpass1.style.display='block';
        return false;
    } else {
        password.classList.add('is-valid');
        password.classList.remove('is-invalid');
        validpass1.style.display='none';
        validarConfirmPassword(); // revalida confirm password
        return true;
    }
}

function validarConfirmPassword(){
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const validpass2 = document.getElementById('validpass2');
    if(confirmPassword.value.trim() !== password.value.trim()){
        confirmPassword.classList.add('is-invalid');
        confirmPassword.classList.remove('is-valid');
        validpass2.style.display='block';
        return false;
    } else {
        confirmPassword.classList.add('is-valid');
        confirmPassword.classList.remove('is-invalid');
        validpass2.style.display='none';
        return true;
    }
}

// Registro final
function registrarUsuario(e){
    e.preventDefault();

    // Verificar todas las validaciones
    if(!validarUsuario() || !validarEmail() || !validarFecha() || !validarPassword() || !validarConfirmPassword()){
        return; // si alguna falla, no registrar
    }

    // Guardar usuario en LocalStorage
    const jugadores = JSON.parse(localStorage.getItem('jugadores')) || [];
    jugadores.push({
        usuario: document.getElementById('usuario').value.trim(),
        email: document.getElementById('email').value.trim(),
        fechaNacimiento: document.getElementById('fechaNacimiento').value,
        password: document.getElementById('password').value.trim()
    });
    localStorage.setItem('jugadores', JSON.stringify(jugadores));

    // Mostrar modal de éxito
    const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
    modal.show();

    // Limpiar formulario
    document.getElementById('formCrear').reset();
    const inputs = document.querySelectorAll('#formCrear input');
    inputs.forEach(input => input.classList.remove('is-valid'));
}
