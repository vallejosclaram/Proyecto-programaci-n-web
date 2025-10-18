document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('adminLoginForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const user = document.getElementById('adminUser').value.trim();
        const pass = document.getElementById('adminPass').value.trim();

        if (user === "admin" && pass === "admin123") {
            localStorage.setItem("isAdminLogged", "true");
            window.location.href = "principal-admin.html";
        } else {
            alert("Usuario o contraseña incorrectos");
        }
    });
});
