document.addEventListener('DOMContentLoaded', () => {
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
  

  const busquedaInput = document.getElementById('busquedaGlobal');
  const filtroSelect = document.getElementById('filtroDenuncias');
  
  // Datos de ejemplo (reemplazá esto con tu fetch real)
  const denunciasUsuarios = [
    { id: 1, nombre: 'Usuario1', denuncias: 1, descripcion: 'Insultos en chat' },
    { id: 2, nombre: 'Usuario2', denuncias: 3, descripcion: 'Conducta antideportiva' },
    { id: 3, nombre: 'Usuario3', denuncias: 2, descripcion: 'Abandono de partida' }
  ];

  const denunciasTorneos = [
    { id: 1, torneo: 'Torneo Estelar', organizacion: 'Nebula Warriors', denuncias: 1, descripcion: 'Problemas de inscripción' },
    { id: 2, torneo: 'Liga Cósmica', organizacion: 'Galaxy Team', denuncias: 4, descripcion: 'Reglas inconsistentes' }
  ];

  function renderDenuncias() {
    const texto = busquedaInput.value.toLowerCase();
    const filtro = filtroSelect.value;

    const filtradoUsuarios = denunciasUsuarios.filter(d => {
      const coincideTexto = d.nombre.toLowerCase().includes(texto) || d.descripcion.toLowerCase().includes(texto);
      const coincideFiltro =
        filtro === 'todas' ||
        (filtro === '1' && d.denuncias === 1) ||
        (filtro === '2' && d.denuncias === 2) ||
        (filtro === '3+' && d.denuncias >= 3);
      return coincideTexto && coincideFiltro;
    });

    const filtradoTorneos = denunciasTorneos.filter(d => {
      const coincideTexto =
        d.torneo.toLowerCase().includes(texto) ||
        d.organizacion.toLowerCase().includes(texto) ||
        d.descripcion.toLowerCase().includes(texto);
      const coincideFiltro =
        filtro === 'todas' ||
        (filtro === '1' && d.denuncias === 1) ||
        (filtro === '2' && d.denuncias === 2) ||
        (filtro === '3+' && d.denuncias >= 3);
      return coincideTexto && coincideFiltro;
    });

    renderBloque(filtradoUsuarios, 'denunciasUsuariosContainer', 'usuario');
    renderBloque(filtradoTorneos, 'denunciasTorneosContainer', 'torneo');
  }

  function renderBloque(data, containerId, tipo) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';

    if (data.length === 0) {
      container.innerHTML = '<p class="vacio">No hay denuncias que coincidan.</p>';
      return;
    }

    data.forEach(item => {
      const card = document.createElement('div');
      card.classList.add('denuncia-card');

      if (tipo === 'usuario') {
        card.innerHTML = `
          <h3>${item.nombre}</h3>
          <p><strong>Denuncias:</strong> ${item.denuncias}</p>
          <p>${item.descripcion}</p>
        `;
      } else {
        card.innerHTML = `
          <h3>${item.torneo}</h3>
          <p><strong>Organización:</strong> ${item.organizacion}</p>
          <p><strong>Denuncias:</strong> ${item.denuncias}</p>
          <p>${item.descripcion}</p>
        `;
      }

      const acciones = document.createElement('div');
      acciones.classList.add('acciones');
      acciones.innerHTML = `
        <button class="bloquear">Bloquear</button>
        <button class="desbloquear">Desbloquear</button>
        <button class="notificar">Notificar</button>
      `;

      card.appendChild(acciones);
      container.appendChild(card);
    });
  }

  // Eventos
  busquedaInput.addEventListener('input', renderDenuncias);
  filtroSelect.addEventListener('change', renderDenuncias);

  // Render inicial
  renderDenuncias();
  
});

