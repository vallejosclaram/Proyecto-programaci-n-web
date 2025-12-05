document.addEventListener('DOMContentLoaded', async () => {
 const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;


  menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });

document.getElementById("comprobante").addEventListener("change", function() {
    if (this.files[0].size > 10 * 1024 * 1024) { // 10 MB
        alert("El archivo es demasiado grande.");
        this.value = "";
    }
});

document.querySelector("#formMembresia").addEventListener("submit", async (e) => {
    e.preventDefault();

    const tipo = document.querySelector("#tipo").value;
    const archivo = document.querySelector("#comprobante").files[0];

    if (!archivo) {
        alert("Tenés que subir un comprobante.");
        return;
    }

    const formData = new FormData();
    formData.append("id_membresia", tipo);
    formData.append("comprobante", archivo);

    const resp = await fetch("guardar_solicitud.php", {
        method: "POST",
        body: formData
    });

    const data = await resp.json();

    if (data.ok) {
        alert("Solicitud enviada correctamente.");
    } else {
        alert("Error: " + data.error);
    }
});


});