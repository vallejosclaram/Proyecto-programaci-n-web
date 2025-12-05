document.addEventListener('DOMContentLoaded', () => {

  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;
   const btn = document.getElementById("enviarComentario");
  const comentarioInput = document.getElementById("dejarcomentario");
  const comentariosDiv = document.getElementById("comentarios");

    const id_objetivo = new URLSearchParams(window.location.search).get("id");

    

    

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
          cargarComentarios();
        } else {
          alert("Error: " + result.error);
        }

      } catch (err) {
        console.error("Error enviando comentario:", err);
      }

    });
   
  }

  cargarComentarios()

    async function cargarComentarios() {
      
        const res = await fetch("datos-perfil.php?id=" + id_objetivo);
        const data = await res.json();

      
        if (!data.comentarios || data.comentarios.length === 0) {
          
            comentariosDiv.innerHTML = "<p>No hay comentarios disponibles</p>";
            return;
        }

       comentariosDiv.innerHTML = data.comentarios
        .map(c => `
            <div class="comentario-item">
            <p><strong>${c.autor}</strong></p>
            <p>— ${c.comentario}</p>
            <hr>
            </div>
        `)
        .join("");
           
    }

    cargarComentarios();
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
