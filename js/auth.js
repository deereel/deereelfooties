export function initAuth() {
  const loginForm = document.getElementById('modalLoginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', e => {
      e.preventDefault();
      const email = document.getElementById('modalLoginEmail').value.trim();
      const password = document.getElementById('modalLoginPassword').value.trim();

      fetch('/auth/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          localStorage.setItem('DRFUser', JSON.stringify(data.user));
          alert('Login successful!');
          window.location.href = '/dashboard.php';
        } else {
          alert(data.error);
        }
      });
    });
  }

  const registerForm = document.getElementById('modalRegisterForm');
  if (registerForm) {
    registerForm.addEventListener('submit', e => {
      e.preventDefault();
      const name = document.getElementById('modalRegisterName').value.trim();
      const email = document.getElementById('modalRegisterEmail').value.trim();
      const password = document.getElementById('modalRegisterPassword').value;
      const confirm = document.getElementById('modalRegisterConfirmPassword').value;

      if (password !== confirm) {
        alert('Passwords do not match.');
        return;
      }

      fetch('/auth/signup.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&confirm_password=${encodeURIComponent(confirm)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('🎉 Account created! You can now sign in.');
          registerForm.classList.add('d-none');
          loginForm.classList.remove('d-none');
        } else {
          alert(data.error || 'Signup failed.');
        }
      });
    });
  }

  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', e => {
      e.preventDefault();
      localStorage.removeItem('DRFUser');
      alert('Logged out!');
      location.reload();
    });
  }
}
