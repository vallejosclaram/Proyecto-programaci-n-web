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

    

        const res = await fetch("datos-perfil.php");
        const data = await res.json();

  if (!data.error) {
  document.getElementById("email").value = data.email;
  document.getElementById("nombre").value = data.nombre;
  document.getElementById("apellido").value = data.apellido;
  document.getElementById("descripcion").value = data.descripcion;
}




document.getElementById("editarPerfilForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(e.target);

  const res = await fetch("process-editar.php", {
    method: "POST",
    body: formData
  });

  const data = await res.json();

  if (data.ok) {
    const modal = new bootstrap.Modal(document.getElementById("modalGuardado"));
    modal.show();
  } else {
    console.log(data.error || "Error al actualizar");
  }
});


 }); 