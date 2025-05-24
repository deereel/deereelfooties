// modal.js

const initModalLoginSignup = () => {
  const modal = document.getElementById('modallogin');
  if (!modal) return;

  const loginBtn = document.getElementById('openLoginBtn');
  const switchToSignup = document.getElementById('switchToSignup');
  const switchToLogin = document.getElementById('switchToLogin');
  const closeModal = modal.querySelector('.modal-close');

  if (loginBtn) {
    loginBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      modal.querySelector('#login-form').classList.remove('d-none');
      modal.querySelector('#signup-form').classList.add('d-none');
    });
  }

  if (switchToSignup) {
    switchToSignup.addEventListener('click', () => {
      modal.querySelector('#login-form').classList.add('d-none');
      modal.querySelector('#signup-form').classList.remove('d-none');
    });
  }

  if (switchToLogin) {
    switchToLogin.addEventListener('click', () => {
      modal.querySelector('#signup-form').classList.add('d-none');
      modal.querySelector('#login-form').classList.remove('d-none');
    });
  }

  if (closeModal) {
    closeModal.addEventListener('click', () => {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    });
  }

  modal.addEventListener('click', e => {
    if (e.target === modal) {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }
  });
};

export { initModalLoginSignup };
