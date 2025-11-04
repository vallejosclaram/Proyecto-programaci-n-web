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

  // Datos ejemplo
  const equipos = [
    { nombre: "Equipo Fénix", juego: "Valorant", puntos: 150, logo: "https://via.placeholder.com/60" },
    { nombre: "Dark Wolves", juego: "League of Legends", puntos: 120, logo: "https://via.placeholder.com/60" },
    { nombre: "Neon Titans", juego: "FIFA", puntos: 100, logo: "https://via.placeholder.com/60" },
  ];

  const jugadores = [
    { nombre: "AxelPro", juego: "Valorant", puntos: 300, logo: "https://via.placeholder.com/60" },
    { nombre: "DaraJ", juego: "League of Legends", puntos: 250, logo: "https://via.placeholder.com/60" },
    { nombre: "MauroKiller", juego: "FIFA", puntos: 200, logo: "https://via.placeholder.com/60" },
  ];

  const contEquipos = document.getElementById('contenedorEquipos');
  const contJugadores = document.getElementById('contenedorJugadores');
  const busquedaInput = document.getElementById('busquedaInput');
  const filtroJuego = document.getElementById('filtroJuego');

  function renderRanking(lista, contenedor) {
    contenedor.innerHTML = "";
    lista.forEach((item, i) => {
      const pos = i + 1;
      let clasePos = "";
      if (pos === 1) clasePos = "rank-oro";
      else if (pos === 2) clasePos = "rank-plata";
      else if (pos === 3) clasePos = "rank-bronce";

      const card = document.createElement('div');
      card.className = 'ranking-card';
      card.innerHTML = `
        <div class="rank-pos ${clasePos}">#${pos}</div>
        <img src="${item.logo}" class="rank-logo" alt="${item.nombre}">
        <div class="rank-info">
          <div class="rank-nombre">${item.nombre}</div>
          <div class="rank-detalle"><i class="fa-solid fa-gamepad"></i> ${item.juego}</div>
        </div>
        <div class="rank-puntos">${item.puntos} pts</div>
      `;
      contenedor.appendChild(card);
    });
  }

  // Mostrar por defecto
  renderRanking(equipos, contEquipos);
  renderRanking(jugadores, contJugadores);

  // Tabs
  const tabEquipos = document.getElementById('tabEquipos');
  const tabJugadores = document.getElementById('tabJugadores');

  tabEquipos.addEventListener('click', () => {
    tabEquipos.classList.add('active');
    tabJugadores.classList.remove('active');
    contEquipos.classList.remove('d-none');
    contJugadores.classList.add('d-none');
  });

  tabJugadores.addEventListener('click', () => {
    tabJugadores.classList.add('active');
    tabEquipos.classList.remove('active');
    contJugadores.classList.remove('d-none');
    contEquipos.classList.add('d-none');
  });

  // 🔍 Filtrado dinámico
  function aplicarFiltros() {
    const texto = busquedaInput.value.toLowerCase();
    const juego = filtroJuego.value;

    // Detectar pestaña activa
    const activoEsJugadores = tabJugadores.classList.contains('active');
    const data = activoEsJugadores ? jugadores : equipos;
    const contenedor = activoEsJugadores ? contJugadores : contEquipos;

    const filtrados = data
      .filter(i => i.nombre.toLowerCase().includes(texto))
      .filter(i => juego === "" || i.juego === juego);

    renderRanking(filtrados, contenedor);
  }

  busquedaInput.addEventListener('input', aplicarFiltros);
  filtroJuego.addEventListener('change', aplicarFiltros);
});
