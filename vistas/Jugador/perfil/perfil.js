document.addEventListener('DOMContentLoaded', () => {

  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;

  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      sidebar?.classList.add('open');
      body.classList.add('menu-open');
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      sidebar?.classList.remove('open');
      body.classList.remove('menu-open');
    });
  }

  
  // ---------------------------

  const btn = document.getElementById("enviarComentario");
  const comentarioInput = document.getElementById("dejarcomentario");

  if (btn && comentarioInput) {
    btn.addEventListener("click", async (e) => {
      e.preventDefault();

      const comentario = comentarioInput.value.trim();
      const id_objetivo = new URLSearchParams(window.location.search).get("id");

      if (!comentario) {
        comentarioInput.innerHTML = "El comentario no puede estar vacío.";
        return;
      }

      const data = {
        comentario: comentario,
        id_objetivo: id_objetivo
      };

      try {
        const resp = await fetch("guardarcomentario.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data)
        });

        const result = await resp.json();

        if (result.ok) {
          comentarioInput.value = "";
          mostrarModalExito();
          cargarComentario();
        } else {
          alert("Error: " + result.error);
        }

      } catch (err) {
        console.error("Error enviando comentario:", err);
      }

    });

    async function cargarComentario() {
      const comentario = document.getElementById("comentarios");
      if (!comentario) return;

      const res = await fetch("datos-perfil.php");
      const data = await res.json();

      if (!data.comentario) {
        comentario.innerHTML = "<p>No hay comentarios disponibles</p>";
      } else {
        comentario.innerHTML = `<p>${data.comentario}</p>`;
      }
    }

    cargarComentario();
  }

  // ---------------------------
  //  MODAL DE ÉXITO
  // ---------------------------

  const modalExito = document.getElementById('modalExito');
  const cerrarModalExito = document.getElementById('cerrarModalExito');

  if (cerrarModalExito && modalExito) {
    cerrarModalExito.addEventListener("click", () => {
      modalExito.style.display = "none";
    });
  }

  function mostrarModalExito() {
    if (modalExito) modalExito.style.display = "flex";
  }

});
