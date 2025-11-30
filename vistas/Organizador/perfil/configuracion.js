// Lógica para cambiar contraseña vía fetch y mostrar feedback en la misma página
(function(){
  const form = document.getElementById('changePasswordForm');
  const alertEl = document.getElementById('changePwdAlert');
  const btn = document.getElementById('changePasswordBtn');

  if (!form) return;

  function showAlert(type, msg){
    alertEl.className = 'inline-alert ' + (type === 'success' ? 'success' : 'error');
    alertEl.textContent = msg;
    alertEl.style.display = 'block';
    setTimeout(()=>{ alertEl.style.opacity = '1'; }, 10);
    // desaparecer tras 5s
    setTimeout(()=>{ alertEl.style.display = 'none'; }, 5000);
  }

  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const current = document.getElementById('pwdCurrent').value.trim();
    const nw = document.getElementById('pwdNew').value.trim();
    const conf = document.getElementById('pwdConfirm').value.trim();
    if (!current || !nw || !conf) { showAlert('error','Completa todos los campos'); return; }
    if (nw !== conf) { showAlert('error','La nueva contraseña y la confirmación no coinciden'); return; }

    btn.disabled = true; btn.textContent = 'Cambiando...';
    try{
      const fd = new FormData();
      fd.append('current', current);
      fd.append('new', nw);
      fd.append('confirm', conf);
      const res = await fetch('/Proyecto-programaci-n-web/Backend/organizador/change_password.php', { method: 'POST', body: fd, credentials: 'include' });
      if (!res.ok) {
        showAlert('error','Error de red: ' + res.statusText);
      } else {
        // intentar parsear JSON
        try{
          const j = await res.json();
          if (j.status === 'ok') { showAlert('success', j.msg || 'Contraseña cambiada correctamente'); form.reset(); }
          else { showAlert('error', j.msg || 'No se pudo cambiar la contraseña'); }
        }catch(_){
          // si no es JSON, asumimos éxito (backend puede redirigir)
          showAlert('success','Contraseña cambiada correctamente'); form.reset();
        }
      }
    }catch(err){ console.error(err); showAlert('error','Error al cambiar la contraseña'); }
    finally{ btn.disabled = false; btn.textContent = 'Cambiar contraseña'; }
  });
})();
