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
  const filterButtons = document.querySelectorAll('.filter-btn');
const rows = document.querySelectorAll('#rankingTable tbody tr');

filterButtons.forEach(button => {
  button.addEventListener('click', () => {
    // Quitar clase activa de todos
    filterButtons.forEach(btn => btn.classList.remove('active'));
    // Activar el botón actual
    button.classList.add('active');

    const filter = button.dataset.filter;

    rows.forEach(row => {
      if (filter === 'all') {
        row.style.display = '';
      } else {
        row.style.display = row.dataset.type === filter ? '' : 'none';
      }
    });
  });
});

});
