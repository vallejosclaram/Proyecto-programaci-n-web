window.onload = function() {
    let form = document.getElementById('loginForm');
    form.addEventListener('submit', validarLogin);
    let enviar = document.getElementById('enviar');
    enviar.addEventListener('click', function(e) {
        if(!validarLogin(e)){
            e.preventDefault();
        }else{
            enviar.innerHTML= '<a href="pantalla-principal.html" id="enviar"><button type="submit"  class="btn w-100 btn-login">Entrar</button></a>'
    }
    })
}

function validarLogin(event) {
    event.preventDefault(); 


    let email = document.getElementById('email');
    let validemail = document.getElementById('validemail');
    let password = document.getElementById('password');
    let validpass = document.getElementById('validpass');
    let flag = true;
    let regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let regexPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    // Validación email
    if (!regexEmail.test(email.value.trim())) {
        email.classList.add("is-invalid");
        validemail.classList.remove("d-none");
        flag = false;
    } else {
        email.classList.remove("is-invalid");
        validemail.classList.add("d-none");
        email.classList.add("is-valid");
    }       

    // Validación contraseña            
    if (password.value.trim().length < 8) {
        password.classList.add("is-invalid");
        validpass.classList.remove("d-none");
        flag = false;
    } else {
        if(!regexPass.test(password.value.trim())){
            password.classList.add("is-invalid");
            validpass.classList.remove("d-none");
            flag = false;
        
        }else{
            password.classList.remove("is-invalid");
            validpass.classList.add("d-none");
            password.classList.add("is-valid");
        }
    }

    if(flag){
        return true;
    }else{
        return false;
    }
}