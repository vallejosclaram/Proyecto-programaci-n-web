document.addEventListener("DOMContentLoaded", () => {
    

    const menuToggle = document.getElementById('menuToggle');
    const closeBtn = document.getElementById('closeBtn');
    

    
 menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });

  document.querySelectorAll(".scroll-controls button").forEach(btn => {
      btn.addEventListener("click", () => {
        const target = document.getElementById(btn.dataset.target);
        const dir = parseInt(btn.dataset.dir, 10);
        target.scrollBy({ left: 300 * dir, behavior: "smooth" });
      });
    });

});