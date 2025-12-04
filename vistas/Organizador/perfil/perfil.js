// perfil.js — lógica del sidebar y de la vista de perfil (seguimiento, contadores)
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.accordion-card').forEach(card => {
  const header = card.querySelector('.accordion-header');
  const body = card.querySelector('.accordion-body');

  header.addEventListener('click', () => {
      card.classList.toggle('open');
  });
});

  // Sidebar basic
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;
  if (menuToggle) menuToggle.addEventListener('click', () => { sidebar?.classList.add('open'); body.classList.add('menu-open'); });
  if (closeBtn) closeBtn.addEventListener('click', () => { sidebar?.classList.remove('open'); body.classList.remove('menu-open'); });

  // Perfil / Seguidores
  const perfilContainer = document.querySelector('.perfil-container');
  if (!perfilContainer) return; // nada más que hacer si no estamos en vista de perfil
  const perfilKey = perfilContainer.dataset.perfilKey || null; // e.g. 'organizador_3'
  const followBtn = document.getElementById('followBtn');
  const seguidoresEl = document.getElementById('v_seguidores');

  // Detectar seguidor actual desde localStorage (si existe)
  function getCurrentFollowerId() {
    try {
      const org = JSON.parse(localStorage.getItem('organizador') || 'null');
      if (org && (org.usuario || org.email || org.id)) return `organizador_${org.usuario || org.email || org.id}`;
    } catch(e){}
    try {
      const jug = JSON.parse(localStorage.getItem('jugador') || 'null');
      if (jug && (jug.usuario || jug.email || jug.id)) return `jugador_${jug.usuario || jug.email || jug.id}`;
    } catch(e){}
    // visitante no registrado — usamos 'guest' (local por navegador)
    return 'guest';
  }

  function getSeguidoresCount(perfilKey) {
    try {
      let count = 0;
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (!key || !key.startsWith('seguimientos_')) continue;
        try {
          const obj = JSON.parse(localStorage.getItem(key) || '{}');
          if (Array.isArray(obj.seguidos) && obj.seguidos.includes(perfilKey)) count++;
        } catch(e) { /* skip malformed */ }
      }
      return count;
    } catch(e) { return 0; }
  }

  function updateSeguidoresUI(perfilKey) {
    const c = getSeguidoresCount(perfilKey);
    if (seguidoresEl) seguidoresEl.textContent = String(c);
    document.querySelectorAll(`[data-follow-count-for="${CSS.escape(perfilKey)}"]`).forEach(n => n.textContent = String(c));
  }

  function updateFollowButtonText() {
    if (!followBtn || !perfilKey) return;
    const followerId = getCurrentFollowerId();
    const key = `seguimientos_${followerId}`;
    const obj = JSON.parse(localStorage.getItem(key) || '{"seguidos": []}');
    followBtn.textContent = (obj.seguidos && obj.seguidos.includes(perfilKey)) ? 'Siguiendo' : 'Seguir';
  }

  if (followBtn && perfilKey) {
    updateFollowButtonText();
    followBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const followerId = getCurrentFollowerId();
      const key = `seguimientos_${followerId}`;
      let obj = JSON.parse(localStorage.getItem(key) || '{"seguidos": []}');
      if (!obj.seguidos) obj.seguidos = [];
      const idx = obj.seguidos.indexOf(perfilKey);
      if (idx > -1) obj.seguidos.splice(idx, 1);
      else obj.seguidos.push(perfilKey);
      localStorage.setItem(key, JSON.stringify(obj));
      updateFollowButtonText();
      updateSeguidoresUI(perfilKey);
      localStorage.setItem('seguimientos_last_change', Date.now().toString());
    });
  }

  // Inicializar contador en la UI
  if (perfilKey) updateSeguidoresUI(perfilKey);

  // Escuchar cambios en storage para sincronizar entre pestañas
  window.addEventListener('storage', (e) => {
    if (!e) return;
    if (e.key && (e.key.startsWith('seguimientos_') || e.key === 'seguimientos_last_change')) {
      if (perfilKey) updateSeguidoresUI(perfilKey);
      updateFollowButtonText();
    }
  });
   
});
