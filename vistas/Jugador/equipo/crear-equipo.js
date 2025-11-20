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

  btnCrear.addEventListener('submit', crear);
  async function crear(){
    let nombre = document.getElementById("nombreEquipo");
     
        const response = await fetch("get_juegos.php");
        const juegos = await response.json();

  }


  document.getElementById('crearEquipoForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const nombre = document.getElementById('nombreEquipo').value.trim();
    const cantidad = parseInt(document.getElementById('cantidadJugadores').value);
    const juego = document.getElementById('juegoEquipo').value.trim();
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

    const nuevoEquipo = {
      id: Date.now().toString(),
      nombre,
      juego,
      descripcion,
      cantidad,
      capitan: jugador.usuario,
      miembros: [jugador.usuario],
      solicitudes: []
    };

    const equipos = JSON.parse(localStorage.getItem('equipos')) || [];
    equipos.push(nuevoEquipo);
    localStorage.setItem('equipos', JSON.stringify(equipos));

    const modal = new bootstrap.Modal(document.getElementById('modalCreado'));
    modal.show();
  });
});