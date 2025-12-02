
    /*(function(){
      const btn = document.getElementById('btnNoti');
      const countEl = document.getElementById('notiCount');
      const dropdown = document.getElementById('notiDropdown');
      const listEl = document.getElementById('notiList');
      const emptyEl = document.getElementById('notiEmpty');

      async function fetchCount(){
        try{
          const res = await fetch('/Proyecto-programaci-n-web/vistas/Organizador/notificaciones/count.php', {credentials:'include'});
          if (!res.ok) return;
          const j = await res.json();
          const c = j.count || 0;
          if (c>0) { countEl.style.display='inline-block'; countEl.textContent = c; }
          else { countEl.style.display='none'; }
        }catch(e){ console.error('noti count', e); }
      }

      async function fetchList(){
        try{
          const res = await fetch('/Proyecto-programaci-n-web/vistas/Organizador/notificaciones/list.php', {credentials:'include'});
          if (!res.ok) return;
          const j = await res.json();
          renderList(j.items || []);
        }catch(e){ console.error('noti list', e); }
      }

      function renderList(items){
        listEl.innerHTML = '';
        if (!items.length) { emptyEl.style.display='block'; return; }
        emptyEl.style.display='none';
        items.forEach(it => {
          const div = document.createElement('div');
          div.style.borderBottom='1px solid #f0f0f0';
          div.style.padding='8px 0';
          div.innerHTML = `
            <div style="font-weight:600">${escapeHtml(it.usuario_nombre || it.email)}</div>
            <div style="font-size:13px;color:#444">Solicita unirse a: <strong>${escapeHtml(it.torneo_nombre)}</strong></div>
            <div style="margin-top:6px; text-align:right">
              <button data-id="${it.id_solicitud_torneo}" class="noti-aceptar btn btn-sm btn-success">Aceptar</button>
              <button data-id="${it.id_solicitud_torneo}" class="noti-rechazar btn btn-sm btn-secondary">Rechazar</button>
            </div>
          `;
          listEl.appendChild(div);
        });
        // attach events
        listEl.querySelectorAll('.noti-aceptar').forEach(b=>b.addEventListener('click', onAceptar));
        listEl.querySelectorAll('.noti-rechazar').forEach(b=>b.addEventListener('click', onRechazar));
      }

      function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

      async function onAceptar(e){
        const id = e.currentTarget.getAttribute('data-id');
        if(!confirm('Aceptar solicitud?')) return;
        try{
          const fd=new FormData(); fd.append('id_solicitud', id);
          const res = await fetch('/Proyecto-programaci-n-web/vistas/Organizador/notificaciones/aceptar.php',{method:'POST', body:fd, credentials:'include'});
          const j = await res.json();
          if (j.status==='ok') { fetchCount(); fetchList(); alert('Solicitud aceptada'); }
          else alert('Error: '+(j.msg||'')); 
        }catch(err){ console.error(err); alert('Error al aceptar'); }
      }

      async function onRechazar(e){
        const id = e.currentTarget.getAttribute('data-id');
        if(!confirm('Rechazar solicitud?')) return;
        try{
          const fd=new FormData(); fd.append('id_solicitud', id);
          const res = await fetch('/Proyecto-programaci-n-web/vistas/Organizador/notificaciones/rechazar.php',{method:'POST', body:fd, credentials:'include'});
          const j = await res.json();
          if (j.status==='ok') { fetchCount(); fetchList(); alert('Solicitud rechazada'); }
          else alert('Error: '+(j.msg||'')); }
        catch(err){ console.error(err); alert('Error al rechazar'); }
      }

      btn.addEventListener('click', async () => {
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
          dropdown.style.display = 'block';
          await fetchList();
        } else {
          dropdown.style.display = 'none';
        }
      });

      // cerrar al click fuera
      document.addEventListener('click', (ev)=>{
        if (!btn.contains(ev.target) && !dropdown.contains(ev.target)) dropdown.style.display='none';
      });

      // refrescar contador cada 20s
      fetchCount(); setInterval(fetchCount, 20000);
    })();*/