document.addEventListener('DOMContentLoaded', () => {
  const teams = [
    {
      id: 't1',
      name: 'Nebula Warriors',
      code: 'NBW',
      tournament: 'Copa Universitaria 2025',
      players: [
        { id: 'p1', name: 'Sofía Ruiz', role: 'Capitana' },
        { id: 'p2', name: 'María López', role: 'Support' },
        { id: 'p3', name: 'Valentina Cruz', role: 'DPS' }
      ]
    },
    {
      id: 't2',
      name: 'Estrellas Violeta',
      code: 'EVT',
      tournament: 'Liga Semestral',
      players: [
        { id: 'p4', name: 'Luna Martín', role: 'DPS' },
        { id: 'p5', name: 'Camila Pérez', role: 'Support' }
      ]
    },
    {
      id: 't3',
      name: 'Guardianas',
      code: 'GDN',
      tournament: 'Copa Universitaria 2025',
      players: [
        { id: 'p6', name: 'Irene Gómez', role: 'Tank' },
        { id: 'p7', name: 'Carla Méndez', role: 'Capitana' },
        { id: 'p8', name: 'Paula Díaz', role: 'DPS' }
      ]
    }
  ];

  const teamsGrid = document.getElementById('teamsGrid');
  const searchInput = document.getElementById('searchInput');
  const filterTorneo = document.getElementById('filterTorneo');
  const playersModal = document.getElementById('playersModal');
  const playersList = document.getElementById('playersList');
  const modalClose = document.getElementById('modalClose');
  const modalTitle = document.getElementById('modalTitle');


  const torneos = [...new Set(teams.map(t => t.tournament))];
  torneos.forEach(t => {
    const opt = document.createElement('option');
    opt.value = t;
    opt.textContent = t;
    filterTorneo.appendChild(opt);
  });

  function renderTeams(list) {
    teamsGrid.innerHTML = '';
    if (list.length === 0) {
      teamsGrid.innerHTML = `<div class="hero-card"><h2>No se encontraron equipos</h2><p class="text-muted">Probá otra búsqueda o filtro.</p></div>`;
      return;
    }

    list.forEach(team => {
      const card = document.createElement('article');
      card.className = 'team-card';
      card.innerHTML = `
        <div class="team-head">
          <div class="team-avatar" aria-hidden="true">${escapeInitials(team.name)}</div>
          <div class="team-info">
            <div class="team-name">${escapeHtml(team.name)}</div>
            <div class="team-meta">Código: ${escapeHtml(team.code)}</div>
          </div>
          <div class="tournament-badge" title="Torneo">${escapeHtml(team.tournament)}</div>
        </div>

        <div class="players-preview" aria-hidden="true">
          ${team.players.slice(0,4).map(p => `
            <div class="player-chip" title="${escapeHtml(p.name)}">
              <div class="player-avatar">${escapeInitials(p.name)}</div>
              <div class="player-name">${escapeHtml(shortName(p.name))}</div>
            </div>
          `).join('')}
          ${team.players.length > 4 ? `<div class="player-chip">+${team.players.length - 4} más</div>` : ''}
        </div>

        <div class="team-actions">
          <button class="btn-small" data-action="view" data-team="${team.id}">👀 Ver jugadores</button>
          <button class="btn-small btn-outline" data-action="message" data-team="${team.id}">✉️ Mensaje</button>
        </div>
      `;

      teamsGrid.appendChild(card);
    });
  }

 
  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'" :'&#39;'}[s]));
  }
  function escapeInitials(name) {
    const parts = String(name).split(' ');
    const initials = (parts[0] ? parts[0][0] : '') + (parts[1] ? parts[1][0] : '');
    return initials.toUpperCase();
  }
  function shortName(full){
    const parts = String(full).split(' ');
    return parts.slice(0,2).join(' ');
  }

 
  teamsGrid.addEventListener('click', (ev) => {
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const action = btn.dataset.action;
    const teamId = btn.dataset.team;
    const team = teams.find(t => t.id === teamId);
    if(!team) return;

    if(action === 'view') {
      openPlayersModal(team);
    } else if(action === 'message') {
      alert(`Abrir chat/DM con el equipo "${team.name}" (ejemplo).`);
    }
  });

  function openPlayersModal(team){
    playersList.innerHTML = '';
    modalTitle.textContent = `Jugadores — ${team.name}`;
    team.players.forEach(p => {
      const li = document.createElement('li');
      li.className = 'player-row';
      li.innerHTML = `
        <div class="player-avatar" style="width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
          ${escapeInitials(p.name)}
        </div>
        <div style="flex:1">
          <div style="font-weight:700">${escapeHtml(p.name)}</div>
          <div style="font-size:0.9rem;color:#bdb0d9">${escapeHtml(p.role)}</div>
        </div>
        <div style="display:flex;gap:0.5rem">
          <button class="btn-small btn-outline" data-player="${p.id}" data-team="${team.id}">Perfil</button>
          
        </div>
      `;
      playersList.appendChild(li);
    });

    playersModal.setAttribute('aria-hidden', 'false');
  }

  modalClose.addEventListener('click', () => {
    playersModal.setAttribute('aria-hidden', 'true');
  });

 
  document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape') playersModal.setAttribute('aria-hidden', 'true');
  });

  
  function applyFilters(){
    const q = searchInput.value.trim().toLowerCase();
    const torneo = filterTorneo.value;
    const filtered = teams.filter(t => {
      const inTorneo = torneo ? t.tournament === torneo : true;
      const matchesQ = !q || t.name.toLowerCase().includes(q) || t.code.toLowerCase().includes(q) || t.players.some(p => p.name.toLowerCase().includes(q));
      return inTorneo && matchesQ;
    });
    renderTeams(filtered);
  }

  searchInput.addEventListener('input', applyFilters);
  filterTorneo.addEventListener('change', applyFilters);


  renderTeams(teams);
});
