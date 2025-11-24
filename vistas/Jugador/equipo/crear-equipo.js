document.addEventListener('DOMContentLoaded', () => {
  const jugador = JSON.parse(localStorage.getItem('jugador')) || {};
  let selectJuegos = document.getElementById("juegoEquipo");
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;
  let btnCrear = document.getElementById("crearEquipo");

  menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });

 getJuegos();

  async function getJuegos(){
    

    try {
        const response = await fetch("get_juegos.php");
        const juegos = await response.json();

        selectJuegos.innerHTML = '<option value="">Seleccionar juego</option>';

        juegos.forEach(j => {
            const opt = document.createElement("option");
            opt.value = j.id_juego;     
            opt.textContent = j.nombre;  
            selectJuegos.appendChild(opt);
        });

    } catch (error) {
        console.error("Error cargando juegos:", error);
        selectJuegos.innerHTML = '<option value="">Error al cargar</option>';
    }

  }

 


  document.getElementById('crearEquipoForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const nombre = document.getElementById('nombreEquipo').value.trim();
    const cantidad = parseInt(document.getElementById('cantidadJugadores').value);
    const juego = document.getElementById('juegoEquipo').value;
    const descripcion = document.getElementById('descripcionEquipo').value.trim();

    if (!nombre) {
      alert('El nombre del equipo es obligatorio');
      return;
    }

    if (!juego) {
      alert('Debés seleccionar un juego');
      return;
    }

    if (isNaN(cantidad) || cantidad < 1 || cantidad > 10) {
      alert('La cantidad de jugadores debe estar entre 1 y 10');
      return;
    }

    const datos={
      nombre: nombre,
      cantidad: cantidad,
      juego: juego,
      descripcion: descripcion
    };
    
  
    try{

      const resp = await fetch('procesar-crear-equipo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      });

      const respuesta = await resp.json();
      console.log(respuesta);
      
      if(respuesta.mensaje){
        const modal = new bootstrap.Modal(document.getElementById('modalCreado'));
        modal.show();
      }

    }catch(error){
      
        console.error(error);
    }
     
     
    

    
  });
});