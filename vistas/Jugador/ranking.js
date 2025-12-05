document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;

  // Menú lateral
  menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });
  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });

  const contEquipos = document.getElementById('contenedorEquipos');
  const contJugadores = document.getElementById('contenedorJugadores');
  const busquedaInput = document.getElementById('busquedaInput');
  const filtroJuego = document.getElementById('filtroJuego');

  function renderRanking(lista, contenedor) {
  contenedor.innerHTML = "";
  lista.forEach((item, i) => {
    const pos = i + 1;
    let clasePos = pos === 1 ? "rank-oro" :
                   pos === 2 ? "rank-plata" :
                   pos === 3 ? "rank-bronce" : "";

    const logo = item.imagen ?? item.logo ?? 'https://via.placeholder.com/60';
    const puntos = item.puntaje ?? item.puntos ?? 0;
    const juego = item.juego ?? "Sin juego";

    const card = document.createElement('div');
    card.className = 'ranking-card';

    card.innerHTML = `
      <div class="rank-pos ${clasePos}">#${pos}</div>
      <img src="${logo}" class="rank-logo" alt="${item.nombre}">
      <div class="rank-info">
        <div class="rank-nombre">${item.nombre}</div>
        <div class="rank-detalle"><i class="fa-solid fa-gamepad"></i> ${juego}</div>
      </div>
      <div class="rank-puntos">${puntos} pts</div>
    `;
    contenedor.appendChild(card);
  });
}

  // Función para pedir datos al backend
  function fetchList(tipo) {
    const params = new URLSearchParams();
    const juegoVal = filtroJuego ? filtroJuego.value : '';
    const nombreVal = busquedaInput ? busquedaInput.value.trim() : '';
    if (juegoVal) params.append('juego', juegoVal);
    if (nombreVal) params.append('nombre', nombreVal);
    params.append('tipo', tipo);
    fetch('http://localhost/Proyecto-programaci-n-web/Backend/ranking/list_rankables.php?' + params.toString())
      .then(r => r.json())
      .then(data => {
        const list = data.data || [];
        if (tipo === 'equipo') renderRanking(list, contEquipos);
        else renderRanking(list, contJugadores);
      })
      .catch(err => {
        console.error('Error fetching list:', err);
      });
  }

  // Mostrar por defecto equipos
  fetchList('equipo');

  // Tabs
  const tabEquipos = document.getElementById('tabEquipos');
  const tabJugadores = document.getElementById('tabJugadores');

  tabEquipos.addEventListener('click', () => {
    tabEquipos.classList.add('active');
    tabJugadores.classList.remove('active');
    contEquipos.classList.remove('d-none');
    contJugadores.classList.add('d-none');
    fetchList('equipo');
  });

  tabJugadores.addEventListener('click', () => {
    tabJugadores.classList.add('active');
    tabEquipos.classList.remove('active');
    contJugadores.classList.remove('d-none');
    contEquipos.classList.add('d-none');
    fetchList('jugador');
  });

  // 🔍 Filtrado dinámico
  function aplicarFiltros() {
    const activoEsJugadores = tabJugadores.classList.contains('active');
    fetchList(activoEsJugadores ? 'jugador' : 'equipo');
  }

  busquedaInput.addEventListener('input', aplicarFiltros);
  filtroJuego.addEventListener('change', aplicarFiltros);
});
