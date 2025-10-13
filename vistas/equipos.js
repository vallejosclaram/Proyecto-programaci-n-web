document.addEventListener("DOMContentLoaded", () => {

  const misEquiposDiv = document.getElementById("misEquipos");
  const otrosEquiposDiv = document.getElementById("otrosEquipos");

 
  const predefinidos = [
    {nombre: "Team Alpha", juego: "League of Legends", jugadores: 5, descripcion: "Competimos todos los fines de semana."},
    {nombre: "Valorant Kings", juego: "Valorant", jugadores: 5, descripcion: "Buscamos nuevo duelista."},
    {nombre: "CS Legends", juego: "CS2", jugadores: 4, descripcion: "Solo gente con experiencia."},
    {nombre: "LoL Titans", juego: "League of Legends", jugadores: 5, descripcion: "Divertirnos y rankear."},
    {nombre: "Valorant Ninjas", juego: "Valorant", jugadores: 5, descripcion: ""}
  ];


  function getMisEquipos() {
    return JSON.parse(localStorage.getItem("equipos")) || [];
  }

  function render() {
    misEquiposDiv.innerHTML = "";
    otrosEquiposDiv.innerHTML = "";

    const misEquipos = getMisEquipos();

    
    misEquipos.forEach(eq => {
      const html = `
        <div class="col-md-4">
          <div class="team-card">
            <h5>${eq.nombre}</h5>
            <p>${eq.juego}</p>
            <button class="btn btn-main ver-detalle"
              data-nombre="${eq.nombre}"
              data-juego="${eq.juego}"
              data-jugadores="${eq.jugadores}"
              data-descripcion="${eq.descripcion || 'Sin descripción'}">
              Ver Detalle
            </button>
          </div>
        </div>`;
      misEquiposDiv.insertAdjacentHTML("beforeend", html);
    });

    
    predefinidos.forEach(eq => {
      if(!misEquipos.find(e => e.nombre === eq.nombre)){
        const html = `
          <div class="col-md-4">
            <div class="team-card">
              <h5>${eq.nombre}</h5>
              <p>${eq.juego}</p>
              <button class="btn btn-main ver-detalle"
                data-nombre="${eq.nombre}"
                data-juego="${eq.juego}"
                data-jugadores="${eq.jugadores}"
                data-descripcion="${eq.descripcion || 'Sin descripción'}">
                Ver Detalle
              </button>
            </div>
          </div>`;
        otrosEquiposDiv.insertAdjacentHTML("beforeend", html);
      }
    });

    bindDetalle();
  }

  const modal = new bootstrap.Modal(document.getElementById("detalleEquipoModal"));
  const nombreEl = document.getElementById("modalNombre");
  const juegoEl = document.getElementById("modalJuego");
  const jugadoresEl = document.getElementById("modalJugadores");
  const descripcionEl = document.getElementById("modalDescripcion");
  const btnInscribirse = document.getElementById("btnInscribirse");

  function bindDetalle(){
    document.querySelectorAll(".ver-detalle").forEach(btn=>{
      btn.onclick = ()=>{
        nombreEl.textContent = btn.dataset.nombre;
        juegoEl.textContent  = btn.dataset.juego;
        jugadoresEl.textContent = btn.dataset.jugadores;
        descripcionEl.textContent = btn.dataset.descripcion;

        
        if(misEquiposDiv.contains(btn.parentElement.parentElement)){
          btnInscribirse.classList.add("d-none");
        } else {
          btnInscribirse.classList.remove("d-none");
          btnInscribirse.onclick = ()=>alert(`Inscripción enviada a: ${btn.dataset.nombre}`);
        }
        modal.show();
      };
    });
  }

 
  const buscarNombre = document.getElementById("buscarNombre");
  const buscarJuego = document.getElementById("buscarJuego");
  const btnBuscar = document.getElementById("btnBuscar");
  const btnLimpiar = document.getElementById("btnLimpiar");

  btnBuscar.onclick = ()=>{
    const nom = buscarNombre.value.toLowerCase();
    const juego = buscarJuego.value;
    const filtrados = [...getMisEquipos(), ...predefinidos].filter(e=>
      (!nom || e.nombre.toLowerCase().includes(nom)) &&
      (!juego || e.juego === juego)
    );
    misEquiposDiv.innerHTML = "";
    otrosEquiposDiv.innerHTML = "";
    filtrados.forEach(eq=>{
      const html = `
      <div class="col-md-4">
        <div class="team-card">
          <h5>${eq.nombre}</h5>
          <p>${eq.juego}</p>
          <button class="btn btn-main ver-detalle"
            data-nombre="${eq.nombre}"
            data-juego="${eq.juego}"
            data-jugadores="${eq.jugadores}"
            data-descripcion="${eq.descripcion || 'Sin descripción'}">
            Ver Detalle
          </button>
        </div>
      </div>`;
      if(getMisEquipos().find(e=>e.nombre===eq.nombre)){
        misEquiposDiv.insertAdjacentHTML("beforeend", html);
      } else {
        otrosEquiposDiv.insertAdjacentHTML("beforeend", html);
      }
    });
    bindDetalle();
  };

  btnLimpiar.onclick = ()=>{
    buscarNombre.value="";
    buscarJuego.value="";
    render();
  };

  render();
});
