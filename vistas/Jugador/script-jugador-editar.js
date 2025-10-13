window.onload = function() {
    let formEditar = document.getElementById('formEditar');
    formEditar.addEventListener('submit', validarFormEditar);

}


function validarFormEditar(e) {
    e.preventDefault();

    let usuario = document.getElementById('usuario');
    let validuser = document.getElementById('validuser');
    let email = document.getElementById('email');
    let validemail = document.getElementById('validemail');
    let fechaNacimiento = document.getElementById('fechaNacimiento');
    let validfecha = document.getElementById('validfecha');
    let pass1 = document.getElementById('pass1');
    let validpass1 = document.getElementById('validpass1');
    let validpass2 = document.getElementById('validpass2');
    let pass2 = document.getElementById('pass2');
    let fotoPerfil = document.getElementById('foto');
    let flag = true;
    let regexUser = /^[A-Za-z0-9]+$/;
    let regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let regexPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
   
   
    // Validación usuario
    if (!regexUser.test(usuario.value.trim())) {
        console.log("usuario no valido");
        usuario.classList.add("is-invalid");
        validuser.classList.remove("d-none");
        usuario.classList.remove("is-valid");
        flag = false;
    }else {
         console.log("valido");
        usuario.classList.remove("is-invalid");
        validuser.classList.add("d-none");
        usuario.classList.add("is-valid");
    }


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

    // Validación fecha de nacimiento   

    let hoy = new Date();
    let fechaIngresada = new Date(fechaNacimiento.value);
    let edad = hoy.getFullYear() - fechaIngresada.getFullYear();
    let mes = hoy.getMonth() - fechaIngresada.getMonth();
    let dia = hoy.getDate() - fechaIngresada.getDate();

    
    
    if(edad < 13){
        validfecha.classList.remove("d-none");
        fechaNacimiento.classList.add("is-invalid");
        console.log(edad);
        flag = false;
    }else{
        if (mes < 0 || (mes === 0 && dia < 0)) {
            edad--;
            validfecha.classList.remove("d-none");
            fechaNacimiento.classList.add("is-invalid");
            flag = false;
        }else{
            validfecha.classList.add("d-none");
            fechaNacimiento.classList.remove("is-invalid");
            fechaNacimiento.classList.add("is-valid");
        }
    }


    // Validación contraseñas

    if (pass1.value.trim().length < 8 || !regexPass.test(pass1.value.trim())) {
        validpass1.classList.remove("d-none");
        pass1.classList.add("is-invalid");
        flag = false;
    } else {
        validpass1.classList.add("d-none");
        document.getElementById('pass1').classList.remove("is-invalid");
        document.getElementById('pass1').classList.add("is-valid");
    }
    if (pass1.value.trim() !== pass2.value.trim()) {
        validpass2.classList.remove("d-none");
        document.getElementById('pass2').classList.add("is-invalid");
        flag = false;
    } else {
        validpass2.classList.add("d-none");
        pass2.classList.add("is-valid");
        pass2.classList.remove("is-invalid");
    }    

    // Validación modal
     if(flag){
        let modal = new bootstrap.Modal(document.getElementById("exampleModal"));
        modal.show();
        form.reset();
        let tag=document.getElementsByTagName("input");
        for(let i=0;i<tag.length;i++){
            tag[i].classList.remove("is-valid");
        }
    }
}   


function validarFormCrear(e) {
    e.preventDefault(); 

    let usuario = document.getElementById('usuario');
    let validuser = document.getElementById('validuser');
    let email = document.getElementById('email');
    let validemail = document.getElementById('validemail');
    let fechaNacimiento = document.getElementById('fechaNacimiento');
    let validfecha = document.getElementById('validfecha');
    let pass1 = document.getElementById('pass1');
    let validpass1 = document.getElementById('validpass1');
    let validpass2 = document.getElementById('validpass2');
    let pass2 = document.getElementById('pass2');

    let flag = true;
    let regexUser = /^[A-Za-z0-9]+$/;
    let regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let regexPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
   
   
    // Validación usuario
    if (!regexUser.test(usuario.value.trim())) {
        console.log("usuario no valido");
        usuario.classList.add("is-invalid");
        validuser.classList.remove("d-none");
        usuario.classList.remove("is-valid");
        flag = false;
    }else {
         console.log("valido");
        usuario.classList.remove("is-invalid");
        validuser.classList.add("d-none");
        usuario.classList.add("is-valid");
    }


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

    // Validación fecha de nacimiento   

    let hoy = new Date();
    let fechaIngresada = new Date(fechaNacimiento.value);
    let edad = hoy.getFullYear() - fechaIngresada.getFullYear();
    let mes = hoy.getMonth() - fechaIngresada.getMonth();
    let dia = hoy.getDate() - fechaIngresada.getDate();

    
    
    if(edad < 13){
        validfecha.classList.remove("d-none");
        fechaNacimiento.classList.add("is-invalid");
        console.log(edad);
        flag = false;
    }else{
        if (mes < 0 || (mes === 0 && dia < 0)) {
            edad--;
            validfecha.classList.remove("d-none");
            fechaNacimiento.classList.add("is-invalid");
            flag = false;
        }else{
            validfecha.classList.add("d-none");
            fechaNacimiento.classList.remove("is-invalid");
            fechaNacimiento.classList.add("is-valid");
        }
    }


    // Validación contraseñas

    if (pass1.value.trim().length < 8 || !regexPass.test(pass1.value.trim())) {
        validpass1.classList.remove("d-none");
        pass1.classList.add("is-invalid");
        flag = false;
    } else {
        validpass1.classList.add("d-none");
        document.getElementById('pass1').classList.remove("is-invalid");
        document.getElementById('pass1').classList.add("is-valid");
    }
    if (pass1.value.trim() !== pass2.value.trim()) {
        validpass2.classList.remove("d-none");
        document.getElementById('pass2').classList.add("is-invalid");
        flag = false;
    } else {
        validpass2.classList.add("d-none");
        pass2.classList.add("is-valid");
        pass2.classList.remove("is-invalid");
    }    

    // Validación modal
     if(flag){
        let modal = new bootstrap.Modal(document.getElementById("exampleModal"));
        modal.show();
        form.reset();
        let tag=document.getElementsByTagName("input");
        for(let i=0;i<tag.length;i++){
            tag[i].classList.remove("is-valid");
        }
    }
}