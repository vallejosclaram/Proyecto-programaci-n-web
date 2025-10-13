window.onload = function(){
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const showPass = document.getElementById('showPass');
    const validEmail = document.getElementById('validemail');
    const validPass = document.getElementById('validpass');

    // Mostrar contraseña
    showPass.addEventListener('change', ()=>{
        passwordInput.type = showPass.checked ? 'text' : 'password';
    });

    loginForm.addEventListener('submit', (e)=>{
        e.preventDefault();

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const usuarios = JSON.parse(localStorage.getItem('usuarios')) || [];
        const usuario = usuarios.find(u => u.email === email);

        if(!usuario){
            validEmail.classList.remove('d-none');
            emailInput.classList.add('is-invalid');
            return;
        } else {
            validEmail.classList.add('d-none');
            emailInput.classList.remove('is-invalid');
        }

        if(usuario.password !== password){
            validPass.classList.remove('d-none');
            passwordInput.classList.add('is-invalid');
            return;
        } else {
            validPass.classList.add('d-none');
            passwordInput.classList.remove('is-invalid');
        }

        alert("Inicio de sesión correcto!");
        loginForm.reset();
        showPass.checked = false;

        // Aquí iría la redirección a la vista principal
        // window.location.href = "vista-principal.html";
    });
}
