
const Utils = (() => {
  const readStore = (key, fallback) => {
    try { return JSON.parse(localStorage.getItem(key)) || fallback; } catch (e) { return fallback; }
  };

  const escapeHtml = (str) => {
    if (str === undefined || str === null) return '';
    return String(str)
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;');
  };

  const debounce = (fn, wait = 200) => {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
  };

  return { readStore, escapeHtml, debounce };
})();

// ==========================
// data.js - Datos de ejemplo y lectura de localStorage
// ==========================
const Data = (() => {
  const samplePlayers = [
    { usuario:'Player1', nombre:'Ariel', avatar:'https://source.unsplash.com/collection/542909/?portrait' },
    { usuario:'Player2', nombre:'Vanesa', avatar:'https://source.unsplash.com/collection/542909/?person' },
    { usuario:'Player3', nombre:'Luis', avatar:'https://source.unsplash.com/collection/542909/?face' },
    { usuario:'Player4', nombre:'Marc', avatar:'https://source.unsplash.com/collection/542909/?gamer' },
    { usuario:'Player5', nombre:'Sofía', avatar:'https://source.unsplash.com/collection/542909/?woman' },
  ];

  const sampleGames = [
    { id:'g1', title:'Quick Match - Valor', desc:'Partida rápida 5v5', img:'https://source.unsplash.com/collection/190727/800x600' },
    { id:'g2', title:'Ranked Duo', desc:'Subí en la tabla', img:'https://source.unsplash.com/collection/190728/800x600' },
    { id:'g3', title:'Custom Night', desc:'Juego por invitación', img:'https://source.unsplash.com/collection/190729/800x600' },
  ];

  const sampleTournaments = [
    { id:'t1', title:'Torneo Integración', date:'2025-11-03', prize:'$500', img:'https://source.unsplash.com/collection/888146/800x600' },
    { id:'t2', title:'Liga UPE', date:'2025-12-01', prize:'Trofeo', img:'https://source.unsplash.com/collection/190726/800x600' },
    { id:'t3', title:'Copa Semanal', date:'2025-10-30', prize:'Bonos', img:'https://source.unsplash.com/collection/190725/800x600' },
  ];

  const players = Utils.readStore('jugadores', samplePlayers);
  const games = Utils.readStore('partidas', sampleGames);
  const tournaments = Utils.readStore('torneos', sampleTournaments);

  return { players, games, tournaments };
})();

// ==========================
// cards.js - Creación de cards dinámicas
// ==========================
const Cards = (() => {
  const createPlayerCard = (p) => {
    const div = document.createElement('div');
    div.className = 'card-compact';
    div.innerHTML = `
      <div class="card-thumb"><img src="${p.avatar || 'https://via.placeholder.com/400x300?text=Jugador'}" alt="${Utils.escapeHtml(p.usuario)}"></div>
      <div>
        <p class="card-title">${Utils.escapeHtml(p.nombre || p.usuario)}</p>
        <div class="card-meta">@${Utils.escapeHtml(p.usuario)}</div>
      </div>
      <div class="card-actions">
        <button class="btn-ghost" data-action="ver-perfil" data-usuario="${Utils.escapeHtml(p.usuario)}">Ver perfil</button>
        <button class="btn-primary" data-action="seguir" data-usuario="${Utils.escapeHtml(p.usuario)}">Seguir</button>
      </div>
    `;
    return div;
  };

  const createGameCard = (g) => {
    const div = document.createElement('div');
    div.className = 'card-compact';
    div.innerHTML = `
      <div class="card-thumb"><img src="${g.img || 'https://via.placeholder.com/400x300?text=Juego'}" alt="${Utils.escapeHtml(g.title)}"></div>
      <div>
        <p class="card-title">${Utils.escapeHtml(g.title)}</p>
        <div class="card-meta">${Utils.escapeHtml(g.desc || '')}</div>
      </div>
      <div class="card-actions">
        <button class="btn-ghost" data-action="ver-game" data-id="${Utils.escapeHtml(g.id)}">Detalles</button>
        <button class="btn-primary" data-action="unirse" data-id="${Utils.escapeHtml(g.id)}">Unirse</button>
      </div>
    `;
    return div;
  };

  const createTournamentCard = (t) => {
    const div = document.createElement('div');
    div.className = 'card-compact';
    div.innerHTML = `
      <div class="card-thumb"><img src="${t.img || 'https://via.placeholder.com/400x300?text=Torneo'}" alt="${Utils.escapeHtml(t.title)}"></div>
      <div>
        <p class="card-title">${Utils.escapeHtml(t.title)}</p>
        <div class="card-meta">Fecha: ${Utils.escapeHtml(t.date)} • Premio: ${Utils.escapeHtml(t.prize || '—')}</div>
      </div>
      <div class="card-actions">
        <button class="btn-ghost" data-action="ver-torneo" data-id="${Utils.escapeHtml(t.id)}">Ver</button>
        <button class="btn-primary" data-action="inscribir" data-id="${Utils.escapeHtml(t.id)}">Inscribirme</button>
      </div>
    `;
    return div;
  };

  const renderList = (container, items, factory) => {
    if (!container) return;
    container.innerHTML = '';
    items.forEach(it => container.appendChild(factory(it)));
  };

  return { createPlayerCard, createGameCard, createTournamentCard, renderList };
})();

// ==========================
// search.js - Funcionalidad de búsqueda
// ==========================
const Search = (() => {
  const searchInput = document.getElementById('globalSearchInput');
  const searchScope = document.getElementById('searchScope');
  const clearSearchBtn = document.getElementById('clearSearchBtn');
  const searchCount = document.getElementById('searchCount');

  const playersList = document.getElementById('playersList');
  const gamesList = document.getElementById('gamesList');
  const tournamentsList = document.getElementById('tournamentsList');

  const origPlayers = Data.players.slice();
  const origGames = Data.games.slice();
  const origTournaments = Data.tournaments.slice();

  const clearHighlights = (container) => {
    if (!container) return;
    container.querySelectorAll('.card-compact').forEach(c => c.classList.remove('highlight'));
    const no = container.querySelector('.no-results');
    if (no) no.remove();
  };

  const showNoResults = (container, text='No se encontraron resultados') => {
    if (!container) return;
    clearHighlights(container);
    const msg = document.createElement('div');
    msg.className = 'no-results';
    msg.textContent = text;
    container.innerHTML = '';
    container.appendChild(msg);
  };

  const filterAndRender = (query, scope) => {
    query = (query || '').trim().toLowerCase();
    let total = 0;

    const matchPlayer = p => (p.usuario?.toLowerCase().includes(query) || p.nombre?.toLowerCase().includes(query));
    const matchGame = g => (g.title?.toLowerCase().includes(query) || g.desc?.toLowerCase().includes(query));
    const matchTournament = t => (t.title?.toLowerCase().includes(query) || t.date?.toLowerCase().includes(query));

    if (scope === 'all' || scope === 'players') {
      if (!query) { Cards.renderList(playersList, origPlayers, Cards.createPlayerCard); total += origPlayers.length; }
      else { const filtered = origPlayers.filter(matchPlayer); filtered.length ? Cards.renderList(playersList, filtered, Cards.createPlayerCard) : showNoResults(playersList); total += filtered.length; }
    }
    if (scope === 'all' || scope === 'games') {
      if (!query) { Cards.renderList(gamesList, origGames, Cards.createGameCard); total += origGames.length; }
      else { const filtered = origGames.filter(matchGame); filtered.length ? Cards.renderList(gamesList, filtered, Cards.createGameCard) : showNoResults(gamesList); total += filtered.length; }
    }
    if (scope === 'all' || scope === 'tournaments') {
      if (!query) { Cards.renderList(tournamentsList, origTournaments, Cards.createTournamentCard); total += origTournaments.length; }
      else { const filtered = origTournaments.filter(matchTournament); filtered.length ? Cards.renderList(tournamentsList, filtered, Cards.createTournamentCard) : showNoResults(tournamentsList); total += filtered.length; }
    }

    searchCount.textContent = `Resultados: ${total}`;
    [playersList, gamesList, tournamentsList].forEach(c => {
      if (!c) return;
      c.querySelectorAll('.card-compact').forEach((card, idx) => {
        card.classList.toggle('highlight', idx < 1 && query.length > 0);
      });
    });
  };

  const init = () => {
    const doSearch = Utils.debounce(() => {
      filterAndRender(searchInput.value, searchScope.value);
    }, 180);

    if (searchInput) searchInput.addEventListener('input', doSearch);
    if (searchScope) searchScope.addEventListener('change', doSearch);
    if (clearSearchBtn) clearSearchBtn.addEventListener('click', () => {
      searchInput.value = '';
      searchScope.value = 'all';
      filterAndRender('', 'all');
      searchInput.focus();
    });

    filterAndRender('', 'all'); // inicial render
  };

  return { init };
})();

// ==========================
// scroll.js - Scroll horizontal de cards
// ==========================
const Scroll = (() => {
  const init = () => {
    document.querySelectorAll('.scroll-controls button').forEach(btn => {
      btn.addEventListener('click', () => {
        const targetId = btn.dataset.target;
        const dir = Number(btn.dataset.dir || 1);
        const el = document.getElementById(targetId);
        if (!el) return;
        const card = el.querySelector('.card-compact');
        const step = (card ? card.offsetWidth : 240) + 12;
        el.scrollBy({ left: dir * step, behavior: 'smooth' });
      });
    });

    document.querySelectorAll('.h-scroll').forEach(el => {
      el.addEventListener('keydown', ev => {
        if (ev.key === 'ArrowRight') { el.scrollBy({ left: 260, behavior: 'smooth' }); ev.preventDefault(); }
        if (ev.key === 'ArrowLeft') { el.scrollBy({ left: -260, behavior: 'smooth' }); ev.preventDefault(); }
      });
    });
  };

  return { init };
})();

// ==========================
// sidebar.js - Manejo de sidebar, overlay y logout
// ==========================
const Sidebar = (() => {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const logoutBtn = document.getElementById('logoutBtn');
  const usernameLabel = document.getElementById('usuarioNombre');

  const openSidebar = () => {
    if (!sidebar || !overlay) return;
    sidebar.classList.add('open');
    overlay.classList.add('visible');
    sidebar.setAttribute('aria-hidden', 'false');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.classList.add('menu-open');
    sidebar.querySelector('.nav-item')?.focus();
  };

  const closeSidebar = () => {
    if (!sidebar || !overlay) return;
    sidebar.classList.remove('open');
    overlay.classList.remove('visible');
    sidebar.setAttribute('aria-hidden', 'true');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('menu-open');
    menuToggle?.focus();
  };

  const init = () => {
    menuToggle?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);
    logoutBtn?.addEventListener('click', () => {
      localStorage.removeItem('sessionUser');
      showModal('Sesión', 'Has cerrado sesión (simulado).');
      if (usernameLabel) usernameLabel.textContent = 'Hola, Jugador';
      closeSidebar();
    });
    document.addEventListener('keydown', ev => { if (ev.key === 'Escape' && sidebar?.classList.contains('open')) closeSidebar(); });
  };

  return { init };
})();

// ==========================
// modal.js - Helper para mostrar modales
// ==========================
const Modal = (() => {
  const modalEl = document.getElementById('modalInfo');
  const bsModal = modalEl ? new bootstrap.Modal(modalEl) : null;

  const showModal = (title, html) => {
    if (!modalEl) { alert(title + '\n' + html); return; }
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalBody').innerHTML = html;
    bsModal.show();
  };

  return { showModal };
})();

// ==========================
// actions.js - Delegated clicks en cards
// ==========================
const Actions = (() => {
  const init = () => {
    document.addEventListener('click', e => {
      const btn = e.target.closest('button');
      if (!btn) return;
      const action = btn.dataset.action;
      if (!action) return;

      const sessionUser = Utils.readStore('sessionUser', null);

      if (action === 'ver-perfil') {
        window.location.href = `perfil/ver.html?jugador=${encodeURIComponent(btn.dataset.usuario)}`;
      }
      if (action === 'seguir') {
        if (!sessionUser) { Modal.showModal('Inicia sesión', 'Debes iniciar sesión para seguir jugadores.'); return; }
        const key = `seguimientos_${sessionUser.usuario || sessionUser.email}`;
        const seg = Utils.readStore(key, { seguidos: [] });
        const idx = seg.seguidos?.indexOf(btn.dataset.usuario) ?? -1;
        if (idx > -1) seg.seguidos.splice(idx, 1); else (seg.seguidos = seg.seguidos || []).push(btn.dataset.usuario);
        localStorage.setItem(key, JSON.stringify(seg));
        Modal.showModal('Hecho', idx > -1 ? `Dejaste de seguir a ${btn.dataset.usuario}` : `Ahora seguís a ${btn.dataset.usuario}`);
      }
      if (action === 'ver-game') Modal.showModal('Detalles de la partida', `Detalles para partida <strong>${Utils.escapeHtml(btn.dataset.id)}</strong> (implementá la vista real).`);
      if (action === 'unirse') Modal.showModal('Unirse', `Te uniste a la partida <strong>${Utils.escapeHtml(btn.dataset.id)}</strong> (flujo simulado).`);
      if (action === 'ver-torneo') window.location.href = `torneo/torneo.html?id=${encodeURIComponent(btn.dataset.id)}`;
      if (action === 'inscribir') Modal.showModal('Inscripción', `Te inscribiste al torneo <strong>${Utils.escapeHtml(btn.dataset.id)}</strong>.`);
    });
  };

  return { init };
})();

// ==========================
// main.js - Inicialización al DOMContentLoaded
// ==========================
document.addEventListener('DOMContentLoaded', () => {
  Sidebar.init();
  Actions.init();
  Scroll.init();
  Search.init();

  // Mostrar saludo si hay sesión
  const usernameLabel = document.getElementById('usuarioNombre');
  const session = Utils.readStore('sessionUser', null);
  if (session && usernameLabel) usernameLabel.textContent = `Hola, ${session.usuario || session.nombre || 'Jugador'}`;
});
