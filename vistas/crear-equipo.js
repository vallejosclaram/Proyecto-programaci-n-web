document.getElementById('form-crear-equipo').addEventListener('submit', function (e) {
  e.preventDefault();

  const nombre      = document.getElementById('nombreEquipo');
  const juego       = document.getElementById('juego');      
  const jugadores   = document.getElementById('jugadores');
  const descripcion = document.getElementById('descripcion');

  let valido = true;

  
  if (nombre.value.trim().length < 3) {
    nombre.classList.add('is-invalid'); valido = false;
  } else nombre.classList.remove('is-invalid');

  if (!juego.value) {
    juego.classList.add('is-invalid'); valido = false;
  } else juego.classList.remove('is-invalid');

  const num = parseInt(jugadores.value, 10);
  if (isNaN(num) || num < 1 || num > 20) {          
    jugadores.classList.add('is-invalid'); valido = false;
  } else jugadores.classList.remove('is-invalid');

  if (!valido) return;

 
  const equipo = {
    nombre: nombre.value.trim(),
    juego: juego.value,
    jugadores: num,
    descripcion: descripcion.value.trim() || ''
  };

 
  const lista = JSON.parse(localStorage.getItem('equipos')) || [];
  lista.push(equipo);
  localStorage.setItem('equipos', JSON.stringify(lista));

  
  nombre.value = '';
  juego.value = '';
  jugadores.value = '';
  descripcion.value = '';

  
  new bootstrap.Modal(document.getElementById('modalCreado')).show();
});
