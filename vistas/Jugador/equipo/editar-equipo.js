document.addEventListener("DOMContentLoaded", () => {

    const menuToggle = document.getElementById('menuToggle');
    const closeBtn = document.getElementById('closeBtn');
    document.getElementById("formBuscarEquipos").addEventListener("submit", e => {
        e.preventDefault();
        cargarEquipos();
    });

    
 menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });
});



async function editarEquipo(){

    const response = await fetch("process-editar-equipo.php?id=" + id);
            const data = await response.json();

            document.getElementById("edit-id").value = data.id;
            document.getElementById("edit-nombre").value = data.nombre;
            document.getElementById("edit-descripcion").value = data.descripcion;
            document.getElementById("edit-juego").value = data.juego;

            modalEditar.show();
}

function cerrarModal() {
    document.getElementById("modal-cambiar-capitan").classList.remove("activo");
}
