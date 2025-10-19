// torneos.js - lógica para visualizar-torneos.html
document.addEventListener('DOMContentLoaded', ()=>{
    const SAMPLE = [
        {id:1, nombre:'Copa UPE - CS2', juego:'CS2', lugar:'Sede Central', fecha:'2025-10-12', estado:'upcoming', equipos:16, descripcion:'Torneo por invitación.'},
        {id:2, nombre:'Liga Diciembre - LoL', juego:'League of Legends', lugar:'Online', fecha:'2025-12-05', estado:'active', equipos:32, descripcion:'Liga semanal con premios.'},
        {id:3, nombre:'Open Verano - Valorant', juego:'Valorant', lugar:'Polideportivo', fecha:'2025-07-20', estado:'finished', equipos:24, descripcion:'Torneo clausurado con final presencial.'}
    ];

    const STORAGE_KEY = 'upe_torneos_v1';
    function loadData(){
        const raw = localStorage.getItem(STORAGE_KEY);
        if(raw) return JSON.parse(raw);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(SAMPLE));
        return SAMPLE;
    }
    function saveData(data){ localStorage.setItem(STORAGE_KEY, JSON.stringify(data)); }

    let torneos = loadData();
    let deleteTarget = null;

    const listado = document.getElementById('listado-torneos');
    const empty = document.getElementById('empty');
    const search = document.getElementById('search');
    const filterStatus = document.getElementById('filter-status');
    const clearBtn = document.getElementById('clear-btn');

    function render(){
        const q = search.value.trim().toLowerCase();
        const filter = filterStatus.value;
        const items = torneos.filter(t=>{
        if(filter !== 'all' && t.estado !== filter) return false;
        if(!q) return true;
        return [t.nombre, t.juego, t.lugar, t.descripcion].join(' ').toLowerCase().includes(q);
        });

        listado.innerHTML = '';
        if(items.length === 0){ empty.classList.remove('d-none'); return; } else { empty.classList.add('d-none'); }

        items.forEach(t=>{
        const div = document.createElement('div');
        div.className = 'col-12 col-md-6 col-lg-4';
        div.innerHTML = `
            <div class="torneo-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                <div class="torneo-title">${t.nombre}</div>
                <div class="torneo-meta"><small>${t.juego} • ${t.lugar} • ${t.fecha}</small></div>
                </div>
                <div>
                <span class="badge ${badgeClass(t.estado)}">${estadoLabel(t.estado)}</span>
                </div>
            </div>
            <p class="mt-2 mb-1">${t.descripcion}</p>
            <div class="d-flex gap-2 justify-content-end">
                <button class="btn btn-sm btn-outline-primary action-btn" data-id="${t.id}" data-action="view">Ver detalles</button>
                <button class="btn btn-sm btn-outline-secondary action-btn" data-id="${t.id}" data-action="edit">Editar</button>
                <button class="btn btn-sm btn-outline-danger action-btn" data-id="${t.id}" data-action="delete">Eliminar</button>
            </div>
            </div>
        `;
        listado.appendChild(div);
        });
    }

    function badgeClass(e){
        if(e==='upcoming') return 'badge-upcoming';
        if(e==='active') return 'badge-active';
        if(e==='finished') return 'badge-finished';
        return 'badge-secondary';
    }
    function estadoLabel(e){
        if(e==='upcoming') return 'Próximo';
        if(e==='active') return 'Activo';
        if(e==='finished') return 'Finalizado';
        return e;
    }

    document.addEventListener('click', (ev)=>{
        const btn = ev.target.closest('.action-btn');
        if(!btn) return;
        const id = Number(btn.dataset.id);
        const action = btn.dataset.action;
        const torneo = torneos.find(x=>x.id===id);
        if(!torneo) return;

        if(action === 'view'){
        document.getElementById('detail-title').textContent = torneo.nombre;
        document.getElementById('detail-body').innerHTML = `
            <p><strong>Juego:</strong> ${torneo.juego}</p>
            <p><strong>Lugar:</strong> ${torneo.lugar}</p>
            <p><strong>Fecha:</strong> ${torneo.fecha}</p>
            <p><strong>Equipos:</strong> ${torneo.equipos}</p>
            <p>${torneo.descripcion}</p>
        `;
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
        }

        if(action === 'edit'){
        // placeholder: redirigir a editar o abrir formulario
        showToast('Función de editar (placeholder)');
        }

        if(action === 'delete'){
        deleteTarget = id;
        document.getElementById('confirm-text').textContent = '¿Eliminar este torneo? Esta acción no se puede deshacer.';
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
        }
    });

    document.getElementById('confirm-delete').addEventListener('click', ()=>{
        if(deleteTarget === null) return;
        torneos = torneos.filter(t=>t.id !== deleteTarget);
        saveData(torneos);
        render();
        const modalEl = document.getElementById('confirmDeleteModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        deleteTarget = null;
        showToast('Torneo eliminado');
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
