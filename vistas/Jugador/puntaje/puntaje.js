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

    


  const selTorneo = document.getElementById('selectTorneo');
  const btnGuardar = document.getElementById('btnGuardarResultados');
  const filas = document.querySelectorAll('#tablaRondas tbody tr');
  const nombreSpans = document.querySelectorAll('.nombre-torneo');

  // nuevos elementos para evidencia
  const inputEvidencia = document.getElementById('fileEvidencia');
  const btnSubirEvidencia = document.getElementById('btnSubirEvidencia');

  let estado = {
    torneo_id: null,
    id_partida: null,
    deshabilitar: true,
    id_reporte_15: null, // lo obtendremos al guardar resultados
  };

  function setDisabledResultados(disabled) {
    filas.forEach(tr => {
      const chk = tr.querySelector('.check-resultado');
      if (chk) {
        chk.checked = false;
        chk.disabled = disabled;
      }
    });
    btnGuardar.disabled = disabled;
  }

  function setDisabledEvidencia(disabled) {
    if (inputEvidencia) inputEvidencia.disabled = disabled;
    if (btnSubirEvidencia) btnSubirEvidencia.disabled = disabled;
  }

  async function fetchPartida(torneo_id) {
    const url = `api/get_partida.php?torneo_id=${encodeURIComponent(torneo_id)}`;
    const resp = await fetch(url);
    const data = await resp.json();
    return data;
  }

  selTorneo.addEventListener('change', async (e) => {
    const torneo_id = e.target.value ? parseInt(e.target.value, 10) : null;
    estado.torneo_id = torneo_id;
    estado.id_partida = null;
    estado.id_reporte_15 = null;

    // Mostrar nombre del torneo
    const nombre = selTorneo.options[selTorneo.selectedIndex]?.text || '';
    nombreSpans.forEach(sp => sp.textContent = nombre);

    setDisabledResultados(true);
    setDisabledEvidencia(true);

    if (!torneo_id) return;

    try {
      const info = await fetchPartida(torneo_id);
      if (info.error) {
        alert(info.error);
        return;
      }

      estado.id_partida = info.id_partida;
      estado.deshabilitar = !!info.deshabilitar;

      // Resultados: habilitar si corresponde
      setDisabledResultados(estado.deshabilitar ? true : false);

      // Evidencia: permitir subir si hay al menos algún reporte (A o B)
      const hayAlguno = (info.existentes?.a > 0) || (info.existentes?.b > 0);
      setDisabledEvidencia(!hayAlguno);
    } catch (err) {
      console.error(err);
      alert('Error al obtener la partida del torneo');
    }
  });

  btnGuardar.addEventListener('click', async () => {
    if (!estado.torneo_id || !estado.id_partida) {
      alert('Seleccioná un torneo antes de guardar.');
      return;
    }
    const resultados = [];
    filas.forEach(tr => {
      const chk = tr.querySelector('.check-resultado');
      resultados.push(chk && chk.checked ? 1 : 0);
    });

    try {
      const resp = await fetch('api/guardar_resultados.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
          torneo_id: estado.torneo_id,
          id_partida: estado.id_partida,
          resultados
        })
      });
      const data = await resp.json();
      if (data.ok) {
        alert('Resultados guardados correctamente');
        setDisabledResultados(true);
        // habilitar evidencia y guardar id_reporte_15 para asociar la captura
        estado.id_reporte_15 = data.id_reporte_15 || null;
        setDisabledEvidencia(false);
      } else {
        alert(data.error || 'Error al guardar resultados');
      }
    } catch (err) {
      console.error(err);
      alert('Error de red al guardar resultados');
    }
  });

  btnSubirEvidencia.addEventListener('click', async () => {
    if (!estado.torneo_id || !estado.id_partida) {
      alert('Seleccioná un torneo.');
      return;
    }
    const file = inputEvidencia.files?.[0];
    if (!file) {
      alert('Seleccioná una imagen de evidencia (JPG/PNG/WEBP).');
      return;
    }

    const fd = new FormData();
    fd.append('torneo_id', String(estado.torneo_id));
    fd.append('id_partida', String(estado.id_partida));
    // si tenemos el id_reporte_15, lo mandamos; si no, el backend tomará el último
    if (estado.id_reporte_15) fd.append('id_reporte', String(estado.id_reporte_15));
    fd.append('evidencia', file);

    try {
      const resp = await fetch('api/subir_evidencia.php', { method: 'POST', body: fd });
      const data = await resp.json();
      if (data.ok) {
        alert('Evidencia subida correctamente');
        // opcional: limpiar input
        inputEvidencia.value = '';
      } else {
        alert(data.error || 'Error al subir evidencia');
      }
    } catch (err) {
      console.error(err);
      alert('Error de red al subir evidencia');
    }
  });

});
