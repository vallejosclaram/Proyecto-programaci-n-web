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

  // Simulamos solicitudes
  const solicitudes = [
    { nombre: "DaraJ", tipo: "Quiere unirse al equipo Fénix", img: "https://via.placeholder.com/60", fecha: "2025-10-25" },
    { nombre: "MauroKiller", tipo: "Quiere unirse a tu torneo Valorant Masters", img: "https://via.placeholder.com/60", fecha: "2025-10-28" },
    { nombre: "AxelPro", tipo: "Invitación para unirse a Dark Wolves", img: "https://via.placeholder.com/60", fecha: "2025-10-29" },
  ];

  const contenedor = document.getElementById('solicitudesContainer');

  solicitudes.forEach((solicitud) => {
    const card = document.createElement('div');
    card.classList.add('solicitud-card');

    card.innerHTML = `
      <div class="solicitud-info">
        <img src="${solicitud.img}" alt="${solicitud.nombre}">
        <div class="solicitud-detalle">
          <div class="solicitud-nombre">${solicitud.nombre}</div>
          <div class="solicitud-tipo">${solicitud.tipo}</div>
          <small class="text-muted">${solicitud.fecha}</small>
        </div>
      </div>
      <div class="solicitud-acciones">
        <button class="btn-accion btn-aceptar">Aceptar</button>
        <button class="btn-accion btn-rechazar">Rechazar</button>
      </div>
    `;

    // Acciones
    card.querySelector('.btn-aceptar').addEventListener('click', () => {
      card.style.borderColor = '#5ce65c';
      card.style.opacity = '0.6';
      card.querySelector('.solicitud-tipo').textContent = "Solicitud aceptada ✅";
    });

    card.querySelector('.btn-rechazar').addEventListener('click', () => {
      card.style.borderColor = '#ff5050';
      card.style.opacity = '0.6';
      card.querySelector('.solicitud-tipo').textContent = "Solicitud rechazada ❌";
    });

    contenedor.appendChild(card);
  });
});

