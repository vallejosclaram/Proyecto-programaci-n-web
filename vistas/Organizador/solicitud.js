// solicitudes.js - lógica para visualizar-solicitudes.html
document.addEventListener('DOMContentLoaded', ()=>{
  // Datos de ejemplo (en producción vendrían del backend)
  const SAMPLE = [
    {id:1, username:'ProGamer123', nombre:'Lucas Gomez', juego:'CS2', mensaje:'Quiero unirme al equipo como rifler', estado:'pending', fecha:'2025-09-20'},
    {id:2, username:'NamiQueen', nombre:'María López', juego:'League of Legends', mensaje:'Soy support con experiencia', estado:'pending', fecha:'2025-09-22'},
    {id:3, username:'AceKiller', nombre:'Pedro Ruiz', juego:'Valorant', mensaje:'Top frag en rankeds', estado:'approved', fecha:'2025-09-10'}
  ];

  const STORAGE_KEY = 'upe_solicitudes_v1';
  function loadData(){
    const raw = localStorage.getItem(STORAGE_KEY);
    if(raw) return JSON.parse(raw);
    localStorage.setItem(STORAGE_KEY, JSON.stringify(SAMPLE));
    return SAMPLE;
  }
  function saveData(data){ localStorage.setItem(STORAGE_KEY, JSON.stringify(data)); }

  let solicitudes = loadData();
  let actionTarget = null;

  const listado = document.getElementById('listado-solicitudes');
  const empty = document.getElementById('empty');
  const search = document.getElementById('search');
  const filterStatus = document.getElementById('filter-status');
  const clearBtn = document.getElementById('clear-btn');

  function render(){
    const q = search.value.trim().toLowerCase();
    const filter = filterStatus.value;
    const items = solicitudes.filter(s=>{
      if(filter!== 'all' && s.estado !== filter) return false;
      if(!q) return true;
      return [s.username, s.nombre, s.juego, s.mensaje].join(' ').toLowerCase().includes(q);
    });

    listado.innerHTML = '';
    if(items.length===0){ empty.classList.remove('d-none'); return; } else { empty.classList.add('d-none'); }

    items.forEach(s=>{
      const div = document.createElement('div');
      div.className = 'solicitud-card bg-light d-flex align-items-center justify-content-between';
      div.innerHTML = `
        <div class="d-flex align-items-center gap-3">
          <div class="avatar">${s.username.slice(0,2).toUpperCase()}</div>
          <div>
            <div><strong>${s.nombre}</strong> <small class="text-muted">(@${s.username})</small></div>
            <div class="meta"><small>${s.juego} • ${s.fecha}</small></div>
            <div class="mt-1">${s.mensaje}</div>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <div><span class="badge bg-secondary badge-status">${estadoLabel(s.estado)}</span></div>
          <div>
            <button class="btn btn-sm btn-approve action-btn" data-id="${s.id}" data-action="approve" ${s.estado==='approved'?'disabled':''}>Aprobar</button>
            <button class="btn btn-sm btn-reject action-btn" data-id="${s.id}" data-action="reject" ${s.estado==='rejected'?'disabled':''}>Rechazar</button>
          </div>
        </div>
      `;
      listado.appendChild(div);
    });
  }

  function estadoLabel(e){
    if(e==='pending') return 'Pendiente';
    if(e==='approved') return 'Aprobada';
    if(e==='rejected') return 'Rechazada';
    return e;
  }

  document.addEventListener('click', (ev)=>{
    const btn = ev.target.closest('.action-btn');
    if(!btn) return;
    const id = Number(btn.dataset.id);
    const action = btn.dataset.action;
    actionTarget = {id, action};
    const texto = action === 'approve' ? '¿Aprobar esta solicitud?' : '¿Rechazar esta solicitud?';
    document.getElementById('confirm-text').textContent = texto;
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
  });

  document.getElementById('confirm-yes').addEventListener('click', ()=>{
    if(!actionTarget) return;
    const idx = solicitudes.findIndex(s=>s.id === actionTarget.id);
    if(idx === -1) return;
    solicitudes[idx].estado = actionTarget.action === 'approve' ? 'approved' : 'rejected';
    saveData(solicitudes);
    render();
    showToast(actionTarget.action === 'approve' ? 'Solicitud aprobada' : 'Solicitud rechazada');
    const modalEl = document.getElementById('confirmModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
    actionTarget = null;
  });

  search.addEventListener('input', render);
  filterStatus.addEventListener('change', render);
  clearBtn.addEventListener('click', ()=>{ search.value=''; filterStatus.value='all'; render(); });

  function showToast(msg){
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-dark border-0';
    toast.role = 'status';
    toast.style.position = 'fixed';
    toast.style.right = '20px';
    toast.style.bottom = '20px';
    toast.style.zIndex = 9999;
    toast.innerHTML = `<div class="d-flex"><div class="toast-body">${msg}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"></button></div>`;
    document.body.appendChild(toast);
    const bs = new bootstrap.Toast(toast, {delay:2500});
    bs.show();
    toast.querySelector('.btn-close').addEventListener('click', ()=>{ bs.hide(); });
    toast.addEventListener('hidden.bs.toast', ()=> toast.remove());
  }

  render();
});