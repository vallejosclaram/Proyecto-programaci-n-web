document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const overlay = document.getElementById('sidebarOverlay');
  const body = document.body;

  menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    overlay.classList.add('visible');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('visible');
    body.classList.remove('menu-open');
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('visible');
    body.classList.remove('menu-open');
  });

  // === Actividad reciente (simulada o luego con PHP) ===
  const actividad = [
    { icon: 'fa-trophy', texto: 'Se creó el torneo "Copa UPE 2025".' },
    { icon: 'fa-users', texto: 'El equipo "Los Titanes" se registró exitosamente.' },
    { icon: 'fa-bell', texto: 'Nueva solicitud de unión al torneo "Liga Norte".' },
    { icon: 'fa-ranking-star', texto: 'Actualizado ranking semanal.' },
    { icon: 'fa-envelope', texto: 'Mensaje recibido de un capitán de equipo.' }
  ];

  const lista = document.getElementById('actividadLista');

  actividad.forEach(item => {
    const li = document.createElement('li');
    li.className = 'actividad-item';
    li.innerHTML = `<i class="fa-solid ${item.icon}"></i><span>${item.texto}</span>`;
    lista.appendChild(li);
  });
});