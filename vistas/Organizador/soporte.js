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
 
 
 document.getElementById('formSoporte').addEventListener('submit', function(e){
      e.preventDefault();
      const nombre = document.getElementById('nombre').value;
      const email = document.getElementById('email').value;
      const asunto = document.getElementById('asunto').value;
      const mensaje = document.getElementById('mensaje').value;

      // Guardar en localStorage (simulación)
      let quejas = JSON.parse(localStorage.getItem('soporte')) || [];
      quejas.push({ nombre, email, asunto, mensaje, fecha: new Date().toLocaleString() });
      localStorage.setItem('soporte', JSON.stringify(quejas));

      alert('Tu mensaje ha sido enviado. ¡Gracias!');
      this.reset();
    });

}); 
