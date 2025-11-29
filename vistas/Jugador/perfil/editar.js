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
      const btn = document.getElementById("btnVincular");
const input = document.getElementById("nicknameInput");
const msg = document.getElementById("vincularMsg");

btn.addEventListener("click", async (e) => {
    e.preventDefault();

    const nickname = input.value.trim();

    if (nickname.length < 3) {
        mostrarMsg("Nickname inválido");
        return;
    }

    // Buscar en Valorant
    const vRes = await fetch("http://localhost:4000/usuarios?nickname=" + nickname);
    const vData = await vRes.json();

    // Buscar en CS2
    const csRes = await fetch("http://localhost:4001/jugadores?nickname=" + nickname);
    const csData = await csRes.json();

    let cuentaID = null;

    if (vData.length > 0) {
        cuentaID = vData[0].puuid;       // Valorant
    } else if (csData.length > 0) {
        cuentaID = csData[0].steamId64;  // Counter Strike
    } else {
        mostrarMsg("❌ Cuenta no encontrada");
        return;
    }

    // Enviar a tu PHP
    const guardar = await fetch("guardarCuentaJuego.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cuenta_id: cuentaID })
    });

    const respuesta = await guardar.json();

    if (respuesta.success === true) {
        mostrarMsg("✅ Cuenta vinculada correctamente");
    } else {
        mostrarMsg("❌ Error: " + (respuesta.error || "desconocido"));
    }
});

 
   
});


