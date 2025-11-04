document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('adminLoginForm');
  const inputUser = document.getElementById('adminUser');
  const inputPass = document.getElementById('adminPass');
  const showPass = document.getElementById('showAdminPass');
  const submitBtn = form.querySelector('button[type="submit"]');

  const MOCK_ADMIN = { user: 'admin', password: 'Admin1234', role: 'admin' };

  
  function setMessage(text, type = 'info') {
    let existing = document.getElementById('adminLoginMsg');
    if (!existing) {
      existing = document.createElement('div');
      existing.id = 'adminLoginMsg';
      existing.className = 'mt-3';
      form.parentNode.insertBefore(existing, form.nextSibling);
    }
    existing.textContent = text;
    existing.className = type === 'error' ? 'mt-3 text-danger' : 'mt-3 text-success';
  }

  function clearMessage() {
    const existing = document.getElementById('adminLoginMsg');
    if (existing) existing.remove();
  }

  function isLocked() {
    const lockUntil = parseInt(localStorage.getItem('adminLockUntil') || '0', 10);
    return lockUntil && Date.now() < lockUntil;
  }

  function incrementAttempts() {
    const key = 'adminLoginAttempts';
    let attempts = parseInt(localStorage.getItem(key) || '0', 10) + 1;
    localStorage.setItem(key, String(attempts));
    if (attempts >= 5) {
    
      localStorage.setItem('adminLockUntil', String(Date.now() + 5 * 60 * 1000));
    }
  }

  function resetAttempts() {
    localStorage.removeItem('adminLoginAttempts');
    localStorage.removeItem('adminLockUntil');
  }

 
  if (!form || !inputUser || !inputPass) return;
  if (isLocked()) {
    setMessage('Cuenta temporalmente bloqueada. Intentá más tarde.', 'error');
    submitBtn.disabled = true;
   
    const unlockAt = parseInt(localStorage.getItem('adminLockUntil') || '0', 10);
    setTimeout(() => location.reload(), Math.max(1000, unlockAt - Date.now()));
    return;
  }

  try {
    const remembered = JSON.parse(localStorage.getItem('adminRemember') || 'null');
    if (remembered && remembered.user) inputUser.value = remembered.user;
  } catch (e) {  }

  
  showPass?.addEventListener('change', () => {
    inputPass.type = showPass.checked ? 'text' : 'password';
  });

  
  [inputUser, inputPass].forEach(el => el.addEventListener('input', () => {
    clearMessage();
    el.classList.remove('is-invalid');
  }));

  
  function validar() {
    let ok = true;
    if (!inputUser.value.trim()) {
      inputUser.classList.add('is-invalid');
      ok = false;
    }
    if (!inputPass.value || inputPass.value.length < 6) {
      inputPass.classList.add('is-invalid');
      ok = false;
    }
    return ok;
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    clearMessage();
    if (isLocked()) {
      setMessage('Cuenta temporalmente bloqueada. Intentá más tarde.', 'error');
      submitBtn.disabled = true;
      return;
    }
    if (!validar()) {
      setMessage('Revisá los campos resaltados', 'error');
      return;
    }

    const user = inputUser.value.trim();
    const pass = inputPass.value;

    const authSuccess = (user.toLowerCase() === MOCK_ADMIN.user && pass === MOCK_ADMIN.password);

    if (authSuccess) {
      const session = { usuario: user, role: MOCK_ADMIN.role, loggedAt: new Date().toISOString() };
      sessionStorage.setItem('session', JSON.stringify(session));
      if (document.getElementById('rememberAdmin')?.checked) {
        localStorage.setItem('adminRemember', JSON.stringify({ user }));
      } else {
        localStorage.removeItem('adminRemember');
      }
      resetAttempts();
      setMessage('Inicio de sesión correcto. Redirigiendo...', 'success');
      setTimeout(() => { window.location.href = '../Administrador/dashboard.html'; }, 700);
      return;
    }

    incrementAttempts();
    const attempts = parseInt(localStorage.getItem('adminLoginAttempts') || '0', 10);
    if (isLocked()) {
      setMessage('Demasiados intentos. Cuenta bloqueada por 5 minutos.', 'error');
      submitBtn.disabled = true;
      const unlockAt = parseInt(localStorage.getItem('adminLockUntil') || '0', 10);
      setTimeout(() => location.reload(), Math.max(1000, unlockAt - Date.now()));
    } else {
      setMessage(`Credenciales inválidas. Intentos: ${attempts}`, 'error');
    }
  });
});
