document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      // 🔑 credentials: 'include' permite que se guarde la sesión en PHP
      const res = await fetch("http://localhost/Proyecto-programaci-n-web/vistas/login.php", { 
        method: 'POST', 
        body: formData,
        credentials: 'include'
      });

      const data = await res.json();

      console.log('LOGIN response:', data); // útil para depuración

      if (!data.success) {
        alert(data.error || 'Error en login');
        return;
      }

      // Redirigir según rol
      const rol = data.rol;

      if (rol === 'organizador') {
        window.location.href = '../Organizador/dashboard.php';
      } else {
        window.location.href = '../Jugador/dashboard.php';
      }

    } catch (err) {
      console.error('Fetch/Login error:', err);
      alert('Error en la conexión con el servidor');
    }
  });
});
