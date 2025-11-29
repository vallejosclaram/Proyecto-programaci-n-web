document.addEventListener('DOMContentLoaded', () => {
  // ---------- helpers ----------
  const getCurrentUser = () => {
    try { return JSON.parse(localStorage.getItem('jugador')) || null; } catch(e){ return null; }
  };
  const isLogged = () => !!getCurrentUser();
  const goToLogin = () => { window.location.href = '../../inicio.html?alert=loginRequired'; };
  const goToRegister = () => { window.location.href = '../../inicio.html?alert=loginRequired'; };
  const saveToLocalArray = (key, item) => {
    const arr = JSON.parse(localStorage.getItem(key) || '[]');
    arr.push(item);
    localStorage.setItem(key, JSON.stringify(arr));
  };
  const nowDate = () => new Date().toISOString().split('T')[0];

  // ---------- DOM (según tu HTML) ----------
  const btnSeguir = document.getElementById('btnSeguir');
  const btnDenunciar = document.getElementById('btnDenunciar');
  const btnComentar = document.getElementById('btnComentar');
  const btnLogout = document.getElementById('btnLogout');

  const modalDenuncia = document.getElementById('modalDenuncia');
  const enviarDenuncia = document.getElementById('enviarDenuncia');
  const cerrarModalDenuncia = document.getElementById('cerrarModalDenuncia');
  const motivoInput = document.getElementById('motivo');
  const modalDenunciadoLabel = document.getElementById('modalDenunciado');

  const modalComentario = document.getElementById('modalComentario');
  const enviarComentario = document.getElementById('enviarComentario');
  const cancelarComentario = document.getElementById('cancelarComentario');
  const comentarioTexto = document.getElementById('comentarioTexto');

  const modalAuth = document.getElementById('modalAuth');
  const irRegistroBtn = document.getElementById('irRegistro');
  const irLoginBtn = document.getElementById('irLogin');
  const cerrarAuthBtn = document.getElementById('cerrarAuth');

  const comentariosList = document.getElementById('comentariosList');
  const editarBtn = document.getElementById('editarPerfilBtn');
  const perfilCardEl = document.getElementById('perfilCard');
  const tituloEl = document.getElementById('perfilTitulo');

  // ---------- obtener perfilUsuario (URL o usuario logueado) ----------
  const urlParams = new URLSearchParams(window.location.search);
  let perfilUsuario = urlParams.get('jugador');

  // si no viene por URL, usamos el usuario que está en sesión (localStorage 'jugador')
  const sessionUser = getCurrentUser();
  if (!perfilUsuario && sessionUser && sessionUser.usuario) {
    perfilUsuario = sessionUser.usuario;
  }
  // fallback
  perfilUsuario = perfilUsuario || 'JugadorEjemplo';

  // ---------- seguridad/escape ----------
  function escapeHtml(str) {
    if (str === undefined || str === null) return '';
    return String(str)
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;')
      .replace(/'/g,'&#039;');
  }

  // ---------- render perfil (datos ejemplo o del usuario en session) ----------
  const ejemploPerfil = {
    usuario: perfilUsuario,
    email: perfilUsuario + '@ejemplo.com',
    nombre: 'Nombre ' + perfilUsuario,
    apellido: 'Apellido',
    descripcion: 'Jugador activo. Perfil de ejemplo para probar denunciar, seguir y comentar.'
  };

  // si existe sessionUser y es el perfil actual, usamos esos datos para mostrar
  const datosMostrar = (sessionUser && sessionUser.usuario === perfilUsuario) ? {
    usuario: sessionUser.usuario,
    email: sessionUser.email || ejemploPerfil.email,
    nombre: sessionUser.nombre || ejemploPerfil.nombre,
    apellido: sessionUser.apellido || ejemploPerfil.apellido,
    descripcion: sessionUser.descripcion || ejemploPerfil.descripcion
  } : ejemploPerfil;

  const setText = (id, text) => { const el = document.getElementById(id); if (el) el.textContent = text; };
  setText('v_usuario', datosMostrar.usuario);
  setText('v_email', datosMostrar.email);
  setText('v_nombre', datosMostrar.nombre);
  setText('v_apellido', datosMostrar.apellido);
  setText('v_descripcion', datosMostrar.descripcion);
  if (tituloEl) tituloEl.textContent = `👤 ${datosMostrar.usuario}`;
  if (document.getElementById('perfilSubtitle')) document.getElementById('perfilSubtitle').textContent = `Perfil público de ${datosMostrar.usuario}`;

  // ---------- seguidores ----------
  function getSeguidoresCount(usuarioPerfil) {
    try {
      let count = 0;
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (!key || !key.startsWith('seguimientos_')) continue;
        try {
          const obj = JSON.parse(localStorage.getItem(key) || '{}');
          if (Array.isArray(obj.seguidos) && obj.seguidos.includes(usuarioPerfil)) count++;
        } catch(e) { /* skip malformed */ }
      }
      return count;
    } catch(e) { return 0; }
  }

  function updateSeguidoresUIFor(usuarioPerfil) {
    const elMain = document.getElementById('v_seguidores');
    if (elMain) elMain.textContent = String(getSeguidoresCount(usuarioPerfil));
    document.querySelectorAll(`.seguidores-count[data-usuario="${CSS.escape(usuarioPerfil)}"]`).forEach(n => {
      n.textContent = String(getSeguidoresCount(usuarioPerfil));
    });
    document.querySelectorAll(`[data-follow-count-for="${CSS.escape(usuarioPerfil)}"]`).forEach(n => {
      n.textContent = String(getSeguidoresCount(usuarioPerfil));
    });
  }

  function refreshAllFollowersCounters() {
    updateSeguidoresUIFor(perfilUsuario);
    document.querySelectorAll('.seguidores-count[data-usuario]').forEach(n => {
      const u = n.dataset.usuario; if (u) updateSeguidoresUIFor(u);
    });
    document.querySelectorAll('[data-follow-count-for]').forEach(n => {
      const u = n.getAttribute('data-follow-count-for'); if (u) updateSeguidoresUIFor(u);
    });
  }

  // ---------- mostrar botón editar  ----------
  const jugadorLogueado = getCurrentUser();
  const perfilEsPropio = Boolean(
    jugadorLogueado &&
    (
      (jugadorLogueado.usuario && String(jugadorLogueado.usuario) === String(perfilUsuario)) ||
      (jugadorLogueado.email && String(jugadorLogueado.email) === String(perfilUsuario)) ||
      (jugadorLogueado.id && String(jugadorLogueado.id) === String(perfilUsuario))
    )
  );

  if (perfilEsPropio) {
    if (editarBtn) {
      editarBtn.style.display = 'inline-block';
      editarBtn.addEventListener('click', () => {
        // guardamos datos temporales para editar (opcional)
        const datosParaEditar = {
          usuario: datosMostrar.usuario,
          email: datosMostrar.email,
          nombre: document.getElementById('v_nombre')?.textContent || datosMostrar.nombre,
          apellido: document.getElementById('v_apellido')?.textContent || datosMostrar.apellido,
          descripcion: document.getElementById('v_descripcion')?.textContent || datosMostrar.descripcion
        };
        localStorage.setItem('editar_jugador', JSON.stringify(datosParaEditar));
        // ir a la vista de edición (ajustá nombre de archivo si lo llamás distinto)
        window.location.href = 'editar.html';
      });
    }
    if (btnSeguir) btnSeguir.style.display = 'none';
    if (btnDenunciar) btnDenunciar.style.display = 'none';
    if (btnComentar) btnComentar.style.display = 'none';
  } else {
    if (editarBtn) editarBtn.style.display = 'none';
    if (btnSeguir) btnSeguir.style.display = 'inline-block';
    if (btnDenunciar) btnDenunciar.style.display = 'inline-block';
    if (btnComentar) btnComentar.style.display = 'inline-block';
  }

  // ---------- comentarios ----------
  const comentariosKey = `comentarios_${perfilUsuario}`;
  function renderComentarios() {
    if (!comentariosList) return;
    comentariosList.innerHTML = '';
    const arr = JSON.parse(localStorage.getItem(comentariosKey) || '[]');
    if (!arr.length) { comentariosList.innerHTML = '<p style="color:#aaa">Sin comentarios aún.</p>'; return; }
    arr.forEach(c => {
      const item = document.createElement('div');
      item.style.background = 'linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.01))';
      item.style.padding = '10px';
      item.style.borderRadius = '8px';
      item.style.marginBottom = '8px';
      const autorEsc = escapeHtml(c.autor);
      const autorLink = `<a href="ver.html?jugador=${encodeURIComponent(c.autor)}">${autorEsc}</a>`;
      item.innerHTML = `<strong style="color:#c77dff">${autorLink}</strong> <small style="color:#aaa"> - ${escapeHtml(c.fecha)}</small><div style="margin-top:6px;color:#f0f0f0">${escapeHtml(c.texto)}</div>`;
      comentariosList.appendChild(item);
    });
  }
  renderComentarios();

  function showAuthModal(){ if (modalAuth) modalAuth.style.display = 'block'; }
  function closeAuthModal(){ if (modalAuth) modalAuth.style.display = 'none'; }
  if (cerrarAuthBtn) cerrarAuthBtn.addEventListener('click', closeAuthModal);
  if (irLoginBtn) irLoginBtn.addEventListener('click', () => { closeAuthModal(); goToLogin(); });
  if (irRegistroBtn) irRegistroBtn.addEventListener('click', () => { closeAuthModal(); goToRegister(); });
  window.addEventListener('click', (e) => { if (e.target === modalAuth) closeAuthModal(); });

  function updateSeguirButtonText() {
    if (!btnSeguir) return;
    if (!isLogged()) { btnSeguir.textContent = 'Seguir'; return; }
    const usuarioActual = getCurrentUser() || {};
    const keyCheck = `seguimientos_${usuarioActual.usuario || usuarioActual.email}`;
    const segCheck = JSON.parse(localStorage.getItem(keyCheck) || '{"seguidos": []}');
    btnSeguir.textContent = (segCheck.seguidos && segCheck.seguidos.includes(perfilUsuario)) ? 'Siguiendo' : 'Seguir';
  }

  if (btnSeguir) {
    updateSeguirButtonText();
    btnSeguir.addEventListener('click', (e) => {
      e.preventDefault();
      if (!isLogged()) { showAuthModal(); return; }
      const usuarioActual = getCurrentUser();
      const key = `seguimientos_${usuarioActual.usuario || usuarioActual.email}`;
      let seg = JSON.parse(localStorage.getItem(key) || '{"seguidos": []}');
      if (!seg.seguidos) seg.seguidos = [];
      const idx = seg.seguidos.indexOf(perfilUsuario);
      if (idx > -1) seg.seguidos.splice(idx, 1);
      else seg.seguidos.push(perfilUsuario);
      localStorage.setItem(key, JSON.stringify(seg));
      updateSeguirButtonText();
      updateSeguidoresUIFor(perfilUsuario);
      localStorage.setItem('seguimientos_last_change', Date.now().toString());
    });
  }

  // ---------- denuncia ----------
  function openModalDenuncia(denunciadoUsuario = perfilUsuario) {
    if (!modalDenuncia) return;
    modalDenuncia.style.display = 'block';
    modalDenuncia.setAttribute('aria-hidden', 'false');
    if (modalDenunciadoLabel) modalDenunciadoLabel.textContent = denunciadoUsuario;
    if (motivoInput) { motivoInput.value = ''; motivoInput.focus(); }
  }
  function closeModalDenuncia() {
    if (!modalDenuncia) return;
    modalDenuncia.style.display = 'none';
    modalDenuncia.setAttribute('aria-hidden', 'true');
  }
  if (btnDenunciar) {
    btnDenunciar.addEventListener('click', (e) => {
      e.preventDefault();
      if (!isLogged()) { showAuthModal(); return; }
      openModalDenuncia(perfilUsuario);
    });
  }
  if (cerrarModalDenuncia) cerrarModalDenuncia.addEventListener('click', closeModalDenuncia);
  window.addEventListener('click', (e) => { if (e.target === modalDenuncia) closeModalDenuncia(); });

  if (enviarDenuncia) {
    enviarDenuncia.addEventListener('click', () => {
      if (!motivoInput) return;
      if (!isLogged()) { showAuthModal(); return; }
      const motivo = motivoInput.value.trim();
      if (!motivo) { alert('Ingresá un motivo para la denuncia'); motivoInput.focus(); return; }
      const usuarioActual = getCurrentUser();
      const denunciadoUsuario = modalDenunciadoLabel ? modalDenunciadoLabel.textContent : perfilUsuario;
      const denuncia = {
        denunciante: usuarioActual.usuario || usuarioActual.email || 'Anónimo',
        denunciado: denunciadoUsuario,
        motivo,
        fecha: nowDate(),
        estado: 'Pendiente'
      };
      saveToLocalArray('denuncias', denuncia);
      closeModalDenuncia();
      alert('Denuncia enviada correctamente. Gracias.');
      localStorage.setItem('denuncias_last_change', Date.now().toString());
    });
  }

  // ---------- comentarios ----------
  function openModalComentario() {
    if (!modalComentario) return;
    modalComentario.style.display = 'block';
    if (comentarioTexto) { comentarioTexto.value = ''; comentarioTexto.focus(); }
  }
  function closeModalComentario() {
    if (!modalComentario) return;
    modalComentario.style.display = 'none';
  }
  if (btnComentar) {
    btnComentar.addEventListener('click', (e) => {
      e.preventDefault();
      if (!isLogged()) { showAuthModal(); return; }
      openModalComentario();
    });
  }
  if (cancelarComentario) cancelarComentario.addEventListener('click', closeModalComentario);
  if (enviarComentario) {
    enviarComentario.addEventListener('click', () => {
      if (!comentarioTexto) return;
      if (!isLogged()) { showAuthModal(); return; }
      const texto = comentarioTexto.value.trim();
      if (!texto) return alert('Escribí un comentario antes de enviar.');
      const usuarioActual = getCurrentUser() || {};
      const nuevo = { autor: usuarioActual.usuario || usuarioActual.email, texto, fecha: nowDate() };
      const arr = JSON.parse(localStorage.getItem(comentariosKey) || '[]');
      arr.push(nuevo);
      localStorage.setItem(comentariosKey, JSON.stringify(arr));
      closeModalComentario();
      renderComentarios();
      localStorage.setItem(`comentarios_last_change_${perfilUsuario}`, Date.now().toString());
    });
  }

  document.addEventListener('click', (e) => {
    const target = e.target;
    if (!target) return;
    if (target.matches && target.matches('.btn-denunciar')) {
      const u = target.dataset.usuario;
      if (!u) return;
      if (!isLogged()) { showAuthModal(); return; }
      openModalDenuncia(u);
    }
    if (target.matches && target.matches('.btn-ver-perfil')) {
      const u = target.dataset.usuario;
      if (!u) return;
      window.location.href = `ver.html?jugador=${encodeURIComponent(u)}`;
    }
  });

  window.addEventListener('click', e => {
    if (e.target === modalAuth) closeAuthModal();
    if (e.target === modalComentario) closeModalComentario();
  });
  window.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeAuthModal(); closeModalDenuncia(); closeModalComentario(); }
  });
  
  if (btnLogout) {
    btnLogout.addEventListener('click', (e) => {
      e.preventDefault();
      // keys que usamos para sesión
      localStorage.removeItem('jugador');
      localStorage.removeItem('organizador');
      // opcional: limpiar otras marcas relacionadas
      localStorage.removeItem('seguimientos_last_change');
      // redirigir
      window.location.href = '../../auth/login.html';
    });
  }


  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;
  if (menuToggle) menuToggle.addEventListener('click', () => { sidebar?.classList.add('open'); body.classList.add('menu-open'); });
  if (closeBtn) closeBtn.addEventListener('click', () => { sidebar?.classList.remove('open'); body.classList.remove('menu-open'); });


  refreshAllFollowersCounters();
  updateSeguirButtonText();


  window.addEventListener('storage', (e) => {
    if (!e) return;
    if (e.key && (e.key.startsWith('seguimientos_') || e.key === 'seguimientos_last_change')) {
      refreshAllFollowersCounters();
      updateSeguirButtonText();
    }
    if (e.key && (e.key === `comentarios_last_change_${perfilUsuario}` || e.key === comentariosKey)) {
      renderComentarios();
    }
  });


  
}); 

